<?php
get_header()
?>
<div class="container-fluid bg-white-folds">
    <div class="row p-5">
        <div class="col-lg-12">
            <h1><?php echo the_title() ?></h1>
        </div>
        <div class="col-lg-12 mb-5">
            <?php echo the_content() ?>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row bg-brown">
        <div class="row mx-0 bg-folds justify-content-center p-5">
            <div class="col-lg-3 align-self-center mt-5">
                <h1 class="display-3 text-mascote">Já conhece nosso <b class="fw-bold">mascote?</b></h1>
            </div>
            <div class="col-lg-3 mt-5">
                <img class="img-mascote img-fluid" src="<?php echo get_template_directory_uri() ?>/assets/img/mascote.png">
            </div>
        </div>
    </div>
</div>
<?php get_footer() ?>