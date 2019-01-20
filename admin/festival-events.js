let buttons;

buttons = Array.from(document.querySelectorAll('.foreign-language button'));

buttons.forEach((x) => x.disabled = true);
let prices, variationsWrapper, variationsTab, triggerPopulateButton;
prices = Array.from(document.querySelectorAll('[name^="variable_regular_price"]'));
variationsTab = document.querySelector('.variations_tab');
triggerPopulateButton = document.querySelector('#trigger_populate_prices');
variationsWrapper = document.querySelector('#variable_product_options_inner > div.woocommerce_variations');


if (triggerPopulateButton) {
    triggerPopulateButton.addEventListener('click', () => {
        console.log("populate prices!!!");
        document.body.style.cursor = 'wait';
    
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
        document.body.style.cursor = 'wait';
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
let setAttributeButton;
setAttributeButton = document.querySelector('#trigger_add_variations');

if (setAttributeButton) {
    setAttributeButton.addEventListener('click', () => {
        const SET_ATTRIBUTES = new SetAttributes();
        SET_ATTRIBUTES.ajaxCall();
    })
}

class SetAttributes {
    constructor() {
        this.locations;
        this.$locations;
        this.festivalStart;
        this.$festivalStart;
        this.festivalEnd;
        this.$festivalEnd;
        this.enumerateDays

        this.allLockers;
        this.$allLockers;
        this.lockers;

        this.setValues();
    }

    setValues() {
        // this.enumerateDays = document.querySelector('[name="_enumerate_days"]').checked;
        this.enumerateDays = (document.querySelector('[name="_enumerate_days"]').checked === true);

        this.$locations = document.querySelector('[name="_festival_locations"]');
        this.locations = this.$locations.value.trim().split(',');
        
        this.$festivalStart = document.querySelector('[name="_festival_start"]');
        this.festivalStart = this.$festivalStart.value;
        
        this.$festivalEnd = document.querySelector('[name="_festival_end"]');
        this.festivalEnd = this.$festivalEnd.value;
        
        this.$allLockers = document.querySelectorAll('[name^="_lockers"]');
        this.allLockers = Array.from(this.$allLockers);
        
        this.lockers = this.allLockers.filter((x) => x.checked);
        
        this.lockers = this.lockers.map((x) => x.dataset.lockertype);
    }

    ajaxCall() {
        console.log("POSTID", localizedVars.postID);
        document.body.style.cursor = 'wait';
        jQuery.ajax({
            url: ajaxurl,
            data: {
                'action': 'fe_set_product_atts',
                // 'data': JSON.stringify()
                'data': {ID: localizedVars.postID, Locations: this.locations, EnumerateDays: this.enumerateDays, FestivalStart: this.festivalStart, FestivalEnd: this.festivalEnd, Lockers: this.lockers}
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
}

