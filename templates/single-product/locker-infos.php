<?php
/**
 * 
 * @param 
 */
defined( 'ABSPATH' ) || exit;

global $lockerDescription, $withOptional, $product;

$lockers = array_map('trim', explode(',',$product->get_attribute('pa_locker')));

$lockerKeys = [
    "outerDiameter" => __("Außenmaß (BxHxT in cm)", 'festival-events'), 
    "innerDiameter" => __("Innenmaß (BxHxT in cm)", 'festival-events'), 
    "suitability" => __('Geeignet für', 'festival-events'), 
    "power" => __('Steckdosenleistung', 'festival-events'), 
    "useable" => __('Mehrfachstecker USB-Verteiler', 'festival-events')
];

$lockerInfo = [
"M" => [
    __("18 x 12 x 35 cm", 'festival-events'),
    __("25 x 14 x 35 cm", 'festival-events'),
    __("Wertsachen, kleine Tasche, kleine elektronische Geräte", 'festival-events'),
    [
        __("max. 15 Watt", 'festival-events'),
        __("Ausreichend zum Laden von Handy, Kamera, Powerbank", 'festival-events')
    ],
    __("Nicht erlaubt. Bitte halte Dich daran, da bei Überschreitung der 15 Watt die Sicherung rausfliegen wird.", 'festival-events'),
    "priority" => 1
],
"L" => [
    __("18 x 40 x 35 cm", 'festival-events'),
    __("25 x 42 x 35 cm", 'festival-events'),
    __("Wertsachen, Tasche oder Rucksack, elektronische Geräte", 'festival-events'),
    [
        __("max. 15 Watt", 'festival-events'),
        __("Ausreichend zum Laden von Handy, Kamera, Powerbank", 'festival-events'),
    ],
    __("Nicht erlaubt. Bitte halte Dich daran, da bei Überschreitung der 15 Watt die Sicherung rausfliegen wird.", 'festival-events'),
    "priority" => 3
],
"M High-Voltage" => [
    __("18 x 12 x 35 cm", 'festival-events'),
    __("25 x 14 x 35 cm", 'festival-events'),
    __("Wertsachen, kleine Tasche, kleine elektronische Geräte", 'festival-events'),
    [
        __("max. 90 Watt", 'festival-events'),
        __("Ausreichend zum Laden mehrerer Handys/Powerbanks, 11” Notebooks, große Soundbox", 'festival-events'),
    ],
    __("Erlaubt, beachte bitte jedoch die max. Steckdosenleistung von 90 Watt.", 'festival-events'),
    "priority" => 2
], 
"L High-Voltage" => [
    __("18 x 40 x 35 cm", 'festival-events'),
    __("25 x 42 x 35 cm", 'festival-events'),
    __("Wertsachen, Tasche oder Rucksack, elektronische Geräte", 'festival-events'),
    [
        __("max. 90 Watt", 'festival-events'),
        __("Ausreichend zum Laden mehrerer Handys/Powerbanks, 11” Notebooks, große Soundbox", 'festival-events'),
    ],
    __("Erlaubt, beachte bitte jedoch die max. Steckdosenleistung von 90 Watt.", 'festival-events'),
    "priority" => 4
], 
"XL" => [
    __("33 x 40 x 48 cm", 'festival-events'),
    __("37 x 43,5 x 48 cm", 'festival-events'),
    __("Wertsachen, größere Tasche/Motorrad-Helm, elektronische Geräte", 'festival-events'),
    [
        __("max. 15 Watt", 'festival-events'),
        __("Ausreichend zum Laden von Handy, Kamera, Powerbank", 'festival-events'),
    ],
    __("Nicht erlaubt. Bitte halte Dich daran, da bei Überschreitung der 15 Watt die Sicherung rausfliegen wird.", 'festival-events'),
    "priority" => 5

], 
"XL High-Voltage" => [
    __("33 x 40 x 48 cm", 'festival-events'),
    __("37 x 43,5 x 48 cm", 'festival-events'),
    __("Wertsachen, größere Tasche/Motorrad-Helm, elektronische Geräte", 'festival-events'),
    [
        __("max. 90 Watt", 'festival-events'),
        __("Ausreichend zum Laden mehrerer Handys/Powerbanks, 11” Notebooks, große Soundbox", 'festival-events'),
    ],
    __("Erlaubt, beachte bitte jedoch die max. Steckdosenleistung von 90 Watt.", 'festival-events'),
    "priority" => 6
]];

function cmp($a, $b) 
{
    return ($a['priority'] < $b['priority']) ? -1 : 1;
}

function buildLockerInfo($lockers, $lockerInfo) {
    $givenLockers = [];
    foreach($lockers AS $key => $lockerName) {
        $givenLockers[$lockerName] = $lockerInfo[$lockerName];
    }
    uasort($givenLockers, "cmp");
    return $givenLockers;
}

$givenLockers = buildLockerInfo($lockers, $lockerInfo); 

?>
<div class="wrapper info">
    <h2 class="wrapper__title">
        <?php echo __('Information zum Schließfach', 'festival-events'); ?>
    </h2>


    <div class="card__container lockers">
        <?php foreach($givenLockers AS $lockerType => $lockerInfo) { 
            list($outerDiameter, $innerDiameter, $suitability, $power, $useable, $priority) = $givenLockers[$lockerType];
        ?> 
            <div class="card">
                <div class="card__title">
                    <?php echo $lockerType; ?>
                </div>
                <div class="card__body">
                    <div class="card__body--group">
                        <div class="card__body--group--description">
                            <?php echo $lockerKeys['outerDiameter']; ?>
                        </div>
                        <div class="card__body--group--info">
                            <?php echo $outerDiameter; ?>
                        </div>
                    </div>
                    <div class="card__body--group">
                        <div class="card__body--group--description">
                            <?php echo $lockerKeys['innerDiameter']; ?>
                        </div>
                        <div class="card__body--group--info">
                            <?php echo $innerDiameter; ?>
                        </div>
                    </div>
                    <div class="card__body--group">
                        <div class="card__body--group--description">
                            <?php echo $lockerKeys['suitability']; ?>
                        </div>
                        <div class="card__body--group--info">
                            <?php echo $suitability; ?>
                        </div>
                    </div>
                    <div class="card__body--group">
                        <div class="card__body--group--description">
                            <?php echo $lockerKeys['power']; ?>
                        </div>
                        <div class="card__body--group--info">
                            <?php foreach($power AS $single) {
                                echo $single;
                            }; ?>
                        </div>
                    </div>
                    <div class="card__body--group">
                        <div class="card__body--group--description">
                            <?php echo $lockerKeys['useable']; ?>
                        </div>
                        <div class="card__body--group--info">
                            <?php echo $useable; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</div> <!-- close container -->

