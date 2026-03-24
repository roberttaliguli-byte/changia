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
    }
    
    /* Centered Notification */
    .notification-container {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        width: auto;
        min-width: 320px;
        max-width: 90%;
        pointer-events: none;
    }
    
    .notification {
        background: white;
        border-radius: var(--radius-md);
        padding: 0.875rem 1.125rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
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
    
    .notification i:first-child {
        font-size: 1rem;
        flex-shrink: 0;
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
        transition: opacity 0.2s;
        font-size: 0.75rem;
        flex-shrink: 0;
        color: var(--text-muted);
    }
    
    /* Single Card Container */
    .single-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }
    
    /* Event Header - Compact */
    .event-header {
        padding: 1rem 1.25rem;
        background: white;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .event-info h6 {
        font-weight: 700;
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
        color: var(--text-primary);
    }
    
    .event-info small {
        font-size: 0.7rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    
    .btn-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: white;
        background: var(--primary);
        border: none;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .btn-primary-custom:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }
    
    .btn-pdf {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--danger);
        background: var(--danger-light);
        border: none;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .btn-pdf:hover {
        background: var(--danger);
        color: white;
        transform: translateY(-1px);
    }
    
    /* Stats Grid - Compact like Dashboard */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.875rem;
        padding: 1rem 1.25rem;
        background: white;
        border-bottom: 1px solid var(--border-color);
    }
    
    .stat-card {
        background: var(--bg-light);
        border-radius: var(--radius-md);
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
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
        font-size: 0.6rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .stat-value {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }
    
    .stat-value small {
        font-size: 0.6rem;
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
        width: 32px;
        height: 32px;
        background: rgba(255, 111, 0, 0.1);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .stat-icon-box i {
        font-size: 0.9rem;
        color: var(--primary);
    }
    
    /* Progress Section - Compact */
    .progress-section {
        padding: 0.875rem 1.25rem;
        background: white;
        border-bottom: 1px solid var(--border-color);
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.375rem;
    }
    
    .progress-label {
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .progress-percent {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    .progress-bar-custom {
        height: 5px;
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
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .table-header h6 {
        font-weight: 700;
        margin-bottom: 0;
        font-size: 0.75rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .table-header h6 i {
        color: var(--primary);
    }
    
    .table-header small {
        font-size: 0.65rem;
        color: var(--text-muted);
        font-weight: 500;
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
        padding: 0.625rem 1rem;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .contributors-table td {
        padding: 0.625rem 1rem;
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
        margin-top: 0.125rem;
    }
    
    .amount-promised {
        font-weight: 600;
        color: var(--text-primary);
        text-align: right;
        font-size: 0.7rem;
    }
    
    .amount-paid {
        font-weight: 600;
        color: var(--success);
        text-align: right;
        font-size: 0.7rem;
    }
    
    .amount-remaining {
        font-weight: 600;
        color: var(--warning);
        text-align: right;
        font-size: 0.7rem;
    }
    
    .badge-completed {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.5rem;
        font-size: 0.6rem;
        font-weight: 600;
        color: var(--success);
        background: var(--success-light);
        border-radius: 20px;
    }
    
    .btn-outline-primary-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.625rem;
        font-size: 0.6rem;
        font-weight: 600;
        color: var(--primary);
        background: white;
        border: 1px solid var(--primary);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-outline-primary-custom:hover {
        background: var(--primary);
        color: white;
    }
    
    /* Table Footer */
    .table-footer {
        padding: 0.875rem 1.25rem;
        border-top: 1px solid var(--border-color);
        background: white;
    }
    
    /* Back Link */
    .back-link {
        text-align: center;
        margin-top: 1.25rem;
    }
    
    .back-link a {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: color 0.2s;
    }
    
    .back-link a:hover {
        color: var(--primary);
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
        padding: 0.875rem 1rem;
    }
    
    .modal-header h6 {
        font-size: 0.8rem;
        font-weight: 700;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 0.75rem 1rem;
    }
    
    /* Loading State */
    .btn-loading {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .spinner-small {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        margin-right: 0.375rem;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Pagination */
    .pagination {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
        margin-bottom: 0;
        justify-content: center;
    }
    
    .pagination .page-link {
        padding: 0.3rem 0.6rem;
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
    @media (max-width: 768px) {
        .pg-wrap {
            padding: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.625rem;
            padding: 0.875rem 1rem;
        }
        
        .event-header {
            flex-direction: column;
            text-align: center;
            padding: 0.875rem 1rem;
        }
        
        .btn-group {
            width: 100%;
            justify-content: center;
        }
        
        .contributors-table th,
        .contributors-table td {
            padding: 0.5rem 0.75rem;
            white-space: nowrap;
        }
        
        .table-header {
            flex-direction: column;
            text-align: center;
        }
        
        .notification-container {
            min-width: 280px;
        }
        
        .stat-card {
            padding: 0.625rem 0.875rem;
        }
        
        .stat-value {
            font-size: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .stat-icon-box {
            width: 28px;
            height: 28px;
        }
        
        .stat-icon-box i {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="pg-wrap">
    <!-- Centered Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>
    
    <!-- Single Card Container -->
    <div class="single-card">
        <!-- Event Header -->
        <div class="event-header">
            <div class="event-info">
                <h6>{{ $event->event_name }}</h6>
                <small><i class="far fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</small>
            </div>
            <div class="btn-group">
                <a href="{{ route('contributors.create', $event) }}" class="btn-primary-custom">
                    <i class="fas fa-plus-circle"></i> Ongeza Mchangiaji
                </a>
                <button onclick="exportToPDF()" class="btn-pdf">
                    <i class="fas fa-file-pdf"></i> Pakua PDF
                </button>
            </div>
        </div>
        
        <!-- Stats Section - Compact like Dashboard -->
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
        
        <!-- Progress Section -->
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
        
        <!-- Table Section -->
        <div class="table-section" id="pdf-content">
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
                            <th class="text-end">Alichoahidi</th>
                            <th class="text-end">Alicholipa</th>
                            <th class="text-end">Mabaki</th>
                            @if(!request()->get('pdf'))
                            <th class="text-center">Kitendo</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = ($contributors->currentPage() - 1) * $contributors->perPage() + 1; @endphp
                        @forelse($contributors as $contributor)
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td>
                                <div class="contributor-name">{{ $contributor->name }}</div>
                                @if($contributor->email)
                                    <div class="contributor-email">{{ $contributor->email }}</div>
                                @endif
                            </td>
                            <td>{{ $contributor->phone }}</td>
                            <td class="amount-promised text-end">{{ number_format($contributor->promised_amount) }} TSh</td>
                            <td class="amount-paid text-end">{{ number_format($contributor->paid_amount) }} TSh</td>
                            <td class="amount-remaining text-end">{{ number_format($contributor->remaining_amount) }} TSh</td>
                            @if(!request()->get('pdf'))
                            <td class="text-center">
                                @if($contributor->remaining_amount > 0)
                                    <button class="btn-outline-primary-custom" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $contributor->id }}">
                                        <i class="fas fa-plus-circle"></i> Ongeza
                                    </button>
                                @else
                                    <span class="badge-completed">
                                        <i class="fas fa-check-circle"></i> Imekamilika
                                    </span>
                                @endif
                            </td>
                            @endif
                        </tr>

                        <!-- Simple Payment Modal -->
                        <div class="modal fade" id="paymentModal{{ $contributor->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('contributors.add.payment', ['event' => $event->id, 'contributor' => $contributor->id]) }}" class="payment-form">
                                        @csrf
                                        <div class="modal-header">
                                            <h6 class="modal-title">Ongeza Malipo</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.7rem;">Mabaki</label>
                                                <div class="alert alert-warning py-2 mb-0" style="background: var(--warning-light); border-color: var(--warning); padding: 0.5rem;">
                                                    <strong class="d-block text-center" style="font-size: 0.9rem;">{{ number_format($contributor->remaining_amount) }} TSh</strong>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold" style="font-size: 0.7rem;">Kiasi cha Malipo <span class="text-danger">*</span></label>
                                                <input type="number" 
                                                       name="amount" 
                                                       class="form-control form-control-sm" 
                                                       min="1000" 
                                                       max="{{ $contributor->remaining_amount }}" 
                                                       step="1000" 
                                                       placeholder="Weka kiasi" 
                                                       required>
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
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-users fa-2x" style="color: var(--text-muted); opacity: 0.3; margin-bottom: 0.5rem; display: block;"></i>
                                <p class="text-muted mb-0" style="font-size: 0.75rem;">Hakuna wachangiaji bado</p>
                                <a href="{{ route('contributors.create', $event) }}" class="btn-primary-custom mt-2" style="display: inline-flex;">
                                    <i class="fas fa-plus-circle me-1"></i>Ongeza Mchangiaji wa Kwanza
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($contributors->count() > 0)
                        <tfoot style="background: var(--bg-light); font-weight: 600;">
                            <tr>
                                <td colspan="3" class="text-end" style="font-size: 0.7rem;">Jumla:</td>
                                <td class="text-end" style="font-size: 0.7rem;">{{ number_format($totalPromised) }} TSh</td>
                                <td class="text-end" style="color: var(--success); font-size: 0.7rem;">{{ number_format($totalPaid) }} TSh</td>
                                <td class="text-end" style="color: var(--warning); font-size: 0.7rem;">{{ number_format($totalRemaining) }} TSh</td>
                                @if(!request()->get('pdf'))
                                <td></td>
                                @endif
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
            
            @if($contributors->hasPages() && !request()->get('pdf'))
                <div class="table-footer">
                    {{ $contributors->links() }}
                </div>
            @endif
        </div>
    </div>
    
    <!-- Back Link -->
    <div class="back-link">
        <a href="{{ route('events.show', $event) }}">
            <i class="fas fa-arrow-left"></i> Rudi kwenye Tukio
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // Auto-disappearing notifications
    function showNotification(message, type = 'success') {
        const container = document.getElementById('notificationContainer');
        if (!container) return;
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        let icon = '';
        switch(type) {
            case 'success':
                icon = '<i class="fas fa-check-circle"></i>';
                break;
            case 'error':
                icon = '<i class="fas fa-exclamation-circle"></i>';
                break;
            default:
                icon = '<i class="fas fa-info-circle"></i>';
        }
        
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
                setTimeout(() => {
                    if (notification.parentNode) notification.remove();
                }, 300);
            }
        }, 3000);
        
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    if (notification.parentNode) notification.remove();
                }, 300);
            });
        }
    }
    
    // PDF Export Function
    function exportToPDF() {
        const element = document.getElementById('pdf-content');
        const eventName = "{{ $event->event_name }}";
        const eventDate = "{{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}";
        
        showNotification('Inaandaa PDF... Tafadhali subiri', 'info');
        
        const opt = {
            margin: [0.5, 0.5, 0.5, 0.5],
            filename: `Wachangiaji_${eventName}_${eventDate}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, letterRendering: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
        };
        
        const cloneElement = element.cloneNode(true);
        
        const buttonsToRemove = cloneElement.querySelectorAll('.btn-outline-primary-custom, .badge-completed');
        buttonsToRemove.forEach(btn => {
            const parentCell = btn.closest('td');
            if (parentCell) {
                parentCell.innerHTML = '';
            }
        });
        
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
                submitBtn.innerHTML = '<span class="spinner-small"></span> Inahifadhi...';
                submitBtn.classList.add('btn-loading');
            });
        });
    });
</script>
@endpush