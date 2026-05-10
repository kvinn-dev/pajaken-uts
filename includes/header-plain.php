<?php
// includes/header-plain.php
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
    
    <!-- Hanya load CSS yang diperlukan untuk profile -->
    <style>
        /* ========== PROFILE STYLES ========== */
        
        /* Input Fields */
        .profile-input {
            width: 100%;
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .profile-input:focus {
            border-color: #6200ff;
            box-shadow: 0 0 0 3px rgba(98, 0, 255, 0.1);
            background-color: white;
            outline: none;
        }
        
        .profile-input:disabled {
            background-color: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
            border-color: #e5e7eb;
        }
        
        .profile-input.editable {
            background-color: white;
            border-color: #d1d5db;
        }
        
        /* Select Fields */
        .profile-select {
            width: 100%;
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            font-size: 14px;
            transition: all 0.2s ease;
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg width='16' height='16' fill='none' stroke='%236b7280' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }
        
        .profile-select:focus {
            border-color: #6200ff;
            box-shadow: 0 0 0 3px rgba(98, 0, 255, 0.1);
            outline: none;
        }
        
        .profile-select:disabled {
            background-color: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }
        
        /* Edit Toggle Button */
        .edit-toggle-btn {
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid #d1d5db;
            color: #6b7280;
            background: white;
            cursor: pointer;
        }
        
        .edit-toggle-btn:hover {
            background-color: #f3f4f6;
        }
        
        .edit-toggle-btn.active {
            background-color: #6200ff;
            color: white;
            border-color: #6200ff;
        }
        
        /* Avatar Styles */
        .avatar-upload-overlay {
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.4);
            opacity: 0;
            transition: opacity 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .avatar-wrapper:hover .avatar-upload-overlay {
            opacity: 1;
        }
        
        /* Animations */
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">