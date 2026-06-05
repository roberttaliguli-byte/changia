@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
<style>
    /* -------------------------------------------------------------------
       DASHBOARD - PROPER SCROLLING ON ALL DEVICES
       Fixed to work like landing page
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

    /* OVERRIDE app.blade.php hidden overflow */
    html, body {
        overflow: auto !important;
        height: auto !important;
        min-height: 100vh;
    }

    /* MAIN CONTENT - NATURAL SCROLLING */
    .main-content {
        margin-left: 240px;
        transition: margin-left 0.25s ease;
        min-height: 100vh;
        background: var(--bg-page);
        overflow: visible !important;
        height: auto !important;
        padding-bottom: 40px;
    }

    /* When sidebar is collapsed */
    .sidebar.collapsed ~ .main-content {
        margin-left: 70px;
    }

    /* Mobile - NO SIDEBAR MARGIN */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0 !important;
            height: auto !important;
            min-height: 100vh;
            overflow: visible !important;
            padding-bottom: 50px;
        }
    }

    /* Dashboard container - FULL WIDTH WITH GOOD PADDING */
    .dashboard-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 24px;
    }

    /* Welcome header */
    .welcome-section {
        margin-bottom: 24px;
    }
    
    .welcome-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 6px;
    }
    
    .welcome-subtitle {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 18px 20px;
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
        transform: translateY(-2px);
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .stat-value small {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    .stat-trend {
        font-size: 0.7rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        background: var(--primary-soft);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i {
        font-size: 1.4rem;
        color: var(--primary);
    }

    /* Progress Card */
    .progress-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 20px 24px;
        border: 1px solid var(--border-light);
        margin-bottom: 24px;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .progress-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .progress-label i {
        color: var(--primary);
        font-size: 0.85rem;
    }

    .progress-percent {
        font-size: 0.85rem;
        font-weight: 800;
        color: var(--primary);
        background: var(--primary-soft);
        padding: 4px 12px;
        border-radius: 30px;
    }

    .progress-bar-container {
        background: var(--gray-100);
        border-radius: 100px;
        height: 8px;
        overflow: hidden;
        margin-bottom: 12px;
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
        font-size: 0.75rem;
        color: var(--text-muted);
        flex-wrap: wrap;
        gap: 10px;
    }

    .progress-stats strong {
        color: var(--text-primary);
        font-weight: 700;
    }

    /* Alert banner */
    .alert-modern {
        background: var(--warning-soft);
        border-left: 3px solid var(--warning);
        border-radius: var(--radius-sm);
        padding: 10px 16px;
        margin-top: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-modern i {
        color: var(--warning);
        font-size: 0.85rem;
    }

    .alert-modern span {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-secondary);
    }

    /* Quick Actions */
    .actions-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        border: 1px solid var(--border-light);
        margin-bottom: 24px;
    }

    .actions-header {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 14px;
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
        gap: 12px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.2s ease;
        background: var(--gray-100);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }

    .action-btn i {
        font-size: 0.8rem;
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
        gap: 24px;
    }

    /* List Cards */
    .list-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .list-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-card);
        flex-wrap: wrap;
        gap: 10px;
    }

    .list-card-title {
        font-size: 0.7rem;
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
        font-size: 0.85rem;
    }

    .view-link {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .view-link:hover {
        gap: 8px;
    }

    /* List Items */
    .list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
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
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-primary);
        margin-bottom: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .list-item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .list-item-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .list-item-right {
        text-align: right;
        flex-shrink: 0;
        margin-left: 16px;
    }

    .amount {
        font-weight: 800;
        font-size: 0.9rem;
        color: var(--primary);
        display: block;
        margin-bottom: 6px;
        white-space: nowrap;
    }

    /* Mini progress */
    .progress-mini {
        width: 100px;
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

    /* Badges */
    .badge-custom {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 2rem;
        opacity: 0.3;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    .empty-state p {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    /* -------------------------------------------------------------------
       RESPONSIVE BREAKPOINTS
    ------------------------------------------------------------------- */
    
    /* Tablet */
    @media (max-width: 1024px) {
        .dashboard-container {
            padding: 16px 20px;
        }
        .stats-grid {
            gap: 15px;
        }
        .stat-value {
            font-size: 1.3rem;
        }
        .stat-icon {
            width: 46px;
            height: 46px;
        }
        .stat-icon i {
            font-size: 1.2rem;
        }
    }

    /* Mobile Landscape */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 14px 16px 40px 16px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .two-col-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .stat-card {
            padding: 14px 16px;
        }
        
        .stat-value {
            font-size: 1.2rem;
        }
        
        .stat-icon {
            width: 42px;
            height: 42px;
        }
        
        .progress-card {
            padding: 16px 18px;
        }
        
        .actions-card {
            padding: 14px 16px;
        }
        
        .list-card-header {
            padding: 12px 16px;
        }
        
        .list-item {
            padding: 12px 16px;
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
        
        .welcome-title {
            font-size: 1.3rem;
        }
    }

    /* Mobile Portrait */
    @media (max-width: 480px) {
        .dashboard-container {
            padding: 12px 12px 40px 12px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .stat-card {
            padding: 12px 14px;
        }
        
        .stat-value {
            font-size: 1.1rem;
        }
        
        .stat-icon {
            width: 38px;
            height: 38px;
        }
        
        .stat-icon i {
            font-size: 1rem;
        }
        
        .actions-grid {
            gap: 8px;
        }
        
        .action-btn {
            padding: 6px 14px;
            font-size: 0.75rem;
        }
        
        .list-item-title {
            font-size: 0.8rem;
        }
        
        .amount {
            font-size: 0.8rem;
        }
        
        .badge-custom {
            font-size: 0.6rem;
            padding: 2px 8px;
        }
        
        .progress-percent {
            font-size: 0.75rem;
            padding: 3px 10px;
        }
        
        .progress-stats {
            flex-direction: column;
            gap: 5px;
        }
        
        .alert-modern {
            padding: 8px 12px;
        }
        
        .alert-modern span {
            font-size: 0.7rem;
        }
        
        .welcome-title {
            font-size: 1.1rem;
        }
        
        .welcome-subtitle {
            font-size: 0.7rem;
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
                <div class="stat-label">
                    <i class="fas fa-calendar-alt"></i> MATUKIO
                </div>
                <div class="stat-value">{{ $totalEvents }}</div>
                <div class="stat-trend">
                    <i class="fas fa-play" style="color: var(--success);"></i>
                    <span>{{ $activeEvents }} yanaendelea</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    <i class="fas fa-users"></i> WACHANGIAJI
                </div>
                <div class="stat-value">{{ number_format($totalContributors) }}</div>
                <div class="stat-trend">
                    <i class="fas fa-user-plus"></i>
                    <span>Wote</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    <i class="fas fa-hand-holding-heart"></i> MICHANGO
                </div>
                <div class="stat-value">{{ number_format($totalCollected) }} <small>TSh</small></div>
                <div class="stat-trend">
                    <i class="fas fa-check-circle"></i>
                    <span>Imekusanywa</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    <i class="fas fa-chart-line"></i> MABAKI
                </div>
                <div class="stat-value">{{ number_format($totalRemaining) }} <small>TSh</small></div>
                <div class="stat-trend">
                    <i class="fas fa-bullseye"></i>
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
            <span>Asilimia: <strong>{{ $overallProgress }}%</strong></span>
        </div>
        
        @if($pendingContributions > 0)
            <div class="alert-modern">
                <i class="fas fa-clock"></i>
                <span><strong>{{ $pendingContributions }}</strong> michango inasubiri kuthibitishwa</span>
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
                    <i class="fas fa-user-plus"></i> Sajili Mchangiaji
                </a>
                <a href="{{ route('cards.create') }}" class="action-btn">
                    <i class="fas fa-id-card"></i> Tengeneza Kadi
                </a>
                <a href="{{ route('reports.summary') }}" class="action-btn">
                    <i class="fas fa-chart-bar"></i> Ripoti
                </a>
                <a href="{{ route('ujumbe.michango') }}" class="action-btn">
                    <i class="fas fa-envelope"></i> Tuma Ujumbe
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
                    $collected = $event->contributors()->sum('paid_amount');
                    $target = $event->contributors()->sum('promised_amount');
                    $progress = $target > 0 ? min(round(($collected / $target) * 100), 100) : 0;
                @endphp
                <a href="{{ route('events.show', $event) }}" class="list-item">
                    <div class="list-item-left">
                        <div class="list-item-title">{{ \Str::limit($event->event_name, 40) }}</div>
                        <div class="list-item-meta">
                            <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</span>
                            @if($event->status == 'active')
                                <span class="badge-custom badge-active"><i class="fas fa-play"></i> Inaendelea</span>
                            @elseif($event->status == 'completed')
                                <span class="badge-custom badge-completed"><i class="fas fa-check-circle"></i> Imekamilika</span>
                            @else
                                <span class="badge-custom badge-pending"><i class="fas fa-pause"></i> Imeahirishwa</span>
                            @endif
                        </div>
                    </div>
                    <div class="list-item-right">
                        <span class="amount">{{ number_format($collected) }} / {{ number_format($target) }} TSh</span>
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
                @if($totalEvents > 0 && $firstEvent ?? null)
                    <a href="{{ route('contributors.index', $firstEvent->id) }}" class="view-link">
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
                            <span class="badge-custom badge-approved"><i class="fas fa-check-circle"></i> Imethibitishwa</span>
                        @else
                            <span class="badge-custom badge-pending"><i class="fas fa-clock"></i> Inasubiri</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-hand-holding-heart"></i>
                    <p>Hakuna michango bado</p>
                    @if($totalEvents > 0 && $firstEvent ?? null)
                        <a href="{{ route('contributors.create', $firstEvent->id) }}" class="action-btn action-primary" style="display: inline-flex;">
                            <i class="fas fa-user-plus"></i> Sajili Mchangiaji
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate main progress bar
        const mainBar = document.querySelector('.progress-bar-fill');
        if (mainBar) {
            const width = mainBar.style.width;
            mainBar.style.width = '0%';
            setTimeout(() => { 
                mainBar.style.width = width; 
            }, 100);
        }
        
        // Animate mini progress bars
        document.querySelectorAll('.progress-mini-fill').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => { 
                bar.style.width = width; 
            }, 150);
        });

        // Fix any remaining scroll issues
        if (document.querySelector('html, body')) {
            document.documentElement.style.overflow = 'auto';
            document.body.style.overflow = 'auto';
            document.documentElement.style.height = 'auto';
            document.body.style.height = 'auto';
        }
    });
</script>
@endpush