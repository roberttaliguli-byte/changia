@extends('layouts.app')

@section('title', 'Sajili Mhasibu')
@section('page_title', 'Sajili Mhasibu')

@push('styles')
<style>
    :root {
        --primary: #FF6F00;
        --primary-light: #FFF3E0;
        --primary-dark: #E65100;
        --success: #10B981;
        --success-light: #D1FAE5;
        --warning: #F59E0B;
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
        max-width: 800px;
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
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    /* Event Header inside Card */
    .event-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        background: white;
    }
    
    .event-info h6 {
        font-weight: 700;
        margin-bottom: 0.25rem;
        font-size: 0.938rem;
        color: var(--text-primary);
    }
    
    .event-info small {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }
    
    .btn-outline-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.813rem;
        font-weight: 500;
        color: var(--text-secondary);
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-outline-custom:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
    }
    
    /* Form Content */
    .form-content {
        padding: 1.5rem;
    }
    
    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    
    /* Form Fields */
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        font-size: 0.813rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        color: var(--text-primary);
    }
    
    .form-group label .required {
        color: var(--danger);
        margin-left: 0.25rem;
    }
    
    .form-control-custom {
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: white;
        transition: all 0.2s;
    }
    
    .form-control-custom:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
    }
    
    .help-text {
        font-size: 0.688rem;
        color: var(--text-secondary);
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .help-text i {
        font-size: 0.688rem;
    }
    
    /* Info Notice */
    .info-notice {
        display: flex;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        background: var(--primary-light);
        border: 1px solid #FFE0B2;
        border-radius: var(--radius-sm);
        margin-bottom: 1.5rem;
    }
    
    .info-notice i {
        color: var(--primary);
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }
    
    .info-notice-content {
        flex: 1;
    }
    
    .info-notice-content strong {
        display: block;
        font-weight: 700;
        margin-bottom: 0.25rem;
        font-size: 0.813rem;
        color: var(--primary-dark);
    }
    
    .info-notice-content span {
        font-size: 0.75rem;
        color: var(--text-secondary);
        line-height: 1.4;
    }
    
    /* Form Footer */
    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border-color);
    }
    
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        font-size: 0.813rem;
        font-weight: 500;
        color: var(--text-secondary);
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-cancel:hover {
        background: var(--danger-light);
        border-color: var(--danger);
        color: var(--danger);
    }
    
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.5rem;
        font-size: 0.813rem;
        font-weight: 600;
        color: white;
        background: var(--primary);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-submit:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }
    
    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    /* Back Link */
    .back-link {
        text-align: center;
        margin-top: 1rem;
    }
    
    .back-link a {
        font-size: 0.813rem;
        color: var(--text-secondary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s;
    }
    
    .back-link a:hover {
        color: var(--primary);
    }
    
    /* Spinner */
    .spinner {
        display: none;
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Responsive */
    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .form-content {
            padding: 1.25rem;
        }
        
        .event-header {
            flex-direction: column;
            text-align: center;
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
        <div class="event-header">
            <div class="event-info">
                <h6><i class="fas fa-user-plus me-2"></i>Sajili Mhasibu Mpya</h6>
                <small>Jaza taarifa za mhasibu anayesimamia michango</small>
            </div>
            <a href="{{ route('mhasibu.index') }}" class="btn-outline-custom">
                <i class="fas fa-list"></i> Orodha ya Wahasibu
            </a>
        </div>
        
        <!-- Form Content -->
        <div class="form-content">
            <form method="POST" action="{{ route('mhasibu.store') }}" id="accountantForm">
                @csrf
                
                <div class="form-grid">
                    <!-- Left Column -->
                    <div>
                        <div class="form-group">
                            <label>Jina Kamili <span class="required">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control-custom" 
                                   value="{{ old('name') }}"
                                   placeholder="Mf: Juma Omary"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label>Barua Pepe <span class="required">*</span></label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control-custom" 
                                   value="{{ old('email') }}"
                                   placeholder="mhasibu@example.com"
                                   required>
                            <div class="help-text">
                                <i class="fas fa-envelope"></i> Hii itatumika kuingia kwenye akaunti
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Namba ya Simu <span class="required">*</span></label>
                            <input type="tel" 
                                   name="phone" 
                                   class="form-control-custom" 
                                   value="{{ old('phone') }}"
                                   placeholder="0712345678"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <div class="form-group">
                            <label>Tukio <span class="required">*</span></label>
                            <select name="event_id" class="form-control-custom" required>
                                <option value="">-- Chagua Tukio --</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->event_name }} - {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="help-text">
                                <i class="fas fa-calendar"></i> Mhasibu atasimamia michango ya tukio hili
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Password <span class="required">*</span></label>
                            <input type="password" 
                                   name="password" 
                                   class="form-control-custom" 
                                   placeholder="Angalau herufi 6"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label>Rudia Password <span class="required">*</span></label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   class="form-control-custom" 
                                   placeholder="Rudia password"
                                   required>
                        </div>
                    </div>
                </div>
                
                <!-- Info Notice -->
                <div class="info-notice">
                    <i class="fas fa-info-circle"></i>
                    <div class="info-notice-content">
                        <strong>Kumbuka:</strong>
                        <span>Mhasibu atapokea taarifa za michango na ataweza kuthibitisha malipo baada ya kuingia kwenye akaunti yake.</span>
                    </div>
                </div>
                
                <!-- Form Footer -->
                <div class="form-footer">
                    <a href="{{ route('mhasibu.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Ghairi
                    </a>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span class="spinner" id="spinner"></span>
                        <i class="fas fa-save"></i> Sajili Mhasibu
                    </button>
                </div>
            </form>
        </div>
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
        
        @if($errors->any())
            @foreach($errors->all() as $error)
                showNotification('{{ $error }}', 'error');
            @endforeach
        @endif
        
        const form = document.getElementById('accountantForm');
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('spinner');
        
        if (form) {
            form.addEventListener('submit', function() {
                spinner.style.display = 'inline-block';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.7';
            });
        }
    });
</script>
@endpush