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
    
    /* Fix scrolling */
    .main-content {
        overflow-y: auto !important;
        height: calc(100vh - var(--topbar-h, 60px));
        padding-bottom: 30px;
    }
    
    /* Full width container */
    .confirm-container {
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
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: white;
        border-radius: var(--radius-md);
        padding: 16px;
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
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
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
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
    
    .stat-icon-box.pending {
        background: var(--warning-light);
        color: var(--warning);
    }
    
    .stat-icon-box.approved {
        background: var(--success-light);
        color: var(--success);
    }
    
    .stat-icon-box.total {
        background: var(--primary-light);
        color: var(--primary);
    }
    
    /* Single Card - FULL WIDTH */
    .single-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        width: 100%;
    }
    
    /* Card Header */
    .card-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-color);
        background: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .card-header h5 {
        font-weight: 700;
        margin-bottom: 0;
        font-size: 0.9rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .card-header h5 i {
        color: var(--warning);
    }
    
    .badge-count {
        background: var(--warning);
        color: white;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        margin-left: 6px;
    }
    
    .card-header small {
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
        padding: 12px 16px;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        text-align: left;
    }
    
    .contributions-table td {
        padding: 12px 16px;
        font-size: 0.75rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    
    .contributions-table tbody tr:hover {
        background: var(--bg-light);
    }
    
    .contributor-name {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.8rem;
        margin-bottom: 2px;
    }
    
    .contributor-phone {
        font-size: 0.65rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .event-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.75rem;
        margin-bottom: 2px;
    }
    
    .event-date {
        font-size: 0.6rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .amount {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--primary);
    }
    
    .payment-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        font-size: 0.65rem;
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
        gap: 4px;
        white-space: nowrap;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .btn-approve {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        font-size: 0.65rem;
        font-weight: 600;
        color: white;
        background: var(--success);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-approve:hover {
        background: #059669;
        transform: translateY(-1px);
    }
    
    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        font-size: 0.65rem;
        font-weight: 600;
        color: white;
        background: var(--danger);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-reject:hover {
        background: #DC2626;
        transform: translateY(-1px);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: var(--text-muted);
        opacity: 0.3;
        margin-bottom: 16px;
        display: block;
    }
    
    .empty-state h6 {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 8px;
        color: var(--text-primary);
    }
    
    .empty-state p {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0;
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
    
    /* Responsive */
    @media (max-width: 1024px) {
        .confirm-container {
            padding: 20px 24px;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
    }
    
    @media (max-width: 768px) {
        .confirm-container {
            padding: 16px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .stat-card {
            padding: 12px;
        }
        
        .stat-value {
            font-size: 1.1rem;
        }
        
        .stat-icon-box {
            width: 36px;
            height: 36px;
        }
        
        .stat-icon-box i {
            font-size: 1rem;
        }
        
        .card-header {
            flex-direction: column;
            text-align: center;
            padding: 12px 16px;
        }
        
        .contributions-table th,
        .contributions-table td {
            padding: 8px 12px;
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
            top: 70px;
        }
    }
    
    @media (max-width: 480px) {
        .confirm-container {
            padding: 12px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="confirm-container">
    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h4>Thibitisha Michango</h4>
            <p>Thibitisha au kataa michango ya wachangiaji</p>
        </div>
    </div>
    
    <!-- Notification Container -->
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
                <div class="stat-value total">{{ number_format($totalAmount / 1000, 1) }}K <small>TSh</small></div>
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
            <div class="stat-icon-box total">
                <i class="fas fa-calendar"></i>
            </div>
        </div>
    </div>
    
    <!-- Single Card -->
    <div class="single-card">
        <div class="card-header">
            <h5>
                <i class="fas fa-hourglass-half"></i>
                Michango Inayosubiri Kuthibitishwa
                @if($pendingCount > 0)
                    <span class="badge-count">{{ $pendingCount }}</span>
                @endif
            </h5>
            <small><i class="fas fa-info-circle"></i> Thibitisha au kataa michango ya wachangiaji</small>
        </div>
        
        @if($pendingContributions->count() > 0)
            <div class="table-responsive">
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
                                <div class="contributor-name">{{ $contribution->contributor->name }}</div>
                                <div class="contributor-phone">
                                    <i class="fas fa-phone-alt"></i> {{ $contribution->contributor->phone }}
                                </div>
                            </td>
                            <td>
                                <div class="event-name">{{ $contribution->contributor->event->event_name }}</div>
                                <div class="event-date">
                                    <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($contribution->contributor->event->event_date)->format('d M Y') }}
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
                                    <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($contribution->created_at)->format('d M Y, H:i') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn-approve" onclick="confirmApprove({{ $contribution->id }})">
                                        <i class="fas fa-check-circle"></i> Thibitisha
                                    </button>
                                    <button type="button" class="btn-reject" onclick="confirmReject({{ $contribution->id }})">
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
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <h6>Hakuna Michango Inayosubiri</h6>
                <p>Michango yote imethibitishwa. Endelea kufanya kazi nzuri!</p>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
    
    function confirmApprove(id) {
        Swal.fire({
            title: 'Thibitisha Malipo?',
            text: "Je, una uhakika unataka kuthibitisha malipo haya?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-check-circle"></i> Ndio, Thibitisha',
            cancelButtonText: '<i class="fas fa-times"></i> Ghairi'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Inathibitisha...',
                    text: 'Tafadhali subiri',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                document.getElementById(`approve-form-${id}`).submit();
            }
        });
    }
    
    function confirmReject(id) {
        Swal.fire({
            title: 'Kataa Malipo?',
            text: "Je, una uhakika unataka kukataa malipo haya?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-times-circle"></i> Ndio, Kataa',
            cancelButtonText: '<i class="fas fa-times"></i> Ghairi'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Inakataa...',
                    text: 'Tafadhali subiri',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                document.getElementById(`reject-form-${id}`).submit();
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
    });
</script>
@endsection