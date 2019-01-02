'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var chooseLockerButtons = void 0,
    lockerSelect = void 0;

chooseLockerButtons = Array.from(document.querySelectorAll('button.chooseLocker'));
lockerSelect = document.querySelector('select#pa_locker');
if (chooseLockerButtons) {
    chooseLockerButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            var identifier = event.target.dataset.identifier + '-' + currentLanguage;
            var options = Array.from(lockerSelect.options);
            var chosen = options.filter(function (x) {
                if (x.value === identifier) {
                    return x;
                }
            });
            chosen = chosen[0];
            lockerSelect.value = chosen.value;
        });
    });
}
var $confirmEmail = void 0;

$confirmEmail = document.querySelector('#billing_email_confirm');

if ($confirmEmail) {
    $confirmEmail.addEventListener('keydown', function (event) {
        if ((event.metaKey || event.ctrlKey) && event.keyCode == 86) {
            event.preventDefault();
            console.log("CEO doesn't want this to happen");
        }
    });
}
var triggerPopulateUserDataButton = void 0;

triggerPopulateUserDataButton = document.querySelector('#populate_data_for_other_lockers');

if (triggerPopulateUserDataButton) {
    triggerPopulateUserDataButton.addEventListener('click', function () {
        var populateUserData = new PopulateUserData();
    });
}

var PopulateUserData = function () {
    function PopulateUserData() {
        _classCallCheck(this, PopulateUserData);

        this._firstNames;
        this._lastNames;
        this._birtdates;

        this.firstName;
        this.lastName;
        this.birtdate;

        this.init();
        this.setData();
    }

    _createClass(PopulateUserData, [{
        key: 'init',
        value: function init() {
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
    }, {
        key: 'setData',
        value: function setData() {
            var _this = this;

            this._firstNames.forEach(function ($firstname) {
                $firstname.value = _this.firstName;
            });

            this._lastNames.forEach(function ($lastName) {
                $lastName.value = _this.lastName;
            });

            this._birthdates.forEach(function ($birthdate) {
                $birthdate.value = _this.birthdate;
            });
        }
    }]);

    return PopulateUserData;
}();