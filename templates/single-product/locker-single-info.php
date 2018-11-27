<?php
/**
 * 
 * @param 
 */

global $lockerDescription, $withOptional;
global $variation;


$lockerInfo = ["M" => ["test", "", "", ""], "L" => ["", "", "", ""], "M HV" => ["", "", "", ""], "L HV" => ["", "a", "", ""], "XL" => ["", "", "", ""], "XL HV" => ["", "", "", ""]];
?>

<div class="card">
    <div class="card__lockertype">
        <?php echo $variation['attributes']['attribute_schliessfaecher']; ?>
    </div>
    <div class="card__locker--duration-price">
        <div class="price">
            <?php echo $variation['display_price']; ?>
        </div>
        <div class="duration">
            <?php echo $variation['attributes']['attribute_dauer']; ?>
        </div>
    </div>
    <div class="card__body">
        <?php 
            $locker_infos = [];
            if (isset($variation['attributes'])) {
                $locker_infos = $lockerInfo[$variation['attributes']['attribute_schliessfaecher']];
            } 
            foreach($locker_infos AS $key => $locker_info_block) {
                echo "<div class='card__body--block'>{$locker_info_block}</div>";
            }
        ?>
        <!-- pull information for locker - from plugin settings (later) -->
    </div>

    
</div>