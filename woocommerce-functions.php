<?php 


function my_custom_woocommerce_theme_support() {
    add_theme_support( 'woocommerce', array(
        // . . .
        // thumbnail_image_width, single_image_width, etc.
 
        // Product grid theme settings
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
             
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ) );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
 
add_action( 'after_setup_theme', 'my_custom_woocommerce_theme_support' );

// add_action( 'save_post', 'ws_set_images_in_languages', 5 );
// function ws_set_images_in_languages( $post_id ) {

// 	// If this is just a revision, don't update.
// 	if ( wp_is_post_revision( $post_id ) ) return;

    
//     // If this isn't a 'product' post, don't update it.
//     $post_type = get_post_type($post_id);
//     if ( 'product' != $post_type ) return;


//     $gallery = get_post_meta($post_id, '_product_image_gallery', true);
//     $thumb = get_post_meta($post_id, '_thumbnail_id', true);
//     global $wpdb;
//     foreach (icl_get_languages('skip_missing=0&orderby=code') as $key2 => $value) {
        
//         if ($key2 != ICL_LANGUAGE_CODE || $gallery === 'instock' || $thumb === 'no' ) {

//             $trans_id = $wpdb->get_var( 
//                 $wpdb->prepare( 
//                     "SELECT t.element_id FROM rst_icl_translations as t
//                     WHERE t.language_code = %s
//                         AND t.trid = (
//                             SELECT t2.trid FROM rst_icl_translations as t2 WHERE t2.element_id = %d AND t.element_type = 'post_product'
//                         )
//                         AND t.element_type = 'post_product'
//                     ",
//                     $key2,
//                     $post_id
//                 )
//             );
//             if ( $trans_id !== NULL ) {

//                 if ( $gallery === 'instock' || ( strpos($gallery, ',') === false && !is_int( $gallery ) ) ) {
//                     $gallery = '';
//                 }
//                 if ( $thumb === 'no' || !is_int( $thumb ) ) {
//                     $thumb = '';
//                 }
                
//                 if( !$wpdb->update($wpdb->postmeta,array('meta_value' => $gallery),array( 'meta_key' => '_product_image_gallery','post_id'	=> $trans_id)) ){
//                     $wpdb->insert(
//                         $wpdb->postmeta,
//                         array(
//                             'meta_key' => 	'_product_image_gallery',
//                             'meta_value'=> 	$gallery,
//                             'post_id'	=> 	$trans_id
//                         )
//                     );
//                 }
//                 if( !$wpdb->update($wpdb->postmeta,array('meta_value' => $thumb),array( 'meta_key' => '_thumbnail_id','post_id'	=> $trans_id)) ){
//                     $wpdb->insert(
//                         $wpdb->postmeta,
//                         array(
//                             'meta_key' => 	'_thumbnail_id',
//                             'meta_value'=> 	$thumb,
//                             'post_id'	=> 	$trans_id
//                         )
//                     );
//                 }
//             }
//         }
//     }
// }

// add_action( 'wp_insert_post_data', 'ws_set_opposite_upsell', 5, 2 );
// function ws_set_opposite_upsell($data, $postarr) {
//     $post_id = $postarr['ID'];
//     // If this is just a revision, don't update.
// 	if ( wp_is_post_revision( $post_id ) ) return;
    
    
//     // If this isn't a 'product' post, don't update it.
//     $post_type = get_post_type($post_id);
//     if ( 'product' != $post_type ) return;
    
//     $oldUpsells = get_post_meta( $post_id, '_upsell_ids', true);
//     if ( isset($postarr['upsell_ids']) ) {

//         $differencesToRemove = array_diff( $oldUpsells, $postarr['upsell_ids'] );

//         foreach ($differencesToRemove as $key => $id) {
//             $productUpsells = get_post_meta($id, '_upsell_ids', true);
//             if ( is_array( $productUpsells ) && ( count( $productUpsells ) > 0 && in_array( $post_id, $productUpsells ) ) ) {
//                 if (($key = array_search($post_id, $productUpsells)) !== false) {
//                     unset($productUpsells[$key]);
//                 }
//                 update_post_meta( $id, '_upsell_ids', $productUpsells );
//             }
//         }
        
//         if ( count($postarr['upsell_ids']) > 0 ) {
//             $differencesToAdd = array_diff( $postarr['upsell_ids'], $oldUpsells );
//             foreach ( $differencesToAdd as $key => $upsell_id) {
//                 $productUpsells = get_post_meta($upsell_id, '_upsell_ids', true);
//                 if ( is_array( $productUpsells ) && ( ( count( $productUpsells ) > 0 && !in_array( $post_id, $productUpsells ) ) || count( $productUpsells ) == 0 ) ) {
//                     $productUpsells[] = $post_id;
//                     update_post_meta( $upsell_id, '_upsell_ids', $productUpsells );
//                 }
//             }
//         }
//     } else {
//         foreach ($oldUpsells as $key => $id) {
//             $productUpsells = get_post_meta($id, '_upsell_ids', true);
//             if ( is_array( $productUpsells ) && ( count( $productUpsells ) > 0 && in_array( $post_id, $productUpsells ) ) ) {
//                 if (($key = array_search($post_id, $productUpsells)) !== false) {
//                     unset($productUpsells[$key]);
//                 }
//                 update_post_meta( $id, '_upsell_ids', $productUpsells );
//             }
//         }
//     }
//     update_post_meta( $post_id, '_upsell_ids', $postarr['upsell_ids'] );
// }
//Tootekategooriaid mitmekeelselt sünkroonides, ei seo WPML All import neid ära.
// Siin fix.

// add_action( 'pmxi_saved_post', 'ws_set_taxonomy_translations' );

// function ws_set_taxonomy_translations($post_id){
//     global $wpdb;
//     $term = get_term( $post_id, 'product_cat', ARRAY_A );
//     if($term){
//         $unique_key = $wpdb->get_results(
//             $wpdb->prepare( 
//                 "SELECT `unique_key`
//                 FROM `{$wpdb->prefix}pmxi_posts`
//                 WHERE `post_id` = %d;",
//                 $post_id
//             ),
//             ARRAY_A
//         );
//         $post_ids = $wpdb->get_results(
//             $wpdb->prepare('SELECT post_id
//                 FROM `{$wpdb->prefix}pmxi_posts`
//                 WHERE `unique_key` = %d;',
//                 $unique_key[0]['unique_key']
//             ),
//             ARRAY_A
//         );
//         $trid = NULL;
//         foreach ($post_ids as $key => $id) {
//             $translation = $wpdb->get_results(
//                 $wpdb->prepare(
//                     'SELECT *
//                     FROM `{$wpdb->prefix}icl_translations`
//                     WHERE `element_id` = %d;',
//                     $id['post_id']
//                 ),
//                 ARRAY_A
//             );
//             if ($translation[0]['language_code'] == 'et') {
//                 $trid = intval($translation[0]['trid']);
//                 echo $trid;
//                 break;
//             }
//         }
//         foreach ($post_ids as $key => $id) {
//             $updated1 = $wpdb->query(
//                 $wpdb->prepare(
//                     'UPDATE `{$wpdb->prefix}icl_translations` SET `trid` = %d WHERE `element_id` = %d AND `language_code` NOT LIKE "et";',
//                     $trid, $id['post_id']
//                 )
//             );
//             $updated2 = $wpdb->query(
//                 $wpdb->prepare(
//                     'UPDATE `{$wpdb->prefix}icl_translations` SET `source_language_code` = "et" WHERE `element_id` = %d AND `language_code` NOT LIKE "et";',
//                     $id['post_id']
//                 )
//             );
//         }
//     }
// }


// Soodustuse protsent

// add_filter('woocommerce_sale_flash', 'ws_custom_sale_flash');
// function ws_custom_sale_flash($text) {
//     global $product;
//     $percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
//     return '<span class="onsale">'.$percentage.'%</span>';  
// } 

// wrapper div-id


// remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
// add_action( 'woocommerce_before_shop_loop_item_title', 'ws_add_product_thumbnail_container', 10);
// function ws_add_product_thumbnail_container() {
//     $size = 'shop_catalog';
//     global $post, $woocommerce;
//     $output = '<div class="imagewrapper">';

//     if ( has_post_thumbnail() ) {               
//         $output .= get_the_post_thumbnail( $post->ID, $size );              
//     }                       
//     $output .= '</div>';
//     echo  $output;
// }
// remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
// add_action( 'woocommerce_shop_loop_item_title', 'ws_add_product_title_container', 10);
// function ws_add_product_title_container() {
//     global $product;
//     $output = '<div class="titlewrapper"><h2>';
//     $output .= $product->get_title();              
//     $output .= '</h2></div>';
//     echo  $output;
// }

// add_action( 'woocommerce_after_shop_loop_item_title', 'ws_display_sku_after_loop_title', 9 );
// function ws_display_sku_after_loop_title() {

//     global $product;

//     if ( $product->get_sku() ) {
//         echo '<div class="skuwrapper"><p>Kood: '.$product->get_sku().'</p></div>';
//     }

// }