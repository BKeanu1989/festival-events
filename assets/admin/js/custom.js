let buttons;

buttons = Array.from(document.querySelectorAll('.foreign-language button'));

buttons.forEach((x) => x.disabled = true);

let $festivalStart;
let $festivalEnd;

$festivalStart = document.querySelector('#_festival_start');
$festivalEnd = document.querySelector('#_festival_end');

if ($festivalStart && $festivalEnd) {
    try {
        new Cleave('#_festival_start', {
            date: true,
            datePattern: ['Y', 'm', 'd'],
            delimiter: '-'
        })
    
        new Cleave('#_festival_end', {
            date: true,
            datePattern: ['Y', 'm', 'd'],
            delimiter: '-'
        })
    } catch(err) {
        console.log(err);
    }
}