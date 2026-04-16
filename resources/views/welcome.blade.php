<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.addEventListener('scroll', () => {
            const nav = document.getElementById('lp-navbar');
            if (!nav) return;
            nav.classList.toggle('scrolled', window.scrollY > 20);
        });

        document.addEventListener('DOMContentLoaded', () => {
            const counters = document.querySelectorAll('[data-target]');
            const io = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (!e.isIntersecting) return;

                    const target = Number(e.target.dataset.target || 0);
                    let count = 0;
                    const step = Math.max(1, target / 60);

                    const t = setInterval(() => {
                        count = Math.min(count + step, target);
                        const suffix = e.target.dataset.suffix || '';
                        e.target.textContent = Math.round(count).toLocaleString() + suffix;
                        if (count >= target) clearInterval(t);
                    }, 25);

                    io.unobserve(e.target);
                });
            });

            counters.forEach(c => io.observe(c));
        });

        function openAuthModal(tab) {
            const modal = document.getElementById('lp-authModal');
            if (!modal) return;
            modal.classList.add('open');
            switchAuthTab(tab || 'register');
        }

        function closeAuthModal() {
            const modal = document.getElementById('lp-authModal');
            if (!modal) return;
            modal.classList.remove('open');
        }

        function switchAuthTab(tab) {
            const registerForm = document.getElementById('lp-register');
            const loginForm = document.getElementById('lp-login');
            const tabRegister = document.getElementById('lp-tab-register');
            const tabLogin = document.getElementById('lp-tab-login');

            if (!registerForm || !loginForm || !tabRegister || !tabLogin) return;

            registerForm.style.display = tab === 'register' ? 'block' : 'none';
            loginForm.style.display = tab === 'login' ? 'block' : 'none';

            tabRegister.classList.toggle('active', tab === 'register');
            tabLogin.classList.toggle('active', tab === 'login');
        }
    </script>
</head>

<body class="lp">
    <nav id="lp-navbar" class="lp-nav">
        <a href="#home" class="lp-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Plateful"
                style="height:26px; width:26px; object-fit:contain;" />
            <span>Plate<span>ful</span></span>
        </a>

        <ul class="lp-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        <div class="lp-cta">
            @auth
                <a href="{{ route('dashboard') }}" class="lp-btn-solid">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="lp-btn-ghost">Login</a>
                <a href="{{ route('register') }}" class="lp-btn-solid">Register</a>
            @endauth
        </div>

        <div class="lp-hamburger"
            onclick="(function(){
            const links = document.getElementById('lp-mobile-links');
            if (!links) return;
            const open = links.dataset.open === '1';
            links.dataset.open = open ? '0' : '1';
            links.style.display = open ? 'none' : 'flex';
        })()">
            <span></span><span></span><span></span>
        </div>
    </nav>

    <ul id="lp-mobile-links" data-open="0"
        style="display:none; position:fixed; top:68px; left:0; right:0; background:var(--bg); padding:1.5rem 5%; border-bottom:1px solid var(--neutral); z-index:49; gap:1rem; flex-direction:column;">
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>

    <section class="lp-hero" id="home">
        <div class="lp-blob"></div>
        <div class="lp-blob2"></div>

        <div class="lp-hero-content">
            <div class="lp-badge">🌱 Smart Food Management Platform</div>
            <p class="lp-tagline">Smart Food Waste Reduction & Mindful Consumption</p>
            <h1>Save Food, <span class="highlight">Save Lives</span> with Plateful</h1>
            <p>
                Plateful helps households reduce food waste through intelligent inventory tracking, expiry alerts,
                donation facilitation, and smart meal planning — all in one place.
            </p>
            <div class="lp-hero-cta">
                @auth
                    <a href="{{ route('dashboard') }}" class="lp-btn-primary">Open Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="lp-btn-primary">✨ Get Started</a>
                    <a href="#features" class="lp-btn-secondary">Explore Features →</a>
                @endauth
            </div>
        </div>

        <div style="flex:1"></div>
    </section>

    <section class="lp-section white">
        <div class="lp-container">
            <div class="lp-stats">
                <div>
                    <span class="lp-stat-num" data-target="5000">0</span>
                    <div class="lp-divider"></div>
                    <div class="lp-stat-label">Food Items Saved</div>
                </div>
                <div>
                    <span class="lp-stat-num" data-target="1200">0</span>
                    <div class="lp-divider"></div>
                    <div class="lp-stat-label">Active Users</div>
                </div>
                <div>
                    <span class="lp-stat-num" data-target="340">0</span>
                    <div class="lp-divider"></div>
                    <div class="lp-stat-label">Donations Made</div>
                </div>
                <div>
                    <span class="lp-stat-num" data-target="85" data-suffix="%">0</span>
                    <div class="lp-divider"></div>
                    <div class="lp-stat-label">Waste Reduced</div>
                </div>
            </div>
        </div>
    </section>

    <section class="lp-section" id="about">
        <div class="lp-container lp-grid-2">
            <div class="lp-grid-3" style="gap:16px; grid-template-columns: 1fr 1fr;">
                <div class="lp-card small primary" style="grid-row: span 2;">
                    <div style="font-size:1.8rem; margin-bottom:.6rem;">🍃</div>
                    <div class="lp-title">Our Mission</div>
                    <div class="lp-desc">Helping households tackle food waste through smart technology and community
                        sharing.</div>
                </div>
                <div class="lp-card small">
                    <div style="font-size:1.8rem; margin-bottom:.6rem;">📊</div>
                    <div class="lp-title">Analytics</div>
                    <div class="lp-desc">Visual reports on your food-saving progress.</div>
                </div>
                <div class="lp-card small accent">
                    <div style="font-size:1.8rem; margin-bottom:.6rem;">🤝</div>
                    <div class="lp-title">Community</div>
                    <div class="lp-desc">Donate surplus food to neighbors in need.</div>
                </div>
            </div>

            <div>
                <span class="lp-tag">About Us</span>
                <h2 class="lp-h2">A Smarter Way to Manage Your Food</h2>
                <p class="lp-desc">
                    Plateful is a web-based platform designed to help households reduce food waste through intelligent
                    inventory management, expiry date tracking, and donation facilitation.
                </p>
                <p class="lp-desc" style="margin-top:1rem;">
                    We believe that small daily actions — tracking what's in your fridge, planning meals wisely, and
                    donating
                    food before it expires — can create a massive collective impact.
                </p>
                <div style="margin-top:1.5rem;">
                    <a href="#features" class="lp-btn-primary">See How It Works</a>
                </div>
            </div>
        </div>
    </section>

    <section class="lp-section" id="features">
        <div class="lp-container">
            <div style="text-align:center; max-width:560px; margin:0 auto 60px;">
                <span class="lp-tag">Features</span>
                <h2 class="lp-h2">Everything You Need to Reduce Waste</h2>
                <p class="lp-desc" style="margin-top:.5rem;">Six powerful features designed around real household
                    needs.</p>
            </div>

            <div class="lp-grid-3">
                <div class="lp-card">
                    <div class="lp-icon">🔐</div>
                    <div class="lp-title">Register & Privacy</div>
                    <div class="lp-desc">Secure registration and privacy controls to manage your account and listings.
                    </div>
                </div>
                <div class="lp-card">
                    <div class="lp-icon blue">📦</div>
                    <div class="lp-title">Manage Food Inventory</div>
                    <div class="lp-desc">Log food items with expiry dates, categories, and storage details.</div>
                </div>
                <div class="lp-card">
                    <div class="lp-icon yellow">🔍</div>
                    <div class="lp-title">Browse Food Listings</div>
                    <div class="lp-desc">Discover available donations from nearby households and claim them easily.
                    </div>
                </div>
                <div class="lp-card">
                    <div class="lp-icon">🔔</div>
                    <div class="lp-title">Smart Notifications</div>
                    <div class="lp-desc">Get alerts for upcoming expiry dates, claimed donations, and reminders.</div>
                </div>
                <div class="lp-card">
                    <div class="lp-icon blue">📈</div>
                    <div class="lp-title">Food Analytics</div>
                    <div class="lp-desc">Track and visualize how much food you’ve saved and donated over time.</div>
                </div>
                <div class="lp-card">
                    <div class="lp-icon yellow">🥗</div>
                    <div class="lp-title">Meal Planning</div>
                    <div class="lp-desc">Plan weekly meals based on your inventory and reduce waste with suggestions.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="lp-footer" id="contact">
        <div class="lp-footer-grid">
            <div class="lp-footer-brand">
                <span class="logo" style="display:flex; align-items:center; gap:.6rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="Plateful"
                        style="height:28px; width:28px; object-fit:contain;" />
                    <span>Plateful</span>
                </span>
                <p style="font-size:.88rem; color:#a09089; line-height:1.7; margin-bottom:1.2rem;">
                    Smart Food Waste Reduction and Mindful Consumption — helping households waste less, share more, and
                    eat well.
                </p>
                <div class="lp-social">
                    <a class="lp-social-btn" href="#" title="Instagram">📸</a>
                    <a class="lp-social-btn" href="#" title="Twitter/X">🐦</a>
                    <a class="lp-social-btn" href="#" title="Facebook">📘</a>
                    <a class="lp-social-btn" href="#" title="TikTok">🎵</a>
                </div>
            </div>

            <div>
                <h4 style="color:var(--white); font-size:.9rem; font-weight:600; margin-bottom:1rem;">Navigation</h4>
                <a href="#home">Home</a><br>
                <a href="#about">About Us</a><br>
                <a href="#features">Features</a><br>
                <a href="#contact">Contact</a>
            </div>

            <div>
                <h4 style="color:var(--white); font-size:.9rem; font-weight:600; margin-bottom:1rem;">Quick Links</h4>
                @guest
                    <a href="{{ route('register') }}">Register</a><br>
                    <a href="{{ route('login') }}">Login</a><br>
                @endguest
                <a href="#contact">Contact</a>
            </div>

            <div>
                <h4 style="color:var(--white); font-size:.9rem; font-weight:600; margin-bottom:1rem;">Contact Us</h4>
                <div style="display:flex; gap:.6rem; margin-bottom:.7rem; align-items:flex-start;">
                    <span style="margin-top:1px">📧</span>
                    <span>hello@plateful.my</span>
                </div>
                <div style="display:flex; gap:.6rem; margin-bottom:.7rem; align-items:flex-start;">
                    <span style="margin-top:1px">📞</span>
                    <span>+60 12-345 6789</span>
                </div>
                <div style="display:flex; gap:.6rem; margin-bottom:.7rem; align-items:flex-start;">
                    <span style="margin-top:1px">📍</span>
                    <span>Kuala Lumpur, Malaysia</span>
                </div>
            </div>
        </div>

        <div class="lp-footer-bottom">
            <p style="font-size:.82rem; color:#7a6a63;">© {{ date('Y') }} Plateful. All rights reserved.</p>
            <div style="display:flex; gap:1.5rem; flex-wrap:wrap;">
                <a href="#contact" style="font-size:.82rem; color:#7a6a63;">Contact</a>
                @guest
                    <a href="{{ route('login') }}" style="font-size:.82rem; color:#7a6a63;">Login</a>
                    <a href="{{ route('register') }}" style="font-size:.82rem; color:#7a6a63;">Register</a>
                @endguest
            </div>
        </div>
    </footer>

    <div class="lp-modal-overlay" id="lp-authModal" onclick="if(event.target===this) closeAuthModal()">
        <div class="lp-modal" role="dialog" aria-modal="true" aria-label="Authentication">
            <button type="button" class="lp-modal-close" onclick="closeAuthModal()">✕</button>
            <h3
                style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif; font-size:1.5rem; margin-bottom:.4rem; color:var(--text);">
                Welcome to Plateful</h3>
            <p style="font-size:.9rem; color:var(--text-light); margin-bottom:1.6rem;">Create your account to start
                saving food.</p>

            <div class="lp-tabs">
                <button type="button" class="lp-tab active" id="lp-tab-register"
                    onclick="switchAuthTab('register')">Register</button>
                <button type="button" class="lp-tab" id="lp-tab-login"
                    onclick="switchAuthTab('login')">Login</button>
            </div>

            <div id="lp-register">
                <p style="font-size:.85rem; color:var(--text-light); margin-bottom:1rem;">Continue to the full register
                    page.</p>
                <a class="lp-btn-full" href="{{ route('register') }}">Create Account ✨</a>
            </div>

            <div id="lp-login" style="display:none">
                <p style="font-size:.85rem; color:var(--text-light); margin-bottom:1rem;">Continue to the full login
                    page.</p>
                <a class="lp-btn-full" href="{{ route('login') }}">Login →</a>
            </div>
        </div>
    </div>
</body>

</html>
