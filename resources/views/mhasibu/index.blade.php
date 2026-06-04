@extends('layouts.app')

@section('title', 'Wahasibu')
@section('page_title', 'Wahasibu')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #FF6F00;
        --primary-light: #FFF3E0;
        --primary-dark: #E65100;
        --success: #10B981;
        --success-light: #D1FAE5;
        --danger: #EF4444;
        --danger-light: #FEE2E2;
        --warning: #F59E0B;
        --warning-light: #FEF3C7;
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
    .accountants-container {
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
    }
    
    .card-header h5 {
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 0.9rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .card-header h5 i {
        color: var(--primary);
    }
    
    .card-header p {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-bottom: 0;
    }
    
    /* Accountant Cards List */
    .accountants-list {
        padding: 0;
    }
    
    .accountant-item {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .accountant-item:hover {
        background: var(--bg-light);
    }
    
    .accountant-item:last-child {
        border-bottom: none;
    }
    
    .accountant-info {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        flex: 1;
    }
    
    .avatar {
        width: 48px;
        height: 48px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        flex-shrink: 0;
    }
    
    .avatar i {
        font-size: 1.3rem;
    }
    
    .accountant-details h6 {
        font-weight: 700;
        margin-bottom: 6px;
        font-size: 0.85rem;
        color: var(--text-primary);
    }
    
    .details-row {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 0.7rem;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }
    
    .details-row span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .details-row i {
        width: 14px;
        color: var(--primary);
    }
    
    .event-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }
    
    .event-badge {
        padding: 3px 10px;
        font-size: 0.65rem;
        font-weight: 500;
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        color: var(--text-secondary);
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .event-badge i {
        font-size: 0.6rem;
        color: var(--primary);
    }
    
    .btn-remove {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--danger);
        background: white;
        border: 1px solid var(--danger);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .btn-remove:hover {
        background: var(--danger-light);
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
        margin-bottom: 20px;
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
        .accountants-container {
            padding: 20px 24px;
        }
    }
    
    @media (max-width: 768px) {
        .accountants-container {
            padding: 16px;
        }
        
        .accountant-item {
            flex-direction: column;
            text-align: center;
            padding: 16px;
        }
        
        .accountant-info {
            flex-direction: column;
            text-align: center;
        }
        
        .details-row {
            justify-content: center;
        }
        
        .event-badges {
            justify-content: center;
        }
        
        .card-header {
            text-align: center;
            padding: 12px 16px;
        }
        
        .header-section {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .notification-container {
            min-width: 280px;
            top: 70px;
        }
    }
    
    @media (max-width: 480px) {
        .accountants-container {
            padding: 12px;
        }
        
        .details-row {
            flex-direction: column;
            gap: 6px;
            align-items: center;
        }
        
        .btn-remove {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="accountants-container">
    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h4>Wahasibu</h4>
            <p>Wahasibu wanaosimamia michango ya matukio yako</p>
        </div>
        <a href="{{ route('mhasibu.create') }}" class="btn-primary-custom">
            <i class="fas fa-user-plus"></i> Sajili Mhasibu Mpya
        </a>
    </div>
    
    <!-- Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>
    
    <!-- Single Card -->
    <div class="single-card">
        <div class="card-header">
            <div>
                <h5><i class="fas fa-user-tie"></i> Orodha ya Wahasibu</h5>
                <p>Wahasibu waliopatiwa majukumu ya kuthibitisha michango</p>
            </div>
        </div>
        
        <div class="accountants-list">
            @forelse($accountants as $accountant)
                <div class="accountant-item">
                    <div class="accountant-info">
                        <div class="avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="accountant-details">
                            <h6>{{ $accountant->name }}</h6>
                            <div class="details-row">
                                <span><i class="fas fa-envelope"></i> {{ $accountant->email }}</span>
                                <span><i class="fas fa-phone"></i> {{ $accountant->phone }}</span>
                            </div>
                            <div class="event-badges">
                                <i class="fas fa-calendar-alt" style="font-size: 0.6rem; color: var(--primary);"></i>
                                <span style="font-size: 0.65rem; color: var(--text-muted);">Matukio:</span>
                                @forelse($accountant->events as $event)
                                    <span class="event-badge">
                                        <i class="fas fa-calendar"></i> {{ $event->event_name }}
                                    </span>
                                @empty
                                    <span class="event-badge">Hakuna tukio</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div>
                        <form method="POST" action="{{ route('mhasibu.destroy', $accountant->id) }}" 
                              onsubmit="return confirmDelete('{{ addslashes($accountant->name) }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-remove">
                                <i class="fas fa-trash-alt"></i> Ondoa
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-user-tie"></i>
                    <h6>Hakuna Wahasibu</h6>
                    <p>Sajili mhasibu wa kwanza kusimamia michango ya matukio yako</p>
                    <a href="{{ route('mhasibu.create') }}" class="btn-primary-custom">
                        <i class="fas fa-plus-circle"></i> Sajili Mhasibu
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

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
    
    function confirmDelete(name) {
        return confirm(`Je, una uhakika unataka kumtoa "${name}" kutoka kwenye orodha ya wahasibu?`);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif
    });
</script>
@endsection