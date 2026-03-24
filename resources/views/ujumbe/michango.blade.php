@extends('layouts.app')

@section('title', 'Tuma Ujumbe wa Michango')
@section('page_title', 'Tuma Ujumbe wa Michango')

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
    
    /* Single Card */
    .single-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    .card-header-custom {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        background: white;
    }
    
    .card-header-custom h6 {
        font-weight: 700;
        margin-bottom: 0;
        font-size: 0.85rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-header-custom h6 i {
        color: var(--primary);
    }
    
    .form-content {
        padding: 1.25rem;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-group label {
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 0.375rem;
        display: block;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .form-control-custom {
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
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
    
    textarea.form-control-custom {
        resize: vertical;
        min-height: 100px;
    }
    
    .help-text {
        font-size: 0.6rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1.25rem;
        font-size: 0.7rem;
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
    
    .back-link {
        text-align: center;
        margin-top: 1rem;
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
    
    .event-selector {
        background: var(--bg-light);
        border-radius: var(--radius-sm);
        padding: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .event-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .event-option:hover {
        background: var(--primary-light);
    }
    
    .event-option input {
        margin: 0;
    }
    
    .event-option label {
        margin: 0;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: none;
        cursor: pointer;
    }
    
    .phone-input-example {
        background: var(--bg-light);
        border-radius: var(--radius-sm);
        padding: 0.5rem 0.75rem;
        font-size: 0.65rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .pg-wrap {
            padding: 1rem;
        }
        
        .form-content {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="pg-wrap">
    <div class="single-card">
        <div class="card-header-custom">
            <h6>
                <i class="fas fa-hand-holding-heart"></i>
                Tuma Ujumbe wa Kuomba Michango
            </h6>
        </div>
        
        <div class="form-content">
            <form method="POST" action="{{ route('ujumbe.tuma.michango') }}" id="smsForm">
                @csrf
                
                <div class="form-group">
                    <label>Chagua Tukio <span class="text-danger">*</span></label>
                    @if($events->count() > 0)
                        <div class="event-selector">
                            @foreach($events as $event)
                                <div class="event-option">
                                    <input type="radio" name="event_id" value="{{ $event->id }}" id="event_{{ $event->id }}" required>
                                    <label for="event_{{ $event->id }}">
                                        <strong>{{ $event->event_name }}</strong>
                                        <br><small class="text-muted">{{ $event->event_date->format('d M, Y') }}</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Hujasajili tukio lolote. <a href="{{ route('events.create') }}">Bonyeza hapa kuunda tukio</a>
                        </div>
                    @endif
                </div>
                
                <div class="form-group">
                    <label>Namba za Simu <span class="text-danger">*</span></label>
                    <textarea name="phone_numbers" class="form-control-custom" rows="5" placeholder="Weka namba za simu kwa njia zifuatazo:&#10;0712345678&#10;0756123456&#10;0614356803" required></textarea>
                    <div class="phone-input-example">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Mfano:</strong> Unaweza kuweka namba nyingi kwa kutumia mstari mpya, koma, au nafasi.
                        <br>Mfumo utazitambua kiotomatiki.
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Ujumbe (Hiari)</label>
                    <textarea name="message" class="form-control-custom" rows="3" placeholder="Acha tupu kutumia ujumbe wa kawaida..."></textarea>
                    <div class="help-text">
                        <i class="fas fa-edit"></i> Ujumbe wako utajumuisha kiungo cha kujisajili
                    </div>
                </div>
                
                <div class="form-footer mt-4">
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> Tuma Ujumbe
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="back-link">
        <a href="{{ route('dashboard') }}">
            <i class="fas fa-arrow-left"></i> Rudi Dashboard
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('smsForm')?.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" style="width: 12px; height: 12px;"></span> Inatuma...';
    });
</script>
@endpush