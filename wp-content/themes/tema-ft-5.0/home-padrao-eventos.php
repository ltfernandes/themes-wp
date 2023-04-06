<?php

/**
 * Template Name: Home Padrão (Eventos)
 */
?>

<?php get_header(); ?>
<?php get_template_part('template-parts/navcategoryandsearch') ?>
<div class="container">
    <div class="row pl-md-3 pr-md-3" id="slider">
        <div class="col-md-8 col-lg-9 mb-3">
            <?php echo do_shortcode('[eventos_slider ordem="" limite="6"]'); ?>
        </div>

        <div class="col-md-4 col-lg-3 mb-3">
            <?php get_template_part('template-parts/banner') ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="row pl-md-3 pr-md-3">
        <?php echo do_shortcode('[eventos_destaques ordem=""]'); ?>
    </div>
</div>

<?php get_footer(); ?>