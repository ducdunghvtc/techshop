<?php
define( 'THEME_URL', get_stylesheet_directory() );
define ( 'CORE', THEME_URL . "/core" );
require_once( CORE . "/init.php" );

if ( !isset($content_width) ) {
	$content_width = 620;
}

if ( !function_exists('wp_theme_setup') ) {
	function wp_theme_setup() {

		/* textdomain */
		$language_folder = THEME_URL . '/languages';
		load_theme_textdomain( 'wp', $language_folder );
		/* link RSS len <head> **/
		add_theme_support( 'automatic-feed-links' );

		/* Theme post thumbnail */
		add_theme_support( 'post-thumbnails' );

		/* Post Format */
		add_theme_support( 'post-formats', array(
			'image',
			'video',
			'gallery',
			'quote',
			'link'
		) );

		/* Theme title-tag */
		add_theme_support( 'title-tag' );

		/* Theme menu */

		register_nav_menus( array(
	    	'header-menu' => __( 'Header Menu', 'text_domain' ),
	    	'primary-menu' => __( 'Primary Menu', 'text_domain' ),
	    	'footer-menu'  => __( 'Footer Menu', 'text_domain' ),
	    	'contact-header'  => __( 'Contact Header', 'text_domain' ),
	    	'contact-footer'  => __( 'Contact Footer', 'text_domain' ),
		) );

		/* sidebar */
		// $sidebarThumbnail = array(
		// 	'name' => __('Image sidebar', 'wp'),
		// 	'id' => 'thumbnail-sidebar',
		// 	'description' => __('Default sidebar'),
		// 	'class' => 'thumbnail-sidebar',
		// );
		// register_sidebar( $sidebarThumbnail );
		
		$sidebar = array(
			'name' => __('Main Sidebar', 'wp'),
			'id' => 'main-sidebar',
			'description' => __('Default sidebar'),
			'class' => 'main-sidebar',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>'
		);
		register_sidebar( $sidebar );

	}
	add_action( 'init', 'wp_theme_setup' );
}

remove_action( 'wp_head', '_wp_render_title_tag', 1 );


/*--------
TEMPLATE FUNCTIONS */
if (!function_exists('wp_header')) {
	function wp_header() { ?>
		<div class="site-name">
			<?php
				global $tp_options;

				if( $tp_options['logo-on'] == 0 ) :
			?>
				<?php
					if ( is_home() ) {
						printf( '<h1><a href="%1$s" title="%2$s">%3$s</a></h1>',
						get_bloginfo('url'),
						get_bloginfo('description'),
						get_bloginfo('sitename') );
					} else {
						printf( '<p><a href="%1$s" title="%2$s">%3$s</a></p>',
						get_bloginfo('url'),
						get_bloginfo('description'),
						get_bloginfo('sitename') );
					}
				?>

			<?php
				else :
			?>
				<img src="<?php echo $tp_options['logo-image']['url']; ?>" />
		<?php endif; ?>
		</div>
		<div class="site-description"><?php bloginfo('description'); ?></div><?php
	}
}

/**
*menu
**/
if ( !function_exists('wp_menu') ) {
	function wp_menu($menu) {
		$menu = array(
			'theme_location' => $menu,
			'container' => 'nav',
			'container_class' => $menu,
			'items_wrap' => '<ul id="%1$s" class="%2$s sf-menu">%3$s</ul>'
		);
		wp_nav_menu( $menu );
	}
}

add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);

function special_nav_class ($classes, $item) {
    if (in_array('current-menu-item', $classes) ){
        $classes[] = 'active';
    }
    return $classes;
}

/**
*pagination
**/
if ( !function_exists('wp_pagination') ) {
	function wp_pagination() {
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return '';
		} ?>
		<nav class="pagination" role="navigation">
			<?php if ( get_next_posts_link() ) : ?>
				<div class="next"><?php next_posts_link( __('Next', 'wp') ); ?></div>
			<?php endif; ?>
			<?php if ( get_previous_posts_link() ) : ?>
				<div class="prev"><?php previous_posts_link( __('Previous', 'wp') ); ?></div>
			<?php endif; ?>
		</nav>
	<?php }
}

/**
*thumbnail
**/
if ( !function_exists('wp_thumbnail') ) {
	function wp_thumbnail($size) {
		if( !is_single() && has_post_thumbnail() && !post_password_required() || has_post_format('image') ) : ?>
		<figure class="post-thumbnail"><?php the_post_thumbnail( $size ); ?></figure>
	<?php endif; ?>
	<?php }
}

/**
*wp_entry_header = title post
**/
if ( !function_exists('wp_entry_header') ) {
	function wp_entry_header() { ?>
		<?php if ( is_single() ) : ?>
			<!-- <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1> -->
			<h2 class="entry-title"><?php the_title(); ?></h2>
		<?php else : ?>
					<h2 class="entry-title"><?php the_title(); ?></h2>
		<?php endif; ?>
	<?php }
}

/**
*wp_entry_meta = get posts
**/
if ( !function_exists('wp_entry_meta') ) {
	function wp_entry_meta() { ?>
		<?php if ( !is_page() ) : ?>
			<div class="entry-meta">
			<?php
				printf( __('<span class="author">Posted by %1$s', 'wp'),
				get_the_author() );

				printf( __('<span class="date-published"> at %1$s', 'wp'),
				get_the_date() );

				printf( __('<span class="category"> in %1$s ', 'wp'),
				get_the_category_list( ',' ) );

				if ( comments_open() ) :
					echo '<span class="meta-reply">';
						comments_popup_link(
							__('Leave a comment', 'wp'),
							__('One comment', 'wp'),
							__('% comments', 'wp'),
							__('Read all comments', 'wp')
							);
					echo '</span>';
				endif;
			?>
			</div>
		<?php endif; ?>
	<?php }
}

/**
*wp_entry_content = post/page
**/
if ( !function_exists('wp_entry_content') ) {
	function wp_entry_content() {
		if( !is_single() && !is_page() ) {
			the_excerpt();
		} else {
			the_content();

			/* Phan trang trong single */
			$link_pages = array(
				'before' => __('<p>Page: ', 'wp'),
				'after' => '</p>',
				'nextpagelink' => __('Next Page', 'wp'),
				'previouspagelink' => __('Previous Page', 'wp')
			);
			wp_link_pages( $link_pages );
		}
	}
}

// function wp_readmore() {
// 	return '<a class="read-more" href="'. get_permalink( get_the_ID() ) . '">'.__('...[Read More]', 'wp').'</a>';
// }
// add_filter('excerpt_more', 'wp_readmore');


/**
*wp_entry_tag = show tag
**/
if ( !function_exists('wp_entry_tag') ) {
	function wp_entry_tag() {
		if ( has_tag() ) :
			echo '<div class="entry-tag">';
			printf( __('Tagged in %1$s', 'wp'), get_the_tag_list( '', ',' ) );
			echo '</div>';
		endif;
	}
}
/*=====import style.css=====*/
function wp_style() {
	wp_register_style( 'style', get_template_directory_uri() . "/style.css", 'all' );
	wp_enqueue_style('style');

	wp_register_style( 'common-style', get_template_directory_uri() . "/common/css/common.css", 'all' );
	wp_enqueue_style('common-style');

	wp_register_style( 'main-style', get_template_directory_uri() . "/common/css/style.css", 'all' );
	wp_enqueue_style('main-style');

	wp_register_style( 'slick-style', get_template_directory_uri() . "/common/css/slick.css", 'all' );
	wp_enqueue_style('slick-style');

	wp_register_style( 'range-price-style', get_template_directory_uri() . "/common/css/jquery.range.css", 'all' );
	wp_enqueue_style('range-price-style');

	wp_register_script( 'jquery-3-script', "https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js", array('jquery') );
	wp_enqueue_script('jquery-3-script');

	wp_register_script( 'slick-script', get_template_directory_uri() . "/common/js/slick.min.js", array('jquery') );
	wp_enqueue_script('slick-script');

	wp_register_script( 'range-min-script', get_template_directory_uri() . "/common/js/jquery.range-min.js", array('jquery') );
	wp_enqueue_script('range-min-script');

	wp_register_script( 'range-script', get_template_directory_uri() . "/common/js/jquery.range.js", array('jquery') );
	wp_enqueue_script('range-script');

	wp_register_script( 'main-script', get_template_directory_uri() . "/common/js/common.js", array('jquery') );
	wp_enqueue_script('main-script');
	
}
add_action('wp_enqueue_scripts', 'wp_style');

function nd_dosth_theme_setup() {
    add_theme_support( 'title-tag' );  
    add_theme_support( 'custom-logo' );
}
add_action( 'after_setup_theme', 'nd_dosth_theme_setup');

function remove_core_updates (){ 
	global $wp_version ; return ( object ) array ( 'last_checked' => time (), 'version_checked' => $wp_version ,); 
} 
add_filter ( 'pre_site_transient_update_core' , 'remove_core_updates' ); 
add_filter ( 'pre_site_transient_update_plugins' , 'remove_core_updates' ); 
add_filter ( 'pre_site_transient_update_themes' , 'remove_core_updates' );



function clean_custom_menu( $theme_location ) {
	if ( ($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location]) ) {
		$menu = get_term( $locations[$theme_location], 'nav_menu' );
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		$menu_list  = '<nav class="contact-nav">';
		$menu_list .= '<ul class="d-flex flex-wrap align-items-md-center list-style-none flex-column flex-md-row">' ."\n";
	
			
		foreach ($menu_items as $menu_item) {
			$link = $menu_item->url;
			$title = $menu_item->title;
			$menu_list .= '<li class="fs-12 fs-lg-14 text-secondary my-1h my-md-0">';
			$menu_list .= '<a class="d-flex align-items-start align-items-md-center fs-12 fs-lg-14 text-secondary" target="_blank" href="'.$link.'">';
		
			if (get_field('icon', $menu_item->ID)) {
				$menu_list .= '<img src="'.get_field('icon', $menu_item->ID).'">';
			}
		
			$menu_list .= $title.'</a>';
			$menu_list .= '</li>';
		}
		
		
		$menu_list .= '</ul></nav>' ."\n";
		
	
	} else {
		$menu_list = '<!-- no menu defined in location "'.$theme_location.'" -->';
	}
	echo $menu_list;
}


// Custom Post Type

// add_action('init', 'create_post_type');
// function create_post_type()
// {
// 	//------------------------------------------
// 	// Main Visual
// 	//------------------------------------------
// 	$main_visual = 'main_visual';
// 	register_post_type($main_visual,
// 		array(
// 			'labels' => array(
// 				'name'          => __('Main Visual'),
// 				'singular_name' => __('main visual'), 
// 			),
// 			'public'        => true,
// 			'menu_position' => 5,
// 			'rewrite'       => array('with_front' => true),
// 			'supports'      => array('title', 'thumbnail')
// 		)
// 	);

// }

// get post where id
// add_action('wp_ajax_idPost', 'get_post_detail');
// add_action('wp_ajax_nopriv_idPost', 'get_post_detail');
// function get_post_detail() {
//    $idPost = isset($_POST['idPost']) ? (int)$_POST['idPost'] : 0;
//    $getPost = array(
//       'post_type' => 'post',
//       'post_status' => 'publish',
//       'p' => $idPost
//    );
//    $getPost = new WP_Query( $getPost );
//    if($getPost->have_posts()) : 
//          while ( $getPost->have_posts() ) : $getPost->the_post(); 
//             $urlThumbnail = get_the_post_thumbnail_url();
//             $title = get_the_title();
//             $content = get_the_content();
//             if(!$urlThumbnail):
// 				$urlThumbnail = get_template_directory_uri().'/common/images/default.jpg';
//             endif;
// 			echo "
// 				<div class='modal-header'>
// 					<h1 class='ttl text-center w-100 fs-24 fs-lg-32'>{$title}</h1>
// 					<button type='button' class='close'></button>
// 				</div>
// 				<div class='modal-body'>
// 					<figure class='thumbnail order-lg-2 col-lg-6 pl-lg-4 mb-1 mb-lg-0'>
// 						<img src='{$urlThumbnail}' alt='{$title}'>
// 					</figure>
// 					<div class='content-post order-lg-1 col-lg-6'>
// 						{$content}
// 					</div>
// 				</div>
// 			";
//          endwhile;
//       endif;
//    die(); 
// }

function percentSale($price, $salePrice) {
	$percent = ($salePrice * 100) / $price;
	$percentSale = 100 - $percent;
	return number_format($percentSale);
}

function custom_theme_setup() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'custom_theme_setup' );

function hk_product_new_badge() {
   global $product;
   $newness_days = 30; // Number of days the badge is shown
   $created = strtotime( $product->get_date_created() );
   if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
      echo '<div class="new-tag w-40 bg-third d-flex align-items-center justify-content-center text-light fs-12">' . esc_html__( 'New', 'woocommerce' ) . '</div>';
   }
}

// single product page
add_action( 'woocommerce_single_product_summary', 'hk_single_product_new_badge', 1 );  
function hk_single_product_new_badge() {
   global $product;
   $newness_days = 30; // Number of days the badge is shown
   $created = strtotime( $product->get_date_created() );
   if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
      echo '<span class="hk-itsnew">' . esc_html__( 'Mới', 'woocommerce' ) . '</span>';
   }
}

add_filter( 'woocommerce_sale_flash', 'add_percentage_to_sale_badge', 20, 3 );
function add_percentage_to_sale_badge( $html, $post, $product ) {
    if( $product->is_type('variable')){
        $percentages = array();

        // Get all variation prices
        $prices = $product->get_variation_prices();

        // Loop through variation prices
        foreach( $prices['price'] as $key => $price ){
            // Only on sale variations
            if( $prices['regular_price'][$key] !== $price ){
                // Calculate and set in the array the percentage for each variation on sale
                $percentages[] = round(100 - ($prices['sale_price'][$key] / $prices['regular_price'][$key] * 100));
            }
        }
        // We keep the highest value
        $percentage = max($percentages) . '%';
    } else {
        $regular_price = (float) $product->get_regular_price();
        $sale_price    = (float) $product->get_sale_price();

        $percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';
    }
    return '<div class="price-sale w-40 bg-secondary d-flex align-items-center justify-content-center text-light fs-12">' . esc_html__( '', 'woocommerce' ) . ' ' . "-". $percentage . '</div>';
	
}

// display an 'Out of Stock' label on archive pages
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_stock', 10 );
function woocommerce_template_loop_stock() {
    global $product;
    if ( ! $product->managing_stock() && ! $product->is_in_stock() )
        echo '<p class="stock out-of-stock">Out of Stock</p>';
}

add_action('woocommerce_after_shop_loop_item_title','change_loop_ratings_location', 2 );
function change_loop_ratings_location(){
    remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating', 5 );
    add_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_rating', 8 );
}



// BAT TRINH SOAN THAO CU CUA WORDPRESS
add_filter('use_block_editor_for_post', '__return_false');

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );
	


// filter products

add_action('wp_ajax_myfilter', 'filter_products'); // wp_ajax_{ACTION HERE} 
add_action('wp_ajax_nopriv_myfilter', 'filter_products');

function filter_products(){
	//wp_quick_view();
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => 9,
	);

	$args['tax_query'] = array( 'relation' => 'AND' );
	$all_terms = array();
	$all_tags = array();
	$all_colors = array();
	
	$regions = get_terms( array( 'taxonomy' => 'product_cat' ) );
	$colors = get_terms( array( 'taxonomy' => 'pa_colors' ) );
	$tags = get_terms( array( 'taxonomy' => 'product_tag' ) );
	

	$selectedSortBy = $_POST['orderby'];

	if ($selectedSortBy == 'popularity') {
		$args['orderby'] = 'popularity';
	} elseif ($selectedSortBy == 'rating') {
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = '_wc_average_rating';
		$args['order'] = 'DESC';
	} elseif ($selectedSortBy == 'date') {
		$args['orderby'] = 'date';
	} elseif ($selectedSortBy == 'price') {
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = '_price';
		$args['order'] = 'ASC';
	} elseif ($selectedSortBy == 'price-desc') {
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = '_price';
		$args['order'] = 'DESC';
	} else {
		$args['orderby'] = 'menu_order';
	}

	// for taxonomies / categories
	if( $regions ) {
		foreach( $regions as $region ) {
			if( $region->parent == 0 ) {
				if( isset( $_POST['region_' . $region->term_id ] ) && $_POST['region_' . $region->term_id] == 'on' ){
					$all_terms[] = $region->term_id;
					}
				foreach( $regions as $subcategory ) {
					if($subcategory->parent == $region->term_id) {
						if( isset( $_POST['region_' . $subcategory->term_id ] ) && $_POST['region_' . $subcategory->term_id] == 'on' ){
							$all_terms[] = $subcategory->term_id;
						}
					}
				}
			}
		}
		
		if( count( $all_terms ) > 0 ) {
			$args['tax_query'][] = array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms'=> $all_terms,
				)
			);
		}
	}


	// for taxonomies / color
	if( $colors ) {
		foreach( $colors as $color ) {
			if( isset( $_POST['color_' . $color->term_id ] ) && $_POST['color_' . $color->term_id] == 'on' )
				$all_colors[] = $color->term_id;
		}
		
		if( count( $all_colors ) > 0 ) {
			$args['tax_query'][] = array(
				array(
					'taxonomy' => 'pa_colors',
					'field' => 'id',
					'terms'=> $all_colors,
				)
			);
		}
	}
	
	// for taxonomies / tags
	if( $tags ) {
		foreach( $tags as $tag ) {
			if( $tag->parent == 0 ) {
				if( isset( $_POST['tag_' . $tag->term_id ] ) && $_POST['tag_' . $tag->term_id] == 'on' ){
					$all_tags[] = $tag->term_id;
				}
			}
		}
		
		if( count( $all_tags ) > 0 ) {
			$args['tax_query'][] = array(
				array(
					'taxonomy' => 'product_tag',
					'field' => 'id',
					'terms'=> $all_tags,
				)
			);
		}
	}
		
	
	// create $args['meta_query'] array if one of the following fields is filled
	if( isset( $_POST['price_min'] ) && $_POST['price_min'] || isset( $_POST['price_max'] ) && $_POST['price_max'] || isset( $_POST['featured_image'] ) && $_POST['featured_image'] == 'on' )
		$args['meta_query'] = array( 'relation'=>'AND' ); // AND means that all conditions of meta_query should be true
 
	// if both minimum price and maximum price are specified we will use BETWEEN comparison
	if( isset( $_POST['price_min'] ) && $_POST['price_min'] && isset( $_POST['price_max'] ) && $_POST['price_max'] ) {
		$args['meta_query'][] = array(
			'key' => '_price',
			'value' => array( $_POST['price_min'], $_POST['price_max'] ),
			'type' => 'numeric',
			'compare' => 'between'
		);
	} else {
		// if only min price is set
		if( isset( $_POST['price_min'] ) && $_POST['price_min'] )
			$args['meta_query'][] = array(
				'key' => '_price',
				'value' => $_POST['price_min'],
				'type' => 'numeric',
				'compare' => '>'
			);
 
		// if only max price is set
		if( isset( $_POST['price_max'] ) && $_POST['price_max'] )
			$args['meta_query'][] = array(
				'key' => '_price',
				'value' => $_POST['price_max'],
				'type' => 'numeric',
				'compare' => '<'
			);
	}
	
	
	$query = new WP_Query( $args );
	$countp = $query ->found_posts;
	global $product; 
	echo '<div class="product d-flex flex-wrap">';
	if( $query->have_posts() ): while( $query->have_posts() ) : $query->the_post(); ?>
			<div class="product-items position-relative bd-fifth-1" data-id="<?php echo get_the_ID(); ?>">
				<?php $product = wc_get_product(get_the_ID()); ?>
				<div class="tag d-flex">
					<?php if($product->is_on_sale()) : ?>
						<div class="price-sale w-40 bg-secondary d-flex align-items-center justify-content-center text-light fs-12">-<?php echo percentSale($product->get_regular_price(),$product->get_sale_price()); ?>%</div>
					<?php endif; ?>
					<?php
						echo hk_product_new_badge();
					?>
					<?php if ( ! $product->is_in_stock()) : ?>
						<div class="stock-tag px-0h bg-six d-flex align-items-center justify-content-center text-fifth fs-12">Out of stock</div>
					<?php endif;?>
				</div>
				<a class="d-block" href="<?php the_permalink(); ?>">
					<picture class="product-image d-flex align-items-center justify-content-center">
						<img src="<?php echo get_the_post_thumbnail_url();?>" alt="<?php the_title();?>">
					</picture>
				</a>
				<div class="product-info p-2">
					<p class="product-category text-third fs-lg-16 fw-bold text-uppercase">
						<?php 
							$cat = array(
								'taxonomy'      => 'product_cat',
								'hide_empty' => true,
								'order'         => 'desc'
							);
							$categories = get_the_terms( $product->$id, 'product_cat' );
							foreach($categories as $category) :
						?>
						<a href="<?php echo get_term_link($category->slug, 'product_cat'); ?>">
							<?php 
								echo $category->name;
								endforeach;
							?>
						</a>
					</p>
					
					<p class="product-title fs-lg-16 mt-0h mb-1h text-fourth"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></p>
					<p class="product-price mt-0h mb-1h text-fourth"><?php echo $product->get_price_html();?></p>
				</div>
				<div class="product-bottom w-100 d-flex align-item-center justify-content-end">
					<?php
					if ( ! wc_review_ratings_enabled() ) {
						return;
					}
					$rating_count = $product->get_rating_count();
					$review_count = $product->get_review_count();
					$average      = $product->get_average_rating();

					if ( $rating_count > 0 ) : ?>

						<div class="woocommerce-product-rating">
							<?php echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
							<?php if ( comments_open() ) : ?>
								<?php //phpcs:disable ?>
								<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s', '%s', $review_count, 'woocommerce' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>)</a>
								<?php // phpcs:enable ?>
							<?php endif ?>
						</div>
					
					<?php endif; ?>
					<?php echo do_shortcode('[wc_quick_buy]'); ?>
					<a class="btn-add-cart lh-0 p-1h bd-top-fifth-1 bd-left-fifth-1 d-inline-flex align-item-center justify-content-center" href="<?php bloginfo('url') ?>?add-to-cart=<?php the_ID(); ?>">
						<img src="<?php echo get_template_directory_uri(); ?>/common/images/bxs-cart.svg" alt="add to cart">
					</a>
				</div>
			</div>
		<?php endwhile; else : echo 'No posts found'; endif; wp_reset_query();
	wp_reset_postdata();
	echo '</div>';
	if ($countp > 9): ?> <!-- Kiểm tra liệu bài viết -->
		<script type="text/javascript">
			$(document).ready(function(){
				ajaxurl = '<?php echo admin_url("admin-ajax.php")?>';
				var offset = 9; // Đặt số lượng bài viết đã hiển thị ban đầu
				var sortValue = $('#filter').find('input[name="orderby"]:checked').val();
				var priceMin = $('input[name="price_min"]').val();
    			var priceMax = $('input[name="price_max"]').val();
				$( "#loadmore" ).click(function() {
					$('.loader').addClass('active');
					$.ajax({
						type: "POST", //Phương thưc truyền Post
						url: ajaxurl,
						data:{
							"action": "loadmore", 
							'offset': offset, 
							'orderby': sortValue,
							'all_terms': '<?php echo json_encode($all_terms); ?>',
                    		'all_colors': '<?php echo json_encode($all_colors); ?>',
                    		'all_tags': '<?php echo json_encode($all_tags); ?>',
							'price_min': priceMin,
                			'price_max': priceMax
						},  //Gửi các dữ liệu
						success:function(response)
						{
							$('.product').append(response);
							offset += offset; //Tăng bài viết đã hiển thị lên 
							$('.btn-wrapper i').addClass('d-none');
							if (offset >= <?php echo $countp ?>) { 
								$('#loadmore').addClass('hide');
							}
							setTimeout(function(){
								$('.loader').removeClass('active');
							},200); 
						}});
				});
			});
		</script>
		<div class="btn-wrapper d-flex align-items-center justify-content-center mt-3h">
			<a href="javascript:;" id="loadmore" class="text-third fs-lg-16 fw-bold d-flex align-items-center text-uppercase lh-1">
				See more <img class="w-24 d-inline-block ml-1" src="<?php echo get_template_directory_uri(); ?>/common/images/arrow.svg" alt="arrow">
			</a>
			<i class="fa fa-spinner fa-spin fa-fw d-none"></i>
		</div>
	<?php endif;
	die();
}

// load more products

add_action( 'wp_ajax_nopriv_loadmore', 'filter_load_posts' );
add_action( 'wp_ajax_loadmore', 'filter_load_posts' );
function filter_load_posts () {
	global $product;
	$offset = !empty($_POST['offset']) ? intval( $_POST['offset'] ) : '';
	$selectedSortBy = $_POST['orderby'];
	$all_terms = json_decode(stripslashes($_POST['all_terms']), true);
    $all_colors = json_decode(stripslashes($_POST['all_colors']), true);
    $all_tags = json_decode(stripslashes($_POST['all_tags']), true);
	$price_min = isset($_POST['price_min']) ? floatval($_POST['price_min']) : null;
	$price_max = isset($_POST['price_max']) ? floatval($_POST['price_max']) : null;

	if ($offset ) {
		$args = array(
			'posts_per_page' => 9,
			'post_type' => 'product',
			'offset' => $offset,
		);

		if ($selectedSortBy == 'popularity') {
			$args['orderby'] = 'popularity';
		} elseif ($selectedSortBy == 'rating') {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_wc_average_rating';
			$args['order'] = 'DESC';
		} elseif ($selectedSortBy == 'date') {
			$args['orderby'] = 'date';
		} elseif ($selectedSortBy == 'price') {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_price';
			$args['order'] = 'ASC';
		} elseif ($selectedSortBy == 'price-desc') {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_price';
			$args['order'] = 'DESC';
		} else {
			$args['orderby'] = 'menu_order';
		}

		if (!empty($all_terms)) {
			$args['tax_query'][] = array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $all_terms,
				),
			);
		}
	
		if (!empty($all_colors)) {
			$args['tax_query'][] = array(
				array(
					'taxonomy' => 'pa_colors',
					'field' => 'id',
					'terms' => $all_colors,
				),
			);
		}

		if (!empty($all_tags)) {
			$args['tax_query'][] = array(
				array(
					'taxonomy' => 'product_tag',
					'field' => 'id',
					'terms' => $all_tags,
				),
			);
		}
		if (!empty($price_min) && !empty($price_max)) {
			$args['meta_query'][] = array(
				'key' => '_price',
				'value' => array($price_min, $price_max),
				'type' => 'numeric',
				'compare' => 'BETWEEN'
			);
		} elseif (!empty($price_min)) {
			$args['meta_query'][] = array(
				'key' => '_price',
				'value' => $price_min,
				'type' => 'numeric',
				'compare' => '>'
			);
		} elseif (!empty($price_max)) {
			$args['meta_query'][] = array(
				'key' => '_price',
				'value' => $price_max,
				'type' => 'numeric',
				'compare' => '<'
			);
		}

		$the_query = new WP_Query( $args );
		if( $the_query->have_posts() ): while( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<div class="product-items position-relative bd-fifth-1" data-id="<?php echo get_the_ID(); ?>">
				<?php $product = wc_get_product(get_the_ID()); ?>
				<div class="tag d-flex">
					<?php if($product->is_on_sale()) : ?>
						<div class="price-sale w-40 bg-secondary d-flex align-items-center justify-content-center text-light fs-12">-<?php echo percentSale($product->get_regular_price(),$product->get_sale_price()); ?>%</div>
					<?php endif; ?>
					<?php
						echo hk_product_new_badge();
					?>
					<?php if ( ! $product->is_in_stock()) : ?>
						<div class="stock-tag px-0h bg-six d-flex align-items-center justify-content-center text-fifth fs-12">Out of stock</div>
					<?php endif;?>
				</div>
				<a class="d-block" href="<?php the_permalink(); ?>">
					<picture class="product-image d-flex align-items-center justify-content-center">
						<img src="<?php echo get_the_post_thumbnail_url();?>" alt="<?php the_title();?>">
					</picture>
				</a>
				<div class="product-info p-2">
					<p class="product-category text-third fs-lg-16 fw-bold text-uppercase">
						<?php 
							$cat = array(
								'taxonomy'      => 'product_cat',
								'hide_empty' => true,
								'order'         => 'desc'
							);
							$categories = get_the_terms( $product->$id, 'product_cat' );
							foreach($categories as $category) :
						?>
						<a href="<?php echo get_term_link($category->slug, 'product_cat'); ?>">
							<?php 
								echo $category->name;
								endforeach;
							?>
						</a>
					</p>
					
					<p class="product-title fs-lg-16 mt-0h mb-1h text-fourth"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></p>
					<p class="product-price mt-0h mb-1h text-fourth"><?php echo $product->get_price_html();?></p>
				</div>
				<div class="product-bottom w-100 d-flex align-item-center justify-content-end">
					<?php
					if ( ! wc_review_ratings_enabled() ) {
						return;
					}
					$rating_count = $product->get_rating_count();
					$review_count = $product->get_review_count();
					$average      = $product->get_average_rating();

					if ( $rating_count > 0 ) : ?>

						<div class="woocommerce-product-rating">
							<?php echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
							<?php if ( comments_open() ) : ?>
								<?php //phpcs:disable ?>
								<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s', '%s', $review_count, 'woocommerce' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>)</a>
								<?php // phpcs:enable ?>
							<?php endif ?>
						</div>
					
					<?php endif; ?>
					<?php echo do_shortcode('[wc_quick_buy]'); ?>
					<a class="btn-add-cart lh-0 p-1h bd-top-fifth-1 bd-left-fifth-1 d-inline-flex align-item-center justify-content-center" href="<?php bloginfo('url') ?>?add-to-cart=<?php the_ID(); ?>">
						<img src="<?php echo get_template_directory_uri(); ?>/common/images/bxs-cart.svg" alt="add to cart">
					</a>
				</div>
			</div>
		<?php endwhile; endif; wp_reset_query();
	}
	die();
}

// custom search products

function template_chooser($template)   
{    
  global $wp_query;   
  $post_type = get_query_var('post_type');   
  if( $wp_query->is_search && $post_type == 'product' )   
  {
    return locate_template('search.php');  //  redirect to archive-search.php
  }   
  return $template;   
}
add_filter('template_include', 'template_chooser');


function personal_message_when_logged_in() {

    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
		$items .= '<a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '">'. $current_user->user_login .'</a>';
		echo $items;
    } else {
    	$items .= '<a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '">Register</a>';
        echo $items;
    }

}


function login_logout_status() {
    if ( is_user_logged_in() ) {
		$login_status .= '<a href="/my-account/customer-logout/">Log out</a>';
		echo $login_status;
    } else {
    	$items .= '<a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '">Log in</a>';
        echo $items;
    }
}


