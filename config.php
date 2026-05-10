<?php
// config.php
session_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pajaken');

// Create connection
function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// ==========================================
// USER DATA
// ==========================================

// Default user data
$defaultUserData = [
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'johndoe123@example.com',
    'phone' => '+62 1234567890',
    'province' => 'Jawa Barat',
    'city' => 'Bekasi',
    'district' => 'Mustika Jaya',
    'village' => 'Mustika Jaya',
    'address_detail' => 'Jl. Angsana No. 123, RT 01/RW 02'
];

// Initialize user data in session if not exists
if (!isset($_SESSION['user_data'])) {
    $_SESSION['user_data'] = $defaultUserData;
}

// Global variable for easy access
$userData = $_SESSION['user_data'];

// ==========================================
// VEHICLES & REMINDERS DATA
// ==========================================

// Sample data for development (remove when using real database)
function getSampleVehicles() {
    return [
        [
            'id' => 1,
            'no_polisi' => 'B 1234 ABC',
            'jenis' => 'motor',
            'warna' => 'Hitam',
            'tanggal_pajak' => '2026-04-21',
        ],
        [
            'id' => 2,
            'no_polisi' => 'DK 4567 BCD',
            'jenis' => 'motor',
            'warna' => 'Merah',
            'tanggal_pajak' => '2026-04-30',
        ],
        [
            'id' => 3,
            'no_polisi' => 'B 7890 XYZ',
            'jenis' => 'mobil',
            'warna' => 'Putih',
            'tanggal_pajak' => '2026-05-15',
        ]
    ];
}

function getSampleReminders() {
    return [
        [
            'id' => 1,
            'vehicle_id' => 1,
            'tanggal' => '2026-04-21',
            'reminder_type' => '7',
            'vehicle' => [
                'id' => 1,
                'no_polisi' => 'B 1234 ABC',
                'jenis' => 'motor',
                'warna' => 'Hitam',
                'tanggal_pajak' => '2026-04-21'
            ]
        ],
        [
            'id' => 2,
            'vehicle_id' => 2,
            'tanggal' => '2026-04-30',
            'reminder_type' => '3',
            'vehicle' => [
                'id' => 2,
                'no_polisi' => 'DK 4567 BCD',
                'jenis' => 'motor',
                'warna' => 'Merah',
                'tanggal_pajak' => '2026-04-30'
            ]
        ]
    ];
}

// Get vehicles and reminders (replace with DB query in production)
$vehicles = getSampleVehicles();
$reminders = getSampleReminders();

// ==========================================
// HELPER FUNCTIONS
// ==========================================

function formatDateID($date) {
    $months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $timestamp = strtotime($date);
    return date('d', $timestamp) . ' ' . $months[date('n', $timestamp) - 1] . ' ' . date('Y', $timestamp);
}

function getLocalISODate($date) {
    return date('Y-m-d', strtotime($date));
}

/**
 * Update user data in session
 * @param array $newData
 * @return void
 */
function updateUserData($newData) {
    foreach ($newData as $key => $value) {
        if (isset($_SESSION['user_data'][$key])) {
            $_SESSION['user_data'][$key] = $value;
        }
    }
}

/**
 * Get user data by key
 * @param string $key
 * @return mixed|null
 */
function getUserData($key) {
    global $userData;
    return $userData[$key] ?? null;
}

/**
 * Get user full name
 * @return string
 */
function getUserFullName() {
    global $userData;
    return trim($userData['first_name'] . ' ' . $userData['last_name']);
}

/**
 * Get user address
 * @return string
 */
function getUserAddress() {
    global $userData;
    $address = $userData['address_detail'] . ', ';
    $address .= $userData['village'] . ', ';
    $address .= $userData['district'] . ', ';
    $address .= $userData['city'] . ', ';
    $address .= $userData['province'];
    return $address;
}
?>