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
    }
    
    .pg-wrap {
        max-width: 1000px;
        margin: 0 auto;
        padding: 1.5rem 1rem;
    }
    
    /* Form Card */
    .form-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    
    .form-header {
        padding: 1rem 1.5rem;
        background: white;
        border-bottom: 1px solid var(--border-color);
    }
    
    .form-header h5 {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-primary);
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-header h5 i {
        color: var(--primary);
        font-size: 1.1rem;
    }
    
    .form-body {
        padding: 1.5rem;
    }
    
    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
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
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 0.5rem 0.75rem;
        font-size: 0.813rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
        background: white;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
        outline: none;
    }
    
    .input-group-text {
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
    }
    
    .form-text {
        font-size: 0.65rem;
        color: var(--text-muted);
        margin-top: 0.375rem;
    }
    
    /* Quick Amount Badges */
    .quick-amounts {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .quick-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.75rem;
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
    
    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }
    
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
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
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
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
    
    /* Info Alert */
    .info-alert {
        background: var(--bg-light);
        border-left: 3px solid var(--primary);
        border-radius: var(--radius-sm);
        padding: 0.875rem 1rem;
        margin-top: 1.25rem;
        display: flex;
        gap: 0.75rem;
    }
    
    .info-alert i {
        color: var(--primary);
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }
    
    .info-alert-content strong {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-primary);
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .info-alert-content p {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-bottom: 0;
    }
    
    /* Error Alert */
    .error-alert {
        background: var(--danger-light);
        border-left: 3px solid var(--danger);
        border-radius: var(--radius-sm);
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .error-alert-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
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
    
    /* Row Layout */
    .row-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .pg-wrap {
            padding: 1rem;
        }
        
        .form-body {
            padding: 1rem;
        }
        
        .row-form {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-actions {
            flex-direction: column-reverse;
        }
        
        .btn-cancel, .btn-submit {
            justify-content: center;
            width: 100%;
        }
        
        .quick-amounts {
            gap: 0.375rem;
        }
        
        .quick-badge {
            padding: 0.25rem 0.625rem;
            font-size: 0.65rem;
        }
    }
</style>
@endpush

@section('content')
<div class="pg-wrap">
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

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-header">
            <h5>
                <i class="fas fa-plus-circle"></i>
                Unda Tukio Jipya
            </h5>
        </div>
        
        <div class="form-body">
            <form method="POST" action="{{ route('events.store') }}" id="eventForm">
                @csrf
                
                <div class="row-form">
                    <!-- Left Column -->
                    <div>
                        <!-- Event Type -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                Aina ya Tukio
                                <span class="required-star">*</span>
                            </label>
                            <select class="form-select" name="event_type" id="eventType" required>
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
                                   id="eventName"
                                   class="form-control" 
                                   placeholder="Mf: Harusi ya Juma & Asha"
                                   value="{{ old('event_name') }}"
                                   required>
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
                                   id="eventDate"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('event_date') }}"
                                   required>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <!-- Target Amount -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-chart-line"></i>
                                Kiasi Lengwa
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">TSh</span>
                                <input type="number" 
                                       name="target_amount" 
                                       class="form-control" 
                                       id="targetAmount"
                                       placeholder="0"
                                       min="0"
                                       step="1000"
                                       value="{{ old('target_amount') }}">
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Hiari - Acha ikiwa huna lengo maalum
                            </div>
                        </div>

                        <!-- Quick Amount Buttons -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-bolt"></i>
                                Mapendekezo ya Lengo
                            </label>
                            <div class="quick-amounts">
                                <span class="quick-badge" onclick="setTargetAmount(500000)">500k TSh</span>
                                <span class="quick-badge" onclick="setTargetAmount(1000000)">1M TSh</span>
                                <span class="quick-badge" onclick="setTargetAmount(2000000)">2M TSh</span>
                                <span class="quick-badge" onclick="setTargetAmount(5000000)">5M TSh</span>
                                <span class="quick-badge" onclick="setTargetAmount(10000000)">10M TSh</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description (Full Width) -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-align-left"></i>
                        Maelezo ya Tukio
                    </label>
                    <textarea name="description" 
                              class="form-control" 
                              rows="3"
                              placeholder="Andika maelezo kuhusu tukio lako...">{{ old('description') }}</textarea>
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Hiari - Maelezo yataonekana kwa wachangiaji
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('dashboard') }}" class="btn-cancel">
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

    <!-- Quick Info -->
    <div class="info-alert">
        <i class="fas fa-lightbulb"></i>
        <div class="info-alert-content">
            <strong>Vidokezo:</strong>
            <p>Baada ya kuunda tukio, utapata kiungo maalum cha kuwakaribisha wachangiaji. Unaweza kufuatilia michango kwa wakati halisi.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Function to set target amount
    function setTargetAmount(amount) {
        document.getElementById('targetAmount').value = amount;
        // Add visual feedback
        const input = document.getElementById('targetAmount');
        input.style.borderColor = 'var(--primary)';
        setTimeout(() => {
            input.style.borderColor = '';
        }, 500);
    }
    
    // Auto-fill event name based on selection
    document.getElementById('eventType')?.addEventListener('change', function() {
        const selected = this.value;
        const eventNameInput = document.getElementById('eventName');
        
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
            }
        }
    });
    
    // Set min date to today
    const today = new Date().toISOString().split('T')[0];
    const eventDateInput = document.getElementById('eventDate');
    if (eventDateInput) {
        eventDateInput.setAttribute('min', today);
    }
    
    // Form validation
    const form = document.getElementById('eventForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form?.addEventListener('submit', function(e) {
        const eventType = document.getElementById('eventType')?.value;
        const eventName = document.getElementById('eventName')?.value.trim();
        const eventDate = document.getElementById('eventDate')?.value;
        
        if (!eventType) {
            e.preventDefault();
            showValidationError('Tafadhali chagua aina ya tukio');
        } else if (!eventName) {
            e.preventDefault();
            showValidationError('Tafadhali weka jina la tukio');
        } else if (!eventDate) {
            e.preventDefault();
            showValidationError('Tafadhali weka tarehe ya tukio');
        } else {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" style="width: 12px; height: 12px;"></span> Inahifadhi...';
        }
    });
    
    function showValidationError(message) {
        // Check if error alert already exists
        let errorAlert = document.querySelector('.error-alert');
        if (!errorAlert) {
            const container = document.querySelector('.pg-wrap');
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
            container?.insertBefore(newAlert, container.firstChild);
        } else {
            const errorSpan = errorAlert.querySelector('.error-alert-content span');
            if (errorSpan) errorSpan.textContent = message;
        }
        
        // Scroll to error
        errorAlert?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Auto-dismiss error alert after 5 seconds
    setTimeout(() => {
        const errorAlert = document.querySelector('.error-alert');
        if (errorAlert) {
            errorAlert.style.opacity = '0';
            setTimeout(() => errorAlert.remove(), 300);
        }
    }, 5000);
</script>
@endpush