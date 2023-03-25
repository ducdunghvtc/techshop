<?php
    /*
    Template Name: Contact
    */
    get_header();
?>
<main class="mt-7 mt-md-11">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3720.7694533448707!2d105.7467999!3d21.161571300000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134fe5207ece4b9%3A0xdc0dbac2f8219862!2s35!5e0!3m2!1svi!2s!4v1679641891751!5m2!1svi!2s" width="100%" style="border:0;height:65rem;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    <div class="container">
        <h2 class="fw-bold text-fourth fs-30 fs-lg-48 pt-8 pb-4">Send message to us<p></p></h2>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div>
                <?php the_content(); ?>
            </div>
        <?php endwhile; endif; ?>
    </div>
</main>
<?php
    get_footer();
?>