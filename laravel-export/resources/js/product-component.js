import Alpine from 'alpinejs';

/**
 * Product — details page: gallery (image + video), quantity, cart actions.
 *
 * Registered as `x-data="product(@js([...]))"` on the product page root.
 * Cart + wishlist share the same localStorage key as the rest of the store.
 */

const CART_KEY = 'jtc_cart_v1';

Alpine.data('product', (config = {}) => ({
    // ---- server data ----
    p: config.product || {},          // decorated product
    gallery: config.gallery || [],    // image URLs
    videoSrc: config.videoSrc || '',

    // ---- ui state ----
    activeMedia: 0,                    // 0..gallery.length-1 = image, gallery.length = video
    qty: 1,
    tab: 'description',
    cart: [],
    wishlist: {},
    toastMsg: '',
    toastShow: false,

    init() {
        try { this.cart = JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch (e) { this.cart = []; }
        this.$watch('cart', () => {
            try { localStorage.setItem(CART_KEY, JSON.stringify(this.cart)); } catch (e) {}
        });
    },

    // ---- gallery ----
    get videoIndex() { return this.gallery.length; },
    get showingVideo() { return this.activeMedia === this.videoIndex; },
    get activeImage() { return this.gallery[Math.min(this.activeMedia, this.gallery.length - 1)]; },
    isActiveMedia(i) { return this.activeMedia === i; },

    // ---- quantity ----
    inc() { this.qty += 1; },
    dec() { this.qty = Math.max(1, this.qty - 1); },

    // ---- cart / wishlist ----
    addToCart() {
        const line = this.cart.find((l) => l.id === this.p.id);
        if (line) line.qty += this.qty;
        else this.cart.push({ id: this.p.id, name: this.p.name, price: this.p.price, image: this.p.image, sku: this.p.sku, qty: this.qty });
        this.showToast('Added ' + this.qty + ' to cart');
    },
    buyNow() {
        this.addToCart();
        this.showToast('Proceeding to checkout…');
        // window.location = '/checkout';
    },
    addProduct(pr) {
        const line = this.cart.find((l) => l.id === pr.id);
        if (line) line.qty += 1;
        else this.cart.push({ id: pr.id, name: pr.name, price: pr.price, image: pr.image, sku: pr.sku, qty: 1 });
        this.showToast('Added to cart');
    },
    toggleWish(id) {
        const was = !!this.wishlist[id];
        this.wishlist[id] = !was;
        if (!was) this.showToast('Saved to wishlist');
    },
    isWished(id) { return !!this.wishlist[id]; },

    get cartCount() { return this.cart.reduce((n, l) => n + l.qty, 0); },

    // ---- tabs ----
    setTab(t) { this.tab = t; },

    // ---- toast ----
    showToast(msg) {
        clearTimeout(this._t);
        this.toastMsg = msg;
        this.toastShow = true;
        this._t = setTimeout(() => { this.toastShow = false; }, 2200);
    },
}));
