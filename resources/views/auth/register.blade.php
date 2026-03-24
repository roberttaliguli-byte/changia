<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jisajili - Changia Smart</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --primary: #FF6F00;
            --primary-dark: #e65100;
            --accent: #FFC107;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-600: #475569;
            --gray-700: #334155;
        }

        body {
            background: var(--gray-100);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .auth-wrapper {
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border: 1px solid var(--gray-200);
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand h2 {
            font-weight: 800;
            color: var(--primary);
            font-size: 1.8rem;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .brand p {
            color: var(--gray-600);
            font-size: 0.9rem;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-600);
            z-index: 10;
            font-size: 1rem;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: white;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,111,0,0.1);
            outline: none;
        }

        .form-control-custom::placeholder {
            color: var(--gray-600);
            font-size: 0.9rem;
        }

        .btn-main {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.2s;
            margin-top: 20px;
        }

        .btn-main:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .alert-custom {
            background: #fee;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #c00;
            font-size: 0.9rem;
            border-left: 3px solid #f00;
        }

        .alert-custom i {
            margin-right: 8px;
        }

        .auth-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-200);
        }

        .auth-footer p {
            color: var(--gray-600);
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .auth-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .home-link {
            text-align: center;
            margin-bottom: 20px;
        }

        .home-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            background: white;
            padding: 8px 20px;
            border-radius: 50px;
            transition: all 0.2s;
            display: inline-block;
            border: 1px solid var(--gray-200);
            font-size: 0.9rem;
        }

        .home-link a:hover {
            background: var(--gray-100);
            transform: translateY(-2px);
        }

        .form-check-input {
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            color: var(--gray-600);
            cursor: pointer;
            font-size: 0.9rem;
        }

        .form-check-label a {
            color: var(--primary);
            text-decoration: none;
        }

        .form-check-label a:hover {
            text-decoration: underline;
        }

        .password-strength {
            font-size: 0.75rem;
            margin-top: -12px;
            margin-bottom: 15px;
        }

        .strength-bar {
            height: 3px;
            background: var(--gray-200);
            border-radius: 3px;
            margin: 8px 0 5px;
            overflow: hidden;
        }

        .strength-bar-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-card {
                padding: 30px 25px;
            }
            
            .brand h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <!-- Home Link -->
        <div class="home-link">
            <a href="/"><i class="fas fa-home me-2"></i>Nyumbani</a>
        </div>

        <!-- Auth Card -->
        <div class="auth-card">
            <div class="brand">
                <h2>CHANGIA SMART</h2>
                <p>Unda akaunti mpya</p>
            </div>

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="alert-custom">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Full Name -->
                <div class="input-group-custom">
                    <i class="fas fa-user"></i>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="form-control-custom" 
                           placeholder="Jina kamili"
                           required>
                </div>

                <!-- Email (Optional) -->
                <div class="input-group-custom">
                    <i class="fas fa-envelope"></i>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="form-control-custom" 
                           placeholder="Barua pepe (si lazima)">
                </div>

                <!-- Phone Number -->
                <div class="input-group-custom">
                    <i class="fas fa-phone-alt"></i>
                    <input type="tel" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           class="form-control-custom" 
                           placeholder="Namba ya simu"
                           required>
                </div>

                <!-- Password -->
                <div class="input-group-custom">
                    <i class="fas fa-lock"></i>
                    <input type="password" 
                           name="password"
                           id="password"
                           class="form-control-custom" 
                           placeholder="Nywila (angalau herufi 5)"
                           required>
                </div>

                <!-- Password Strength Indicator -->
                <div class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-bar-fill" id="strengthBar"></div>
                    </div>
                    <span id="strengthText" class="text-secondary">Weka nywila</span>
                </div>

                <!-- Confirm Password -->
                <div class="input-group-custom">
                    <i class="fas fa-lock"></i>
                    <input type="password" 
                           name="password_confirmation"
                           class="form-control-custom" 
                           placeholder="Rudia nywila"
                           required>
                </div>

                <!-- Terms and Conditions -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label" for="terms">
                        Nakubali <a href="/terms" target="_blank">Sheria na Masharti</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-main">
                    <i class="fas fa-user-plus me-2"></i>Jisajili
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer">
                <p>Tayari una akaunti? <a href="{{ route('login') }}">Ingia hapa</a></p>
            </div>
        </div>
    </div>

    <!-- Password Strength Script -->
    <script>
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            
            if (password.length >= 5) strength += 25;
            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/) && password.match(/[^a-zA-Z0-9]/)) strength += 25;
            
            strengthBar.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthBar.style.background = '#dc3545';
                strengthText.textContent = 'Nywila dhaifu';
            } else if (strength <= 50) {
                strengthBar.style.background = '#ffc107';
                strengthText.textContent = 'Nywila wastani';
            } else if (strength <= 75) {
                strengthBar.style.background = '#17a2b8';
                strengthText.textContent = 'Nywila nzuri';
            } else {
                strengthBar.style.background = '#28a745';
                strengthText.textContent = 'Nywila imara';
            }
        });
    </script>
</body>
</html>