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
        --text-primary: #111827;
        --text-secondary: #4B5563;
        --text-muted: #6B7280;
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
    
    .pg-wrap {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem 1rem;
    }
    
    /* Header Section */
    .header-section {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
    }
    
    .header-title h4 {
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
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
    }
    
    .btn-create:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
        color: white;
    }
    
    /* Stats Grid - Compact */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.875rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: white;
        border-radius: var(--radius-md);
        padding: 0.875rem 1rem;
        transition: all 0.2s ease;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
    
    .stat-card:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
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
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }
    
    .stat-value small {
        font-size: 0.6rem;
        font-weight: 500;
        color: var(--text-muted);
    }
    
    .stat-icon-box {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .stat-icon-box i {
        font-size: 1rem;
    }
    
    .bg-primary-soft {
        background: rgba(255, 111, 0, 0.1);
        color: var(--primary);
    }
    
    .bg-success-soft {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }
    
    .bg-info-soft {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }
    
    .bg-warning-soft {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }
    
    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    .filter-body {
        padding: 0.875rem 1rem;
    }
    
    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: flex-end;
    }
    
    .filter-input {
        flex: 1;
        min-width: 180px;
    }
    
    .filter-input label {
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .filter-input .form-control,
    .filter-input .form-select {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 0.4rem 0.75rem;
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
        gap: 0.375rem;
        padding: 0.4rem 1rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: white;
        background: var(--primary);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        height: 34px;
    }
    
    .btn-filter:hover {
        background: var(--primary-dark);
    }
    
    /* Events Grid - Balanced Cards */
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
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
        height: 100%;
    }
    
    .event-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }
    
    .event-body {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .event-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .status-badge {
        font-size: 0.6rem;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .status-active {
        background: var(--success-light);
        color: var(--success);
    }
    
    .status-completed {
        background: var(--info-light);
        color: var(--info);
    }
    
    .status-cancelled {
        background: var(--danger-light);
        color: var(--danger);
    }
    
    .days-badge {
        font-size: 0.6rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
    }
    
    .days-warning {
        background: var(--warning-light);
        color: var(--warning);
    }
    
    .days-danger {
        background: var(--danger-light);
        color: var(--danger);
    }
    
    .event-title {
        margin-bottom: 0.5rem;
    }
    
    .event-title a {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-primary);
        text-decoration: none;
        transition: color 0.2s;
        display: block;
        line-height: 1.3;
    }
    
    .event-title a:hover {
        color: var(--primary);
    }
    
    .event-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        font-size: 0.65rem;
        color: var(--text-muted);
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .event-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .progress-section {
        margin: 0.5rem 0;
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
    }
    
    .progress-header span {
        font-size: 0.6rem;
        color: var(--text-muted);
    }
    
    .progress-header .progress-percent {
        font-weight: 700;
        color: var(--primary);
    }
    
    .progress-bar-custom {
        height: 4px;
        background: var(--border-color);
        border-radius: 2px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: var(--primary);
        border-radius: 2px;
        transition: width 0.3s ease;
    }
    
    .amounts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin: 0.75rem 0;
        padding: 0.5rem 0;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }
    
    .amount-item {
        text-align: left;
    }
    
    .amount-item.text-end {
        text-align: right;
    }
    
    .amount-label {
        font-size: 0.55rem;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.125rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .amount-value {
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--text-primary);
    }
    
    .amount-value.primary {
        color: var(--primary);
    }
    
    .event-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 0.75rem;
    }
    
    .contributors-count {
        font-size: 0.65rem;
        color: var(--text-muted);
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--primary);
        background: transparent;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-view:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary-dark);
    }
    
    /* Empty State */
    .empty-state {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        text-align: center;
        padding: 3rem 1.5rem;
    }
    
    .empty-state i {
        font-size: 2.5rem;
        opacity: 0.3;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }
    
    .empty-state h6 {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }
    
    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }
    
    .pagination {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
        margin-bottom: 0;
    }
    
    .pagination .page-link {
        padding: 0.375rem 0.75rem;
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
    
    /* Responsive */
    @media (max-width: 1024px) {
        .events-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .pg-wrap {
            padding: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.625rem;
        }
        
        .events-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .filter-group {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-input {
            width: 100%;
        }
        
        .btn-filter {
            width: 100%;
            justify-content: center;
        }
        
        .header-section {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .amounts-grid {
            gap: 0.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .event-meta {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .amounts-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .amount-item.text-end {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')
<div class="pg-wrap">
    <!-- Header Section -->
    <div class="header-section">
        <div class="header-title">
            <h4>Matukio Yangu</h4>
            <p>Orodha ya matukio yako yote na maendeleo yake</p>
        </div>
        <a href="{{ route('events.create') }}" class="btn-create">
            <i class="fas fa-plus-circle"></i> Unda Tukio Jipya
        </a>
    </div>
    
    <!-- Stats Grid -->
    @php
        $totalEvents = $events->total();
        $activeEvents = $events->where('status', 'active')->count();
        $completedEvents = $events->where('status', 'completed')->count();
        $totalCollected = $events->sum(function($e) { return $e->total_collected ?? 0; });
    @endphp
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Jumla ya Matukio</div>
                <div class="stat-value">{{ $totalEvents }}</div>
            </div>
            <div class="stat-icon-box bg-primary-soft">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Yanayoendelea</div>
                <div class="stat-value">{{ $activeEvents }}</div>
            </div>
            <div class="stat-icon-box bg-success-soft">
                <i class="fas fa-play"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Yaliyokamilika</div>
                <div class="stat-value">{{ $completedEvents }}</div>
            </div>
            <div class="stat-icon-box bg-info-soft">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Jumla ya Michango</div>
                <div class="stat-value">{{ number_format($totalCollected / 1000) }}K <small>TSh</small></div>
            </div>
            <div class="stat-icon-box bg-warning-soft">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
        </div>
    </div>
    
    <!-- Filter Card -->
    <div class="filter-card">
        <div class="filter-body">
            <form method="GET" action="{{ route('events.index') }}" id="filterForm">
                <div class="filter-group">
                    <div class="filter-input">
                        <label><i class="fas fa-search"></i> Tafuta</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Jina la tukio..." value="{{ request('search') }}">
                    </div>
                    <div class="filter-input">
                        <label><i class="fas fa-filter"></i> Hali</label>
                        <select name="status" class="form-select">
                            <option value="">Zote</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Yanayoendelea</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Yaliyokamilika</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Yaliyofutwa</option>
                        </select>
                    </div>
                    <div class="filter-input">
                        <label><i class="fas fa-sort"></i> Panga</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Ya hivi karibuni</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Ya zamani</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Jina (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Jina (Z-A)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Chuja
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Events Grid - Balanced Cards -->
    @if($events->count() > 0)
        <div class="events-grid">
            @foreach($events as $event)
                @php
                    $collected = $event->total_collected ?? $event->contributions()->where('status', 'approved')->sum('amount');
                    $target = $event->target_amount ?? 0;
                    $progress = $target > 0 ? min(round(($collected / $target) * 100), 100) : 0;
                    $contributorCount = $event->contributors_count ?? $event->contributors()->count();
                    $daysLeft = \Carbon\Carbon::parse($event->event_date)->diffInDays(now(), false);
                    
                    $statusClass = $event->status == 'active' ? 'status-active' : ($event->status == 'completed' ? 'status-completed' : 'status-cancelled');
                    $statusText = $event->status == 'active' ? 'Inaendelea' : ($event->status == 'completed' ? 'Imekamilika' : 'Imefutwa');
                @endphp
                
                <div class="event-card">
                    <div class="event-body">
                        <!-- Header with Status and Days -->
                        <div class="event-header">
                            <span class="status-badge {{ $statusClass }}">
                                <i class="fas {{ $event->status == 'active' ? 'fa-play' : ($event->status == 'completed' ? 'fa-check-circle' : 'fa-times-circle') }}"></i>
                                {{ $statusText }}
                            </span>
                            @if($daysLeft > 0 && $event->status == 'active')
                                <span class="days-badge days-warning">
                                    <i class="fas fa-hourglass-half"></i> {{ $daysLeft }} siku
                                </span>
                            @elseif($daysLeft <= 0 && $event->status == 'active')
                                <span class="days-badge days-danger">
                                    <i class="fas fa-exclamation-triangle"></i> Imechelewa
                                </span>
                            @endif
                        </div>
                        
                        <!-- Event Title -->
                        <div class="event-title">
                            <a href="{{ route('events.show', $event) }}">
                                {{ Str::limit($event->event_name, 50) }}
                            </a>
                        </div>
                        
                        <!-- Event Meta Info -->
                        <div class="event-meta">
                            <span><i class="fas fa-tag"></i> {{ ucfirst($event->event_type) }}</span>
                            <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</span>
                        </div>
                        
                        <!-- Progress Bar -->
                        @if($target > 0)
                            <div class="progress-section">
                                <div class="progress-header">
                                    <span>Maendeleo</span>
                                    <span class="progress-percent">{{ $progress }}%</span>
                                </div>
                                <div class="progress-bar-custom">
                                    <div class="progress-fill" style="width: {{ $progress }}%;"></div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Amounts - Balanced Grid -->
                        <div class="amounts-grid">
                            <div class="amount-item">
                                <span class="amount-label">Imekusanywa</span>
                                <span class="amount-value primary">{{ number_format($collected) }} TSh</span>
                            </div>
                            @if($target > 0)
                                <div class="amount-item text-end">
                                    <span class="amount-label">Lengo</span>
                                    <span class="amount-value">{{ number_format($target) }} TSh</span>
                                </div>
                            @else
                                <div class="amount-item text-end">
                                    <span class="amount-label">Hakuna Lengo</span>
                                    <span class="amount-value">-</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Footer -->
                        <div class="event-footer">
                            <span class="contributors-count">
                                <i class="fas fa-users"></i> {{ $contributorCount }} Wachangiaji
                            </span>
                            <a href="{{ route('events.show', $event) }}" class="btn-view">
                                Tazama <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($events->hasPages())
            <div class="pagination-container">
                {{ $events->appends(request()->query())->links() }}
            </div>
        @endif
        
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-calendar-alt"></i>
            <h6>Hakuna Matukio</h6>
            <p>Hujawa na matukio bado. Unda tukio lako la kwanza kuanza kukusanya michango.</p>
            <a href="{{ route('events.create') }}" class="btn-create">
                <i class="fas fa-plus-circle"></i> Unda Tukio Jipya
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when select changes
        const statusSelect = document.querySelector('select[name="status"]');
        const sortSelect = document.querySelector('select[name="sort"]');
        
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
        
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
    });
</script>
@endpush