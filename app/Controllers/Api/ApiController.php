<?php
/**
 * Global Delivered Logistics - API Controller
 * Provides JSON endpoints for frontend AJAX calls.
 */

namespace App\Controllers\Api;

use App\Core\Controller;

class ApiController extends Controller
{
    /**
     * Get all countries (JSON)
     * GET /admin/api/countries
     */
    public function getCountries(): void
    {
        header('Content-Type: application/json');

        $countries = $this->db->fetchAll(
            "SELECT id, name, code, phone_code, currency FROM countries WHERE is_active = 1 ORDER BY name ASC"
        );

        echo json_encode(['success' => true, 'data' => $countries]);
        exit;
    }

    /**
     * Get cities for a country (JSON)
     * GET /api/cities?country=Kenya
     */
    public function getCities(): void
    {
        header('Content-Type: application/json');

        $country = sanitize($_GET['country'] ?? '');

        if (empty($country)) {
            echo json_encode(['success' => false, 'cities' => []]);
            exit;
        }

        // Major cities per country (curated list)
        $cities = $this->getCityData();
        $result = $cities[strtoupper($country)] ?? [];

        // Also check by common name variations
        if (empty($result)) {
            foreach ($cities as $key => $val) {
                if (stripos($key, $country) !== false || stripos($country, $key) !== false) {
                    $result = $val;
                    break;
                }
            }
        }

        echo json_encode(['success' => true, 'cities' => $result]);
        exit;
    }

    /**
     * Geocode a zip/postal code (JSON)
     * GET /api/geocode?postal_code=00100&country=Kenya
     */
    public function geocode(): void
    {
        header('Content-Type: application/json');

        $postalCode = sanitize($_GET['postal_code'] ?? '');
        $country = sanitize($_GET['country'] ?? '');

        if (empty($postalCode)) {
            echo json_encode(['success' => false, 'message' => 'Postal code required']);
            exit;
        }

        // Use Nominatim (OpenStreetMap) free geocoding
        $query = http_build_query([
            'postalcode' => $postalCode,
            'country' => $country,
            'format' => 'json',
            'addressdetails' => 1,
            'limit' => 1,
        ]);

        $url = "https://nominatim.openstreetmap.org/search?{$query}";

        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
                'header' => "User-Agent: GlobalDeliveredLogistics/1.0\r\n",
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            echo json_encode(['success' => false, 'message' => 'Geocoding service unavailable']);
            exit;
        }

        $data = json_decode($response, true);

        if (empty($data)) {
            echo json_encode(['success' => false, 'message' => 'No results found']);
            exit;
        }

        $result = $data[0];
        $address = $result['address'] ?? [];

        echo json_encode([
            'success' => true,
            'data' => [
                'city' => $address['city'] ?? $address['town'] ?? $address['village'] ?? $address['county'] ?? '',
                'state' => $address['state'] ?? $address['region'] ?? '',
                'country' => $address['country'] ?? '',
                'latitude' => $result['lat'] ?? '',
                'longitude' => $result['lon'] ?? '',
                'display_name' => $result['display_name'] ?? '',
            ],
        ]);
        exit;
    }

    /**
     * Curated city data by country
     */
    private function getCityData(): array
    {
        return [
            'KENYA' => [
                'Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Eldoret', 'Thika', 'Malindi',
                'Kitale', 'Garissa', 'Kakamega', 'Machakos', 'Meru', 'Nyeri', 'Lamu',
                'Naivasha', 'Naro Moru', 'Voi', 'Embu', 'Kericho', 'Bungoma',
            ],
            'UNITED STATES' => [
                'New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia',
                'San Antonio', 'San Diego', 'Dallas', 'San Jose', 'Austin', 'Jacksonville',
                'Fort Worth', 'Columbus', 'Charlotte', 'Indianapolis', 'San Francisco',
                'Seattle', 'Denver', 'Washington DC', 'Nashville', 'Oklahoma City', 'El Paso',
                'Boston', 'Portland', 'Las Vegas', 'Memphis', 'Louisville', 'Baltimore',
                'Miami', 'Atlanta', 'Minneapolis', 'New Orleans', 'Detroit', 'Honolulu',
            ],
            'UNITED KINGDOM' => [
                'London', 'Manchester', 'Birmingham', 'Leeds', 'Glasgow', 'Edinburgh',
                'Liverpool', 'Bristol', 'Sheffield', 'Nottingham', 'Leicester', 'Coventry',
                'Bradford', 'Cardiff', 'Belfast', 'Newcastle', 'Southampton', 'Brighton',
            ],
            'UNITED ARAB EMIRATES' => [
                'Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah',
                'Umm Al Quwain', 'Khor Fakkan',
            ],
            'SOUTH AFRICA' => [
                'Johannesburg', 'Cape Town', 'Durban', 'Pretoria', 'Port Elizabeth',
                'Bloemfontein', 'Kimberley', 'Polokwane', 'Nelspruit', 'Richards Bay',
            ],
            'TANZANIA' => [
                'Dar es Salaam', 'Dodoma', 'Arusha', 'Mwanza', 'Zanzibar City', 'Tanga',
                'Tabora', 'Kigoma', 'Moshi', 'Morogoro',
            ],
            'UGANDA' => [
                'Kampala', 'Entebbe', 'Jinja', 'Mbale', 'Mbarara', 'Gulu', 'Lira',
                'Fort Portal', 'Soroti', 'Masaka',
            ],
            'NIGERIA' => [
                'Lagos', 'Abuja', 'Kano', 'Ibadan', 'Port Harcourt', 'Benin City',
                'Kaduna', 'Enugu', 'Abeokuta', 'Jos',
            ],
            'GHANA' => [
                'Accra', 'Kumasi', 'Tamale', 'Takoradi', 'Cape Coast', 'Tema',
                'Ho', 'Sunyani', 'Bolgatanga',
            ],
            'INDIA' => [
                'Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata', 'Hyderabad',
                'Pune', 'Ahmedabad', 'Jaipur', 'Lucknow', 'Kochi', 'Goa',
            ],
            'CHINA' => [
                'Shanghai', 'Beijing', 'Guangzhou', 'Shenzhen', 'Chengdu', 'Hangzhou',
                'Wuhan', 'Xi\'an', 'Chongqing', 'Suzhou', 'Tianjin', 'Nanjing',
            ],
            'JAPAN' => [
                'Tokyo', 'Osaka', 'Yokohama', 'Nagoya', 'Sapporo', 'Fukuoka',
                'Kobe', 'Kyoto', 'Hiroshima', 'Sendai',
            ],
            'AUSTRALIA' => [
                'Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide', 'Gold Coast',
                'Canberra', 'Newcastle', 'Wollongong', 'Hobart',
            ],
            'FRANCE' => [
                'Paris', 'Marseille', 'Lyon', 'Toulouse', 'Nice', 'Nantes',
                'Strasbourg', 'Bordeaux', 'Lille', 'Montpellier',
            ],
            'GERMANY' => [
                'Berlin', 'Hamburg', 'Munich', 'Cologne', 'Frankfurt', 'Stuttgart',
                'Düsseldorf', 'Leipzig', 'Dortmund', 'Essen',
            ],
            'BRAZIL' => [
                'Sao Paulo', 'Rio de Janeiro', 'Brasilia', 'Salvador', 'Fortaleza',
                'Belo Horizonte', 'Manaus', 'Curitiba', 'Recife', 'Porto Alegre',
            ],
            'CANADA' => [
                'Toronto', 'Montreal', 'Vancouver', 'Calgary', 'Edmonton', 'Ottawa',
                'Winnipeg', 'Quebec City', 'Hamilton', 'Victoria',
            ],
            'SINGAPORE' => [
                'Singapore',
            ],
            'NETHERLANDS' => [
                'Amsterdam', 'Rotterdam', 'The Hague', 'Utrecht', 'Eindhoven',
                'Tilburg', 'Groningen', 'Almere', 'Breda', 'Nijmegen',
            ],
            'ITALY' => [
                'Rome', 'Milan', 'Naples', 'Turin', 'Palermo', 'Genoa',
                'Bologna', 'Florence', 'Venice', 'Catania',
            ],
            'SPAIN' => [
                'Madrid', 'Barcelona', 'Valencia', 'Seville', 'Bilbao', 'Malaga',
                'Zaragoza', 'Las Palmas', 'Murcia', 'Palma',
            ],
            'SOUTH KOREA' => [
                'Seoul', 'Busan', 'Incheon', 'Daegu', 'Daejeon', 'Gwangju',
                'Suwon', 'Ulsan', 'Changwon', 'Seongnam',
            ],
            'THAILAND' => [
                'Bangkok', 'Chiang Mai', 'Pattaya', 'Phuket', 'Hat Yai',
                'Nonthaburi', 'Chiang Rai', 'Krabi', 'Ko Samui',
            ],
            'EGYPT' => [
                'Cairo', 'Alexandria', 'Giza', 'Sharm El Sheikh', 'Luxor',
                'Aswan', 'Port Said', 'Suez', 'Hurghada',
            ],
            'MOROCCO' => [
                'Casablanca', 'Rabat', 'Marrakech', 'Fez', 'Tangier', 'Agadir',
                'Meknes', 'Oujda', 'Kenitra',
            ],
            'ETHIOPIA' => [
                'Addis Ababa', 'Dire Dawa', 'Mekelle', 'Adama', 'Gondar',
                'Hawassa', 'Bahir Dar', 'Jimma',
            ],
            'RWANDA' => [
                'Kigali', 'Butare', 'Gitarama', 'Gisenyi', 'Byumba',
            ],
            'DRC' => [
                'Kinshasa', 'Lubumbashi', 'Mbuji-Mayi', 'Kisangani', 'Goma',
            ],
            'MOZAMBIQUE' => [
                'Maputo', 'Beira', 'Nampula', 'Quelimane', 'Tete',
            ],
        ];
    }

    /**
     * Evolution API Webhook Handler
     * Receives WhatsApp events from Evolution API.
     * POST /api/webhooks/evolution
     */
    public function evolutionWebhook(): void
    {
        header('Content-Type: application/json');

        try {
            $settings = $this->db->fetch("SELECT * FROM whatsapp_settings LIMIT 1");

            // Validate webhook secret if configured
            if (!empty($settings->webhook_secret)) {
                $secret = $_SERVER['HTTP_X_WEBHOOK_SECRET'] ?? $_GET['secret'] ?? '';
                if ($secret !== $settings->webhook_secret) {
                    http_response_code(403);
                    echo json_encode(['error' => 'Invalid webhook secret']);
                    exit;
                }
            }

            $payload = json_decode(file_get_contents('php://input'), true);

            if (!$payload) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid payload']);
                exit;
            }

            $event = $payload['event'] ?? '';
            $instance = $payload['instance'] ?? '';
            $data = $payload['data'] ?? [];

            error_log("Evolution webhook: event={$event} instance={$instance}");

            switch ($event) {
                case 'messages.upsert':
                    $this->handleIncomingMessage($instance, $data);
                    break;

                case 'messages.update':
                    $this->handleMessagesUpdate($instance, $data);
                    break;

                case 'connection.update':
                    $this->handleConnectionUpdate($instance, $data);
                    break;

                case 'qrcode.updated':
                    $this->handleQRUpdate($instance, $data);
                    break;

                default:
                    error_log("Evolution webhook: Unknown event {$event} from {$instance}");
            }

            echo json_encode(['success' => true, 'event' => $event]);

        } catch (\Exception $e) {
            error_log("Evolution webhook error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Internal error']);
        }
        exit;
    }

    private function handleIncomingMessage(string $instance, array $data): void
    {
        $message = $data['data'] ?? $data;
        $key = $message['key'] ?? [];
        $phone = str_replace('@s.whatsapp.net', '', $key['remoteJid'] ?? '');
        $fromMe = $key['fromMe'] ?? false;
        $messageId = $key['id'] ?? '';
        $pushName = $message['pushName'] ?? '';

        if (!$phone || $phone === 'status@broadcast') return;

        $messageType = 'text';
        $messageText = '';
        $mediaUrl = null;
        $mediaType = null;

        if (isset($message['message'])) {
            $msg = $message['message'];
            if (isset($msg['conversation'])) {
                $messageText = $msg['conversation'];
            } elseif (isset($msg['extendedTextMessage']['text'])) {
                $messageText = $msg['extendedTextMessage']['text'];
            } elseif (isset($msg['imageMessage'])) {
                $messageType = 'image';
                $messageText = $msg['imageMessage']['caption'] ?? '';
                $mediaUrl = $msg['imageMessage']['url'] ?? null;
                $mediaType = 'image';
            } elseif (isset($msg['videoMessage'])) {
                $messageType = 'video';
                $messageText = $msg['videoMessage']['caption'] ?? '';
                $mediaUrl = $msg['videoMessage']['url'] ?? null;
                $mediaType = 'video';
            } elseif (isset($msg['documentMessage'])) {
                $messageType = 'document';
                $messageText = $msg['documentMessage']['caption'] ?? '';
                $mediaUrl = $msg['documentMessage']['url'] ?? null;
                $mediaType = $msg['documentMessage']['mimetype'] ?? 'document';
            } elseif (isset($msg['audioMessage'])) {
                $messageType = 'audio';
                $mediaUrl = $msg['audioMessage']['url'] ?? null;
                $mediaType = 'audio';
            } elseif (isset($msg['locationMessage'])) {
                $messageType = 'location';
                $messageText = $msg['locationMessage']['name'] ?? '';
            } elseif (isset($msg['contactMessage'])) {
                $messageType = 'contact';
                $messageText = $msg['contactMessage']['displayName'] ?? '';
            }
        }

        $contactName = $pushName ?: $phone;

        // Save/update contact
        $this->db->query(
            "INSERT INTO whatsapp_contacts (phone, name, instance_name, last_seen, created_at)
             VALUES (?, ?, ?, NOW(), NOW())
             ON DUPLICATE KEY UPDATE name = VALUES(name), last_seen = NOW()",
            [$phone, $contactName, $instance]
        );

        // Save message
        $this->db->query(
            "INSERT INTO whatsapp_messages (instance_name, phone, contact_name, direction, message_type, message, media_url, media_type, status, message_id, from_me, read_status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'delivered', ?, ?, 0, NOW())",
            [
                $instance, $phone, $contactName,
                $fromMe ? 'outbound' : 'inbound',
                $messageType, $messageText,
                $mediaUrl, $mediaType,
                $messageId, $fromMe ? 1 : 0,
            ]
        );
    }

    private function handleMessagesUpdate(string $instance, array $data): void
    {
        $key = $data['key'] ?? [];
        $messageId = $key['id'] ?? '';
        $status = $data['update'] ?? [];

        if (empty($messageId)) return;

        if (isset($status['status'])) {
            $newStatus = $status['status'];
            $dbStatus = match($newStatus) {
                'DELIVERY_ACK' => 'delivered',
                'READ' => 'read',
                'PLAYED' => 'played',
                'SENT' => 'sent',
                'SERVER_ACK' => 'sent',
                default => 'sent',
            };

            $this->db->query(
                "UPDATE whatsapp_messages SET status = ? WHERE message_id = ?",
                [$dbStatus, $messageId]
            );

            if ($dbStatus === 'read') {
                $this->db->query(
                    "UPDATE whatsapp_messages SET read_status = 1, read_at = NOW() WHERE message_id = ?",
                    [$messageId]
                );
            }
        }
    }

    private function handleConnectionUpdate(string $instance, array $data): void
    {
        $state = $data['state'] ?? $data['connection'] ?? 'disconnected';

        $statusMap = [
            'open' => 'open',
            'close' => 'disconnected',
            'connecting' => 'connecting',
        ];

        $dbStatus = $statusMap[$state] ?? 'disconnected';

        $this->db->query(
            "UPDATE whatsapp_instances SET status = ? WHERE instance_name = ?",
            [$dbStatus, $instance]
        );

        if ($dbStatus === 'open') {
            $this->db->query(
                "UPDATE whatsapp_instances SET qrcode = NULL WHERE instance_name = ?",
                [$instance]
            );

            if (isset($data['user']) || isset($data['info'])) {
                $user = $data['user'] ?? $data['info'] ?? [];
                $this->db->query(
                    "UPDATE whatsapp_instances SET phone = ?, profile_name = ? WHERE instance_name = ?",
                    [$user['id'] ?? $user['phone'] ?? '', $user['name'] ?? $user['pushName'] ?? '', $instance]
                );
            }
        }
    }

    private function handleQRUpdate(string $instance, array $data): void
    {
        $qrcode = $data['base64'] ?? $data['data'] ?? null;

        if ($qrcode) {
            $this->db->query(
                "UPDATE whatsapp_instances SET qrcode = ? WHERE instance_name = ?",
                [$qrcode, $instance]
            );
        }
    }
}
