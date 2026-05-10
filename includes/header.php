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
    <style>
        /* ============================================ */
        /* TOGGLE BUTTON (Motor/Mobil)                  */
        /* ============================================ */

        /* 1. Base State - Default */
        .toggle-btn {
            border: 2px solid #d1d5db;
            color: #6b7280;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
            opacity: 1;
            filter: none;
        }

        /* 2. Hover - Hanya jika TIDAK disabled */
        .toggle-btn:not([disabled]):hover {
            border-color: #4f46e5;
            background: #faf8ff;
            color: #4f46e5;
        }

        /* 3. Active - State terkuat (harus override semua) */
        .toggle-btn.active,
        .toggle-btn[data-active="true"] {
            border-color: #4f46e5 !important;
            color: #4f46e5 !important;
            background: #f0ebff !important;
            font-weight: 600;
            opacity: 1 !important;
            cursor: pointer;
            filter: none !important;
            pointer-events: auto;
        }

        /* 4. Disabled - Saat belum pilih kendaraan */
        .toggle-btn[disabled],
        .toggle-btn.disabled {
            opacity: 0.4 !important;
            cursor: not-allowed;
            background: #f3f4f6;
            border-color: #e5e7eb;
            color: #9ca3af;
            pointer-events: none;
            filter: grayscale(30%);
            font-weight: 400;
        }

        /* 5. Locked - Tipe yang tidak sesuai data */
        .toggle-btn.locked {
            opacity: 0.3 !important;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(50%);
            font-weight: 400;
            position: relative;
        }

        /* Override: Active HARUS mengalahkan disabled dan locked */
        .toggle-btn.active[disabled],
        .toggle-btn.active.locked {
            opacity: 1 !important;
            filter: none !important;
            pointer-events: auto;
            cursor: pointer;
        }

        /* ============================================ */
        /* REMINDER BUTTON (3/7/30 Hari)                */
        /* ============================================ */

        .reminder-btn {
            border: 2px solid #d1d5db;
            color: #6b7280;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
            opacity: 1;
        }

        .reminder-btn:not([disabled]):hover {
            border-color: #4f46e5;
            background: #faf8ff;
            color: #4f46e5;
        }

        .reminder-btn.active {
            border-color: #4f46e5 !important;
            color: #4f46e5 !important;
            background: #f0ebff !important;
            font-weight: 600;
            opacity: 1 !important;
        }

        .reminder-btn[disabled] {
            opacity: 0.4 !important;
            cursor: not-allowed;
            background: #f3f4f6;
            border-color: #e5e7eb;
            color: #9ca3af;
            pointer-events: none;
        }

        .reminder-btn.active[disabled] {
            opacity: 1 !important;
            cursor: pointer;
            pointer-events: auto;
        }

        /* ============================================ */
        /* INPUT STYLES                                 */
        /* ============================================ */

        input[disabled],
        input[readonly] {
            cursor: not-allowed;
            background-color: #f3f4f6 !important;
            border-color: #e5e7eb !important;
            color: #9ca3af !important;
            opacity: 0.7;
        }

        input.filled {
            background-color: #f9fafb !important;
            border-color: #d1d5db !important;
            color: #374151 !important;
            cursor: default;
            opacity: 1 !important;
        }

        /* ============================================ */
        /* SUBMIT BUTTON                                */
        /* ============================================ */

        #submitReminder {
            transition: all 0.3s ease;
        }

        #submitReminder:disabled {
            background: #d1d5db !important;
            color: #9ca3af !important;
            cursor: not-allowed;
            opacity: 0.6;
            transform: none !important;
        }

        #submitReminder:not(:disabled):hover {
            background: #4a00cc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(98, 0, 255, 0.3);
        }

        #submitReminder:not(:disabled):active {
            transform: translateY(0);
        }

        /* ============================================ */
        /* CALENDAR CELL                                */
        /* ============================================ */

        .rm-cal-cell {
            min-height: 98px;
            border-right: 1px solid #f3f4f6;
            border-bottom: 1px solid #f3f4f6;
            padding: 10px;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
        }

        .rm-cal-cell:hover {
            background-color: #faf8ff;
        }

        .rm-cal-cell:nth-child(7n) {
            border-right: none;
        }

        .rm-cal-cell.today {
            background-color: #f0ebff;
        }

        .rm-cal-cell.today .rm-date-num {
            color: #4f46e5;
            font-weight: 700;
        }

        .rm-cal-cell.selected {
            background-color: #e8e0ff;
            box-shadow: inset 0 0 0 2px #4f46e5;
        }

        .rm-cal-cell.has-event {
            background-color: #fff5f5;
        }

        .rm-date-top {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 4px;
            margin-bottom: 8px;
        }

        .rm-date-num {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }

        .rm-date-num.muted {
            color: #d1d5db;
        }

        /* ============================================ */
        /* EVENT DOTS & BLOCKS                          */
        /* ============================================ */

        .rm-event-dot {
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
            flex-shrink: 0;
            animation: pulse-dot 2s infinite;
        }

        .rm-event-dot.reminder-dot {
            background: #a78bfa;
            animation: pulse-dot 3s infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.3);
                opacity: 0.7;
            }
        }

        .rm-event-block {
            background: #fef2f2;
            border-left: 3px solid #4f46e5;
            padding: 6px 8px;
            border-radius: 4px;
            font-size: 11px;
            color: #4f46e5;
            margin-top: auto;
            line-height: 1.3;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .rm-event-block:hover {
            transform: scale(1.02);
            background: #fee2e2;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
        }

        .rm-event-block.overdue {
            background: #fee2e2;
            border-left-color: #991b1b;
            color: #991b1b;
        }

        /* ============================================ */
        /* MINI CALENDAR                                */
        /* ============================================ */

        .rm-mini-day {
            font-size: 11px;
            font-weight: 600;
            color: #374151;
            height: 28px;
            width: 28px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rm-mini-date {
            font-size: 11px;
            color: #6b7280;
            height: 28px;
            width: 28px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .rm-mini-date.muted {
            color: #d1d5db;
        }

        .rm-mini-date.due-date-full {
            background: #4f46e5;
            color: white;
            font-weight: 600;
        }

        .rm-mini-date.preview-outline {
            border: 1.5px solid #4f46e5;
            color: #4f46e5;
            font-weight: 500;
        }

        .rm-mini-date.today-subtle {
            background: #f3f4f6;
            color: #374151;
            font-weight: 600;
        }

        .rm-mini-date.has-event {
            position: relative;
        }

        .rm-mini-date.has-event::after {
            content: '';
            position: absolute;
            bottom: 3px;
            width: 4px;
            height: 4px;
            background: #4f46e5;
            border-radius: 50%;
        }

        /* ============================================ */
        /* MODAL                                        */
        /* ============================================ */

        #eventDetailModal {
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        #eventDetailModal>div {
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #modalCloseBtn {
            transition: all 0.3s ease;
        }

        #modalCloseBtn:hover {
            color: #4f46e5;
            transform: rotate(90deg);
        }

        /* ============================================ */
        /* ALERTS & ANIMATIONS                          */
        /* ============================================ */

        .animate-fadeIn {
            animation: fadeInDown 0.3s ease-in;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
        }
    </style>
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

                <div class="flex items-center gap-4">
                    <div class="relative cursor-pointer">
                        <svg width="24" height="24" fill="none" stroke="#6200ff" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9zm-6 13a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                        </svg>
                        <div class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-red-500 rounded-full"></div>
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