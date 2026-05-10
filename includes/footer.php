<!-- Footer -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Helper Functions
        function getLocalISODate(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        let currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
        let selectedDate = null;
        let activeReminderPreviewDate = null;

        // Data from PHP
        const vehicles = <?php echo json_encode($vehicles ?? []); ?>;
        const reminders = <?php echo json_encode($reminders ?? []); ?>;
        let reminderEvents = [];

        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // DOM Elements
        const calendarGrid = document.getElementById('calendarGrid');
        const miniCalendarGrid = document.getElementById('miniCalendarGrid');
        const currentMonthYear = document.getElementById('currentMonthYear');
        const vehicleSelect = document.getElementById('vehicleSelect');
        const taxDueDateDisplay = document.getElementById('taxDueDateDisplay');
        const taxDueDateInput = document.getElementById('taxDueDate');
        const btnMotor = document.getElementById('btnMotor');
        const btnMobil = document.getElementById('btnMobil');
        const vehicleTypeInput = document.getElementById('vehicleTypeInput');
        const reminderDaysInput = document.getElementById('reminderDaysInput');
        const searchInput = document.getElementById('searchVehicle');
        const eventDetailModal = document.getElementById('eventDetailModal');
        const submitBtn = document.getElementById('submitReminder');

        function processReminders() {
            reminderEvents = [];
            reminders.forEach(r => {
                if (!r.tanggal || !r.reminder_type) return;
                const dueDate = new Date(r.tanggal);
                dueDate.setHours(0, 0, 0, 0);
                const reminderDate = new Date(dueDate);
                reminderDate.setDate(reminderDate.getDate() - parseInt(r.reminder_type, 10));
                reminderEvents.push({
                    date: getLocalISODate(reminderDate),
                    type: 'reminder',
                    vehicle: r.vehicle,
                    originalReminder: r
                });
            });
        }

        function updateReminderOptions(taxDateStr, vehicleId = null) {
            const reminderButtons = document.querySelectorAll('.reminder-btn');

            if (!taxDateStr) {
                reminderButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.remove('active');
                });
                reminderDaysInput.value = '';
                submitBtn.disabled = true;
                submitBtn.textContent = 'Pilih Kendaraan';
                return;
            }

            const taxDate = new Date(taxDateStr);
            taxDate.setHours(0, 0, 0, 0);
            const diffTime = taxDate.getTime() - today.getTime();
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            let existingTypes = [];
            if (vehicleId) {
                existingTypes = reminders
                    .filter(r => r.vehicle_id == vehicleId)
                    .map(r => String(r.reminder_type));
            }

            let firstAvailable = null;

            reminderButtons.forEach(btn => {
                const days = parseInt(btn.dataset.days, 10);
                btn.classList.remove('active');

                const isAlreadySet = existingTypes.includes(String(days));

                if (diffDays < days || isAlreadySet) {
                    btn.disabled = true;
                    btn.title = isAlreadySet ? 'Pengingat ini sudah diatur' : 'Tanggal jatuh tempo terlalu dekat';
                } else {
                    btn.disabled = false;
                    btn.title = '';
                    if (!firstAvailable) firstAvailable = btn;
                }
            });

            if (firstAvailable) {
                firstAvailable.classList.add('active');
                reminderDaysInput.value = firstAvailable.dataset.days;
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan Pengingat';
            } else {
                reminderDaysInput.value = '';
                submitBtn.disabled = true;
                submitBtn.textContent = 'Opsi pengingat tidak tersedia';
            }
        }

        function lockVehicleTypeButtons(activeType) {
            btnMotor.classList.remove('locked', 'active');
            btnMobil.classList.remove('locked', 'active');

            if (activeType === 'motor') {
                btnMotor.classList.add('active');
                btnMobil.classList.add('locked');
                vehicleTypeInput.value = 'motor';
            } else if (activeType === 'mobil') {
                btnMobil.classList.add('active');
                btnMotor.classList.add('locked');
                vehicleTypeInput.value = 'mobil';
            }
        }

        function resetVehicleTypeButtons() {
            btnMotor.classList.remove('active', 'locked');
            btnMobil.classList.remove('active', 'locked');
            vehicleTypeInput.value = '';
        }

        // Initialize form
        resetVehicleTypeButtons();
        updateReminderOptions(null);

        // Vehicle Select Change
        vehicleSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            activeReminderPreviewDate = null;

            if (selectedOption.value && selectedOption.dataset.taxDate) {
                taxDueDateInput.value = selectedOption.dataset.taxDate;

                const [y, m, d] = selectedOption.dataset.taxDate.split('-').map(Number);
                const dueDate = new Date(y, m - 1, d);

                taxDueDateDisplay.value = dueDate.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                taxDueDateDisplay.style.color = '#374151';
                taxDueDateDisplay.style.backgroundColor = '#f9fafb';
                taxDueDateDisplay.style.borderColor = '#d1d5db';

                selectedDate = dueDate;
                selectedDate.setHours(0, 0, 0, 0);
                currentDate = new Date(y, m - 1, 1);

                const vehicleType = selectedOption.dataset.type;
                if (vehicleType) {
                    lockVehicleTypeButtons(vehicleType);
                }

                updateReminderOptions(selectedOption.dataset.taxDate, selectedOption.value);
            } else {
                taxDueDateInput.value = '';
                taxDueDateDisplay.value = '';
                taxDueDateDisplay.placeholder = 'Pilih kendaraan terlebih dahulu';
                taxDueDateDisplay.style.color = '#9ca3af';
                taxDueDateDisplay.style.backgroundColor = '#f3f4f6';
                taxDueDateDisplay.style.borderColor = '#e5e7eb';

                selectedDate = null;
                activeReminderPreviewDate = null;
                updateReminderOptions(null);
                resetVehicleTypeButtons();
            }

            updateMonthYear();
            renderCalendar();
            renderMiniCalendar();
        });

        // Toggle buttons
        btnMotor.addEventListener('click', function() {
            if (this.classList.contains('locked')) return;
            lockVehicleTypeButtons('motor');
        });

        btnMobil.addEventListener('click', function() {
            if (this.classList.contains('locked')) return;
            lockVehicleTypeButtons('mobil');
        });

        // Reminder Days Toggle
        document.querySelectorAll('.reminder-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.disabled) return;
                document.querySelectorAll('.reminder-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                reminderDaysInput.value = this.dataset.days;

                const dueDateStr = taxDueDateInput.value;
                if (dueDateStr) {
                    const dueDate = new Date(dueDateStr);
                    activeReminderPreviewDate = new Date(dueDate);
                    activeReminderPreviewDate.setDate(dueDate.getDate() - parseInt(this.dataset.days, 10));
                    activeReminderPreviewDate.setHours(0, 0, 0, 0);
                } else {
                    activeReminderPreviewDate = null;
                }
                renderMiniCalendar();
            });
        });

        // Calendar Navigation
        document.getElementById('prevMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateMonthYear();
            renderCalendar();
            renderMiniCalendar();
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateMonthYear();
            renderCalendar();
            renderMiniCalendar();
        });

        // Search
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.rm-event-block').forEach(block => {
                block.style.opacity = block.textContent.toLowerCase().includes(query) ? '1' : '0.3';
            });
        });

        // Cancel Button
        document.getElementById('btnCancel').addEventListener('click', function() {
            vehicleSelect.value = '';
            document.getElementById('reminderForm').reset();
            selectedDate = null;
            activeReminderPreviewDate = null;

            taxDueDateInput.value = '';
            taxDueDateDisplay.value = '';
            taxDueDateDisplay.placeholder = 'Pilih kendaraan terlebih dahulu';
            taxDueDateDisplay.style.color = '#9ca3af';
            taxDueDateDisplay.style.backgroundColor = '#f3f4f6';
            taxDueDateDisplay.style.borderColor = '#e5e7eb';

            resetVehicleTypeButtons();
            updateReminderOptions(null);
            currentDate = new Date(today.getFullYear(), today.getMonth(), 1);

            updateMonthYear();
            renderCalendar();
            renderMiniCalendar();
        });

        // Add Button
        document.getElementById('btnAddReminder').addEventListener('click', function() {
            vehicleSelect.focus();
        });

        // Form Validation
        document.getElementById('reminderForm').addEventListener('submit', function(e) {
            if (!vehicleSelect.value) {
                e.preventDefault();
                alert('Silakan pilih kendaraan terlebih dahulu.');
                vehicleSelect.focus();
            }
        });

        // Calendar Functions
        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            let html = '';

            for (let i = firstDay - 1; i >= 0; i--) {
                const day = daysInPrevMonth - i;
                const date = new Date(year, month - 1, day);
                html += generateCalendarCell(date, day, true);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                html += generateCalendarCell(date, day, false);
            }

            const totalCells = firstDay + daysInMonth;
            const remainingCells = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);

            for (let day = 1; day <= remainingCells; day++) {
                const date = new Date(year, month + 1, day);
                html += generateCalendarCell(date, day, true);
            }

            calendarGrid.innerHTML = html;
        }

        function generateCalendarCell(date, day, isMuted) {
            const dateStr = getLocalISODate(date);
            const dateTime = date.getTime();
            const isToday = dateTime === today.getTime();
            const isSelected = selectedDate && dateTime === selectedDate.getTime();

            const dueEventsOnDate = reminders.filter(r => r.tanggal?.substring(0, 10) === dateStr);
            const uniqueDueEvents = [];
            const seenVehicles = new Set();
            dueEventsOnDate.forEach(event => {
                if (!seenVehicles.has(event.vehicle_id)) {
                    seenVehicles.add(event.vehicle_id);
                    uniqueDueEvents.push(event);
                }
            });

            const hasDueEvent = uniqueDueEvents.length > 0;
            const reminderEventsOnDate = reminderEvents.filter(e => e.date === dateStr && e.type === 'reminder');
            const hasReminderEvent = reminderEventsOnDate.length > 0;
            const isOverdue = hasDueEvent && dateTime < today.getTime();

            let cellClass = 'rm-cal-cell';
            if (isToday) cellClass += ' today';
            if (isSelected) cellClass += ' selected';
            if (hasDueEvent) cellClass += ' has-event';

            let html = `<div class="${cellClass}" data-date="${dateStr}">`;
            html += '<div class="rm-date-top">';

            if (hasDueEvent && !isMuted) {
                html += '<div class="rm-event-dot" title="Jatuh Tempo"></div>';
            }
            if (hasReminderEvent && !isMuted) {
                html += '<div class="rm-event-dot reminder-dot" title="Pengingat Aktif"></div>';
            }

            html += `<div class="rm-date-num${isMuted ? ' muted' : ''}">${day}</div>`;
            html += '</div>';

            if (hasDueEvent && !isMuted) {
                uniqueDueEvents.forEach(event => {
                    const overdueClass = isOverdue ? ' overdue' : '';
                    const noPolisi = event.vehicle?.no_polisi || event.no_polisi || 'Kendaraan';
                    const jenis = event.vehicle?.jenis || event.jenis || '';
                    html += `<div class="rm-event-block${overdueClass}" data-vehicle-id="${event.vehicle_id}" title="Klik untuk detail">`;
                    html += `Pajak ${jenis}<br>(${noPolisi})`;
                    html += '</div>';
                });
            }

            html += '</div>';
            return html;
        }

        function renderMiniCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            let html = '';

            days.forEach(day => html += `<div class="rm-mini-day">${day}</div>`);

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            for (let i = firstDay - 1; i >= 0; i--) {
                html += `<div class="rm-mini-date muted">${daysInPrevMonth - i}</div>`;
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                const dateStr = getLocalISODate(date);
                const dateTime = date.getTime();
                const isToday = dateTime === today.getTime();
                const isSelected = selectedDate && dateTime === selectedDate.getTime();
                const isPreview = activeReminderPreviewDate && dateTime === activeReminderPreviewDate.getTime();
                const hasDueEvent = reminders.some(r => r.tanggal?.substring(0, 10) === dateStr);
                const hasReminderEvent = reminderEvents.some(e => e.date === dateStr && e.type === 'reminder');

                let dateClass = 'rm-mini-date';
                if (isSelected) dateClass += ' due-date-full';
                else if (isPreview) dateClass += ' preview-outline';
                else if (isToday) dateClass += ' today-subtle';
                else if (hasDueEvent || hasReminderEvent) dateClass += ' has-event';

                html += `<div class="${dateClass}">${day}</div>`;
            }

            const totalCells = firstDay + daysInMonth;
            const remainingCells = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);

            for (let day = 1; day <= remainingCells; day++) {
                html += `<div class="rm-mini-date muted">${day}</div>`;
            }

            miniCalendarGrid.innerHTML = html;
        }

        function updateMonthYear() {
            currentMonthYear.textContent = `${months[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        }

        // Modal Handling
        calendarGrid.addEventListener('click', function(e) {
            const eventBlock = e.target.closest('.rm-event-block');
            if (eventBlock && eventBlock.dataset.vehicleId) {
                const vehicleId = parseInt(eventBlock.dataset.vehicleId, 10);
                const reminder = reminders.find(r => r.vehicle_id === vehicleId);

                if (reminder && reminder.vehicle) {
                    const vehicle = reminder.vehicle;
                    const dueDate = new Date(vehicle.tanggal_pajak);
                    const isOverdue = dueDate.getTime() < today.getTime();

                    document.getElementById('modalNoPolisi').textContent = vehicle.no_polisi;
                    document.getElementById('modalJenis').textContent = vehicle.jenis.charAt(0).toUpperCase() + vehicle.jenis.slice(1);
                    document.getElementById('modalWarna').textContent = vehicle.warna;
                    document.getElementById('modalTanggalPajak').textContent = dueDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });

                    const statusSpan = document.getElementById('modalStatus');
                    statusSpan.textContent = isOverdue ? 'Jatuh Tempo' : 'Aktif';
                    statusSpan.style.color = isOverdue ? '#ef4444' : '#10b981';

                    document.getElementById('modalBayarBtn').href = `/pembayaran/${vehicle.id}`;
                    eventDetailModal.style.display = 'flex';
                }
            }
        });

        document.getElementById('modalCloseBtn').addEventListener('click', () => {
            eventDetailModal.style.display = 'none';
        });

        eventDetailModal.addEventListener('click', function(e) {
            if (e.target === this) {
                eventDetailModal.style.display = 'none';
            }
        });

        // Initial Render
        processReminders();
        renderCalendar();
        renderMiniCalendar();
        updateMonthYear();
    });

    // Notification Dropdown
    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const bell = event.target.closest('[onclick="toggleNotificationDropdown()"]');

        if (!bell && dropdown && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });

    // Tandai semua sudah dibaca
    function markAllAsRead(event) {
        event.stopPropagation();

        // Hapus class unread dan dot
        const unreadItems = document.querySelectorAll('.dropdown-notif-item.unread');
        unreadItems.forEach(item => {
            item.classList.remove('unread');
            const dot = item.querySelector('.dropdown-notif-dot');
            if (dot) dot.remove();
        });

        // Hapus red dot di bell icon
        const bellDot = document.querySelector('.bell-dot, .bg-red-500.rounded-full');
        if (bellDot) bellDot.style.display = 'none';

        // Optional: Kirim request ke server
        // fetch('/notifications/mark-all-read', { method: 'POST' });
    }
</script>

</body>

</html>