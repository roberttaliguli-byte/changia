@extends('layouts.app')

@section('title', 'Unda Tukio Jipya')
@section('page_title', 'Unda Tukio Jipya')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #FF6F00;
        --primary-light: #FFF3E0;
        --primary-dark: #E65100;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
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
    
    /* FIXED SCROLLING - Full height with proper bottom scroll on mobile */
    .main-content {
        overflow-y: auto !important;
        height: calc(100vh - var(--topbar-h, 60px));
        padding-bottom: 30px;
    }
    
    /* Mobile scroll fix - ensures full bottom reach */
    @media (max-width: 768px) {
        .main-content {
            height: auto !important;
            min-height: 100vh;
            overflow-y: visible !important;
            padding-bottom: 50px;
        }
        html, body {
            height: auto;
            overflow-x: hidden;
        }
        body {
            overflow-y: auto !important;
        }
    }
    
    /* Full width container - NO CENTERING like dashboard */
    .create-container {
        width: 100%;
        padding: 24px 32px;
    }
    
    /* Header Section - matches dashboard */
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
    
    /* Form Card - Full width with max-width for readability */
    .form-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        max-width: 100%;
        margin: 0;
    }
    
    .form-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        background: white;
    }
    
    .form-header h5 {
        font-weight: 700;
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-primary);
    }
    
    .form-header h5 i {
        color: var(--primary);
        font-size: 1.1rem;
    }
    
    .form-body {
        padding: 24px;
    }
    
    /* Form Grid - Two columns */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group-full {
        grid-column: span 2;
    }
    
    .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-secondary);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .form-label i {
        color: var(--primary);
        font-size: 0.7rem;
    }
    
    .required-star {
        color: var(--danger);
        font-size: 0.7rem;
    }
    
    .form-control, .form-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 0.813rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
        background: white;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
    }
    
    .form-text {
        font-size: 0.65rem;
        color: var(--text-muted);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    /* Quick Amount Buttons */
    .quick-amounts {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }
    
    .quick-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .quick-badge:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary-dark);
        transform: translateY(-1px);
    }
    
    /* Error Alert */
    .error-alert {
        background: #FEF2F2;
        border-left: 3px solid var(--danger);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .error-alert-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .error-alert-content i {
        color: var(--danger);
        font-size: 0.875rem;
    }
    
    .error-alert-content span {
        font-size: 0.75rem;
        color: var(--danger);
        font-weight: 500;
    }
    
    .error-alert .btn-close-custom {
        cursor: pointer;
        color: var(--text-muted);
        font-size: 0.7rem;
        transition: color 0.2s;
    }
    
    .error-alert .btn-close-custom:hover {
        color: var(--danger);
    }
    
    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }
    
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
        background: var(--bg-light);
        border-color: var(--text-muted);
        color: var(--text-primary);
    }
    
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
        box-shadow: var(--shadow-sm);
    }
    
    /* Info Box */
    .info-box {
        background: var(--bg-light);
        border-radius: var(--radius-md);
        padding: 16px;
        margin-top: 24px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
        border: 1px solid var(--border-color);
    }
    
    .info-box i {
        color: var(--primary);
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .info-box-content strong {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-primary);
        display: block;
        margin-bottom: 4px;
    }
    
    .info-box-content p {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-bottom: 0;
    }
    
    /* Loading spinner */
    .spinner-border-sm {
        width: 12px;
        height: 12px;
        border-width: 2px;
        display: inline-block;
        border-radius: 50%;
        animation: spinner-border 0.75s linear infinite;
    }
    
    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
    
    /* Responsive - ensures full scroll to bottom */
    @media (max-width: 1024px) {
        .create-container {
            padding: 20px 24px;
        }
    }
    
    @media (max-width: 768px) {
        .create-container {
            padding: 16px 16px 40px 16px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .form-group-full {
            grid-column: span 1;
        }
        
        .form-body {
            padding: 20px;
        }
        
        .form-header {
            padding: 16px 20px;
        }
        
        .form-actions {
            flex-direction: column-reverse;
        }
        
        .btn-cancel, .btn-submit {
            justify-content: center;
            width: 100%;
        }
        
        .quick-amounts {
            gap: 6px;
        }
        
        .quick-badge {
            padding: 4px 10px;
            font-size: 0.65rem;
        }
        
        .info-box {
            padding: 12px;
            margin-bottom: 20px;
        }
    }
    
    @media (max-width: 480px) {
        .create-container {
            padding: 12px 12px 40px 12px;
        }
        
        .form-body {
            padding: 16px;
        }
        
        .form-header {
            padding: 12px 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="create-container">
    <!-- Header Section - Matches Dashboard -->
    <div class="header-section">
        <div class="header-title">
            <h4>Unda Tukio Jipya</h4>
            <p>Jaza taarifa zote ili kuunda tukio lako</p>
        </div>
        <a href="{{ route('events.index') }}" class="btn-cancel" style="padding: 8px 16px;">
            <i class="fas fa-arrow-left"></i> Rudi kwenye Matukio
        </a>
    </div>
    
    <!-- Form Card -->
    <div class="form-card">
        <div class="form-header">
            <h5>
                <i class="fas fa-plus-circle"></i>
                Fomu ya Kuunda Tukio
            </h5>
        </div>
        <div class="form-body">
            <!-- Error Alert -->
            @if ($errors->any())
                <div class="error-alert">
                    <div class="error-alert-content">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                    <div class="btn-close-custom" onclick="this.closest('.error-alert').remove()">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('events.store') }}" id="createEventForm">
                @csrf
                
                <div class="form-grid">
                    <!-- Left Column -->
                    <div>
                        <!-- Event Type -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                Aina ya Tukio
                                <span class="required-star">*</span>
                            </label>
                            <select name="event_type" class="form-select" required>
                                <option value="" selected disabled>-- Chagua Aina ya Tukio --</option>
                                <option value="harusi" {{ old('event_type') == 'harusi' ? 'selected' : '' }}>🎊 Harusi</option>
                                <option value="sendoff" {{ old('event_type') == 'sendoff' ? 'selected' : '' }}>✈️ Send-off</option>
                                <option value="birthday" {{ old('event_type') == 'birthday' ? 'selected' : '' }}>🎂 Siku ya Kuzaliwa</option>
                                <option value="graduation" {{ old('event_type') == 'graduation' ? 'selected' : '' }}>🎓 Graduation</option>
                                <option value="kitchen" {{ old('event_type') == 'kitchen' ? 'selected' : '' }}>🍽️ Kitchen Party</option>
                                <option value="baby" {{ old('event_type') == 'baby' ? 'selected' : '' }}>👶 Baby Shower</option>
                                <option value="fundraising" {{ old('event_type') == 'fundraising' ? 'selected' : '' }}>🤝 Harambee</option>
                                <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>📌 Nyingine</option>
                            </select>
                        </div>

                        <!-- Event Name -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-font"></i>
                                Jina la Tukio
                                <span class="required-star">*</span>
                            </label>
                            <input type="text" 
                                   name="event_name" 
                                   class="form-control" 
                                   value="{{ old('event_name') }}"
                                   required 
                                   placeholder="Mf: Harusi ya Juma & Asha">
                        </div>

                        <!-- Event Date -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt"></i>
                                Tarehe ya Tukio
                                <span class="required-star">*</span>
                            </label>
                            <input type="date" 
                                   name="event_date" 
                                   class="form-control" 
                                   value="{{ old('event_date') }}"
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <!-- Target Amount -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-chart-line"></i>
                                Kiasi Lengwa (TSh)
                            </label>
                            <input type="number" 
                                   name="target_amount" 
                                   id="target_amount"
                                   class="form-control" 
                                   value="{{ old('target_amount') }}"
                                   min="0" 
                                   step="1000" 
                                   placeholder="0">
                            
                            <!-- Quick Amount Buttons -->
                            <div class="quick-amounts">
                                <span class="quick-badge" onclick="setTargetAmount(500000)">500k TSh</span>
                                <span class="quick-badge" onclick="setTargetAmount(1000000)">1M TSh</span>
                                <span class="quick-badge" onclick="setTargetAmount(2000000)">2M TSh</span>
                                <span class="quick-badge" onclick="setTargetAmount(5000000)">5M TSh</span>
                                <span class="quick-badge" onclick="setTargetAmount(10000000)">10M TSh</span>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i>
                                Hiari - Acha ikiwa huna lengo maalum
                            </div>
                        </div>

                        <!-- Status (Hidden - always active on create) -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-flag-checkered"></i>
                                Hali ya Tukio
                            </label>
                            <div style="padding: 8px 12px; background: var(--bg-light); border-radius: var(--radius-sm);">
                                <span style="display: inline-flex; align-items: center; gap: 6px; background: #D1FAE5; color: #10B981; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 600;">
                                    <i class="fas fa-play"></i> Inaendelea (Chaguo-msingi)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description - Full Width -->
                <div class="form-group form-group-full">
                    <label class="form-label">
                        <i class="fas fa-align-left"></i>
                        Maelezo ya Tukio
                    </label>
                    <textarea name="description" 
                              class="form-control" 
                              rows="4"
                              placeholder="Andika maelezo kuhusu tukio lako...">{{ old('description') }}</textarea>
                    <div class="form-text">
                        <i class="fas fa-info-circle"></i>
                        Hiari - Maelezo yataonekana kwa wachangiaji wako
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('events.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i>
                        Ghairi
                    </a>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-save"></i>
                        Hifadhi Tukio
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Box - Helpful Tips -->
    <div class="info-box">
        <i class="fas fa-lightbulb"></i>
        <div class="info-box-content">
            <strong>Vidokezi vya Kusaidia:</strong>
            <p>Baada ya kuunda tukio, utaweza kuongeza wachangiaji, kufuatilia michango, na kutuma kadi za mwaliko. Hakikisha umejaza taarifa zote kwa usahihi.</p>
        </div>
    </div>
</div>

<script>
    // Function to set target amount
    function setTargetAmount(amount) {
        const targetInput = document.getElementById('target_amount');
        if (targetInput) {
            targetInput.value = amount;
            targetInput.style.borderColor = 'var(--primary)';
            setTimeout(() => {
                targetInput.style.borderColor = '';
            }, 500);
        }
    }
    
    // Auto-fill event name suggestion based on selected type
    const eventTypeSelect = document.querySelector('select[name="event_type"]');
    if (eventTypeSelect) {
        eventTypeSelect.addEventListener('change', function() {
            const selected = this.value;
            const eventNameInput = document.querySelector('input[name="event_name"]');
            
            if (selected && !eventNameInput.value.trim()) {
                const suggestions = {
                    'harusi': 'Harusi ya ',
                    'sendoff': 'Send-off ya ',
                    'birthday': 'Siku ya Kuzaliwa ya ',
                    'graduation': 'Graduation ya ',
                    'kitchen': 'Kitchen Party ya ',
                    'baby': 'Baby Shower ya ',
                    'fundraising': 'Harambee ya ',
                    'other': 'Sherehe ya '
                };
                
                if (suggestions[selected]) {
                    eventNameInput.value = suggestions[selected];
                    eventNameInput.focus();
                    // Move cursor to end of text
                    eventNameInput.setSelectionRange(eventNameInput.value.length, eventNameInput.value.length);
                }
            }
        });
    }
    
    // Form validation
    const form = document.getElementById('createEventForm');
    const submitBtn = document.getElementById('submitBtn');
    
    function showError(message) {
        // Check if error alert already exists
        let errorAlert = document.querySelector('.error-alert');
        const container = document.querySelector('.form-body');
        
        if (!errorAlert && container) {
            const newAlert = document.createElement('div');
            newAlert.className = 'error-alert';
            newAlert.innerHTML = `
                <div class="error-alert-content">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>${message}</span>
                </div>
                <div class="btn-close-custom" onclick="this.closest('.error-alert').remove()">
                    <i class="fas fa-times"></i>
                </div>
            `;
            container.insertBefore(newAlert, container.firstChild);
            errorAlert = newAlert;
        } else if (errorAlert) {
            const errorSpan = errorAlert.querySelector('.error-alert-content span');
            if (errorSpan) errorSpan.textContent = message;
        }
        
        // Scroll to error
        if (errorAlert) {
            errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.error-alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    }
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const eventType = document.querySelector('select[name="event_type"]')?.value;
            const eventName = document.querySelector('input[name="event_name"]')?.value.trim();
            const eventDate = document.querySelector('input[name="event_date"]')?.value;
            
            if (!eventType) {
                e.preventDefault();
                showError('Tafadhali chagua aina ya tukio');
            } else if (!eventName) {
                e.preventDefault();
                showError('Tafadhali weka jina la tukio');
            } else if (!eventDate) {
                e.preventDefault();
                showError('Tafadhali weka tarehe ya tukio');
            } else {
                // Show loading state
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border-sm" style="width:12px;height:12px;border:2px solid white;border-right-color:transparent;border-radius:50%;display:inline-block;animation:spinner-border 0.75s linear infinite;margin-right:8px;"></span> Inahifadhi...';
                }
            }
        });
    }
    
    // Set min date to today
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.querySelector('input[name="event_date"]');
    if (dateInput) {
        dateInput.setAttribute('min', today);
    }
</script>
@endsection