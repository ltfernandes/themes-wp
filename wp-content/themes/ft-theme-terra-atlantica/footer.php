<?php 
$id_logo = get_theme_mod('footer_logo');
$url_logo = wp_get_attachment_url($id_logo); 
?>
<footer id="contato">
    <div class="container-fluid">
        <div class="offset-lg-1 col-lg-10 mb-3 mt-2">
            <div class="row">
                <div class="col-lg-3 col-md-4 text-center pt-5 align-self-center footer-logo">
                    <img class="img-fluid" src="<?php echo $url_logo ?>" alt="">
                </div>
                <div class="col-lg-4 col-md-8 pt-5 align-self-center">
                    <p> <img src="<?php echo get_template_directory_uri() ?>/assets/img/icone_central.png" alt="">
                        Central de atendimento</p>
                    <?php dynamic_sidebar('central_atendimento')?>
                    <p>
                        <a class="me-2" href="<?php echo get_theme_mod('link_instagram') ?>" target="_blank">
                            <img src="<?php echo get_template_directory_uri() ?>/assets/img/icone_insta.png"
                                alt="Instagram">
                        </a>
                        <a href="<?php echo get_theme_mod('link_facebook') ?>" target="_blank">
                            <img src="<?php echo get_template_directory_uri() ?>/assets/img/icone_face.png"
                                alt="Facebook">
                        </a>
                    </p>
                </div>
                <div class="col-lg-5 col-md-12 pt-5 align-self-center">
                    <div class="row">
                        <div class="col-md-4">
                            <?php wp_nav_menu( array('menu' => 'institucional')) ?>
                        </div>
                        <div class="col-md-4">
                            <?php wp_nav_menu( array('menu' => 'cadastro')) ?>
                        </div>
                        <div class="col-md-4">
                            <?php wp_nav_menu( array('menu' => 'atendimento')) ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php wp_footer();?>
</footer>

</html>