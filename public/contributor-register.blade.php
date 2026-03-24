<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sajili Mchango - Changia Smart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #FF6F00;
            --primary-dark: #E65100;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 450px;
            width: 90%;
            margin: 1rem;
        }
        
        .card-header {
            background: var(--primary);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 700;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            color: #374151;
        }
        
        .form-control {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,111,0,0.1);
            outline: none;
        }
        
        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
        }
        
        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .event-info {
            background: #f9fafb;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .event-info h5 {
            margin: 0;
            color: var(--primary);
            font-weight: 700;
        }
        
        .event-info p {
            margin: 0.5rem 0 0;
            font-size: 0.875rem;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="card-header">
            <i class="fas fa-hand-holding-heart fa-2x mb-2"></i>
            <h4>Sajili Mchango Wako</h4>
        </div>
        
        <div class="card-body">
            <div class="event-info">
                <h5>{{ $event->event_name }}</h5>
                <p><i class="far fa-calendar"></i> {{ $event->event_date->format('d M, Y') }}</p>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('contributor.public.store', $event) }}">
                @csrf
                <input type="hidden" name="phone" value="{{ $phone ?? '' }}">
                
                <div class="form-group">
                    <label>Jina Kamili <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="Mf: Juma Omary">
                </div>
                
                @if(!$phone)
                <div class="form-group">
                    <label>Namba ya Simu <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" class="form-control" required placeholder="0712 345 678">
                </div>
                @endif
                
                <div class="form-group">
                    <label>Kiasi Unachoahidi <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">TSh</span>
                        <input type="number" name="promised_amount" class="form-control" required min="1000" step="1000" placeholder="0">
                    </div>
                    <small class="text-muted">Angalau TSh 1,000</small>
                </div>
                
                <button type="submit" class="btn-submit mt-3">
                    <i class="fas fa-save me-2"></i> Sajili Mchango
                </button>
            </form>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>