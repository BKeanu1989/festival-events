<?php
/**
 * 
 * @param 
 */
defined( 'ABSPATH' ) || exit;

global $lockerDescription, $withOptional;
global $variation;
global $lockers;

$lockers = getLockers($variations);
$uniqueLockers = array_unique($lockers);

$lockerInfo = [
"M" => ["Außenmaß: 18cm x 12cm x 35cm (BxHxT), Innenmaß: 25cm x 14cm x 35 cm (BxHxT)", "passend für Wertsachen, kleine Tasche, kleine elektronische Geräte", "Steckdose max. 15 Watt", "Zum Laden von Handy, Kamera, Powerbank"],
"L" => ["Außenmaß: 18cm x 40cm x 35cm (BxHxT), Innenmaß: 25cm x 42cm x 35 cm (BxHxT)", "passend für Wertsachen, Tasche/Rucksack, elektronische Geräte", "Steckdose max. 15 Watt", "Zum Laden von Handy, Kamera, Powerbank"],
"M HV" => ["Außenmaß: 18cm x 12cm x 35cm (BxHxT), Innenmaß: 25cm x 14cm x 35 cm (BxHxT)", "passend für Wertsachen, kleine Tasche, kleine elektronische Geräte", "Steckdose max. 90 Watt", "Zum Laden von Notebook bis 11 Zoll, Soundbox."], 
"L HV" => ["Außenmaß: 18cm x 40cm x 35cm (BxHxT), Innenmaß: 25cm x 42cm x 35 cm (BxHxT)", "passend für Wertsachen, Tasche/Rucksack, elektronische Geräte", "Steckdose max. 90 Watt", "Zum Laden von Notebook bis 15 Zoll, Soundbox."], 
"XL" => ["", "", "", ""], 
"XL HV" => ["", "", "", ""]];
?>
<div class="wrapper">
    <h2 class="wrapper__title">
        <?php echo __('Information zum Schließfach', 'festival-events'); ?>
    </h2>
    <div class="card__container">
        <?php 
            foreach($uniqueLockers as $key => $value) { ?>
                <div class="card locker_infos">
                    <div class="card__title">
                        <?php echo $value; ?>
                    </div>
                    <div class="card__body">
                        <?php
                            foreach($lockerInfo[$value] AS $description) { ?>
                                <div class="card__body--description">
                                    <?php echo $description; ?>
                                </div>
                            <?php }
                        ?>
                    </div>
                    <div class="card__footer">
                    </div>
                </div>
            <?php } ?>
    </div>
</div>