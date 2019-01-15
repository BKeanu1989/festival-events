let all_are_you_renter_container;
let are_you_renter_boxes;
let form_checkout;
let fieldsToValidate;

all_are_you_renter_container = document.querySelectorAll('div.checkbox-required[data-identifier]');

if (all_are_you_renter_container) {
    are_you_renter_boxes = Array.from(document.querySelectorAll('input[type="radio"][name^="renter"]'));
    if (are_you_renter_boxes) {
        are_you_renter_boxes.forEach((radioButton) => {
            let key = radioButton.dataset.identifier;
            radioButton.addEventListener('change', () => {
                try {
                    let value = radioButton.value;
                    let findExtraPerson = document.querySelector(`.extra_person__wrapper[data-identifier="${key}"]`);
    
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

jQuery( 'form.variations' ).on( 'found_variation', function() {

    jQuery( '.single_variation .price' ).show();
})
