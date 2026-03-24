<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>Changia Smart · Simulia Michango kwa Urahisi</title>
  <!-- Bootstrap 5.3 + icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* ===== PROFESSIONAL THEME – Fully Responsive ===== */
    :root {
      --primary: #FF6F00;
      --primary-dark: #e65100;
      --primary-light: #FF9800;
      --accent: #FFC107;
      --dark: #111;
      --light-bg: #f8f9fa;
      --gray-700: #495057;
      --border-light: #dee2e6;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, system-ui, sans-serif;
      color: #212529;
      line-height: 1.5;
      overflow-x: hidden;
      background: white;
    }

    /* Typography - Responsive */
    h1 {
      font-size: clamp(1.8rem, 5vw, 3.2rem);
      font-weight: 700;
      line-height: 1.2;
    }

    h2 {
      font-size: clamp(1.5rem, 4vw, 2.4rem);
      font-weight: 700;
    }

    h5 {
      font-size: clamp(1rem, 3vw, 1.25rem);
    }

    p {
      font-size: clamp(0.875rem, 2.5vw, 1rem);
    }

    /* Navbar */
    .navbar {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(4px);
      box-shadow: 0 2px 15px rgba(0,0,0,0.04);
      padding: 0.7rem 0;
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .navbar-brand {
      font-weight: 800;
      font-size: clamp(1.2rem, 4vw, 1.7rem);
      letter-spacing: -0.5px;
      color: var(--primary) !important;
    }

    .navbar-brand i {
      font-size: clamp(1rem, 3vw, 1.3rem);
    }

    .nav-link {
      font-weight: 500;
      color: #2b2b2b !important;
      margin: 0 0.2rem;
      transition: color 0.2s;
      font-size: clamp(0.8rem, 2.5vw, 0.95rem);
    }

    .nav-link:hover {
      color: var(--primary) !important;
    }

    /* Buttons - Responsive */
    .btn-main {
      background-color: var(--primary);
      color: white;
      border: none;
      font-weight: 600;
      padding: clamp(0.4rem, 2vw, 0.6rem) clamp(1rem, 3vw, 1.5rem);
      transition: all 0.15s;
      box-shadow: 0 2px 5px rgba(255,111,0,0.2);
      font-size: clamp(0.75rem, 2.5vw, 0.9rem);
      white-space: nowrap;
    }

    .btn-main:hover {
      background-color: var(--primary-dark);
      transform: translateY(-1px);
      box-shadow: 0 4px 10px rgba(255,111,0,0.3);
    }

    .btn-accent {
      background-color: var(--accent);
      color: #212529;
      border: none;
      font-weight: 600;
      padding: clamp(0.4rem, 2vw, 0.6rem) clamp(1rem, 3vw, 1.5rem);
      transition: background 0.15s;
      font-size: clamp(0.75rem, 2.5vw, 0.9rem);
      white-space: nowrap;
    }

    .btn-accent:hover {
      background-color: #e0a800;
      color: #000;
    }

    .btn-outline-dark-sm {
      border: 1px solid #ced4da;
      background: white;
      font-weight: 500;
      padding: clamp(0.3rem, 2vw, 0.4rem) clamp(0.8rem, 2.5vw, 1rem);
      font-size: clamp(0.7rem, 2vw, 0.85rem);
      border-radius: 50px;
      white-space: nowrap;
    }

    .btn-outline-dark-sm:hover {
      background: #f1f3f5;
      border-color: #adb5bd;
    }

    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, rgba(0,0,0,0.75), rgba(0,0,0,0.55)),
                  url('https://images.unsplash.com/photo-1521334884684-d80222895322?q=80&w=2070&auto=format&fit=crop') center/cover no-repeat;
      padding: clamp(60px, 12vw, 140px) clamp(15px, 5vw, 20px);
      color: white;
      text-align: center;
    }

    .hero h1 {
      max-width: 900px;
      margin-left: auto;
      margin-right: auto;
    }

    .hero p {
      font-size: clamp(0.9rem, 3vw, 1.3rem);
      max-width: 700px;
      margin: 1rem auto;
      opacity: 0.9;
    }

    /* Section Styling */
    .section {
      padding: clamp(40px, 8vw, 90px) clamp(15px, 5vw, 20px);
    }

    .section-title {
      text-align: center;
      font-weight: 700;
      margin-bottom: clamp(1.5rem, 6vw, 3.5rem);
      letter-spacing: -0.02em;
      color: #1e1e1e;
      position: relative;
    }

    .section-title::after {
      content: '';
      display: block;
      width: clamp(50px, 10vw, 70px);
      height: 4px;
      background: var(--primary);
      margin: 0.8rem auto 0;
      border-radius: 4px;
    }

    /* Cards */
    .card-box {
      background: white;
      padding: clamp(1rem, 4vw, 2rem) clamp(0.8rem, 3vw, 1.5rem);
      border-radius: 20px;
      box-shadow: 0 10px 30px -8px rgba(0,0,0,0.06);
      text-align: center;
      height: 100%;
      transition: transform 0.2s, box-shadow 0.2s;
      border: 1px solid rgba(0,0,0,0.02);
    }

    .card-box:hover {
      transform: translateY(-6px);
      box-shadow: 0 25px 40px -12px rgba(255,111,0,0.15);
    }

    .card-box i {
      font-size: clamp(1.5rem, 5vw, 2rem);
      margin-bottom: 0.8rem;
    }

    .card-box h5 {
      color: var(--primary);
      font-weight: 700;
      margin-bottom: 0.8rem;
    }

    .card-box p {
      color: #5e6e82;
      font-size: clamp(0.75rem, 2.5vw, 0.9rem);
    }

    /* Steps */
    .step-item {
      background: white;
      border-radius: 24px;
      padding: clamp(1.2rem, 4vw, 2rem) clamp(0.8rem, 3vw, 1rem);
      box-shadow: 0 8px 20px rgba(0,0,0,0.02);
      height: 100%;
      border: 1px solid #f1f3f5;
      text-align: center;
    }

    .step-number {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: clamp(40px, 10vw, 48px);
      height: clamp(40px, 10vw, 48px);
      background: var(--primary);
      color: white;
      border-radius: 50%;
      font-weight: 700;
      font-size: clamp(1.2rem, 4vw, 1.6rem);
      margin-bottom: 1rem;
    }

    .step-item h5 {
      font-weight: 700;
      margin-bottom: 0.8rem;
    }

    /* Event Boxes */
    .event-box {
      background: white;
      padding: clamp(0.8rem, 3vw, 1.2rem) clamp(0.5rem, 2vw, 1rem);
      border-left: 5px solid var(--accent);
      border-radius: 16px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.02);
      font-weight: 600;
      font-size: clamp(0.8rem, 2.5vw, 1.1rem);
      color: #2c3e50;
      text-align: center;
      transition: all 0.2s;
      border: 1px solid #f0f0f0;
    }

    .event-box:hover {
      border-left-width: 8px;
      border-left-color: var(--primary);
      background: #fff9f0;
      transform: translateX(5px);
    }

    /* About Section */
    .about-text {
      max-width: 800px;
      margin: 0 auto;
      background: white;
      padding: clamp(1.5rem, 5vw, 2.5rem) clamp(1.2rem, 4vw, 3rem);
      border-radius: 32px;
      box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
      font-size: clamp(0.9rem, 2.5vw, 1.1rem);
      color: #2b2b2b;
      border: 1px solid rgba(255,111,0,0.1);
      text-align: center;
    }

    /* CTA Section */
    .cta {
      background: linear-gradient(145deg, var(--primary), #f57c00);
      color: white;
      text-align: center;
      padding: clamp(40px, 8vw, 80px) clamp(15px, 5vw, 20px);
    }

    .cta h2 {
      font-size: clamp(1.5rem, 5vw, 2.6rem);
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .cta p {
      font-size: clamp(0.9rem, 3vw, 1.1rem);
    }

    .btn-light-custom {
      background: white;
      color: var(--primary);
      border: none;
      padding: clamp(0.6rem, 2.5vw, 0.9rem) clamp(1.5rem, 5vw, 2.8rem);
      font-weight: 600;
      border-radius: 50px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      transition: transform 0.15s;
      font-size: clamp(0.8rem, 2.5vw, 1rem);
      text-decoration: none;
      display: inline-block;
    }

    .btn-light-custom:hover {
      background: #fff;
      transform: scale(1.03);
      color: var(--primary-dark);
    }

    /* Footer */
    footer {
      background: #151515;
      color: #adb5bd;
      padding: clamp(2rem, 6vw, 4rem) clamp(1rem, 4vw, 2rem) clamp(1rem, 3vw, 2rem);
    }

    footer h6 {
      color: white;
      font-weight: 600;
      margin-bottom: 1.2rem;
      font-size: clamp(0.9rem, 3vw, 1.1rem);
    }

    footer a {
      color: #adb5bd;
      text-decoration: none;
      transition: color 0.2s;
      font-size: clamp(0.75rem, 2.5vw, 0.85rem);
    }

    footer a:hover {
      color: var(--primary);
    }

    footer .social-links a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
      transition: all 0.2s;
      margin-right: 8px;
    }

    footer .social-links a:hover {
      background: var(--primary);
      transform: translateY(-2px);
    }

    footer hr {
      background-color: #2c2c2c;
      opacity: 0.5;
      margin: 1.5rem 0;
    }

    /* Contact Info Cards */
    .contact-card {
      background: rgba(255,255,255,0.05);
      border-radius: 12px;
      padding: 0.8rem;
      margin-bottom: 0.8rem;
      transition: all 0.2s;
    }

    .contact-card:hover {
      background: rgba(255,111,0,0.1);
      transform: translateX(5px);
    }

    .contact-card i {
      color: var(--primary);
      width: 28px;
      font-size: 1rem;
    }

    /* Responsive Grid */
    .row-custom {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    @media (max-width: 768px) {
      .navbar-collapse {
        background: white;
        padding: 1rem;
        border-radius: 12px;
        margin-top: 0.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      }
      
      .navbar-nav {
        gap: 0.5rem;
      }
      
      .hero {
        min-height: 60vh;
        display: flex;
        align-items: center;
      }
      
      .event-box {
        margin-bottom: 0.5rem;
      }
      
      .about-text {
        margin: 0 0.5rem;
      }
    }

    @media (max-width: 576px) {
      .row-custom {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
      
      .btn-main, .btn-accent {
        width: 100%;
        text-align: center;
      }
      
      .d-flex.gap-3 {
        flex-direction: column;
        gap: 0.8rem !important;
      }
      
      .d-flex.gap-3 a {
        width: 100%;
      }
    }

    /* Smooth Scroll */
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand" href="#">
      <i class="fas fa-hand-holding-heart me-2"></i>CHANGIA SMART
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-2 gap-lg-0">
        <li class="nav-item"><a class="nav-link" href="#huduma">Huduma</a></li>
        <li class="nav-item"><a class="nav-link" href="#jinsi">Jinsi Inavyofanya Kazi</a></li>
        <li class="nav-item"><a class="nav-link" href="#matukio">Matukio</a></li>
        <li class="nav-item"><a class="nav-link" href="#kuhusu">Tufahamu</a></li>
        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
          <div class="d-flex gap-2 flex-wrap flex-lg-nowrap">
            <a href="/login" class="btn btn-outline-dark-sm rounded-pill"><i class="fas fa-sign-in-alt me-1"></i>Ingia</a>
            <a href="/register" class="btn btn-main rounded-pill"><i class="fas fa-calendar-plus me-1"></i>Sajili Tukio</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ========== HERO ========== -->
<section class="hero">
  <div class="container">
    <h1>Simamia Michango ya Matukio <br>kwa Urahisi na Uwazi</h1>
    <p>Mfumo wa kisasa unaorahisisha ukusanyaji, ufuatiliaji na uwazi wa michango — wote kwa mkono mmoja.</p>
    <div class="mt-4 d-flex gap-3 justify-content-center flex-wrap">
      <a href="/register" class="btn btn-main rounded-pill"><i class="fas fa-rocket me-2"></i>Anza Sasa</a>
      <a href="#huduma" class="btn btn-accent rounded-pill"><i class="fas fa-compass me-2"></i>Angalia Huduma</a>
    </div>
  </div>
</section>

<!-- ========== HUDUMA ZETU ========== -->
<section class="section" id="huduma">
  <div class="container">
    <h2 class="section-title">Huduma Zetu</h2>
    <div class="row-custom">
      <div class="card-box"><i class="fas fa-eye" style="color:#FF6F00;"></i><h5>Uwazi kamili</h5><p>Fuatilia kila mchango kwa wakati halisi, hakuna mshiko.</p></div>
      <div class="card-box"><i class="fas fa-bell" style="color:#FF6F00;"></i><h5>Vikumbusho</h5><p>SMS na Email za kiotomatiki kuwakumbusha wachangiaji.</p></div>
      <div class="card-box"><i class="fas fa-shield-alt" style="color:#FF6F00;"></i><h5>Usalama</h5><p>Taarifa zako zimesimbwa kwa njia ya kisasa.</p></div>
      <div class="card-box"><i class="fas fa-chart-pie" style="color:#FF6F00;"></i><h5>Ripoti mahiri</h5><p>Pata taarifa kamili za michango kwa uchambuzi wa papo hapo.</p></div>
    </div>
  </div>
</section>

<!-- ========== JINSI INAVYOFANYA KAZI ========== -->
<section class="section bg-light" id="jinsi">
  <div class="container">
    <h2 class="section-title">Jinsi Inavyofanya Kazi</h2>
    <div class="row-custom">
      <div class="step-item"><div class="step-number">1</div><h5>Unda Tukio</h5><p class="text-secondary">Jaza taarifa za tukio lako, weka lengo na tarehe.</p></div>
      <div class="step-item"><div class="step-number">2</div><h5>Tuma Mialiko</h5><p class="text-secondary">Washirikishe wachangiaji kwa link ya kipekee au QR.</p></div>
      <div class="step-item"><div class="step-number">3</div><h5>Fuatilia & Furahi</h5><p class="text-secondary">Angalia maendeleo ya michango na uwashukuru washiriki.</p></div>
    </div>
  </div>
</section>

<!-- ========== MATUKIO YANAYOWEZA KUSIMAMIWA ========== -->
<section class="section" id="matukio">
  <div class="container">
    <h2 class="section-title">Matukio Yoyote, Tuko Huku</h2>
    <div class="row g-3">
      <div class="col-6 col-md-3"><div class="event-box"><i class="fas fa-heart me-2" style="color:#FF6F00;"></i> Harusi</div></div>
      <div class="col-6 col-md-3"><div class="event-box"><i class="fas fa-plane me-2" style="color:#FF6F00;"></i> Send‑off</div></div>
      <div class="col-6 col-md-3"><div class="event-box"><i class="fas fa-birthday-cake me-2" style="color:#FF6F00;"></i> Siku ya kuzaliwa</div></div>
      <div class="col-6 col-md-3"><div class="event-box"><i class="fas fa-graduation-cap me-2" style="color:#FF6F00;"></i> Graduation</div></div>
      <div class="col-6 col-md-3"><div class="event-box"><i class="fas fa-utensils me-2" style="color:#FF6F00;"></i> Kitchen Party</div></div>
      <div class="col-6 col-md-3"><div class="event-box"><i class="fas fa-baby me-2" style="color:#FF6F00;"></i> Baby Shower</div></div>
      <div class="col-6 col-md-3"><div class="event-box"><i class="fas fa-hand-holding-usd me-2" style="color:#FF6F00;"></i> Harambee</div></div>
      <div class="col-6 col-md-3"><div class="event-box"><i class="fas fa-glass-cheers me-2" style="color:#FF6F00;"></i> Sherehe nyingine</div></div>
    </div>
  </div>
</section>

<!-- ========== TUFAMU (about) ========== -->
<section class="section bg-light" id="kuhusu">
  <div class="container">
    <h2 class="section-title">Tufahamu</h2>
    <div class="about-text">
      <i class="fas fa-quote-right fa-2x mb-3" style="color: #FF6F00; opacity: 0.4;"></i>
      <p class="mb-3"><strong>Changia Smart</strong> ni mfumo wa kidijitali unaobadilisha jinsi michango ya matukio inavyosimamiwa Tanzania. Tunaleta <strong>uwazi, urahisi na usalama</strong> katika kila harusi, send‑off, au sherehe yoyote.</p>
      <p class="mb-0">Tangu 2022, tumewasaidia zaidi ya wachangiaji 5,000 kufurahia matukio bila wasiwasi wa fedha.</p>
    </div>
  </div>
</section>

<!-- ========== CTA ========== -->
<section class="cta">
  <div class="container">
    <h2>Anza Kutumia Changia Smart Leo</h2>
    <p class="lead mt-2">Unda tukio lako kwa dakika chache — bure kabisa.</p>
    <a href="/register" class="btn-light-custom mt-3"><i class="fas fa-calendar-check me-2"></i>Sajili Tukio</a>
  </div>
</section>

<!-- ========== FOOTER with Contact Info ========== -->
<footer>
  <div class="container">
    <div class="row gy-4">
      <div class="col-md-4">
        <h6><i class="fas fa-hand-holding-heart me-2" style="color: #FF6F00;"></i>Changia Smart</h6>
        <p>Mfumo wa kisasa wa michango ya matukio – uwazi, imani na teknolojia.</p>
        <div class="social-links d-flex gap-2 mt-3">
          <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="https://twitter.com" target="_blank"><i class="fab fa-x-twitter"></i></a>
          <a href="https://instagram.com/firefox_designer" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
      </div>

      <div class="col-md-2">
        <h6>Ramani</h6>
        <ul class="list-unstyled">
          <li><a href="#huduma">Huduma</a></li>
          <li><a href="#jinsi">Maelekezo</a></li>
          <li><a href="#matukio">Matukio</a></li>
          <li><a href="#kuhusu">Kuhusu</a></li>
        </ul>
      </div>

      <div class="col-md-3">
        <h6><i class="fas fa-address-card me-2" style="color:#FF6F00;"></i>Wasiliana Nasi</h6>
        <div class="contact-card">
          <i class="fas fa-envelope me-2"></i> roberttaliguli@gmail.com
        </div>
        <div class="contact-card">
          <i class="fas fa-phone-alt me-2"></i> +255 614 356 8030
        </div>
        <div class="contact-card">
          <i class="fab fa-instagram me-2"></i> @firefox_designer
        </div>
        <div class="contact-card">
          <i class="fas fa-map-marker-alt me-2"></i> Dar es Salaam, Tanzania
        </div>
      </div>

      <div class="col-md-3">
        <h6>Nyaraka</h6>
        <ul class="list-unstyled">
          <li><a href="#">Sheria na masharti</a></li>
          <li><a href="#">Sera ya faragha</a></li>
          <li><a href="#">Maswali ya mara kwa mara</a></li>
          <li><a href="#">Usaidizi</a></li>
        </ul>
      </div>
    </div>

    <hr>
    <div class="text-center small">
      © 2026 Changia Smart – Haki zote zimehifadhiwa. | Imeundwa kwa urahisi Tanzania
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href === "#" || href === "") return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // Close mobile menu when clicking a link
  const navbarCollapse = document.getElementById('navbarMain');
  const navLinks = document.querySelectorAll('.nav-link');
  
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth < 992) {
        const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
        if (bsCollapse) bsCollapse.hide();
      }
    });
  });
</script>
</body>
</html>