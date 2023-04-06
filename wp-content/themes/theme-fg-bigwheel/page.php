<?php get_header();?>
<div class="page-thumb container-fluid p-0">
    <?php the_post_thumbnail('post-thumbnail', array('class' => 'img-fluid img-slider w-100'))?>
</div>
<div class="container my-4 px-md-5">
    <h1><?php echo the_title();?></h1>
    <?php the_content();?>
</div>
<?php get_footer();?>