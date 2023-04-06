<?php

/**
 * Template Name: Interna Padrão Full
 */
?>
<?php get_header();?>
    <?php get_template_part('template-parts/navcategoryandsearch') ?>
    <div class="container">
        <div class="row pl-3 pr-3">
            <div class="col-md-12 col-lg-12 mb-3">
                <div class="col-md-12 box-div" style="min-height: 90%;">
                    <?php the_content();?>
                </div>
            </div>

        </div>
    </div>
</div>
<?php get_footer();?>