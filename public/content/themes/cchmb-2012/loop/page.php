<?php while ( have_posts() ) : the_post(); ?>
<?php
  $header_attr = '';
  if ( has_post_thumbnail() ) {
    $large = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
    $small = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');
    $header_attr = ' data-image-large="' . $large[0] . '" data-image-small="' . $small[0] . '"';
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

