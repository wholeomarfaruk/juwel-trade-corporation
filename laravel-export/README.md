# Juwel Trade Corporation — Laravel storefront

Homepage for the JTC medical & wellness store, built for **Laravel + Blade + Alpine.js 3.6 + SCSS (compiled by Vite)**. No inline styles — all CSS lives in `resources/sass`, all behaviour in one Alpine component.

## What's in this package

```
app/Http/Controllers/StorefrontController.php   # page + demo catalogue data
routes/web.php                                  # the route (copy into your web.php)
public/images/jtc-logo.jpeg                     # logo (place in public/images/)
resources/
  js/
    app.js                     # bootstraps Alpine + registers both components
    storefront-component.js    # the storefront() Alpine data component (homepage)
    shop-component.js          # the shop() Alpine data component (filter/search/sort)
  sass/
    storefront.scss            # entry (@use of the partials below)
    _variables.scss            # brand palette + tokens (blue #1B7FC4 / green #3DA935)
    _base.scss  _header.scss  _hero.scss  _product.scss  _footer.scss  _modals.scss  _shop.scss
  views/
    layouts/app.blade.php
    storefront/index.blade.php # homepage
    storefront/shop.blade.php  # shop / catalogue page
    storefront/partials/*.blade.php
vite.config.js
package.json
```

## Install into an existing Laravel app

1. **Copy files** into your project, preserving the paths above (merge `routes/web.php`, don't overwrite).

2. **Logo:** ensure `public/images/jtc-logo.jpeg` exists (included here). Optionally add a `public/images/promos/deal-strip.jpg` banner — the promo-strip references it.

3. **Install front-end deps:**
   ```bash
   npm install alpinejs sass laravel-vite-plugin vite
   ```

4. **Register the Vite inputs** — either use the included `vite.config.js`, or add these to yours:
   ```js
   input: ['resources/sass/storefront.scss', 'resources/js/app.js']
   ```

5. **Run the dev server:**
   ```bash
   npm run dev        # watch/HMR
   npm run build      # production build
   php artisan serve
   ```
   Visit `/`.

## Pages

- **`/` — homepage** (`storefront/index.blade.php`, `storefront()` component): top bar, header, hero slider + banners, category carousel, deal/best-seller rails, promo grid, browse grid, trust strip, footer, cart drawer, auth + support modals.
- **`/shop` — catalogue** (`storefront/shop.blade.php`, `shop()` component): sidebar filters (search, price min/max + slider up to ৳200,000, category, brand — each with live counts), toolbar with sort dropdown, removable active-filter chips, responsive product grid, numbered pagination, empty state, and a slide-in filter drawer on mobile. Fully client-side (no page reloads) via Alpine.

## Notes

- **Alpine** — a single `storefront()` component (`resources/js/storefront-component.js`) drives the hero slider (auto-rotate + arrows/dots), the looping category carousel, add-to-cart + quantity + remove (persisted to `localStorage` under `jtc_cart_v1`), wishlist toggles, the login/sign-up modal, the support modal, the mobile off-canvas menu, and toasts. Registered on the page root via `x-data="storefront(@js([...]))"`.
- **Data** — the controller ships hard-coded demo products/categories/slides so it renders out of the box. Swap `storeData()` for your Eloquent models; keep the same array keys (the product `decorate()` helper computes the display fields the card partial expects: `priceText`, `showDealPct`, `pctText`, etc.).
- **Server vs client** — product name/price/badges are rendered by Blade (SEO-friendly); only interactive bits (wishlist state, cart, add button) use Alpine, keyed by product `id`.
- **Responsive** — pure CSS via media queries in the SCSS (`$bp-tablet: 1100px`, `$bp-mobile: 768px`): hamburger + full-width search + hidden hero side-banners on mobile; auto-fill/auto-fit product grids; `clamp()` rail card widths.
- **Fonts** — Sora + Inter are loaded via `<link>` in `layouts/app.blade.php`. Self-host for production if you prefer.
- **Images** — product/category/slide images use `picsum.photos` seeds as placeholders; replace the `img()` output in the controller with your real CDN/storage URLs.

## Alpine 3.6

`package.json` pins `alpinejs` to `^3.6` (resolves the latest compatible 3.x). The component uses only stable Alpine 3 APIs (`x-data`, `x-for`, `x-show`, `x-text`, `x-cloak`, `$watch`), so any Alpine 3.6+ release works.
