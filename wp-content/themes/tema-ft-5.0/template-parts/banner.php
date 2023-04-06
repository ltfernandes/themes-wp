<div id='carouselbanner' class="carousel slide" data-ride="carousel">

    <div class="carousel-inner">

        <?php
        $args_banner = array(
            'post_type' => 'banners',
            'post_per_page' => 5,
        );

        $query_banner_posts = new WP_Query($args_banner);
        ?>
        
        <?php if( $query_banner_posts->have_posts()) : 
            $qtd_banner = $qtd_banners[0];
            $c = 0; 
            while($query_banner_posts->have_posts()): $query_banner_posts->the_post(); ?>
        
        <div class="carousel-item <?php $c++; if($c == 1) { echo 'active';} ?>">
            <a href="<?php echo get_post_permalink() ?>">
                <?php the_post_thumbnail('post-thumbnail', array())?>
            </a>
            <div class="carousel-caption d-none d-md-block">
                
            </div>
        </div>
        <?php endwhile; endif; ?>

        <?php if($c > 1): ?>
        <a href="#carouselbanner" class="carousel-control-prev" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="sr-only">Anterior</span>
        </a>

        <a href="#carouselbanner" class="carousel-control-next" role="button" data-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="sr-only">Próximo</span>
        </a>
        <?php endif;?>
    </div>
</div>