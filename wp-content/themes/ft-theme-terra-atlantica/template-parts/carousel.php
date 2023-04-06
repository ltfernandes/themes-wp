<div id="carouselBanners" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <?php
    $args_banner = array(
      'post_type' => 'slides',
      'posts_per_page' => 5,
    );

    $query_banner_posts = new WP_Query($args_banner);

    if ($query_banner_posts->have_posts()) :
      $qtd_banner = $qtd_banners[0];
      $c = 0;
      while ($query_banner_posts->have_posts()) : $query_banner_posts->the_post(); ?>
        <div class="carousel-item <?php $c++;
                                  if ($c == 1) {
                                    echo 'active';
                                  } ?>">


          <div class="w-100" style="background-image: url(<?php the_post_thumbnail_url() ?>);">
          </div>

        </div>
    <?php endwhile;
    endif; ?>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselBanners" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Voltar</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselBanners" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Seguir</span>
  </button>

  <div id="btn-cta">
      <a aria-current="page" href="https://terraatlantica.com.br/ingressos">
        <div class="btn btn-warning btn-compre-aqui">Garanta seu ingresso!</div>
      </a>
  </div>
</div>