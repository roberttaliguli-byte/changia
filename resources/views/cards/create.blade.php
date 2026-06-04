@extends('layouts.app')

@section('title', 'Tengeneza Kadi')
@section('page_title', 'Tengeneza Kadi ya Mwaliko')

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
    .cards-container {
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

    /* Two Column Layout */
    .two-column-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    /* Card Styles */
    .card-custom {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card-header-custom {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
    }

    .card-header-custom h2 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-header-custom p {
        margin: 8px 0 0;
        opacity: 0.9;
        font-size: 0.75rem;
    }

    /* Form Content */
    .form-content {
        padding: 24px;
        flex: 1;
        overflow-y: auto;
        max-height: calc(100vh - 200px);
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

    .form-group label .required {
        color: var(--danger);
    }

    .form-control, .form-select {
        width: 100%;
        padding: 10px 12px;
        font-size: 0.8rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        color: white;
        background: var(--primary);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 16px;
    }

    .btn-submit:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Preview Section - Right Column */
    .preview-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .preview-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .preview-header h2 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .preview-header p {
        margin: 8px 0 0;
        opacity: 0.9;
        font-size: 0.75rem;
    }

    .preview-content {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .preview-placeholder {
        color: var(--text-muted);
    }

    .preview-placeholder i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
    }

    .preview-card-generated {
        display: none;
    }

    .preview-card-generated.active {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .card-preview {
        background: linear-gradient(135deg, #fff 0%, #fef3e2 100%);
        border-radius: var(--radius-md);
        padding: 24px;
        width: 100%;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        margin-bottom: 20px;
    }

    .card-preview h3 {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 16px;
    }

    .card-preview .event-detail {
        margin-bottom: 12px;
        text-align: left;
    }

    .card-preview .event-detail i {
        width: 24px;
        color: var(--primary);
        margin-right: 8px;
    }

    .card-preview .event-detail span {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .success-icon {
        width: 60px;
        height: 60px;
        background: var(--success);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .success-icon i {
        font-size: 30px;
        color: white;
    }

    .share-buttons {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        width: 100%;
    }

    .btn-whatsapp {
        flex: 1;
        padding: 10px;
        background: #25D366;
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-whatsapp:hover {
        background: #128C7E;
        transform: translateY(-1px);
    }

    .btn-copy {
        flex: 1;
        padding: 10px;
        background: var(--info);
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-copy:hover {
        background: #2563EB;
        transform: translateY(-1px);
    }

    /* Badge Styles */
    .badge-custom {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .badge-invitation {
        background: var(--primary-light);
        color: var(--primary-dark);
    }

    .badge-contribution {
        background: var(--success-light);
        color: var(--success);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .cards-container {
            padding: 20px 24px;
        }
    }

    @media (max-width: 768px) {
        .cards-container {
            padding: 16px;
        }

        .two-column-layout {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .form-content {
            max-height: none;
            padding: 20px;
        }

        .card-header-custom, .preview-header {
            padding: 16px;
        }

        .share-buttons {
            flex-direction: column;
        }
    }

    @media (max-width: 480px) {
        .cards-container {
            padding: 12px;
        }

        .form-content {
            padding: 16px;
        }

        .card-preview {
            padding: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="cards-container">
    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h4>Tengeneza Kadi ya Mwaliko</h4>
            <p>Jaza taarifa na utengeneze kadi nzuri ya mwaliko au ombi la mchango</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-submit" style="width: auto; padding: 8px 20px; margin-top: 0;">
            <i class="fas fa-arrow-left"></i> Rudi Dashboard
        </a>
    </div>

    <!-- Two Column Layout -->
    <div class="two-column-layout">
        <!-- Left Column - Form Section -->
        <div class="card-custom">
            <div class="card-header-custom">
                <h2>
                    <i class="fas fa-id-card"></i> Unda Kadi Yako
                </h2>
                <p>Jaza taarifa zote hapa kuunda kadi yako</p>
            </div>

            <div class="form-content">
                <form id="cardForm">
                    @csrf

                    <div class="form-group">
                        <label>Aina ya Kadi <span class="required">*</span></label>
                        <select name="card_type" id="cardType" class="form-select" required>
                            <option value="">-- Chagua Aina --</option>
                            <option value="invitation">📨 Kadi ya Mwaliko</option>
                            <option value="contribution">🤝 Kadi ya Ombi la Mchango</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jina la Tukio <span class="required">*</span></label>
                        <select name="title" id="eventTitle" class="form-select" required>
                            <option value="">-- Chagua Aina ya Tukio --</option>
                            <option value="harusi">💍 Harusi (Wedding)</option>
                            <option value="send_off">✈️ Send Off / Farewell</option>
                            <option value="graduation">🎓 Mahafali (Graduation)</option>
                            <option value="birthday">🎂 Siku ya Kuzaliwa (Birthday)</option>
                            <option value="anniversary">💕 Maadhimisho ya Miaka</option>
                            <option value="other">📌 Nyinginezo</option>
                        </select>
                    </div>

                    <div id="namesSection">
                        <div class="form-group" id="groomSection">
                            <label>Jina la Bwana Harusi / Mhusika</label>
                            <input type="text" name="groom_name" class="form-control" placeholder="Mfano: Yusuph Juma">
                        </div>

                        <div class="form-group" id="brideSection">
                            <label>Jina la Bi Harusi / Mhusika Mwenza</label>
                            <input type="text" name="bride_name" class="form-control" placeholder="Mfano: Amina Mohamed">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tarehe ya Tukio <span class="required">*</span></label>
                        <input type="date" name="event_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Saa ya Tukio <span class="required">*</span></label>
                        <input type="time" name="event_time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Mahali pa Tukio <span class="required">*</span></label>
                        <textarea name="location" class="form-control" rows="2" placeholder="Mfano: JNICC, Dar es Salaam" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Maelezo ya Ziada (Hiari)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Maelezo mengine kuhusu tukio..."></textarea>
                    </div>

                    <div id="amountSection" style="display: none;">
                        <div class="form-group">
                            <label>Kiasho cha Mchango (TSh)</label>
                            <input type="number" name="suggested_amount" class="form-control" placeholder="Mfano: 50000">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Namba ya Mawasiliano <span class="required">*</span></label>
                        <input type="tel" name="contact_phone" class="form-control" placeholder="Mfano: 0712345678" required>
                    </div>

                    <div class="form-group">
                        <label>Barua pepe (Hiari)</label>
                        <input type="email" name="contact_email" class="form-control" placeholder="email@example.com">
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-magic"></i> Tengeneza Kadi
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column - Preview Section -->
        <div class="preview-card">
            <div class="preview-header">
                <h2>
                    <i class="fas fa-eye"></i> Hakiki Kadi Yako
                </h2>
                <p>Huu ni muonekano wa kadi yako baada ya kuundwa</p>
            </div>

            <div class="preview-content">
                <div id="previewPlaceholder" class="preview-placeholder">
                    <i class="fas fa-id-card"></i>
                    <p>Hakuna kadi ya kuonyesha</p>
                    <small>Jaza fomu upande wa kushoto kuona hakiki</small>
                </div>

                <div id="previewGenerated" class="preview-card-generated">
                    <div class="card-preview" id="cardPreview">
                        <!-- Dynamic preview will be inserted here -->
                    </div>
                    <div class="share-buttons">
                        <button class="btn-whatsapp" onclick="shareOnWhatsApp()">
                            <i class="fab fa-whatsapp"></i> Shiriki WhatsApp
                        </button>
                        <button class="btn-copy" onclick="copyCardLink()">
                            <i class="fas fa-link"></i> Nakili Kiungo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let generatedLink = '';

    // Toggle amount section based on card type
    document.getElementById('cardType')?.addEventListener('change', function() {
        const amountSection = document.getElementById('amountSection');
        if (this.value === 'contribution') {
            amountSection.style.display = 'block';
        } else {
            amountSection.style.display = 'none';
        }
    });

    // Toggle bride section based on event type
    document.getElementById('eventTitle')?.addEventListener('change', function() {
        const groomSection = document.getElementById('groomSection');
        const brideSection = document.getElementById('brideSection');
        const title = this.value;
        const groomInput = document.querySelector('input[name="groom_name"]');
        const brideInput = document.querySelector('input[name="bride_name"]');

        if (title === 'harusi') {
            groomSection.style.display = 'block';
            brideSection.style.display = 'block';
            groomInput.required = true;
            brideInput.required = true;
            document.querySelector('#groomSection label').innerHTML = 'Jina la Bwana Harusi';
            document.querySelector('#brideSection label').innerHTML = 'Jina la Bi Harusi';
        } else if (title === 'send_off') {
            groomSection.style.display = 'block';
            brideSection.style.display = 'none';
            groomInput.required = true;
            brideInput.required = false;
            document.querySelector('#groomSection label').innerHTML = 'Jina la Mhusika';
        } else if (title === 'graduation') {
            groomSection.style.display = 'block';
            brideSection.style.display = 'none';
            groomInput.required = true;
            brideInput.required = false;
            document.querySelector('#groomSection label').innerHTML = 'Jina la Mhitimu';
        } else if (title === 'birthday') {
            groomSection.style.display = 'block';
            brideSection.style.display = 'none';
            groomInput.required = true;
            brideInput.required = false;
            document.querySelector('#groomSection label').innerHTML = 'Jina la Mhusika';
        } else if (title === 'anniversary') {
            groomSection.style.display = 'block';
            brideSection.style.display = 'block';
            groomInput.required = true;
            brideInput.required = true;
            document.querySelector('#groomSection label').innerHTML = 'Jina la Mume';
            document.querySelector('#brideSection label').innerHTML = 'Jina la Mke';
        } else {
            groomSection.style.display = 'block';
            brideSection.style.display = 'none';
            groomInput.required = true;
            brideInput.required = false;
            document.querySelector('#groomSection label').innerHTML = 'Jina la Mhusika';
        }
    });

    // Live preview update
    function updatePreview() {
        const cardType = document.getElementById('cardType')?.value;
        const eventTitle = document.getElementById('eventTitle')?.value;
        const groomName = document.querySelector('input[name="groom_name"]')?.value || '';
        const brideName = document.querySelector('input[name="bride_name"]')?.value || '';
        const eventDate = document.querySelector('input[name="event_date"]')?.value || '';
        const eventTime = document.querySelector('input[name="event_time"]')?.value || '';
        const location = document.querySelector('textarea[name="location"]')?.value || '';
        const description = document.querySelector('textarea[name="description"]')?.value || '';
        const suggestedAmount = document.querySelector('input[name="suggested_amount"]')?.value || '';
        const phone = document.querySelector('input[name="contact_phone"]')?.value || '';

        const previewDiv = document.getElementById('cardPreview');
        let titleText = '';
        
        // Set title based on event type
        switch(eventTitle) {
            case 'harusi': titleText = 'Harusi ya'; break;
            case 'send_off': titleText = 'Send Off ya'; break;
            case 'graduation': titleText = 'Mahafali ya'; break;
            case 'birthday': titleText = 'Siku ya Kuzaliwa ya'; break;
            case 'anniversary': titleText = 'Maadhimisho ya Miaka ya'; break;
            default: titleText = 'Sherehe ya';
        }

        let namesText = '';
        if (eventTitle === 'harusi' && groomName && brideName) {
            namesText = `${groomName} & ${brideName}`;
        } else if (groomName) {
            namesText = groomName;
        }

        let amountText = '';
        if (cardType === 'contribution' && suggestedAmount) {
            amountText = `<div class="event-detail">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Mchango unaopendekezwa: <strong>${parseInt(suggestedAmount).toLocaleString()} TSh</strong></span>
            </div>`;
        }

        const badgeClass = cardType === 'invitation' ? 'badge-invitation' : 'badge-contribution';
        const badgeText = cardType === 'invitation' ? '📨 Mwaliko' : '🤝 Ombi la Mchango';

        previewDiv.innerHTML = `
            <div style="text-align: center; margin-bottom: 16px;">
                <span class="badge-custom ${badgeClass}">${badgeText}</span>
            </div>
            <h3 style="text-align: center; font-size: 1.25rem; margin-bottom: 16px;">${titleText} ${namesText}</h3>
            <div class="event-detail">
                <i class="fas fa-calendar-alt"></i>
                <span>Tarehe: ${eventDate || 'Not set'} ${eventTime ? 'saa ' + eventTime : ''}</span>
            </div>
            <div class="event-detail">
                <i class="fas fa-map-marker-alt"></i>
                <span>Mahali: ${location || 'Not set'}</span>
            </div>
            ${description ? `<div class="event-detail">
                <i class="fas fa-info-circle"></i>
                <span>${description}</span>
            </div>` : ''}
            ${amountText}
            <div class="event-detail" style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #E5E7EB;">
                <i class="fas fa-phone-alt"></i>
                <span>Mawasiliano: ${phone}</span>
            </div>
            <div style="text-align: center; margin-top: 20px; padding-top: 12px; border-top: 1px solid #E5E7EB;">
                <small style="color: #6B7280;">CHANGIA SMART - Mfumo wa Kukusanya Michango</small>
            </div>
        `;
    }

    // Add event listeners for live preview
    const formInputs = ['cardType', 'eventTitle', 'groom_name', 'bride_name', 'event_date', 'event_time', 'location', 'description', 'suggested_amount', 'contact_phone'];
    formInputs.forEach(id => {
        const element = document.getElementById(id) || document.querySelector(`[name="${id}"]`);
        if (element) {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        }
    });

    // Initialize preview on page load
    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
    });

    // Form submission
    document.getElementById('cardForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Inatengeneza...';

        const formData = new FormData(this);

        try {
            const response = await fetch('{{ route("cards.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                generatedLink = result.share_link;
                
                // Show generated preview with success
                document.getElementById('previewPlaceholder').style.display = 'none';
                const generatedDiv = document.getElementById('previewGenerated');
                generatedDiv.classList.add('active');
                generatedDiv.style.display = 'flex';
                
                // Add success message to preview
                const previewDiv = document.getElementById('cardPreview');
                const successHtml = `
                    <div class="success-icon" style="margin: 0 auto 16px;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p style="color: #10B981; font-weight: 600; margin-bottom: 16px;">✓ Kadi imeundwa kikamilifu!</p>
                `;
                previewDiv.insertAdjacentHTML('afterbegin', successHtml);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Imefanikiwa!',
                    html: `Kadi yako imeundwa kikamilifu.<br><br>Unaweza kuishiriki kwa kupitia WhatsApp au kunakili kiungo.`,
                    confirmButtonColor: '#FF6F00',
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                Swal.fire('Hitilafu', result.message || 'Imeshindwa kuunda kadi', 'error');
            }
        } catch (error) {
            Swal.fire('Hitilafu', 'Imeshindwa kuunda kadi. Jaribu tena.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // Share on WhatsApp
    function shareOnWhatsApp() {
        if (generatedLink) {
            const message = encodeURIComponent(`Nimeunda kadi ya mwaliko. Tazama hapa: ${generatedLink}`);
            window.open(`https://wa.me/?text=${message}`, '_blank');
        } else {
            Swal.fire('Hitilafu', 'Hakuna kiungo cha kushiriki. Jaribu kuunda kadi kwanza.', 'error');
        }
    }

    // Copy card link
    function copyCardLink() {
        if (generatedLink) {
            navigator.clipboard.writeText(generatedLink).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Ime nakiliwa!',
                    text: 'Kiungo kimenakiliwa kwenye clipboard',
                    timer: 1500,
                    showConfirmButton: false
                });
            }).catch(() => {
                Swal.fire('Hitilafu', 'Imeshindwa kunakili kiungo', 'error');
            });
        } else {
            Swal.fire('Hitilafu', 'Hakuna kiungo cha kunakili. Jaribu kuunda kadi kwanza.', 'error');
        }
    }
</script>
@endsection