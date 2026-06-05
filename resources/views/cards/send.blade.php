@extends('layouts.app')

@section('title', 'Tuma Kadi')
@section('page_title', 'Tuma Kadi kwa WhatsApp')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    :root {
        --primary: #FF6F00;
        --primary-light: #FFF3E0;
        --primary-dark: #E65100;
        --success: #10B981;
        --success-light: #D1FAE5;
        --danger: #EF4444;
        --info: #3B82F6;
        --info-light: #DBEAFE;
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

    .cards-container {
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

    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        background: var(--primary);
        border: none;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-create:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
        color: white;
    }

    .two-column-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .cards-grid-section {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .section-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        background: white;
    }

    .section-header h5 {
        font-weight: 700;
        font-size: 0.85rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-primary);
    }

    .section-header h5 i {
        color: var(--primary);
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
        padding: 20px;
        flex: 1;
        overflow-y: auto;
        max-height: calc(100vh - 200px);
    }

    .card-item {
        background: white;
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s;
        cursor: pointer;
        border: 2px solid transparent;
        position: relative;
    }

    .card-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .card-item.selected {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .card-item.selected::before {
        content: '✓';
        position: absolute;
        top: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
    }

    .card-image {
        width: 100%;
        height: 160px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .card-image i {
        font-size: 48px;
        color: white;
        opacity: 0.8;
    }

    .card-info {
        padding: 12px 16px;
    }

    .card-title {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 4px;
        color: var(--text-primary);
    }

    .card-date {
        font-size: 0.65rem;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .card-type {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
    }

    .type-invitation {
        background: var(--primary-light);
        color: var(--primary-dark);
    }

    .type-contribution {
        background: var(--success-light);
        color: var(--success);
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.55rem;
        font-weight: 600;
        margin-top: 6px;
    }

    .status-pending {
        background: var(--warning-light);
        color: var(--warning);
    }

    .status-approved {
        background: var(--info-light);
        color: var(--info);
    }

    .status-completed {
        background: var(--success-light);
        color: var(--success);
    }

    .status-rejected {
        background: #FEE2E2;
        color: var(--danger);
    }

    /* Send Section */
    .send-section {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .send-content {
        padding: 24px;
        flex: 1;
    }

    .selected-card-info {
        background: var(--bg-light);
        border-radius: var(--radius-md);
        padding: 16px;
        margin-bottom: 24px;
        border-left: 4px solid var(--primary);
    }

    .selected-card-info h6 {
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .selected-card-info p {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    /* Designed Card Preview */
    .designed-card {
        background: var(--bg-light);
        border-radius: var(--radius-md);
        padding: 16px;
        margin-bottom: 24px;
        border: 1px solid var(--success);
    }

    .designed-card h6 {
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--success);
    }

    .designed-card img {
        width: 100%;
        max-height: 300px;
        object-fit: contain;
        border-radius: var(--radius-sm);
        margin-bottom: 12px;
    }

    .designed-card .design-info {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-align: center;
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

    .input-with-icon {
        position: relative;
    }

    .input-with-icon i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .input-with-icon input {
        width: 100%;
        padding: 12px 14px 12px 42px;
        font-size: 0.85rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }

    .input-with-icon input:focus {
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
        gap: 5px;
    }

    .contact-buttons {
        display: flex;
        gap: 12px;
        margin-top: 16px;
        flex-wrap: wrap;
    }

    .btn-send {
        flex: 1;
        padding: 12px 20px;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-whatsapp {
        background: #25D366;
        color: white;
    }

    .btn-whatsapp:hover {
        background: #128C7E;
        transform: translateY(-1px);
    }

    .btn-whatsapp:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-contact {
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
    }

    .btn-contact:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: var(--radius-lg);
    }

    .empty-state i {
        font-size: 64px;
        color: var(--text-muted);
        opacity: 0.3;
        margin-bottom: 16px;
    }

    .empty-state h6 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .empty-state p {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .spinner-sm {
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    @media (max-width: 1024px) {
        .cards-container { padding: 20px 24px; }
    }

    @media (max-width: 768px) {
        .cards-container { padding: 16px; }
        .two-column-layout { grid-template-columns: 1fr; gap: 16px; }
        .cards-grid { max-height: 400px; padding: 16px; }
        .send-content { padding: 20px; }
        .contact-buttons { flex-direction: column; }
        .btn-send { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="cards-container">
    <div class="header-section">
        <div class="header-title">
            <h4>Tuma Kadi</h4>
            <p>Chagua kadi na utume kwa wapokeaji kupitia WhatsApp</p>
        </div>
        <a href="{{ route('cards.create') }}" class="btn-create">
            <i class="fas fa-plus-circle"></i> Tengeneza Kadi Mpya
        </a>
    </div>

    @if($cards->count() > 0)
        <div class="two-column-layout">
            <!-- Left Column - Cards Grid -->
            <div class="cards-grid-section">
                <div class="section-header">
                    <h5>
                        <i class="fas fa-id-card"></i>
                        Kadi Zako
                    </h5>
                    <p>Bonyeza kwenye kadi ili kuichagua</p>
                </div>
                <div class="cards-grid" id="cardsGrid">
                    @foreach($cards as $card)
                        <div class="card-item" 
                             data-card-id="{{ $card->id }}" 
                             data-card-title="{{ $card->title }}" 
                             data-card-date="{{ date('d M Y', strtotime($card->event_date)) }}" 
                             data-card-type="{{ $card->card_type }}"
                             data-admin-status="{{ $card->admin_status }}"
                             data-design-file="{{ $card->design_file_path }}"
                             onclick="selectCard({{ $card->id }})">
   <div class="card-image">
    @if($card->design_file_path && $card->admin_status == 'completed')
        <img src="{{ asset('storage/' . $card->design_file_path) }}" alt="Designed Card" style="width:100%; height:100%; object-fit:cover;">
    @else
        <i class="fas {{ $card->card_type === 'invitation' ? 'fa-envelope-open-text' : 'fa-hand-holding-heart' }}"></i>
    @endif
</div>
                            <div class="card-info">
                                <div class="card-title">{{ ucfirst($card->title) }}</div>
                                <div class="card-date">{{ date('d M Y', strtotime($card->event_date)) }}</div>
                                <span class="card-type type-{{ $card->card_type }}">
                                    {{ $card->card_type === 'invitation' ? '📨 Mwaliko' : '🤝 Mchango' }}
                                </span>
                                <br>
                                <span class="status-badge status-{{ $card->admin_status }}">
                                    @if($card->admin_status == 'pending') ⏳ Inasubiri
                                    @elseif($card->admin_status == 'approved') ✓ Imeidhinishwa
                                    @elseif($card->admin_status == 'completed') ✅ Imekamilika
                                    @else ❌ Imekataliwa @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column - Send Section -->
            <div class="send-section" id="sendSection">
                <div class="section-header">
                    <h5>
                        <i class="fab fa-whatsapp"></i>
                        Tuma Kadi
                    </h5>
                    <p>Jaza taarifa za mpokeaji na utume</p>
                </div>
                <div class="send-content">
                    <div id="selectedCardPreview" class="selected-card-info" style="display: none;">
                        <h6><i class="fas fa-check-circle" style="color: var(--success);"></i> Kadi Imechaguliwa</h6>
                        <p id="selectedCardTitle">-</p>
                        <p id="selectedCardDate">-</p>
                        <p id="selectedCardType">-</p>
                        <p id="selectedCardStatus">-</p>
                    </div>

                    <!-- Designed Card from Admin -->
                    <div id="designedCardPreview" class="designed-card" style="display: none;">
                        <h6><i class="fas fa-palette"></i> Kadi Iliyobuniwa na Msimamizi</h6>
                        <img id="designedCardImage" src="" alt="Designed Card">
                        <div class="design-info" id="designInfo"></div>
                    </div>

                    <form id="sendForm">
                        @csrf
                        <input type="hidden" name="card_id" id="selectedCardId">

                        <div class="form-group">
                            <label>Namba ya Simu ya Mpokeaji <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-phone-alt"></i>
                                <input type="tel" name="phone_number" id="phoneNumber" class="form-control" placeholder="0712345678" required>
                            </div>
                            <div class="help-text">
                                <i class="fas fa-info-circle"></i> Weka namba ya simu kuanzia 0 au 255 (Mfano: 0712345678 au 255712345678)
                            </div>
                        </div>

                        <div class="contact-buttons">
                            <button type="button" class="btn-send btn-contact" id="selectContactBtn">
                                <i class="fas fa-address-book"></i> Chagua kutoka Anwani
                            </button>
                            <button type="submit" class="btn-send btn-whatsapp" id="submitBtn">
                                <i class="fab fa-whatsapp"></i> Tuma kwa WhatsApp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-id-card"></i>
            <h6>Hakuna Kadi Bado</h6>
            <p>Jaza fomu ya kuunda kadi yako ya kwanza kuanza kutuma</p>
            <a href="{{ route('cards.create') }}" class="btn-create" style="display: inline-flex;">
                <i class="fas fa-plus-circle"></i> Tengeneza Kadi
            </a>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let selectedCardId = null;
    let cardData = {};

    // Fetch card details from server
    async function fetchCardDetails(cardId) {
        try {
            const response = await fetch(`/cards/data/${cardId}`);
            const data = await response.json();
            cardData[cardId] = data;
            return data;
        } catch (error) {
            console.error('Error fetching card details:', error);
            return null;
        }
    }

async function selectCard(cardId) {
    // Remove selected class from all cards
    document.querySelectorAll('.card-item').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    const selectedCard = document.querySelector(`.card-item[data-card-id="${cardId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('selected');
        
        // Get card data from attributes
        const cardTitle = selectedCard.getAttribute('data-card-title');
        const cardDate = selectedCard.getAttribute('data-card-date');
        const cardType = selectedCard.getAttribute('data-card-type');
        const adminStatus = selectedCard.getAttribute('data-admin-status');
        
        const cardTypeText = cardType === 'invitation' ? '📨 Mwaliko' : '🤝 Ombi la Mchango';
        
        let statusText = '';
        let canSend = false;
        
        switch(adminStatus) {
            case 'pending':
                statusText = '⏳ Inasubiri uidhinishwe na msimamizi';
                canSend = false;
                break;
            case 'approved':
                statusText = '✓ Imeidhinishwa - Inasubiri kubuniwa';
                canSend = false;
                break;
            case 'completed':
                statusText = '✅ Imekamilika - Unaweza kutuma kadi';
                canSend = true;
                break;
            case 'rejected':
                statusText = '❌ Imekataliwa - Wasiliana na msimamizi';
                canSend = false;
                break;
        }
        
        // Update preview
        document.getElementById('selectedCardId').value = cardId;
        document.getElementById('selectedCardTitle').innerHTML = `<i class="fas fa-tag"></i> ${cardTitle}`;
        document.getElementById('selectedCardDate').innerHTML = `<i class="far fa-calendar"></i> ${cardDate}`;
        document.getElementById('selectedCardType').innerHTML = `<i class="fas fa-info-circle"></i> ${cardTypeText}`;
        document.getElementById('selectedCardStatus').innerHTML = `<i class="fas fa-clock"></i> ${statusText}`;
        document.getElementById('selectedCardPreview').style.display = 'block';
        
        // Fetch card details from server to get the image
        const details = await fetchCardDetails(cardId);
        
        // Show designed card if completed
        const designedCardDiv = document.getElementById('designedCardPreview');
        if (adminStatus === 'completed' && details && details.design_file_url) {
            document.getElementById('designedCardImage').src = details.design_file_url;
            
            if (details.design_cost) {
                document.getElementById('designInfo').innerHTML = `Gharama ya kubuni: TSh ${Number(details.design_cost).toLocaleString()}`;
            } else {
                document.getElementById('designInfo').innerHTML = 'Kadi imebuniwa na msimamizi';
            }
            designedCardDiv.style.display = 'block';
        } else {
            designedCardDiv.style.display = 'none';
        }
        
        // Enable/disable send button based on status
        const submitBtn = document.getElementById('submitBtn');
        if (canSend) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
        }
    }
    
    selectedCardId = cardId;
}

    // Select from phone contacts
    async function selectFromContacts() {
        if ('contacts' in navigator && 'select' in navigator.contacts) {
            try {
                Swal.fire({
                    title: 'Fungua anwani...',
                    text: 'Tafadhali ruhusu kufikia anwani zako',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                const contacts = await navigator.contacts.select(['tel'], { multiple: false });
                Swal.close();
                
                if (contacts && contacts.length > 0 && contacts[0].tel && contacts[0].tel.length > 0) {
                    let phone = contacts[0].tel[0];
                    phone = phone.replace(/[^0-9+]/g, '');
                    phone = phone.replace(/^\+/, '');
                    if (phone.startsWith('0')) {
                        phone = '255' + phone.substring(1);
                    }
                    document.getElementById('phoneNumber').value = phone;
                    Swal.fire({
                        icon: 'success',
                        title: 'Imefanikiwa!',
                        text: 'Namba imechaguliwa',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Hakuna namba', 'Hakuna namba ya simu iliyochaguliwa', 'info');
                }
            } catch (error) {
                Swal.close();
                Swal.fire('Hitilafu', 'Imeshindwa kufungua anwani', 'error');
            }
        } else {
            Swal.fire({
                title: 'Kipengele Hakipo',
                text: 'Tafadhali weka namba kwa mkono',
                icon: 'info',
                confirmButtonColor: '#FF6F00'
            });
        }
    }

    document.getElementById('selectContactBtn')?.addEventListener('click', selectFromContacts);

    // Send form submission
    document.getElementById('sendForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!selectedCardId) {
            Swal.fire({
                title: 'Hitilafu',
                text: 'Tafadhali chagua kadi kwanza',
                icon: 'warning',
                confirmButtonColor: '#FF6F00'
            });
            return;
        }
        
        let phoneNumber = document.getElementById('phoneNumber').value;
        if (!phoneNumber) {
            Swal.fire({
                title: 'Hitilafu',
                text: 'Tafadhali weka namba ya simu',
                icon: 'warning',
                confirmButtonColor: '#FF6F00'
            });
            return;
        }
        
        phoneNumber = phoneNumber.replace(/[^0-9+]/g, '');
        document.getElementById('phoneNumber').value = phoneNumber;
        
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-sm"></span> Inatuma...';
        
        const formData = new FormData(this);
        formData.append('card_id', selectedCardId);
        
        try {
            const response = await fetch('{{ route("cards.share") }}', {
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
                    html: 'Kadi imetumwa kikamilifu kupitia WhatsApp',
                    confirmButtonColor: '#FF6F00',
                    timer: 3000,
                    timerProgressBar: true
                });
                
                document.getElementById('phoneNumber').value = '';
            } else {
                Swal.fire({
                    title: 'Hitilafu',
                    text: result.message || 'Imeshindwa kutuma kadi',
                    icon: 'error',
                    confirmButtonColor: '#FF6F00'
                });
            }
        } catch (error) {
            Swal.fire({
                title: 'Hitilafu',
                text: 'Imeshindwa kutuma kadi. Jaribu tena.',
                icon: 'error',
                confirmButtonColor: '#FF6F00'
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
</script>
@endsection