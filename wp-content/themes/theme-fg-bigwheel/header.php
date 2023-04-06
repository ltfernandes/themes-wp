<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>
<nav class="navbar sticky-top navbar-expand-lg bg-white navbar-menu-header">
    <div class="container-lg justify-content-center">
        <div class="row w-100">
            <div class="col-6 col-lg-3">
                <div class="row">
                    <a class="navbar-brand d-inline-block" href="<?= get_home_url(); ?>">
                        <?php
                        if (has_custom_logo()){
                            $custom_logo_id = get_theme_mod('custom_logo');
                            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                            echo '<img src="'. esc_url($logo[0]). '" class="img-fluid">';
                        }
                        ?>
                    </a>
                </div>
                <div class="row d-md-block d-lg-none mt-3">
                    <a class="btn btn-comprar" href="#">Comprar agora</a>
                </div>
            </div>
            <div class="col-6 col-lg-9 navbar-menu-toggler">
                <button class="navbar-toggler" id="button-nav-mobile" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenuMobile" aria-controls="navbarMenuMobile" aria-expanded="false">
                <div class="navbar-toggler-button " id="nav-icon-mobile">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                </button>
                <div class="collapse navbar-menu navbar-menu-mobile text-center text-nowrap" id="navbarMenuMobile">
                    <?php
                    wp_nav_menu([
                    'menu' => 'menu-header-mobile',
                    'menu_class' => 'nav nav-link justify-content-between',
                    'container_class' => 'container-menu-header-mobile',
                    ]);
                    ?>
                </div>
                <div class="collapse navbar-collapse navbar-menu">
                    <ul class="navbar-nav mb-2 mb-lg-0 w-100">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= get_home_url() . '/nossas-politicas'; ?>">Nossas Políticas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Portal do Parceiro</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-minha-conta" href="#"><i class="far fa-user"></i> Minha conta</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-comprar" href="#">Comprar agora</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<body <?php body_class(); ?>>