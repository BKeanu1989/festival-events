<?php
/**
 * 
 * 
 */

defined( 'ABSPATH' ) || exit;

global $product;

$product_id = $product->get_id();
// $get_opening_times_per_location = get_post_meta($product_id, '_opening_items_per_location');
$get_opening_times_per_location = get_post_meta($product_id, '_opening_times');

// component
?>

<div class="card__container">
    <div class="card miscellaneous-info">
        <div class="card__title">
            <h2><?php _e('Standorte & Öffnungszeiten', 'festival-events'); ?></h2>
        </div>
        <div class="card__image">
            <!-- display image here -->
            <img src="https://festivalsafeboxen.bigboxberlin.de/wp-content/uploads/2017/12/BigBoxBerlin_SafeBOX_WRS-7.jpg" alt="">
        </div>
        <div class="card__body">
            <div class="card__locations">
                <h3><?php _e('Standorte:', 'festival-events'); ?></h3>
                <ul>
                <?php foreach($get_opening_times_per_location[0] AS $key => $value) { ?>
                    <?php echo "<li>". ucfirst($key) . "</li>" ?>
                <?php } ?>
                </ul>
            </div>
            <div class="card__opening_times">
                <h3><?php _e('Öffnungszeiten', 'festival-events') ?></h3>
                <?php foreach($get_opening_times_per_location[0] AS $key => $value) { ?>
                    <?php echo "<li>". ucfirst($value) . "</li>" ?>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="card our-service">
        <div class="card__title">
            <h2><?php _e('Unser Service für dich', 'festival-events'); ?></h2>
        </div>
        <div class="card__image">
            <!-- this is hardcoded for now -->
            <img src="https://festivalsafeboxen.bigboxberlin.de/wp-content/uploads/2016/02/strom.jpg" alt="">
        </div>
        <div class="card__body">
            <ul>
                <li><span class="icon">key</span><span class="description">Alle Schließfächer sind mit einer Steckdose ausgestattet.</span></li>
                <li><span class="icon">alert</span><span class="description">High-Voltage Schließfächer für stromintensive Geräte wie z.B. JBL Soundboxen oder Notebooks.</span></li>
                <li><span class="icon">heart</span><span class="description">Du kannst jederzeit an Dein Schließfach. Unser Personal ist rund um die Uhr für Dich da.</span></li>
                <li><span class="icon">like</span><span class="description">Dein Schließfach-Inhalt ist mit bis zu 500 Euro versichert.</span></li>
            </ul>
        </div>
    </div>
</div>