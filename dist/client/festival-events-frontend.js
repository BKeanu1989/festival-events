'use strict';

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var all_are_you_renter_container = void 0;
var are_you_renter_boxes = void 0;
var form_checkout = void 0;
var fieldsToValidate = void 0;

all_are_you_renter_container = document.querySelectorAll('div.checkbox-required[data-identifier]');

if (all_are_you_renter_container) {
    are_you_renter_boxes = Array.from(document.querySelectorAll('input[type="radio"][name^="renter"]'));
    if (are_you_renter_boxes) {
        are_you_renter_boxes.forEach(function (radioButton) {
            var key = radioButton.dataset.identifier;
            radioButton.addEventListener('change', function () {
                try {
                    var value = radioButton.value;
                    var findExtraPerson = document.querySelector('.extra_person__wrapper[data-identifier="' + key + '"]');

                    if (value === 'yes') {
                        findExtraPerson.classList.add('hide_if_yes');
                        findExtraPerson.classList.add('hide_if_default');
                        findExtraPerson.classList.toggle('fadeIn');
                    }
                    if (value === 'no') {
                        findExtraPerson.classList.remove('hide_if_yes');
                        findExtraPerson.classList.remove('hide_if_default');
                        findExtraPerson.classList.toggle('fadeIn');

                        new Cleave('input[name="extra_person-birthdate[' + key + ']"]', {
                            date: true,
                            datePattern: ['Y', 'm', 'd'],
                            delimiter: '-'
                        });
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

        var radioValidator = new RadioValidator();
        radioValidator.init();

        var inputValidator = new InputValidator();
        inputValidator.init();

        if (wm_validationPassed.get(radioValidator) === false || wm_validationPassed.get(inputValidator) === false) return false;
    });
}

jQuery('form.variations').on('found_variation', function () {

    jQuery('.single_variation .price').show();
});

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
var wm_validationPassed = new WeakMap();

var Validator = function () {
    function Validator() {
        _classCallCheck(this, Validator);
    }

    _createClass(Validator, [{
        key: 'init',
        value: function init() {}
    }, {
        key: 'scrollIntoView',
        value: function scrollIntoView($element) {
            $element.scrollIntoView({ behavior: 'smooth' });
        }
    }]);

    return Validator;
}();

var RadioValidator = function (_Validator) {
    _inherits(RadioValidator, _Validator);

    function RadioValidator() {
        _classCallCheck(this, RadioValidator);

        var _this = _possibleConstructorReturn(this, (RadioValidator.__proto__ || Object.getPrototypeOf(RadioValidator)).call(this));

        _this.allRadioContainer;
        _this.allUniqueHashes;
        _this.groupedRadioButtons = [];
        return _this;
    }

    _createClass(RadioValidator, [{
        key: 'init',
        value: function init() {
            this.allRadioContainer();
            this.uniqueHashes();
            this.groupRadioButtons();

            this.validationHandler();
            this.handleInvalid();
            this.scrollToFirstInvalid();
        }
    }, {
        key: 'groupRadioButtons',
        value: function groupRadioButtons() {
            var _this2 = this;

            this.allUniqueHashes.forEach(function (hash) {
                var radioContainer = document.querySelector('div[data-identifier="' + hash + '"]');
                _this2.groupedRadioButtons.push(radioContainer);
            });
        }
    }, {
        key: 'allRadioContainer',
        value: function allRadioContainer() {
            this.allRadioContainer = Array.from(document.querySelectorAll('div[data-identifier]'));
        }
    }, {
        key: 'uniqueHashes',
        value: function uniqueHashes() {
            this.allUniqueHashes = new Set(this.allRadioContainer.map(function (x) {
                return x.dataset.identifier;
            }));
        }
    }, {
        key: 'validationHandler',
        value: function validationHandler() {
            var validationArray = [];
            var passedValidation = void 0;
            this.groupedRadioButtons.forEach(function (singleGroup) {
                try {
                    var radioButtons = Array.from(singleGroup.querySelectorAll('input[type="radio"]'));
                    var validationPassed = false;
                    validationPassed = radioButtons.some(function (radioInput) {
                        return radioInput.checked;
                    });
                    wm_validationPassed.set(singleGroup, validationPassed);
                    validationArray.push(validationPassed);
                } catch (err) {
                    console.log(err);
                }
            });
            passedValidation = validationArray.every(function (x) {
                return x;
            });
            wm_validationPassed.set(this, passedValidation);
        }
    }, {
        key: 'handleInvalid',
        value: function handleInvalid() {
            this.groupedRadioButtons.forEach(function (singleGroup) {
                try {
                    if (singleGroup.classList.contains('blink')) {
                        singleGroup.classList.remove('blink');
                    }
                    setTimeout(function () {
                        if (wm_validationPassed.get(singleGroup) === false) {
                            singleGroup.classList.add('blink');
                        }
                    }, 500);
                } catch (err) {
                    console.log(err);
                }
            });
        }
    }, {
        key: 'scrollToFirstInvalid',
        value: function scrollToFirstInvalid() {
            var _this3 = this;

            try {
                this.groupedRadioButtons.some(function (singleGroup) {
                    try {
                        if (wm_validationPassed.get(singleGroup) === false) {
                            _get(RadioValidator.prototype.__proto__ || Object.getPrototypeOf(RadioValidator.prototype), 'scrollIntoView', _this3).call(_this3, singleGroup);
                            return true;
                        }
                    } catch (err) {
                        console.log(err);
                    }
                });
            } catch (err) {
                console.log(err);
            }
        }
    }]);

    return RadioValidator;
}(Validator);

var InputValidator = function (_Validator2) {
    _inherits(InputValidator, _Validator2);

    function InputValidator() {
        _classCallCheck(this, InputValidator);

        var _this4 = _possibleConstructorReturn(this, (InputValidator.__proto__ || Object.getPrototypeOf(InputValidator)).call(this));

        _this4.groupsToValidate;
        _this4.validationPassed_array = [];
        return _this4;
    }

    _createClass(InputValidator, [{
        key: 'init',
        value: function init() {
            this.groupsToValidate = Array.from(document.querySelectorAll('.extra_person__wrapper:not(.hide_if_yes)'));

            this.validationHandler();
            this.handleInvalid();
        }
    }, {
        key: 'validationHandler',
        value: function validationHandler() {
            var _this5 = this;

            this.groupsToValidate.forEach(function (singleGroup) {
                try {
                    _this5.validateInputGroup(singleGroup);
                } catch (err) {
                    console.log(err);
                }
            });
            var passedValidation = this.validationPassed_array.every(function (x) {
                return x;
            });
            wm_validationPassed.set(this, passedValidation);
        }
    }, {
        key: 'validateInputGroup',
        value: function validateInputGroup($group) {
            var _this6 = this;

            var passed = void 0;
            var $firstname = $group.querySelector('[name^="extra_person-first_name"]');
            var $lastname = $group.querySelector('[name^="extra_person-last_name"]');
            var $bday = $group.querySelector('[name^="extra_person-birthdate"]');

            var groupInputs = [$firstname, $lastname, $bday];

            groupInputs.forEach(function ($input) {
                _this6.handleEmptyInput($input);
            });

            passed = groupInputs.every(function (input) {
                return input.value;
            });

            this.validationPassed_array.push(passed);
            wm_validationPassed.set($group, passed);
        }
    }, {
        key: 'handleInvalid',
        value: function handleInvalid() {
            this.groupsToValidate.forEach(function (singleContainer) {
                try {
                    var singleGroup = singleContainer.querySelector('.extra_person__wrapper--input-wrapper');
                    if (singleGroup.classList.contains('blink')) {
                        singleGroup.classList.remove('blink');
                    }
                    setTimeout(function () {
                        if (wm_validationPassed.get(singleContainer) === false) {
                            singleGroup.classList.add('blink');
                        }
                    }, 500);
                } catch (err) {
                    console.log(err);
                }
            });

            this.scrollToFirstInvalid();
        }
    }, {
        key: 'scrollToFirstInvalid',
        value: function scrollToFirstInvalid() {
            var _this7 = this;

            try {
                this.groupsToValidate.some(function (singleGroup) {
                    if (wm_validationPassed.get(singleGroup) === false) {
                        _get(InputValidator.prototype.__proto__ || Object.getPrototypeOf(InputValidator.prototype), 'scrollIntoView', _this7).call(_this7, singleGroup);
                        return true;
                    }
                });
            } catch (err) {
                console.log(err);
            }
        }
    }, {
        key: 'attachInputEvent',
        value: function attachInputEvent($input) {
            $input.addEventListener('input', function (event) {
                try {
                    if ($input.classList.contains('invalid')) {
                        $input.classList.remove('invalid');
                    }
                } catch (err) {
                    console.log(err);
                }
            });
        }
    }, {
        key: 'handleEmptyInput',
        value: function handleEmptyInput($input) {
            if ($input.value === '') {
                if ($input.classList.contains('invalid')) {
                    $input.classList.remove('invalid');
                }
                $input.classList.add('invalid');
                this.attachInputEvent($input);
            }
        }
    }]);

    return InputValidator;
}(Validator);
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

var MoveElement = function () {
    // only if window.innerWidth > 1200 whatever ...
    function MoveElement($movingElement, $endingElement) {
        _classCallCheck(this, MoveElement);

        this.movingElement = $movingElement;
        this.movingElementPos;
        this.endingElement = $endingElement;
        this.endingElementPos;
        this.active = false;
        this.pageYOffset;
        this.oldPageYOffset;
        this.unit = 'px';
        this.step = 20;
    }

    _createClass(MoveElement, [{
        key: 'init',
        value: function init() {
            this.pageYOffset = window.pageYOffset;
            this.oldPageYOffset = window.pageYOffset;
        }
    }, {
        key: 'install',
        value: function install() {
            this.init();
            this.moveHandler();
        }
    }, {
        key: 'moveElement',
        value: function moveElement() {

            var topNow = parseFloat(this.movingElement.style.top) || 0;
            var newTop = this.pageYOffset - this.oldPageYOffset;
            debugger;
            var newTopString = newTop + this.unit;
            this.movingElement.style.top = newTopString;
        }
    }, {
        key: 'moveHandler',
        value: function moveHandler() {
            var that = this;
            window.addEventListener('scroll', function (event) {

                if (!that.active) {
                    window.requestAnimationFrame(function () {
                        that.pageYOffset = window.pageYOffset;
                        that.moveElement();
                        that.active = false;
                    });
                    // this.active = true;
                }
            });
        }
    }]);

    return MoveElement;
}();

var $movingElement = document.querySelector('.single-product-form-wrapper');
var $endingElement = document.querySelector('.wrapper.info');
var mover = void 0;

if ($movingElement && $endingElement) {
    // mover = new MoveElement($movingElement, $endingElement);
    // mover.install();
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
            var _this8 = this;

            this._firstNames.forEach(function ($firstname) {
                $firstname.value = _this8.firstName;
            });

            this._lastNames.forEach(function ($lastName) {
                $lastName.value = _this8.lastName;
            });

            this._birthdates.forEach(function ($birthdate) {
                $birthdate.value = _this8.birthdate;
            });
        }
    }]);

    return PopulateUserData;
}();