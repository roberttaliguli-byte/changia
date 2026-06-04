@extends('layouts.app')

@section('title', 'Sajili Mhasibu')
@section('page_title', 'Sajili Mhasibu')

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
        --danger: #EF4444;
        --danger-light: #FEE2E2;
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
    
    /* Fix scrolling - only one scrollbar */
    .main-content {
        overflow-y: auto !important;
        height: calc(100vh - var(--topbar-h, 60px));
        padding-bottom: 30px;
    }
    
    /* Full width container */
    .create-container {
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
    
    /* Single Card Container - FULL WIDTH */
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
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
    
    .btn-outline-custom {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: 0.75rem;
        font-weight: 600;
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
        width: 100%;
        padding: 24px;
    }
    
    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        margin-bottom: 16px;
    }
    
    /* Form Fields */
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-group label {
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 8px;
        display: block;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .form-group label .required {
        color: var(--danger);
        margin-left: 4px;
    }
    
    .form-control-custom {
        width: 100%;
        padding: 10px 12px;
        font-size: 0.8rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: white;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }
    
    .form-control-custom:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
    }
    
    .help-text {
        font-size: 0.6rem;
        color: var(--text-muted);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    /* Info Notice */
    .info-notice {
        display: flex;
        gap: 12px;
        padding: 16px 20px;
        background: var(--primary-light);
        border: 1px solid #FFE0B2;
        border-radius: var(--radius-sm);
        margin: 20px 0;
        width: 100%;
    }
    
    .info-notice i {
        color: var(--primary);
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .info-notice-content {
        flex: 1;
    }
    
    .info-notice-content strong {
        display: block;
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 0.75rem;
        color: var(--primary-dark);
    }
    
    .info-notice-content span {
        font-size: 0.7rem;
        color: var(--text-secondary);
        line-height: 1.4;
    }
    
    /* Form Footer */
    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 24px;
        border-top: 1px solid var(--border-color);
        margin-top: 16px;
    }
    
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        font-size: 0.75rem;
        font-weight: 600;
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
        gap: 6px;
        padding: 10px 24px;
        font-size: 0.75rem;
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
    
    /* Back Link */
    .back-link {
        margin-top: 20px;
    }
    
    .back-link a {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
    }
    
    .back-link a:hover {
        color: var(--primary);
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
    
    /* Loading Spinner */
    .spinner-border-sm {
        width: 12px;
        height: 12px;
        border-width: 2px;
        display: inline-block;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        animation: spin 0.6s linear infinite;
        margin-right: 6px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .create-container {
            padding: 20px 24px;
        }
    }
    
    @media (max-width: 768px) {
        .create-container {
            padding: 16px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .form-content {
            padding: 16px;
        }
        
        .card-header {
            flex-direction: column;
            text-align: center;
            padding: 12px 16px;
        }
        
        .form-footer {
            flex-direction: column-reverse;
        }
        
        .btn-cancel, .btn-submit {
            justify-content: center;
            width: 100%;
        }
        
        .notification-container {
            min-width: 280px;
            top: 70px;
        }
        
        .info-notice {
            padding: 12px 16px;
        }
    }
    
    @media (max-width: 480px) {
        .create-container {
            padding: 12px;
        }
        
        .form-group label {
            font-size: 0.65rem;
        }
        
        .form-control-custom {
            font-size: 0.7rem;
            padding: 8px 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="create-container">
    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h4>Sajili Mhasibu</h4>
            <p>Jaza taarifa za mhasibu anayesimamia michango</p>
        </div>
        <a href="{{ route('mhasibu.index') }}" class="btn-outline-custom">
            <i class="fas fa-list"></i> Orodha ya Wahasibu
        </a>
    </div>
    
    <!-- Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>
    
    <!-- Single Form Container -->
    <div class="single-card">
        <div class="card-header">
            <div>
                <h5><i class="fas fa-user-tie"></i> Maelezo ya Mhasibu</h5>
                <p>Taarifa zote muhimu za kuingia kwenye mfumo</p>
            </div>
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
                                <i class="fas fa-info-circle"></i> Hii itatumika kuingia kwenye akaunti
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
                                <i class="fas fa-info-circle"></i> Mhasibu atasimamia michango ya tukio hili
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
                    <i class="fas fa-lightbulb"></i>
                    <div class="info-notice-content">
                        <strong>ℹ️ Kumbuka:</strong>
                        <span>Mhasibu atapokea taarifa za michango na ataweza kuthibitisha malipo baada ya kuingia kwenye akaunti yake.</span>
                    </div>
                </div>
                
                <!-- Form Footer -->
                <div class="form-footer">
                    <a href="{{ route('mhasibu.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Ghairi
                    </a>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-save"></i> Sajili Mhasibu
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Back Link -->
    <div class="back-link">
        <a href="{{ route('dashboard') }}">
            <i class="fas fa-arrow-left"></i> Rudi kwenye Dashboard
        </a>
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
    
    document.addEventListener('DOMContentLoaded', function() {
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
        
        if (form) {
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border-sm"></span> Inahifadhi...';
            });
        }
    });
</script>
@endsection