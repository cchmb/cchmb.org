<?php while ( have_posts() ) : the_post(); ?>
<?php
  if ( has_post_thumbnail() ) {
    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
    $header_attr = ' class="header-image"';
    $header_attr .= ' style="background:url(\'' . $thumbnail[0] . '\'); width: 100%; height: 300px;"';
  }

?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header<?php echo $header_attr; ?>>
        <?php get_template_module('entry/title'); ?>
      </header>
      <?php get_template_module('entry/content'); ?>
      <?php comments_template( '', true ); ?>
    </article><!-- #post-<?php the_ID(); ?> -->
<?php endwhile; ?>

