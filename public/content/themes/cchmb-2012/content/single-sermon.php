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
    <?php get_template_module('entry/meta'); ?>

    <div id="sermon-data">
      <section id="overview">
        <h2><?php _e('Overview', 'cchmb'); ?></h2>
        <?php get_template_module('entry/excerpt'); ?>
      </section>

      <section id="notes">
        <h2><?php _e('Notes', 'cchmb'); ?></h2>
        <?php get_template_module('entry/content'); ?>
      </section>

      <section id="media">
        <h2><?php _e('Media', 'cchmb'); ?></h2>
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
    <h2 class="series-title"><?php echo $series->name; ?></h2>
    <ul>
<?php foreach ($sermon_ids as $sermon_id): 
      $class = $post->ID == $sermon_id ? ' class="current"' : '';
?>
      <li<?php echo $class; ?>>
        <a href="<?php echo get_permalink($sermon_id); ?>">
          <span class="title"><?php echo get_the_title($sermon_id); ?></span>
          <time datetime="<?php esc_attr_e( get_the_time('c', $sermon_id) ); ?>"><?php
            esc_html_e( cchmb_get_the_date('', $sermon_id) ); ?></time>
        </a>
      </li>
<?php endforeach ?>
    </ul>
  </div>

<?php endwhile; ?>
