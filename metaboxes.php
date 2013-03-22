<?php


//--------------------------------------------PRODUCT PRICE------------------------------------------
//--------------------------------------------PRODUCT PRICE------------------------------------------
//--------------------------------------------PRODUCT PRICE------------------------------------------

add_action( 'add_meta_boxes', '_grid_product_price_box' );
function _grid_product_price_box() {
    add_meta_box( 
        '_grid_product_price_box',
        __( 'Pricing', '_grid_product_price' ),
        '_grid_product_price_box_content',
        'grid_products',
        'side',
        'high'
    );
}

function _grid_product_price_box_content( $post ) {
	wp_nonce_field( plugin_basename( __FILE__ ), '_grid_product_price_box_content_nonce' );
	echo '<div class="productfieldholder">Price: </div><input type="text" id="_grid_product_price" name="_grid_product_price" placeholder="Product Price" class="admininputfield"value="'.get_post_meta( $_GET[post], '_grid_product_price', true ).'"> <br/>';

	}


add_action( 'save_post', '_grid_product_price_box_save' );
function _grid_product_price_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !wp_verify_nonce( $_POST['_grid_product_price_box_content_nonce'], plugin_basename( __FILE__ ) ) )
	return;

	if ( 'post' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
		return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
		return;
	}


update_post_meta( $post_id, '_grid_product_price', $_POST['_grid_product_price'] );


	
	
}


//--------------------------------------------PRODUCT PRICE END------------------------------------------
//--------------------------------------------PRODUCT PRICE END------------------------------------------
//--------------------------------------------PRODUCT PRICE END------------------------------------------





?>