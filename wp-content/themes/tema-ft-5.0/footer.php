</body>
<?php 
$id_logo = get_theme_mod('footer_logo');
$url_logo = wp_get_attachment_url($id_logo); 
?>
<style>
    footer {
        background-color: <?php echo get_theme_mod('footer_background_color')?>; 
        color: <?php echo get_theme_mod('footer_font_color')?>
    }
    footer a {
        color: <?php echo get_theme_mod('footer_link_color')?>
    }

    footer a:hover {
        color: <?php echo get_theme_mod('footer_link_hover_color')?>
    }
</style>
<footer class="pb-5" style="">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 text-center pt-5 align-self-center footer-logo">
                <img class="img-fluid" src="<?php echo $url_logo ?>" alt="">
                </br>
                <small>© <?php echo date('Y'); ?> Todos os direitos reservados.</small>
                </br>
                <small>Desenvolvido por <a href="https://alquimidia.com.br" target="_blank" rel="noopener noreferrer">Alquimídia</a></small>
            </div>
            <div class="col-lg-4 col-md-8 pt-5 align-self-center">
                <?php dynamic_sidebar('funcionamento')?>
            </div>
            <div class="col-lg-5 col-md-12 pt-5 align-self-center">
                <div class="row">
                    <div class="col-md-4">
                    <?php dynamic_sidebar('footer-1')?>  
                    </div>
                    <div class="col-md-4">
                    <?php dynamic_sidebar('footer-2')?>  
                    </div>
                    <div class="col-md-4">
                    <?php dynamic_sidebar('footer-3')?>  
                    </div>
                    <div class="col-md-12 pt-2 text-right">
                    <?php dynamic_sidebar('footer-4')?>  
                    <div><a href="https://transparencyreport.google.com/safe-browsing/search?url=<?php echo $_SERVER['HTTP_HOST'];?>" target="_blank"><img src="https://frameticket.com.br/selos/selos-seguranca.png" alt="Site Seguro"></a></div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <?php wp_footer();?>
</footer>
</html>