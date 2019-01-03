let radio, firstname, lastname, birthday, street, housenumber, phone, email, email_confirm;
radio = document.querySelector('input[type="radio"][value="yes"]').checked = true;

firstname = document.querySelector('#billing_first_name').value = "kevin";
lastname = document.querySelector('#billing_last_name').value = "fechner";
birthday = document.querySelector('#billing_birthday').value = "1989-10-13";
phone = document.querySelector('#billing_phone').value = "01757572737";
email = document.querySelector('#billing_email').value = "developer@test.de";
email_confirm = document.querySelector('#billing_email_confirm').value = "developer@test.de";

are_you_renter_set = true;