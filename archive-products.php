<?php
    /*
    Template Name: Products
    */
    get_header(); 
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'posts_per_page' => 12,
        'post_type'      => 'post',
        'category_name'  => 'main-products',
        'paged'          => $paged,
    );
    $the_query = new WP_Query( $args );

?> 
<main class="main page-archive mt-7 mt-md-11">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div>
            <?php the_content(); ?>
        </div>
    <?php endwhile; endif; ?>
    <div class="container">
        <div class="post d-flex flex-wrap">
            <?php if( $the_query->have_posts() ): while( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <a data-id="<?php echo get_the_ID(); ?>" class="js-loaddetail d-flex position-relative flex-column" href="avascript:void(0)">
                    <figure class="d-flex align-items-center justify-content-center">
                        <img src="<?php echo get_the_post_thumbnail_url();?>" alt="<?php the_title();?>">
                    </figure>
                    <div class="products-text p-1h">
                        <h3 class="title fs-20 fw-bold"><?php the_title();?></>
                        <div class="text fs-14 mt-0hq">
                            <?php the_excerpt();?>
                        </div>
                    </div>
                </a>
            <?php endwhile; endif; wp_reset_query(); ?>
        </div>	
        <!-- PAGINATION -->
        <div class="pagination mt-lg-5 mt-4 px-lg-6 d-flex justify-content-center align-items-center w-100">
            <?php
                $total_pages = $the_query->max_num_pages;
                // echo $total_pages;
                if ($total_pages >=2){
            ?>
            <?php
                if ($total_pages >1){
                    echo paginate_links( array(
                        'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                        'total'        => $total_pages,
                        'current'      => max( 1, get_query_var( 'paged' ) ),
                        'format'       => '?paged=%#%',
                        'show_all'     => false,
                        'type'         => 'plain',
                        'end_size'     => 4,
                        'mid_size'     => 1,
                        'prev_next'    => true,
                        // 'prev_text'    => sprintf( '%1$s', __( '&lsaquo;', 'text-domain' ) ),
                        // 'next_text'    => sprintf( '%1$s', __( '&rsaquo;', 'text-domain' ) ),
                        'prev_text'    => sprintf( '<', __( 'Newer Posts', 'text-domain' ) ),
                        'next_text'    => sprintf( '>', __( 'Older Posts', 'text-domain' ) ),
                        'add_args'     => false,
                        'add_fragment' => '',
                    ) );
                }     
                }
            ?>
        </div>
    </div>	
</main>

<?php
    get_footer();
?>
