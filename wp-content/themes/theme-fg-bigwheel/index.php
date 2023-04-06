<?php get_header() ?>

<!--Carousel Banners-->
<div class="container-fluid px-0 d-none d-md-block">
    <?php get_template_part('utilities/carousel')?>
</div>
<div class="container-fluid mobile-carousel px-0 d-block d-md-none">
    <img src="https://aprova.fgbigwheel.com.br/wp-content/uploads/2022/01/fast-pass-stories-3.jpg" alt="">
</div>

<?= do_shortcode('[unidade-negocios force_id="9201" force_data_visita="" force_horario_visita="" force_calendario="" template_custom=""]'); ?>

<?php get_footer() ?>