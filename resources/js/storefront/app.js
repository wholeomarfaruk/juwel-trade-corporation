// =============================================================================
// Juwel Trade Corporation — storefront entry
//
// Alpine.js is provided by Livewire (@livewireScripts). We must NOT import or
// start a second Alpine instance (that breaks Livewire). Instead we register
// the storefront() component onto Livewire's Alpine via the `alpine:init` event.
// =============================================================================

import { storefront } from './storefront-component';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('storefront', storefront);
});
