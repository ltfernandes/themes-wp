<?php
/**
* Template Name: Largura Total da página
*/
?>
<?php get_header();?>
    <?php get_template_part('template-parts/navcategoryandsearch') ?>
    <div class="container">
        <div class="row pl-md-3 pr-md-3">
            <div class="col-md-12 col-lg-12 mb-3">
                <div class="col-md-12 box-div" style="min-height: 90%;">
                    <h4><?php echo the_title();?></h4>
                    <?php the_content();?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer();?>