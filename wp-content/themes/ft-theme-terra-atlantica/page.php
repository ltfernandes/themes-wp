<?php get_header() ?>
<div class="container-fluid bg-white-folds" style="padding: 10rem 2rem;">
    <div class="row p-lg-5">
        <div class="col-lg-10 offset-lg-1">
            <h1><?php echo the_title() ?></h1>
        </div>
        <div class="col-lg-10 offset-lg-1">
            <?php echo the_content() ?>
        </div>
    </div>
</div>
<?php get_template_part('template-parts/mascote') ?>
<?php get_footer() ?>