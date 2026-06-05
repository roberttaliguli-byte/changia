@extends('layouts.app')

@section('title', 'Matukio Yangu')
@section('page_title', 'Matukio Yangu')

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
    
    /* FIX SCROLLING - Full height with proper bottom scroll on mobile */
    .main-content {
        overflow-y: auto !important;
        height: calc(100vh - var(--topbar-h, 60px));
        padding-bottom: 30px;
    }
    
    /* Mobile scroll fix - ensures full bottom reach */
    @media (max-width: 768px) {
        .main-content {
            height: auto !important;
            min-height: 100vh;
            overflow-y: visible !important;
            padding-bottom: 50px;
        }
        html, body {
            height: auto;
            overflow-x: hidden;
        }
        body {
            overflow-y: auto !important;
        }
    }
    
    /* Full width container - NO CENTERING */
    .events-container {
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
    
    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
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
    
    .btn-create:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
        color: white;
    }
    
    /* Stats Grid - Full width */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: white;
        border-radius: var(--radius-md);
        padding: 16px 20px;
        transition: all 0.2s ease;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }
    
    .stat-info {
        flex: 1;
    }
    
    .stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }
    
    .stat-value small {
        font-size: 0.7rem;
        font-weight: 500;
        color: var(--text-muted);
    }
    
    .stat-value.remaining {
        color: var(--warning);
    }
    
    .stat-icon-box {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .stat-icon-box i {
        font-size: 1.25rem;
    }
    
    .bg-primary-soft { background: rgba(255, 111, 0, 0.1); color: var(--primary); }
    .bg-success-soft { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .bg-info-soft { background: rgba(59, 130, 246, 0.1); color: var(--info); }
    .bg-warning-soft { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
    
    /* Filter Card - Full width */
    .filter-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        margin-bottom: 24px;
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
        min-width: 180px;
    }
    
    .filter-input label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 6px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-input .form-control,
    .filter-input .form-select {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 8px 12px;
        font-size: 0.75rem;
        font-family: 'Inter', sans-serif;
        background: white;
        width: 100%;
    }
    
    .filter-input .form-control:focus,
    .filter-input .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
        outline: none;
    }
    
    .btn-filter {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 8px 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        background: var(--primary);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
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
    
    /* Events Grid - Full width responsive */
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .event-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .event-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }
    
    .event-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .event-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .status-badge {
        font-size: 0.65rem;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-active { background: var(--success-light); color: var(--success); }
    .status-completed { background: var(--info-light); color: var(--info); }
    .status-cancelled { background: var(--danger-light); color: var(--danger); }
    
    .event-title {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-primary);
        line-height: 1.3;
        margin-bottom: 8px;
    }
    
    .event-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
        font-size: 0.7rem;
        color: var(--text-muted);
        padding-bottom: 8px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .event-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .progress-section {
        margin: 8px 0;
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }
    
    .progress-header span {
        font-size: 0.65rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    
    .progress-percent {
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
        transition: width 0.3s ease;
    }
    
    .amounts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin: 12px 0;
        padding: 8px 0;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }
    
    .amount-label {
        font-size: 0.6rem;
        color: var(--text-muted);
        display: block;
        margin-bottom: 4px;
        text-transform: uppercase;
        font-weight: 600;
    }
    
    .amount-value {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-primary);
    }
    
    .amount-value.primary { color: var(--primary); }
    .amount-value.warning { color: var(--warning); }
    
    .event-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 12px;
    }
    
    .contributors-count {
        font-size: 0.7rem;
        color: var(--text-muted);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .btn-icon {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        font-size: 0.65rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
        border: 1px solid var(--border-color);
        background: white;
    }
    
    .btn-view {
        color: var(--primary);
    }
    
    .btn-view:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary-dark);
    }
    
    .btn-edit {
        color: var(--info);
    }
    
    .btn-edit:hover {
        background: var(--info-light);
        border-color: var(--info);
        color: var(--info);
    }
    
    .btn-delete {
        color: var(--danger);
    }
    
    .btn-delete:hover {
        background: var(--danger-light);
        border-color: var(--danger);
        color: var(--danger);
    }
    
    .btn-link-copy {
        color: #25D366;
        border-color: #25D366;
    }
    
    .btn-link-copy:hover {
        background: #25D366;
        color: white;
        border-color: #25D366;
    }
    
    /* Modal Styles */
    .modal-custom {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1100;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(2px);
    }
    
    .modal-custom.active {
        display: flex;
    }
    
    .modal-container {
        background: white;
        border-radius: var(--radius-lg);
        width: 90%;
        max-width: 550px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-md);
        animation: modalSlideIn 0.2s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }
    
    .modal-header h5 {
        font-weight: 700;
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-primary);
    }
    
    .modal-header h5 i {
        color: var(--primary);
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: var(--text-muted);
        transition: color 0.2s;
    }
    
    .modal-close:hover {
        color: var(--danger);
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        padding: 16px 20px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    
    .form-group-modal {
        margin-bottom: 16px;
    }
    
    .form-group-modal label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-secondary);
        margin-bottom: 6px;
        display: block;
    }
    
    .form-group-modal input,
    .form-group-modal select,
    .form-group-modal textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-family: 'Inter', sans-serif;
    }
    
    .form-group-modal input:focus,
    .form-group-modal select:focus,
    .form-group-modal textarea:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
    }
    
    .btn-modal-primary {
        background: var(--primary);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-modal-primary:hover {
        background: var(--primary-dark);
    }
    
    .btn-modal-secondary {
        background: var(--bg-light);
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        padding: 8px 20px;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-modal-secondary:hover {
        background: var(--border-color);
    }
    
    .btn-modal-danger {
        background: var(--danger);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-modal-danger:hover {
        background: #dc2626;
    }
    
    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 24px;
    }
    
    .pagination {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    
    .pagination .page-link {
        padding: 8px 14px;
        font-size: 0.75rem;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: white;
        text-decoration: none;
        font-weight: 500;
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
    
    .empty-state {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state i {
        font-size: 3rem;
        opacity: 0.3;
        color: var(--text-muted);
        margin-bottom: 16px;
    }
    
    .empty-state h6 {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    
    .empty-state p {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 20px;
    }
    
    /* Responsive - ensures full scroll to bottom */
    @media (max-width: 1024px) {
        .events-container {
            padding: 20px 24px;
        }
        .events-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
    }
    
    @media (max-width: 768px) {
        .events-container {
            padding: 16px 16px 40px 16px;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .stat-card {
            padding: 12px 16px;
        }
        .stat-value {
            font-size: 1.1rem;
        }
        .stat-icon-box {
            width: 40px;
            height: 40px;
        }
        .events-grid {
            grid-template-columns: 1fr;
            gap: 12px;
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
        .action-buttons {
            flex-wrap: wrap;
        }
        .header-section {
            flex-direction: column;
            align-items: flex-start;
        }
        .modal-container {
            width: 95%;
            margin: 16px;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .events-container {
            padding: 12px 12px 40px 12px;
        }
        .event-body {
            padding: 12px;
        }
        .amounts-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .amounts-grid .text-end {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')
<div class="events-container">
    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h4>Matukio Yangu</h4>
            <p>Orodha na maendeleo ya matukio yako</p>
        </div>
        <button class="btn-create" onclick="openCreateModal()">
            <i class="fas fa-plus-circle"></i> Unda Tukio Jipya
        </button>
    </div>
    
    <!-- Stats - FIXED: Now shows remaining amount from target -->
    @php
        $totalEvents = $events->total();
        $activeEvents = $events->where('status', 'active')->count();
        $completedEvents = $events->where('status', 'completed')->count();
        $totalCollected = $events->sum(function($e) { return $e->total_collected ?? 0; });
        $totalTarget = $events->sum('target_amount');
        $totalRemaining = max(0, $totalTarget - $totalCollected);
    @endphp
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Jumla ya Matukio</div>
                <div class="stat-value">{{ $totalEvents }}</div>
            </div>
            <div class="stat-icon-box bg-primary-soft"><i class="fas fa-calendar-alt"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Yanayoendelea</div>
                <div class="stat-value">{{ $activeEvents }}</div>
            </div>
            <div class="stat-icon-box bg-success-soft"><i class="fas fa-play"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Yaliyokamilika</div>
                <div class="stat-value">{{ $completedEvents }}</div>
            </div>
            <div class="stat-icon-box bg-info-soft"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Mabaki ya Lengo</div>
                <div class="stat-value remaining">{{ number_format($totalRemaining) }} <small>TSh</small></div>
            </div>
            <div class="stat-icon-box bg-warning-soft"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="filter-card">
        <div class="filter-body">
            <form method="GET" action="{{ route('events.index') }}" id="filterForm">
                <div class="filter-group">
                    <div class="filter-input">
                        <label><i class="fas fa-search"></i> Tafuta Tukio</label>
                        <input type="text" name="search" class="form-control" placeholder="Jina la tukio..." value="{{ request('search') }}">
                    </div>
                    <div class="filter-input">
                        <label><i class="fas fa-filter"></i> Hali ya Tukio</label>
                        <select name="status" class="form-select">
                            <option value="">Zote</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Yanayoendelea</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Yaliyokamilika</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Yaliyofutwa</option>
                        </select>
                    </div>
                    <div class="filter-input">
                        <label><i class="fas fa-sort"></i> Panga kwa</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Ya hivi karibuni</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Ya zamani</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Jina (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Jina (Z-A)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Chuja</button>
                    @if(request('search') || request('status') || request('sort'))
                        <a href="{{ route('events.index') }}" class="btn-filter btn-reset"><i class="fas fa-times"></i> Futa</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    
    <!-- Events Grid -->
    @if($events->count() > 0)
        <div class="events-grid">
            @foreach($events as $event)
                @php
                    $collected = $event->total_collected ?? $event->contributions()->where('contributions.status', 'approved')->sum('amount');
                    $target = $event->target_amount ?? 0;
                    $progress = $target > 0 ? min(round(($collected / $target) * 100), 100) : 0;
                    $contributorCount = $event->contributors_count ?? $event->contributors()->count();
                    $remainingForEvent = max(0, $target - $collected);
                    $statusClass = $event->status == 'active' ? 'status-active' : ($event->status == 'completed' ? 'status-completed' : 'status-cancelled');
                    $statusText = $event->status == 'active' ? 'Inaendelea' : ($event->status == 'completed' ? 'Imekamilika' : 'Imefutwa');
                    $statusIcon = $event->status == 'active' ? 'fa-play' : ($event->status == 'completed' ? 'fa-check-circle' : 'fa-times-circle');
                @endphp
                
                <div class="event-card">
                    <div class="event-body">
                        <div class="event-header">
                            <span class="status-badge {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }}"></i> {{ $statusText }}
                            </span>
                        </div>
                        
                        <div class="event-title">
                            {{ Str::limit($event->event_name, 50) }}
                        </div>
                        
                        <div class="event-meta">
                            <span><i class="fas fa-tag"></i> {{ ucfirst($event->event_type) }}</span>
                            <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</span>
                        </div>
                        
                        @if($target > 0)
                        <div class="progress-section">
                            <div class="progress-header">
                                <span>Maendeleo ya Ukusanyaji</span>
                                <span class="progress-percent">{{ $progress }}%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill" style="width: {{ $progress }}%;"></div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="amounts-grid">
                            <div>
                                <span class="amount-label">Imekusanywa</span>
                                <span class="amount-value primary">{{ number_format($collected) }} TSh</span>
                            </div>
                            <div class="text-end">
                                <span class="amount-label">Lengo</span>
                                <span class="amount-value">{{ $target > 0 ? number_format($target) . ' TSh' : 'Hakuna Lengo' }}</span>
                            </div>
                            @if($target > 0)
                            <div>
                                <span class="amount-label">Mabaki</span>
                                <span class="amount-value warning">{{ number_format($remainingForEvent) }} TSh</span>
                            </div>
                            <div class="text-end">
                                <span class="amount-label">Wachangiaji</span>
                                <span class="amount-value">{{ $contributorCount }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="event-footer">
                          
                            <div class="action-buttons">
                                <a href="{{ route('contributors.index', $event->id) }}" class="btn-icon btn-view">
                                    <i class="fas fa-eye"></i> Tazama
                                </a>
                                <button onclick="getRegistrationLink({{ $event->id }})" class="btn-icon btn-link-copy">
                                    <i class="fab fa-whatsapp"></i> Pata Link
                                </button>
                                @if(auth()->user()->ownsEvent($event) || auth()->user()->role == 'admin')
                                    <button onclick="openEditModal({{ $event->id }}, '{{ addslashes($event->event_name) }}', '{{ $event->event_type }}', '{{ $event->event_date instanceof \Carbon\Carbon ? $event->event_date->format('Y-m-d') : date('Y-m-d', strtotime($event->event_date)) }}', '{{ $event->target_amount }}', '{{ addslashes($event->description) }}', '{{ $event->status }}')" class="btn-icon btn-edit">
                                        <i class="fas fa-edit"></i> Hariri
                                    </button>
                                    <button onclick="openDeleteModal({{ $event->id }}, '{{ addslashes($event->event_name) }}')" class="btn-icon btn-delete">
                                        <i class="fas fa-trash"></i> Futa
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($events->hasPages())
            <div class="pagination-container">
                {{ $events->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-calendar-alt"></i>
            <h6>Hakuna Matukio</h6>
            <p>Hujawa na matukio bado. Unda tukio lako la kwanza kuanza kukusanya michango.</p>
            <button class="btn-create" onclick="openCreateModal()"><i class="fas fa-plus-circle"></i> Unda Tukio Jipya</button>
        </div>
    @endif
</div>

<!-- CREATE MODAL -->
<div id="createModal" class="modal-custom">
    <div class="modal-container">
        <div class="modal-header">
            <h5><i class="fas fa-plus-circle"></i> Unda Tukio Jipya</h5>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        <form method="POST" action="{{ route('events.store') }}" id="createForm">
            @csrf
            <div class="modal-body">
                <div class="form-group-modal">
                    <label>Aina ya Tukio <span style="color: red;">*</span></label>
                    <select name="event_type" required>
                        <option value="">-- Chagua Aina ya Tukio --</option>
                        <option value="harusi">🎊 Harusi</option>
                        <option value="sendoff">✈️ Send-off</option>
                        <option value="birthday">🎂 Siku ya Kuzaliwa</option>
                        <option value="graduation">🎓 Graduation</option>
                        <option value="kitchen">🍽️ Kitchen Party</option>
                        <option value="baby">👶 Baby Shower</option>
                        <option value="fundraising">🤝 Harambee</option>
                        <option value="other">📌 Nyingine</option>
                    </select>
                </div>
                <div class="form-group-modal">
                    <label>Jina la Tukio <span style="color: red;">*</span></label>
                    <input type="text" name="event_name" required placeholder="Mf: Harusi ya Juma &amp; Asha">
                </div>
                <div class="form-group-modal">
                    <label>Tarehe ya Tukio <span style="color: red;">*</span></label>
                    <input type="date" name="event_date" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group-modal">
                    <label>Kiasi Lengwa (TSh)</label>
                    <input type="number" name="target_amount" min="0" step="1000" placeholder="0">
                    <small style="font-size: 0.6rem; color: #6B7280; display: block; margin-top: 4px;">Hiari - Acha ikiwa huna lengo maalum</small>
                </div>
                <div class="form-group-modal">
                    <label>Maelezo ya Tukio</label>
                    <textarea name="description" rows="3" placeholder="Andika maelezo kuhusu tukio lako..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-secondary" onclick="closeCreateModal()">Ghairi</button>
                <button type="submit" class="btn-modal-primary">Hifadhi Tukio</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal-custom">
    <div class="modal-container">
        <div class="modal-header">
            <h5><i class="fas fa-edit"></i> Hariri Tukio</h5>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form method="POST" action="" id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group-modal">
                    <label>Aina ya Tukio <span style="color: red;">*</span></label>
                    <select name="event_type" id="edit_event_type" required>
                        <option value="harusi">🎊 Harusi</option>
                        <option value="sendoff">✈️ Send-off</option>
                        <option value="birthday">🎂 Siku ya Kuzaliwa</option>
                        <option value="graduation">🎓 Graduation</option>
                        <option value="kitchen">🍽️ Kitchen Party</option>
                        <option value="baby">👶 Baby Shower</option>
                        <option value="fundraising">🤝 Harambee</option>
                        <option value="other">📌 Nyingine</option>
                    </select>
                </div>
                <div class="form-group-modal">
                    <label>Jina la Tukio <span style="color: red;">*</span></label>
                    <input type="text" name="event_name" id="edit_event_name" required>
                </div>
                <div class="form-group-modal">
                    <label>Tarehe ya Tukio <span style="color: red;">*</span></label>
                    <input type="date" name="event_date" id="edit_event_date" required>
                </div>
                <div class="form-group-modal">
                    <label>Kiasi Lengwa (TSh)</label>
                    <input type="number" name="target_amount" id="edit_target_amount" min="0" step="1000">
                </div>
                <div class="form-group-modal">
                    <label>Hali ya Tukio</label>
                    <select name="status" id="edit_status">
                        <option value="active">Inaendelea</option>
                        <option value="completed">Imekamilika</option>
                        <option value="cancelled">Imefutwa</option>
                    </select>
                </div>
                <div class="form-group-modal">
                    <label>Maelezo ya Tukio</label>
                    <textarea name="description" id="edit_description" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-secondary" onclick="closeEditModal()">Ghairi</button>
                <button type="submit" class="btn-modal-primary">Hifadhi Mabadiliko</button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="modal-custom">
    <div class="modal-container">
        <div class="modal-header">
            <h5><i class="fas fa-trash-alt" style="color: var(--danger);"></i> Futa Tukio</h5>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 8px; color: var(--text-primary);">Je, una uhakika unataka kufuta tukio hili?</p>
            <p id="deleteEventName" style="font-weight: 700; color: var(--danger); margin-bottom: 16px;"></p>
            <p style="font-size: 0.7rem; color: var(--text-muted);"><i class="fas fa-exclamation-triangle"></i> Kitendo hiki hakiwezi kurejeshwa. Michango yote itafutwa.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="closeDeleteModal()">Ghairi</button>
            <form method="POST" action="" id="deleteForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modal-danger">Futa Tukio</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Create Modal
    function openCreateModal() {
        document.getElementById('createModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeCreateModal() {
        document.getElementById('createModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('createForm').reset();
    }
    
    // Edit Modal
    function openEditModal(id, name, type, date, target, description, status) {
        const form = document.getElementById('editForm');
        form.action = '/events/' + id;
        
        document.getElementById('edit_event_name').value = name;
        document.getElementById('edit_event_type').value = type;
        document.getElementById('edit_event_date').value = date;
        document.getElementById('edit_target_amount').value = target || '';
        document.getElementById('edit_description').value = description || '';
        document.getElementById('edit_status').value = status;
        
        document.getElementById('editModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Delete Modal
    function openDeleteModal(id, name) {
        const form = document.getElementById('deleteForm');
        form.action = '/events/' + id;
        document.getElementById('deleteEventName').innerText = '"' + name + '"';
        document.getElementById('deleteModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
        }
    });
    
    // Close modals on overlay click
    document.querySelectorAll('.modal-custom').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // Auto-submit filter on select change
    const statusSelect = document.querySelector('select[name="status"]');
    const sortSelect = document.querySelector('select[name="sort"]');
    
    if (statusSelect) {
        statusSelect.addEventListener('change', () => document.getElementById('filterForm').submit());
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', () => document.getElementById('filterForm').submit());
    }
    
    // Quick search with debounce
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

    // Get registration link
    async function getRegistrationLink(eventId) {
        Swal.fire({
            title: 'Inaandaa...',
            text: 'Tafadhali subiri',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        try {
            const response = await fetch(`/events/${eventId}/get-registration-link`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            Swal.close();
            
            if (data.success) {
                const shareText = `Karibu kuchangia katika tukio letu!\n\nBonyeza link hii kusajili na kuahidi mchango wako:\n${data.link}\n\nAsante kwa ushirikiano wako!`;
                
                Swal.fire({
                    title: 'Kiungo cha Usajili',
                    html: `
                        <div style="text-align: center;">
                            <img src="${data.qr_code}" style="width: 150px; height: 150px; margin-bottom: 15px; border-radius: 10px;">
                            <p style="font-size: 0.8rem; word-break: break-all; background: #f3f4f6; padding: 10px; border-radius: 8px;">${data.link}</p>
                            <div style="display: flex; gap: 10px; justify-content: center; margin-top: 15px;">
                                <button onclick="copyToClipboard('${data.link}')" class="swal2-confirm swal2-styled" style="background: #3B82F6;">
                                    <i class="fas fa-copy"></i> Nakili
                                </button>
                                <button onclick="shareViaWhatsApp('${data.link}')" class="swal2-confirm swal2-styled" style="background: #25D366;">
                                    <i class="fab fa-whatsapp"></i> Shiriki WhatsApp
                                </button>
                            </div>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Funga',
                    confirmButtonColor: '#FF6F00'
                });
            } else {
                Swal.fire('Hitilafu', 'Imeshindwa kuunda kiungo. Jaribu tena.', 'error');
            }
        } catch (error) {
            Swal.close();
            Swal.fire('Hitilafu', 'Imeshindwa kuunda kiungo. Jaribu tena.', 'error');
        }
    }

    // Copy to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Ime nakiliwa!',
                text: 'Kiungo kimenakiliwa kwenye clipboard',
                timer: 1500,
                showConfirmButton: false
            });
        }).catch(() => {
            Swal.fire('Hitilafu', 'Imeshindwa kunakili. Tafadhali nakili kwa mkono.', 'error');
        });
    }

    // Share via WhatsApp
    function shareViaWhatsApp(link) {
        const message = encodeURIComponent(`Karibu kuchangia katika tukio letu!\n\nBonyeza link hii kusajili na kuahidi mchango wako:\n${link}\n\nAsante kwa ushirikiano wako!`);
        window.open(`https://wa.me/?text=${message}`, '_blank');
    }
</script>
@endsection