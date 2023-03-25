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

	wp_register_script( 'jquery-3-script', "https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js", array('jquery') );
	wp_enqueue_script('jquery-3-script');

	wp_register_script( 'slick-script', get_template_directory_uri() . "/common/js/slick.min.js", array('jquery') );
	wp_enqueue_script('slick-script');
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
	
			
		foreach( $menu_items as $menu_item ) {
			$link = $menu_item->url;
			$title = $menu_item->title;
			$menu_list .= '<li class="fs-12 fs-lg-14 text-secondary my-1h my-md-0">';
			$menu_list .= '<a class="d-flex align-items-start align-items-md-center fs-12 fs-lg-14 text-secondary" target="_blank" href="'.$link.'" ><img src="'.get_field('icon', $menu_item->ID).'">'.$title.'</a>';
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

add_action('wp_ajax_nopriv_loadmore', 'get_post_loadmore');
function get_post_loadmore() {
    $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0; // lấy dữ liệu phái client gởi
    $getposts = new WP_query(); $getposts->query('post_type=product,post_status=publish&posts_per_page=5&offset='.$offset);
	global $product;
    if( $getposts->have_posts() ): while ($getposts->have_posts()) : $getposts->the_post(); ?>
    	<div class="product-items position-relative bd-fifth-1">
			<div class="tag d-flex">
				<?php if($product->is_on_sale()) : ?>
					<div class="price-sale w-40 bg-secondary d-flex align-items-center justify-content-center text-light fs-12">-<?php echo percentSale($product->get_regular_price(),$product->get_sale_price()); ?>%</div>
				<?php endif; ?>
				<?php
					echo hk_product_new_badge();
				?>
				<?php if ( ! $product->is_in_stock() ) : ?>
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
						$categories = get_the_terms( $product->get_id(), 'product_cat' );
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
	die(); 
}
