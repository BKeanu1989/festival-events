let chooseLockerButtons, lockerSelect;

chooseLockerButtons = Array.from(document.querySelectorAll('button.chooseLocker'));
lockerSelect = document.querySelector('select#pa_locker');
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