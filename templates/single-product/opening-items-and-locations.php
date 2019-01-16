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
            <div class="card__body">
                <?php foreach($get_opening_times_per_location[0] AS $key => $value) { ?>
                    <div class="card__body--item card__locations">
                        <h3><?php _e('Standort', 'festival-events'); echo " " . ucfirst($key) ?></h3>
                        <?php echo "<p>". ucfirst($value) . "</p>" ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>