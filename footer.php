
<div class="footer position-relative mt-4 mt-lg-8">
    <div class="footer-top">
        <div class="container d-flex flex-wrap">
            <div class="service-slider">
                <?php 
                    $services = array(
                        array(
                            'title' => 'FREESHIP',
                            'text' => 'Free shipping on all US order or order above $200',
                        ),
                        array(
                            'title' => '30 DAYS RETURN',
                            'text' => 'Simply return it within 30 days for an exchange.',
                        ),
                        array(
                            'title' => 'SUPPORT 24/7',
                            'text' => 'Contact us 24 hours a day, 7 days a week',
                        ),
                    );
                ?>

                <?php 
                    foreach($services as $key => $service) : 
                ?>
                    <div class="service-items bg-fifth px-3 py-3 py-lg-5">
                        <div class="d-flex">
                            <img src="<?php echo get_template_directory_uri(); ?>/common/images/footer-slide-<?php echo $key+1;?>.png" alt="footer-slide-<?php echo $key+1;?>">
                            <div class="service-text ml-3">
                                <p class="fw-bold fs-lg-16"><?php echo $service['title'];?></p>
                                <p class="fs-lg-16 mt-1q"><?php echo $service['text'];?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <footer class="footer-main bg-fourth">
        <div class="pt-12 pt-lg-16h pb-4 pb-lg-9">
            <div class="container d-flex flex-wrap flex-lg-nowrap align-items-start justify-content-between">
                <div class="footer-logo d-inline-flex align-items-center">
                    <?php the_custom_logo();?>
                    <span class="d-inline-block text-light ml-2 fw-bold fs-16 fs-lg-18">TECHSHOP</span>
                </div>
                <?php clean_custom_menu("contact-footer");?>
                <?php wp_nav_menu(array("theme_location" => "footer-menu"));?>
                <?php echo do_shortcode('[mailpoet_form id="1"]'); ?>
            </div>
        </div>
        <p class="py-1h px-2 fs-12 fs-lg-14 w-100 footer-copyright bg-secondary text-center text-light">© 2021 TECHSHOP. ALL RIGHTS RESERVED</p>
    </footer>
</div>
<div class="modal" id="modal_detail">
    <div class="modal-overlay"></div>
    <div class="modal-content">

    </div>
</div>
<div class="loader"><span></span></div>
<?php wp_footer();?>
</body>
</html>
