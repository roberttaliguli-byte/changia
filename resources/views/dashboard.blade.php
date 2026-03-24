@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

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
        min-height: 100vh;
    }
    
    .pg-wrap {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem 1rem;
        position: relative;
    }
    
    /* Welcome Section - COMPACT */
    .welcome-section {
        margin-bottom: 1.25rem;
    }
    
    .welcome-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--text-primary);
        margin-bottom: 0;
        line-height: 1.3;
    }
    
    .welcome-subtitle {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 0.125rem;
    }
    
    /* Stats Grid - COMPACT CARDS */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.875rem;
        margin-bottom: 1.25rem;
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
        margin-bottom: 0.125rem;
    }
    
    .stat-value small {
        font-size: 0.6rem;
        font-weight: 500;
        color: var(--text-muted);
    }
    
    .stat-trend {
        font-size: 0.6rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.2rem;
    }
    
    .stat-trend i {
        font-size: 0.55rem;
    }
    
    .trend-up {
        color: var(--success);
    }
    
    .stat-icon-box {
        width: 36px;
        height: 36px;
        background: var(--primary-light);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .stat-icon-box i {
        font-size: 1rem;
        color: var(--primary);
        opacity: 0.8;
    }
    
    /* Progress Card - COMPACT */
    .progress-card {
        background: white;
        border-radius: var(--radius-md);
        padding: 0.875rem 1rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.25rem;
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .progress-label {
        font-size: 0.7rem;
        font-weight: 700;
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
        margin-bottom: 0.5rem;
    }
    
    .progress-fill {
        height: 100%;
        background: var(--primary);
        border-radius: 3px;
        transition: width 0.6s ease;
    }
    
    .progress-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.65rem;
        color: var(--text-muted);
    }
    
    /* Alert - COMPACT */
    .alert-custom {
        background: var(--warning-light);
        border-left: 3px solid var(--warning);
        padding: 0.5rem 0.75rem;
        border-radius: var(--radius-sm);
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert-custom i {
        color: var(--warning);
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    
    .alert-custom span {
        font-size: 0.7rem;
        color: var(--text-secondary);
    }
    
    /* Quick Actions - COMPACT */
    .actions-card {
        background: white;
        border-radius: var(--radius-md);
        padding: 0.875rem 1rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.25rem;
    }
    
    .actions-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.625rem;
    }
    
    .actions-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-secondary);
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-action:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary-dark);
        transform: translateY(-1px);
    }
    
    .btn-action-primary {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    
    .btn-action-primary:hover {
        background: var(--primary-dark);
        color: white;
    }
    
    /* Row Layout */
    .row-custom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    /* Single Card - COMPACT */
    .single-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 1px solid var(--border-color);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    /* Card Header - COMPACT */
    .card-header-custom {
        padding: 0.75rem 1rem;
        background: white;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .card-header-custom h6 {
        font-weight: 700;
        margin-bottom: 0;
        font-size: 0.75rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .card-header-custom h6 i {
        font-size: 0.8rem;
        color: var(--primary);
    }
    
    .view-link {
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: gap 0.2s;
    }
    
    .view-link:hover {
        gap: 0.375rem;
        color: var(--primary-dark);
    }
    
    /* List Items - COMPACT */
    .list-items {
        flex: 1;
    }
    
    .list-item {
        display: block;
        padding: 0.625rem 1rem;
        border-bottom: 1px solid var(--border-color);
        text-decoration: none;
        transition: background 0.2s;
    }
    
    .list-item:last-child {
        border-bottom: none;
    }
    
    .list-item:hover {
        background: var(--bg-light);
    }
    
    .list-item-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
    }
    
    .list-item-main {
        flex: 1;
        min-width: 0;
    }
    
    .list-item-title {
        font-weight: 600;
        font-size: 0.75rem;
        color: var(--text-primary);
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .list-item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        font-size: 0.6rem;
        color: var(--text-muted);
    }
    
    .list-item-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
    }
    
    .list-item-amount {
        text-align: right;
        flex-shrink: 0;
    }
    
    .amount-value {
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--primary);
        display: block;
        margin-bottom: 0.2rem;
        white-space: nowrap;
    }
    
    /* Progress Mini - COMPACT */
    .progress-mini {
        height: 2px;
        background: var(--border-color);
        border-radius: 2px;
        overflow: hidden;
        width: 80px;
    }
    
    .progress-mini-bar {
        height: 100%;
        background: var(--primary);
        border-radius: 2px;
        transition: width 0.3s ease;
    }
    
    /* Status Badges - COMPACT */
    .status-badge {
        font-size: 0.55rem;
        padding: 0.15rem 0.4rem;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        white-space: nowrap;
    }
    
    .status-active {
        background: var(--primary-light);
        color: var(--primary-dark);
    }
    
    .status-completed {
        background: var(--success-light);
        color: var(--success);
    }
    
    .status-approved {
        background: var(--success-light);
        color: var(--success);
    }
    
    .status-pending {
        background: var(--warning-light);
        color: var(--warning);
    }
    
    /* Empty State - COMPACT */
    .empty-state {
        text-align: center;
        padding: 1.5rem;
    }
    
    .empty-state i {
        font-size: 1.5rem;
        opacity: 0.3;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid {
            gap: 0.75rem;
        }
    }
    
    @media (max-width: 768px) {
        .pg-wrap {
            padding: 1rem;
        }
        
        .welcome-title {
            font-size: 1.1rem;
        }
        
        .welcome-subtitle {
            font-size: 0.7rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.625rem;
            margin-bottom: 1rem;
        }
        
        .stat-card {
            padding: 0.625rem 0.75rem;
        }
        
        .stat-value {
            font-size: 1rem;
        }
        
        .stat-icon-box {
            width: 32px;
            height: 32px;
        }
        
        .stat-icon-box i {
            font-size: 0.875rem;
        }
        
        .progress-card,
        .actions-card {
            padding: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .row-custom {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .list-item-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.375rem;
        }
        
        .list-item-amount {
            text-align: left;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .progress-mini {
            width: 100%;
            margin-top: 0.25rem;
        }
        
        .amount-value {
            margin-bottom: 0;
        }
        
        .actions-grid {
            gap: 0.375rem;
        }
        
        .btn-action {
            padding: 0.3rem 0.625rem;
            font-size: 0.65rem;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .stat-card {
            padding: 0.5rem 0.75rem;
        }
        
        .list-item-amount {
            flex-wrap: wrap;
            gap: 0.375rem;
        }
    }
</style>
@endpush

@section('content')
<div class="pg-wrap">
    <!-- Welcome Section - COMPACT -->
    <div class="welcome-section">
        <h1 class="welcome-title">Karibu, {{ auth()->user()->name }}!</h1>
        <p class="welcome-subtitle">Muhtasari wa shughuli zako kwenye mfumo</p>
    </div>
    
    <!-- Stats Grid - COMPACT CARDS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">MATUKIO</div>
                <div class="stat-value">{{ $totalEvents }}</div>
                <div class="stat-trend">
                    <i class="fas fa-play trend-up"></i>
                    <span>{{ $activeEvents }} yanaendelea</span>
                </div>
            </div>
            <div class="stat-icon-box">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">WACHANGIAJI</div>
                <div class="stat-value">{{ number_format($totalContributors) }}</div>
                <div class="stat-trend">
                    <span>Wote</span>
                </div>
            </div>
            <div class="stat-icon-box">
                <i class="fas fa-users"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">MICHANGO</div>
                <div class="stat-value">{{ number_format($totalCollected) }} <small>TSh</small></div>
                <div class="stat-trend">
                    <span>Imekusanywa</span>
                </div>
            </div>
            <div class="stat-icon-box">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">MABAKI</div>
                <div class="stat-value">{{ number_format($totalRemaining) }} <small>TSh</small></div>
                <div class="stat-trend">
                    <span>{{ number_format($totalPromised) }} TSh walioahidi</span>
                </div>
            </div>
            <div class="stat-icon-box">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <!-- Progress Section - COMPACT -->
    <div class="progress-card">
        <div class="progress-header">
            <span class="progress-label">
                <i class="fas fa-chart-line me-1"></i> MAENDELEO YA JUMLA
            </span>
            <span class="progress-percent">{{ $overallProgress }}%</span>
        </div>
        <div class="progress-bar-custom">
            <div class="progress-fill" style="width: {{ $overallProgress }}%;"></div>
        </div>
        <div class="progress-stats">
            <span>Imekusanywa: {{ number_format($totalCollected) }} TSh</span>
            <span>Lengo: {{ number_format($totalPromised) }} TSh</span>
        </div>
        
        @if($pendingContributions > 0)
            <div class="alert-custom">
                <i class="fas fa-clock"></i>
                <span>{{ $pendingContributions }} michango inasubiri kuthibitishwa!</span>
            </div>
        @endif
    </div>
    
    <!-- Quick Actions - COMPACT -->
    <div class="actions-card">
        <div class="actions-label">
            <i class="fas fa-bolt me-1"></i> HATUA ZA HARAKA
        </div>
        <div class="actions-grid">
            <a href="{{ route('events.create') }}" class="btn-action btn-action-primary">
                <i class="fas fa-plus-circle"></i> Unda Tukio
            </a>
            @if($totalEvents > 0)
                @php $firstEvent = $events->first(); @endphp
                <a href="{{ route('contributors.create', $firstEvent->id) }}" class="btn-action">
                    <i class="fas fa-user-plus"></i> Mchangiaji
                </a>
                <a href="{{ route('cards.create') }}" class="btn-action">
                    <i class="fas fa-id-card"></i> Tengeneza Card
                </a>
                <a href="{{ route('reports.summary') }}" class="btn-action">
                    <i class="fas fa-chart-bar"></i> Ripoti
                </a>
            @endif
        </div>
    </div>
    
    <!-- Two Column Layout -->
    <div class="row-custom">
        <!-- Recent Events -->
        <div class="single-card">
            <div class="card-header-custom">
                <h6>
                    <i class="fas fa-calendar-alt"></i>
                    Matukio ya Hivi Punde
                </h6>
                <a href="{{ route('events.index') }}" class="view-link">
                    Tazama yote <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="list-items">
                @forelse($events->take(5) as $event)
                    @php
                        $collected = $event->contributions()->where('contributions.status', 'approved')->sum('contributions.amount');
                        $target = $event->target_amount ?? 0;
                        $progress = $target > 0 ? min(round(($collected / $target) * 100), 100) : 0;
                    @endphp
                    <a href="{{ route('events.show', $event) }}" class="list-item">
                        <div class="list-item-content">
                            <div class="list-item-main">
                                <div class="list-item-title">{{ $event->event_name }}</div>
                                <div class="list-item-meta">
                                    <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</span>
                                    @if($event->status == 'active')
                                        <span class="status-badge status-active">Inaendelea</span>
                                    @elseif($event->status == 'completed')
                                        <span class="status-badge status-completed">Imekamilika</span>
                                    @endif
                                </div>
                            </div>
                            <div class="list-item-amount">
                                <span class="amount-value">{{ number_format($collected) }} TSh</span>
                                <div class="progress-mini">
                                    <div class="progress-mini-bar" style="width: {{ $progress }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Hakuna matukio bado</p>
                        <a href="{{ route('events.create') }}" class="btn-action btn-action-primary" style="display: inline-flex;">
                            <i class="fas fa-plus-circle"></i> Unda Tukio
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Contributions -->
        <div class="single-card">
            <div class="card-header-custom">
                <h6>
                    <i class="fas fa-history"></i>
                    Michango ya Hivi Punde
                </h6>
                @if($totalEvents > 0)
                    <a href="{{ route('contributors.index', $firstEvent->id ?? 0) }}" class="view-link">
                        Endelea kuona <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            </div>
            <div class="list-items">
                @forelse($recentContributions->take(5) as $contribution)
                    <div class="list-item">
                        <div class="list-item-content">
                            <div class="list-item-main">
                                <div class="list-item-title">{{ $contribution->contributor->name }}</div>
                                <div class="list-item-meta">
                                    <span><i class="fas fa-tag"></i> {{ $contribution->contributor->event->event_name ?? 'N/A' }}</span>
                                    <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($contribution->created_at)->format('d M, Y') }}</span>
                                </div>
                            </div>
                            <div class="list-item-amount">
                                <span class="amount-value">{{ number_format($contribution->amount) }} TSh</span>
                                @if($contribution->status == 'approved')
                                    <span class="status-badge status-approved"><i class="fas fa-check-circle"></i> Imethibitishwa</span>
                                @else
                                    <span class="status-badge status-pending"><i class="fas fa-clock"></i> Inasubiri</span>
                                @endif
                            </div>
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
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Any additional JavaScript
    });
</script>
@endpush