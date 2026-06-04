@extends('layouts.app')

@section('title', 'Wachangiaji')
@section('page_title', 'Wachangiaji')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #FF6F00;
        --primary-light: #FFF3E0;
        --primary-dark: #E65100;
        --success: #10B981;
        --success-light: #D1FAE5;
        --warning: #F59E0B;
        --warning-light: #FEF3C7;
        --danger: #EF4444;
        --danger-light: #FEE2E2;
        --info: #3B82F6;
        --info-light: #DBEAFE;
        --text-primary: #000000;
        --text-secondary: #1F2937;
        --text-muted: #4B5563;
        --bg-light: #F9FAFB;
        --border-color: #E5E7EB;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
    }
    
    body {
        background: var(--bg-light);
        font-family: 'Inter', sans-serif;
    }
    
    /* Fix scrolling - only one scrollbar */
    .main-content {
        overflow-y: auto !important;
        height: calc(100vh - var(--topbar-h, 60px));
        padding-bottom: 30px;
    }
    
    /* Full width container */
    .contributors-container {
        width: 100%;
        padding: 24px 32px;
    }
    
    /* Header Section */
    .header-section {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 1rem;
    }
    
    .header-title h4 {
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .header-title p {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 0;
    }
    
    .btn-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        background: var(--primary);
        border: none;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-primary-custom:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }
    
    .btn-pdf {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--danger);
        background: var(--danger-light);
        border: none;
        border-radius: var(--radius-sm);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-pdf:hover {
        background: var(--danger);
        color: white;
    }
    
    .btn-excel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #1B5E20;
        background: #E8F5E9;
        border: none;
        border-radius: var(--radius-sm);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-excel:hover {
        background: #1B5E20;
        color: white;
    }
    
    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .filter-body {
        padding: 16px 20px;
    }
    
    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-end;
    }
    
    .filter-input {
        flex: 1;
        min-width: 160px;
    }
    
    .filter-input label {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 4px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .filter-input input,
    .filter-input select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-family: 'Inter', sans-serif;
        background: white;
    }
    
    .filter-input input:focus,
    .filter-input select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
    }
    
    .btn-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        background: var(--primary);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        height: 38px;
    }
    
    .btn-filter:hover {
        background: var(--primary-dark);
    }
    
    .btn-reset {
        background: var(--text-muted);
    }
    
    .btn-reset:hover {
        background: var(--text-secondary);
    }
    
    /* Single Card */
    .single-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }
    
    /* Event Header */
    .event-header {
        padding: 16px 20px;
        background: white;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .event-info h6 {
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 0.9rem;
        color: var(--text-primary);
    }
    
    .event-info small {
        font-size: 0.7rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    
    /* Stats Grid - 3 columns like dashboard */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        padding: 16px 20px;
        background: white;
        border-bottom: 1px solid var(--border-color);
    }
    
    .stat-card {
        background: var(--bg-light);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        transition: all 0.2s;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .stat-card:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
        border-color: var(--primary-light);
    }
    
    .stat-info {
        flex: 1;
    }
    
    .stat-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .stat-value {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }
    
    .stat-value small {
        font-size: 0.65rem;
        font-weight: 500;
        color: var(--text-muted);
    }
    
    .stat-value.success {
        color: var(--success);
    }
    
    .stat-value.warning {
        color: var(--warning);
    }
    
    .stat-icon-box {
        width: 40px;
        height: 40px;
        background: rgba(255, 111, 0, 0.1);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .stat-icon-box i {
        font-size: 1.1rem;
        color: var(--primary);
    }
    
    /* Progress Section */
    .progress-section {
        padding: 12px 20px;
        background: white;
        border-bottom: 1px solid var(--border-color);
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }
    
    .progress-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .progress-percent {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    .progress-bar-custom {
        height: 6px;
        background: var(--border-color);
        border-radius: 3px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: var(--primary);
        border-radius: 3px;
        transition: width 0.6s ease;
    }
    
    /* Table Section */
    .table-section {
        background: white;
    }
    
    .table-header {
        padding: 12px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .table-header h6 {
        font-weight: 700;
        margin-bottom: 0;
        font-size: 0.75rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .table-header h6 i {
        color: var(--primary);
    }
    
    .table-header small {
        font-size: 0.65rem;
        color: var(--text-muted);
    }
    
    /* Table Styles */
    .table-responsive {
        overflow-x: auto;
    }
    
    .contributors-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .contributors-table thead {
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-color);
    }
    
    .contributors-table th {
        padding: 10px 12px;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .contributors-table td {
        padding: 10px 12px;
        font-size: 0.7rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    
    .contributors-table tbody tr:hover {
        background: var(--bg-light);
    }
    
    .contributor-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.75rem;
    }
    
    .contributor-email {
        font-size: 0.6rem;
        color: var(--text-muted);
        margin-top: 2px;
    }
    
    .amount-promised, .amount-paid, .amount-remaining {
        font-weight: 600;
        text-align: right;
        font-size: 0.7rem;
    }
    
    .amount-promised {
        color: var(--text-primary);
    }
    
    .amount-paid {
        color: var(--success);
    }
    
    .amount-remaining {
        color: var(--warning);
    }
    
    .badge-completed {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        font-size: 0.6rem;
        font-weight: 600;
        color: var(--success);
        background: var(--success-light);
        border-radius: 20px;
    }
    
    .action-buttons-group {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        font-size: 0.6rem;
        font-weight: 600;
        color: var(--info);
        background: white;
        border: 1px solid var(--info);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-edit:hover {
        background: var(--info);
        color: white;
    }
    
    .btn-add-payment {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        font-size: 0.6rem;
        font-weight: 600;
        color: var(--primary);
        background: white;
        border: 1px solid var(--primary);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-add-payment:hover {
        background: var(--primary);
        color: white;
    }
    
    /* Table Footer */
    .table-footer {
        padding: 12px 20px;
        border-top: 1px solid var(--border-color);
        background: white;
    }
    
    /* Pagination */
    .pagination {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 0;
        justify-content: center;
    }
    
    .pagination .page-link {
        padding: 6px 12px;
        font-size: 0.7rem;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: white;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .pagination .page-link:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
    }
    
    .pagination .active .page-link {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    
    .pagination .disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* Modal Styles */
    .modal-content {
        border-radius: var(--radius-md);
        border: none;
        box-shadow: var(--shadow-md);
    }
    
    .modal-header {
        background: white;
        border-bottom: 1px solid var(--border-color);
        padding: 12px 16px;
    }
    
    .modal-header h6 {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .modal-body {
        padding: 16px;
    }
    
    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 12px 16px;
    }
    
    /* Back Link */
    .back-link {
        margin-top: 20px;
    }
    
    .back-link a {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
    }
    
    .back-link a:hover {
        color: var(--primary);
    }
    
    /* Notification */
    .notification-container {
        position: fixed;
        top: 80px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        min-width: 300px;
        max-width: 90%;
        pointer-events: none;
    }
    
    .notification {
        background: white;
        border-radius: var(--radius-md);
        padding: 12px 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: var(--shadow-md);
        animation: slideDown 0.3s ease;
        pointer-events: auto;
        border-left: 4px solid;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .notification-success {
        border-left-color: var(--success);
    }
    
    .notification-success i:first-child {
        color: var(--success);
    }
    
    .notification-error {
        border-left-color: var(--danger);
    }
    
    .notification-error i:first-child {
        color: var(--danger);
    }
    
    .notification-content {
        flex: 1;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-primary);
    }
    
    .notification-close {
        cursor: pointer;
        opacity: 0.6;
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    
    /* Loading Spinner */
    .spinner-border-sm {
        width: 12px;
        height: 12px;
        border-width: 2px;
        display: inline-block;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        animation: spin 0.6s linear infinite;
        margin-right: 6px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .contributors-container {
            padding: 20px 24px;
        }
    }
    
    @media (max-width: 768px) {
        .contributors-container {
            padding: 16px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 10px;
            padding: 12px 16px;
        }
        
        .event-header {
            flex-direction: column;
            text-align: center;
            padding: 12px 16px;
        }
        
        .btn-group {
            width: 100%;
            justify-content: center;
        }
        
        .filter-group {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-input {
            width: 100%;
        }
        
        .btn-filter, .btn-reset {
            width: 100%;
            justify-content: center;
        }
        
        .contributors-table th,
        .contributors-table td {
            padding: 8px 10px;
            white-space: nowrap;
        }
        
        .table-header {
            flex-direction: column;
            text-align: center;
        }
        
        .notification-container {
            min-width: 280px;
            top: 70px;
        }
        
        .stat-card {
            padding: 10px 12px;
        }
        
        .stat-value {
            font-size: 1rem;
        }
        
        .stat-icon-box {
            width: 36px;
            height: 36px;
        }
        
        .action-buttons-group {
            flex-direction: column;
        }
    }
    
    @media (max-width: 480px) {
        .contributors-container {
            padding: 12px;
        }
        
        .stat-icon-box {
            width: 32px;
            height: 32px;
        }
        
        .stat-icon-box i {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
<div class="contributors-container">
    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h4>Wachangiaji</h4>
            <p>Orodha ya wachangiaji na michango yao</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('contributors.create', $event) }}" class="btn-primary-custom">
                <i class="fas fa-plus-circle"></i> Ongeza Mchangiaji
            </a>
            <button onclick="exportToExcel()" class="btn-excel">
                <i class="fas fa-file-excel"></i> Excel
            </button>
            <button onclick="exportToPDF()" class="btn-pdf">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
        </div>
    </div>
    
    <!-- Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>
    
    <!-- Single Card -->
    <div class="single-card">
        <!-- Event Header -->
        <div class="event-header">
            <div class="event-info">
                <h6>{{ $event->event_name }}</h6>
                <small><i class="far fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</small>
            </div>
        </div>
        
        <!-- Stats -->
        @php
            $totalPromised = $event->contributors()->sum('promised_amount');
            $totalPaid = $event->contributors()->sum('paid_amount');
            $totalRemaining = $event->contributors()->sum('remaining_amount');
            $percentage = $totalPromised > 0 ? min(round(($totalPaid / $totalPromised) * 100), 100) : 0;
        @endphp
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-label">
                        <i class="fas fa-handshake"></i> Walioahidi
                    </div>
                    <div class="stat-value">{{ number_format($totalPromised) }} <small>TSh</small></div>
                </div>
                <div class="stat-icon-box">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-label">
                        <i class="fas fa-check-circle"></i> Waliolipa
                    </div>
                    <div class="stat-value success">{{ number_format($totalPaid) }} <small>TSh</small></div>
                </div>
                <div class="stat-icon-box">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-label">
                        <i class="fas fa-clock"></i> Mabaki
                    </div>
                    <div class="stat-value warning">{{ number_format($totalRemaining) }} <small>TSh</small></div>
                </div>
                <div class="stat-icon-box">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
        
        <!-- Progress -->
        <div class="progress-section">
            <div class="progress-header">
                <span class="progress-label">
                    <i class="fas fa-chart-line me-1"></i> Maendeleo ya Ukusanyaji
                </span>
                <span class="progress-percent">{{ $percentage }}%</span>
            </div>
            <div class="progress-bar-custom">
                <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-card">
            <div class="filter-body">
                <form method="GET" action="{{ route('contributors.index', $event) }}" id="filterForm">
                    <div class="filter-group">
                        <div class="filter-input">
                            <label><i class="fas fa-search"></i> Tafuta</label>
                            <input type="text" name="search" class="form-control" placeholder="Jina au Simu..." value="{{ request('search') }}">
                        </div>
                        <div class="filter-input">
                            <label><i class="fas fa-filter"></i> Hali</label>
                            <select name="status">
                                <option value="">Zote</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Imekamilika</option>
                                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Sehemu</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Bado</option>
                            </select>
                        </div>
                        <div class="filter-input">
                            <label><i class="fas fa-sort"></i> Panga</label>
                            <select name="sort">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Ya hivi karibuni</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Ya zamani</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Jina (A-Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Jina (Z-A)</option>
                                <option value="amount_high" {{ request('sort') == 'amount_high' ? 'selected' : '' }}>Kiasi kikubwa</option>
                                <option value="amount_low" {{ request('sort') == 'amount_low' ? 'selected' : '' }}>Kiasi kidogo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Chuja</button>
                        @if(request('search') || request('status') || request('sort'))
                            <a href="{{ route('contributors.index', $event) }}" class="btn-filter btn-reset"><i class="fas fa-times"></i> Futa</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Table Section -->
        <div class="table-section" id="export-content">
            <div class="table-header">
                <h6><i class="fas fa-list-ul"></i> Orodha ya Wachangiaji</h6>
                <small><i class="fas fa-users me-1"></i> Jumla: {{ $contributors->total() }} Wachangiaji</small>
            </div>
            
            <div class="table-responsive">
                <table class="contributors-table" id="contributors-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jina</th>
                            <th>Simu</th>
                            <th>Barua Pepe</th>
                            <th class="text-end">Alichoahidi (TSh)</th>
                            <th class="text-end">Alicholipa (TSh)</th>
                            <th class="text-end">Mabaki (TSh)</th>
                            <th class="text-center">Hali</th>
                            <th class="text-center">Kitendo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = ($contributors->currentPage() - 1) * $contributors->perPage() + 1; @endphp
                        @forelse($contributors as $contributor)
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td>
                                <div class="contributor-name">{{ $contributor->name }}</div>
                            </td>
                            <td>{{ $contributor->phone }}</td>
                            <td>{{ $contributor->email ?: '-' }}</td>
                            <td class="amount-promised text-end">{{ number_format($contributor->promised_amount) }}</td>
                            <td class="amount-paid text-end">{{ number_format($contributor->paid_amount) }}</td>
                            <td class="amount-remaining text-end">{{ number_format($contributor->remaining_amount) }}</td>
                            <td class="text-center">
                                @if($contributor->remaining_amount == 0)
                                    <span class="badge-completed">
                                        <i class="fas fa-check-circle"></i> Imekamilika
                                    </span>
                                @elseif($contributor->paid_amount > 0)
                                    <span class="badge-completed" style="background: var(--warning-light); color: var(--warning);">
                                        <i class="fas fa-hourglass-half"></i> Sehemu
                                    </span>
                                @else
                                    <span class="badge-completed" style="background: var(--danger-light); color: var(--danger);">
                                        <i class="fas fa-clock"></i> Bado
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="action-buttons-group">
                                    <button class="btn-edit" onclick="openEditModal({{ $contributor->id }}, '{{ addslashes($contributor->name) }}', '{{ $contributor->phone }}', '{{ $contributor->email }}', {{ $contributor->promised_amount }})">
                                        <i class="fas fa-edit"></i> Hariri
                                    </button>
                                    @if($contributor->remaining_amount > 0)
                                        <button class="btn-add-payment" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $contributor->id }}">
                                            <i class="fas fa-plus-circle"></i> Ongeza
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $contributor->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('contributors.update', ['event' => $event->id, 'contributor' => $contributor->id]) }}" class="edit-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h6 class="modal-title"><i class="fas fa-edit"></i> Hariri Mchangiaji</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label" style="font-size: 0.75rem; font-weight: 600;">Jina Kamili</label>
                                                <input type="text" name="name" class="form-control form-control-sm" value="{{ $contributor->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" style="font-size: 0.75rem; font-weight: 600;">Namba ya Simu</label>
                                                <input type="tel" name="phone" class="form-control form-control-sm" value="{{ $contributor->phone }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" style="font-size: 0.75rem; font-weight: 600;">Barua Pepe</label>
                                                <input type="email" name="email" class="form-control form-control-sm" value="{{ $contributor->email }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" style="font-size: 0.75rem; font-weight: 600;">Kiasi Alichoahidi (TSh)</label>
                                                <input type="number" name="promised_amount" class="form-control form-control-sm" value="{{ $contributor->promised_amount }}" min="0" step="1000" required>
                                                <small class="text-muted" style="font-size: 0.6rem;">Mabadiliko yataathiri mabaki</small>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label" style="font-size: 0.75rem; font-weight: 600;">Maelezo</label>
                                                <textarea name="notes" class="form-control form-control-sm" rows="2">{{ $contributor->notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Ghairi</button>
                                            <button type="submit" class="btn btn-primary btn-sm" style="background: var(--primary);">Hifadhi Mabadiliko</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Modal -->
                        <div class="modal fade" id="paymentModal{{ $contributor->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('contributors.add.payment', ['event' => $event->id, 'contributor' => $contributor->id]) }}" class="payment-form">
                                        @csrf
                                        <div class="modal-header">
                                            <h6 class="modal-title"><i class="fas fa-plus-circle"></i> Ongeza Malipo - {{ $contributor->name }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label" style="font-size: 0.7rem;">Mabaki</label>
                                                <div class="alert alert-warning py-2 mb-0" style="background: var(--warning-light); border-color: var(--warning); padding: 8px;">
                                                    <strong class="d-block text-center" style="font-size: 0.9rem;">{{ number_format($contributor->remaining_amount) }} TSh</strong>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label" style="font-size: 0.7rem;">Kiasi cha Malipo <span class="text-danger">*</span></label>
                                                <input type="number" 
                                                       name="amount" 
                                                       class="form-control form-control-sm" 
                                                       min="1000" 
                                                       max="{{ $contributor->remaining_amount }}" 
                                                       step="1000" 
                                                       placeholder="Weka kiasi" 
                                                       required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label" style="font-size: 0.7rem;">Njia ya Malipo</label>
                                                <select name="payment_method" class="form-select form-select-sm">
                                                    <option value="pending">Chagua Njia</option>
                                                    <option value="cash">💰 Fedha Taslimu</option>
                                                    <option value="mpesa">📱 M-Pesa</option>
                                                    <option value="bank">🏦 Benki</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Ghairi</button>
                                            <button type="submit" class="btn btn-primary btn-sm" style="background: var(--primary);">Hifadhi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-users fa-2x" style="color: var(--text-muted); opacity: 0.3; margin-bottom: 10px; display: block;"></i>
                                <p class="text-muted mb-0" style="font-size: 0.75rem;">Hakuna wachangiaji bado</p>
                                <a href="{{ route('contributors.create', $event) }}" class="btn-primary-custom mt-2" style="display: inline-flex;">
                                    <i class="fas fa-plus-circle me-1"></i> Ongeza Mchangiaji wa Kwanza
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($contributors->count() > 0)
                        <tfoot style="background: var(--bg-light); font-weight: 600;">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Jumla:</strong></td>
                                <td class="text-end"><strong>{{ number_format($totalPromised) }}</strong></td>
                                <td class="text-end" style="color: var(--success);"><strong>{{ number_format($totalPaid) }}</strong></td>
                                <td class="text-end" style="color: var(--warning);"><strong>{{ number_format($totalRemaining) }}</strong></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
            
            <!-- Pagination -->
            @if($contributors->hasPages())
                <div class="table-footer">
                    {{ $contributors->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
    
    <!-- Back Link -->
    <div class="back-link">
        <a href="{{ route('events.index') }}">
            <i class="fas fa-arrow-left"></i> Rudi kwenye Matukio
        </a>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Show notification function
    function showNotification(message, type = 'success') {
        const container = document.getElementById('notificationContainer');
        if (!container) return;
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        let icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>';
        
        notification.innerHTML = `
            ${icon}
            <div class="notification-content">${message}</div>
            <div class="notification-close">
                <i class="fas fa-times"></i>
            </div>
        `;
        
        container.appendChild(notification);
        
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';
                setTimeout(() => notification.remove(), 300);
            }
        }, 3000);
        
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            });
        }
    }
    
    // Open Edit Modal
    function openEditModal(id, name, phone, email, promisedAmount) {
        const modal = new bootstrap.Modal(document.getElementById(`editModal${id}`));
        modal.show();
    }
    
    // Auto-submit filter
    document.querySelectorAll('select[name="status"], select[name="sort"]').forEach(el => {
        if (el) {
            el.addEventListener('change', () => document.getElementById('filterForm').submit());
        }
    });
    
    // Search with debounce
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        });
    }
    
    // Export to Excel
    function exportToExcel() {
        showNotification('Inaandaa Excel... Tafadhali subiri', 'info');
        
        const table = document.getElementById('contributors-table');
        const wsData = [];
        
        const headers = [];
        const headerCells = table.querySelectorAll('thead th');
        headerCells.forEach(th => {
            if (th.innerText.trim() !== 'Kitendo') {
                headers.push(th.innerText.trim());
            }
        });
        wsData.push(headers);
        
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const rowData = [];
            const cells = row.querySelectorAll('td');
            for (let i = 0; i < cells.length - 1; i++) {
                let cellText = cells[i].innerText.trim();
                cellText = cellText.replace(/\n/g, ' ').replace(/\s+/g, ' ').trim();
                rowData.push(cellText);
            }
            wsData.push(rowData);
        });
        
        const tfoot = table.querySelector('tfoot');
        if (tfoot) {
            const footerRow = tfoot.querySelector('tr');
            if (footerRow) {
                const footerData = [];
                const footerCells = footerRow.querySelectorAll('td');
                footerCells.forEach((td, index) => {
                    if (index < 7) {
                        footerData.push(td.innerText.trim());
                    }
                });
                wsData.push(['', '', '', '', '', '', '']);
                wsData.push(footerData);
            }
        }
        
        const ws = XLSX.utils.aoa_to_sheet(wsData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Wachangiaji');
        
        ws['!cols'] = [
            {wch: 5}, {wch: 25}, {wch: 15}, {wch: 25}, {wch: 15}, {wch: 15}, {wch: 15}
        ];
        
        const eventName = "{{ $event->event_name }}";
        const filename = `Wachangiaji_${eventName}_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}.xlsx`;
        
        XLSX.writeFile(wb, filename);
        
        setTimeout(() => {
            showNotification('Excel imepakuliwa kikamilifu!', 'success');
        }, 500);
    }
    
    // PDF Export
    function exportToPDF() {
        const element = document.getElementById('export-content');
        const eventName = "{{ $event->event_name }}";
        
        showNotification('Inaandaa PDF... Tafadhali subiri', 'info');
        
        const opt = {
            margin: [0.5, 0.5, 0.5, 0.5],
            filename: `Wachangiaji_${eventName}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, letterRendering: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
        };
        
        const cloneElement = element.cloneNode(true);
        
        const buttonsToRemove = cloneElement.querySelectorAll('.btn-edit, .btn-add-payment, .badge-completed');
        buttonsToRemove.forEach(btn => {
            const parentCell = btn.closest('td');
            if (parentCell) parentCell.innerHTML = '';
        });
        
        const headers = cloneElement.querySelectorAll('thead th');
        if (headers.length > 0) {
            const lastHeader = headers[headers.length - 1];
            if (lastHeader.innerText.trim() === 'Kitendo') {
                lastHeader.remove();
            }
        }
        
        const rows = cloneElement.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 0) {
                const lastCell = cells[cells.length - 1];
                if (lastCell && lastCell.classList && lastCell.classList.contains('text-center')) {
                    lastCell.remove();
                }
            }
        });
        
        const tfoot = cloneElement.querySelector('tfoot');
        if (tfoot) {
            const footerCells = tfoot.querySelectorAll('td');
            if (footerCells.length > 0) {
                const lastFooterCell = footerCells[footerCells.length - 1];
                if (lastFooterCell) lastFooterCell.remove();
            }
        }
        
        const tempContainer = document.createElement('div');
        tempContainer.style.padding = '20px';
        tempContainer.style.background = 'white';
        tempContainer.style.fontFamily = 'Inter, sans-serif';
        
        const header = document.createElement('div');
        header.style.textAlign = 'center';
        header.style.marginBottom = '20px';
        header.style.paddingBottom = '10px';
        header.style.borderBottom = '2px solid #FF6F00';
        header.innerHTML = `
            <h2 style="color: #FF6F00; margin-bottom: 5px; font-size: 18px;">{{ $event->event_name }}</h2>
            <p style="color: #6B7280; margin: 0; font-size: 12px;">Tarehe: {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</p>
            <p style="color: #6B7280; margin: 5px 0 0 0; font-size: 12px;">Orodha ya Wachangiaji</p>
        `;
        
        const stats = document.createElement('div');
        stats.style.display = 'flex';
        stats.style.justifyContent = 'space-around';
        stats.style.marginBottom = '20px';
        stats.style.padding = '12px';
        stats.style.background = '#F9FAFB';
        stats.style.borderRadius = '8px';
        stats.innerHTML = `
            <div style="text-align: center;">
                <div style="font-size: 11px; color: #6B7280;">Walioahidi</div>
                <div style="font-size: 14px; font-weight: bold;">{{ number_format($totalPromised) }} TSh</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 11px; color: #6B7280;">Waliolipa</div>
                <div style="font-size: 14px; font-weight: bold; color: #10B981;">{{ number_format($totalPaid) }} TSh</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 11px; color: #6B7280;">Mabaki</div>
                <div style="font-size: 14px; font-weight: bold; color: #F59E0B;">{{ number_format($totalRemaining) }} TSh</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 11px; color: #6B7280;">Maendeleo</div>
                <div style="font-size: 14px; font-weight: bold; color: #FF6F00;">{{ $percentage }}%</div>
            </div>
        `;
        
        const footer = document.createElement('div');
        footer.style.textAlign = 'center';
        footer.style.marginTop = '20px';
        footer.style.paddingTop = '10px';
        footer.style.borderTop = '1px solid #E5E7EB';
        footer.style.fontSize = '9px';
        footer.style.color = '#6B7280';
        footer.innerHTML = `<p>Imetolewa kutoka CHANGIA SMART | {{ now()->format('d/m/Y H:i') }}</p>`;
        
        tempContainer.appendChild(header);
        tempContainer.appendChild(stats);
        tempContainer.appendChild(cloneElement);
        tempContainer.appendChild(footer);
        
        document.body.appendChild(tempContainer);
        
        html2pdf().set(opt).from(tempContainer).save().then(() => {
            document.body.removeChild(tempContainer);
            showNotification('PDF imepakuliwa kikamilifu!', 'success');
        }).catch(error => {
            document.body.removeChild(tempContainer);
            showNotification('Hitilafu wakati wa kupakua PDF', 'error');
        });
    }
    
    // Handle form submissions
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif
        
        @if($errors->any())
            showNotification('{{ $errors->first() }}', 'error');
        @endif
        
        const paymentForms = document.querySelectorAll('.payment-form');
        paymentForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border-sm"></span> Inahifadhi...';
            });
        });
        
        const editForms = document.querySelectorAll('.edit-form');
        editForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border-sm"></span> Inahifadhi...';
            });
        });
    });
</script>
@endsection