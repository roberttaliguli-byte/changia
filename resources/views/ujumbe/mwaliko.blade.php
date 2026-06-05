@extends('layouts.app')

@section('title', 'Tuma Mwaliko - SMS')
@section('page_title', 'Tuma Mwaliko')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    :root {
        --primary: #FF6F00;
        --primary-light: #FFF3E0;
        --primary-dark: #E65100;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --info: #3B82F6;
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
    
    .main-content {
        overflow-y: auto !important;
        height: calc(100vh - var(--topbar-h, 60px));
        padding-bottom: 30px;
    }
    
    .message-container {
        width: 100%;
        padding: 24px 32px;
    }
    
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
    
    .single-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        width: 100%;
        margin-bottom: 20px;
    }
    
    .card-header-custom {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-color);
        background: white;
    }
    
    .card-header-custom h5 {
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 0.9rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .card-header-custom h5 i {
        color: var(--primary);
    }
    
    .card-header-custom p {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-bottom: 0;
    }
    
    .form-content {
        padding: 24px;
    }
    
    .form-group {
        margin-bottom: 20px;
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
    
    .form-control-custom, .form-select-custom {
        width: 100%;
        padding: 10px 12px;
        font-size: 0.8rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: white;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }
    
    .form-control-custom:focus, .form-select-custom:focus {
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
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .event-selector {
        background: var(--bg-light);
        border-radius: var(--radius-sm);
        padding: 8px;
        border: 1px solid var(--border-color);
        max-height: 200px;
        overflow-y: auto;
    }
    
    .event-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    
    .event-option:hover {
        background: var(--primary-light);
        border-color: var(--primary-light);
    }
    
    .event-option input {
        margin: 0;
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
    }
    
    .event-option label {
        margin: 0;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: none;
        cursor: pointer;
        flex: 1;
    }
    
    .event-option small {
        display: block;
        font-size: 0.6rem;
        color: var(--text-muted);
    }
    
    .contact-buttons {
        display: flex;
        gap: 10px;
        margin-top: 12px;
        flex-wrap: wrap;
    }
    
    .btn-contact {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid var(--border-color);
        background: var(--bg-light);
        color: var(--text-secondary);
    }
    
    .btn-contact:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
    }
    
    .btn-contact i {
        font-size: 0.9rem;
    }
    
    .template-buttons {
        display: flex;
        gap: 8px;
        margin-top: 8px;
        flex-wrap: wrap;
    }
    
    .btn-template {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        font-size: 0.7rem;
        font-weight: 500;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid var(--border-color);
        background: var(--info-light);
        color: var(--info);
    }
    
    .btn-template:hover {
        background: var(--info);
        color: white;
        border-color: var(--info);
    }
    
    .numbers-preview {
        background: var(--bg-light);
        border-radius: var(--radius-sm);
        padding: 12px;
        margin-top: 12px;
        border: 1px solid var(--border-color);
        display: none;
    }
    
    .numbers-preview.active {
        display: block;
    }
    
    .numbers-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        max-height: 150px;
        overflow-y: auto;
    }
    
    .number-chip {
        background: var(--primary-light);
        color: var(--primary-dark);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .number-chip .remove-number {
        cursor: pointer;
        font-size: 0.8rem;
        opacity: 0.7;
        font-weight: bold;
    }
    
    .number-chip .remove-number:hover {
        opacity: 1;
        color: var(--danger);
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        margin-top: 16px;
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
    
    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .btn-secondary {
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
        cursor: pointer;
    }
    
    .btn-secondary:hover {
        background: var(--bg-light);
    }
    
    .btn-back {
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
    }
    
    .btn-back:hover {
        background: var(--danger-light);
        border-color: var(--danger);
        color: var(--danger);
    }
    
    .export-section {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid var(--border-color);
        text-align: center;
    }
    
    .link-option {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }
    
    .link-option label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: normal;
        text-transform: none;
    }
    
    .link-option input[type="radio"] {
        width: 16px;
        height: 16px;
        margin: 0;
    }
    
    .custom-link-input {
        margin-top: 10px;
        display: none;
    }
    
    .custom-link-input.active {
        display: block;
    }
    
    .loading-spinner {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.6s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .message-container {
            padding: 16px;
        }
        
        .form-content {
            padding: 16px;
        }
        
        .card-header-custom {
            padding: 12px 16px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-submit, .btn-back, .btn-secondary {
            justify-content: center;
            width: 100%;
        }
        
        .contact-buttons {
            justify-content: center;
        }
        
        .link-option {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="message-container">
    <div class="header-section">
        <div class="header-title">
            <h4><i class="fas fa-sms"></i> Tuma Mwaliko kwa SMS</h4>
            <p>Tuma ujumbe wa mwaliko kwa wachangiaji kupitia SMS</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Rudi Dashboard
        </a>
    </div>
    
    <div id="notificationContainer"></div>
    
    <div class="single-card">
        <div class="card-header-custom">
            <h5><i class="fas fa-envelope-open-text"></i> Tuma Mwaliko Mpya (SMS)</h5>
            <p>Jaza taarifa na utume mwaliko kwa wachangiaji kupitia SMS</p>
        </div>
        
        <div class="form-content">
         <form method="POST" action="{{ route('ujumbe.tuma-mwaliko') }}" id="messageForm">   @csrf
                
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Chagua Tukio <span class="text-danger">*</span></label>
                    @if($events->count() > 0)
                        <div class="event-selector" id="eventSelector">
                            @foreach($events as $event)
                                <div class="event-option">
                                    <input type="radio" name="event_id" value="{{ $event->id }}" id="event_{{ $event->id }}" data-event-name="{{ $event->event_name }}" required>
                                    <label for="event_{{ $event->id }}">
                                        <strong>{{ $event->event_name }}</strong>
                                        <small>{{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning" style="background: var(--warning-light); border-left: 3px solid var(--warning); padding: 12px; border-radius: var(--radius-sm);">
                            <i class="fas fa-exclamation-triangle"></i> Hujasajili tukio lolote. 
                            <a href="{{ route('events.create') }}" style="color: var(--primary);">Bonyeza hapa kuunda tukio</a>
                        </div>
                    @endif
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-phone-alt"></i> Namba za Simu na Majina (Hiari)</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <textarea name="phone_numbers" id="phoneNumbers" class="form-control-custom" rows="4" placeholder="Weka namba za simu:&#10;0712345678&#10;0754123456" required></textarea>
                        <textarea name="names" id="namesInput" class="form-control-custom" rows="4" placeholder="Weka majina (kwa mpangilio sawa na namba):&#10;Yusuph Juma&#10;Amina Mohamed"></textarea>
                    </div>
                    <div class="help-text">
                        <i class="fas fa-info-circle"></i> Weka namba moja kwa mstari. Majina ni hiari - yakiongezwa, yataonekana kwenye ujumbe
                    </div>
                    
                    <div class="contact-buttons">
                        <button type="button" class="btn-contact" id="selectFromContactsBtn">
                            <i class="fas fa-address-book"></i> Chagua kutoka Anwani za Simu
                        </button>
                        <button type="button" class="btn-contact" id="loadPreviousContributorsBtn">
                            <i class="fas fa-history"></i> Wachangiaji Waliopo
                        </button>
                        <button type="button" class="btn-contact" id="clearAllNumbersBtn">
                            <i class="fas fa-trash"></i> Futa Zote
                        </button>
                    </div>
                    
                    <div id="numbersPreview" class="numbers-preview">
                        <h6><i class="fas fa-list"></i> Namba zilizochaguliwa (<span id="selectedCount">0</span>):</h6>
                        <div id="numbersList" class="numbers-list"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-link"></i> Chaguo la Kiungo (Link) - Hiari</label>
                    <div class="link-option">
                        <label>
                            <input type="radio" name="link_option" value="auto" checked> Tumia kiungo cha kiotomatiki
                        </label>
                        <label>
                            <input type="radio" name="link_option" value="custom"> Weka kiungo changu mwenyewe
                        </label>
                        <label>
                            <input type="radio" name="link_option" value="none"> Usijumuishe kiungo
                        </label>
                    </div>
                    <div id="customLinkContainer" class="custom-link-input">
                        <input type="text" name="custom_link" id="customLink" class="form-control-custom" placeholder="https://example.com/tukio">
                        <div class="help-text">
                            <i class="fas fa-info-circle"></i> Weka kiungo chako mwenyewe (kwa mfano: link ya Google Form au tovuti yako)
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-file-alt"></i> Chagua Template ya Ujumbe</label>
                    <div class="template-buttons">
                        <button type="button" class="btn-template" data-template="Habari [NAME],\n\nKaribu katika tukio la [EVENT_NAME] linalofanyika tarehe [EVENT_DATE].\n\n[LINK]\n\nAsante kwa ushirikiano wako.">
                            <i class="fas fa-file-alt"></i> Mwaliko wa Kawaida
                        </button>
                        <button type="button" class="btn-template" id="weddingTemplateBtn">
                            <i class="fas fa-heart"></i> Mwaliko wa Harusi
                        </button>
                        <button type="button" class="btn-template" data-template="Habari [NAME],\n\nTunakukaribisha kwenye sherehe yetu ya [EVENT_NAME] tarehe [EVENT_DATE].\n\nKwa maelezo zaidi: [LINK]\n\nKaribu sana!">
                            <i class="fas fa-glass-cheers"></i> Mwaliko wa Sherehe
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-edit"></i> Ujumbe wako</label>
                    <textarea name="message" id="messageText" class="form-control-custom" rows="5" placeholder="Andika ujumbe wako hapa...&#10;&#10;Unaweza kutumia:&#10;[NAME] - Jina la mpokeaji&#10;[EVENT_NAME] - Jina la tukio&#10;[EVENT_DATE] - Tarehe ya tukio&#10;[LINK] - Kiungo (kama umechagua)" required></textarea>
                    <div class="help-text">
                        <i class="fas fa-lightbulb"></i> <strong>Vidokezo:</strong> Tumia [NAME] kwa jina, [EVENT_NAME] kwa jina la tukio, [EVENT_DATE] kwa tarehe, [LINK] kwa kiungo (kama umechagua)
                    </div>
                </div>
                
                <div class="form-actions">
                    <div>
                        <a href="{{ route('dashboard') }}" class="btn-back">
                            <i class="fas fa-times"></i> Ghairi
                        </a>
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <button type="button" class="btn-secondary" id="previewBtn">
                            <i class="fas fa-eye"></i> Hakiki Ujumbe
                        </button>
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="fas fa-sms"></i> Tuma kwa SMS
                        </button>
                    </div>
                </div>
                
                <div class="export-section" id="exportSection" style="display: none;">
                    <button type="button" class="btn-secondary" id="exportExcelBtn">
                        <i class="fas fa-file-excel"></i> Pakua Ripoti ya SMS (Excel)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let selectedNumbers = [];
    
    function showNotification(message, type = 'success') {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Imefanikiwa' : (type === 'error' ? 'Hitilafu' : 'Taarifa'),
            text: message,
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }
    
    function formatPhoneNumber(number) {
        let cleaned = number.replace(/[^0-9+]/g, '');
        cleaned = cleaned.replace(/^\+/, '');
        
        if (cleaned.startsWith('0')) {
            cleaned = '255' + cleaned.substring(1);
        }
        
        if (cleaned.length === 9) {
            cleaned = '255' + cleaned;
        }
        
        return cleaned;
    }
    
    function updateNumbersPreview() {
        const textarea = document.getElementById('phoneNumbers');
        const previewDiv = document.getElementById('numbersPreview');
        const numbersListDiv = document.getElementById('numbersList');
        const selectedCountSpan = document.getElementById('selectedCount');
        
        if (!textarea || !previewDiv || !numbersListDiv) return;
        
        let numbers = textarea.value.split(/[\n\r,;\s]+/);
        numbers = numbers.filter(n => n.trim().length > 0);
        
        selectedNumbers = [];
        
        if (numbers.length > 0) {
            previewDiv.classList.add('active');
            numbersListDiv.innerHTML = '';
            numbers.forEach(number => {
                const formatted = formatPhoneNumber(number);
                if (formatted.match(/^255[0-9]{9}$/)) {
                    selectedNumbers.push(formatted);
                    const chip = document.createElement('span');
                    chip.className = 'number-chip';
                    chip.innerHTML = `${formatted} <span class="remove-number" data-number="${number}">&times;</span>`;
                    numbersListDiv.appendChild(chip);
                }
            });
            
            selectedCountSpan.textContent = selectedNumbers.length;
            
            document.querySelectorAll('.remove-number').forEach(el => {
                el.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const numberToRemove = this.getAttribute('data-number');
                    removeNumber(numberToRemove);
                });
            });
        } else {
            previewDiv.classList.remove('active');
            selectedCountSpan.textContent = '0';
        }
    }
    
    function removeNumber(numberToRemove) {
        const textarea = document.getElementById('phoneNumbers');
        if (textarea) {
            let regex = new RegExp(numberToRemove.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
            let newValue = textarea.value.replace(regex, '').replace(/[,\n\r\s]+/g, '\n').replace(/\n\n/g, '\n').trim();
            textarea.value = newValue;
            updateNumbersPreview();
            showNotification(`Namba ${numberToRemove} imeondolewa`, 'info');
        }
    }
    
    function clearAllNumbers() {
        Swal.fire({
            title: 'Futa namba zote?',
            text: 'Utakuwa unaondoa namba zote ulizoweka.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FF6F00',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ndio, Futa',
            cancelButtonText: 'Ghairi'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('phoneNumbers').value = '';
                document.getElementById('namesInput').value = '';
                updateNumbersPreview();
                showNotification('Namba zote zimefutwa', 'success');
            }
        });
    }
    
    async function selectFromPhoneContacts() {
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        if (isMobile && 'contacts' in navigator && 'select' in navigator.contacts) {
            try {
                Swal.fire({
                    title: 'Fungua anwani...',
                    text: 'Tafadhali ruhusu kufikia anwani zako',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                const contacts = await navigator.contacts.select(['tel', 'name'], { multiple: true });
                Swal.close();
                
                if (contacts && contacts.length > 0) {
                    const phoneNumbers = [];
                    const names = [];
                    
                    contacts.forEach(contact => {
                        if (contact.tel && contact.tel.length > 0) {
                            let phone = contact.tel[0].replace(/[^0-9+]/g, '');
                            phoneNumbers.push(phone);
                            names.push(contact.name ? contact.name[0] : '');
                        }
                    });
                    
                    if (phoneNumbers.length > 0) {
                        const textarea = document.getElementById('phoneNumbers');
                        const namesInput = document.getElementById('namesInput');
                        const existingNumbers = textarea.value;
                        const existingNames = namesInput.value;
                        
                        textarea.value = existingNumbers ? existingNumbers + '\n' + phoneNumbers.join('\n') : phoneNumbers.join('\n');
                        namesInput.value = existingNames ? existingNames + '\n' + names.join('\n') : names.join('\n');
                        
                        updateNumbersPreview();
                        showNotification(`Namba ${phoneNumbers.length} zimeongezwa kutoka anwani`, 'success');
                    }
                } else {
                    showNotification('Hakuna wasiliani waliochaguliwa', 'info');
                }
            } catch (error) {
                Swal.close();
                showNotification('Hitilafu kufungua anwani. Jaribu kuweka namba kwa mkono.', 'error');
            }
        } else {
            Swal.fire({
                title: 'Weka Namba kwa Mkono',
                html: '<textarea id="manualNumbers" class="swal2-textarea" placeholder="Weka namba za simu, moja kwa mstari&#10;0712345678&#10;0754123456" rows="5"></textarea>',
                showCancelButton: true,
                confirmButtonText: 'Ongeza',
                cancelButtonText: 'Ghairi',
                preConfirm: () => {
                    const input = document.getElementById('manualNumbers');
                    return input ? input.value : '';
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const textarea = document.getElementById('phoneNumbers');
                    const existing = textarea.value;
                    textarea.value = existing ? existing + '\n' + result.value : result.value;
                    updateNumbersPreview();
                    showNotification('Namba zimeongezwa', 'success');
                }
            });
        }
    }
    
    async function loadPreviousContributors() {
        const selectedEvent = document.querySelector('input[name="event_id"]:checked');
        if (!selectedEvent) {
            showNotification('Tafadhali chagua tukio kwanza', 'error');
            return;
        }
        
        const eventId = selectedEvent.value;
        
        Swal.fire({ title: 'Inapakia...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        
        try {
            const response = await fetch(`/api/event/${eventId}/contributors`);
            const contributors = await response.json();
            Swal.close();
            
            if (contributors && contributors.length > 0) {
                const phoneNumbers = contributors.map(c => c.phone).join('\n');
                const names = contributors.map(c => c.name || '').join('\n');
                
                const textarea = document.getElementById('phoneNumbers');
                const namesInput = document.getElementById('namesInput');
                
                textarea.value = phoneNumbers;
                namesInput.value = names;
                
                updateNumbersPreview();
                showNotification(`Namba ${contributors.length} za wachangiaji zimeongezwa`, 'success');
            } else {
                showNotification('Hakuna wachangiaji wa awali kwa tukio hili', 'info');
            }
        } catch (error) {
            Swal.close();
            showNotification('Hitilafu kupakia wachangiaji', 'error');
        }
    }
    
    function handleLinkOptionChange() {
        const linkOption = document.querySelector('input[name="link_option"]:checked');
        const customLinkContainer = document.getElementById('customLinkContainer');
        
        if (linkOption && linkOption.value === 'custom') {
            customLinkContainer.classList.add('active');
        } else {
            customLinkContainer.classList.remove('active');
        }
    }
    
    function setTemplate(templateText) {
        document.getElementById('messageText').value = templateText;
        showNotification('Template imepakiwa. Unaweza kuibadilisha.', 'success');
    }
    
    async function previewMessage() {
        const selectedEvent = document.querySelector('input[name="event_id"]:checked');
        if (!selectedEvent) {
            showNotification('Tafadhali chagua tukio kwanza', 'error');
            return;
        }
        
        const eventId = selectedEvent.value;
        const message = document.getElementById('messageText')?.value;
        
        if (!message) {
            showNotification('Tafadhali andika ujumbe kwanza', 'error');
            return;
        }
        
        try {
            const response = await fetch(`/api/event/${eventId}/details`);
            const event = await response.json();
            
            let preview = message
                .replace(/\[NAME\]/g, '[Jina la Mpokeaji]')
                .replace(/\[EVENT_NAME\]/g, event.event_name)
                .replace(/\[EVENT_DATE\]/g, event.event_date);
            
            const linkOption = document.querySelector('input[name="link_option"]:checked');
            if (linkOption && linkOption.value === 'auto') {
                preview = preview.replace(/\[LINK\]/g, '[Kiungo cha kiotomatiki]');
            } else if (linkOption && linkOption.value === 'custom') {
                const customLink = document.getElementById('customLink')?.value || '[Kiungo chako]';
                preview = preview.replace(/\[LINK\]/g, customLink);
            } else {
                preview = preview.replace(/\[LINK\]/g, '');
            }
            
            Swal.fire({
                title: 'Hakiki ya Ujumbe',
                html: `<div style="text-align: left; white-space: pre-wrap; max-height: 400px; overflow-y: auto; padding: 10px; background: #f9fafb; border-radius: 8px;">${preview}</div>`,
                icon: 'info',
                confirmButtonText: 'Sawa',
                confirmButtonColor: '#FF6F00'
            });
        } catch (error) {
            showNotification('Hitilafu kupata maelezo ya tukio', 'error');
        }
    }
    
    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('selectFromContactsBtn')?.addEventListener('click', selectFromPhoneContacts);
        document.getElementById('loadPreviousContributorsBtn')?.addEventListener('click', loadPreviousContributors);
        document.getElementById('clearAllNumbersBtn')?.addEventListener('click', clearAllNumbers);
        document.getElementById('previewBtn')?.addEventListener('click', previewMessage);
        
        document.querySelectorAll('input[name="link_option"]').forEach(radio => {
            radio.addEventListener('change', handleLinkOptionChange);
        });
        
        document.getElementById('weddingTemplateBtn')?.addEventListener('click', function() {
            setTemplate("Habari [NAME],\n\nMzee Yusuph na familia yake wanakuomba mchango wako wa hali na mali ili kufanikisha sherehe ya [EVENT_NAME] itakayofanyika tarehe [EVENT_DATE].\n\n[LINK]\n\nAsante kwa ushirikiano wako.");
        });
        
        document.querySelectorAll('.btn-template[data-template]').forEach(btn => {
            btn.addEventListener('click', function() {
                setTemplate(this.getAttribute('data-template'));
            });
        });
        
        document.getElementById('phoneNumbers')?.addEventListener('input', updateNumbersPreview);
        
        // Form submission
        const form = document.getElementById('messageForm');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const selectedEvent = document.querySelector('input[name="event_id"]:checked');
                if (!selectedEvent) {
                    showNotification('Tafadhali chagua tukio kwanza', 'error');
                    return;
                }
                
                const phoneNumbers = document.getElementById('phoneNumbers')?.value;
                if (!phoneNumbers || phoneNumbers.trim() === '') {
                    showNotification('Tafadhali weka angalau namba moja ya simu', 'error');
                    return;
                }
                
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Inatuma SMS...';
                
                const formData = new FormData(form);
                formData.append('event_id', selectedEvent.value);
                
                try {
                    const response = await fetch('{{ route("ujumbe.tuma-mwaliko") }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Imefanikiwa!',
                            html: `<b>${result.total_sent}</b> kati ya <b>${selectedNumbers.length}</b> ujumbe umetumwa kikamilifu.`,
                            confirmButtonColor: '#FF6F00'
                        });
                        
                        if (result.total_sent > 0) {
                            document.getElementById('exportSection').style.display = 'block';
                        }
                        
                        if (result.total_sent === selectedNumbers.length) {
                            document.getElementById('phoneNumbers').value = '';
                            document.getElementById('namesInput').value = '';
                            updateNumbersPreview();
                        }
                    } else {
                        showNotification(result.message || 'Hitilafu ilitokea. Jaribu tena.', 'error');
                    }
                } catch (error) {
                    showNotification('Hitilafu ya mtandao. Jaribu tena.', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
        
        document.getElementById('exportExcelBtn')?.addEventListener('click', function() {
            window.location.href = '{{ route("ujumbe.download.export") }}';
        });
        
        updateNumbersPreview();
    });
</script>
@endsection