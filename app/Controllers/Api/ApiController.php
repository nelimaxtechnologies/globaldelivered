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
}
