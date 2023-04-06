<?php
/**
* Template Name: Evento
*/
?>
<?php get_header();?>
<?php get_template_part('template-parts/navcategoryandsearch') ?>
<div class="container-fluid">
    <div class="row pl-md-3 pr-md-3">
        <div class="col-md-12 col-lg-12 mb-3">
            <div class="col-md-12 box-div">
            <?php echo do_shortcode('[evento force_id=""]'); ?>
            </div>
        </div>
    </div>
</div>
</div>
<?php get_footer();?>