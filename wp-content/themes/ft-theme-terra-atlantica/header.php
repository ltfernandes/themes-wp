<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <script src="<?php echo get_template_directory_uri() ?>/assets/js/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php 
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
    ?>
    <nav class="navbar navbar-expand-lg <?php echo is_home() ? 'navbar-dark' : 'navbar-light'?> fixed-top header pt-0"
        id="header">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand d-lg-none p-0" href="<?php echo get_home_url()?>"> <img
                    src="<?php echo esc_url($logo[0])  ?>" alt="Logo" class="img-fluid p-0"> </a>

            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 justify-content-center w-100">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"
                            href="<?php echo is_home() ? '' : site_url()?>#oparque">O Parque</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"
                            href="<?php echo is_home() ? '' : site_url()?>#atracoes">Atrações</a>
                    </li>
                    <li class="nav-item d-none d-lg-block">
                        <a class="navbar-brand" href="<?php echo get_home_url()?>"> <img
                                src="<?php echo esc_url($logo[0])  ?>" alt=""> </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"
                            href="<?php echo is_home() ? '' : site_url()?>#contato">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="<?php echo site_url()?>/blog">Blog</a>
                    </li>
                    <li class="nav-item <?php echo is_home() ? '' : 'd-none'?>">
                        <a class="nav-link" aria-current="page" href="<?php echo site_url()?>/ingressos">
                            <div class="btn btn-warning btn-sm">Ingressos</div>
                        </a>
                    </li>
                    <li class="nav-item <?php echo is_home() ? 'd-none' : ''?>">
                        <?php get_template_part('template-parts/dropdownMyAccount') ?>
                    </li>
                </ul>
            </div>
            <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header align-self-end">
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page"
                                href="<?php echo is_home() ? '' : site_url()?>#oparque">O Parque</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page"
                                href="<?php echo is_home() ? '' : site_url()?>#atracoes">Atrações</a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="navbar-brand" href="<?php echo get_home_url()?>"> <img
                                    src="<?php echo esc_url($logo[0])  ?>" alt=""> </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page"
                                href="<?php echo is_home() ? '' : site_url()?>#contato">Contato</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">Blog</a>
                        </li>
                        <li class="nav-item <?php echo is_home() ? '' : 'd-none'?>">
                            <a class="nav-link" aria-current="page" href="<?php echo site_url()?>/ingressos">
                                <div class="btn btn-warning btn-sm">Ingressos</div>
                            </a>
                        </li>
                        <li class="nav-item <?php echo is_home() ? 'd-none' : ''?>">
                            <?php get_template_part('template-parts/dropdownMyAccount') ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>