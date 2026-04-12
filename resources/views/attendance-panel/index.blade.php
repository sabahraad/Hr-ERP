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
        
        /* Mobile Responsive Styles */
        @media (max-width: 480px) {
            .app-header {
                padding: 12px 15px;
            }
            
            .app-header h1 {
                font-size: 16px;
            }
            
            .user-menu span {
                font-size: 12px;
                max-width: 100px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            .logout-btn {
                padding: 6px 12px;
                font-size: 12px;
            }
            
            .tab {
                padding: 12px 10px;
                font-size: 13px;
            }
            
            .tab-content {
                padding: 15px;
            }
            
            .current-time {
                font-size: 36px;
            }
            
            .current-date {
                font-size: 14px;
            }
            
            .check-button {
                width: 160px;
                height: 160px;
                font-size: 16px;
            }
            
            .check-button i {
                font-size: 40px;
            }
            
            .status-info {
                padding: 15px;
                margin-top: 25px;
            }
            
            .status-item .value {
                font-size: 16px;
            }
            
            .status-item .label {
                font-size: 11px;
            }
            
            .calendar-grid {
                gap: 4px;
                padding: 10px;
            }
            
            .calendar-day {
                font-size: 12px;
            }
            
            .calendar-day .status-label {
                font-size: 7px;
                padding: 1px 2px;
            }
            
            .stats-summary {
                padding: 12px;
            }
            
            .stats-summary-item .number {
                font-size: 20px;
            }
            
            .stats-summary-item .label {
                font-size: 11px;
            }
            
            .stats-chart-container {
                width: 180px;
                height: 180px;
            }
            
            .chart-center-text .number {
                font-size: 24px;
            }
            
            .stats-legend {
                gap: 10px;
            }
            
            .legend-item {
                font-size: 12px;
            }
        }
        
        @media (max-width: 360px) {
            .calendar-day {
                font-size: 10px;
            }
            
            .calendar-day .status-label {
                font-size: 6px;
            }
            
            .stats-summary {
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .stats-summary-item {
                flex: 1;
                min-width: 70px;
            }
        }
        
        /* Touch device optimizations */
        @media (hover: none) {
            .check-button:active {
                transform: scale(0.95);
            }
            
            .tab:active {
                background: rgba(98, 88, 166, 0.1);
            }
        }
        
        /* Leave Tab Styles */
        .leave-screen {
            display: none;
            min-height: calc(100vh - 120px);
            position: relative;
        }

        .leave-screen.active {
            display: block;
        }

        .leave-header {
            background: #6258a6;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .leave-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }

        .back-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 5px;
        }

        /* Leave List Screen */
        .leave-list-container {
            padding: 15px;
            padding-bottom: 100px;
        }

        .leave-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .leave-card-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            background: #e0e0e0;
        }

        .leave-card-info {
            flex: 1;
        }

        .leave-card-name {
            font-weight: 600;
            color: #333;
            font-size: 15px;
        }

        .leave-card-type {
            color: #4caf50;
            font-size: 13px;
        }

        .leave-card-date {
            color: #888;
            font-size: 12px;
            margin-top: 3px;
        }

        .leave-card-status {
            text-align: right;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.approved {
            background: #e8f5e9;
            color: #4caf50;
        }

        .status-badge.pending {
            background: #fff3e0;
            color: #ff9800;
        }

        .status-badge.rejected {
            background: #ffebee;
            color: #f44336;
        }

        .view-btn {
            background: transparent;
            border: none;
            color: #6258a6;
            font-size: 12px;
            cursor: pointer;
            margin-top: 5px;
        }

        .fab-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #6258a6;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(98, 88, 166, 0.4);
            transition: transform 0.2s;
            z-index: 100;
        }

        .fab-button:active {
            transform: scale(0.95);
        }

        /* Leave Types Screen */
        .leave-types-container {
            padding: 20px;
            padding-bottom: 100px;
        }

        .section-title {
            color: #333;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .leave-type-item {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .leave-type-item:hover {
            border-color: #6258a6;
        }

        .leave-type-item.selected {
            border-color: #6258a6;
            background: #f3f0ff;
        }

        .leave-type-radio {
            width: 22px;
            height: 22px;
            border: 2px solid #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .leave-type-item.selected .leave-type-radio {
            border-color: #6258a6;
            background: #6258a6;
        }

        .leave-type-radio::after {
            content: '';
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            display: none;
        }

        .leave-type-item.selected .leave-type-radio::after {
            display: block;
        }

        .leave-type-info {
            flex: 1;
        }

        .leave-type-name {
            font-weight: 500;
            color: #333;
            font-size: 15px;
        }

        .leave-type-days {
            color: #888;
            font-size: 13px;
            margin-top: 2px;
        }

        .leave-type-icon {
            width: 50px;
            height: 50px;
            background: #ffebee;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #e91e63;
            flex-shrink: 0;
        }

        .next-btn {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #6258a6;
            color: white;
            border: none;
            padding: 18px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            z-index: 100;
        }

        .next-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Date Selection Screen */
        .calendar-container {
            padding: 20px;
            padding-bottom: 100px;
        }

        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-nav button {
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            color: #6258a6;
        }

        .calendar-nav span {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .weekday-headers {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            margin-bottom: 10px;
        }

        .weekday-headers span {
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }

        .weekday-headers span.weekend {
            color: #e91e63;
        }

        .leave-calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .calendar-day-cell {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            color: #333;
            font-weight: 500;
        }

        .calendar-day-cell:hover:not(.disabled):not(.weekend):not(.selected):not(.in-range) {
            background: #f0f0f0;
        }

        .calendar-day-cell.selected {
            background: #2196f3;
            color: white;
        }

        .calendar-day-cell.in-range {
            background: #64b5f6;
            color: white;
        }

        .calendar-day-cell.weekend {
            color: #e91e63;
            cursor: default;
        }

        .calendar-day-cell.disabled {
            color: #ccc;
            cursor: default;
        }

        .calendar-day-cell.empty {
            cursor: default;
        }

        /* Request Form Screen */
        .request-form-container {
            padding: 20px;
            padding-bottom: 100px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            resize: none;
            font-family: inherit;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #6258a6;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            border: 2px dashed #ccc;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .file-upload-label:hover {
            border-color: #6258a6;
            background: #f9f9f9;
        }

        .file-upload-label i {
            font-size: 24px;
            color: #999;
            margin-bottom: 10px;
        }

        .file-upload-label span {
            color: #666;
            font-size: 14px;
        }

        .file-name {
            display: block;
            margin-top: 10px;
            color: #6258a6;
            font-size: 13px;
        }

        .submit-btn {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #6258a6;
            color: white;
            border: none;
            padding: 18px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            z-index: 100;
        }
        
        .text-center {
            text-align: center;
        }
        
        .p-4 {
            padding: 20px;
        }
        
        .text-muted {
            color: #888;
        }
        
        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }
        
        .modal-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 400px;
            max-height: 80vh;
            overflow-y: auto;
            animation: modalSlideUp 0.3s ease;
        }
        
        @keyframes modalSlideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .modal-header {
            background: #6258a6;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 15px 15px 0 0;
        }
        
        .modal-header h3 {
            margin: 0;
            font-size: 18px;
        }
        
        .modal-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            line-height: 1;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #666;
            font-size: 14px;
        }
        
        .detail-value {
            color: #333;
            font-size: 14px;
            font-weight: 500;
            text-align: right;
        }
        
        .detail-value.status-approved {
            color: #4caf50;
        }
        
        .detail-value.status-pending {
            color: #ff9800;
        }
        
        .detail-value.status-rejected {
            color: #f44336;
        }
        
        .reason-box {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
        }
        
        .reason-box label {
            color: #666;
            font-size: 12px;
            display: block;
            margin-bottom: 5px;
        }
        
        .reason-box p {
            color: #333;
            font-size: 14px;
            margin: 0;
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
        <button class="tab" onclick="switchTab('leave')">
            <i class="fas fa-calendar-alt"></i> Leave
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
    
    <!-- Leave Tab -->
    <div id="leave-tab" class="tab-content">
        <!-- Screen 1: My Leave Requests List -->
        <div id="leave-list-screen" class="leave-screen active">
            <div class="leave-header">
                <h3>All Leave Request</h3>
            </div>
            <div class="leave-list-container" id="leaveRequestsList">
                <div class="text-center p-4 text-muted">Loading...</div>
            </div>
            <button class="fab-button" onclick="showLeaveTypeScreen()">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        <!-- Screen 2: Select Leave Type -->
        <div id="leave-type-screen" class="leave-screen">
            <div class="leave-header">
                <button class="back-btn" onclick="showLeaveListScreen()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h3>All Leave Request</h3>
            </div>
            <div class="leave-types-container">
                <h4 class="section-title">Leave Types</h4>
                <div id="leaveTypesList">
                    <!-- Leave types will be loaded here -->
                </div>
            </div>
            <button class="next-btn" id="leaveTypeNextBtn" onclick="showDateSelectionScreen()" disabled>
                Next
            </button>
        </div>

        <!-- Screen 3: Select Date -->
        <div id="date-selection-screen" class="leave-screen">
            <div class="leave-header">
                <button class="back-btn" onclick="showLeaveTypeScreen()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h3>Select Date</h3>
            </div>
            <div class="calendar-container">
                <div class="calendar-nav">
                    <button onclick="changeLeaveMonth(-1)"><i class="fas fa-chevron-left"></i></button>
                    <span id="leaveCalendarMonth">December 2024</span>
                    <button onclick="changeLeaveMonth(1)"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="weekday-headers">
                    <span>SU</span><span>MO</span><span>TU</span><span>WE</span>
                    <span>TH</span><span class="weekend">FR</span><span class="weekend">SA</span>
                </div>
                <div class="leave-calendar-grid" id="leaveCalendarGrid">
                    <!-- Calendar days will be generated here -->
                </div>
            </div>
            <button class="next-btn" id="dateNextBtn" onclick="showRequestFormScreen()" disabled>
                Next
            </button>
        </div>

        <!-- Screen 4: Request Leave Form -->
        <div id="request-form-screen" class="leave-screen">
            <div class="leave-header">
                <button class="back-btn" onclick="showDateSelectionScreen()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h3>Request Leave</h3>
            </div>
            <div class="request-form-container">
                <div class="form-group">
                    <label>Note*</label>
                    <textarea id="leaveReason" placeholder="Write Reason" rows="4"></textarea>
                </div>
                <div class="form-group file-upload">
                    <label for="leaveFile" class="file-upload-label">
                        <i class="fas fa-upload"></i>
                        <span>Upload Your File</span>
                    </label>
                    <input type="file" id="leaveFile" accept=".jpg,.jpeg,.png,.pdf" hidden>
                    <span id="fileName" class="file-name"></span>
                </div>
            </div>
            <button class="submit-btn" onclick="submitLeaveRequest()">
                Submit Leave
            </button>
        </div>
    </div>
    
    <!-- Leave Details Modal -->
    <div id="leaveDetailsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Leave Details</h3>
                <button class="modal-close" onclick="closeLeaveModal()">&times;</button>
            </div>
            <div class="modal-body" id="leaveModalBody">
                <!-- Content will be loaded here -->
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
            } else if (tab === 'report') {
                document.querySelectorAll('.tab')[1].classList.add('active');
                document.getElementById('report-tab').classList.add('active');
                // Small delay to ensure tab is visible before rendering chart
                setTimeout(initChart, 100);
            } else if (tab === 'leave') {
                document.querySelectorAll('.tab')[2].classList.add('active');
                document.getElementById('leave-tab').classList.add('active');
                loadLeaveRequests();
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
        
        // ==================== LEAVE APPLICATION FUNCTIONS ====================

        let selectedLeaveType = null;
        let selectedDates = [];
        let leaveStartDate = null;
        let leaveEndDate = null;
        let leaveCurrentMonth = new Date().getMonth() + 1;
        let leaveCurrentYear = new Date().getFullYear();

        // Screen Navigation
        function showLeaveListScreen() {
            document.querySelectorAll('.leave-screen').forEach(s => s.classList.remove('active'));
            document.getElementById('leave-list-screen').classList.add('active');
            // Reset date selection
            leaveStartDate = null;
            leaveEndDate = null;
            selectedDates = [];
            loadLeaveRequests();
        }

        function showLeaveTypeScreen() {
            document.querySelectorAll('.leave-screen').forEach(s => s.classList.remove('active'));
            document.getElementById('leave-type-screen').classList.add('active');
            loadLeaveTypes();
        }

        function showDateSelectionScreen() {
            if (!selectedLeaveType) return;
            document.querySelectorAll('.leave-screen').forEach(s => s.classList.remove('active'));
            document.getElementById('date-selection-screen').classList.add('active');
            // Reset date selection when entering screen
            leaveStartDate = null;
            leaveEndDate = null;
            selectedDates = [];
            document.getElementById('dateNextBtn').disabled = true;
            renderLeaveCalendar();
        }

        function showRequestFormScreen() {
            if (selectedDates.length === 0) return;
            document.querySelectorAll('.leave-screen').forEach(s => s.classList.remove('active'));
            document.getElementById('request-form-screen').classList.add('active');
        }

        // Load Leave Requests
        async function loadLeaveRequests() {
            try {
                showLoading(true, 'Loading...');
                const response = await fetch('{{ route("attendance-panel.my-leave-requests") }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                showLoading(false);
                
                if (data.success) {
                    renderLeaveRequests(data.data);
                } else {
                    document.getElementById('leaveRequestsList').innerHTML = '<div class="text-center p-4 text-muted">No leave requests found</div>';
                }
            } catch (error) {
                showLoading(false);
                document.getElementById('leaveRequestsList').innerHTML = '<div class="text-center p-4 text-muted">Failed to load leave requests</div>';
            }
        }

        function renderLeaveRequests(requests) {
            const container = document.getElementById('leaveRequestsList');
            
            if (requests.length === 0) {
                container.innerHTML = '<div class="text-center p-4 text-muted">No leave requests found</div>';
                return;
            }
            
            container.innerHTML = requests.map(req => {
                const statusClass = req.status_label.toLowerCase();
                const startDate = new Date(req.start_date);
                const endDate = new Date(req.end_date);
                const dateStr = `${startDate.toLocaleDateString('en-US', { day: 'numeric', month: 'long' })} to ${endDate.toLocaleDateString('en-US', { day: 'numeric', month: 'long' })} (${endDate.getFullYear()})`;
                
                return `
                    <div class="leave-card" 
                         data-leave-id="${req.leave_application_id}"
                         data-leave-type="${req.leave_type}"
                         data-start-date="${req.start_date}"
                         data-end-date="${req.end_date}"
                         data-status="${req.status}"
                         data-status-label="${req.status_label}"
                         data-reason="${req.reason || ''}"
                         data-count="${req.count}">
                        <div class="leave-card-avatar" style="display:flex;align-items:center;justify-content:center;background:#6258a6;color:white;font-weight:bold;font-size:20px;">
                            {{ substr(session('attendance_user_name'), 0, 1) }}
                        </div>
                        <div class="leave-card-info">
                            <div class="leave-card-name">{{ session('attendance_user_name') }} <span style="color: #4caf50">(${req.leave_type})</span></div>
                            <div class="leave-card-date">${dateStr}</div>
                        </div>
                        <div class="leave-card-status">
                            <span class="status-badge ${statusClass}">${req.status_label}</span>
                            <button class="view-btn" onclick="viewLeaveDetails(${req.leave_application_id})">View</button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function viewLeaveDetails(id) {
            // Find the leave request from the loaded data
            const leaveCard = document.querySelector(`[data-leave-id="${id}"]`);
            if (!leaveCard) return;
            
            const leaveType = leaveCard.dataset.leaveType;
            const startDate = leaveCard.dataset.startDate;
            const endDate = leaveCard.dataset.endDate;
            const status = leaveCard.dataset.status;
            const statusLabel = leaveCard.dataset.statusLabel;
            const reason = leaveCard.dataset.reason || 'No reason provided';
            const count = leaveCard.dataset.count;
            
            const statusClass = status == 1 ? 'status-approved' : (status == 2 ? 'status-rejected' : 'status-pending');
            
            const modalBody = document.getElementById('leaveModalBody');
            modalBody.innerHTML = `
                <div class="detail-row">
                    <span class="detail-label">Leave Type</span>
                    <span class="detail-value">${leaveType}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Start Date</span>
                    <span class="detail-value">${formatDateLong(startDate)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">End Date</span>
                    <span class="detail-value">${formatDateLong(endDate)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Days</span>
                    <span class="detail-value">${count} day(s)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value ${statusClass}">${statusLabel}</span>
                </div>
                <div class="reason-box">
                    <label>Reason</label>
                    <p>${reason}</p>
                </div>
            `;
            
            document.getElementById('leaveDetailsModal').style.display = 'flex';
        }
        
        function closeLeaveModal() {
            document.getElementById('leaveDetailsModal').style.display = 'none';
        }
        
        function formatDateLong(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', { 
                weekday: 'short', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }
        
        // Close modal when clicking outside
        document.getElementById('leaveDetailsModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLeaveModal();
            }
        });

        // Load Leave Types
        async function loadLeaveTypes() {
            try {
                showLoading(true, 'Loading...');
                const response = await fetch('{{ route("attendance-panel.leave-types") }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                showLoading(false);
                
                if (data.success) {
                    renderLeaveTypes(data.data);
                }
            } catch (error) {
                showLoading(false);
                showToast('Failed to load leave types', 'error');
            }
        }

        function renderLeaveTypes(types) {
            const container = document.getElementById('leaveTypesList');
            
            if (types.length === 0) {
                container.innerHTML = '<div class="text-center p-4 text-muted">No leave types available</div>';
                return;
            }
            
            container.innerHTML = types.map((type, index) => `
                <div class="leave-type-item" onclick="selectLeaveType(${type.leave_setting_id}, '${type.leave_type}', ${type.available_days}, this)">
                    <div class="leave-type-radio"></div>
                    <div class="leave-type-info">
                        <div class="leave-type-name">${type.leave_type}</div>
                        <div class="leave-type-days">${type.available_days} days available</div>
                    </div>
                    <div class="leave-type-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            `).join('');
        }

        function selectLeaveType(id, name, days, element) {
            if (days <= 0) {
                showToast('No available days for this leave type', 'error');
                return;
            }
            selectedLeaveType = { id, name, days };
            document.querySelectorAll('.leave-type-item').forEach(item => item.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('leaveTypeNextBtn').disabled = false;
        }

        // Calendar Functions
        function renderLeaveCalendar() {
            const grid = document.getElementById('leaveCalendarGrid');
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'];
            
            document.getElementById('leaveCalendarMonth').textContent = 
                `${monthNames[leaveCurrentMonth - 1]} ${leaveCurrentYear}`;
            
            const firstDay = new Date(leaveCurrentYear, leaveCurrentMonth - 1, 1).getDay();
            const daysInMonth = new Date(leaveCurrentYear, leaveCurrentMonth, 0).getDate();
            
            let html = '';
            
            // Empty cells for days before the first day of month
            for (let i = 0; i < firstDay; i++) {
                html += '<div class="calendar-day-cell empty"></div>';
            }
            
            // Days
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(leaveCurrentYear, leaveCurrentMonth - 1, day);
                const dateStr = formatDateForComparison(date);
                const dayOfWeek = date.getDay();
                const isWeekend = dayOfWeek === 5 || dayOfWeek === 6; // Friday = 5, Saturday = 6
                
                // Check if date is in selected range
                let isSelected = false;
                let isInRange = false;
                
                if (leaveStartDate && !leaveEndDate) {
                    // Only start date selected
                    isSelected = dateStr === leaveStartDate;
                } else if (leaveStartDate && leaveEndDate) {
                    // Both selected - check if in range
                    const start = new Date(leaveStartDate);
                    const end = new Date(leaveEndDate);
                    const current = new Date(dateStr);
                    
                    isSelected = dateStr === leaveStartDate || dateStr === leaveEndDate;
                    isInRange = current > start && current < end;
                }
                
                let classes = 'calendar-day-cell';
                if (isWeekend) {
                    classes += ' weekend';
                } else if (isSelected) {
                    classes += ' selected';
                } else if (isInRange) {
                    classes += ' in-range';
                }
                
                const clickable = !isWeekend ? `onclick="handleDateClick('${dateStr}')"` : '';
                html += `<div class="${classes}" ${clickable}>${day}</div>`;
            }
            
            grid.innerHTML = html;
            updateDateNextButton();
        }

        function formatDateForComparison(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function handleDateClick(dateStr) {
            if (!leaveStartDate) {
                // First click - set start date
                leaveStartDate = dateStr;
                leaveEndDate = null;
                selectedDates = [dateStr];
            } else if (!leaveEndDate) {
                // Second click - set end date
                if (dateStr < leaveStartDate) {
                    // If clicked date is before start, swap them
                    leaveEndDate = leaveStartDate;
                    leaveStartDate = dateStr;
                } else {
                    leaveEndDate = dateStr;
                }
                // Generate all dates in range
                generateDateRange();
            } else {
                // Third click - reset and start new selection
                leaveStartDate = dateStr;
                leaveEndDate = null;
                selectedDates = [dateStr];
            }
            
            renderLeaveCalendar();
        }

        function generateDateRange() {
            selectedDates = [];
            const start = new Date(leaveStartDate);
            const end = new Date(leaveEndDate);
            const current = new Date(start);
            
            while (current <= end) {
                selectedDates.push(formatDateForComparison(current));
                current.setDate(current.getDate() + 1);
            }
        }

        function updateDateNextButton() {
            document.getElementById('dateNextBtn').disabled = selectedDates.length === 0;
        }

        function changeLeaveMonth(direction) {
            leaveCurrentMonth += direction;
            if (leaveCurrentMonth > 12) {
                leaveCurrentMonth = 1;
                leaveCurrentYear++;
            } else if (leaveCurrentMonth < 1) {
                leaveCurrentMonth = 12;
                leaveCurrentYear--;
            }
            renderLeaveCalendar();
        }

        // File Upload
        document.getElementById('leaveFile')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            document.getElementById('fileName').textContent = fileName;
        });

        // Submit Leave Request
        async function submitLeaveRequest() {
            const reason = document.getElementById('leaveReason').value.trim();
            const fileInput = document.getElementById('leaveFile');
            
            if (!reason) {
                showToast('Please enter a reason', 'error');
                return;
            }
            
            if (!selectedLeaveType || selectedDates.length === 0) {
                showToast('Please complete all steps', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('leave_setting_id', selectedLeaveType.id);
            formData.append('start_date', leaveStartDate || selectedDates[0]);
            formData.append('end_date', leaveEndDate || selectedDates[selectedDates.length - 1]);
            formData.append('reason', reason);
            
            if (fileInput.files[0]) {
                formData.append('image', fileInput.files[0]);
            }
            
            try {
                showLoading(true, 'Submitting...');
                const response = await fetch('{{ route("attendance-panel.submit-leave") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                const data = await response.json();
                showLoading(false);
                
                if (data.success) {
                    showToast('Leave application submitted successfully!', 'success');
                    // Reset and go back to list
                    selectedLeaveType = null;
                    selectedDates = [];
                    leaveStartDate = null;
                    leaveEndDate = null;
                    document.getElementById('leaveReason').value = '';
                    document.getElementById('leaveFile').value = '';
                    document.getElementById('fileName').textContent = '';
                    document.getElementById('leaveTypeNextBtn').disabled = true;
                    document.getElementById('dateNextBtn').disabled = true;
                    showLeaveListScreen();
                } else {
                    showToast(data.message || 'Failed to submit', 'error');
                }
            } catch (error) {
                showLoading(false);
                showToast('Something went wrong', 'error');
            }
        }
    </script>
</body>
</html>
