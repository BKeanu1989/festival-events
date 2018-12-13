let prices, variationsWrapper, variationsTab, triggerPopulateButton;
prices = Array.from(document.querySelectorAll('[name^="variable_regular_price"]'));
variationsTab = document.querySelector('.variations_tab');
triggerPopulateButton = document.querySelector('#trigger_populate_prices');
variationsWrapper = document.querySelector('#variable_product_options_inner > div.woocommerce_variations');


if (triggerPopulateButton) {
    triggerPopulateButton.addEventListener('click', () => {
        console.log("populate prices!!!");
    
        let priceSetter = new PriceSetter();
        priceSetter.populatePrices();
        console.log(priceSetter.variations);
    })
    console.log(variationsWrapper);
}

class PriceSetter {
    constructor() {
        this.lockerPrices = this.getDomLockerPrices();
    }
    
    populatePrices(mutationsList, observer) {
        jQuery.ajax({
            url: ajaxurl,
            data: {
                'action': 'fe_set_prices',
                // 'data': JSON.stringify()
                'data': {lockerPrices: this.lockerPrices, productID: localizedVars.postID}
            },
            success: function(data) {
                console.log(data);
                location.reload(true);
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    getDomLockerPrices() {
        let lockerPrices = {};
        lockerPrices["Full Festival"] = [];
        lockerPrices["Daily"] = [];
        let priceInputs = Array.from(document.querySelectorAll('[type="number"][name^="_schließf"]'));
        priceInputs.forEach((x) => {
            let obj = {};
            let period = (x.dataset.period == 'Full Festival') ? 'Full Festival' : 'Daily';
            obj.lockerType = x.dataset.lockertype;
            obj.price = x.value;
            obj.period = period;
            lockerPrices[period].push(obj);
        })
        return lockerPrices;
    }
}