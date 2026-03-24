@extends('layouts.app')

@section('title', $event->event_name)
@section('page_title', $event->event_name)

@push('styles')
<style>
    :root {
        --primary: #FF6F00;
        --primary-light: #FFF3E0;
        --primary-dark: #E65100;
        --success: #10B981;
        --warning: #F59E0B;
        --text-primary: #111827;
        --text-secondary: #4B5563;
        --text-muted: #6B7280;
        --bg-light: #F9FAFB;
        --border-color: #E5E7EB;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --radius-md: 12px;
        --radius-sm: 8px;
    }
    
    body {
        background: var(--bg-light);
        font-family: 'Inter', sans-serif;
    }
    
    .pg-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem;
    }
    
    /* Single Unified Card */
    .unified-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    /* Header */
    .card-header {
        padding: 1rem 1.25rem;
        background: white;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .event-title h5 {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }
    
    .event-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.7rem;
        color: var(--text-muted);
    }
    
    .event-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .btn-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        cursor: pointer;
        border: none;
    }
    
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    
    .btn-primary:hover {
        background: var(--primary-dark);
    }
    
    .btn-warning {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .btn-info {
        background: #DBEAFE;
        color: #2563EB;
    }
    
    /* Two Column Grid */
    .two-column {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .info-box {
        background: var(--bg-light);
        border-radius: var(--radius-sm);
        padding: 0.875rem;
    }
    
    .info-box h6 {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .info-box h6 i {
        color: var(--primary);
    }
    
    .info-box p {
        font-size: 0.8rem;
        color: var(--text-primary);
        margin-bottom: 0;
        line-height: 1.4;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    
    .info-row:last-child {
        margin-bottom: 0;
    }
    
    .info-label {
        font-size: 0.7rem;
        color: var(--text-muted);
    }
    
    .info-value {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.5rem;
        font-size: 0.65rem;
        font-weight: 600;
        border-radius: 20px;
    }
    
    .status-active {
        background: #D1FAE5;
        color: #10B981;
    }
    
    /* Progress Section */
    .progress-section {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    
    .progress-header span {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
    }
    
    .progress-percent {
        color: var(--primary);
        font-weight: 700;
    }
    
    .progress-bar {
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
    }
    
    .progress-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    
    .pending-alert {
        background: #FEF3C7;
        border-radius: var(--radius-sm);
        padding: 0.5rem 0.75rem;
        font-size: 0.7rem;
        color: #D97706;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
        text-align: center;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-item .stat-label {
        font-size: 0.6rem;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .stat-item .stat-number {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-primary);
    }
    
    /* Tabs */
    .tabs-nav {
        display: flex;
        border-bottom: 1px solid var(--border-color);
        background: white;
        padding: 0 1.25rem;
    }
    
    .tab-btn {
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    
    .tab-content {
        padding: 0;
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
    }
    
    /* Tables */
    .table-responsive {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th {
        padding: 0.75rem 1rem;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        text-align: left;
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-color);
    }
    
    td {
        padding: 0.75rem 1rem;
        font-size: 0.7rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    tr:hover td {
        background: var(--bg-light);
    }
    
    .text-end {
        text-align: right;
    }
    
    .text-center {
        text-align: center;
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .badge-approved {
        background: #D1FAE5;
        color: #10B981;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .tfoot-summary {
        background: var(--bg-light);
        font-weight: 700;
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem;
    }
    
    .empty-state i {
        font-size: 2rem;
        opacity: 0.3;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .empty-state p {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
    }
    
    .back-link {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .back-link a {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .back-link a:hover {
        color: var(--primary);
    }
    
    @media (max-width: 768px) {
        .pg-wrap {
            padding: 1rem;
        }
        
        .two-column {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            padding: 1rem;
        }
        
        .stats-row {
            gap: 0.5rem;
        }
        
        .btn-group {
            width: 100%;
        }
        
        .tabs-nav {
            padding: 0 0.75rem;
        }
        
        .tab-btn {
            padding: 0.625rem 0.75rem;
            font-size: 0.7rem;
        }
        
        th, td {
            padding: 0.5rem 0.75rem;
            white-space: nowrap;
        }
    }
</style>
@endpush

@section('content')
<div class="pg-wrap">
    <!-- Single Unified Card -->
    <div class="unified-card">
        <!-- Header -->
        <div class="card-header">
            <div>
                <h5>{{ $event->event_name }}</h5>
                <div class="event-meta">
                    <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</span>
                    <span><i class="fas fa-tag"></i> {{ ucfirst($event->event_type) }}</span>
                </div>
            </div>
            <div class="btn-group">
                @if(auth()->user()->ownsEvent($event) || auth()->user()->role == 'admin')
                <a href="{{ route('events.edit', $event) }}" class="btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Hariri
                </a>
                @endif
                <a href="{{ route('contributors.create', $event) }}" class="btn-sm btn-primary">
                    <i class="fas fa-plus-circle"></i> Ongeza Mchango
                </a>
                <button class="btn-sm btn-info" onclick="copyInviteLink()">
                    <i class="fas fa-link"></i> Kiungo
                </button>
            </div>
        </div>
        
        @php
            $totalCollected = $event->contributions()->where('contributions.status', 'approved')->sum('contributions.amount');
            $totalPending = $event->contributions()->where('contributions.status', 'pending')->sum('contributions.amount');
            $percentage = $event->target_amount > 0 ? min(round(($totalCollected / $event->target_amount) * 100), 100) : 0;
            $contributorCount = $event->contributors()->count();
            $contributionCount = $event->contributions()->count();
            $highestContribution = $event->contributions()->max('contributions.amount') ?? 0;
        @endphp
        
        <!-- Two Column Info -->
        <div class="two-column">
            <div class="info-box">
                <h6><i class="fas fa-info-circle"></i> Maelezo ya Tukio</h6>
                <p>{{ $event->description ?? 'Hakuna maelezo ya ziada kwa tukio hili.' }}</p>
            </div>
            <div class="info-box">
                <h6><i class="fas fa-chart-simple"></i> Taarifa za Tukio</h6>
                <div class="info-row">
                    <span class="info-label">Lengo</span>
                    <span class="info-value">{{ number_format($event->target_amount ?? 0) }} TSh</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Hali</span>
                    <span class="status-badge status-active">
                        <i class="fas {{ $event->status == 'active' ? 'fa-play' : ($event->status == 'completed' ? 'fa-check-circle' : 'fa-times-circle') }}"></i>
                        {{ $event->status == 'active' ? 'Inaendelea' : ($event->status == 'completed' ? 'Imekamilika' : 'Imefutwa') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Imeundwa</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($event->created_at)->format('d M, Y') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Progress Section -->
        <div class="progress-section">
            <div class="progress-header">
                <span>Maendeleo ya Ukusanyaji</span>
                <span class="progress-percent">{{ $percentage }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
            </div>
            <div class="progress-stats">
                <span>Imekusanywa: <strong>{{ number_format($totalCollected) }} TSh</strong></span>
                <span>Lengo: {{ number_format($event->target_amount ?? 0) }} TSh</span>
            </div>
            
            @if($totalPending > 0)
            <div class="pending-alert">
                <i class="fas fa-clock"></i> Inasubiri: {{ number_format($totalPending) }} TSh
            </div>
            @endif
            
            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-label">Wachangiaji</span>
                    <span class="stat-number">{{ $contributorCount }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Michango</span>
                    <span class="stat-number">{{ $contributionCount }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Mchango wa Juu</span>
                    <span class="stat-number">{{ number_format($highestContribution) }} TSh</span>
                </div>
            </div>
        </div>
        
        <!-- Tabs Navigation -->
        <div class="tabs-nav">
            <button class="tab-btn active" data-tab="contributions">
                <i class="fas fa-hand-holding-heart"></i> Michango <span style="background: #E5E7EB; padding: 0.125rem 0.375rem; border-radius: 20px; font-size: 0.6rem;">{{ $contributionCount }}</span>
            </button>
            <button class="tab-btn" data-tab="contributors">
                <i class="fas fa-users"></i> Wachangiaji <span style="background: #E5E7EB; padding: 0.125rem 0.375rem; border-radius: 20px; font-size: 0.6rem;">{{ $contributorCount }}</span>
            </button>
        </div>
        
        <div class="tab-content">
            <!-- Contributions Tab -->
            <div class="tab-pane active" id="contributions">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mchangiaji</th>
                                <th>Simu</th>
                                <th class="text-end">Kiasi (TSh)</th>
                                <th class="text-center">Njia</th>
                                <th class="text-center">Hali</th>
                                <th>Tarehe</th>
                                @if(auth()->user()->canApproveContributions())
                                    <th class="text-center">Kitendo</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @forelse($event->contributions()->with('contributor')->latest()->get() as $contribution)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td><strong>{{ $contribution->contributor->name }}</strong></td>
                                <td>{{ $contribution->contributor->phone }}</td>
                                <td class="text-end fw-bold" style="color: var(--primary);">{{ number_format($contribution->amount) }} TSh</td>
                                <td class="text-center">{{ $contribution->payment_method_display }}</td>
                                <td class="text-center">
                                    @if($contribution->status == 'pending')
                                        <span class="badge-pending"><i class="fas fa-clock"></i> Inasubiri</span>
                                    @else
                                        <span class="badge-approved"><i class="fas fa-check-circle"></i> Imethibitishwa</span>
                                    @endif
                                </td>
                                <td><small>{{ \Carbon\Carbon::parse($contribution->created_at)->format('d/m/Y H:i') }}</small></td>
                                @if(auth()->user()->canApproveContributions())
                                    <td class="text-center">
                                        @if($contribution->status == 'pending')
                                            <div class="d-flex gap-1 justify-content-center">
                                                <form method="POST" action="{{ route('contributions.approve', $contribution) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-sm" style="background:#D1FAE5; color:#10B981; border:none; padding:0.25rem 0.5rem; border-radius:4px; cursor:pointer;" onclick="return confirm('Thibitisha mchango huu?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('contributions.reject', $contribution) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-sm" style="background:#FEE2E2; color:#EF4444; border:none; padding:0.25rem 0.5rem; border-radius:4px; cursor:pointer;" onclick="return confirm('Kataa mchango huu?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ auth()->user()->canApproveContributions() ? '8' : '7' }}" class="empty-state">
                                    <i class="fas fa-hand-holding-heart"></i>
                                    <p>Hakuna michango bado</p>
                                    <a href="{{ route('contributors.create', $event) }}" class="btn-sm btn-primary">Ongeza Mchango</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($contributionCount > 0)
                        <tfoot class="tfoot-summary">
                            <tr>
                                <td colspan="3" class="fw-semibold">Jumla</td>
                                <td class="text-end fw-bold">{{ number_format($event->contributions()->sum('amount')) }} TSh</td>
                                <td colspan="{{ auth()->user()->canApproveContributions() ? '4' : '3' }}"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
            
            <!-- Contributors Tab -->
            <div class="tab-pane" id="contributors">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jina</th>
                                <th>Simu</th>
                                <th class="text-end">Alichoahidi</th>
                                <th class="text-end">Alicholipa</th>
                                <th class="text-end">Mabaki</th>
                                <th class="text-center">Hali</th>
                                <th class="text-center">Kitendo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @forelse($event->contributors()->with('contributions')->latest()->get() as $contributor)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>
                                    <strong>{{ $contributor->name }}</strong>
                                    @if($contributor->email)<br><small class="text-secondary">{{ $contributor->email }}</small>@endif
                                </td>
                                <td>{{ $contributor->phone }}</td>
                                <td class="text-end">{{ number_format($contributor->promised_amount) }} TSh</td>
                                <td class="text-end text-success">{{ number_format($contributor->paid_amount) }} TSh</td>
                                <td class="text-end text-warning">{{ number_format($contributor->remaining_amount) }} TSh</td>
                                <td class="text-center">
                                    @if($contributor->status == 'completed')
                                        <span class="badge-approved"><i class="fas fa-check-circle"></i> Imekamilika</span>
                                    @elseif($contributor->status == 'partial')
                                        <span class="badge-pending"><i class="fas fa-hourglass-half"></i> Sehemu</span>
                                    @else
                                        <span class="badge-pending"><i class="fas fa-clock"></i> Bado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($contributor->remaining_amount > 0)
                                        <button class="btn-sm" style="background:var(--primary-light); color:var(--primary); border:none; padding:0.25rem 0.5rem; border-radius:4px; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $contributor->id }}">
                                            <i class="fas fa-plus-circle"></i> Ongeza
                                        </button>
                                    @else
                                        <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Payment Modal -->
                            <div class="modal fade" id="paymentModal{{ $contributor->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('contributors.add.payment', ['event' => $event->id, 'contributor' => $contributor->id]) }}">
                                            @csrf
                                            <div class="modal-header">
                                                <h6 class="modal-title">Ongeza Malipo - {{ $contributor->name }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <small class="text-secondary">Walioahidi:</small>
                                                        <div class="fw-semibold">{{ number_format($contributor->promised_amount) }} TSh</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-secondary">Waliolipa:</small>
                                                        <div class="fw-semibold text-success">{{ number_format($contributor->paid_amount) }} TSh</div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <small class="text-secondary">Mabaki:</small>
                                                    <div class="fw-bold" style="color: var(--warning); font-size: 1rem;">{{ number_format($contributor->remaining_amount) }} TSh</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Kiasi cha Malipo <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text" style="background: var(--bg-light);">TSh</span>
                                                        <input type="number" name="amount" class="form-control" min="1000" max="{{ $contributor->remaining_amount }}" step="1000" required>
                                                    </div>
                                                    <small class="text-secondary">Angalau TSh 1,000</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Njia ya Malipo</label>
                                                    <select name="payment_method" class="form-select">
                                                        <option value="cash">💰 Fedha Taslimu</option>
                                                        <option value="mpesa">📱 M-Pesa</option>
                                                        <option value="bank">🏦 Benki</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-semibold">Maelezo (hiari)</label>
                                                    <textarea name="notes" class="form-control" rows="2" placeholder="Maelezo ya ziada..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Ghairi</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Hifadhi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <p>Hakuna wachangiaji bado</p>
                                    <a href="{{ route('contributors.create', $event) }}" class="btn-sm btn-primary">Ongeza Mchangiaji wa Kwanza</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($contributorCount > 0)
                        <tfoot class="tfoot-summary">
                            <tr>
                                <td colspan="3" class="fw-semibold">Jumla</td>
                                <td class="text-end fw-bold">{{ number_format($event->contributors()->sum('promised_amount')) }} TSh</td>
                                <td class="text-end fw-bold text-success">{{ number_format($event->contributors()->sum('paid_amount')) }} TSh</td>
                                <td class="text-end fw-bold text-warning">{{ number_format($event->contributors()->sum('remaining_amount')) }} TSh</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back Link -->
    <div class="back-link">
        <a href="{{ route('dashboard') }}">
            <i class="fas fa-arrow-left"></i> Rudi Dashboard
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
        const tabs = document.querySelectorAll('.tab-btn');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabId = tab.dataset.tab;
                tabs.forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
    });
    
    function copyInviteLink() {
        const inviteLink = "{{ route('events.show', $event) }}";
        navigator.clipboard.writeText(inviteLink).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Imekopiwa!',
                text: 'Kiungo cha mwaliko kimekopiwa kwenye clipboard',
                timer: 2000,
                showConfirmButton: false
            });
        }).catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Hitilafu',
                text: 'Tafadhali nakili kiungo kwa mkono: ' + inviteLink,
                confirmButtonColor: '#FF6F00'
            });
        });
    }
</script>
@endsection