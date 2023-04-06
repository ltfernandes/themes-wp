<!DOCTYPE html>
<html lang="pt" style="margin-top: 0 !important">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <script src="https://kit.fontawesome.com/baa464612a.js" crossorigin="anonymous"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <!-- header -->
    <style>
        #cadastre-se .btn-outline-primary {
        color: <?php echo get_theme_mod('button_new_account')?>;
        border-color: <?php echo get_theme_mod('button_new_account')?>;
        }
        #cadastre-se .btn-outline-primary:hover {
        color: <?php echo get_theme_mod('button_new_account_text')?>;
        background-color: <?php echo get_theme_mod('button_new_account')?>;
        }

        #cart .btn-success{
            color: <?php echo get_theme_mod('button_cart_text')?>;
            background-color: <?php echo get_theme_mod('button_cart')?>;
            border-color: <?php echo get_theme_mod('button_cart')?>;
        }

        #cart .btn-success:hover{
            color: <?php echo get_theme_mod('button_cart_text_hover')?>;
            background-color: <?php echo get_theme_mod('button_cart_hover')?>;
            border-color: <?php echo get_theme_mod('button_cart_hover')?>;
        }

        #dropdownMyAccountMenuButton.btn-info{
            color: <?php echo get_theme_mod('button_my_account_text')?>;
            background-color: <?php echo get_theme_mod('button_my_account')?>;
            border-color: <?php echo get_theme_mod('button_my_account')?>;
        }

        #dropdownMyAccountMenuButton.btn-info:hover{
            color: <?php echo get_theme_mod('button_my_account_text_hover')?>;
            background-color: <?php echo get_theme_mod('button_my_account_hover')?>;
            border-color: <?php echo get_theme_mod('button_my_account_hover')?>;
        }

        #dropdownMyAccountMenuButton.btn-info:not(:disabled):not(.disabled).active, .btn-info:not(:disabled):not(.disabled):active, .show>.btn-info.dropdown-toggle {
            color: <?php echo get_theme_mod('button_my_account_text_hover')?>;
            background-color: <?php echo get_theme_mod('button_my_account_hover')?>;
            border-color: <?php echo get_theme_mod('button_my_account_hover')?>;
        }

        #dropdownMyAccountMenuButton.btn-info:not(:disabled):not(.disabled).active:focus, .btn-info:not(:disabled):not(.disabled):active:focus, .show>.btn-info.dropdown-toggle:focus {
            box-shadow: 0 0 0 0.2rem <?php echo get_theme_mod('button_my_account_hover').'78'?>;
        }
       
    </style>
    <header>
        <nav class="navbar navbar-expand-md navbar-light bg-light" style="background-color: <?php echo get_theme_mod('header_background_color')?>!important">
            <div class="container">
                <div class="col-md-4 col-8">
                    <a class="navbar-brand" href="<?php echo get_home_url(); ?>">
                        <?php 
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

                        if (has_custom_logo()){
                            echo '<img src="'. esc_url($logo[0]). '" class="img-fluid">';
                        }
                        else {
                            echo '<img src="'. get_template_directory_uri(). '/assets/img/sem-logo-preta.png" class="img-fluid">';
                        }
                        ?>
                    </a>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMyAccount" aria-controls="navbarMyAccount" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Botões vísiveis somente em telas maiores -->
                <div class="col-md-8 collapse navbar-collapse" style="flex-direction: row-reverse;">
                <?php dynamic_sidebar('navbar-topo')?>  
                </div>
                
                <!-- Lista de itens aparentes em mobile -->
                <div class="col-md-8 collapse" style="padding-top: 2rem" id="navbarMyAccount">
                    <?php echo do_shortcode('[botoes-minha-conta mobile="S"]'); ?>
                    <?php wp_nav_menu( array('menu' => 'topo','menu_class'=>'list-group navbar-topo','container_class'=>'list-group-item')) ?>
                </div>

            </div>
        </nav>				
    </header>
    <?php dynamic_sidebar('navbar-basetopo')?>
