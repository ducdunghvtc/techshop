<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
// do_action( 'woocommerce_before_main_content' );

?>
<main>


<!-- <header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header> -->
<div class="container d-flex pt-8">
	<?php
	do_action( 'woocommerce_sidebar' );
	?>
	<div class="product-wrap">
		<div class="product d-flex flex-wrap">
			<?php
				$args = array(
					'posts_per_page'    => 5,
					'post_type'         => 'product',
				);
				$the_query = new WP_Query( $args );
				global $product;
			?>
			<?php if( $the_query->have_posts() ): while( $the_query->have_posts() ) : $the_query->the_post(); ?>
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
			<?php endwhile; endif; wp_reset_query(); ?>
		</div>
		<button class="load-more">Xem thêm</button>
	</div>
</main>
<?php 
get_footer( 'shop' );?>
