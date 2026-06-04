<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usajili Umekamilika - {{ $event->event_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #FF6F00;
            --primary-dark: #E65100;
            --primary-light: #FFF3E0;
            --success: #10B981;
            --text-primary: #000000;
            --text-secondary: #1F2937;
            --text-muted: #4B5563;
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
        }
        
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
            background: rgba(16, 185, 129, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(16, 185, 129, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }
        
        .hero-icon i {
            font-size: 50px;
            color: white;
        }
        
        .hero-section h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.2;
        }
        
        .hero-section .event-name {
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
        
        /* Right Column - Success Section */
        .success-section {
            width: 500px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 100vh;
            text-align: center;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: scaleIn 0.5s ease-out;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .success-icon i {
            font-size: 40px;
            color: white;
        }
        
        .success-section h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            animation: fadeInUp 0.5s ease-out 0.2s both;
        }
        
        .contributor-name {
            color: var(--primary);
            font-weight: 800;
        }
        
        .amount-card {
            background: var(--primary-light);
            border-radius: var(--radius-md);
            padding: 20px;
            margin: 20px 0;
            animation: fadeInUp 0.5s ease-out 0.3s both;
        }
        
        .amount-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        .amount {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        .amount small {
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .message-card {
            background: var(--bg-light);
            border-radius: var(--radius-md);
            padding: 20px;
            margin: 20px 0;
            text-align: left;
            animation: fadeInUp 0.5s ease-out 0.4s both;
        }
        
        .message-card p {
            color: var(--text-secondary);
            font-size: 0.85rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .message-card .event-highlight {
            font-weight: 700;
            color: var(--primary);
        }
        
        .info-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        
        .info-item i {
            width: 20px;
            color: var(--primary);
        }
        
        .button-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 20px;
            animation: fadeInUp 0.5s ease-out 0.5s both;
        }
        
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        .btn-home:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-share {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: #25D366;
            color: white;
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .btn-share:hover {
            background: #128C7E;
            transform: translateY(-2px);
        }
        
        .footer {
            text-align: center;
            padding-top: 20px;
            margin-top: 20px;
            font-size: 0.65rem;
            color: var(--text-muted);
            border-top: 1px solid var(--border-color);
            animation: fadeInUp 0.5s ease-out 0.6s both;
        }
        
        /* Confetti animation */
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: var(--primary);
            position: absolute;
            animation: confettiFall 3s linear forwards;
        }
        
        @keyframes confettiFall {
            to {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Scrollbar */
        .success-section::-webkit-scrollbar {
            width: 6px;
        }
        
        .success-section::-webkit-scrollbar-track {
            background: var(--bg-light);
        }
        
        .success-section::-webkit-scrollbar-thumb {
            background: var(--primary);
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
                font-size: 1.5rem;
            }
            
            .hero-section .event-name {
                font-size: 1rem;
            }
            
            .hero-section .quote {
                display: none;
            }
            
            .success-section {
                width: 100%;
                max-width: 100%;
                padding: 30px 20px;
                max-height: none;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .btn-home, .btn-share {
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .success-section {
                padding: 24px 16px;
            }
            
            .amount {
                font-size: 1.5rem;
            }
            
            .message-card {
                padding: 16px;
            }
            
            .info-item {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="two-column-layout">
        <!-- Left Column - Hero Section with Wedding Theme -->
        <div class="hero-section">
            <div class="hero-content">
                <div class="hero-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Ahadi Yako<br>Imepokelewa!</h1>
                <div class="event-name">
                    <i class="fas fa-heart" style="color: #FF6F00; font-size: 0.9rem;"></i>
                    {{ $event->event_name }}
                    <i class="fas fa-heart" style="color: #FF6F00; font-size: 0.9rem;"></i>
                </div>
                <div class="quote">
                    <i class="fas fa-quote-left"></i>
                    Kila mchango unasaidia kufanikisha ndoto zetu. Asante kwa kuwa sehemu ya siku yetu maalum!
                    <i class="fas fa-quote-right"></i>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Success Message -->
        <div class="success-section">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h2>Asante <span class="contributor-name">{{ $contributor->name }}</span>!</h2>
            <p style="color: var(--text-muted); font-size: 0.8rem;">Ahadi yako imerekodiwa kikamilifu</p>
            
            <div class="amount-card">
                <div class="amount-label">Umeahidi Kiasi Cha</div>
                <div class="amount">
                    {{ number_format($contributor->promised_amount) }} <small>TSh</small>
                </div>
            </div>
            
            <div class="message-card">
                <p>
                    <i class="fas fa-gift" style="color: var(--primary); margin-right: 8px;"></i>
                    Umeahidi kuchangia <strong class="event-highlight">{{ number_format($contributor->promised_amount) }} TSh</strong> 
                    kwa tukio la <strong class="event-highlight">{{ $event->event_name }}</strong>.
                </p>
                <p>
                    <i class="fas fa-clock" style="color: var(--primary); margin-right: 8px;"></i>
                    Mhasibu atakusiliana nawe kwa maelezo zaidi ya malipo kwa muda wa siku 2-3.
                </p>
                
                <div class="info-list">
                    <div class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>Utapokea simu au ujumbe wa uthibitisho</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-hand-holding-usd"></i>
                        <span>Unaweza kulipa kwa awamu (sehemu kwa sehemu)</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-receipt"></i>
                        <span>Utapata risiti baada ya kila malipo</span>
                    </div>
                </div>
            </div>
            
            <div class="button-group">
                <a href="{{ route('home') }}" class="btn-home">
                    <i class="fas fa-home"></i> Rudi Nyumbani
                </a>
                <button onclick="shareSuccess()" class="btn-share">
                    <i class="fab fa-whatsapp"></i> Shiriki Habari
                </button>
            </div>
            
            <div class="footer">
                <p><i class="fas fa-heart" style="color: #FF6F00;"></i> CHANGIA SMART - Asante kwa ushirikiano wako! <i class="fas fa-heart" style="color: #FF6F00;"></i></p>
            </div>
        </div>
    </div>
    
    <script>
        // Create confetti effect on page load
        function createConfetti() {
            const colors = ['#FF6F00', '#10B981', '#F59E0B', '#3B82F6', '#EF4444'];
            for (let i = 0; i < 100; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 10 + 5 + 'px';
                confetti.style.height = confetti.style.width;
                confetti.style.position = 'fixed';
                confetti.style.top = '-10px';
                confetti.style.borderRadius = '50%';
                confetti.style.animationDelay = Math.random() * 2 + 's';
                confetti.style.animationDuration = Math.random() * 2 + 2 + 's';
                document.body.appendChild(confetti);
                
                // Remove confetti after animation
                setTimeout(() => {
                    confetti.remove();
                }, 4000);
            }
        }
        
        // Share success on WhatsApp
        function shareSuccess() {
            const eventName = "{{ $event->event_name }}";
            const contributorName = "{{ $contributor->name }}";
            const amount = "{{ number_format($contributor->promised_amount) }}";
            const message = `Asante ${contributorName}! Nimeahidi kuchangia TSh ${amount} kwa tukio la ${eventName}. Hongereni sana! 🎉🎊`;
            const encodedMessage = encodeURIComponent(message);
            window.open(`https://wa.me/?text=${encodedMessage}`, '_blank');
        }
        
        // Trigger confetti on load
        document.addEventListener('DOMContentLoaded', function() {
            createConfetti();
            
            // Play success sound (optional - uncomment if you have sound)
            // var audio = new Audio('/sounds/success.mp3');
            // audio.play().catch(e => console.log('Audio play failed'));
        });
        
        // Add floating hearts animation
        function createFloatingHeart() {
            const heart = document.createElement('div');
            heart.innerHTML = '❤️';
            heart.style.position = 'fixed';
            heart.style.left = Math.random() * 100 + '%';
            heart.style.bottom = '-20px';
            heart.style.fontSize = Math.random() * 20 + 15 + 'px';
            heart.style.opacity = Math.random() * 0.5 + 0.3;
            heart.style.animation = 'floatUp ' + (Math.random() * 3 + 2) + 's linear forwards';
            heart.style.zIndex = '999';
            heart.style.pointerEvents = 'none';
            document.body.appendChild(heart);
            
            setTimeout(() => {
                heart.remove();
            }, 5000);
        }
        
        // Add floating hearts animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes floatUp {
                to {
                    transform: translateY(-100vh);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Generate hearts periodically
        setInterval(createFloatingHeart, 2000);
    </script>
</body>
</html>