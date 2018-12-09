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
        this.lockerPrices = this.getDomLockerPrices();
    }
    
    populatePrices(mutationsList, observer) {
        this.variations = Array.from(this.variationsWrapper.querySelectorAll('.woocommerce_variation'));
        this.variations.forEach($variation => {
            let lockerInfoOfVariation;
            console.log($variation);
            let lockerOfVariation = $variation.querySelector('select[name^="attribute_schliessf"]');
            let lockerVariation_ID = $variation.querySelector('.remove_variation').getAttribute('rel');
            // find locker of variation lockerPrices
            this.lockerPrices.forEach((lockerInfo) => {
                if (lockerInfo.lockerType === lockerOfVariation.value) {
                    lockerInfoOfVariation = lockerInfo;
                }
            })

            Object.assign(lockerInfoOfVariation, {ID: lockerVariation_ID});

            jQuery.ajax({
                url: ajaxurl,
                data: {
                    'action': 'fe_set_prices',
                    // 'data': JSON.stringify()
                    'data': lockerInfoOfVariation
                },
                success: function(data) {
                    console.log(data);
                },
                error: function(err) {
                    console.log(err);
                }
            });

        });
        if (this.observerInstalled) this.removeObserver();
    }

    removeObserver() {
        this.observer.disconnect();
    }
    
    installObserver() {
        this.config = { attributes: false, childList: true, subtree: true };
        this.observer = new MutationObserver(this.populatePrices.bind(this));
        this.observer.observe(variationsWrapper, this.config);
        this.observerInstalled = true;
    }

    getDomLockerPrices() {
        let lockerPrices = [];
        let priceInputs = Array.from(document.querySelectorAll('[type="number"][name^="_schließf"]'));
        priceInputs.forEach((x) => {
            let obj = {};
            obj.lockerType = x.dataset.lockertype;
            obj.price = x.value;
            lockerPrices.push(obj);
        })
        return lockerPrices;
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