<?php
/**
 * 
 * @param 
 */
defined( 'ABSPATH' ) || exit;

global $lockerDescription, $withOptional, $product;

$lockers = array_map('trim', explode(',',$product->get_attribute('pa_locker')));

$lockerInfo = [
"M" => [__("Außenmaß: 18cm x 12cm x 35cm (BxHxT), Innenmaß: 25cm x 14cm x 35 cm (BxHxT)"), __("passend für Wertsachen, kleine Tasche, kleine elektronische Geräte"), __("Steckdose max. 15 Watt"), __("Zum Laden von Handy, Kamera, Powerbank")],
"L" => [__("Außenmaß: 18cm x 40cm x 35cm (BxHxT), Innenmaß: 25cm x 42cm x 35 cm (BxHxT)"), __("passend für Wertsachen, Tasche/Rucksack, elektronische Geräte"), __("Steckdose max. 15 Watt"), __("Zum Laden von Handy, Kamera, Powerbank")],
"M High-Voltage" => [__("Außenmaß: 18cm x 12cm x 35cm (BxHxT), Innenmaß: 25cm x 14cm x 35 cm (BxHxT)"), __("passend für Wertsachen, kleine Tasche, kleine elektronische Geräte"), __("Steckdose max. 90 Watt"), __("Zum Laden von Notebook bis 11 Zoll, Soundbox.")], 
"L High-Voltage" => [__("Außenmaß: 18cm x 40cm x 35cm (BxHxT), Innenmaß: 25cm x 42cm x 35 cm (BxHxT)"), __("passend für Wertsachen, Tasche/Rucksack, elektronische Geräte"), __("Steckdose max. 90 Watt"), __("Zum Laden von Notebook bis 15 Zoll, Soundbox.")], 
"XL" => ["", "", "", ""], 
"XL High-Voltage" => ["", "", "", ""]];
?>
<div class="wrapper">
    <h2 class="wrapper__title">
        <?php echo __('Information zum Schließfach', 'festival-events'); ?>
    </h2>
    <div class="card__container">
        <?php 
            //TODO: slugify value
            // M High Voltage -> m-hv etc
            foreach($lockers as $key => $value) { 
                    $identifier = fe_slugify_locker($value);
                ?>
                <div class="card locker_infos" data-identifier="<?php echo $identifier ?>">
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