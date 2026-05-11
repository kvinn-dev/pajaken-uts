<?php
// notification.php
require_once 'config.php';
$pageTitle = 'Notifikasi';
include 'includes/header-plain.php';

// Sample notifications data (ganti dengan database query nanti)
$notifications = [
    [
        'id' => 1,
        'type' => 'jatuh_tempo',
        'type_label' => 'Jatuh Tempo',
        'type_color' => 'orange',
        'type_bg' => '#f59e0b',
        'title' => 'Pajak Motor B 1234 ABC Jatuh Tempo',
        'message' => 'Pajak kendaraan motor Anda dengan nomor polisi B 1234 ABC akan jatuh tempo pada tanggal 21 April 2026. Segera lakukan pembayaran untuk menghindari denda.',
        'read_at' => null,
        'created_at' => '2026-04-18 10:30:00',
        'action_url' => 'reminder.php',
        'action_text' => 'Atur Pengingat'
    ],
    [
        'id' => 2,
        'type' => 'selesai',
        'type_label' => 'Selesai',
        'type_color' => 'green',
        'type_bg' => '#10b981',
        'title' => 'Pembayaran Pajak Berhasil',
        'message' => 'Pembayaran pajak untuk kendaraan DK 4567 BCD telah berhasil diproses. Bukti pembayaran telah dikirim ke email Anda.',
        'read_at' => '2026-04-15 14:20:00',
        'created_at' => '2026-04-15 14:20:00',
        'action_url' => '#',
        'action_text' => 'Lihat Bukti'
    ],
    [
        'id' => 3,
        'type' => 'masalah',
        'type_label' => 'Masalah',
        'type_color' => 'red',
        'type_bg' => '#ef4444',
        'title' => 'Gagal Memproses Pembayaran',
        'message' => 'Pembayaran pajak untuk kendaraan B 7890 XYZ gagal diproses. Silakan coba lagi atau hubungi customer service.',
        'read_at' => null,
        'created_at' => '2026-04-12 09:15:00',
        'action_url' => 'tax-check.php',
        'action_text' => 'Coba Lagi'
    ],
    [
        'id' => 4,
        'type' => 'info',
        'type_label' => 'Info',
        'type_color' => 'blue',
        'type_bg' => '#3b82f6',
        'title' => 'Pengingat 7 Hari Sebelum Jatuh Tempo',
        'message' => 'Pengingat untuk pembayaran pajak kendaraan B 1234 ABC dalam 7 hari ke depan telah diaktifkan.',
        'read_at' => '2026-04-10 08:00:00',
        'created_at' => '2026-04-10 08:00:00',
        'action_url' => 'reminder.php',
        'action_text' => 'Kelola Pengingat'
    ],
    [
        'id' => 5,
        'type' => 'info',
        'type_label' => 'Info',
        'type_color' => 'blue',
        'type_bg' => '#3b82f6',
        'title' => 'Info Perubahan Tarif Pajak',
        'message' => 'Mulai Mei 2026, terdapat penyesuaian tarif pajak kendaraan bermotor. Cek informasi selengkapnya.',
        'read_at' => null,
        'created_at' => '2026-04-05 11:45:00',
        'action_url' => '#',
        'action_text' => 'Baca Selengkapnya'
    ]
];

// Fungsi untuk menghitung waktu relatif
function diffForHumans($datetime)
{
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

// Tandai semua dibaca (via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_all_read'])) {
    // Di sini update database
    $_SESSION['success'] = 'Semua notifikasi telah ditandai sudah dibaca';
    header('Location: notification.php');
    exit;
}
?>

<!-- HEADER -->
<header class="top-nav-notif">
    <a href="#" onclick="handleBack(event)" class="nav-back-btn-notif">
        <img width="18" height="18" src="https://img.icons8.com/ios-filled/50/4f46e5/back.png" alt="back" />
        Kembali
    </a>
    <div class="nav-right">
        <a href="profile-info.php">
            <div class="nav-avatar">
                <img src="assets/profile.svg" alt="profile" />
            </div>
        </a>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="notif-container">
    <div class="notif-main">
        <div class="notif-controls">
            <button class="notif-filter-btn">
                Terbaru
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 9l6 6 6-6"></path>
                </svg>
            </button>
            <form method="POST" style="display: inline;">
                <input type="hidden" name="mark_all_read" value="1">
                <button type="submit" class="notif-mark-read-btn">Tandai semua telah dibaca</button>
            </form>
        </div>

        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="notif-card <?php echo !$notif['read_at'] ? 'unread' : ''; ?>" data-id="<?php echo $notif['id']; ?>">
                    <div class="notif-icon-wrapper" style="background: <?php echo $notif['type_bg']; ?>;">
                        <?php if ($notif['type'] === 'jatuh_tempo'): ?>
                            <svg width="24" height="24" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 6v6l4 2"></path>
                            </svg>
                        <?php elseif ($notif['type'] === 'selesai'): ?>
                            <svg width="24" height="24" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                <path d="M22 4L12 14.01l-3-3"></path>
                            </svg>
                        <?php elseif ($notif['type'] === 'masalah'): ?>
                            <svg width="24" height="24" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 8v4M12 16h.01"></path>
                            </svg>
                        <?php else: ?>
                            <svg width="24" height="24" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <path d="M16 2v4M8 2v4M3 10h18"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div class="notif-content">
                        <div class="notif-title-row">
                            <h3><?php echo htmlspecialchars($notif['title']); ?></h3>
                            <span class="badge <?php echo $notif['type_color']; ?>"><?php echo $notif['type_label']; ?></span>
                        </div>
                        <p><?php echo htmlspecialchars($notif['message']); ?></p>
                    </div>
                    <div class="notif-actions">
                        <span class="action-text">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="M21 21l-4.35-4.35"></path>
                            </svg>
                            <?php echo diffForHumans($notif['created_at']); ?>
                        </span>
                        <?php if (!empty($notif['action_url'])): ?>
                            <a href="<?php echo $notif['action_url']; ?>" class="action-link">
                                <?php echo $notif['action_text']; ?>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="notif-empty">
                <svg width="48" height="48" fill="none" stroke="#ccc" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9zm-6 13a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                </svg>
                <h3>Tidak Ada Notifikasi</h3>
                <p>Anda belum memiliki notifikasi. Semua pembayaran pajak Anda sudah aman!</p>
            </div>
        <?php endif; ?>
    </div>

    <aside class="notif-sidebar">
        <a href="tax-check.php" class="btn-sidebar-primary">Cek Pajak Baru</a>

        <ul class="sidebar-menu">
            <li>
                <a href="#" class="active">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"></path>
                    </svg>
                    Semua Notifikasi
                    <svg class="check" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </a>
            </li>
            <li>
                <a href="#">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l3 3"></path>
                    </svg>
                    Terbaru
                </a>
            </li>
            <li>
                <a href="#">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 8v4l2 2"></path>
                    </svg>
                    Riwayat Notifikasi
                </a>
            </li>
        </ul>

        <div class="sidebar-divider"></div>

        <ul class="legend-list">
            <li>
                <div class="dot orange"></div>
                Jatuh Tempo
            </li>
            <li>
                <div class="dot green"></div>
                Selesai
            </li>
            <li>
                <div class="dot red"></div>
                Masalah
            </li>
            <li>
                <div class="dot blue"></div>
                Info
            </li>
            <li>
                <div class="dot gray"></div>
                Lainnya
            </li>
        </ul>
    </aside>
</main>

<script>
    // Handle back button
    function handleBack(e) {
        e.preventDefault();
        if (window.history.length > 1 && document.referrer) {
            window.history.back();
        } else {
            window.location.href = 'homepage.php';
        }
    }
</script>

<?php include 'includes/footer.php'; ?>