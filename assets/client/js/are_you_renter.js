let all_are_you_renter_container;
let are_you_renter_boxes;
let form_checkout;
let fieldsToValidate;
let uniqueIdentifiers = new Set();

all_are_you_renter_container = document.querySelectorAll('div.checkbox-required[data-identifier]');
let wm_validationPassed = new WeakMap();

if (all_are_you_renter_container) {
    are_you_renter_boxes = Array.from(document.querySelectorAll('input[type="radio"][name^="renter"'));
    if (are_you_renter_boxes) {
        are_you_renter_boxes.forEach((radioButton) => {
            let key = radioButton.dataset.identifier;
            uniqueIdentifiers.add(key);
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
        let validatedCheckboxes = validateCheckbox();
        if (!validatedCheckboxes) {
            try {
                all_are_you_renter_container[0].scrollIntoView({behavior: 'smooth'});
                all_are_you_renter_container.forEach((x) => {
                    if (x.classList.contains('blink')) {
                        console.log("remove blink class");
                        x.classList.remove('blink');
                    }
                });
                // blinking is set in css
                setTimeout(() => {
                    console.log(all_are_you_renter_container);
                    all_are_you_renter_container.forEach((x) => {
                        // console.log(x);
                        if (!wm_validationPassed.get(x)) {
                            x.classList.add('blink');
                            console.log(x);
                        }
                    })
                }, 500);
                return false;

            } catch(err) {
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
    let validationArray = [];
    let validationPassed;
    uniqueIdentifiers.forEach((data_hash) => {
        try {
            let validationPassed = false;
            let radioContainer = document.querySelector(`div[data-identifier="${data_hash}"]`);
            let radiosPerGroup = Array.from(radioContainer.querySelectorAll('input[type="radio"]'));
            validationPassed = radiosPerGroup.some((element) => {
                return element.checked
            });
            wm_validationPassed.set(radioContainer, validationPassed);
            validationArray.push(validationPassed);
        } catch(err) {
            console.log(err);
        }
    });
    validationPassed = validationArray.every((x) => x);
    return validationPassed;
}

//TODO: client side validation of extra fields
// get all extra_person__wrapper fields without hide_if_yes hide_if_default
fieldsToValidate = document.querySelectorAll('.extra_person__wrapper:not(.hide_if_yes)');
if (fieldsToValidate) {

}