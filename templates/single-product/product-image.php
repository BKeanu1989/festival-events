<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.2
 */

defined( 'ABSPATH' ) || exit;

global $product;
$product_id = $product->get_id();
// TODO: implement banner text
$bannerText = get_post_meta($product_id, '_banner_text',true);

$post_thumbnail_id = $product->get_image_id();
$img_src = wp_get_attachment_image_url($post_thumbnail_id, 'full');
$img_srcset = wp_get_attachment_image_srcset($post_thumbnail_id, 'full');
?>
<div class="banner__wrapper">
    <div class="banner__text">
        <p class="banner__text--wrapper">
            <?php echo $bannerText; ?>
        </p>
    </div>
    <img src="<?php echo esc_url( $img_src ); ?>"
    srcset="<?php echo esc_attr( $img_srcset ); ?>"
    >
</div>