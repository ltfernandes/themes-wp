<div id="sliderControls" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php
            $args_banner = array(
                'post_type' => 'banners',
            );

            $query_banner_posts = new WP_Query($args_banner);

            if( $query_banner_posts->have_posts()) : 
                $qtd_banner = $qtd_banners[0];
                $c = 0; 
                while($query_banner_posts->have_posts()): $query_banner_posts->the_post();
            ?>
        <div class="carousel-item <?php $c++; if($c == 1) { echo 'active';} ?>">
            <a href="<?php the_permalink()?>">
                <?php the_post_thumbnail('post-thumbnail', array('class' => 'img-fluid img-slider'))?>
            </a>
            <div class="carousel-caption d-none d-md-block">

            </div>
        </div>
        <?php endwhile; endif; ?>

    </div>

    <?php if($c > 1): ?>
    <button class="carousel-control-prev" type="button" data-bs-target="#sliderControls" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#sliderControls" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Próximo</span>
    </button>
    <?php endif;?>
</div>