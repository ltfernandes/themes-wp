<?php get_header() ?>
<?php get_template_part('template-parts/carousel') ?>
<div class="container-fluid bg-white-folds">
    <div class="row bg-brown-folds px-4 pb-4" style="padding-top: 6vmax;" id="oparque">
        <div class="col-lg-7 offset-lg-5 align-self-center">
            <div class="row">
                <h1 class="text-white">O Parque</h1>
            </div>
        </div>
    </div>

    <div class="row p-4 mb-4">
        <div class="col-lg-5 px-5" style="margin-top: -10%;">
            <div class="img-parque p-3">
                <img src="<?php echo get_template_directory_uri() ?>/assets/img/oparque.jpg" class="img-fluid">
            </div>
        </div>
        <div class="col-lg-7 pe-5">
            <div class="row pt-2">
                <?php dynamic_sidebar('o_parque')?>
            </div>
        </div>
    </div>
    <div class="row justify-content-center" id="atracoes">
        <?php get_template_part('template-parts/carouselAtracoes') ?>
    </div>
</div>
<?php get_template_part('template-parts/mascote') ?>
<?php get_footer() ?>