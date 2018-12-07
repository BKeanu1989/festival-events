let prices, variationsWrapper, variationsTab, triggerPopulateButton;
prices = Array.from(document.querySelectorAll('[name^="variable_regular_price"]'));
variationsTab = document.querySelector('.variations_tab');
triggerPopulateButton = document.querySelector('#trigger_populate_prices');
variationsWrapper = document.querySelector('#variable_product_options_inner > div.woocommerce_variations');

triggerPopulateButton.addEventListener('click', () => {
    console.log("populate prices!!!");
    console.log(variationsWrapper);

    let priceSetter = new PriceSetter(variationsWrapper);
    if ((variationsWrapper.querySelectorAll('.woocommerce_variation')).length == 0) {
        priceSetter.installObserver();
    } else {
        priceSetter.populatePrices();
    }
})

console.log(variationsWrapper);

class PriceSetter {
    constructor(variationsWrapper) {
        this.observerInstalled = false;
        this.variationsWrapper = variationsWrapper;
        this.lockerPrices = getDomLockerPrices();
    }
    
    populatePrices(mutationsList, observer) {
        console.log("all mutations done");
        this.variations = Array.from(this.variationsWrapper.querySelectorAll('.woocommerce_variation'));
        this.variations.forEach(variation => {
            console.log(variation);
        });
        if (this.observerInstalled) this.removeObserver();
    }

    removeObserver() {
        console.log("remove observer");
        this.observer.disconnect();
    }
    
    installObserver() {
        console.log("install observer");
        this.config = { attributes: false, childList: true, subtree: true };
        this.observer = new MutationObserver(this.populatePrices.bind(this));
        this.observer.observe(variationsWrapper, this.config);
        this.observerInstalled = true;
    }

    getDomLockerPrices() {
        
    }
}











function simulateClick(element) {
    var event = new MouseEvent('click', {
        view: window,
        bubbles: true,
        cancelable: true
    });
    // var cb = document.getElementById('checkbox'); 
    let clicked = element.dispatchEvent(event);
    if (clicked) {
        alert("not cancelled");
        // A handler called preventDefault.
    } else {
        // None of the handlers called preventDefault.
        alert("cancelled");
    }
}