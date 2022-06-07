<?php 
  function delete_variations( $product_id, $force_delete = false ) {
	if ( ! is_numeric( $product_id ) || 0 >= $product_id ) {
		return;
	}

	$variation_ids = wp_parse_id_list(
		get_posts(
			array(
				'post_parent' => $product_id,
				'post_type'   => 'product_variation',
				'fields'      => 'ids',
				'post_status' => array( 'any', 'trash', 'auto-draft' ),
				'numberposts' => -1, // phpcs:ignore WordPress.VIP.PostsPerPage.posts_per_page_numberposts
			)
		)
	);

	if ( ! empty( $variation_ids ) ) {
		foreach ( $variation_ids as $variation_id ) {
			if ( $force_delete ) {
				do_action( 'woocommerce_before_delete_product_variation', $variation_id );
				wp_delete_post( $variation_id, true );
				do_action( 'woocommerce_delete_product_variation', $variation_id );
			} else {
				wp_trash_post( $variation_id );
				do_action( 'woocommerce_trash_product_variation', $variation_id );
			}
		}
	}

	delete_transient( 'wc_product_children_' . $product_id );
}

  delete_variations($post_id); 
?>    