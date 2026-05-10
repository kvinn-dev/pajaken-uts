<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Pajaken';
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?> - Pajaken</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/global.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6200ff',
                        'primary-light': '#f0ebff',
                        'primary-dark': '#4a00cc',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans antialiased">

    <!-- Top Navigation -->
    <header class="fixed top-0 left-0 right-0 z-40 backdrop-blur-sm">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="index.php" class="flex-shrink-0">
                    <img src="assets/logo-pajaken.svg" alt="Logo Pajaken" class="h-10" />
                </a>

                <nav class="hidden md:flex bg-white rounded-full p-1.5 shadow-sm border border-gray-100">
                    <a href="homepage.php" class="px-6 py-2.5 rounded-full text-sm font-medium text-gray-600 hover:text-primary transition-colors">
                        Beranda
                    </a>
                    <a href="tax-check.php" class="px-6 py-2.5 rounded-full text-sm font-medium text-gray-600 hover:text-primary transition-colors">
                        Cek Pajak
                    </a>
                    <a href="reminder.php" class="px-6 py-2.5 rounded-full text-sm font-medium bg-primary text-white">
                        Pengingat
                    </a>
                </nav>

                <div class="flex items-center gap-6">
                    <!-- Notification Bell dengan Dropdown -->
                    <div class="relative">
                        <div class="relative cursor-pointer" onclick="toggleNotificationDropdown()">
                            <svg width="24" height="24" fill="none" stroke="#6200ff" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9zm-6 13a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                            <div class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-red-500 rounded-full"></div>
                        </div>

                        <!-- Notification Dropdown -->
                        <div id="notificationDropdown" class="notification-dropdown hidden">
                            <!-- Header -->
                            <div class="dropdown-header">
                                <h3>Notifikasi</h3>
                                <button onclick="markAllAsRead(event)" class="dropdown-mark-read">Tandai semua telah dibaca</button>
                            </div>

                            <!-- Body -->
                            <div class="dropdown-body">
                                <?php
                                // Sample notifications data
                                $notifications = [
                                    [
                                        'id' => 1,
                                        'type' => 'jatuh_tempo',
                                        'title' => 'Pajak Motor Jatuh Tempo',
                                        'message' => 'Pajak motor B 1234 ABC akan jatuh tempo pada 21 April 2026. Segera lakukan pembayaran.',
                                        'time' => '2 hari yang lalu',
                                        'read' => false
                                    ],
                                    [
                                        'id' => 2,
                                        'type' => 'info',
                                        'title' => 'Pengingat Aktif',
                                        'message' => 'Pengingat 7 hari sebelum pajak motor B 1234 ABC jatuh tempo telah diaktifkan.',
                                        'time' => '3 hari yang lalu',
                                        'read' => false
                                    ],
                                    [
                                        'id' => 3,
                                        'type' => 'selesai',
                                        'title' => 'Pembayaran Berhasil',
                                        'message' => 'Pembayaran pajak untuk kendaraan DK 4567 BCD telah berhasil diproses.',
                                        'time' => '5 hari yang lalu',
                                        'read' => true
                                    ],
                                    [
                                        'id' => 4,
                                        'type' => 'maintenance',
                                        'title' => 'Info Maintenance',
                                        'message' => 'Sistem akan melakukan maintenance pada 25 April 2026 pukul 00:00 - 06:00 WIB.',
                                        'time' => '1 minggu yang lalu',
                                        'read' => true
                                    ],
                                    [
                                        'id' => 5,
                                        'type' => 'promo',
                                        'title' => 'Promo Diskon Pajak',
                                        'message' => 'Dapatkan diskon 10% untuk pembayaran pajak kendaraan sebelum 30 April 2026.',
                                        'time' => '1 minggu yang lalu',
                                        'read' => true
                                    ]
                                ];

                                if (!empty($notifications)):
                                    foreach ($notifications as $notif):
                                        // Tentukan warna icon berdasarkan tipe
                                        $iconBgColors = [
                                            'info' => 'background: #3b82f6;',
                                            'maintenance' => 'background: #f59e0b;',
                                            'greeting' => 'background: #ec4899;',
                                            'event' => 'background: #8b5cf6;',
                                            'promo' => 'background: #10b981;',
                                            'jatuh_tempo' => 'background: #ef4444;',
                                            'selesai' => 'background: #10b981;',
                                            'masalah' => 'background: #f97316;'
                                        ];
                                        $iconBg = $iconBgColors[$notif['type']] ?? 'background: #6b7280;';

                                        // Tentukan icon SVG berdasarkan tipe
                                        $iconSvg = '';
                                        switch ($notif['type']) {
                                            case 'info':
                                                $iconSvg = '<svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg>';
                                                break;
                                            case 'maintenance':
                                                $iconSvg = '<svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"></path></svg>';
                                                break;
                                            case 'event':
                                                $iconSvg = '<svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>';
                                                break;
                                            case 'promo':
                                                $iconSvg = '<svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                                                break;
                                            case 'jatuh_tempo':
                                                $iconSvg = '<svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>';
                                                break;
                                            case 'selesai':
                                                $iconSvg = '<svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path><path d="M22 4L12 14.01l-3-3"></path></svg>';
                                                break;
                                            case 'masalah':
                                                $iconSvg = '<svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v4M12 16h.01"></path></svg>';
                                                break;
                                            default:
                                                $iconSvg = '<svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg>';
                                        }
                                ?>
                                        <div class="dropdown-notif-item <?php echo !$notif['read'] ? 'unread' : ''; ?>" data-id="<?php echo $notif['id']; ?>">
                                            <div class="dropdown-notif-icon" style="<?php echo $iconBg; ?>">
                                                <?php echo $iconSvg; ?>
                                            </div>
                                            <div class="dropdown-notif-content">
                                                <p class="dropdown-notif-title"><?php echo htmlspecialchars($notif['title']); ?></p>
                                                <p class="dropdown-notif-desc"><?php echo htmlspecialchars($notif['message']); ?></p>
                                                <span class="dropdown-notif-time"><?php echo htmlspecialchars($notif['time']); ?></span>
                                            </div>
                                            <?php if (!$notif['read']): ?>
                                                <div class="dropdown-notif-dot"></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php
                                    endforeach;
                                else:
                                    ?>
                                    <div class="dropdown-empty">
                                        <p class="dropdown-empty-text">Tidak ada notifikasi</p>
                                        <p class="dropdown-empty-subtext">Notifikasi akan muncul di sini</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- View All -->
                            <a href="notification.php" class="dropdown-view-all">Lihat Semua Notifikasi</a>
                        </div>
                    </div>
                    <a href="profile-info.php">
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary">
                            <img src="assets/profile.svg" alt="profile" class="w-full h-full object-cover" />
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </header>