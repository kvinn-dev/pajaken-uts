<?php
// profile-info.php
require_once 'config.php';
$pageTitle = 'Pengaturan Profil';
include 'includes/header-plain.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        // Update profile data
        $userData['first_name'] = $_POST['first_name'] ?? $userData['first_name'];
        $userData['last_name'] = $_POST['last_name'] ?? $userData['last_name'];
        $userData['email'] = $_POST['email'] ?? $userData['email'];
        $userData['phone'] = $_POST['phone'] ?? $userData['phone'];

        $_SESSION['success'] = 'Profil berhasil diperbarui!';
        header('Location: profile-info.php');
        exit;
    }

    if ($action === 'update_address') {
        // Update address data
        $userData['province'] = $_POST['province'] ?? $userData['province'];
        $userData['city'] = $_POST['city'] ?? $userData['city'];
        $userData['district'] = $_POST['district'] ?? $userData['district'];
        $userData['village'] = $_POST['village'] ?? $userData['village'];
        $userData['address_detail'] = $_POST['address_detail'] ?? $userData['address_detail'];

        $_SESSION['success'] = 'Alamat berhasil diperbarui!';
        header('Location: profile-info.php');
        exit;
    }
}

$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
?>

<!-- Profile Header -->
<div class="relative bg-gradient-to-r from-primary to-purple-700 h-[200px]">
    <div class="mx-auto px-6">
        <!-- Top Navigation -->
        <div class="flex justify-between items-start pt-8">
            <a href="#" onclick="handleBack(event)" class="flex items-center gap-2 text-white/90 hover:text-white transition-colors group">
                <svg class="group-hover:-translate-x-1 transition-transform" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 12H5M12 19l-7-7 7-7"></path>
                </svg>
                <span class="text-md font-medium">Kembali</span>
            </a>

            <button class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-105" title="Edit Foto Profil">
                <svg width="16" height="16" fill="none" stroke="#6200ff" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Avatar -->
    <div class="absolute -bottom-20 left-10">
        <div class="avatar-wrapper relative w-44 h-44 rounded-full overflow-hidden border-[6px] border-gray-50 bg-white">
            <img src="assets/profile.svg" alt="profile" class="w-full h-full object-cover" />
            <div class="avatar-upload-overlay" onclick="document.getElementById('avatarUpload').click()">
                <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <input type="file" id="avatarUpload" class="hidden" accept="image/*" />
        </div>
    </div>
</div>

<!-- Profile Content -->
<div class="mx-auto px-6">
    <!-- User Info -->
    <div class="mt-24 ml-10">
        <h1 class="text-3xl font-bold text-gray-800">
            <?php echo htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']); ?>
        </h1>
        <div class="flex items-center gap-2 mt-2">
            <svg width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <p class="text-gray-500">
                Kab. <?php echo htmlspecialchars($userData['city']); ?>, <?php echo htmlspecialchars($userData['province']); ?>
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-6">
            <button id="editAllBtn" class="bg-primary text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-primary-dark transition-all active:scale-95 shadow-sm hover:shadow-md">
                <span class="flex items-center gap-2">
                    Edit Profil
                </span>
            </button>
            <a href="profile-setting.php" class="bg-white border-2 border-primary text-primary px-6 py-2.5 rounded-full text-sm font-medium hover:bg-primary-light transition-all active:scale-95">
                <span class="flex items-center gap-2">
                    Pengaturan
                </span>
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    <?php if ($success): ?>
        <div class="mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 animate-fadeIn">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                <path d="M22 4L12 14.01l-3-3"></path>
            </svg>
            <span class="text-sm"><?php echo htmlspecialchars($success); ?></span>
        </div>
    <?php endif; ?>

    <!-- Form Sections -->
    <div class="mt-8 space-y-6 px-6 pb-12 mb-12">

        <!-- Profile Information Card -->
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Profil</h3>
                    <p class="text-xs text-gray-400 mt-1">Data pribadi Anda</p>
                </div>
                <button onclick="toggleSection('profileSection')" class="edit-toggle-btn" id="profileEditBtn">
                    <span class="flex items-center gap-1.5">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit
                    </span>
                </button>
            </div>

            <form id="profileSection" action="profile-info.php" method="POST">
                <input type="hidden" name="action" value="update_profile">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Nama Depan <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="text"
                            name="first_name"
                            value="<?php echo htmlspecialchars($userData['first_name']); ?>"
                            class="profile-input"
                            disabled
                            required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Nama Belakang</label>
                        <input
                            type="text"
                            name="last_name"
                            value="<?php echo htmlspecialchars($userData['last_name']); ?>"
                            class="profile-input"
                            disabled />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="<?php echo htmlspecialchars($userData['email']); ?>"
                            class="profile-input"
                            disabled
                            required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            No. Handphone <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="text"
                            name="phone"
                            value="<?php echo htmlspecialchars($userData['phone']); ?>"
                            class="profile-input"
                            disabled
                            required
                            placeholder="+62" />
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 hidden" id="profileActions">
                    <button type="button" onclick="toggleSection('profileSection')" class="px-6 py-2.5 rounded-full text-sm font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-full text-sm font-medium bg-primary text-white hover:bg-primary-dark transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Address Card -->
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Alamat</h3>
                    <p class="text-xs text-gray-400 mt-1">Informasi tempat tinggal</p>
                </div>
                <button onclick="toggleSection('addressSection')" class="edit-toggle-btn" id="addressEditBtn">
                    <span class="flex items-center gap-1.5">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit
                    </span>
                </button>
            </div>

            <form id="addressSection" action="profile-info.php" method="POST">
                <input type="hidden" name="action" value="update_address">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Provinsi <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="text"
                            name="province"
                            value="<?php echo htmlspecialchars($userData['province']); ?>"
                            class="profile-input"
                            disabled
                            required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Kab/Kota <span class="text-red-400">*</span>
                        </label>
                        <select name="city" class="profile-select" disabled required>
                            <option value="">Pilih Kab/Kota</option>
                            <option value="Bekasi" <?php echo $userData['city'] === 'Bekasi' ? 'selected' : ''; ?>>Bekasi</option>
                            <option value="Jakarta" <?php echo $userData['city'] === 'Jakarta' ? 'selected' : ''; ?>>Jakarta</option>
                            <option value="Bandung" <?php echo $userData['city'] === 'Bandung' ? 'selected' : ''; ?>>Bandung</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Kecamatan <span class="text-red-400">*</span>
                        </label>
                        <select name="district" class="profile-select" disabled required>
                            <option value="">Pilih Kecamatan</option>
                            <option value="Mustika Jaya" <?php echo $userData['district'] === 'Mustika Jaya' ? 'selected' : ''; ?>>Mustika Jaya</option>
                            <option value="Bekasi Utara" <?php echo $userData['district'] === 'Bekasi Utara' ? 'selected' : ''; ?>>Bekasi Utara</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Kelurahan <span class="text-red-400">*</span>
                        </label>
                        <select name="village" class="profile-select" disabled required>
                            <option value="">Pilih Kelurahan</option>
                            <option value="Mustika Jaya" <?php echo $userData['village'] === 'Mustika Jaya' ? 'selected' : ''; ?>>Mustika Jaya</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Detail Alamat</label>
                        <textarea
                            name="address_detail"
                            rows="3"
                            placeholder="cth: Jl. Angsana No. 123, RT 01/RW 02"
                            class="profile-input resize-none"
                            disabled><?php echo htmlspecialchars($userData['address_detail']); ?></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 hidden" id="addressActions">
                    <button type="button" onclick="toggleSection('addressSection')" class="px-6 py-2.5 rounded-full text-sm font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-full text-sm font-medium bg-primary text-white hover:bg-primary-dark transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

    // Toggle edit mode for each section
    function toggleSection(sectionId) {
        const section = document.getElementById(sectionId);
        const inputs = section.querySelectorAll('input, select, textarea');
        const actionsDiv = document.getElementById(sectionId.replace('Section', 'Actions'));
        const editBtn = document.getElementById(sectionId.replace('Section', 'EditBtn'));

        const isEditing = !inputs[0].disabled;

        inputs.forEach(input => {
            input.disabled = isEditing;
            if (isEditing) {
                input.classList.remove('editable');
            } else {
                input.classList.add('editable');
            }
        });

        if (actionsDiv) {
            actionsDiv.classList.toggle('hidden', isEditing);
        }

        if (editBtn) {
            if (isEditing) {
                editBtn.classList.remove('active');
                editBtn.innerHTML = `
                <span class="flex items-center gap-1.5">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit
                </span>
            `;
            } else {
                editBtn.classList.add('active');
                editBtn.innerHTML = `
                <span class="flex items-center gap-1.5">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12"></path>
                    </svg>
                    Tutup
                </span>
            `;
            }
        }
    }

    // Edit All button
    document.getElementById('editAllBtn').addEventListener('click', function() {
        const sections = ['profileSection', 'addressSection'];
        sections.forEach(sectionId => {
            const inputs = document.getElementById(sectionId).querySelectorAll('input, select, textarea');
            const isEditing = !inputs[0].disabled;

            // Only enable if currently disabled
            if (isEditing === false) {
                toggleSection(sectionId);
            }
        });
    });

    // Avatar upload
    document.getElementById('avatarUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.avatar-wrapper img').src = e.target.result;
                // Show success feedback
                const avatar = document.querySelector('.avatar-wrapper');
                avatar.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    avatar.style.transform = 'scale(1)';
                }, 200);
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php include 'includes/footer.php'; ?>