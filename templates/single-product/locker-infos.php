<?php
/**
 * 
 * @param 
 */
defined( 'ABSPATH' ) || exit;

global $lockerDescription, $withOptional, $product;

$lockers = array_map('trim', explode(',',$product->get_attribute('pa_locker')));

$lockerKeys = [
    "outerDiameter" => __("Außenmaß (HxBxT in cm)", 'festival-events'), 
    "innerDiameter" => __("Innenmaß (HxBxT in cm)", 'festival-events'), 
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
    __("Nicht erlaubt. Bitte halte Dich daran, da bei Überschreitung der 15 Watt die Sicherung rausfliegen wird.", 'festival-events')
],
"L" => [
    __("18 x 40 x 35 cm", 'festival-events'),
    __("25 x 42 x 35 cm", 'festival-events'),
    __("Wertsachen, Tasche oder Rucksack, elektronische Geräte", 'festival-events'),
    [
        __("max. 15 Watt", 'festival-events'),
        __("Ausreichend zum Laden von Handy, Kamera, Powerbank", 'festival-events'),
    ],
    __("Nicht erlaubt. Bitte halte Dich daran, da bei Überschreitung der 15 Watt die Sicherung rausfliegen wird.", 'festival-events')
],
"M High-Voltage" => [
    __("18 x 12 x 35 cm", 'festival-events'),
    __("25 x 14 x 35 cm", 'festival-events'),
    __("Wertsachen, kleine Tasche, kleine elektronische Geräte", 'festival-events'),
    [
        __("max. 90 Watt", 'festival-events'),
        __("Ausreichend zum Laden von Notebook bis 11 Zoll, Soundbox, gleichzeitiges Aufladen mehrerer Handys/Powerbanks", 'festival-events'),
    ],
    __("Erlaubt, beachte bitte jedoch die max. Steckdosenleistung von 90 Watt.", 'festival-events')
], 
"L High-Voltage" => [
    __("18 x 40 x 35 cm", 'festival-events'),
    __("25 x 42 x 35 cm", 'festival-events'),
    __("Wertsachen, Tasche oder Rucksack, elektronische Geräte", 'festival-events'),
    [
        __("max. 90 Watt", 'festival-events'),
        __("Ausreichend zum Laden von Notebook bis 11 Zoll, Soundbox, gleichzeitiges Aufladen mehrerer Handys/Powerbanks", 'festival-events'),
    ],
    __("Erlaubt, beachte bitte jedoch die max. Steckdosenleistung von 90 Watt.", 'festival-events')
], 
"XL" => [
    __("33 x 40 x 48 cm", 'festival-events'),
    __("37 x 43,5 x 48 cm", 'festival-events'),
    __("Wertsachen, größere Tasche/Motorrad-Helm, elektronische Geräte", 'festival-events'),
    [
        __("max. 15 Watt", 'festival-events'),
        __("Ausreichend zum Laden von Handy, Kamera, Powerbank", 'festival-events'),
    ],
    __("Nicht erlaubt. Bitte halte Dich daran, da bei Überschreitung der 15 Watt die Sicherung rausfliegen wird.", 'festival-events')
], 
"XL High-Voltage" => [
    __("33 x 40 x 48 cm", 'festival-events'),
    __("37 x 43,5 x 48 cm", 'festival-events'),
    __("Wertsachen, größere Tasche/Motorrad-Helm, elektronische Geräte", 'festival-events'),
    [
        __("max. 90 Watt", 'festival-events'),
        __("Ausreichend zum Laden von Notebook bis 11 Zoll, Soundbox, gleichzeitiges Aufladen  mehrerer Handys/Powerbanks", 'festival-events'),
    ],
    __("Erlaubt, beachte bitte jedoch die max. Steckdosenleistung von 90 Watt.", 'festival-events')
]];

$givenLockers = [];
foreach($lockers AS $key => $value) {
    $givenLockers[$value] = $lockerInfo[$value];
}

$tableData = [];

foreach($givenLockers AS $key => $value) {
    list($outerDiameter, $innerDiameter, $suitability, $power, $useable) = $givenLockers[$key];
    $tableData["outerDiameter"][$key] = $outerDiameter;
    $tableData["innerDiameter"][$key] = $innerDiameter;
    $tableData["suitability"][$key] = $suitability;
    $tableData["power"][$key] = $power;
    $tableData["useable"][$key] = $useable;
}
?>
<div class="wrapper info">
    <h2 class="wrapper__title">
        <?php echo __('Information zum Schließfach', 'festival-events'); ?>
    </h2>
    <div class="card__container table-responsive">
        <!-- </div> -->
        <table class="table">
            <tr>
                <th></th>
                <?php
                    foreach($lockers AS $key => $value) {
                        echo "<th>{$value}</th>";
                    }
                ?>
            </tr>
            <?php
                foreach($tableData AS $key => $value) {
                    echo "<tr>";
                    switch($key) {
                        case 'outerDiameter':
                            $description = __('Außenmaß (HxBxT in cm)', 'festival-events');
                            break;
                        case 'innerDiameter':
                            $description = __('Innenmaß (HxBxT in cm)', 'festival-events');
                            break;                            
                        case 'suitability':
                            $description = __('Geeignet für', 'festival-events');
                            break;
                        case 'power':
                            $description = __('Steckdosenleistung', 'festival-events');
                            break;
                        case 'useable':
                            $description = __('Mehrfachstecker USB-Verteiler', 'festival-events');
                            break;
                    }
                    echo "<td>{$description}</td>";
                    foreach($tableData[$key] AS $info) {
                        if (is_array($info)) {
                            echo "<td>";
                                echo "<ul>";
                                foreach($info as $listItem) {
                                    echo "<li>{$listItem}</li>";
                                }
                                echo "</ul>";
                            echo "</td>";
                        } else {
                            echo "<td>{$info}</td>";
                        }
                    }
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
</div>
</div> <!-- close container -->

