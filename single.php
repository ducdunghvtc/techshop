<?php
	get_header();
?>
<main class="mt-7 mt-lg-11 product-detail">
    <div class="container">
        <div class="d-flex">
            <div class="content col-12 col-md-9">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <div>
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; endif; ?>
            </div>
            <?php get_sidebar();?>
        </div>
    </div>
</main>

<?php
    get_footer();
?>