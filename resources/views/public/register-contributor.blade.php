<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sajili Mchango - {{ $event->event_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #FF6F00;
            --primary-dark: #E65100;
            --primary-light: #FFF3E0;
            --success: #10B981;
            --danger: #EF4444;
            --text-primary: #000000;
            --text-secondary: #1F2937;
            --text-muted: #4B5563;
            --bg-light: #F9FAFB;
            --border-color: #E5E7EB;
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
        
        .hero-section .event-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #FF6F00;
        }
        
        .hero-section .event-date {
            font-size: 1rem;
            margin-bottom: 30px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .hero-features {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 40px;
        }
        
        .hero-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            border-radius: 50px;
            backdrop-filter: blur(5px);
        }
        
        .hero-feature i {
            width: 25px;
            color: #FF6F00;
        }
        
        /* Right Column - Form Section */
        .form-section {
            width: 500px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 100vh;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        
        .form-header p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        
        .event-info-card {
            background: var(--bg-light);
            border-radius: var(--radius-md);
            padding: 16px 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .event-info-card h3 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }
        
        .event-info-card p {
            font-size: 0.7rem;
            color: var(--text-muted);
        }
        
        .event-info-card .event-badge {
            background: var(--primary-light);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 8px;
            display: block;
        }
        
        .form-group label .required {
            color: var(--danger);
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
        
        .input-with-icon input,
        .input-with-icon textarea {
            width: 100%;
            padding: 12px 14px 12px 42px;
            font-size: 0.85rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        
        .input-with-icon textarea {
            padding-top: 12px;
            padding-left: 42px;
            min-height: 80px;
            resize: vertical;
        }
        
        .input-with-icon input:focus,
        .input-with-icon textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
        }
        
        .quick-amounts {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }
        
        .quick-amount {
            padding: 6px 14px;
            font-size: 0.7rem;
            font-weight: 600;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .quick-amount:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }
        
        .btn-submit {
            width: 100%;
            padding: 14px;
            font-size: 0.85rem;
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
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .help-text {
            font-size: 0.6rem;
            color: var(--text-muted);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .error-alert {
            background: #FEF2F2;
            border-left: 3px solid var(--danger);
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error-alert i {
            color: var(--danger);
        }
        
        .error-alert span {
            font-size: 0.75rem;
            color: var(--danger);
        }
        
        .footer {
            text-align: center;
            padding-top: 20px;
            margin-top: 20px;
            font-size: 0.65rem;
            color: var(--text-muted);
            border-top: 1px solid var(--border-color);
        }
        
        /* Scrollbar */
        .form-section::-webkit-scrollbar {
            width: 6px;
        }
        
        .form-section::-webkit-scrollbar-track {
            background: var(--bg-light);
        }
        
        .form-section::-webkit-scrollbar-thumb {
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
                min-height: 400px;
            }
            
            .hero-section h1 {
                font-size: 1.8rem;
            }
            
            .hero-section .event-name {
                font-size: 1.2rem;
            }
            
            .form-section {
                width: 100%;
                max-width: 100%;
                padding: 30px 20px;
                max-height: none;
            }
            
            .hero-features {
                display: none;
            }
        }
        
        @media (max-width: 480px) {
            .form-section {
                padding: 24px 16px;
            }
            
            .quick-amounts {
                gap: 6px;
            }
            
            .quick-amount {
                padding: 4px 10px;
                font-size: 0.65rem;
            }
            
            .event-info-card {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }
        
        /* Animation */
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
        
        .hero-section, .form-section {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="two-column-layout">
        <!-- Left Column - Hero Section with Wedding Theme -->
        <div class="hero-section">
            <div class="hero-content">
                <div class="hero-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h1>Karibu Kwenye<br>Sherehe Yetu</h1>
                <div class="event-name">
                    <i class="fas fa-heart" style="color: #FF6F00; font-size: 1rem;"></i>
                    {{ $event->event_name }}
                    <i class="fas fa-heart" style="color: #FF6F00; font-size: 1rem;"></i>
                </div>
                <div class="event-date">
                    <i class="fas fa-calendar-alt"></i>
                    {{ \Carbon\Carbon::parse($event->event_date)->format('d F, Y') }}
                </div>
                <div class="hero-features">
                    <div class="hero-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Ahidi mchango wako kwa urahisi</span>
                    </div>
                    <div class="hero-feature">
                        <i class="fas fa-lock"></i>
                        <span>Taarifa zako zinamfikia mhusika</span>
                    </div>
                    <div class="hero-feature">
                        <i class="fas fa-clock"></i>
                        <span>Malipo kwa awamu yanaruhusiwa</span>
                    </div>
                    <div class="hero-feature">
                        <i class="fas fa-gift"></i>
                        <span>Shukrani zetu kwako kwa ushirikiano</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Form Section -->
        <div class="form-section">
            <div class="form-header">
                <h2>Sajili Mchango</h2>
                <p>Jaza taarifa zako ili kuahidi mchango</p>
            </div>
            
            <div class="event-info-card">
                <div>
                    <h3>{{ $event->event_name }}</h3>
                    <p><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</p>
                </div>
                <div class="event-badge">
                    <i class="fas fa-gift"></i> Karibu Sana
                </div>
            </div>
            
            @if($errors->any())
                <div class="error-alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif
            
            <form method="POST" action="{{ route('public.contributor.store', $token) }}" id="registrationForm">
                @csrf
                
                <div class="form-group">
                    <label>Jina Kamili <span class="required">*</span></label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Mf: Juma Omary">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Namba ya Simu <span class="required">*</span></label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone-alt"></i>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="0712345678">
                    </div>
                    <div class="help-text">
                        <i class="fas fa-info-circle"></i> Kwa taarifa za malipo na mawasiliano
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Barua Pepe <span class="optional">(hiari)</span></label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="juma@example.com">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Kiasi Unachotaka Kuahidi <span class="required">*</span></label>
                    <div class="quick-amounts">
                        <button type="button" class="quick-amount" onclick="setAmount(50000)">50,000</button>
                        <button type="button" class="quick-amount" onclick="setAmount(100000)">100,000</button>
                        <button type="button" class="quick-amount" onclick="setAmount(200000)">200,000</button>
                        <button type="button" class="quick-amount" onclick="setAmount(500000)">500,000</button>
                        <button type="button" class="quick-amount" onclick="setAmount(1000000)">1,000,000</button>
                    </div>
                    <div class="input-with-icon">
                        <i class="fas fa-money-bill-wave"></i>
                        <input type="number" name="promised_amount" id="promisedAmount" class="form-control" min="1000" step="1000" value="{{ old('promised_amount') }}" required placeholder="Weka kiasi">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Maelezo (hiari)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-pen"></i>
                        <textarea name="notes" rows="3" placeholder="Maelezo ya ziada...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i> Hifadhi na Ahidi Mchango
                </button>
            </form>
            
            <div class="footer">
                <p><i class="fas fa-heart" style="color: #FF6F00;"></i> CHANGIA SMART - Mfumo wa Kukusanya Michango <i class="fas fa-heart" style="color: #FF6F00;"></i></p>
            </div>
        </div>
    </div>
    
    <script>
        function setAmount(amount) {
            document.getElementById('promisedAmount').value = amount;
            // Visual feedback
            const input = document.getElementById('promisedAmount');
            input.style.borderColor = '#FF6F00';
            setTimeout(() => {
                input.style.borderColor = '';
            }, 500);
        }
        
        document.getElementById('registrationForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" style="width: 14px; height: 14px;"></span> Inahifadhi...';
        });
    </script>
</body>
</html>