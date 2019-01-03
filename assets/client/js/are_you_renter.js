let are_you_renter;

are_you_renter = Array.from(document.querySelectorAll('input[type="radio"]'));

if (are_you_renter) {
    are_you_renter.forEach((radioButton) => {
        radioButton.addEventListener('change', () => {
            console.log("changed");
            let value = radioButton.value;
            if (value === 'yes') {
                // do nothing xD
                let visibleFields = Array.from(document.querySelectorAll('.extra_person_field:not(.hide_if_yes)'));
        
                visibleFields.forEach((x) => {
                    x.classList.add('hide_if_yes');
                    x.classList.add('hide_if_default')
                })
            }
            if (value === 'no') {
                // toggle class hide_if_yes hide_if_default
                let hiddenFields = Array.from(document.querySelectorAll('.hide_if_yes.hide_if_default'));
        
                hiddenFields.forEach((x) => {
                    x.classList.remove('hide_if_yes');
                    x.classList.remove('hide_if_default')
                })
            }
        })
    })
}