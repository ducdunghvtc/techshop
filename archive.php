<?php
    /*
    Template Name: Blog
    */
    get_header(); 
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'posts_per_page' => 12,
        'post_type'      => 'post',
        'paged'          => $paged,
    );
    $the_query = new WP_Query( $args );

?> 
<main class="main page-archive mt-7 mt-md-11">
    <div class="container">
        <div class="post equipment d-flex flex-wrap pt-8">
            <?php if( $the_query->have_posts() ): while( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <a class="d-flex position-relative flex-column" href="<?php the_permalink(); ?>">
                    <figure class="d-flex align-items-center justify-content-center">
                        <?php the_post_thumbnail(); ?>
                    </figure>
                    <h3 class="post-title fs-14 fs-md-18 mt-1h"><?php the_title(); ?></h4>
                </a>
            <?php endwhile; endif; wp_reset_query(); ?>
        </div>	
        <!-- PAGINATION -->
        <div class="pagination my-lg-5 my-4 px-lg-6 d-flex justify-content-center align-items-center w-100">
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
