<?php
// config.php
session_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pajaken_uts');

// Create connection
function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// ==========================================
// USER DATA (dari database)
// ==========================================

// Default user data (fallback)
$defaultUserData = [
    'id' => 0,
    'first_name' => 'User',
    'last_name' => '',
    'email' => '',
    'phone' => '',
    'nik' => ''
];

/**
 * Ambil data user dari database
 * @param int $userId
 * @return array
 */
function getUserFromDB($userId = 1) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    
    return $user ?: null;
}

// Initialize user data in session if not exists
if (!isset($_SESSION['user_data'])) {
    $dbUser = getUserFromDB(1); // Ambil user ID 1
    if ($dbUser) {
        $_SESSION['user_data'] = $dbUser;
    } else {
        $_SESSION['user_data'] = $defaultUserData;
    }
}

// Global variable for easy access
$userData = $_SESSION['user_data'];

// ==========================================
// VEHICLES DATA (dari database)
// ==========================================

/**
 * Ambil semua kendaraan dari database
 * @param int $userId
 * @return array
 */
function getVehiclesFromDB($userId = 1) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT * FROM kendaraan WHERE user_id = ? ORDER BY tanggal_pajak ASC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    
    return $vehicles ?: [];
}

/**
 * Ambil kendaraan berdasarkan ID
 * @param int $vehicleId
 * @return array|null
 */
function getVehicleById($vehicleId) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT * FROM kendaraan WHERE id = ?");
    $stmt->bind_param("i", $vehicleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicle = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    
    return $vehicle ?: null;
}

// ==========================================
// REMINDERS DATA (dari database)
// ==========================================

/**
 * Ambil semua reminders dari database
 * @param int $userId
 * @return array
 */
function getRemindersFromDB($userId = 1) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT r.*, k.no_polisi, k.jenis, k.warna, k.tanggal_pajak 
        FROM reminders r 
        JOIN kendaraan k ON r.vehicle_id = k.id 
        WHERE r.user_id = ? AND r.is_active = 1
        ORDER BY r.tanggal ASC
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $reminders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    
    return $reminders ?: [];
}

// ==========================================
// NOTIFICATIONS DATA (dari database)
// ==========================================

/**
 * Ambil semua notifikasi dari database
 * @param int $userId
 * @param int $limit
 * @return array
 */
function getNotificationsFromDB($userId = 1, $limit = 10) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT * FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT ?
    ");
    $stmt->bind_param("ii", $userId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    
    return $notifications ?: [];
}

/**
 * Hitung notifikasi belum dibaca
 * @param int $userId
 * @return int
 */
function getUnreadNotificationCount($userId = 1) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM notifications WHERE user_id = ? AND read_at IS NULL");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['total'] ?? 0;
    $stmt->close();
    $conn->close();
    
    return $count;
}

// ==========================================
// STATISTICS (dari database)
// ==========================================

/**
 * Total kendaraan user
 * @param int $userId
 * @return int
 */
function getTotalKendaraan($userId = 1) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM kendaraan WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc()['total'] ?? 0;
    $stmt->close();
    $conn->close();
    
    return $total;
}

// Ambil data dari database
$vehicles = getVehiclesFromDB(1);
$reminders = getRemindersFromDB(1);
$allNotifications = getNotificationsFromDB(1, 5);
$unreadCount = getUnreadNotificationCount(1);

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

function diffForHumans($datetime) {
    $timestamp = strtotime($datetime);
    $now = time();
    $diff = $now - $timestamp;
    
    if ($diff < 60) return 'Baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit yang lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam yang lalu';
    if ($diff < 604800) return floor($diff / 86400) . ' hari yang lalu';
    if ($diff < 2592000) return floor($diff / 604800) . ' minggu yang lalu';
    return date('d M Y', $timestamp);
}

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Update user data in session
 */
function updateUserData($newData) {
    foreach ($newData as $key => $value) {
        if (isset($_SESSION['user_data'][$key])) {
            $_SESSION['user_data'][$key] = $value;
        }
    }
    // Reload dari DB setelah update
    $_SESSION['user_data'] = getUserFromDB($_SESSION['user_data']['id'] ?? 1);
}

/**
 * Get user data by key
 */
function getUserData($key) {
    global $userData;
    return $userData[$key] ?? null;
}

/**
 * Get user full name
 */
function getUserFullName() {
    global $userData;
    return trim(($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''));
}
?>