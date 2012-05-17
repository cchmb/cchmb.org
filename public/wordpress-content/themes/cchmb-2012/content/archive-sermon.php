<section id="latest">
  <h2>Latest Sermons</h2>
<?php
    $args = array('post_type' => 'sermon', 'posts_per_page' => 3);
    $loop = new WP_Query($args);
    while( $loop->have_posts() ) : $loop->the_post();
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php if ( has_post_thumbnail() ): ?>
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a><br/>
      <?php endif; ?>
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </article>
  <?php endwhile; ?>
</section>

<section id="series">
  <h2>Sermon Series</h2>
  <?php
    $sermon_series = get_active_sermon_series();
    foreach($sermon_series as $series):
      $series_thumbnail = get_the_sermon_series_thumbnail($series->term_id, 'medium');
  ?>
    <article>
      <?php if ( $series_thumbnail ): ?>
        <a href="<?php echo get_term_link($series); ?>"><?php echo $series_thumbnail; ?></a><br />
      <?php endif; ?>
      <a href="<?php echo get_term_link($series); ?>"><?php echo $series->name ?></a>
    </article>
  <?php endforeach; ?>
</section>
