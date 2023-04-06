<?php get_header(); ?>
<?php get_template_part('template-parts/navcategoryandsearch') ?>
<div class="container">
    <div class="row pl-3 pr-3">
        <div class="col-md-8 col-lg-9 mb-3">
            <div class="col-md-12 box-div" style="min-height: 90%;">
                <?php
                //loop de posts
                if (have_posts()) : ?>
                    <h4 class="blue-title-search">Resultado da pesquisa por: <em><?php the_search_query(); ?></em></h4>
                    <?php
                    while (have_posts()) : the_post();
                    ?>

                        <?php get_template_part('template-parts/searchlist') ?>

                    <?php
                    endwhile;
                else :
                    ?>
                    <div style="padding:2rem; text-align: center">
                        <h4 class="blue-title-search"><em>Ops! Nenhum resultado foi encontrado para sua pesquisa.</em></h4>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!--Banner Lateral-->
        <div class="col-md-4 col-lg-3 mb-3">
            <?php dynamic_sidebar('sidebar-lateral') ?>
        </div>
    </div>
</div>
</div>
<?php get_footer(); ?>