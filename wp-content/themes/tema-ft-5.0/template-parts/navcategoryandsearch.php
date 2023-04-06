<?php
if (get_theme_mod('header_with_search') <> 'N') {
?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container pt-2">
            <div class="col-md-9" id="categorylist">
                <div class="collapse navbar-collapse">
                    <?php echo do_shortcode('[categorias_lista]'); ?>
                </div>
            </div>
            <div class="col-md-3" id="searchform">
                <div class="collapse navbar-collapse" style="flex-direction: row-reverse;">
                    <?php echo do_shortcode('[form_busca_eventos]'); ?>
                </div>
            </div>
        </div>
    </nav>
<?php } ?>