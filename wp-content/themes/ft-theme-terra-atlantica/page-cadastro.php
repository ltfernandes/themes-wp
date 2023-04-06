<?php
get_header();

get_template_part('template-parts/navcategoryandsearch');
?>
<div class="container">
<?php
do_shortcode('[cadastro template_custom=""]');
?>
</div>

<?php get_footer(); ?>