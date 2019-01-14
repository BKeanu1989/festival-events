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
<div class="wrapper">
    <div class="card__container">
        <div class="card miscellaneous-info">
            <div class="card__title">
                <h2><?php _e('Allgemeine Informationen', 'festival-events'); ?></h2>
            </div>
            <div class="card__image">
                <!-- display image here -->
                <!-- TODO: srcset-->
                <!-- <img src="https://festivalsafeboxen.bigboxberlin.de/wp-content/uploads/2017/12/BigBoxBerlin_SafeBOX_WRS-7.jpg" alt=""> -->
            </div>
            <div class="card__body">
                <div class="card__body--item card__locations">
                    <h3><i class="fas fa-users"></i><?php _e('Standorte', 'festival-events'); ?></h3>
                    <ul>
                    <?php foreach($get_opening_times_per_location[0] AS $key => $value) { ?>
                        <?php echo "<li>". ucfirst($key) . "</li>" ?>
                    <?php } ?>
                    </ul>
                </div>
                <div class="card__body--item card__opening_times">
                    <h3><i class="fas fa-door-open"></i><?php _e('Öffnungszeiten', 'festival-events') ?></h3>
                    <ul>
                    <?php foreach($get_opening_times_per_location[0] AS $key => $value) { ?>
                        <?php echo "<li>". ucfirst($value) . "</li>" ?>
                    <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>