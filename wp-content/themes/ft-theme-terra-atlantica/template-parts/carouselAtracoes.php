<div id="carouselAtracoes" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner px-4" role="listbox">
        <?php
  $args_banner = array(
    'post_type' => 'atracoes',
    'posts_per_page' => 0,
  );

  $query_banner_posts = new WP_Query($args_banner);

  if( $query_banner_posts->have_posts()) : 
    $qtd_banner = $qtd_banners[0];
    $c = 0; 
    while($query_banner_posts->have_posts()): $query_banner_posts->the_post(); ?>
        <div class="carousel-item mb-4 <?php $c++; if($c == 1) { echo 'active';} ?>">
            <div class="col-lg-4 col-md-4 col-10 offset-1 my-5">
                <div class="titulo-atracao text-center">
                    <div class="h-100 py-3 d-flex justify-content-center">
                        <h1 class="align-self-center"><b><?php echo the_title() ?></b></h1>
                    </div>
                </div>
                <div class="img-atracao" style="background-image: url(<?php the_post_thumbnail_url()?>);">
                </div>
                <div class="descricao-atracao text-center mt-4">
                    <?php echo the_excerpt() ?>
                </div>
            </div>
        </div>
        <?php endwhile; 
  endif; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselAtracoes" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselAtracoes" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>