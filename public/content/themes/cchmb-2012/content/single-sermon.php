<?php
  global $post;

?>
<?php while ( have_posts() ) : the_post(); ?>


<?php 
  $audio_url = get_post_meta($post->ID, '_sermon_audio', true);
  $youtube_iframe_url = get_sermon_youtube_url( $post, 'iframe');
  $youtube_url = get_sermon_youtube_url( $post );
?>
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ( $youtube_iframe_url ) : ?>
      <div class="sermon-video">
        <iframe src="<?php echo $youtube_iframe_url; ?>" frameborder="0"></iframe>
      </div>
    <?php endif; ?>
    <?php get_template_module('entry/title'); ?>

    <div id="sermon-data">
      <section id="description">
        <h4>Description</h4>
        <?php get_template_module('entry/excerpt'); ?>
      </section>

      <section id="notes">
        <h4>Notes</h4>
        <?php get_template_module('entry/content'); ?>
      </section>

      <section id="media">
        <h4>Media</h4>
        <ul>
          <?php if ( $youtube_url ) : ?>
            <li><a href="<?php echo $youtube_url; ?>">Watch on YouTube</a></li>
          <?php endif; ?>
          <?php if ( $audio_url ) : ?>
            <li><a href="<?php echo $audio_url; ?>">Audio MP3</a></li>
          <?php endif; ?>
        </ul>
      </section>
    </div>

  </article>


<?php
  // sidebar of other sermons in this series
  $series = get_primary_sermon_series($post->ID);
  $sermon_ids = get_sermon_ids_in_series( $series->term_id );
?>
  <div class="sermons-sidebar">
    <h3><?php echo $series->name; ?></h3>
    <ul>
<?php foreach ($sermon_ids as $sermon_id): 
      $class = $post->ID == $sermon_id ? ' class="active"' : '';
?>
      <li<?php echo $class; ?>>
        <a href="<?php echo get_permalink($sermon_id); ?>"><?php echo get_the_title($sermon_id); ?></a>
      </li>
<?php endforeach ?>
    </ul>
  </div>

<?php endwhile; ?>
