<?php 
$product_1=array( 'post_title' => $data2[0]->label,
'post_content' => $data2[0]->label,
'post_status' => 'publish',
'post_type' => "product",
'post_name'=>$data2[0]->label);

$post_id = wp_insert_post( $product_1 );
wp_set_object_terms( $post_id, 'simple', 'product_type' );

update_post_meta( $post_id, '_price', $data2[0]->price_ttc );
update_post_meta( $post_id, '_stock_status', 'instock');
update_post_meta( $post_id, '_sku', $data2[0]->barcode );




?>
