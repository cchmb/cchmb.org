<?php
  $series = get_queried_object();
?>

<div class="series-thumbnail" style="float: left; margin-right: 1em;">
  <?php the_sermon_series_thumbnail('medium'); ?>
</div>
<h1 class="page-title"><?php esc_html_e($series->name); ?></h1>
<div class="archive-meta"><?php esc_html_e($series->description); ?></div>


<section style="clear: both;">
  <h2><?php _e('Sermons', 'sermons'); ?></h2>

  <?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php if ( has_post_thumbnail() ): ?>
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a><br/>
      <?php endif; ?>
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </article>
  <?php endwhile; ?>
</section>
