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
<div class="container d-lg-flex pt-8">
	<div class="side-bar mt-1h">

		<form action="<?php echo site_url(); ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
			<div class='sort-by bd-bottom-fifth-1 pb-2'>
				<p class='fw-bold mb-2 fs-lg-16'>SORT BY</p>
				<ul class='list-style-none'>
					<?php
						$catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
							'menu_order' => __( 'Default sorting', 'woocommerce' ),
						
							'popularity' => __( 'Sort by popularity', 'woocommerce' ),
						
							'rating'     => __( 'Sort by average rating', 'woocommerce' ),
						
							'date'       => __( 'Sort by latest', 'woocommerce' ),
						
							'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
						
							'price-desc' => __( 'Sort by price: high to low', 'woocommerce' )
						) );
						if( $catalog_orderby ) : // to make it simple I use default categories
							foreach ( $catalog_orderby as $id => $name ) :
								echo '<li><label><input type="radio" aria-label="Shop order" name="orderby" value="' . $id  . '" class="br">' . esc_attr( $name ). '</label></li>';
							endforeach;
						endif;
					?>
				</ul>
			</div>
			<?php
				$regions = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => 1, 'hierarchical' => 1, ) );
				if( $regions ) {
					echo "<div class='categories mt-2 bd-bottom-fifth-1 pb-2'><p class='fw-bold mb-2 fs-lg-16'>CATEGORIES</p><ul class='list-style-none'>";
					foreach( $regions as $region ) {
						if( $region->parent == 0 ) {
						
						echo '<li><label class="label" for="region_' . $region->term_id . '"><input type="checkbox" id="region_' . $region->term_id . '" name="region_' . $region->term_id . '" />' . $region->name . '<span class="checkmark"></span></label><span class="d-inline-block count-post">('.$region->count.')</span>';
						foreach( $regions as $subcategory ) {
							if($subcategory->parent == $region->term_id) {
								echo '<ul class="ml-2 list-style-none"><li><label class="label" for="region_' . $subcategory->term_id . '"><input type="checkbox" data-parent="region_' . $region->term_id . '" id="region_' . $subcategory->term_id . '" name="region_' . $subcategory->term_id . '" />' . $subcategory->name . '<span class="checkmark"></span></label><span class="d-inline-block count-post">('.$subcategory->count.')</span></li></ul>';
							}
						}
						echo '</li>';
						
						}
					}
					echo "</ul></div>";
				}
			?>
			<?php
				$colors = get_terms( array( 'taxonomy' => 'pa_colors', 'hide_empty' => 1 ) );
				if( $colors ) {
					echo "<div class='colors mt-2 bd-bottom-fifth-1 pb-2'><p class='fw-bold mb-2 fs-lg-16'>COLORS</p><ul class='list-style-none'>";
					foreach( $colors as $color ) :
						// $value = get_field( 'chose_color', 'term_' . $color->term_id );
						echo '<li><label class="label" for="color_' . $color->term_id . '"><input type="checkbox" id="color_' . $color->term_id . '" name="color_' . $color->term_id . '" />' . $color->name . '<span class="checkmark"></span></label><span class="d-inline-block count-post">('.$color->count.')</span>';
					endforeach;
					echo "</ul></div>";
				}
			?>
			<?php
				$tags = get_terms( array( 'taxonomy' => 'product_tag', 'hide_empty' => 1, 'hierarchical' => 1, ) );
				if( $tags ) {
					echo "<div class='tags mt-2 bd-bottom-fifth-1 pb-2'><p class='fw-bold mb-2 fs-lg-16'>TAGS</p><ul class='list-style-none d-flex flex-wrap'>";
					foreach( $tags as $tag ) {
						if( $tag->parent == 0 ) {
							echo '<li><input type="checkbox" id="tag_' . $tag->term_id . '" name="tag_' . $tag->term_id . '" /><span class="checkmark">' . $tag->name . '</span></li>';
						}
					}
					echo "</ul></div>";
				}
			?>

			<div class='priceFilter mt-2'>
				<p class='fw-bold mb-2 fs-lg-16'>PRICES</p>
				<input type="text" name="price_min" placeholder="Min price" />
				<input type="text" name="price_max" placeholder="Max price" />
			</div>
					
			<input type="hidden" name="action" value="myfilter">
		</form>
	</div>
	<div class="product-wrap">
		<div class="product d-flex flex-wrap">
			<?php
				$args = array(
					'posts_per_page'    => 9,
					'post_type'         => 'product',
				);
				$the_query = new WP_Query( $args );
				$countp = $the_query ->found_posts;
				global $product;
			?>
			<?php if( $the_query->have_posts() ): while( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<div class="product-items position-relative bd-fifth-1" data-id="<?php echo get_the_ID(); ?>">
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
		<?php if ($countp > 9): ?> <!-- Kiểm tra liệu bài viết -->
			<script type="text/javascript">
				$(document).ready(function(){
					ajaxurl = '<?php echo admin_url("admin-ajax.php")?>';
					offset = 9; // Đặt số lượng bài viết đã hiển thị ban đầu
					$( "#loadmore" ).click(function() {
						$('.btn-wrapper i').removeClass('d-none'); 
						$.ajax({
							type: "POST", //Phương thưc truyền Post
							url: ajaxurl,
							data:{
								"action": "loadmore", 
								'offset': offset, 
							},  //Gửi các dữ liệu
							success:function(response)
							{
								$('.product').append(response);
								offset = offset + 9; //Tăng bài viết đã hiển thị lên 
								$('.btn-wrapper i').addClass('d-none');
								if (offset >= <?php echo $countp ?>) { 
									$('#loadmore').addClass('hide');
								}
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
		<?php endif; ?>
	</div>
</main>
<?php get_footer( 'shop' );?>
