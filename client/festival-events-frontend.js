let all_are_you_renter_container;
let are_you_renter_boxes;
let form_checkout;
let fieldsToValidate;

all_are_you_renter_container = document.querySelectorAll('div.checkbox-required[data-identifier]');

if (all_are_you_renter_container) {
    are_you_renter_boxes = Array.from(document.querySelectorAll('input[type="radio"][name^="renter"'));
    if (are_you_renter_boxes) {
        are_you_renter_boxes.forEach((radioButton) => {
            let key = radioButton.dataset.identifier;
            radioButton.addEventListener('change', () => {
                try {
                    let value = radioButton.value;
                    let findExtraPerson = document.querySelector(`.extra_person__wrapper[data-identifier="${key}"`);
    
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
                } catch(err) {
                    console.log(err);
                }
            })
        })
    }
}

form_checkout = document.querySelector('form[name="checkout"]');
if (form_checkout) {
    jQuery( 'form.checkout' ).on( 'checkout_place_order', function() {

        let radioValidator = new RadioValidator();
        radioValidator.init();

        let inputValidator = new InputValidator();
        inputValidator.init();

        if (wm_validationPassed.get(radioValidator) === false || wm_validationPassed.get(inputValidator) === false) return false;

    });
}
let chooseLockerButtons, lockerSelect, productForm;

chooseLockerButtons = Array.from(document.querySelectorAll('button.chooseLocker'));
lockerSelect = document.querySelector('select#pa_locker');
productForm = document.querySelector('form.cart.variations_form');

if (chooseLockerButtons) {
    chooseLockerButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            let identifier = event.target.dataset.identifier + '-' + currentLanguage;
            let options = Array.from(lockerSelect.options);
            let chosen = options.filter((x) => {
                if (x.value === identifier) {
                    return x;
                }
            })
            chosen = chosen[0];
            lockerSelect.value = chosen.value;
            jQuery(".variations_form").trigger('check_variations');
            jQuery( '.single_variation .price' ).show();
            productForm.scrollIntoView({behavior: 'smooth'});
        })
    });
}
let wm_validationPassed = new WeakMap();

class Validator {
    constructor() {

    }

    init() {

    }

    scrollIntoView($element) {
        $element.scrollIntoView({behavior: 'smooth'});
    }
}
class RadioValidator extends Validator {
    constructor() {
        super();
        this.allRadioContainer;
        this.allUniqueHashes;
        this.groupedRadioButtons = [];
    }

    init() {
        this.allRadioContainer();
        this.uniqueHashes();
        this.groupRadioButtons();

        this.validationHandler();
        this.handleInvalid();
        this.scrollToFirstInvalid();
    }

    groupRadioButtons() {
        this.allUniqueHashes.forEach((hash) => {
            let radioContainer = document.querySelector(`div[data-identifier="${hash}"]`);
            this.groupedRadioButtons.push(radioContainer);
        })
    }

    allRadioContainer() {
        this.allRadioContainer = Array.from(document.querySelectorAll('div[data-identifier]'));
    }

    uniqueHashes() {
        this.allUniqueHashes = new Set(this.allRadioContainer.map((x) => {
            return x.dataset.identifier;
        }));
    }

    validationHandler() {
        let validationArray = [];
        let passedValidation;
        this.groupedRadioButtons.forEach((singleGroup) => {
            try {
                let radioButtons = Array.from(singleGroup.querySelectorAll('input[type="radio"]'));
                let validationPassed = false;
                validationPassed = radioButtons.some((radioInput) => {
                    return radioInput.checked;
                });
                wm_validationPassed.set(singleGroup, validationPassed);
                validationArray.push(validationPassed);
            } catch(err) {
                console.log(err);
            }
        })
        passedValidation = validationArray.every((x) => x);
        wm_validationPassed.set(this, passedValidation);
    }

    handleInvalid() {
        this.groupedRadioButtons.forEach((singleGroup) => {
            try {
                if (singleGroup.classList.contains('blink')) {
                    singleGroup.classList.remove('blink');
                }
                setTimeout(() => {
                    if (wm_validationPassed.get(singleGroup) === false) {
                        singleGroup.classList.add('blink');
                    }
                }, 500);
            } catch(err) {
                console.log(err);
            }
        })
    }

    scrollToFirstInvalid() {
        try {
            this.groupedRadioButtons.some((singleGroup) => {
                try {
                    if (wm_validationPassed.get(singleGroup) === false) {
                        super.scrollIntoView(singleGroup);
                        return true;
                    }
                } catch(err) {
                    console.log(err);
                }
            })
        } catch(err) {
            console.log(err);
        }
    }
}
class InputValidator extends Validator {
    constructor() {
        super();
        this.groupsToValidate;
        this.validationPassed_array = [];
    }

    init() {
        this.groupsToValidate = Array.from(document.querySelectorAll('.extra_person__wrapper:not(.hide_if_yes)'));

        this.validationHandler();
        this.handleInvalid();

    }

    validationHandler() {
        this.groupsToValidate.forEach((singleGroup) => {
            try {
                this.validateInputGroup(singleGroup);
            } catch(err) {
                console.log(err)
            }
        })
        let passedValidation = this.validationPassed_array.every((x) => x);
        wm_validationPassed.set(this, passedValidation);
    }

    validateInputGroup($group) {
        let passed;
        let $firstname = $group.querySelector('[name^="extra_person-first_name"]');
        let $lastname = $group.querySelector('[name^="extra_person-last_name"]');
        let $bday = $group.querySelector('[name^="extra_person-birthdate"]');

        let groupInputs = [$firstname, $lastname, $bday];

        groupInputs.forEach($input => {
            this.handleEmptyInput($input);
        });

        passed = groupInputs.every((input) => {
            return input.value;
        })

        this.validationPassed_array.push(passed);
        wm_validationPassed.set($group, passed);
    }

    handleInvalid() {
        this.groupsToValidate.forEach((singleContainer) => {
            try {
                let singleGroup = singleContainer.querySelector('.extra_person__wrapper--input-wrapper');
                if (singleGroup.classList.contains('blink')) {
                    singleGroup.classList.remove('blink');
                }
                setTimeout(() => {
                    if (wm_validationPassed.get(singleContainer) === false) {
                        singleGroup.classList.add('blink');
                    }
                }, 500);
            } catch(err) {
                console.log(err)
            }
        });

        this.scrollToFirstInvalid();
    }

    scrollToFirstInvalid() {
        try {
            this.groupsToValidate.some((singleGroup) => {
                if (wm_validationPassed.get(singleGroup) === false) {
                    super.scrollIntoView(singleGroup);
                    return true;
                }
            })
        } catch(err) {
            console.log(err);
        }
    }

    attachInputEvent($input) {
        $input.addEventListener('input', (event) => {
            try {
                if ($input.classList.contains('invalid')) {
                    $input.classList.remove('invalid');
                }
            } catch(err) {
                console.log(err);
            }
        })
    }

    handleEmptyInput($input) {
        if ($input.value === '') {
            if ($input.classList.contains('invalid')) {
                $input.classList.remove('invalid');
            }
            $input.classList.add('invalid');
            this.attachInputEvent($input);
        }
    }
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
let $confirmEmail;

$confirmEmail = document.querySelector('#billing_email_confirm');

if ($confirmEmail) {
    $confirmEmail.addEventListener('keydown', function(event) {
        if ((event.metaKey || event.ctrlKey) && event.keyCode == 86) {
            event.preventDefault();
            console.log("CEO doesn't want this to happen");
        }
    })
}
let triggerPopulateUserDataButton;

triggerPopulateUserDataButton = document.querySelector('#populate_data_for_other_lockers');

if (triggerPopulateUserDataButton) {
    triggerPopulateUserDataButton.addEventListener('click', () => {
        const populateUserData = new PopulateUserData();
    })
}


class PopulateUserData {
    constructor() {
        this._firstNames;
        this._lastNames;
        this._birtdates;

        this.firstName;
        this.lastName;
        this.birtdate;

        this.init();
        this.setData();
    }

    init() {
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

    setData() {
        this._firstNames.forEach($firstname => {
            $firstname.value = this.firstName;
        });

        this._lastNames.forEach($lastName => {
            $lastName.value = this.lastName;
        });

        this._birthdates.forEach($birthdate => {
            $birthdate.value = this.birthdate;
        });
    }
}