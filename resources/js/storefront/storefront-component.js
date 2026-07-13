/**
 * Storefront — the single Alpine component that powers the JTC homepage.
 *
 * Registered as `x-data="storefront(@js([...]))"` on the page root.
 * All server data (products, categories, slides…) is passed in from Blade.
 *
 * The cart itself is server-side (App\Livewire\Website\CartManager, backed by
 * the carts/cart_items tables) — this component only tracks the header badge
 * count and the drawer's open/closed state. `cartCount` is seeded from the
 * `_cart_count` cookie on page load and kept live via the `cart-updated`
 * browser event CartManager dispatches after every add/remove/qty change.
 *
 * NOTE: Alpine is provided by Livewire (@livewireScripts). We do NOT import or
 * start our own Alpine here — instead this factory is registered on Livewire's
 * Alpine via the `alpine:init` event (see resources/js/storefront/app.js).
 */

// Fixed card + gap for the category carousel (180px card + 7px gap).
const CAT_STEP = 187;

export const storefront = (config = {}) => ({
    // ---- server data ----
    products: config.products || [],
    slides: config.slides || [],
    heroBanners: config.heroBanners || [],
    categories: config.categories || [],

    // ---- ui state ----
    cartCount: config.cartCount || 0,
    wishlist: {},
    heroIndex: 0,
    catIndex: 0,
    cartOpen: false,
    mmenuOpen: false,
    searchCatOpen: false,
    selectedCategory: null,
    searchModalOpen: false,
    searchModalCatOpen: false,
    authOpen: false,
    authMode: 'login',
    authForm: { name: '', email: '', phone: '', login: '', password: '', passwordConfirmation: '', remember: false },
    authError: '',
    authLoading: false,
    authSuccess: false,
    supportOpen: false,
    mobileFiltersOpen: false,
    user: config.user || null,
    toastMsg: 'Added to cart',
    toastShow: false,

    // ---- lifecycle ----
    init() {
        this._heroTimer = setInterval(() => this.heroGo(this.heroIndex + 1), 5500);
        this._catTimer = setInterval(() => this.catNext(), 3800);

        // ?auth=1 (set by the customer.auth middleware when a guest hits a
        // login-required page like /account or /orders) auto-opens the
        // login modal instead of the scaffolded /login page.
        if (new URLSearchParams(window.location.search).get('auth') === '1') {
            this.openAuthModal();
            const url = new URL(window.location.href);
            url.searchParams.delete('auth');
            window.history.replaceState({}, '', url);
        }

        // close overlays with Escape
        this._onKey = (e) => { if (e.key === 'Escape') this.closeAll(); };
        window.addEventListener('keydown', this._onKey);

        // CartManager (Livewire) dispatches this after every add/remove/qty change.
        this._onCartUpdated = (e) => {
            const payload = e.detail[0] ?? e.detail;
            this.cartCount = payload?.cartcount ?? 0;
        };
        window.addEventListener('cart-updated', this._onCartUpdated);
    },

    destroy() {
        clearInterval(this._heroTimer);
        clearInterval(this._catTimer);
        clearTimeout(this._toastTimer);
        window.removeEventListener('keydown', this._onKey);
        window.removeEventListener('cart-updated', this._onCartUpdated);
    },

    // ---- hero slider ----
    get slideCount() { return this.slides.length; },
    heroGo(n) {
        const t = this.slideCount;
        this.heroIndex = ((n % t) + t) % t;
    },
    heroPrev() { this.heroGo(this.heroIndex - 1); this._restartHero(); },
    heroNext() { this.heroGo(this.heroIndex + 1); this._restartHero(); },
    _restartHero() {
        clearInterval(this._heroTimer);
        this._heroTimer = setInterval(() => this.heroGo(this.heroIndex + 1), 5500);
    },

    // ---- category carousel ----
    // Renders the real category list once — no doubled/duplicated array.
    // catIndex wraps around the actual length via modulo, so "next" past
    // the last item jumps straight back to the first (a hard cut, not a
    // sliding illusion) instead of relying on a second copy of the list.
    get catItems() { return this.categories; },
    get catTransform() { return `translateX(-${this.catIndex * CAT_STEP}px)`; },
    get catTransition() { return 'transform 0.7s cubic-bezier(.4,0,.2,1)'; },
    catNext() {
        const len = this.categories.length;
        if (!len) return;
        this.catIndex = (this.catIndex + 1) % len;
    },
    catPrev() {
        const len = this.categories.length;
        if (!len) return;
        this.catIndex = (this.catIndex - 1 + len) % len;
        this._restartCat();
    },
    catNextManual() { this.catNext(); this._restartCat(); },
    _restartCat() {
        clearInterval(this._catTimer);
        this._catTimer = setInterval(() => this.catNext(), 3800);
    },
    selectCategory(name) {
        this.selectedCategory = name;
        this.searchCatOpen = false;
        this.showToast('Browsing ' + name);
    },

    // ---- wishlist ----
    toggleWish(id) {
        const was = !!this.wishlist[id];
        this.wishlist[id] = !was;
        if (!was) this.showToast('Saved to wishlist');
    },
    isWished(id) { return !!this.wishlist[id]; },

    // ---- auth ----
    openAuthModal() { this.closeAll(); this.authOpen = true; this.authMode = 'login'; this.authError = ''; this.authSuccess = false; },
    toggleAuthMode() {
        this.authMode = this.authMode === 'login' ? 'signup' : 'login';
        this.authError = '';
        this.authSuccess = false;
    },
    async submitAuth() {
        this.authError = '';
        this.authLoading = true;

        const isSignup = this.authMode === 'signup';
        const url = isSignup ? '/account/register' : '/account/login';
        const body = isSignup
            ? {
                name: this.authForm.name,
                email: this.authForm.email,
                phone: this.authForm.phone,
                password: this.authForm.password,
                password_confirmation: this.authForm.passwordConfirmation,
            }
            : {
                login: this.authForm.login,
                password: this.authForm.password,
                remember: this.authForm.remember,
            };

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(body),
            });
            const data = await res.json();

            if (!res.ok) {
                this.authError = data.message || 'Something went wrong. Please try again.';
                return;
            }

            this.authForm = { name: '', email: '', phone: '', login: '', password: '', passwordConfirmation: '', remember: false };

            if (isSignup) {
                this.authSuccess = true;
            } else {
                this.user = data.user;
                this.authSuccess = true;
                this.showToast('Signed in successfully');
            }
        } catch (e) {
            this.authError = 'Network error. Please try again.';
        } finally {
            this.authLoading = false;
        }
    },
    async logoutUser() {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            await fetch('/account/logout', {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
            });
        } catch (e) {}
        this.user = null;
        this.showToast('Signed out successfully');
    },
    get authTitle() { return this.authMode === 'login' ? 'Welcome back' : 'Create your account'; },
    get authSubtitle() {
        return this.authMode === 'login'
            ? 'Sign in to track orders and check out faster.'
            : 'Join Juwel Trade Corporation to shop and track orders.';
    },
    get authSubmitText() { return this.authMode === 'login' ? 'Sign in' : 'Create account'; },
    get authSwitchPrompt() { return this.authMode === 'login' ? "Don't have an account?" : 'Already have an account?'; },
    get authSwitchAction() { return this.authMode === 'login' ? 'Sign up' : 'Sign in'; },
    get authSuccessTitle() { return this.authMode === 'login' ? 'Login successful!' : 'Account created!'; },
    get authSuccessMessage() {
        return this.authMode === 'login'
            ? 'You have logged in successfully.'
            : 'Your account has been created successfully. Please log in to continue.';
    },

    // ---- overlays ----
    openCart() { this.closeAll(); this.cartOpen = true; },
    openMenu() { this.closeAll(); this.mmenuOpen = true; },
    openSupport() { this.closeAll(); this.supportOpen = true; },
    openSearchModal() { this.closeAll(); this.searchModalOpen = true; },
    closeAll() {
        this.cartOpen = false;
        this.mmenuOpen = false;
        this.authOpen = false;
        this.supportOpen = false;
        this.searchCatOpen = false;
        this.searchModalOpen = false;
        this.searchModalCatOpen = false;
        this.mobileFiltersOpen = false;
    },

    // ---- toast ----
    showToast(msg) {
        clearTimeout(this._toastTimer);
        this.toastMsg = msg;
        this.toastShow = true;
        this._toastTimer = setTimeout(() => { this.toastShow = false; }, 2200);
    },
});
