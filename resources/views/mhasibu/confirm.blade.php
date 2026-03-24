@extends('layouts.app')

@section('title', 'Thibitisha Michango')
@section('page_title', 'Thibitisha Michango')

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
        padding: 0.75rem 1rem;
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
    
    /* Stats Grid */
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
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
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
    
    .stat-value.pending {
        color: var(--warning);
    }
    
    .stat-value.approved {
        color: var(--success);
    }
    
    .stat-value.total {
        color: var(--primary);
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
    
    .stat-icon-box.pending i {
        color: var(--warning);
    }
    
    .stat-icon-box.approved i {
        color: var(--success);
    }
    
    .stat-icon-box.total i {
        color: var(--primary);
    }
    
    /* Card */
    .card-custom {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    .card-header-custom {
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        background: white;
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
        color: var(--warning);
    }
    
    .card-header-custom .badge-count {
        background: var(--warning);
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    
    .card-header-custom small {
        font-size: 0.65rem;
        color: var(--text-muted);
    }
    
    /* Table */
    .table-responsive {
        overflow-x: auto;
    }
    
    .contributions-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .contributions-table thead {
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-color);
    }
    
    .contributions-table th {
        padding: 0.625rem 1rem;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        text-align: left;
    }
    
    .contributions-table td {
        padding: 0.75rem 1rem;
        font-size: 0.7rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    
    .contributions-table tbody tr:hover {
        background: var(--bg-light);
    }
    
    .contributor-info {
        display: flex;
        flex-direction: column;
    }
    
    .contributor-name {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.75rem;
    }
    
    .contributor-phone {
        font-size: 0.6rem;
        color: var(--text-muted);
        margin-top: 0.125rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .event-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.7rem;
    }
    
    .event-date {
        font-size: 0.6rem;
        color: var(--text-muted);
        margin-top: 0.125rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .amount {
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--primary);
    }
    
    .payment-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.5rem;
        font-size: 0.6rem;
        font-weight: 600;
        border-radius: 20px;
        white-space: nowrap;
    }
    
    .payment-cash {
        background: var(--info-light);
        color: var(--info);
    }
    
    .payment-mpesa {
        background: var(--success-light);
        color: var(--success);
    }
    
    .payment-bank {
        background: var(--primary-light);
        color: var(--primary);
    }
    
    .payment-pending {
        background: var(--warning-light);
        color: var(--warning);
    }
    
    .date-info {
        font-size: 0.65rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.25rem;
        white-space: nowrap;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .btn-approve {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.3rem 0.75rem;
        font-size: 0.6rem;
        font-weight: 600;
        color: white;
        background: var(--success);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .btn-approve:hover {
        background: #059669;
        transform: translateY(-1px);
    }
    
    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.3rem 0.75rem;
        font-size: 0.6rem;
        font-weight: 600;
        color: white;
        background: var(--danger);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .btn-reject:hover {
        background: #DC2626;
        transform: translateY(-1px);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1.5rem;
    }
    
    .empty-state i {
        font-size: 2rem;
        color: var(--text-muted);
        opacity: 0.3;
        margin-bottom: 0.75rem;
        display: block;
    }
    
    .empty-state h6 {
        font-weight: 700;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }
    
    .empty-state p {
        font-size: 0.7rem;
        color: var(--text-secondary);
        max-width: 400px;
        margin: 0 auto;
    }
    
    /* Loading State for Buttons */
    .btn-loading {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }
    
    .spinner-small {
        display: inline-block;
        width: 10px;
        height: 10px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        margin-right: 0.25rem;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid {
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
        
        .stat-card {
            padding: 0.625rem 0.875rem;
        }
        
        .stat-value {
            font-size: 1rem;
        }
        
        .card-header-custom {
            flex-direction: column;
            text-align: center;
        }
        
        .contributions-table th,
        .contributions-table td {
            padding: 0.5rem 0.75rem;
            white-space: nowrap;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-approve, .btn-reject {
            justify-content: center;
            width: 100%;
        }
        
        .notification-container {
            min-width: 280px;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
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
    
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    <i class="fas fa-clock"></i> Yanayosubiri
                </div>
                <div class="stat-value pending">{{ number_format($pendingCount) }}</div>
            </div>
            <div class="stat-icon-box pending">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    <i class="fas fa-check-circle"></i> Yaliyothibitishwa
                </div>
                <div class="stat-value approved">{{ number_format($approvedCount) }}</div>
            </div>
            <div class="stat-icon-box approved">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    <i class="fas fa-hand-holding-usd"></i> Jumla ya Michango
                </div>
                <div class="stat-value total">{{ number_format($totalAmount / 1000) }}K <small>TSh</small></div>
            </div>
            <div class="stat-icon-box total">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    <i class="fas fa-calendar-alt"></i> Matukio Yako
                </div>
                <div class="stat-value">{{ number_format($eventsCount) }}</div>
            </div>
            <div class="stat-icon-box">
                <i class="fas fa-calendar"></i>
            </div>
        </div>
    </div>
    
    <!-- Pending Contributions Card -->
    <div class="card-custom">
        <div class="card-header-custom">
            <h6>
                <i class="fas fa-hourglass-half"></i>
                Michango Inayosubiri Kuthibitishwa
                @if($pendingCount > 0)
                    <span class="badge-count">{{ $pendingCount }}</span>
                @endif
            </h6>
            <small><i class="fas fa-info-circle"></i> Thibitisha au kataa michango ya wachangiaji</small>
        </div>
        
        <div class="table-responsive">
            @if($pendingContributions->count() > 0)
                <table class="contributions-table">
                    <thead>
                        <tr>
                            <th>Mchangiaji</th>
                            <th>Tukio</th>
                            <th>Kiasi</th>
                            <th>Njia ya Malipo</th>
                            <th>Tarehe ya Malipo</th>
                            <th>Kitendo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingContributions as $contribution)
                        <tr id="contribution-{{ $contribution->id }}">
                            <td>
                                <div class="contributor-info">
                                    <span class="contributor-name">{{ $contribution->contributor->name }}</span>
                                    <span class="contributor-phone">
                                        <i class="fas fa-phone-alt fa-xs"></i> {{ $contribution->contributor->phone }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="contributor-info">
                                    <span class="event-name">{{ $contribution->contributor->event->event_name }}</span>
                                    <span class="event-date">
                                        <i class="fas fa-calendar fa-xs"></i> {{ \Carbon\Carbon::parse($contribution->contributor->event->event_date)->format('d M Y') }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="amount">{{ number_format($contribution->amount) }} TSh</span>
                            </td>
                            <td>
                                @php
                                    $paymentClass = 'payment-pending';
                                    $paymentIcon = '⏳';
                                    $paymentText = 'Inasubiri';
                                    
                                    if($contribution->payment_method == 'cash') {
                                        $paymentClass = 'payment-cash';
                                        $paymentIcon = '💵';
                                        $paymentText = 'Taslimu';
                                    } elseif($contribution->payment_method == 'mpesa') {
                                        $paymentClass = 'payment-mpesa';
                                        $paymentIcon = '📱';
                                        $paymentText = 'M-Pesa';
                                    } elseif($contribution->payment_method == 'bank') {
                                        $paymentClass = 'payment-bank';
                                        $paymentIcon = '🏦';
                                        $paymentText = 'Benki';
                                    }
                                @endphp
                                <span class="payment-badge {{ $paymentClass }}">
                                    {{ $paymentIcon }} {{ $paymentText }}
                                </span>
                            </td>
                            <td>
                                <span class="date-info">
                                    <i class="fas fa-clock fa-xs"></i> {{ \Carbon\Carbon::parse($contribution->created_at)->format('d M Y, H:i') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn-approve" onclick="confirmApprove({{ $contribution->id }}, '{{ addslashes($contribution->contributor->name) }}', '{{ number_format($contribution->amount) }}')">
                                        <i class="fas fa-check-circle"></i> Thibitisha
                                    </button>
                                    <button type="button" class="btn-reject" onclick="confirmReject({{ $contribution->id }}, '{{ addslashes($contribution->contributor->name) }}', '{{ number_format($contribution->amount) }}')">
                                        <i class="fas fa-times-circle"></i> Kataa
                                    </button>
                                </div>
                                <form id="approve-form-{{ $contribution->id }}" method="POST" action="{{ route('mhasibu.approve', $contribution->id) }}" style="display: none;">
                                    @csrf
                                </form>
                                <form id="reject-form-{{ $contribution->id }}" method="POST" action="{{ route('mhasibu.reject', $contribution->id) }}" style="display: none;">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <h6>Hakuna Michango Inayosubiri</h6>
                    <p>Michango yote imethibitishwa. Endelea kufanya kazi nzuri!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            case 'warning':
                icon = '<i class="fas fa-exclamation-triangle"></i>';
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
    
    // SweetAlert2 confirmation for approve
    function confirmApprove(id, contributorName, amount) {
        Swal.fire({
            title: 'Thibitisha Malipo?',
            html: `Je, una uhakika unataka kuthibitisha malipo ya <strong>${amount} TSh</strong> kutoka kwa <strong>${contributorName}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-check-circle"></i> Ndio, Thibitisha',
            cancelButtonText: '<i class="fas fa-times"></i> Ghairi',
            reverseButtons: true,
            background: 'white',
            customClass: {
                popup: 'rounded-3',
                title: 'fw-bold',
                confirmButton: 'btn-approve',
                cancelButton: 'btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Inathibitisha...',
                    text: 'Tafadhali subiri',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                const form = document.getElementById(`approve-form-${id}`);
                if (form) {
                    form.submit();
                }
            }
        });
    }
    
    // SweetAlert2 confirmation for reject
    function confirmReject(id, contributorName, amount) {
        Swal.fire({
            title: 'Kataa Malipo?',
            html: `Je, una uhakika unataka kukataa malipo ya <strong>${amount} TSh</strong> kutoka kwa <strong>${contributorName}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-times-circle"></i> Ndio, Kataa',
            cancelButtonText: '<i class="fas fa-times"></i> Ghairi',
            reverseButtons: true,
            background: 'white',
            customClass: {
                popup: 'rounded-3',
                title: 'fw-bold',
                confirmButton: 'btn-reject',
                cancelButton: 'btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Inakataa...',
                    text: 'Tafadhali subiri',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                const form = document.getElementById(`reject-form-${id}`);
                if (form) {
                    form.submit();
                }
            }
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Imefanikiwa!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#FF6F00',
                confirmButtonText: '<i class="fas fa-check"></i> Sawa',
                timer: 3000,
                timerProgressBar: true
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                title: 'Hitilafu!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#FF6F00',
                confirmButtonText: '<i class="fas fa-times"></i> Sawa'
            });
        @endif
        
        @if(session('warning'))
            Swal.fire({
                title: 'Tahadhari!',
                text: '{{ session('warning') }}',
                icon: 'warning',
                confirmButtonColor: '#FF6F00',
                confirmButtonText: '<i class="fas fa-check"></i> Sawa'
            });
        @endif
    });
</script>
@endpush