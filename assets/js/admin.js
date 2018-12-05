// detour to fix wc bus

let prices;
prices = Array.from(document.querySelectorAll('[name^="variable_regular_price"]'));

// can i use safari "Array.from"
// prototyping
prices.forEach((x) => x.value = '5');

// attribute_schliessfaecher[0]