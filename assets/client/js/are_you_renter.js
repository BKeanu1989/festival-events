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
            handleInvalidCheckbox();
        }
        
        let allFieldsValid = handleFieldsToValidate();
        console.log("should scroll @invalid");
        if (!allFieldsValid || !validatedCheckboxes) return false;
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

function handleInvalidCheckbox() {
    try {

        // blinking is set in css
        scrollIntoViewNBlink(all_are_you_renter_container);
        return false;
    } catch(err) {
        console.log(err);
    }    
}

//TODO: client side validation of extra fields
// get all extra_person__wrapper fields without hide_if_yes hide_if_default
function handleFieldsToValidate() {
    let validationPassed_array = [];
    fieldsToValidate = document.querySelectorAll('.extra_person__wrapper:not(.hide_if_yes)');
    if (fieldsToValidate) {
        fieldsToValidate.forEach((singleWrapper) => {
            let returnValue;
            let $firstname = singleWrapper.querySelector('[name^="extra_person-first_name"]');
            let $lastname = singleWrapper.querySelector('[name^="extra_person-last_name"]');
            let $bday = singleWrapper.querySelector('[name^="extra_person-birthdate"]');

            let singleWrapperInputs = [$firstname, $lastname, $bday];

            returnValue = handleEmptyInput(singleWrapperInputs);
            validationPassed_array.push(returnValue);
            attachInputEvent(singleWrapperInputs);
        });
    }
    scrollIntoViewNBlink(fieldsToValidate);

    return validationPassed_array.every((x) => x);
}

function handleEmptyInput($inputs = []) {
    let validationPassed_array = [];
    $inputs.forEach(($input) => {
        try {
            let single_passed_validation;
            if ($input.value === '') {
                single_passed_validation = false;
                $input.classList.add('invalid');
            } else {
                single_passed_validation = true;
            }
            validationPassed_array.push(single_passed_validation);
            wm_validationPassed.set($input, single_passed_validation);
        } catch(err) {
            console.log(err);
        }
    });
    let validationPassed = validationPassed_array.every((x) => x);
    return validationPassed;
}

function attachInputEvent($inputs = []) {
    $inputs.forEach(($input) => {
        $input.addEventListener('input', (event) => {
            try {
                if ($input.classList.contains('invalid')) {
                    $input.classList.remove('invalid');
                }
            } catch(err) {
                console.log(err);
            }
        })
    })
}

function resetValidationClassesNScroll($elements) {
    let firstErrorElement = false;
    $elements.forEach((x) => {
        if (x.classList.contains('blink')) {
            x.classList.remove('blink');
        }
        console.log("are you here");
        if (wm_validationPassed.get(x) === false && !firstErrorElement) {
            firstErrorElement = true;
            console.log("should scroll to", x);
        }
        if (firstErrorElement) {
            x.scrollIntoView({behavior: 'smooth'});
        }
    });
}

function scrollIntoViewNBlink($elements) {
    try {
        resetValidationClassesNScroll($elements);
    
        setTimeout(() => {
            $elements.forEach((x) => {
                // console.log(x);
                if (wm_validationPassed.get(x) === false) {
                    x.classList.add('blink');
                }
            })
        }, 500);
    } catch(err) {
        console.log(err);
    }
}