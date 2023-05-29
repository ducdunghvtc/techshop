<?php
    get_header();
?>
<main class="">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="container pt-4 pt-lg-8">
            <?php the_content(); ?>
        </div>
    <?php endwhile; endif; ?>
</main>
<?php
    get_footer();
?>