<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Employee Attendance">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>My Attendance</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }
        
        /* Header */
        .app-header {
            background: linear-gradient(135deg, #6258a6 0%, #82cae8 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .app-header h1 {
            font-size: 20px;
            margin: 0;
            font-weight: 600;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-menu span {
            font-size: 14px;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        /* Tabs */
        .tab-container {
            display: flex;
            background: white;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            border: none;
            background: transparent;
            font-size: 14px;
            font-weight: 500;
            color: #666;
            position: relative;
            transition: all 0.3s;
        }
        
        .tab.active {
            color: #6258a6;
        }
        
        .tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20%;
            right: 20%;
            height: 3px;
            background: #6258a6;
            border-radius: 3px 3px 0 0;
        }
        
        /* Tab Content */
        .tab-content {
            display: none;
            padding: 20px;
            min-height: calc(100vh - 120px);
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Check-in/out Section */
        .checkin-section {
            text-align: center;
            padding: 30px 0;
        }
        
        .current-time {
            font-size: 48px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }
        
        .current-date {
            font-size: 16px;
            color: #666;
            margin-bottom: 40px;
        }
        
        .check-button {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: none;
            font-size: 20px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }
        
        .check-button::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 60%);
            animation: pulse-ring 2s ease-out infinite;
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.5); opacity: 1; }
            100% { transform: scale(1.2); opacity: 0; }
        }
        
        .check-button.check-in {
            background: linear-gradient(135deg, #e91e63 0%, #9c27b0 100%);
        }
        
        .check-button.check-out {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .check-button.completed {
            background: linear-gradient(135deg, #4caf50 0%, #8bc34a 100%);
            animation: none;
        }
        
        .check-button.completed::before {
            display: none;
        }
        
        .check-button:disabled {
            background: #ccc;
            cursor: not-allowed;
            animation: none;
        }
        
        .check-button:disabled::before {
            display: none;
        }
        
        .check-button i {
            font-size: 50px;
            margin-bottom: 10px;
        }
        
        .check-button span {
            position: relative;
            z-index: 1;
        }
        
        /* Status Info */
        .status-info {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .status-item {
            text-align: center;
        }
        
        .status-item .label {
            font-size: 12px;
            color: #999;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .status-item .value {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }
        
        .status-item .value.success {
            color: #4caf50;
        }
        
        /* Location Info */
        .location-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            background: #e3f2fd;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .location-bar i {
            color: #2196f3;
            margin-right: 8px;
        }
        
        .location-bar span {
            color: #1976d2;
            font-size: 14px;
        }
        
        /* Calendar Section */
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 10px;
        }
        
        .calendar-header button {
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            color: #6258a6;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .calendar-header button:hover {
            transform: scale(1.1);
        }
        
        .calendar-header h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            text-align: center;
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .calendar-day-header {
            font-size: 12px;
            font-weight: 600;
            color: #999;
            padding: 10px 0;
        }
        
        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            position: relative;
            transition: all 0.3s;
        }
        
        .calendar-day:hover {
            transform: scale(1.1);
        }
        
        .calendar-day.empty {
            cursor: default;
        }
        
        .calendar-day.empty:hover {
            transform: none;
        }
        
        .calendar-day.today {
            border: 2px solid #6258a6;
            font-weight: 700;
        }
        
        .calendar-day.on-time {
            background: #cddc39;
            color: #827717;
        }
        
        .calendar-day.late {
            background: #ffc107;
            color: #f57f17;
        }
        
        .calendar-day.late-in {
            background: #ffc107;
            color: #f57f17;
        }
        
        .calendar-day.early-out {
            background: #ffc107;
            color: #f57f17;
        }
        
        .calendar-day.late-and-early {
            background: #ffc107;
            color: #f57f17;
        }
        
        .calendar-day.absent {
            background: #ff9800;
            color: #e65100;
        }
        
        .calendar-day.on-leave {
            background: #ce93d8;
            color: #6a1b9a;
        }
        
        .calendar-day.weekend {
            background: #9e9e9e;
            color: #fff;
        }
        
        .calendar-day.holiday {
            background: #90caf9;
            color: #1565c0;
        }
        
        .calendar-day .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            margin-top: 3px;
        }
        
        .calendar-day .status-label {
            font-size: 8px;
            padding: 2px 4px;
            border-radius: 3px;
            margin-top: 2px;
            background: rgba(255,255,255,0.9);
            white-space: nowrap;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Stats Section */
        .stats-container {
            margin-top: 30px;
            text-align: center;
        }
        
        .stats-summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stats-summary-item {
            text-align: center;
        }
        
        .stats-summary-item .number {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }
        
        .stats-summary-item .label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .stats-chart-container {
            width: 220px;
            height: 220px;
            margin: 0 auto;
            position: relative;
        }
        
        .stats-chart-container canvas {
            width: 100% !important;
            height: 100% !important;
        }
        
        .chart-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        
        .chart-center-text .number {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }
        
        .chart-center-text .label {
            font-size: 12px;
            color: #666;
        }
        
        .stats-legend {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #666;
        }
        
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 6px;
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            flex-direction: column;
        }
        
        .loading-overlay.show {
            display: flex;
        }
        
        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #6258a6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .loading-text {
            margin-top: 15px;
            color: #666;
            font-size: 14px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Toast Notification */
        .toast-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10000;
        }
        
        .toast {
            background: #333;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
        }
        
        .toast.success {
            background: #4caf50;
        }
        
        .toast.error {
            background: #f44336;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translate(-50%, 20px);
            }
            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="app-header">
        <h1><i class="fas fa-fingerprint"></i> Attendance</h1>
        <div class="user-menu">
            <span>{{ session('attendance_user_name') }}</span>
            <button class="logout-btn" onclick="logout()">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>
    
    <!-- Tabs -->
    <div class="tab-container">
        <button class="tab active" onclick="switchTab('checkin')">
            <i class="fas fa-fingerprint"></i> Check-in/out
        </button>
        <button class="tab" onclick="switchTab('report')">
            <i class="fas fa-chart-pie"></i> All Report
        </button>
    </div>
    
    <!-- Check-in/out Tab -->
    <div id="checkin-tab" class="tab-content active">
        <div class="checkin-section">
            <div class="current-time" id="currentTime">--:--</div>
            <div class="current-date" id="currentDate">---</div>
            
            @if(!$attendanceStatus['checked_in'])
                <button class="check-button check-in" id="actionBtn" onclick="handleCheckIn()">
                    <i class="fas fa-fingerprint"></i>
                    <span>CHECK IN</span>
                </button>
            @elseif(!$attendanceStatus['checked_out'])
                <button class="check-button check-out" id="actionBtn" onclick="handleCheckOut()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>CHECK OUT</span>
                </button>
            @else
                <button class="check-button completed" disabled>
                    <i class="fas fa-check-circle"></i>
                    <span>COMPLETED</span>
                </button>
            @endif
            
            <div class="location-bar">
                <i class="fas fa-check-circle"></i>
                <span>Ready to mark attendance</span>
            </div>
            
            <div class="status-info">
                <div class="status-item">
                    <div class="label">Check In</div>
                    <div class="value" id="checkInTime">{{ $attendanceStatus['check_in_time'] ?? '--:--' }}</div>
                </div>
                <div class="status-item">
                    <div class="label">Check Out</div>
                    <div class="value" id="checkOutTime">{{ $attendanceStatus['check_out_time'] ?? '--:--' }}</div>
                </div>
                <div class="status-item">
                    <div class="label">Working Hr's</div>
                    <div class="value success" id="workingHours">{{ $attendanceStatus['working_hours'] ?? '00:00' }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Report Tab -->
    <div id="report-tab" class="tab-content">
        <div class="calendar-header">
            <button onclick="changeMonth(-1)"><i class="fas fa-chevron-left"></i></button>
            <h3 id="calendarMonth">{{ date('F Y') }}</h3>
            <button onclick="changeMonth(1)"><i class="fas fa-chevron-right"></i></button>
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
                $firstDay = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->dayOfWeek;
                $daysInMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->daysInMonth;
                $today = date('j');
            @endphp
            
            @for($i = 0; $i < $firstDay; $i++)
                <div class="calendar-day empty"></div>
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
                        case 'late_in':
                            $statusClass = 'late-in';
                            break;
                        case 'early_out':
                            $statusClass = 'early-out';
                            break;
                        case 'late_and_early':
                            $statusClass = 'late-and-early';
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
                    @php
                        $statusLabel = '';
                        switch($dayData['status']) {
                            case 'on_time':
                                $statusLabel = 'On Time';
                                break;
                            case 'late_in':
                            case 'early_out':
                            case 'late_and_early':
                                $statusLabel = 'Late';
                                break;
                            case 'absent':
                                $statusLabel = 'Absent';
                                break;
                            case 'on_leave':
                                $statusLabel = 'On Leave';
                                break;
                            case 'holiday':
                                $statusLabel = 'Holiday';
                                break;
                            case 'weekend':
                                $statusLabel = 'Weekend';
                                break;
                        }
                    @endphp
                    @if($statusLabel)
                        <div class="status-label">{{ $statusLabel }}</div>
                    @endif
                </div>
            @endfor
        </div>
        
        <div class="stats-container">
            <div class="stats-summary" id="statsSummary">
                <div class="stats-summary-item">
                    <div class="number" id="totalWorkingDays">0</div>
                    <div class="label">Working Days</div>
                </div>
                <div class="stats-summary-item">
                    <div class="number" id="totalOnTime">0</div>
                    <div class="label">On Time</div>
                </div>
                <div class="stats-summary-item">
                    <div class="number" id="totalLate">0</div>
                    <div class="label">Late</div>
                </div>
                <div class="stats-summary-item">
                    <div class="number" id="totalAbsent">0</div>
                    <div class="label">Absent</div>
                </div>
            </div>
            <div class="stats-chart-container">
                <canvas id="attendanceChart"></canvas>
                <div class="chart-center-text" id="chartCenterText">
                    <div class="number" id="centerNumber">0</div>
                    <div class="label" id="centerLabel">Days</div>
                </div>
            </div>
            <div class="stats-legend">
                <div class="legend-item">
                    <div class="legend-color" style="background: #cddc39;"></div>
                    <span>On Time</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #ffc107;"></div>
                    <span>Late</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #ff9800;"></div>
                    <span>Absent</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #9e9e9e;"></div>
                    <span>Working</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #ce93d8;"></div>
                    <span>On Leave</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
        <div class="loading-text">Processing...</div>
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            
            if (tab === 'checkin') {
                document.querySelectorAll('.tab')[0].classList.add('active');
                document.getElementById('checkin-tab').classList.add('active');
            } else {
                document.querySelectorAll('.tab')[1].classList.add('active');
                document.getElementById('report-tab').classList.add('active');
                // Small delay to ensure tab is visible before rendering chart
                setTimeout(initChart, 100);
            }
        }
        
        // Show/hide loading
        function showLoading(show, text = 'Processing...') {
            const overlay = document.getElementById('loadingOverlay');
            overlay.querySelector('.loading-text').textContent = text;
            overlay.classList.toggle('show', show);
        }
        
        // Show toast notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
        
        // Office time settings from backend
        const lateThreshold = '{{ $lateThreshold ?? "09:30" }}'; // Format: HH:mm
        const endTime = '{{ $endTime ?? "16:00" }}'; // Format: HH:mm
        
        console.log('Late Threshold:', lateThreshold);
        console.log('End Time:', endTime);
        
        // Handle Check In
        function handleCheckIn() {
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinute = now.getMinutes();
            const currentTime = `${String(currentHour).padStart(2, '0')}:${String(currentMinute).padStart(2, '0')}`;
            
            console.log('Current Time:', currentTime);
            console.log('Late Threshold:', lateThreshold);
            
            // Check if late (compare with lateThreshold from backend)
            const isLate = currentTime > lateThreshold;
            console.log('Is Late:', isLate);
            
            if (isLate) {
                // Show reason prompt for late entry
                Swal.fire({
                    title: 'Late Check-in',
                    text: `You are checking in after ${lateThreshold}. Please provide a reason:`,
                    input: 'text',
                    inputPlaceholder: 'Enter your reason...',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#6258a6',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Please enter a reason for late check-in';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitCheckIn(result.value);
                    }
                });
            } else {
                submitCheckIn(null);
            }
        }
        
        function submitCheckIn(reason) {
            console.log('Submitting check-in with reason:', reason);
            showLoading(true, 'Checking in...');
            
            fetch('{{ route("attendance-panel.checkin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"').content
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                showLoading(false);
                
                if (data.success) {
                    // Special message for SOL cheat code
                    if (data.cheat_code) {
                        Swal.fire({
                            title: '🎉 Enjoy your day!',
                            text: 'Check-in marked as on-time',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        showToast('Check-in successful!', 'success');
                    }
                    document.getElementById('checkInTime').textContent = data.check_in_time;
                    document.getElementById('workingHours').textContent = data.working_hours;
                    
                    // Change button to check-out
                    const btn = document.getElementById('actionBtn');
                    btn.className = 'check-button check-out';
                    btn.innerHTML = '<i class="fas fa-sign-out-alt"></i><span>CHECK OUT</span>';
                    btn.onclick = handleCheckOut;
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Check-in error:', error);
                showLoading(false);
                showToast('Something went wrong. Please try again.', 'error');
            });
        }
        
        // Handle Check Out
        function handleCheckOut() {
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinute = now.getMinutes();
            const currentTime = `${String(currentHour).padStart(2, '0')}:${String(currentMinute).padStart(2, '0')}`;
            
            // Check if early out (compare with endTime from backend)
            const isEarlyOut = currentTime < endTime;
            
            if (isEarlyOut) {
                // Show reason prompt for early out
                Swal.fire({
                    title: 'Early Check-out',
                    text: `You are checking out before ${endTime}. Please provide a reason:`,
                    input: 'text',
                    inputPlaceholder: 'Enter your reason...',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#6258a6',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Please enter a reason for early check-out';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitCheckOut(result.value);
                    }
                });
            } else {
                submitCheckOut(null);
            }
        }
        
        function submitCheckOut(reason) {
            showLoading(true, 'Checking out...');
            
            fetch('{{ route("attendance-panel.checkout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"').content
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                
                if (data.success) {
                    showToast('Check-out successful!', 'success');
                    document.getElementById('checkOutTime').textContent = data.check_out_time;
                    document.getElementById('workingHours').textContent = data.working_hours;
                    
                    // Change button to completed
                    const btn = document.getElementById('actionBtn');
                    btn.className = 'check-button completed';
                    btn.innerHTML = '<i class="fas fa-check-circle"></i><span>COMPLETED</span>';
                    btn.disabled = true;
                    btn.onclick = null;
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showLoading(false);
                showToast('Something went wrong. Please try again.', 'error');
            });
        }
        
        // Logout
        function logout() {
            Swal.fire({
                title: 'Logout?',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#6258a6',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("attendance-panel.logout") }}';
                }
            });
        }
        
        // Initialize Chart
        let attendanceChart = null;
        function initChart() {
            const ctx = document.getElementById('attendanceChart');
            if (!ctx) return;
            
            // Always get fresh data from current calendar
            const calendarDays = document.querySelectorAll('.calendar-day:not(.empty)');
            let onTime = 0, lateIn = 0, earlyOut = 0, lateAndEarly = 0, absent = 0, onLeave = 0, holiday = 0, weekend = 0;
            
            calendarDays.forEach(day => {
                if (day.classList.contains('on-time')) onTime++;
                else if (day.classList.contains('late-in')) lateIn++;
                else if (day.classList.contains('early-out')) earlyOut++;
                else if (day.classList.contains('late-and-early')) lateAndEarly++;
                else if (day.classList.contains('absent')) absent++;
                else if (day.classList.contains('on-leave')) onLeave++;
                else if (day.classList.contains('holiday')) holiday++;
                else if (day.classList.contains('weekend')) weekend++;
            });
            
            // Calculate summary stats (simplified for chart)
            const totalLate = lateIn + earlyOut + lateAndEarly;
            const employeeWorkingDays = onTime + totalLate + absent;
            
            // Calculate total working days in month (excluding weekends and holidays)
            const totalDaysInMonth = calendarDays.length;
            const workingDaysInMonth = totalDaysInMonth - weekend - holiday;
            
            // Update center text (total working days in month)
            document.getElementById('centerNumber').textContent = workingDaysInMonth;
            document.getElementById('centerLabel').textContent = 'Working Days';
            
            // Update summary display (total working days in month, not employee's days)
            document.getElementById('totalWorkingDays').textContent = workingDaysInMonth;
            document.getElementById('totalOnTime').textContent = onTime;
            document.getElementById('totalLate').textContent = totalLate;
            document.getElementById('totalAbsent').textContent = absent;
            
            // Simplified data for chart (4 categories)
            const chartData = [onTime, totalLate, absent, onLeave + holiday + weekend];
            const chartLabels = ['On Time', 'Late', 'Absent', 'Others'];
            const chartColors = ['#cddc39', '#ffc107', '#ff9800', '#e0e0e0']; // On Time: lime, Late: yellow, Absent: orange, Others: gray
            
            if (attendanceChart) {
                // Update existing chart with new data
                attendanceChart.data.datasets[0].data = chartData;
                attendanceChart.update();
                return;
            }
            
            attendanceChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: chartData,
                        backgroundColor: chartColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    return label + ': ' + value + ' days';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Change month
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
            
            showLoading(true, 'Loading...');
            
            fetch(`{{ route('attendance-panel.monthly-report') }}?month=${currentMonth}&year=${currentYear}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"').content
                }
            })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                
                if (data.success) {
                    updateCalendar(data.attendance, currentMonth, currentYear);
                    document.getElementById('calendarMonth').textContent = new Date(currentYear, currentMonth - 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                    
                    // Update summary stats (simplified)
                    const totalLate = (data.stats.late_in || 0) + (data.stats.early_out || 0) + (data.stats.late_and_early || 0);
                    const employeeWorkingDays = data.stats.on_time + totalLate + data.stats.absent;
                    const others = (data.stats.on_leave || 0) + (data.stats.holiday || 0) + (data.stats.weekend || 0);
                    
                    // Calculate working days in month (total days - weekends - holidays)
                    const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
                    const workingDaysInMonth = daysInMonth - (data.stats.weekend || 0) - (data.stats.holiday || 0);
                    
                    // Update center text (total working days in month)
                    document.getElementById('centerNumber').textContent = workingDaysInMonth;
                    
                    // Update summary display (total working days in month, not employee's days)
                    document.getElementById('totalWorkingDays').textContent = workingDaysInMonth;
                    document.getElementById('totalOnTime').textContent = data.stats.on_time;
                    document.getElementById('totalLate').textContent = totalLate;
                    document.getElementById('totalAbsent').textContent = data.stats.absent;
                    
                    if (attendanceChart) {
                        attendanceChart.data.datasets[0].data = [
                            data.stats.on_time,
                            totalLate,
                            data.stats.absent,
                            others
                        ];
                        attendanceChart.update();
                    }
                }
            })
            .catch(error => {
                showLoading(false);
                showToast('Failed to load data', 'error');
            });
        }
        
        function updateCalendar(attendanceData, month, year) {
            const grid = document.querySelector('.calendar-grid');
            
            // Keep headers, remove days
            const headers = Array.from(grid.querySelectorAll('.calendar-day-header'));
            grid.innerHTML = '';
            headers.forEach(h => grid.appendChild(h));
            
            // Add empty cells for first day
            const firstDay = new Date(year, month - 1, 1).getDay();
            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day empty';
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
                    case 'late_in': statusClass = 'late-in'; break;
                    case 'early_out': statusClass = 'early-out'; break;
                    case 'late_and_early': statusClass = 'late-and-early'; break;
                    case 'absent': statusClass = 'absent'; break;
                    case 'on_leave': statusClass = 'on-leave'; break;
                    case 'weekend': statusClass = 'weekend'; break;
                    case 'holiday': statusClass = 'holiday'; break;
                }
                
                // Get status label
                let statusLabel = '';
                switch(dayData.status) {
                    case 'on_time': statusLabel = 'On Time'; break;
                    case 'late_in':
                    case 'early_out':
                    case 'late_and_early': statusLabel = 'Late'; break;
                    case 'absent': statusLabel = 'Absent'; break;
                    case 'on_leave': statusLabel = 'On Leave'; break;
                    case 'holiday': statusLabel = 'Holiday'; break;
                    case 'weekend': statusLabel = 'Weekend'; break;
                }
                
                dayEl.className = `calendar-day ${statusClass} ${(isCurrentMonth && day === today) ? 'today' : ''}`;
                dayEl.innerHTML = day + (statusLabel ? `<div class="status-label">${statusLabel}</div>` : '');
                
                grid.appendChild(dayEl);
            }
        }
    </script>
</body>
</html>
