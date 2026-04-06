@include('frontend.header')
@include('frontend.navbar')

<style>
    .attendance-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .attendance-header {
        background: linear-gradient(277.57deg, #6258a6 0%, #82cae8 100%);
        color: white;
        padding: 15px;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }
    
    .attendance-header h3 {
        margin: 0;
        font-size: 20px;
    }
    
    .attendance-tabs {
        display: flex;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .attendance-tab {
        flex: 1;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        border: none;
        background: transparent;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.3s;
    }
    
    .attendance-tab.active {
        color: #6258a6;
        border-bottom: 2px solid #6258a6;
        background: white;
    }
    
    .attendance-tab:hover {
        color: #6258a6;
    }
    
    .tab-content {
        display: none;
        padding: 20px;
        background: white;
        border-radius: 0 0 10px 10px;
        min-height: 400px;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .check-in-out-section {
        text-align: center;
        padding: 30px 0;
    }
    
    .check-button {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: none;
        font-size: 18px;
        font-weight: bold;
        color: white;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .check-button.check-in {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .check-button.check-out {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .check-button:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    .check-button i {
        font-size: 40px;
        margin-bottom: 10px;
    }
    
    .current-time {
        font-size: 36px;
        font-weight: bold;
        color: #333;
        margin: 20px 0 5px;
    }
    
    .current-date {
        font-size: 16px;
        color: #666;
        margin-bottom: 30px;
    }
    
    .attendance-info {
        display: flex;
        justify-content: space-around;
        margin-top: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .info-item {
        text-align: center;
    }
    
    .info-item .time {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }
    
    .info-item .label {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }
    
    .location-info {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        background: #e3f2fd;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .location-info i {
        color: #2196f3;
        margin-right: 8px;
    }
    
    .location-info span {
        color: #1976d2;
        font-size: 14px;
    }
    
    /* Calendar Styles */
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .calendar-header button {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #6258a6;
    }
    
    .calendar-header h4 {
        margin: 0;
        color: #333;
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        text-align: center;
    }
    
    .calendar-day-header {
        font-weight: bold;
        color: #666;
        padding: 10px 0;
        font-size: 12px;
    }
    
    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        position: relative;
    }
    
    .calendar-day:hover {
        background: #f0f0f0;
    }
    
    .calendar-day.today {
        border: 2px solid #6258a6;
    }
    
    .calendar-day.on-time {
        background: #c8e6c9;
        color: #2e7d32;
    }
    
    .calendar-day.late {
        background: #fff3e0;
        color: #ef6c00;
    }
    
    .calendar-day.absent {
        background: #ffcdd2;
        color: #c62828;
    }
    
    .calendar-day.on-leave {
        background: #e1bee7;
        color: #7b1fa2;
    }
    
    .calendar-day.weekend {
        background: #f5f5f5;
        color: #999;
    }
    
    .calendar-day.holiday {
        background: #bbdefb;
        color: #1565c0;
    }
    
    .calendar-day .status-label {
        font-size: 8px;
        margin-top: 2px;
        padding: 1px 3px;
        border-radius: 3px;
        background: rgba(255,255,255,0.8);
    }
    
    .stats-section {
        margin-top: 30px;
        text-align: center;
    }
    
    .stats-chart {
        width: 200px;
        height: 200px;
        margin: 0 auto;
    }
    
    .stats-legend {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        font-size: 12px;
    }
    
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 5px;
    }
    
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loading-overlay.show {
        display: flex;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #6258a6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="attendance-container">
            <div class="attendance-header">
                <h3><i class="la la-clock"></i> Attendance</h3>
            </div>
            
            <div class="attendance-tabs">
                <button class="attendance-tab active" onclick="switchTab('checkin')">
                    <i class="la la-fingerprint"></i> Check-in-out
                </button>
                <button class="attendance-tab" onclick="switchTab('report')">
                    <i class="la la-calendar"></i> All Report
                </button>
            </div>
            
            <!-- Check-in/Check-out Tab -->
            <div id="checkin-tab" class="tab-content active">
                <div class="check-in-out-section">
                    <div class="current-time" id="currentTime">{{ date('h:i A') }}</div>
                    <div class="current-date" id="currentDate">{{ date('l, M d, Y') }}</div>
                    
                    @if(!$attendanceStatus['checked_in'])
                        <button class="check-button check-in" id="checkInBtn" onclick="handleCheckIn()">
                            <i class="la la-fingerprint"></i>
                            <span>CHECK IN</span>
                        </button>
                    @elseif(!$attendanceStatus['checked_out'])
                        <button class="check-button check-out" id="checkOutBtn" onclick="handleCheckOut()">
                            <i class="la la-sign-out"></i>
                            <span>CHECK OUT</span>
                        </button>
                    @else
                        <button class="check-button" disabled style="background: #4caf50;">
                            <i class="la la-check"></i>
                            <span>COMPLETED</span>
                        </button>
                    @endif
                    
                    <div class="location-info">
                        <i class="la la-map-marker"></i>
                        <span>Ready to check</span>
                    </div>
                    
                    <div class="attendance-info">
                        <div class="info-item">
                            <div class="time" id="checkInTime">{{ $attendanceStatus['check_in_time'] ?? '--:--' }}</div>
                            <div class="label">Check In</div>
                        </div>
                        <div class="info-item">
                            <div class="time" id="checkOutTime">{{ $attendanceStatus['check_out_time'] ?? '--:--' }}</div>
                            <div class="label">Check Out</div>
                        </div>
                        <div class="info-item">
                            <div class="time" id="workingHours">{{ $attendanceStatus['working_hours'] ?? '00:00' }}</div>
                            <div class="label">Working Hr's</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- All Report Tab -->
            <div id="report-tab" class="tab-content">
                <div class="calendar-header">
                    <button onclick="changeMonth(-1)"><i class="la la-chevron-left"></i></button>
                    <h4 id="calendarMonth">{{ date('F Y') }}</h4>
                    <button onclick="changeMonth(1)"><i class="la la-chevron-right"></i></button>
                </div>
                
                <div class="calendar-grid">
                    <div class="calendar-day-header">SU</div>
                    <div class="calendar-day-header">MO</div>
                    <div class="calendar-day-header">TU</div>
                    <div class="calendar-day-header">WE</div>
                    <div class="calendar-day-header">TH</div>
                    <div class="calendar-day-header">FR</div>
                    <div class="calendar-day-header">SA</div>
                    
                    @php
                        $firstDay = Carbon\Carbon::create($currentYear, $currentMonth, 1)->dayOfWeek;
                        $daysInMonth = Carbon\Carbon::create($currentYear, $currentMonth, 1)->daysInMonth;
                        $today = date('j');
                    @endphp
                    
                    @for($i = 0; $i < $firstDay; $i++)
                        <div class="calendar-day"></div>
                    @endfor
                    
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $dayData = $monthlyAttendance[$day] ?? ['status' => 'working', 'label' => ''];
                            $isToday = ($day == $today);
                            $statusClass = '';
                            
                            switch($dayData['status']) {
                                case 'on_time':
                                    $statusClass = 'on-time';
                                    break;
                                case 'late':
                                    $statusClass = 'late';
                                    break;
                                case 'absent':
                                    $statusClass = 'absent';
                                    break;
                                case 'on_leave':
                                    $statusClass = 'on-leave';
                                    break;
                                case 'weekend':
                                    $statusClass = 'weekend';
                                    break;
                                case 'holiday':
                                    $statusClass = 'holiday';
                                    break;
                            }
                        @endphp
                        <div class="calendar-day {{ $statusClass }} {{ $isToday ? 'today' : '' }}" data-date="{{ $dayData['date'] ?? '' }}">
                            {{ $day }}
                            @if($dayData['label'])
                                <span class="status-label">{{ $dayData['label'] }}</span>
                            @endif
                        </div>
                    @endfor
                </div>
                
                <div class="stats-section">
                    <canvas id="attendanceChart" class="stats-chart"></canvas>
                    <div class="stats-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #c8e6c9;"></div>
                            <span>On Time</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #fff3e0;"></div>
                            <span>Late</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #ffcdd2;"></div>
                            <span>Absent</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #e1bee7;"></div>
                            <span>On Leave</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
        document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'short',
            day: '2-digit'
        });
    }
    setInterval(updateTime, 1000);
    updateTime();
    
    // Tab switching
    function switchTab(tab) {
        document.querySelectorAll('.attendance-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        
        if (tab === 'checkin') {
            document.querySelectorAll('.attendance-tab')[0].classList.add('active');
            document.getElementById('checkin-tab').classList.add('active');
        } else {
            document.querySelectorAll('.attendance-tab')[1].classList.add('active');
            document.getElementById('report-tab').classList.add('active');
            initChart();
        }
    }
    
    // Show/hide loading
    function showLoading(show) {
        document.getElementById('loadingOverlay').classList.toggle('show', show);
    }
    
    // Handle Check In
    function handleCheckIn() {
        showLoading(true);
        
        fetch('/api/check-in', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            showLoading(false);
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Check-in Successful!',
                    text: 'You have successfully checked in at ' + data.check_in_time,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            showLoading(false);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.'
            });
        });
    }
    
    // Handle Check Out
    function handleCheckOut() {
        showLoading(true);
        
        fetch('/api/check-out', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            showLoading(false);
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Check-out Successful!',
                    text: 'You have successfully checked out at ' + data.check_out_time,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            showLoading(false);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.'
            });
        });
    }
    
    // Initialize Chart
    let attendanceChart = null;
    function initChart() {
        if (attendanceChart) return;
        
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Count statuses from calendar
        const calendarDays = document.querySelectorAll('.calendar-day:not(:empty)');
        let onTime = 0, late = 0, absent = 0, onLeave = 0;
        
        calendarDays.forEach(day => {
            if (day.classList.contains('on-time')) onTime++;
            else if (day.classList.contains('late')) late++;
            else if (day.classList.contains('absent')) absent++;
            else if (day.classList.contains('on-leave')) onLeave++;
        });
        
        attendanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['On Time', 'Late', 'Absent', 'On Leave'],
                datasets: [{
                    data: [onTime, late, absent, onLeave],
                    backgroundColor: [
                        '#c8e6c9',
                        '#fff3e0',
                        '#ffcdd2',
                        '#e1bee7'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    // Change month (placeholder for future implementation)
    let currentMonth = {{ $currentMonth }};
    let currentYear = {{ $currentYear }};
    
    function changeMonth(direction) {
        currentMonth += direction;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        } else if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        
        // Fetch new month data
        fetch(`/api/monthly-attendance-report?month=${currentMonth}&year=${currentYear}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update calendar
                updateCalendar(data.attendance, currentMonth, currentYear);
                document.getElementById('calendarMonth').textContent = new Date(currentYear, currentMonth - 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                
                // Update chart
                if (attendanceChart) {
                    attendanceChart.data.datasets[0].data = [
                        data.stats.on_time,
                        data.stats.late,
                        data.stats.absent,
                        data.stats.on_leave
                    ];
                    attendanceChart.update();
                }
            }
        });
    }
    
    function updateCalendar(attendanceData, month, year) {
        const grid = document.querySelector('.calendar-grid');
        const dayHeaders = grid.querySelectorAll('.calendar-day-header');
        
        // Clear existing days
        const existingDays = grid.querySelectorAll('.calendar-day');
        existingDays.forEach(day => day.remove());
        
        // Add empty cells for first day
        const firstDay = new Date(year, month - 1, 1).getDay();
        for (let i = 0; i < firstDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day';
            grid.appendChild(emptyDay);
        }
        
        // Add days
        const daysInMonth = new Date(year, month, 0).getDate();
        const today = new Date().getDate();
        const isCurrentMonth = (month === new Date().getMonth() + 1 && year === new Date().getFullYear());
        
        for (let day = 1; day <= daysInMonth; day++) {
            const dayData = attendanceData[day] || { status: 'working', label: '' };
            const dayEl = document.createElement('div');
            
            let statusClass = '';
            switch(dayData.status) {
                case 'on_time': statusClass = 'on-time'; break;
                case 'late': statusClass = 'late'; break;
                case 'absent': statusClass = 'absent'; break;
                case 'on_leave': statusClass = 'on-leave'; break;
                case 'weekend': statusClass = 'weekend'; break;
                case 'holiday': statusClass = 'holiday'; break;
            }
            
            dayEl.className = `calendar-day ${statusClass} ${(isCurrentMonth && day === today) ? 'today' : ''}`;
            dayEl.innerHTML = day + (dayData.label ? `<span class="status-label">${dayData.label}</span>` : '');
            
            grid.appendChild(dayEl);
        }
    }
</script>

</body>
</html>
