@extends('layouts.app')

@section('title', 'Wahasibu')
@section('page_title', 'Wahasibu')

@push('styles')
<style>
    :root {
        --primary: #FF6F00;
        --primary-light: #FFF3E0;
        --primary-dark: #E65100;
        --success: #10B981;
        --success-light: #D1FAE5;
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
        font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    }
    
    .pg-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem 1rem;
        position: relative;
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
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
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
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .notification-content {
        flex: 1;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
        line-height: 1.4;
    }
    
    .notification-close {
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.2s;
        font-size: 0.875rem;
        flex-shrink: 0;
        color: var(--text-muted);
    }
    
    .notification-close:hover {
        opacity: 1;
    }
    
    /* Single Card Container */
    .single-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    
    /* Header */
    .page-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        background: white;
    }
    
    .page-header h6 {
        font-weight: 700;
        margin-bottom: 0.25rem;
        font-size: 1rem;
        color: var(--text-primary);
    }
    
    .page-header small {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }
    
    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.813rem;
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
    
    /* Accountant Cards */
    .accountant-card {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }
    
    .accountant-card:hover {
        background: var(--bg-light);
    }
    
    .accountant-card:last-child {
        border-bottom: none;
    }
    
    .accountant-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
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
        font-size: 1.5rem;
    }
    
    .accountant-details h6 {
        font-weight: 700;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
        color: var(--text-primary);
    }
    
    .accountant-details .details {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }
    
    .accountant-details .details i {
        width: 14px;
        margin-right: 0.25rem;
    }
    
    .event-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }
    
    .event-badge {
        padding: 0.25rem 0.625rem;
        font-size: 0.688rem;
        font-weight: 500;
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        color: var(--text-secondary);
    }
    
    .btn-remove {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.688rem;
        font-weight: 500;
        color: var(--danger);
        background: white;
        border: 1px solid var(--danger);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .btn-remove:hover {
        background: var(--danger-light);
        transform: translateY(-1px);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: var(--text-muted);
        opacity: 0.3;
        margin-bottom: 1rem;
        display: block;
    }
    
    .empty-state h6 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }
    
    .empty-state p {
        font-size: 0.813rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .accountant-info {
            flex-direction: column;
            text-align: center;
        }
        
        .page-header {
            flex-direction: column;
            text-align: center;
        }
        
        .event-badges {
            justify-content: center;
        }
        
        .notification-container {
            min-width: 280px;
        }
        
        .notification {
            padding: 0.75rem 1rem;
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
        <!-- Header -->
        <div class="page-header">
            <div>
                <h6><i class="fas fa-user-tie me-2"></i>Wahasibu</h6>
                <small>Wahasibu wanaosimamia michango ya matukio yako</small>
            </div>
            <a href="{{ route('mhasibu.create') }}" class="btn-primary-custom">
                <i class="fas fa-user-plus"></i> Sajili Mhasibu Mpya
            </a>
        </div>
        
        <!-- Accountants List -->
        @forelse($accountants as $accountant)
            <div class="accountant-card">
                <div class="accountant-info">
                    <div class="avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="accountant-details" style="flex: 1;">
                        <h6>{{ $accountant->name }}</h6>
                        <div class="details">
                            <span><i class="fas fa-envelope"></i> {{ $accountant->email }}</span>
                            <span><i class="fas fa-phone"></i> {{ $accountant->phone }}</span>
                        </div>
                        <div class="event-badges mt-2">
                            <small class="text-muted">Matukio:</small>
                            @foreach($accountant->events as $event)
                                <span class="event-badge">
                                    <i class="fas fa-calendar me-1"></i>{{ $event->event_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <form method="POST" action="{{ route('mhasibu.destroy', $accountant->id) }}" onsubmit="return confirm('Una uhakika unataka kumtoa mhasibu huyu?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-remove">
                                <i class="fas fa-trash"></i> Ondoa
                            </button>
                        </form>
                    </div>
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
@endsection

@push('scripts')
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
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    if (notification.parentNode) notification.remove();
                }, 300);
            }
        }, 3000);
        
        // Close button functionality
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
    
    document.addEventListener('DOMContentLoaded', function() {
        // Check for session messages
        @if(session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif
    });
</script>
@endpush