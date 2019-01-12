<?php
/**
 * 
 * @param 
 */
defined( 'ABSPATH' ) || exit;

global $lockerDescription, $withOptional, $product;

$lockers = array_map('trim', explode(',',$product->get_attribute('pa_locker')));

$lockerKeys = ["size" => __("Größe", 'festival-events'), "suitability" => __('Eignung', 'festival-events'), "power" => __('Leistung', 'festival-events'), "useable" => __('Nutzbar für', 'festival-events')];

$lockerInfo = [
"M" => [__("Außenmaß: 18cm x 12cm x 35cm (BxHxT), Innenmaß: 25cm x 14cm x 35 cm (BxHxT)"), __("passend für Wertsachen, kleine Tasche, kleine elektronische Geräte"), __("Steckdose max. 15 Watt"), __("Zum Laden von Handy, Kamera, Powerbank")],
"L" => [__("Außenmaß: 18cm x 40cm x 35cm (BxHxT), Innenmaß: 25cm x 42cm x 35 cm (BxHxT)"), __("passend für Wertsachen, Tasche/Rucksack, elektronische Geräte"), __("Steckdose max. 15 Watt"), __("Zum Laden von Handy, Kamera, Powerbank")],
"M High-Voltage" => [__("Außenmaß: 18cm x 12cm x 35cm (BxHxT), Innenmaß: 25cm x 14cm x 35 cm (BxHxT)"), __("passend für Wertsachen, kleine Tasche, kleine elektronische Geräte"), __("Steckdose max. 90 Watt"), __("Zum Laden von Notebook bis 11 Zoll, Soundbox.")], 
"L High-Voltage" => [__("Außenmaß: 18cm x 40cm x 35cm (BxHxT), Innenmaß: 25cm x 42cm x 35 cm (BxHxT)"), __("passend für Wertsachen, Tasche/Rucksack, elektronische Geräte"), __("Steckdose max. 90 Watt"), __("Zum Laden von Notebook bis 15 Zoll, Soundbox.")], 
"XL" => ["", "", "", ""], 
"XL High-Voltage" => ["", "", "", ""]];

// foreach($lockerInfo AS $key => $value) {
//     $arraySize = count($lockerInfo[$key]);

//     for ($i = 0; $i < $arraySize; $i++) {
//         $temp = $lockerInfo[$key][$i];
//         $lockerInfo[$key][$lockerKeys[$i]] = $temp;
//         unset($lockerInfo[$key][$i]);
//     }
// }

$givenLockers = [];
foreach($lockers AS $key => $value) {
    $givenLockers[$value] = $lockerInfo[$value];
}

$tableData = [];

foreach($givenLockers AS $key => $value) {
    list($size, $suitability, $power, $useable) = $givenLockers[$key];
    $tableData["size"][$key] = $size;
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
                        case 'size':
                            $description = __('Größe', 'festival-events');
                            break;
                        case 'suitability':
                            $description = __('Eignung', 'festival-events');
                            break;
                        case 'power':
                            $description = __('Leistung', 'festival-events');
                            break;
                        case 'useable':
                            $description = __('Nutzbar für', 'festival-events');
                            break;
                    }
                    echo "<td>{$description}</td>";
                    foreach($tableData[$key] AS $info) {
                        echo "<td>{$info}</td>";
                    }
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
</div>