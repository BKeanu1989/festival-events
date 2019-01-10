let are_you_renter_container;
let are_you_renter;
let are_you_renter_set = false;
let form_checkout;

// are_you_renter_container = document.querySelector('#renter_field');

// if (are_you_renter_container) {
    are_you_renter = Array.from(document.querySelectorAll('input[type="radio"][name^="renter"'));
    if (are_you_renter) {
        are_you_renter.forEach((radioButton) => {
            console.log(radioButton);
            radioButton.addEventListener('change', () => {
                are_you_renter_set = true;
                let value = radioButton.value;
                let key = radioButton.dataset.identifier;
                console.log("key:", key);
                let findExtraPerson = document.querySelector(`.extra_person__wrapper[data-identifier="${key}"`);

                if (value === 'yes') {
                    // do nothing xD
                    findExtraPerson.classList.add('hide_if_yes');
                    findExtraPerson.classList.add('hide_if_default');
                    findExtraPerson.classList.toggle('fadeIn');
                }
                if (value === 'no') {
                    findExtraPerson.classList.remove('hide_if_yes');
                    findExtraPerson.classList.remove('hide_if_default');
                    findExtraPerson.classList.toggle('fadeIn');
                }
            })
        })
    }
// }
form_checkout = document.querySelector('form[name="checkout"]');
if (form_checkout) {
    jQuery( 'form.checkout' ).on( 'checkout_place_order', function() {
        if (!are_you_renter_set) {
            if (are_you_renter_container.classList.contains('blink')) {
                are_you_renter_container.classList.remove('blink');
            }

            are_you_renter_container.scrollIntoView({behavior: 'smooth'});
            // blinking is set in css
            setTimeout(() => {
                are_you_renter_container.classList.add('blink');
            }, 500);
            return false;
        }
        return true;
    });
}

//TODO: client side validation of extra fields
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
            productForm.scrollIntoView({behavior: 'smooth'});
        })
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