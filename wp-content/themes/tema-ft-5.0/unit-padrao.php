<?php

/**
 * Template Name: Unidade de Negócios
 */
?>

<?php get_header(); ?>
<?php get_template_part('template-parts/navcategoryandsearch') ?>
<?php echo do_shortcode('[unidade-negocios id="" data="" horario="" forca-calendario=""]'); ?>
</div>
<?php get_footer(); ?>