<?php
    get_header();
?>
<main class="mt-7 mt-md-11">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div>
            <?php the_content(); ?>
        </div>
    <?php endwhile; endif; ?>
</main>
<?php
    get_footer();
?>