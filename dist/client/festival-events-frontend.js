'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var all_are_you_renter_container = void 0;
var are_you_renter_boxes = void 0;
var form_checkout = void 0;
var fieldsToValidate = void 0;
var uniqueIdentifiers = new Set();

all_are_you_renter_container = document.querySelectorAll('div.checkbox-required[data-identifier]');
var wm_validationPassed = new WeakMap();

if (all_are_you_renter_container) {
    are_you_renter_boxes = Array.from(document.querySelectorAll('input[type="radio"][name^="renter"'));
    if (are_you_renter_boxes) {
        are_you_renter_boxes.forEach(function (radioButton) {
            var key = radioButton.dataset.identifier;
            uniqueIdentifiers.add(key);
            radioButton.addEventListener('change', function () {
                try {
                    var value = radioButton.value;
                    var findExtraPerson = document.querySelector('.extra_person__wrapper[data-identifier="' + key + '"');

                    if (value === 'yes') {
                        findExtraPerson.classList.add('hide_if_yes');
                        findExtraPerson.classList.add('hide_if_default');
                        findExtraPerson.classList.toggle('fadeIn');
                    }
                    if (value === 'no') {
                        findExtraPerson.classList.remove('hide_if_yes');
                        findExtraPerson.classList.remove('hide_if_default');
                        findExtraPerson.classList.toggle('fadeIn');
                    }
                } catch (err) {
                    console.log(err);
                }
            });
        });
    }
}

form_checkout = document.querySelector('form[name="checkout"]');
if (form_checkout) {
    jQuery('form.checkout').on('checkout_place_order', function () {
        var validatedCheckboxes = validateCheckbox();
        if (!validatedCheckboxes) {
            try {
                all_are_you_renter_container[0].scrollIntoView({ behavior: 'smooth' });
                all_are_you_renter_container.forEach(function (x) {
                    if (x.classList.contains('blink')) {
                        console.log("remove blink class");
                        x.classList.remove('blink');
                    }
                });
                // blinking is set in css
                setTimeout(function () {
                    console.log(all_are_you_renter_container);
                    all_are_you_renter_container.forEach(function (x) {
                        // console.log(x);
                        if (!wm_validationPassed.get(x)) {
                            x.classList.add('blink');
                            console.log(x);
                        }
                    });
                }, 500);
                return false;
            } catch (err) {
                console.log(err);
            }
        }
        return true;
    });
}

//TODO: validate all renter buttons
// set for data-identifier (grouping)
// if no value is set in a group --> fail validation
function validateCheckbox() {
    var validationArray = [];
    var validationPassed = void 0;
    uniqueIdentifiers.forEach(function (data_hash) {
        try {
            var _validationPassed = false;
            var radioContainer = document.querySelector('div[data-identifier="' + data_hash + '"]');
            var radiosPerGroup = Array.from(radioContainer.querySelectorAll('input[type="radio"]'));
            _validationPassed = radiosPerGroup.some(function (element) {
                return element.checked;
            });
            wm_validationPassed.set(radioContainer, _validationPassed);
            validationArray.push(_validationPassed);
        } catch (err) {
            console.log(err);
        }
    });
    validationPassed = validationArray.every(function (x) {
        return x;
    });
    return validationPassed;
}

//TODO: client side validation of extra fields
// get all extra_person__wrapper fields without hide_if_yes hide_if_default
fieldsToValidate = document.querySelectorAll('.extra_person__wrapper:not(.hide_if_yes)');
if (fieldsToValidate) {}
var chooseLockerButtons = void 0,
    lockerSelect = void 0,
    productForm = void 0;

chooseLockerButtons = Array.from(document.querySelectorAll('button.chooseLocker'));
lockerSelect = document.querySelector('select#pa_locker');
productForm = document.querySelector('form.cart.variations_form');

if (chooseLockerButtons) {
    chooseLockerButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            var identifier = event.target.dataset.identifier + '-' + currentLanguage;
            var options = Array.from(lockerSelect.options);
            var chosen = options.filter(function (x) {
                if (x.value === identifier) {
                    return x;
                }
            });
            chosen = chosen[0];
            lockerSelect.value = chosen.value;
            jQuery(".variations_form").trigger('check_variations');
            productForm.scrollIntoView({ behavior: 'smooth' });
        });
    });
}
// let form = document.querySelector('form.variations_form.cart');
// let listeners = getEventListeners(form);
// let whiteListed = ['reload_product_variations', 'show_variation', 'reset_data', 'change', 'found_variation', 'check_variations', 'update_variation_values'];

// monitorEvents(form, whiteListed);

// // not really working (for jquery events only?)

// let form = document.querySelector('form.variations_form.cart');
// let listeners = getEventListeners(form);
// listeners = Object.keys(listeners);
// listeners.forEach((listener) => {
//     console.log(listener);
//     if (listener === 'check_variations') {
//         jQuery('body').on(listener, function() {
//             console.log(`triggered ${listener}`);
//         })
//     }
// })
var $confirmEmail = void 0;

$confirmEmail = document.querySelector('#billing_email_confirm');

if ($confirmEmail) {
    $confirmEmail.addEventListener('keydown', function (event) {
        if ((event.metaKey || event.ctrlKey) && event.keyCode == 86) {
            event.preventDefault();
            console.log("CEO doesn't want this to happen");
        }
    });
}
var triggerPopulateUserDataButton = void 0;

triggerPopulateUserDataButton = document.querySelector('#populate_data_for_other_lockers');

if (triggerPopulateUserDataButton) {
    triggerPopulateUserDataButton.addEventListener('click', function () {
        var populateUserData = new PopulateUserData();
    });
}

var PopulateUserData = function () {
    function PopulateUserData() {
        _classCallCheck(this, PopulateUserData);

        this._firstNames;
        this._lastNames;
        this._birtdates;

        this.firstName;
        this.lastName;
        this.birtdate;

        this.init();
        this.setData();
    }

    _createClass(PopulateUserData, [{
        key: 'init',
        value: function init() {
            this._firstNames = Array.from(document.querySelectorAll('[name*="[first_name]"]'));
            this._lastNames = Array.from(document.querySelectorAll('[name*="[last_name]"]'));
            this._birthdates = Array.from(document.querySelectorAll('[name*="[birthdate]"]'));

            this.firstName = this._firstNames[0].value;
            this.lastName = this._lastNames[0].value;
            this.birthdate = this._birthdates[0].value;

            this._firstNames.shift();
            this._lastNames.shift();
            this._birthdates.shift();
        }
    }, {
        key: 'setData',
        value: function setData() {
            var _this = this;

            this._firstNames.forEach(function ($firstname) {
                $firstname.value = _this.firstName;
            });

            this._lastNames.forEach(function ($lastName) {
                $lastName.value = _this.lastName;
            });

            this._birthdates.forEach(function ($birthdate) {
                $birthdate.value = _this.birthdate;
            });
        }
    }]);

    return PopulateUserData;
}();