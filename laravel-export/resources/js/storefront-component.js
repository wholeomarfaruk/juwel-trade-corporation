import Alpine from 'alpinejs';

/**
 * Storefront — the single Alpine component that powers the JTC homepage.
 *
 * Registered as `x-data="storefront(@js([...]))"` on the page root.
 * All server data (products, categories, slides…) is passed in from Blade;
 * the cart is persisted to localStorage.
 */

const money = (n) => '৳' + Number(n).toLocaleString('en-US');
const CART_KEY = 'jtc_cart_v1';

// Fixed card + gap for the category carousel (180px card + 7px gap).
const CAT_STEP = 187;

Alpine.data('storefront', (config = {}) => ({
    // ---- server data ----
    products: config.products || [],
    slides: config.slides || [],
    heroBanners: config.heroBanners || [],
    categories: config.categories || [],

    // ---- ui state ----
    cart: [],
    wishlist: {},
    heroIndex: 0,
    catIndex: 0,
    catNoTransition: false,
    cartOpen: false,
    mmenuOpen: false,
    searchCatOpen: false,
    selectedCategory: null,
    authOpen: false,
    authMode: 'login',
    supportOpen: false,
    user: null,
    toastMsg: 'Added to cart',
    toastShow: false,

    // ---- lifecycle ----
    init() {
        try { this.cart = JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch (e) { this.cart = []; }

        this._heroTimer = setInterval(() => this.heroGo(this.heroIndex + 1), 5500);
        this._catTimer = setInterval(() => this.catNext(), 3800);

        // close overlays with Escape
        this._onKey = (e) => { if (e.key === 'Escape') this.closeAll(); };
        window.addEventListener('keydown', this._onKey);

        this.$watch('cart', () => {
            try { localStorage.setItem(CART_KEY, JSON.stringify(this.cart)); } catch (e) {}
        });
    },

    destroy() {
        clearInterval(this._heroTimer);
        clearInterval(this._catTimer);
        clearTimeout(this._catSnap);
        clearTimeout(this._toastTimer);
        window.removeEventListener('keydown', this._onKey);
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

    // ---- category carousel (seamless loop over a doubled list) ----
    get catItems() { return [...this.categories, ...this.categories]; },
    get catTransform() { return `translateX(-${this.catIndex * CAT_STEP}px)`; },
    get catTransition() { return this.catNoTransition ? 'none' : 'transform 0.7s cubic-bezier(.4,0,.2,1)'; },
    catNext() {
        const len = this.categories.length;
        this.catNoTransition = false;
        this.catIndex += 1;
        clearTimeout(this._catSnap);
        this._catSnap = setTimeout(() => {
            if (this.catIndex >= len) {
                this.catNoTransition = true;
                this.catIndex -= len;
                requestAnimationFrame(() => requestAnimationFrame(() => { this.catNoTransition = false; }));
            }
        }, 720);
    },
    catPrev() {
        const len = this.categories.length;
        if (this.catIndex === 0) {
            this.catNoTransition = true;
            this.catIndex = len;
            requestAnimationFrame(() => requestAnimationFrame(() => {
                this.catNoTransition = false;
                this.catIndex = len - 1;
            }));
        } else {
            this.catNoTransition = false;
            this.catIndex -= 1;
        }
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

    // ---- cart ----
    findProduct(id) { return this.products.find((p) => p.id === id); },
    addToCart(id) {
        const p = this.findProduct(id);
        if (!p) return;
        const line = this.cart.find((l) => l.id === id);
        if (line) {
            line.qty += 1;
        } else {
            this.cart.push({ id: p.id, name: p.name, price: p.price, image: p.image, sku: p.sku, qty: 1 });
        }
        this.cartOpen = true;
        this.showToast('Added to cart');
    },
    setQty(id, delta) {
        const line = this.cart.find((l) => l.id === id);
        if (!line) return;
        line.qty += delta;
        if (line.qty <= 0) this.removeLine(id);
    },
    removeLine(id) { this.cart = this.cart.filter((l) => l.id !== id); },
    lineTotal(l) { return money(l.price * l.qty); },
    linePrice(l) { return money(l.price); },
    get cartCount() { return this.cart.reduce((n, l) => n + l.qty, 0); },
    get cartEmpty() { return this.cart.length === 0; },
    get cartSubtotal() { return money(this.cart.reduce((s, l) => s + l.price * l.qty, 0)); },

    // ---- wishlist ----
    toggleWish(id) {
        const was = !!this.wishlist[id];
        this.wishlist[id] = !was;
        if (!was) this.showToast('Saved to wishlist');
    },
    isWished(id) { return !!this.wishlist[id]; },

    // ---- auth ----
    openAuthModal() { this.closeAll(); this.authOpen = true; this.authMode = 'login'; },
    toggleAuthMode() { this.authMode = this.authMode === 'login' ? 'signup' : 'login'; },
    submitAuth(e) {
        const name = (e.target.querySelector('input[type=text]') || {}).value || 'Member';
        this.authOpen = false;
        this.user = { name: this.authMode === 'signup' ? name : 'Account' };
        this.showToast(this.authMode === 'signup' ? 'Account created — welcome!' : 'Signed in successfully');
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

    // ---- overlays ----
    openCart() { this.closeAll(); this.cartOpen = true; },
    openMenu() { this.closeAll(); this.mmenuOpen = true; },
    openSupport() { this.closeAll(); this.supportOpen = true; },
    closeAll() {
        this.cartOpen = false;
        this.mmenuOpen = false;
        this.authOpen = false;
        this.supportOpen = false;
        this.searchCatOpen = false;
    },

    // ---- toast ----
    showToast(msg) {
        clearTimeout(this._toastTimer);
        this.toastMsg = msg;
        this.toastShow = true;
        this._toastTimer = setTimeout(() => { this.toastShow = false; }, 2200);
    },
}));
