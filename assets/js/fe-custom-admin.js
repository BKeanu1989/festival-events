let prices, variations;
prices = Array.from(document.querySelectorAll('[name^="variable_regular_price"]'));

// can i use safari "Array.from"
// prototyping
prices.forEach((x) => x.value = '5');

// attribute_schliessfaecher[0]

variations = document.querySelector('#variable_product_options_inner > div.woocommerce_variations');
console.log(variations);

// Options for the observer (which mutations to observe)
var config = { attributes: false, childList: true, subtree: true };

// Callback function to execute when mutations are observed
var callback = function(mutationsList, observer) {
    for(var mutation of mutationsList) {
        if (mutation.type == 'childList') {
            console.log('A child node has been added or removed.');
        }
    }
};

// Create an observer instance linked to the callback function
var observer = new MutationObserver(callback);

// Start observing the target node for configured mutations
observer.observe(variations, config);

// Later, you can stop observing
// observer.disconnect();

class PriceSetter {
    constructor() {

    }
}