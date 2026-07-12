import Alpine from 'alpinejs';

/**
 * Shop — client-side filter / search / sort / paginate for the catalogue page.
 *
 * Registered as `x-data="shop(@js([...]))"` on the shop page root.
 * Cart + wishlist share the same localStorage key as the homepage.
 */

const money = (n) => '৳' + Number(n).toLocaleString('en-US');
const CART_KEY = 'jtc_cart_v1';
const PRICE_CAP = 200000;
const PER_PAGE = 12;

Alpine.data('shop', (config = {}) => ({
    // ---- server data ----
    products: config.products || [],
    categories: config.categories || [],
    brands: config.brands || [],

    // ---- filter state ----
    search: '',
    selectedCats: [],
    selectedBrands: [],
    priceMin: 0,
    priceMax: PRICE_CAP,
    sort: 'popularity',
    page: 1,

    // ---- cart / wishlist / ui ----
    cart: [],
    wishlist: {},
    mobileFiltersOpen: false,
    toastMsg: '',
    toastShow: false,

    priceCap: PRICE_CAP,

    init() {
        try { this.cart = JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch (e) { this.cart = []; }
        this.$watch('cart', () => {
            try { localStorage.setItem(CART_KEY, JSON.stringify(this.cart)); } catch (e) {}
        });
        // reset to page 1 whenever a filter changes
        ['search', 'selectedCats', 'selectedBrands', 'priceMin', 'priceMax', 'sort'].forEach((k) => {
            this.$watch(k, () => { this.page = 1; });
        });
    },

    money,

    // ---- filtering ----
    matches(p) {
        const q = this.search.trim().toLowerCase();
        if (q && !p.name.toLowerCase().includes(q) && !p.brand.toLowerCase().includes(q)) return false;
        if (this.selectedCats.length && !this.selectedCats.includes(p.category)) return false;
        if (this.selectedBrands.length && !this.selectedBrands.includes(p.brand)) return false;
        if (p.price < this.priceMin || p.price > this.priceMax) return false;
        return true;
    },

    get filtered() {
        const arr = this.products.filter((p) => this.matches(p));
        const s = this.sort;
        if (s === 'price_low') arr.sort((a, b) => a.price - b.price);
        else if (s === 'price_high') arr.sort((a, b) => b.price - a.price);
        else if (s === 'rating') arr.sort((a, b) => b.rating - a.rating);
        else if (s === 'newest') arr.sort((a, b) => (b.badge === 'new') - (a.badge === 'new') || b.id - a.id);
        else arr.sort((a, b) => b.reviews - a.reviews);
        return arr;
    },

    get total() { return this.filtered.length; },
    get totalPages() { return Math.max(1, Math.ceil(this.total / PER_PAGE)); },
    get currentPage() { return Math.min(this.page, this.totalPages); },
    get paged() {
        const p = this.currentPage;
        return this.filtered.slice((p - 1) * PER_PAGE, p * PER_PAGE);
    },
    get pageNumbers() {
        return Array.from({ length: this.totalPages }, (_, i) => i + 1);
    },

    // counts shown next to each facet (respect the *other* active filters)
    catCount(slug) {
        return this.products.filter((p) => {
            const q = this.search.trim().toLowerCase();
            if (q && !p.name.toLowerCase().includes(q) && !p.brand.toLowerCase().includes(q)) return false;
            if (this.selectedBrands.length && !this.selectedBrands.includes(p.brand)) return false;
            if (p.price < this.priceMin || p.price > this.priceMax) return false;
            return p.category === slug;
        }).length;
    },
    brandCount(name) {
        return this.products.filter((p) => {
            const q = this.search.trim().toLowerCase();
            if (q && !p.name.toLowerCase().includes(q) && !p.brand.toLowerCase().includes(q)) return false;
            if (this.selectedCats.length && !this.selectedCats.includes(p.category)) return false;
            if (p.price < this.priceMin || p.price > this.priceMax) return false;
            return p.brand === name;
        }).length;
    },

    toggleCat(slug) {
        this.selectedCats = this.selectedCats.includes(slug)
            ? this.selectedCats.filter((c) => c !== slug)
            : [...this.selectedCats, slug];
    },
    toggleBrand(name) {
        this.selectedBrands = this.selectedBrands.includes(name)
            ? this.selectedBrands.filter((b) => b !== name)
            : [...this.selectedBrands, name];
    },
    clearFilters() {
        this.search = '';
        this.selectedCats = [];
        this.selectedBrands = [];
        this.priceMin = 0;
        this.priceMax = PRICE_CAP;
    },
    catName(slug) {
        const c = this.categories.find((x) => x.slug === slug);
        return c ? c.name : slug;
    },

    get activeChips() {
        const chips = [];
        if (this.search) chips.push({ label: 'Search: "' + this.search + '"', clear: () => { this.search = ''; } });
        this.selectedCats.forEach((slug) => chips.push({ label: this.catName(slug), clear: () => this.toggleCat(slug) }));
        this.selectedBrands.forEach((b) => chips.push({ label: b, clear: () => this.toggleBrand(b) }));
        if (this.priceMin > 0 || this.priceMax < PRICE_CAP) {
            chips.push({ label: money(this.priceMin) + ' – ' + money(this.priceMax), clear: () => { this.priceMin = 0; this.priceMax = PRICE_CAP; } });
        }
        return chips;
    },

    // ---- cart / wishlist ----
    addToCart(id) {
        const p = this.products.find((x) => x.id === id);
        if (!p) return;
        const line = this.cart.find((l) => l.id === id);
        if (line) line.qty += 1;
        else this.cart.push({ id: p.id, name: p.name, price: p.price, image: p.image, sku: p.sku, qty: 1 });
        this.showToast('Added to cart');
    },
    toggleWish(id) {
        const was = !!this.wishlist[id];
        this.wishlist[id] = !was;
        if (!was) this.showToast('Saved to wishlist');
    },
    isWished(id) { return !!this.wishlist[id]; },

    // ---- toast ----
    showToast(msg) {
        clearTimeout(this._t);
        this.toastMsg = msg;
        this.toastShow = true;
        this._t = setTimeout(() => { this.toastShow = false; }, 2200);
    },
}));
