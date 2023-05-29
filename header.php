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
	<div class="bg-fourth w-100 box-shadow-black-0h py-1 py-lg-0">
		<div class="container d-flex justify-content-center align-items-center">
			<h1 class="header-logo">
				<?php the_custom_logo();?>
				<span class="d-inline-block ml-2 text-light fw-bold fs-16 fs-lg-18">TECHSHOP</span>
			</h1>
			<div class="header-nav">
				<?php wp_nav_menu(array("theme_location" => "primary-menu"));?>

				<div class="user mx-md-3 mx-xl-5">
					<span class="user-icon d-inline-block"><img src="<?php echo get_template_directory_uri(); ?>/common/images/bxs-user.svg" alt="user"></span>
					<ul class="list-style-none pl-3 p-lg-3">
						<li class="mb-1 mb-lg-2"><?php personal_message_when_logged_in(); ?></li>
						<li class="mb-1 mb-lg-0"><?php login_logout_status(); ?></li>
					</ul>
				</div>
				<form role="search" method="get" class="pt-1 pt-lg-0 woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="screen-reader-text"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
					<input type="search" placeholder="<?php echo esc_attr__( 'Search', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
					<input type="hidden" name="post_type" value="product" />
				</form>
				<div class="w-100 d-block d-md-none">
					<div class="header-contact">
						<?php clean_custom_menu("contact-header");?>
					</div>
				</div>
			</div>
			<button class="header-toggle-navi js-toggle-navi d-lg-none">
				<span></span>
				<span></span>
				<span></span>
			</button>
		</div>
	</div>
</header>