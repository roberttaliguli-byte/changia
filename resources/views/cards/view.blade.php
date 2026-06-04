<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Kadi ya {{ ucfirst($card->card_type === 'invitation' ? 'Mwaliko' : 'Mchango') }} - {{ $card->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: url('https://images.unsplash.com/photo-1519741497674-611481863552?w=1600') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }
        
        /* Dark overlay for better readability */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }
        
        /* Two Column Layout */
        .two-column-layout {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }
        
        /* Left Column - Hero Section */
        .hero-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
            text-align: center;
            backdrop-filter: blur(2px);
        }
        
        .hero-content {
            max-width: 500px;
        }
        
        .hero-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 111, 0, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .hero-icon i {
            font-size: 50px;
            color: white;
        }
        
        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero-section .event-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #FF6F00;
        }
        
        .hero-section .quote {
            font-size: 0.9rem;
            margin-top: 30px;
            font-style: italic;
            opacity: 0.9;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hero-section .quote i {
            color: #FF6F00;
            margin: 0 5px;
        }
        
        /* Right Column - Card Section */
        .card-section {
            width: 500px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 100vh;
        }
        
        .card-container {
            padding: 40px;
        }
        
        .card-preview {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.6s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-header {
            background: linear-gradient(135deg, #FF6F00 0%, #E65100 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        
        .card-header h2 {
            font-size: 1.5rem;
            margin-bottom: 8px;
            font-weight: 700;
        }
        
        .card-header p {
            opacity: 0.9;
            font-size: 0.8rem;
        }
        
        .card-body {
            padding: 30px 24px;
        }
        
        .event-type {
            text-align: center;
            margin-bottom: 24px;
        }
        
        .badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #FFF3E0;
            color: #FF6F00;
        }
        
        .names {
            text-align: center;
            margin-bottom: 24px;
        }
        
        .names h3 {
            font-size: 1.5rem;
            color: #111827;
            margin-bottom: 8px;
            font-weight: 800;
        }
        
        .names p {
            color: #6B7280;
            font-size: 0.8rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .info-icon {
            width: 40px;
            font-size: 1.1rem;
            color: #FF6F00;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-content .label {
            font-size: 0.65rem;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-content .value {
            font-size: 0.85rem;
            font-weight: 500;
            color: #1F2937;
            margin-top: 4px;
        }
        
        .amount-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            margin: 20px 0;
        }
        
        .amount-box .label {
            font-size: 0.7rem;
            opacity: 0.9;
        }
        
        .amount-box .amount {
            font-size: 1.5rem;
            font-weight: 800;
            margin-top: 8px;
        }
        
        .description-text {
            background: #F9FAFB;
            padding: 16px;
            border-radius: 12px;
            margin: 16px 0;
            font-size: 0.8rem;
            color: #4B5563;
            line-height: 1.5;
        }
        
        .contact-info {
            background: #F3F4F6;
            padding: 16px;
            border-radius: 12px;
            margin-top: 20px;
            text-align: center;
        }
        
        .contact-info a {
            color: #FF6F00;
            text-decoration: none;
            font-weight: 600;
        }
        
        .share-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        
        .btn-share {
            flex: 1;
            padding: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-whatsapp {
            background: #25D366;
            color: white;
        }
        
        .btn-whatsapp:hover {
            background: #128C7E;
            transform: translateY(-2px);
        }
        
        .btn-copy {
            background: #3B82F6;
            color: white;
        }
        
        .btn-copy:hover {
            background: #2563EB;
            transform: translateY(-2px);
        }
        
        .btn-back {
            background: #6B7280;
            color: white;
        }
        
        .btn-back:hover {
            background: #4B5563;
            transform: translateY(-2px);
        }
        
        .footer {
            text-align: center;
            padding: 16px;
            background: #F9FAFB;
            font-size: 0.65rem;
            color: #9CA3AF;
            border-top: 1px solid #E5E7EB;
        }
        
        /* Scrollbar */
        .card-section::-webkit-scrollbar {
            width: 6px;
        }
        
        .card-section::-webkit-scrollbar-track {
            background: #F3F4F6;
        }
        
        .card-section::-webkit-scrollbar-thumb {
            background: #FF6F00;
            border-radius: 3px;
        }
        
        /* Responsive */
        @media (max-width: 968px) {
            .two-column-layout {
                flex-direction: column;
            }
            
            .hero-section {
                padding: 60px 20px;
                min-height: 350px;
            }
            
            .hero-section h1 {
                font-size: 1.8rem;
            }
            
            .hero-section .event-title {
                font-size: 1.1rem;
            }
            
            .hero-section .quote {
                display: none;
            }
            
            .card-section {
                width: 100%;
                max-width: 100%;
                max-height: none;
            }
            
            .card-container {
                padding: 24px;
            }
            
            .share-buttons {
                flex-direction: column;
            }
        }
        
        @media (max-width: 480px) {
            .card-container {
                padding: 16px;
            }
            
            .card-header {
                padding: 30px 16px;
            }
            
            .card-header h2 {
                font-size: 1.2rem;
            }
            
            .card-body {
                padding: 20px 16px;
            }
            
            .names h3 {
                font-size: 1.2rem;
            }
            
            .amount-box .amount {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="two-column-layout">
        <!-- Left Column - Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <div class="hero-icon">
                    <i class="fas {{ $card->card_type === 'invitation' ? 'fa-envelope-open-text' : 'fa-hand-holding-heart' }}"></i>
                </div>
                <h1>
                    @if($card->card_type === 'invitation')
                        Karibu Kwenye<br>Sherehe Yetu
                    @else
                        Karibu Kuchangia<br>Tukio Letu
                    @endif
                </h1>
                <div class="event-title">
                    <i class="fas fa-heart" style="color: #FF6F00; font-size: 0.9rem;"></i>
                    {{ ucfirst($card->title) }}
                    <i class="fas fa-heart" style="color: #FF6F00; font-size: 0.9rem;"></i>
                </div>
                <div class="quote">
                    <i class="fas fa-quote-left"></i>
                    @if($card->card_type === 'invitation')
                        Tunafuraha kukualika kushiriki nasi siku yetu maalum. Kila mwaliko ni baraka kwetu.
                    @else
                        Mchango wako wowote utasaidia sana kufanikisha tukio letu. Asante kwa kushiriki.
                    @endif
                    <i class="fas fa-quote-right"></i>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Card Section -->
        <div class="card-section">
            <div class="card-container">
                <div class="card-preview">
                    <div class="card-header">
                        <h2>
                            @if($card->card_type === 'invitation')
                                <i class="fas fa-envelope"></i> MWALIKO
                            @else
                                <i class="fas fa-hand-holding-heart"></i> OMBI LA MCHANGO
                            @endif
                        </h2>
                        <p>Kadi ya Kielektroniki</p>
                    </div>
                    
                    <div class="card-body">
                        <div class="event-type">
                            <span class="badge">
                                <i class="fas {{ $card->card_type === 'invitation' ? 'fa-gift' : 'fa-chart-line' }}"></i>
                                {{ ucfirst($card->title) }}
                            </span>
                        </div>
                        
                        <div class="names">
                            @if($card->card_type === 'invitation')
                                @if($card->groom_name && $card->bride_name)
                                    <h3>{{ $card->groom_name }} & {{ $card->bride_name }}</h3>
                                    <p>wanakuomba kwa heshima na upendo</p>
                                @elseif($card->honoree_name)
                                    <h3>{{ $card->honoree_name }}</h3>
                                    <p>wanakuomba kwa heshima na upendo</p>
                                @endif
                            @else
                                <h3>Mchango wa hiari</h3>
                                <p>Tunakukaribisha kuchangia</p>
                            @endif
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="info-content">
                                <div class="label">TAREHE</div>
                                <div class="value">{{ date('d F Y', strtotime($card->event_date)) }}</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-clock"></i></div>
                            <div class="info-content">
                                <div class="label">SAA</div>
                                <div class="value">{{ date('h:i A', strtotime($card->event_time)) }}</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="info-content">
                                <div class="label">MAHALI</div>
                                <div class="value">{{ $card->location }}</div>
                            </div>
                        </div>
                        
                        @if($card->card_type === 'contribution' && $card->suggested_amount)
                            <div class="amount-box">
                                <div class="label">KIASHO CHA MCHANGO</div>
                                <div class="amount">TSh {{ number_format($card->suggested_amount) }}</div>
                            </div>
                        @endif
                        
                        @if($card->description)
                            <div class="description-text">
                                <i class="fas fa-quote-left" style="color: #FF6F00; margin-right: 8px;"></i>
                                {{ $card->description }}
                            </div>
                        @endif
                        
                        <div class="contact-info">
                            <i class="fas fa-phone-alt"></i> Mawasiliano: 
                            <a href="tel:{{ $card->contact_phone }}">{{ $card->contact_phone }}</a>
                            @if($card->contact_email)
                                <br><i class="fas fa-envelope"></i> 
                                <a href="mailto:{{ $card->contact_email }}">{{ $card->contact_email }}</a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="footer">
                        <i class="fas fa-heart" style="color: #FF6F00;"></i>
                        Kadi imetengenezwa kwa teknolojia ya kisasa
                        <i class="fas fa-heart" style="color: #FF6F00;"></i>
                    </div>
                </div>
                
                <!-- Share Buttons -->
                <div class="share-buttons">
                    <button class="btn-share btn-whatsapp" onclick="shareOnWhatsApp()">
                        <i class="fab fa-whatsapp"></i> Shiriki WhatsApp
                    </button>
                    <button class="btn-share btn-copy" onclick="copyCardLink()">
                        <i class="fas fa-link"></i> Nakili Kiungo
                    </button>
                    <a href="{{ url()->previous() }}" class="btn-share btn-back">
                        <i class="fas fa-arrow-left"></i> Rudi Nyuma
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Get current page URL
        const currentUrl = window.location.href;
        
        // Share on WhatsApp
        function shareOnWhatsApp() {
            const eventName = "{{ $card->title }}";
            const cardType = "{{ $card->card_type === 'invitation' ? 'Mwaliko' : 'Ombi la Mchango' }}";
            const message = `🎉 *${cardType.toUpperCase()}* 🎉\n\nTazama kadi hii nzuri ya ${eventName}.\n\n🔗 ${currentUrl}\n\nImetengenezwa kwa teknolojia ya kisasa.`;
            const encodedMessage = encodeURIComponent(message);
            window.open(`https://wa.me/?text=${encodedMessage}`, '_blank');
        }
        
        // Copy card link
        function copyCardLink() {
            navigator.clipboard.writeText(currentUrl).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Ime nakiliwa!',
                    text: 'Kiungo kimenakiliwa kwenye clipboard',
                    timer: 1500,
                    showConfirmButton: false,
                    background: 'white',
                    customClass: {
                        popup: 'rounded-3'
                    }
                });
            }).catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Hitilafu',
                    text: 'Imeshindwa kunakili kiungo',
                    confirmButtonColor: '#FF6F00'
                });
            });
        }
        
        // Track view count (optional AJAX call)
        fetch('{{ route("cards.view", $card->share_link) }}', {
            method: 'HEAD',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).catch(() => {});
    </script>
</body>
</html>