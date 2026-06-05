@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
<style>
    /* -------------------------------------------------------------------
       COMPACT MODERN DASHBOARD - WITH PROPER SCROLLING (FIXED FOR MOBILE)
    ------------------------------------------------------------------- */
    :root {
        --primary: #FF6F00;
        --primary-dark: #E65100;
        --primary-light: #FF9800;
        --primary-soft: #FFF3E0;
        --success: #10B981;
        --success-soft: #ECFDF5;
        --warning: #F59E0B;
        --warning-soft: #FFFBEB;
        --danger: #EF4444;
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-400: #9CA3AF;
        --gray-500: #6B7280;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
        --bg-page: #F9FAFB;
        --bg-card: #FFFFFF;
        --border-light: #E5E7EB;
        --text-primary: #000000;
        --text-secondary: #1F2937;
        --text-muted: #4B5563;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 10px;
        --radius-lg: 12px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: var(--bg-page);
        color: var(--text-primary);
        font-size: 13px;
        line-height: 1.4;
    }

    /* FIXED SCROLLING - Only one scrollbar, full height on mobile */
    .main-content {
        margin-left: 240px;
        transition: margin-left 0.25s ease;
        min-height: 100vh;
        background: var(--bg-page);
        overflow-y: auto !important;
        height: 100vh;
        padding-bottom: 30px;
    }

    /* When sidebar is collapsed */
    .sidebar.collapsed ~ .main-content {
        margin-left: 70px;
    }

    /* Mobile sidebar fix - FULL SCROLLABLE TO BOTTOM */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0 !important;
            height: auto !important;      /* Critical: allows natural scroll */
            min-height: 100vh;
            overflow-y: visible !important;
            padding-bottom: 40px;
        }
        /* Ensure body/html can scroll freely */
        html, body {
            height: auto;
            overflow-x: hidden;
        }
        body {
            overflow-y: auto !important;
        }
    }

    /* Dashboard container - COMPACT */
    .dashboard-container {
        width: 100%;
        padding: 20px 24px;
    }

    /* Welcome header - COMPACT */
    .welcome-section {
        margin-bottom: 20px;
    }
    
    .welcome-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .welcome-subtitle {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    /* Stats Grid - COMPACT 4 columns */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: var(--bg-card);
        border-radius: var(--radius-md);
        padding: 14px 16px;
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
        transform: translateY(-1px);
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .stat-value small {
        font-size: 0.7rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    .stat-trend {
        font-size: 0.65rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        background: var(--primary-soft);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i {
        font-size: 1.2rem;
        color: var(--primary);
    }

    /* Progress Card - COMPACT */
    .progress-card {
        background: var(--bg-card);
        border-radius: var(--radius-md);
        padding: 14px 16px;
        border: 1px solid var(--border-light);
        margin-bottom: 20px;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .progress-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .progress-label i {
        color: var(--primary);
        font-size: 0.75rem;
    }

    .progress-percent {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary);
        background: var(--primary-soft);
        padding: 3px 10px;
        border-radius: 20px;
    }

    .progress-bar-container {
        background: var(--gray-100);
        border-radius: 100px;
        height: 6px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-bar-fill {
        background: var(--primary);
        border-radius: 100px;
        height: 100%;
        width: 0%;
        transition: width 0.6s ease;
    }

    .progress-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .progress-stats strong {
        color: var(--text-primary);
        font-weight: 700;
    }

    /* Alert banner - COMPACT */
    .alert-modern {
        background: var(--warning-soft);
        border-left: 3px solid var(--warning);
        border-radius: var(--radius-sm);
        padding: 8px 12px;
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-modern i {
        color: var(--warning);
        font-size: 0.75rem;
    }

    .alert-modern span {
        font-size: 0.7rem;
        font-weight: 500;
        color: var(--text-secondary);
    }

    /* Quick Actions - COMPACT */
    .actions-card {
        background: var(--bg-card);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        border: 1px solid var(--border-light);
        margin-bottom: 20px;
    }

    .actions-header {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .actions-header i {
        color: var(--primary);
    }

    .actions-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.2s ease;
        background: var(--gray-100);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }

    .action-btn i {
        font-size: 0.75rem;
    }

    .action-btn:hover {
        background: var(--primary-soft);
        color: var(--primary-dark);
        border-color: var(--primary-light);
        transform: translateY(-1px);
    }

    .action-primary {
        background: var(--primary);
        color: white;
        border: none;
    }

    .action-primary:hover {
        background: var(--primary-dark);
        color: white;
    }

    /* Two column grid */
    .two-col-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    /* List Cards - COMPACT */
    .list-card {
        background: var(--bg-card);
        border-radius: var(--radius-md);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .list-card-header {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-card);
    }

    .list-card-title {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .list-card-title i {
        color: var(--primary);
        font-size: 0.75rem;
    }

    .view-link {
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .view-link:hover {
        gap: 6px;
    }

    /* List Items - COMPACT */
    .list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-light);
        text-decoration: none;
        transition: background 0.2s;
    }

    .list-item:last-child {
        border-bottom: none;
    }

    .list-item:hover {
        background: var(--gray-50);
    }

    .list-item-left {
        flex: 1;
        min-width: 0;
    }

    .list-item-title {
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--text-primary);
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .list-item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 0.65rem;
        color: var(--text-muted);
    }

    .list-item-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .list-item-right {
        text-align: right;
        flex-shrink: 0;
        margin-left: 16px;
    }

    .amount {
        font-weight: 700;
        font-size: 0.8rem;
        color: var(--primary);
        display: block;
        margin-bottom: 5px;
        white-space: nowrap;
    }

    /* Mini progress */
    .progress-mini {
        width: 80px;
        height: 4px;
        background: var(--gray-200);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-mini-fill {
        height: 100%;
        background: var(--primary);
        border-radius: 4px;
    }

    /* Badges - COMPACT */
    .badge {
        font-size: 0.6rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-active {
        background: var(--primary-soft);
        color: var(--primary-dark);
    }

    .badge-completed, .badge-approved {
        background: var(--success-soft);
        color: var(--success);
    }

    .badge-pending {
        background: var(--warning-soft);
        color: var(--warning);
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 30px 20px;
    }

    .empty-state i {
        font-size: 1.5rem;
        opacity: 0.3;
        color: var(--text-muted);
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-bottom: 10px;
    }

    /* -------------------------------------------------------------------
       RESPONSIVE - FULL SCROLL ON MOBILE (REACH BOTTOM)
    ------------------------------------------------------------------- */
    @media (max-width: 1024px) {
        .dashboard-container {
            padding: 16px 20px;
        }
        .stats-grid {
            gap: 12px;
        }
        .stat-value {
            font-size: 1.2rem;
        }
        .stat-icon {
            width: 38px;
            height: 38px;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 14px 16px 32px 16px;  /* extra bottom padding for smooth scroll end */
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .two-col-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .list-item {
            flex-wrap: wrap;
        }
        .list-item-right {
            width: 100%;
            margin-left: 0;
            margin-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .progress-mini {
            width: 120px;
        }
        .stat-card {
            padding: 12px 14px;
        }
        .stat-value {
            font-size: 1.1rem;
        }
        /* Ensure main content does not clip and scrolls completely */
        .main-content {
            overflow-y: visible !important;
            height: auto !important;
            min-height: 100vh;
            padding-bottom: 50px;
        }
    }

    @media (max-width: 480px) {
        .dashboard-container {
            padding: 12px 12px 40px 12px;
        }
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .stat-card {
            padding: 10px 12px;
        }
        .actions-grid {
            gap: 8px;
        }
        .action-btn {
            padding: 5px 12px;
            font-size: 0.7rem;
        }
        .list-item {
            padding: 10px 12px;
        }
        .list-item-title {
            font-size: 0.75rem;
        }
        .amount {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1 class="welcome-title">Karibu, {{ auth()->user()->name }}!</h1>
        <p class="welcome-subtitle">Muhtasari wa shughuli zako na maendeleo ya michango</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">MATUKIO</div>
                <div class="stat-value">{{ $totalEvents }}</div>
                <div class="stat-trend">
                    <i class="fas fa-play" style="color: var(--success); font-size: 0.55rem;"></i>
                    <span>{{ $activeEvents }} yanaendelea</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">WACHANGIAJI</div>
                <div class="stat-value">{{ number_format($totalContributors) }}</div>
                <div class="stat-trend">
                    <i class="fas fa-users"></i>
                    <span>Wote</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">MICHANGO</div>
                <div class="stat-value">{{ number_format($totalCollected) }} <small>TSh</small></div>
                <div class="stat-trend">
                    <i class="fas fa-hand-holding-heart"></i>
                    <span>Imekusanywa</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">MABAKI</div>
                <div class="stat-value">{{ number_format($totalRemaining) }} <small>TSh</small></div>
                <div class="stat-trend">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ number_format($totalPromised) }} TSh lengo</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="progress-card">
        <div class="progress-header">
            <span class="progress-label">
                <i class="fas fa-chart-line"></i> MAENDELEO YA JUMLA
            </span>
            <span class="progress-percent">{{ $overallProgress }}%</span>
        </div>
        <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: {{ $overallProgress }}%;"></div>
        </div>
        <div class="progress-stats">
            <span>Imekusanywa: <strong>{{ number_format($totalCollected) }} TSh</strong></span>
            <span>Lengo: <strong>{{ number_format($totalPromised) }} TSh</strong></span>
        </div>
        
        @if($pendingContributions > 0)
            <div class="alert-modern">
                <i class="fas fa-clock"></i>
                <span>{{ $pendingContributions }} michango inasubiri kuthibitishwa</span>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="actions-card">
        <div class="actions-header">
            <i class="fas fa-bolt"></i> HATUA ZA HARAKA
        </div>
        <div class="actions-grid">
            <a href="{{ route('events.create') }}" class="action-btn action-primary">
                <i class="fas fa-plus-circle"></i> Unda Tukio
            </a>
            @if($totalEvents > 0)
                @php $firstEvent = $events->first(); @endphp
                <a href="{{ route('contributors.create', $firstEvent->id) }}" class="action-btn">
                    <i class="fas fa-user-plus"></i> Mchangiaji
                </a>
                <a href="{{ route('cards.create') }}" class="action-btn">
                    <i class="fas fa-id-card"></i> Tengeneza Card
                </a>
                <a href="{{ route('reports.summary') }}" class="action-btn">
                    <i class="fas fa-chart-bar"></i> Ripoti
                </a>
                <a href="{{ route('ujumbe.michango') }}" class="action-btn">
                    <i class="fas fa-envelope"></i> Ujumbe
                </a>
            @endif
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="two-col-grid">
        <!-- Recent Events -->
        <div class="list-card">
            <div class="list-card-header">
                <h6 class="list-card-title">
                    <i class="fas fa-calendar-alt"></i>
                    Matukio ya Hivi Punde
                </h6>
                <a href="{{ route('events.index') }}" class="view-link">
                    Tazama yote <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @forelse($events->take(5) as $event)
                @php
                    $collected = $event->contributions()->where('contributions.status', 'approved')->sum('contributions.amount');
                    $target = $event->target_amount ?? 0;
                    $progress = $target > 0 ? min(round(($collected / $target) * 100), 100) : 0;
                @endphp
                <a href="{{ route('events.show', $event) }}" class="list-item">
                    <div class="list-item-left">
                        <div class="list-item-title">{{ \Str::limit($event->event_name, 40) }}</div>
                        <div class="list-item-meta">
                            <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</span>
                            @if($event->status == 'active')
                                <span class="badge badge-active">Inaendelea</span>
                            @elseif($event->status == 'completed')
                                <span class="badge badge-completed">Imekamilika</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-item-right">
                        <span class="amount">{{ number_format($collected) }} TSh</span>
                        <div class="progress-mini">
                            <div class="progress-mini-fill" style="width: {{ $progress }}%;"></div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-alt"></i>
                    <p>Hakuna matukio bado</p>
                    <a href="{{ route('events.create') }}" class="action-btn action-primary" style="display: inline-flex;">
                        <i class="fas fa-plus-circle"></i> Unda Tukio
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Recent Contributions -->
        <div class="list-card">
            <div class="list-card-header">
                <h6 class="list-card-title">
                    <i class="fas fa-history"></i>
                    Michango ya Hivi Punde
                </h6>
                @if($totalEvents > 0)
                    <a href="{{ route('contributors.index', $firstEvent->id ?? 0) }}" class="view-link">
                        Endelea kuona <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            </div>
            @forelse($recentContributions->take(5) as $contribution)
                <div class="list-item">
                    <div class="list-item-left">
                        <div class="list-item-title">{{ $contribution->contributor->name ?? 'Mchangiaji' }}</div>
                        <div class="list-item-meta">
                            <span><i class="fas fa-tag"></i> {{ $contribution->contributor->event->event_name ?? 'N/A' }}</span>
                            <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($contribution->created_at)->format('d M, Y') }}</span>
                        </div>
                    </div>
                    <div class="list-item-right">
                        <span class="amount">{{ number_format($contribution->amount) }} TSh</span>
                        @if($contribution->status == 'approved')
                            <span class="badge badge-approved"><i class="fas fa-check-circle"></i> Imethibitishwa</span>
                        @else
                            <span class="badge badge-pending"><i class="fas fa-clock"></i> Inasubiri</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-hand-holding-heart"></i>
                    <p>Hakuna michango bado</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate progress bars
        const mainBar = document.querySelector('.progress-bar-fill');
        if (mainBar) {
            const width = mainBar.style.width;
            mainBar.style.width = '0%';
            setTimeout(() => { mainBar.style.width = width; }, 100);
        }
        
        // Animate mini progress bars
        document.querySelectorAll('.progress-mini-fill').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => { bar.style.width = width; }, 150);
        });
    });
</script>
@endpush