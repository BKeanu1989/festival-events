'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var prices = void 0,
    variationsWrapper = void 0,
    variationsTab = void 0,
    triggerPopulateButton = void 0;
prices = Array.from(document.querySelectorAll('[name^="variable_regular_price"]'));
variationsTab = document.querySelector('.variations_tab');
triggerPopulateButton = document.querySelector('#trigger_populate_prices');
variationsWrapper = document.querySelector('#variable_product_options_inner > div.woocommerce_variations');

if (triggerPopulateButton) {
    triggerPopulateButton.addEventListener('click', function () {
        console.log("populate prices!!!");

        var priceSetter = new PriceSetter();
        priceSetter.populatePrices();
        console.log(priceSetter.variations);
    });
    console.log(variationsWrapper);
}

var PriceSetter = function () {
    function PriceSetter() {
        _classCallCheck(this, PriceSetter);

        this.lockerPrices = this.getDomLockerPrices();
    }

    _createClass(PriceSetter, [{
        key: 'populatePrices',
        value: function populatePrices(mutationsList, observer) {
            jQuery.ajax({
                url: ajaxurl,
                data: {
                    'action': 'fe_set_prices',
                    // 'data': JSON.stringify()
                    'data': { lockerPrices: this.lockerPrices, productID: localizedVars.postID }
                },
                success: function success(data) {
                    console.log(data);
                },
                error: function error(err) {
                    console.log(err);
                }
            });
        }
    }, {
        key: 'getDomLockerPrices',
        value: function getDomLockerPrices() {
            var lockerPrices = {};
            lockerPrices["Full Festival"] = [];
            lockerPrices["Daily"] = [];
            var priceInputs = Array.from(document.querySelectorAll('[type="number"][name^="_schließf"]'));
            priceInputs.forEach(function (x) {
                var obj = {};
                var period = x.dataset.period == 'Full Festival' ? 'Full Festival' : 'Daily';
                obj.lockerType = x.dataset.lockertype;
                obj.price = x.value;
                obj.period = period;
                lockerPrices[period].push(obj);
            });
            return lockerPrices;
        }
    }]);

    return PriceSetter;
}();

var SetAttributes = function () {
    function SetAttributes() {
        _classCallCheck(this, SetAttributes);

        this.locations;
        this.$locations;
        this.festivalStart;
        this.$festivalStart;
        this.festivalEnd;
        this.$festivalEnd;
        this.enumerateDays;

        this.allLockers;
        this.$allLockers;
        this.lockers;

        this.setValues();
    }

    _createClass(SetAttributes, [{
        key: 'setValues',
        value: function setValues() {
            // this.enumerateDays = document.querySelector('[name="_enumerate_days"]').checked;
            this.enumerateDays = document.querySelector('[name="_enumerate_days"]').checked === true;

            this.$locations = document.querySelector('[name="_festival_locations"]');
            this.locations = this.$locations.value.trim().split(',');

            this.$festivalStart = document.querySelector('[name="_festival_start"]');
            this.festivalStart = this.$festivalStart.value;

            this.$festivalEnd = document.querySelector('[name="_festival_end"]');
            this.festivalEnd = this.$festivalEnd.value;

            this.$allLockers = document.querySelectorAll('[name^="_lockers"]');
            this.allLockers = Array.from(this.$allLockers);

            this.lockers = this.allLockers.filter(function (x) {
                return x.checked;
            });

            this.lockers = this.lockers.map(function (x) {
                return x.dataset.lockertype;
            });
        }
    }, {
        key: 'ajaxCall',
        value: function ajaxCall() {
            console.log("POSTID", localizedVars.postID);
            jQuery.ajax({
                url: ajaxurl,
                data: {
                    'action': 'fe_set_product_atts',
                    // 'data': JSON.stringify()
                    'data': { ID: localizedVars.postID, Locations: this.locations, EnumerateDays: this.enumerateDays, FestivalStart: this.festivalStart, FestivalEnd: this.festivalEnd, Lockers: this.lockers }
                },
                success: function success(data) {
                    console.log(data);
                    location.reload(true);
                },
                error: function error(err) {
                    console.log(err);
                }
            });
        }
    }]);

    return SetAttributes;
}();

var SET_ATTRIBUTES = new SetAttributes();
SET_ATTRIBUTES.ajaxCall();