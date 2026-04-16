<footer class="lp-footer" id="contact">
    <div class="lp-footer-grid">
        <div class="lp-footer-brand">
            <span class="logo" style="display:flex; align-items:center; gap:.6rem;">
                <img src="{{ asset('images/logo.png') }}" alt="Plateful" style="height:28px; width:28px; object-fit:contain;" />
                <span>Plateful</span>
            </span>
            <p style="font-size:.88rem; color:#a09089; line-height:1.7; margin-bottom:1.2rem;">
                Smart Food Waste Reduction and Mindful Consumption — helping households waste less, share more, and eat well.
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
            <a href="{{ route('dashboard') }}">Dashboard</a><br>
            <a href="{{ route('inventory.index') }}">Inventory</a><br>
            @if (Route::has('meal-plans.index'))
                <a href="{{ route('meal-plans.index') }}">Meal Plan</a><br>
            @endif
            @if (Route::has('donations.index'))
                <a href="{{ route('donations.index') }}">Donation</a><br>
            @endif
        </div>

        <div>
            <h4 style="color:var(--white); font-size:.9rem; font-weight:600; margin-bottom:1rem;">Quick Links</h4>
            @if (Route::has('recipes.index'))
                <a href="{{ route('recipes.index') }}">Recipes</a><br>
            @endif
            @if (Route::has('analytics.index'))
                <a href="{{ route('analytics.index') }}">Analytics</a><br>
            @endif
            <a href="{{ route('profile.edit') }}">Profile</a><br>
            @if (Route::has('landing.terms'))
                <a href="{{ route('landing.terms') }}">Terms</a><br>
            @endif
            @if (Route::has('landing.privacy'))
                <a href="{{ route('landing.privacy') }}">Privacy</a>
            @endif
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
            @if (Route::has('landing.terms'))
                <a href="{{ route('landing.terms') }}" style="font-size:.82rem; color:#7a6a63;">Terms</a>
            @endif
            @if (Route::has('landing.privacy'))
                <a href="{{ route('landing.privacy') }}" style="font-size:.82rem; color:#7a6a63;">Privacy</a>
            @endif
        </div>
    </div>
</footer>
