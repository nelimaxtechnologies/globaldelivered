<?php
/**
 * Global Delivered Logistics - Admin Document Controller
 * Full file upload handling with drag-and-drop, MIME validation, path traversal protection.
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class DocumentController extends Controller
{
    private array $allowedMimes = [
        'application/pdf',
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv',
        'text/plain',
    ];

    private array $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt'];
    private int $maxFileSize = 10485760; // 10MB
    private string $uploadDir = '';

    public function __construct()
    {
        parent::__construct();
        $this->uploadDir = defined('UPLOADS_PATH') ? UPLOADS_PATH : (STORAGE_PATH . '/uploads');
    }

    /**
     * List all documents
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $type = sanitize($_GET['type'] ?? '');
        $shipmentId = (int) ($_GET['shipment_id'] ?? 0);
        $search = sanitize($_GET['search'] ?? '');
        $perPage = min(100, max(10, (int) ($_GET['per_page'] ?? 25)));

        $where = "WHERE 1=1";
        $params = [];

        if (!empty($type)) { $where .= " AND document_type = ?"; $params[] = $type; }
        if ($shipmentId > 0) { $where .= " AND shipment_id = ?"; $params[] = $shipmentId; }
        if (!empty($search)) {
            $where .= " AND (original_name LIKE ? OR notes LIKE ? OR document_type LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s]);
        }

        // Stats
        $stats = $this->db->fetch(
            "SELECT
                COUNT(*) as total,
                COALESCE(SUM(file_size), 0) as total_size,
                SUM(CASE WHEN document_type = 'invoice' THEN 1 ELSE 0 END) as invoices,
                SUM(CASE WHEN document_type = 'label' THEN 1 ELSE 0 END) as labels,
                SUM(CASE WHEN document_type = 'proof_of_delivery' THEN 1 ELSE 0 END) as pod,
                SUM(CASE WHEN document_type = 'photo' THEN 1 ELSE 0 END) as photos,
                SUM(CASE WHEN document_type = 'customs' THEN 1 ELSE 0 END) as customs,
                SUM(CASE WHEN document_type = 'receipt' THEN 1 ELSE 0 END) as receipts,
                SUM(CASE WHEN document_type = 'contract' THEN 1 ELSE 0 END) as contracts,
                SUM(CASE WHEN document_type = 'other' THEN 1 ELSE 0 END) as other_docs
             FROM documents"
        );

        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM documents {$where}",
            "SELECT d.*, s.tracking_number, CONCAT(u.first_name, ' ', u.last_name) as uploaded_by_name
             FROM documents d
             LEFT JOIN shipments s ON d.shipment_id = s.id
             LEFT JOIN users u ON d.uploaded_by = u.id
             {$where} ORDER BY d.created_at DESC",
            $params, $page, $perPage
        );

        $docTypes = $this->db->fetchAll("SELECT DISTINCT document_type FROM documents ORDER BY document_type");

        $this->adminView('documents/index', [
            'pageTitle' => 'Documents Management',
            'documents' => $paginated->data,
            'pagination' => $paginated,
            'stats' => $stats,
            'docTypes' => $docTypes,
            'filters' => ['type' => $type, 'shipment_id' => $shipmentId, 'search' => $search],
        ]);
    }

    /**
     * Show upload form
     */
    public function create(): void
    {
        $shipmentId = (int) ($_GET['shipment_id'] ?? 0);
        $shipment = null;

        if ($shipmentId) {
            $shipment = $this->db->fetch("SELECT id, tracking_number FROM shipments WHERE id = ? AND deleted_at IS NULL", [$shipmentId]);
        }

        $customerId = (int) ($_GET['customer_id'] ?? 0);
        $customer = null;
        if ($customerId) {
            $customer = $this->db->fetch("SELECT id, first_name, last_name FROM customers WHERE id = ? AND deleted_at IS NULL", [$customerId]);
        }

        $shipments = $this->db->fetchAll("SELECT id, tracking_number, CONCAT(recipient_name, ' (', tracking_number, ')') as label FROM shipments WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 50");

        // Recent uploads for sidebar
        $recentDocs = $this->db->fetchAll(
            "SELECT d.id, d.original_name, d.document_type, d.file_size, d.created_at,
                    CONCAT(u.first_name, ' ', u.last_name) as uploaded_by_name
             FROM documents d
             LEFT JOIN users u ON d.uploaded_by = u.id
             ORDER BY d.created_at DESC LIMIT 5"
        );

        $this->adminView('documents/create', [
            'pageTitle' => 'Upload Document',
            'shipment' => $shipment,
            'customer' => $customer,
            'shipments' => $shipments,
            'recentDocs' => $recentDocs,
        ]);
    }

    /**
     * Handle file upload with full security validation
     */
    public function store(): void
    {
        $data = $this->getPostData();
        $shipmentId = !empty($data['shipment_id']) ? (int) $data['shipment_id'] : null;
        $customerId = !empty($data['customer_id']) ? (int) $data['customer_id'] : null;
        $documentType = sanitize($data['document_type'] ?? 'other');
        $notes = sanitize($data['notes'] ?? '');
        
        // Check if file was uploaded
        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $errorCode = $_FILES['file']['error'] ?? -1;
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds server upload limit.',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds form upload limit.',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
                UPLOAD_ERR_NO_FILE => 'No file was selected.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server upload directory is missing.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            ];
            $message = $errorMessages[$errorCode] ?? 'File upload failed.';
            
            if ($this->isAjax()) {
                $this->error($message);
            }
            flash('error', $message);
            $this->back();
        }

        $file = $_FILES['file'];
        
        try {
            // --- SECURITY: Validate file extension (prevent double extension attacks) ---
            $originalName = basename($file['name']);
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            
            if (!in_array($extension, $this->allowedExtensions)) {
                throw new \Exception("File type '.{$extension}' is not allowed. Allowed: " . implode(', ', $this->allowedExtensions));
            }
            
            // --- SECURITY: Validate MIME type ---
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $this->allowedMimes)) {
                throw new \Exception("File MIME type '{$mimeType}' is not allowed.");
            }
            
            // --- SECURITY: Validate file size ---
            if ($file['size'] > $this->maxFileSize) {
                $maxSize = $this->maxFileSize / 1048576;
                throw new \Exception("File size exceeds the maximum allowed size of {$maxSize}MB.");
            }
            
            // --- SECURITY: Prevent path traversal ---
            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $safeName = uniqid() . '_' . $safeName;
            
            // Ensure upload directory exists
            $typeDir = $this->uploadDir . '/' . $documentType;
            if (!is_dir($typeDir)) {
                mkdir($typeDir, 0755, true);
            }
            
            // Create date-based subdirectory for organization
            $dateDir = date('Y/m');
            $fullDir = $typeDir . '/' . $dateDir;
            if (!is_dir($fullDir)) {
                mkdir($fullDir, 0755, true);
            }
            
            // Final safe path - NO user input in path beyond the safe name
            $relativePath = $documentType . '/' . $dateDir . '/' . $safeName;
            $destPath = $this->uploadDir . '/' . $relativePath;
            
            // --- SECURITY: Verify destination is within upload directory ---
            $realDest = realpath(dirname($destPath));
            $realUpload = realpath($this->uploadDir);
            if ($realDest === false || strpos($realDest, $realUpload) !== 0) {
                throw new \Exception('Invalid file path detected.');
            }
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                throw new \Exception('Failed to save uploaded file.');
            }
            
            // Set proper file permissions
            chmod($destPath, 0644);
            
            // --- Store in database ---
            $this->db->query(
                "INSERT INTO documents (shipment_id, customer_id, document_type, name, original_name, 
                 file_path, file_size, mime_type, notes, uploaded_by, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    $shipmentId,
                    $customerId,
                    $documentType,
                    $safeName,
                    $originalName,
                    $relativePath,
                    $file['size'],
                    $mimeType,
                    $notes,
                    $_SESSION['user_id'],
                ]
            );
            
            $docId = $this->db->lastInsertId();
            log_activity('document_uploaded', 'document', $docId, null, [
                'name' => $originalName,
                'type' => $documentType,
                'size' => $file['size'],
            ]);
            
            if ($this->isAjax()) {
                $this->success([
                    'id' => (int) $docId,
                    'name' => $originalName,
                    'path' => $relativePath,
                    'size' => $file['size'],
                    'type' => $documentType,
                ], 'File uploaded successfully!');
            }
            
            flash('success', 'Document uploaded successfully!');
            
            if ($shipmentId) {
                $this->redirect("/admin/shipments/{$shipmentId}");
            }
            $this->redirect('/admin/documents');
            
        } catch (\Exception $e) {
            // Clean up temp file if it exists
            if (file_exists($file['tmp_name'])) {
                @unlink($file['tmp_name']);
            }
            
            error_log("Document upload error: " . $e->getMessage());
            
            if ($this->isAjax()) {
                $this->error($e->getMessage());
            }
            
            flash('error', $e->getMessage());
            $this->back();
        }
    }

    /**
     * Show document details
     */
    public function show(int $id): void
    {
        $doc = $this->db->fetch(
            "SELECT d.*, s.tracking_number, CONCAT(u.first_name, ' ', u.last_name) as uploaded_by_name
             FROM documents d
             LEFT JOIN shipments s ON d.shipment_id = s.id
             LEFT JOIN users u ON d.uploaded_by = u.id
             WHERE d.id = ?",
            [$id]
        );
        
        if (!$doc) {
            flash('error', 'Document not found.');
            $this->redirect('/admin/documents');
        }
        
        $this->adminView('documents/show', [
            'pageTitle' => "Document: {$doc->name}",
            'doc' => $doc,
        ]);
    }

    /**
     * Download document with path traversal protection
     */
    public function download(int $id): void
    {
        $doc = $this->db->fetch("SELECT * FROM documents WHERE id = ?", [$id]);
        
        if (!$doc) {
            flash('error', 'Document not found.');
            $this->redirect('/admin/documents');
        }
        
        $filePath = $this->uploadDir . '/' . $doc->file_path;
        
        // SECURITY: Verify the file is within the upload directory
        $realFile = realpath($filePath);
        $realUpload = realpath($this->uploadDir);
        
        if ($realFile === false || strpos($realFile, $realUpload) !== 0) {
            flash('error', 'Invalid file path.');
            $this->redirect('/admin/documents');
        }
        
        if (!file_exists($realFile)) {
            flash('error', 'File not found on disk.');
            $this->redirect('/admin/documents');
        }
        
        // Serve the file
        header('Content-Type: ' . $doc->mime_type);
        header('Content-Disposition: attachment; filename="' . $doc->original_name . '"');
        header('Content-Length: ' . filesize($realFile));
        header('Cache-Control: private, max-age=0');
        readfile($realFile);
        exit;
    }

    /**
     * Delete document (file + database)
     */
    public function destroy(int $id): void
    {
        $doc = $this->db->fetch("SELECT * FROM documents WHERE id = ?", [$id]);
        
        if (!$doc) {
            flash('error', 'Document not found.');
            $this->redirect('/admin/documents');
        }
        
        // Delete physical file (with path traversal protection)
        $filePath = $this->uploadDir . '/' . $doc->file_path;
        $realFile = realpath($filePath);
        $realUpload = realpath($this->uploadDir);
        
        if ($realFile !== false && strpos($realFile, $realUpload) === 0 && file_exists($realFile)) {
            @unlink($realFile);
        }
        
        // Delete from database
        $this->db->query("DELETE FROM documents WHERE id = ?", [$id]);
        
        log_activity('document_deleted', 'document', $id);
        flash('success', 'Document deleted.');
        $this->redirect('/admin/documents');
    }
}
