@extends('layouts.app')

@section('title', 'Tuma Ujumbe wa Michango - SMS')
@section('page_title', 'Tuma Ujumbe wa Michango')

@push('styles')
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
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 1px solid var(--border-color);
        width: 100%;
        margin-bottom: 20px;
    }
    
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
    
    .form-control-custom,
    .form-select-custom {
        width: 100%;
        padding: 10px 12px;
        font-size: 0.8rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: white;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }
    
    .form-control-custom:focus,
    .form-select-custom:focus {
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
    
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        margin-top: 16px;
        gap: 12px;
        flex-wrap: wrap;
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
    
    .notification-info {
        border-left-color: var(--primary);
    }
    
    .notification-info i:first-child {
        color: var(--primary);
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
    
    .numbers-preview {
        background: var(--bg-light);
        border-radius: var(--radius-sm);
        padding: 12px;
        margin-top: 10px;
        border: 1px solid var(--border-color);
        display: none;
    }
    
    .numbers-preview.active {
        display: block;
    }
    
    .numbers-preview h6 {
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-primary);
    }
    
    .numbers-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
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
    
    @media (max-width: 768px) {
        .message-container {
            padding: 16px;
        }
        
        .form-content {
            padding: 16px;
        }
        
        .card-header {
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
        
        .notification-container {
            min-width: 280px;
            top: 70px;
        }
    }
</style>
@endpush

@section('content')
<div class="message-container">
    <div class="header-section">
        <div class="header-title">
            <h4><i class="fas fa-sms"></i> Tuma Ujumbe wa Michango</h4>
            <p>Tuma ujumbe wa SMS kwa wachangiaji</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Rudi Dashboard
        </a>
    </div>
    
    <div id="notificationContainer" class="notification-container"></div>
    
    <div class="single-card">
        <div class="card-header">
            <h5><i class="fas fa-sms" style="color: #FF6F00;"></i> Tuma Ujumbe wa Kuomba Michango (SMS)</h5>
            <p>Ujumbe utatumwa kwa namba za simu ulizochagua kupitia SMS</p>
        </div>
        
        <div class="form-content">
         <form method="POST" action="{{ route('ujumbe.tuma-michango') }}" id="messageForm">
                @csrf
                
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Chagua Tukio</label>
                    <select name="event_id" id="eventSelect" class="form-select-custom" required>
                        <option value="">-- Chagua Tukio --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->event_name }} - {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>
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
                    
                    <!-- Contact picker buttons -->
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
                    
                    <!-- Preview selected numbers -->
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
                        <input type="text" name="custom_link" id="customLink" class="form-control-custom" placeholder="https://example.com/ahadi">
                        <div class="help-text">
                            <i class="fas fa-info-circle"></i> Weka kiungo chako mwenyewe (kwa mfano: link ya Google Form au tovuti yako)
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-file-alt"></i> Chagua Template ya Ujumbe</label>
                    <div class="template-buttons">
                        @foreach($templates as $key => $template)
                            <button type="button" class="btn-template" data-template="{{ $template['template'] }}">
                                <i class="fas fa-file-alt"></i> {{ $template['name'] }}
                            </button>
                        @endforeach
                        <button type="button" class="btn-template" id="weddingCustomTemplateBtn">
                            <i class="fas fa-heart"></i> Mwaliko wa Harusi
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
    // Global variables
    let selectedNumbers = [];
    
    // Show notification function
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
    
    // Format phone number to international format
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
    
    // Update numbers preview
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
    
    // Remove number from list
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
    
    // Clear all numbers
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
    
    // Select from phone contacts (Mobile)
    async function selectFromPhoneContacts() {
        // Check if running on mobile and Contact Picker API is available
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        if (isMobile && 'contacts' in navigator && 'select' in navigator.contacts) {
            try {
                Swal.fire({
                    title: 'Fungua anwani...',
                    text: 'Tafadhali ruhusu kufikia anwani zako',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const contacts = await navigator.contacts.select(['tel', 'name'], { multiple: true });
                
                Swal.close();
                
                if (contacts && contacts.length > 0) {
                    const phoneNumbers = [];
                    const names = [];
                    
                    contacts.forEach(contact => {
                        if (contact.tel && contact.tel.length > 0) {
                            // Get the first phone number
                            let phone = contact.tel[0];
                            // Remove any non-digit characters
                            phone = phone.replace(/[^0-9+]/g, '');
                            phoneNumbers.push(phone);
                            names.push(contact.name ? contact.name[0] : '');
                        }
                    });
                    
                    if (phoneNumbers.length > 0) {
                        const textarea = document.getElementById('phoneNumbers');
                        const namesInput = document.getElementById('namesInput');
                        const existingNumbers = textarea.value;
                        const existingNames = namesInput.value;
                        
                        const newNumbers = phoneNumbers.join('\n');
                        const newNames = names.join('\n');
                        
                        textarea.value = existingNumbers ? existingNumbers + '\n' + newNumbers : newNumbers;
                        namesInput.value = existingNames ? existingNames + '\n' + newNames : newNames;
                        
                        updateNumbersPreview();
                        showNotification(`Namba ${phoneNumbers.length} zimeongezwa kutoka anwani`, 'success');
                    } else {
                        showNotification('Hakuna namba za simu zilizopatikana', 'error');
                    }
                } else {
                    showNotification('Hakuna wasiliani waliochaguliwa', 'info');
                }
            } catch (error) {
                Swal.close();
                console.error('Error selecting contacts:', error);
                if (error.name === 'NotAllowedError') {
                    showNotification('Tafadhali ruhusu ufikiaji wa anwani za simu', 'error');
                } else {
                    showNotification('Hitilafu kufungua anwani. Jaribu kuweka namba kwa mkono.', 'error');
                }
            }
        } else {
            // For desktop or browsers without Contact Picker API
            Swal.fire({
                title: 'Weka Namba kwa Mkono',
                html: '<textarea id="manualNumbers" class="swal2-textarea" placeholder="Weka namba za simu, moja kwa mstari&#10;0712345678&#10;0754123456"></textarea>',
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
    
    // Load previous contributors
    async function loadPreviousContributors() {
        const eventId = document.getElementById('eventSelect')?.value;
        if (!eventId) {
            showNotification('Tafadhali chagua tukio kwanza', 'error');
            return;
        }
        
        Swal.fire({
            title: 'Inapakia...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        try {
            const response = await fetch(`/api/event/${eventId}/contributors`);
            const contributors = await response.json();
            
            Swal.close();
            
            if (contributors && contributors.length > 0) {
                const phoneNumbers = contributors.map(c => c.phone).join('\n');
                const names = contributors.map(c => c.name || '').join('\n');
                
                const textarea = document.getElementById('phoneNumbers');
                const namesInput = document.getElementById('namesInput');
                const existingNumbers = textarea.value;
                const existingNames = namesInput.value;
                
                textarea.value = existingNumbers ? existingNumbers + '\n' + phoneNumbers : phoneNumbers;
                namesInput.value = existingNames ? existingNames + '\n' + names : names;
                
                updateNumbersPreview();
                showNotification(`Namba ${contributors.length} za wachangiaji zimeongezwa`, 'success');
            } else {
                showNotification('Hakuna wachangiaji wa awali kwa tukio hili', 'info');
            }
        } catch (error) {
            Swal.close();
            console.error('Error loading contributors:', error);
            showNotification('Hitilafu kupakia wachangiaji. Tafadhali jaribu tena.', 'error');
        }
    }
    
    // Set template
    function setTemplate(templateText) {
        const messageTextarea = document.getElementById('messageText');
        if (messageTextarea) {
            messageTextarea.value = templateText;
            showNotification('Template imepakiwa. Unaweza kuibadilisha.', 'success');
        }
    }
    
    // Get link based on selection
    function getSelectedLink(eventId, phone) {
        const linkOption = document.querySelector('input[name="link_option"]:checked');
        if (!linkOption) return '';
        
        switch(linkOption.value) {
            case 'auto':
                return `/register-contributor/${eventId}/${phone}`;
            case 'custom':
                return document.getElementById('customLink')?.value || '';
            case 'none':
                return '';
            default:
                return '';
        }
    }
    
    // Preview message
    async function previewMessage() {
        const eventId = document.getElementById('eventSelect')?.value;
        if (!eventId) {
            showNotification('Tafadhali chagua tukio kwanza', 'error');
            return;
        }
        
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
    
    // Handle link option change
    function handleLinkOptionChange() {
        const linkOption = document.querySelector('input[name="link_option"]:checked');
        const customLinkContainer = document.getElementById('customLinkContainer');
        
        if (linkOption && linkOption.value === 'custom') {
            customLinkContainer.classList.add('active');
        } else {
            customLinkContainer.classList.remove('active');
        }
    }
    
    // Setup template buttons
    function setupTemplateButtons() {
        document.querySelectorAll('.btn-template').forEach(btn => {
            btn.addEventListener('click', function() {
                const template = this.getAttribute('data-template');
                if (template) {
                    setTemplate(template);
                }
            });
        });
        
        // Wedding custom template
        document.getElementById('weddingCustomTemplateBtn')?.addEventListener('click', function() {
            const weddingTemplate = "Habari [NAME],\n\nMzee Yusuph na familia yake wanakuomba mchango wako wa hali na mali ili kufanikisha sherehe ya [EVENT_NAME] itakayofanyika tarehe [EVENT_DATE].\n\nAsante kwa ushirikiano wako.";
            setTemplate(weddingTemplate);
        });
    }
    
    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Contact picker buttons
        document.getElementById('selectFromContactsBtn')?.addEventListener('click', selectFromPhoneContacts);
        document.getElementById('loadPreviousContributorsBtn')?.addEventListener('click', loadPreviousContributors);
        document.getElementById('clearAllNumbersBtn')?.addEventListener('click', clearAllNumbers);
        document.getElementById('previewBtn')?.addEventListener('click', previewMessage);
        
        // Link option change
        document.querySelectorAll('input[name="link_option"]').forEach(radio => {
            radio.addEventListener('change', handleLinkOptionChange);
        });
        
        // Setup template buttons
        setupTemplateButtons();
        
        // Update preview when textarea changes
        const phoneTextarea = document.getElementById('phoneNumbers');
        if (phoneTextarea) {
            phoneTextarea.addEventListener('input', updateNumbersPreview);
            phoneTextarea.addEventListener('change', updateNumbersPreview);
        }
        
        // Form submission
        const form = document.getElementById('messageForm');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const phoneNumbers = document.getElementById('phoneNumbers')?.value;
                if (!phoneNumbers || phoneNumbers.trim() === '') {
                    showNotification('Tafadhali weka angalau namba moja ya simu', 'error');
                    return;
                }
                
                // Validate phone numbers
                const numbers = phoneNumbers.split(/[\n\r,;\s]+/).filter(n => n.trim());
                const validNumbers = numbers.filter(n => {
                    const formatted = formatPhoneNumber(n);
                    return formatted.match(/^255[0-9]{9}$/);
                });
                
                if (validNumbers.length === 0) {
                    showNotification('Hakuna namba sahihi zilizowekwa. Hakikisha namba ni za Tanzania (0712345678 au 255712345678)', 'error');
                    return;
                }
                
                const message = document.getElementById('messageText')?.value;
                if (!message) {
                    showNotification('Tafadhali andika ujumbe', 'error');
                    return;
                }
                
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Inatuma SMS...';
                
                const formData = new FormData(form);
                
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Imefanikiwa!',
                            html: `<b>${result.total_sent}</b> kati ya <b>${validNumbers.length}</b> ujumbe umetumwa kikamilifu.`,
                            confirmButtonColor: '#FF6F00'
                        });
                        
                        if (result.total_sent > 0) {
                            document.getElementById('exportSection').style.display = 'block';
                        }
                        
                        if (result.failed && result.failed.length > 0) {
                            showNotification(`Namba zilizoshindwa: ${result.failed.slice(0, 5).join(', ')}${result.failed.length > 5 ? '...' : ''}`, 'error');
                        }
                        
                        // Clear form after successful send
                        if (result.total_sent === validNumbers.length) {
                            document.getElementById('phoneNumbers').value = '';
                            document.getElementById('namesInput').value = '';
                            updateNumbersPreview();
                        }
                    } else {
                        showNotification(result.message || 'Hitilafu ilitokea. Jaribu tena.', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Hitilafu ya mtandao. Jaribu tena.', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
        
        // Export Excel
        document.getElementById('exportExcelBtn')?.addEventListener('click', function() {
            window.location.href = '{{ route("ujumbe.download.export") }}';
        });
        
        // Initial preview update
        updateNumbersPreview();
        
        // Show session messages
        @if(session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif
    });
</script>
@endsection