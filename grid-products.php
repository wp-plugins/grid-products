<?php
/*
Plugin Name: Grid Products
Description: Manage product listings
Author: Evan M
Author URI: website-design-firm.com
Plugin URI: website-design-firm.com
Version: 1
*/ 


add_image_size('product-image',240,140, true);
add_image_size('product-image2',195,140, true);


/*

[product cat="liftmaster" hidetitle="yes" featured="yes" view="list" buttontext="View Gallery" des="no" maxdes="25"]

*/




add_action( 'init', 'create_product_post_types' );
function create_product_post_types() {
	 $labels = array(
		'name' => _x( 'Product Categories', 'taxonomy general name' ),
		'singular_name' => _x( 'Product Category', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Product Categories' ),
		'all_items' => __( 'All Product Categories' ),
		'parent_item' => __( 'Parent Product Category' ),
		'parent_item_colon' => __( 'Parent Product Category:' ),
		'edit_item' => __( 'Edit Product Category' ), 
		'update_item' => __( 'Update Product Category' ),
		'add_new_item' => __( 'Add New Product Category' ),
		'new_item_name' => __( 'New Product Category Name' ),
  ); 	
  	register_taxonomy('grid_product_category',array('grid_products'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'product-category' ),
  ));
	register_post_type( 'grid_products',
		array(
			'labels' => array(
				'name' => __( 'Products' ),
				'singular_name' => __( 'Product' ),
				'edit_item'	=>	__( 'Edit Product'),
				'add_new_item'	=>	__( 'Add Product')
			),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'rewrite' => array( 'slug' => 'product', 'with_front' => false ),
			'taxonomies' => array( 'Products '),
			'supports' => array(
			 'title',
	  'editor',
	  'excerpt',
	  'trackbacks',
	  'custom-fields',
	  'revisions',
	  'thumbnail',
	  'author',
	  'page-attributes',
	  )
		)
	);
}	


add_action('restrict_manage_posts','restrict_listings_by_categories');
function restrict_listings_by_categories() {
    global $typenow;
    global $wp_query;
    if ($typenow=='grid_products') {
        
		$tax_slug = 'grid_product_category';
        
		// retrieve the taxonomy object
		$tax_obj = get_taxonomy($tax_slug);
		$tax_name = $tax_obj->labels->name;
		// retrieve array of term objects per taxonomy
		$terms = get_terms($tax_slug);

		// output html for taxonomy dropdown filter
		echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
		echo "<option value=''>Show All $tax_name</option>";
		foreach ($terms as $term) {
			// output each select option line, check against the last $_GET to show the current option selected
			echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
		}
		echo "</select>";
    }
}

add_shortcode('product', 'product_shortcode');
// define the shortcode function
function product_shortcode($atts) {
	extract(shortcode_atts(array(
		'cat'	=> '', 
		'id'	=> '',
		'view' => '',
		'featured' => '',
		'hidetitle' => '',
		'buttontext' => '',
		'des' => '',
		'maxdes' => '',
	), $atts));
		
	// stuff that loads when the shortcode is called goes here
		
		if ( ! empty($id) ) { 
				$grid_products = new WP_Query(array(
				'order'          => 'ASC',
				'orderby' 		 => 'menu_order ID',
				'p'	 			=> $id,
				'post_type'      => 'grid_products',
				'post_status'    => null,
				'posts_per_page'    => 1) );
			} else {		
				$grid_products = new WP_Query(array(
				'order'          => 'ASC',
				'orderby' 		 => 'menu_order ID',
				'grid_product_category'	 => $cat,
				'post_type'      => 'grid_products',
				'post_status'    => null,
				'nopaging' 	=> 1,
				'posts_per_page' => -10) );
			} 
					
			global $wpdb; $catname = $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE slug = '$cat'");
			$product_shortcode = '';
			
			if ( !empty( $cat ) && $hidetitle != 'yes' ) { $product_shortcode .= '<div class="product-catname">' . $catname . '</div>'; }	
			
			
		$thetermid = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE slug = '$cat'");
			
			
		$thetermdes = $wpdb->get_var("SELECT description FROM $wpdb->term_taxonomy WHERE term_taxonomy_id = '$thetermid'");
			
			
			if ( !empty( $cat ) ) { $product_shortcode .= '<div class="productcatdes">'.$thetermdes.'</div>'; }
			
		
			if ($view == 'list' && $featured == 'yes'){
			$product_shortcode .= '<table class="producttable" style="background: #eee;"><tbody>';
			}
			
			if ($view == 'list' && $featured != 'yes'){
			$product_shortcode .= '<table class="producttable"><tbody>';
			}
			
			
			
			/*
			if ($view != 'list' && $featured != 'yes'){
			$product_shortcode .= '<div class="productcategoryholder">';
			}
			
			if ($view != 'list' && $featured == 'yes'){
			$product_shortcode .= '<div class="productcategoryholder"  style="background: #eee;">';
			}
			*/
			
			
				if ($view != 'list' && $featured != 'yes'){
			$product_shortcode .= '<ul class="products">';
			}
			
			if ($view != 'list' && $featured == 'yes'){
			$product_shortcode .= '<ul class="products" style="background: #eee;">';
			}
			
			
			
			
			
			while($grid_products->have_posts()): $grid_products->the_post();
			
		
			
			if ($view == 'list'){
				$theimage=wp_get_attachment_image_src( get_post_thumbnail_id($post->ID) , 'product-image');
	
				$product_shortcode .= '<tr><td align="center"><a href="' . get_permalink() . '"><img src="'.$theimage[0].'" alt="" /></a></td>';
				$product_shortcode .= '<td><div class="product-title"><a href="' . get_permalink() . '">'. get_the_title().'</a></div>';
				
				if ($buttontext == NULL){
				$product_shortcode .= '<div class="product-excerpt">'.get_the_excerpt() . '</div></td></tr><tr><td colspan="2"><div class="productmoretag"><a href="' . get_permalink() . '">Read More</a></div></td></tr>';
	            }else{
	           $product_shortcode .= '<div class="product-excerpt">'.get_the_excerpt() . '</div></td></tr><tr><td colspan="2"><div class="productmoretag"><a href="' . get_permalink() . '">'.$buttontext.'</a></div></td></tr>';
	            }
	$product_shortcode .= '<tr><td colspan="2"><div class="spacer"></div></td></tr>';
	
	}else {
	
	$theimage=wp_get_attachment_image_src( get_post_thumbnail_id($post->ID) , 'product-image2');
		
	$product_shortcode .= '<li>';
	$product_shortcode .= '<a href="' . get_permalink() . '"><img src="'.$theimage[0].'" style="max-width:195px;" alt="" />';
	$product_shortcode .= '<h4>'. get_the_title().'</h4>';
	if ($des != 'no'){
	$paragraph = explode (' ', get_the_excerpt());
	
	if (is_numeric($maxdes)) {
	$paragraph = array_slice ($paragraph, 0, $maxdes);
	} else {
	$paragraph = array_slice ($paragraph, 0, '20');
	}
	
	
 	$paragraph=implode (' ', $paragraph);
	$product_shortcode .= '<p>' .$paragraph. '...</p>';
	} 
	$product_shortcode .= '</a>';
	if ($buttontext == NULL){
		$product_shortcode .= '<div class="productmoreholder"><div class="productmoretag"><a href="' . get_permalink() . '">Read More</a></div></div>';
    }else{
        $product_shortcode .= '<div class="productmoreholder"><div class="productmoretag"><a href="' . get_permalink() . '">'.$buttontext.'</a></div></div>';
    }		
	$product_shortcode .= '</li>';
	
	
	
	
	}


	
			endwhile; // end slideshow loop
			
			
		if ($view == 'list'){	
	$product_shortcode .= '</tbody></table>';
}	
		
		/*
			if ($view != 'list'){
			$product_shortcode .= '</div>';
			}		
		*/
		
			if ($view != 'list'){
			$product_shortcode .= '</ul>';
			}		
			
			
			
			wp_reset_query();
	
	$product_shortcode = do_shortcode( $product_shortcode );
	return (__($product_shortcode));
}//ends the product_shortcode function

add_filter('manage_edit-grid_products_columns', 'product_columns');
function product_columns($columns) {
    $columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Question' ),
		'grid_product_category' => __( 'Categories' ),
		'date' => __( 'Date' )
	);
    return $columns;
}

add_action('manage_posts_custom_column',  'product_show_columns');
function product_show_columns($name) {
    global $post;
    switch ($name) {
        case 'grid_product_category':
            $grid_product_cats = get_the_terms(0, "grid_product_category");
			$cats_html = array();
			if(is_array($grid_product_cats)){
				foreach ($grid_product_cats as $term)
						array_push($cats_html, '<a href="edit.php?post_type=grid_products&grid_product_category='.$term->slug.'">' . $term->name . '</a>');

				echo implode($cats_html, ", ");
			}
			break;
		default :
			break;	
	}
}

// scripts to go in the header and/or footer

function product_init() {

	if( ! is_admin() ) {
		wp_enqueue_script('jquery');
	}
 
  wp_enqueue_style('products',  plugins_url('styles.css', __FILE__), false, $product_version, 'screen'); 
}

add_action('init', 'product_init');



add_action('admin_menu', 'add_grid_product_option_page');

function add_grid_product_option_page() {
	// hook in the options page function
	add_options_page('Grid Products', 'Grid Products', 'manage_options', __FILE__, 'grid_product_options_page');

}



function grid_product_options_page() { 	
	?>
	<div class="wrap" style="width:500px">
		<h2>Grid Products Shortcodes Explained</h2>
		
		<h3>Possible Shortcodes</h3>
		<ul>
		<li><hr><h3>cat</h3> Used to display only produces in a certain category. If not set ALL products from any category will be shown.<br/><br/><b>Usage :</b> cat="category-slug"<br/><br/></li>
		<li><hr><h3>id</h3> Used  to display a single product. <br/><b>* Note: </b>the cat & the id attributes are mutually exclusive. Don't use both in the same shortcode. <br/><br/><b>Usage :</b> id="1234" - where 1234 is the post ID.<br/><br/></li>
		<li><hr><h3>hidetitle</h3> Used in conjunction with the "cat" shortcode to hide the category title incase you would like to use something else instead of the category name.<br/><br/><b>Usage :</b> hidetitle="yes"<br/><br/></li>
		<li><hr><h3>featured</h3> Will set the background of the container to a default light grey.<br/><br/><b>Usage :</b> featured="yes"<br/><br/></li>
		<li><hr><h3>view</h3> The default view is a grid view, if you would prefer to use "list" view set this 
		attribute to equal list <br/><br/><b>Usage :</b> view="list"<br/><br/></li>
		<li><hr><h3>buttontext</h3>The default button text is "Read More" if you would like to change the text use this attribute <br/><br/><b>Usage :</b> buttontext="your text here"<br/><br/></li>
		<li><hr><h3>des</h3> Used to disable the product excerpt in the default grid view. <br/><br/><b>Usage :</b> des="no"<br/><br/></li>
		<li><hr><h3>maxdes</h3>  Used to set the number of words used in the 
		excerpt in the default grid view. (default - 20) must be a number.<br/><br/><b>Usage :</b> maxdes="50"<br/><br/><hr></li>
		</ul>

	</div><!--//wrap div-->
<?php } ?>