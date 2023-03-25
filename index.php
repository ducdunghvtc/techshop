<?php
    /*
    Template Name: Home
    */
    get_header();
?>

<main class="main woocommerce">
    <section class="main-visual">
        <?php
            global $product;
            $newArgs = array(
                'posts_per_page'        => 3,
                'post_type'             => 'product',
                'hide_empty'            => 1,
                'orderby'               => 'date',
            );
            $main_product = new WP_Query( $newArgs );
        ?>
        <div class="main-visual-content">
            <div class="banner-slider d-flex flex-wrap">
                <?php if( $main_product->have_posts() ): while( $main_product->have_posts() ) : $main_product->the_post(); ?>
                    <div class="main-visual-items position-relative" style="background: linear-gradient(270.38deg, #0B0742 0.37%, rgba(44, 40, 98, 0) 99.75%), url(<?php echo get_the_post_thumbnail_url();?>) no-repeat;background-size: cover;">
                        <div class="main-visual-content pt-4 pt-lg-10 pb-10 pb-lg-20 text-right">
                            <div class="container">
                                <div class="main-visual-info">
                                    <div class="line"></div>
                                    <span class="text-uppercase text-third fs-lg-16 fw-bold">New product</span>
                                    <h2 class="main-visual-title fs-40 fs-lg-72 mt-0h mb-1h text-light fw-bold"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h2>
                                    <p class="text-light fs-lg-16"><?php echo $product->post->post_content; ?></p>
                                    <div class="main-visual-button mt-3 mt-lg-6h w-100 d-flex align-item-center justify-content-end">
                                        <?php echo do_shortcode('[wc_quick_buy]'); ?>
                                        <div class="ico-arrow bg-light">
                                            <div class="arrow-right"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; endif; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <section class="best-sellers mt-3 mt-lg-10">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="best-seller-title text-secondary fs-30 fs-lg-48 fw-bold mr-2">Best sellers</h2>
                <a href="/shop" class="text-third fs-lg-16 fw-bold d-flex align-items-center text-uppercase lh-1">
                    See more <img class="w-24 d-inline-block ml-1" src="<?php echo get_template_directory_uri(); ?>/common/images/arrow.svg" alt="arrow">
                </a>
            </div>
            <div class="product d-flex flex-wrap">
                <?php
                    $tax_query[] = array(
                        'taxonomy'          => 'product_visibility',
                        'field'             => 'name',
                        'terms'             => 'featured',
                        'operator'          => 'IN',
                    );
                    $args = array(
                        'posts_per_page'    => 8,
                        'post_type'         => 'product',
                        'tax_query'         => $tax_query,
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
        </div>
    </section>
    <section class="flash-sale mt-3 mt-lg-7h">
        <?php
            $flashTitle = get_field('title');
            $flashContent = get_field('content');
            $flashTimer = get_field('timer');
            $flashLink = get_field('link');
            $flashImage = get_field('image');
        ?>
        <div class="d-lg-flex">
            <div class="flash-sale-content p-4 p-lg-8h bg-secondary">
                <div class="line white"></div>
                <?php if ($flashTitle) : ?>
                    <h2 class="text-light fs-lg-16 fw-bold text-uppercase"><?php echo $flashTitle; ?></h2>
                <?php endif; ?>
                <?php if ($flashContent) : ?>
                    <p class="text-light fs-lg-16 mt-3"><?php echo $flashContent; ?></p>
                <?php endif; ?>
                <?php if ($flashTimer) : ?>
                    <?php echo $flashTimer; ?>
                <?php endif; ?>
                <?php if ($flashLink) : ?>
                    <div class="btn-join d-inline-flex align-items-center justify-content-center">
                        <a class="text-light fs-lg-16 d-flex align-items-center justify-content-center text-uppercase fw-bold bg-third" href="<?php echo $flashLink['url']; ?>"><?php echo $flashLink['title']; ?></a>
                        <div class="ico-arrow">
                            <div class="arrow-right"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="flash-sale-image">
                <img src="<?php echo $flashImage ?>" alt="image">
            </div>
        </div>
    </section>
    <section class="new-products mt-3 mt-lg-10">
        <div class="container">
            <h2 class="best-seller-title text-secondary fs-30 fs-lg-48 fw-bold mr-2">New products</h2>
            <ul class="tabs">
                <?php
                    $categories = get_terms( array(
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => 1,
                        
                    ) );
                    $i=0;
                    foreach ( $categories as $cat ) : 
                        $childCate = get_terms('product_cat',array('hide_empty' => 1,'parent' => $cat->term_id, 'orderby' => 'term_id','order' => 'DESC' ));
                        foreach ( $childCate as $child ) : 
                        $i++;
                ?>
                        <li class="<?php if($i == 1) : echo 'active'; endif; ?>" data-id="<?php echo $child->slug; ?>"><?php echo $child->name; ?></li>
                <?php endforeach;endforeach; ?>
            </ul>
            <div id="tabs-content">
            <?php
                global $product;
                $i=0;
                foreach ( $categories as $cat ) :
                    $childCate = get_terms('product_cat',array('hide_empty' => 1, 'parent' => $cat->term_id, 'orderby' => 'term_id','order' => 'DESC' ));
                    foreach ( $childCate as $key => $child ) : 
                    $newArgs = array(
                        'posts_per_page'        => 6,
                        'post_type'             => 'product',
                        'product_cat'           => $child->slug,
                        'orderby'               => 'name',
                        'order'                 => 'ASC',
                        'date_query' => array(
                            array(
                                'after'     => '-30 days',
                                'column' => 'post_date',
                            ),
                        ),
                    );
                $new_product = new WP_Query( $newArgs );
                $i++;
            ?>
            <div class="tab-content <?php if($i == 1) : echo 'active'; endif; ?>" id="<?php echo $child->slug; ?>">
                <div class="js-slide product d-flex flex-wrap">
                    <?php if( $new_product->have_posts() ): while( $new_product->have_posts() ) : $new_product->the_post(); ?>
                        <div class="product-items position-relative bd-fifth-1">
                            <div class="tag d-flex">
                                <?php if($product->is_on_sale()) : ?>
                                    <div class="price-sale w-40 bg-secondary d-flex align-items-center justify-content-center text-light fs-12">-<?php echo percentSale($product->get_regular_price(),$product->get_sale_price()); ?>%</div>
                                <?php endif; ?>
                                <?php echo hk_product_new_badge(); ?>
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
                    <?php endwhile; endif; wp_reset_postdata(); ?>
                </div>
            </div>
            <?php endforeach;endforeach;?>
        </div>
        </div>
    </section>
    <section class="blog mt-3 mt-lg-8">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="best-seller-title text-secondary fs-30 fs-lg-48 fw-bold mr-2">Blog</h2>
                <a href="/blog" class="text-third fs-lg-16 fw-bold d-flex align-items-center text-uppercase lh-1">
                    See more <img class="w-24 d-inline-block ml-1" src="<?php echo get_template_directory_uri(); ?>/common/images/arrow.svg" alt="arrow">
                </a>
            </div>
            <?php
                $newArgs = array(
                    'posts_per_page'        => 6,
                    'post_type'             => 'post',
                    'hide_empty'            => 1,
                );
                $blog_query = new WP_Query( $newArgs );
            ?>
            <div class="blog-content">
                <div class="blog-slider product d-flex flex-wrap">
                    <?php if( $blog_query->have_posts() ): while( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
                        <div class="blog-items">
                            <a class="d-block" href="<?php the_permalink(); ?>">
                                <picture class="blog-image d-flex align-items-center justify-content-center">
                                    <img src="<?php echo get_the_post_thumbnail_url();?>" alt="<?php the_title();?>">
                                </picture>
                                <div class="blog-info">
                                    <p class="blog-title fs-lg-16 mt-2 mb-1h text-fourth fw-bold text-uppercase"><?php the_title();?></p>
                                    <p class="blog-date fs-lg-16 text-third"><?php echo get_the_date('F j, Y');?></p>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; endif; wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </section>
</main>

        
<?php
    get_footer();
?>
