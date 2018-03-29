<?php 

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