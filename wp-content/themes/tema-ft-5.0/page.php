<?php get_header();?>
    <?php get_template_part('template-parts/navcategoryandsearch') ?>
    <div class="container">
        <div class="row pl-3 pr-3">
            <div class="col-md-8 col-lg-9 mb-3">
                <div class="col-md-12 box-div" style="min-height: 90%;">
                    <h4><?php echo the_title();?></h4>
                    <?php the_content();?>
                </div>
            </div>

            <!--Banner Lateral-->
            <div class="col-md-4 col-lg-3 mb-3">
            <?php dynamic_sidebar('sidebar-lateral')?>  
            </div>
        </div>
    </div>
</div>
<?php get_footer();?>