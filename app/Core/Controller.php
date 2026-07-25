<?php
/**
 * Global Delivered Logistics - Base Controller
 * 
 * Provides common functionality for all controllers including
 * view rendering, JSON responses, and request validation.
 */

namespace App\Core;

abstract class Controller
{
    protected array $data = [];
    protected array $middleware = [];
    protected ?Database $db = null;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->init();
    }

    /**
     * Initialize controller - override in child classes
     */
    protected function init(): void {}

    /**
     * Render a view with layout support
     */
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        $data = array_merge($this->data, $data);
        $data['content'] = $this->renderView($view, $data);
        
        $layoutPath = VIEWS_PATH . '/layouts/' . $layout . '.php';
        if (file_exists($layoutPath)) {
            extract($data);
            require $layoutPath;
        } else {
            echo $data['content'];
        }
    }

    /**
     * Render a raw view without layout
     */
    protected function renderView(string $view, array $data = []): string
    {
        $viewPath = VIEWS_PATH . '/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }
        
        extract($data);
        ob_start();
        require $viewPath;
        return ob_get_clean();
    }

    /**
     * Render admin view
     */
    protected function adminView(string $view, array $data = []): void
    {
        $data = array_merge($this->data, $data);
        $data['content'] = $this->renderView('admin/' . $view, $data);
        
        $layoutPath = VIEWS_PATH . '/admin/layouts/master.php';
        if (file_exists($layoutPath)) {
            extract($data);
            require $layoutPath;
        } else {
            echo $data['content'];
        }
    }

    /**
     * Return JSON response
     */
    protected function json($data, int $statusCode = 200): void
    {
        json_response($data, $statusCode);
    }

    /**
     * Return success JSON
     */
    protected function success($data = null, string $message = 'Success', int $code = 200): void
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];
        
        // Include fresh CSRF token for AJAX requests to stay in sync
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!empty($_SESSION['_csrf_token'])) {
            $response['_csrf_token'] = $_SESSION['_csrf_token'];
        }
        
        $this->json($response, $code);
    }

    /**
     * Return error JSON
     */
    protected function error(string $message = 'Error', int $code = 400, $errors = null): void
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        $this->json($response, $code);
    }

    /**
     * Validate request data
     */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        $validated = [];

        foreach ($rules as $field => $ruleString) {
            $ruleset = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($ruleset as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }

                switch ($rule) {
                    case 'required':
                        if (empty($value) && $value !== '0' && $value !== 0) {
                            $errors[$field][] = "{$field} is required";
                        }
                        break;
                    
                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = "{$field} must be a valid email";
                        }
                        break;
                    
                    case 'numeric':
                        if (!empty($value) && !is_numeric($value)) {
                            $errors[$field][] = "{$field} must be numeric";
                        }
                        break;
                    
                    case 'integer':
                        if (!empty($value) && !ctype_digit((string) $value)) {
                            $errors[$field][] = "{$field} must be an integer";
                        }
                        break;
                    
                    case 'min':
                        $min = (float) ($params[0] ?? 0);
                        if (!empty($value) && (float) $value < $min) {
                            $errors[$field][] = "{$field} must be at least {$min}";
                        }
                        break;
                    
                    case 'max':
                        $max = (float) ($params[0] ?? 0);
                        if (!empty($value) && (float) $value > $max) {
                            $errors[$field][] = "{$field} must not exceed {$max}";
                        }
                        break;
                    
                    case 'min_length':
                        $min = (int) ($params[0] ?? 0);
                        if (!empty($value) && strlen((string) $value) < $min) {
                            $errors[$field][] = "{$field} must be at least {$min} characters";
                        }
                        break;
                    
                    case 'max_length':
                        $max = (int) ($params[0] ?? 0);
                        if (!empty($value) && strlen((string) $value) > $max) {
                            $errors[$field][] = "{$field} must not exceed {$max} characters";
                        }
                        break;
                    
                    case 'in':
                        if (!empty($value) && !in_array($value, $params)) {
                            $errors[$field][] = "{$field} must be one of: " . implode(', ', $params);
                        }
                        break;
                }
            }

            if (!isset($errors[$field])) {
                $validated[$field] = $value;
            }
        }

        if (!empty($errors)) {
            $this->error('Validation failed', 422, $errors);
        }

        return $validated;
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjax(): bool
    {
        return is_ajax();
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): void
    {
        redirect($url);
    }

    /**
     * Redirect back
     */
    protected function back(): void
    {
        back();
    }

    /**
     * Get POST data
     */
    protected function getPostData(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (str_contains($contentType, 'application/json')) {
            $input = file_get_contents('php://input');
            return json_decode($input, true) ?? [];
        }
        
        return $_POST;
    }

    /**
     * Get current user
     */
    protected function getUser(): ?object
    {
        if (isset($_SESSION['user_id'])) {
            return $this->db->fetch("SELECT * FROM users WHERE id = ? AND is_active = 1", [$_SESSION['user_id']]);
        }
        return null;
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf(): bool
    {
        $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        
        if (empty($_SESSION['_csrf_token']) || !hash_equals($_SESSION['_csrf_token'], $token)) {
            $this->error('Invalid CSRF token', 419);
            return false;
        }
        return true;
    }
}
