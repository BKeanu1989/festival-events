let are_you_renter_container;
let are_you_renter;
let are_you_renter_set = false;
let form_checkout;

are_you_renter_container = document.querySelector('#renter_field');
if (are_you_renter_container) {
    are_you_renter = Array.from(are_you_renter_container.querySelectorAll('input[type="radio"]'));
    if (are_you_renter) {
        are_you_renter.forEach((radioButton) => {
            radioButton.addEventListener('change', () => {
                are_you_renter_set = true;
                let value = radioButton.value;
                if (value === 'yes') {
                    // do nothing xD
                    let visibleFields = Array.from(document.querySelectorAll('.extra_person_field:not(.hide_if_yes)'));
                    
                    visibleFields.forEach((x) => {
                        x.classList.add('hide_if_yes');
                        x.classList.add('hide_if_default');
                        x.classList.toggle('fadeIn');
                    })
                    // are_you_renter_container.classList.toggle('blink');

                }
                if (value === 'no') {
                    // toggle class hide_if_yes hide_if_default
                    let hiddenFields = Array.from(document.querySelectorAll('.hide_if_yes.hide_if_default'));
                    
                    hiddenFields.forEach((x) => {
                        let span = x.querySelector('span.optional');
                        if (span) {
                            let parent = span.parentNode;
                            // the following is only done for appearance - no functionality is added
                            let abbr = document.createElement('abbr');
                            abbr.classList.add('required');
                            // TODO: screen reader foreign languages...
                            abbr.setAttribute('title', 'erforderlich');
                            let abbr_content = document.createTextNode('*');
                            abbr.appendChild(abbr_content);
                            parent.replaceChild(abbr, span);
                        }
    
                        x.classList.remove('hide_if_yes');
                        x.classList.remove('hide_if_default');
                        x.classList.toggle('fadeIn');
                    })
                }
            })
        })
    }
}
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
            // TODO: force update of form (price) -> if everything is set
            productForm.scrollIntoView({behavior: 'smooth'});
        })
    });
}
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