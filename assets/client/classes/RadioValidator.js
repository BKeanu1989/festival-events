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