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
// get all extra_person__wrapper fields without hide_if_yes hide_if_default
let fieldsToValidate =  document.querySelectorAll('.extra_person__wrapper:not(.hide_if_yes)');