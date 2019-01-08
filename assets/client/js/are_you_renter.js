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

//TODO: client side validation of extra fields