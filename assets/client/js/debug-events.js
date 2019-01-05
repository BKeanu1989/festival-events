let form = document.querySelector('form.variations_form.cart');
let listeners = getEventListeners(form);
let whiteListed = ['reload_product_variations', 'show_variation', 'reset_data', 'change', 'found_variation', 'check_variations', 'update_variation_values'];

monitorEvents(form, whiteListed);

// not really working (for jquery events only?)

let form = document.querySelector('form.variations_form.cart');
let listeners = getEventListeners(form);
listeners = Object.keys(listeners);
listeners.forEach((listener) => {
    console.log(listener);
    if (listener === 'check_variations') {
        jQuery('body').on(listener, function() {
            console.log(`triggered ${listener}`);
        })
    }
})