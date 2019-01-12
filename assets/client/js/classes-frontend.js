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
        // this.allRadioButtons = document.querySelector()
        this.allRadioContainer = Array.from(document.querySelectorAll('div[data-identifier]'));
    }

    uniqueHashes() {
        // just to be sure via set
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

// function validateCheckbox() {
//     let validationArray = [];
//     let validationPassed;
//     uniqueIdentifiers.forEach((data_hash) => {
//         try {
//             let validationPassed = false;
//             let radioContainer = document.querySelector(`div[data-identifier="${data_hash}"]`);
//             let radiosPerGroup = Array.from(radioContainer.querySelectorAll('input[type="radio"]'));
//             validationPassed = radiosPerGroup.some((element) => {
//                 return element.checked
//             });
//             wm_validationPassed.set(radioContainer, validationPassed);
//             validationArray.push(validationPassed);
//         } catch(err) {
//             console.log(err);
//         }
//     });
//     validationPassed = validationArray.every((x) => x);
//     return validationPassed;
// // }


// function handleInvalidCheckbox() {
//     try {

//         // blinking is set in css
//         scrollIntoViewNBlink(all_are_you_renter_container);
//         return false;
//     } catch(err) {
//         console.log(err);
//     }    
// }
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
        // this.groupsToValidate
        console.log(wm_validationPassed);
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