<?php
/**
 * 
 * 
 */

defined( 'ABSPATH' ) || exit;

global $product;

$product_id = $product->get_id();
$get_opening_times_per_location = get_post_meta($product_id, '_opening_items_per_location');

// component
?>

<div class="card miscellaneous-info">
    <div class="card__image">
        <!-- display image here -->
    </div>
    <div class="card__locations">
        card_locations
    </div>
    <div class="card__opening_times">
        opening times
    </div>
</div>

<div class="card our-service">
    <div class="card__image">
        <!-- this is hardcoded for now -->
        <ul>
            <li><span class="icon">key</span><span class="description">Alle Schließfächer sind mit einer Steckdose ausgestattet.</span></li>
            <li><span class="icon">alert</span><span class="description">High-Voltage Schließfächer für stromintensive Geräte wie z.B. JBL Soundboxen oder Notebooks.</span></li>
            <li><span class="icon">heart</span><span class="description">Du kannst jederzeit an Dein Schließfach. Unser Personal ist rund um die Uhr für Dich da.</span></li>
            <li><span class="icon">like</span><span class="description">Dein Schließfach-Inhalt ist mit bis zu 500 Euro versichert.</span></li>
        </ul>
    </div>
</div>