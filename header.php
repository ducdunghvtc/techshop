<!DOCTYPE html>
<html lang="ja">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="header d-flex flex-column">
	<?php global $tp_options; ?>
	<div class="w-100 py-1 d-none d-md-block">
		<div class="container header-top">
			<div class="header-contact d-flex align-items-center">
				<?php clean_custom_menu("contact-header");?>
			</div>
		</div>
	</div>
	<div class="bg-secondary w-100 py-2">
		<div class="container d-flex justify-content-center align-items-center">
			<h1 class="header-logo">
				<?php the_custom_logo();?>
				<span class="d-inline-block ml-2 text-light fw-bold fs-16 fs-lg-18">TECHSHOP</span>
			</h1>
			<div class="header-nav">
				<?php wp_nav_menu(array("theme_location" => "primary-menu"));?>
				<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
					<input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
					<button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>" class="<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>"><?php echo esc_html_x( 'Search', 'submit button', 'woocommerce' ); ?></button>
					<input type="hidden" name="post_type" value="product" />
				</form>
				<div class="w-100 d-block d-md-none">
					<div class="header-contact">
						<?php clean_custom_menu("contact-header");?>
					</div>
				</div>
			</div>
			<button class="header-toggle-navi js-toggle-navi d-md-none">
				<span></span>
				<span></span>
				<span></span>
			</button>
		</div>
	</div>
</header>