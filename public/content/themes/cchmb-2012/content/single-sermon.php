<?php
  global $post;
?>
<?php while ( have_posts() ) : the_post(); ?>
<?php 
  $audio_url = get_post_meta($post->ID, '_sermon_audio', true);
  $youtube_id = get_post_meta($post->ID, '_sermon_youtube_id', true);
?>
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ( $youtube_id ) : ?>
      <div class="sermon-video">
        <iframe width="700" height="386" src="http://www.youtube.com/embed/<?php echo $youtube_id; ?>" frameborder="0" allowfullscreen></iframe>
      </div>
    <?php endif; ?>
    <?php get_template_module('entry/title'); ?>

    <ul id="tabs">
      <li><a href="#description">Description</a></li>
      <li><a href="#notes" title="For you note-takers">Notes</a></li>
      <li><a href="#media">Media</a></li>
    </ul>

    <div id="panes">
      <section id="description">
        <?php get_template_module('entry/excerpt'); ?>
      </section>

      <section id="notes">
        <?php get_template_module('entry/content'); ?>
      </section>

      <section id="media">
        <ul>
          <?php if ( $youtube_id ) : ?>
            <li><a href="http://youtu.be/<?php echo $youtube_id; ?>">Watch on YouTube</a></li>
          <?php endif; ?>
          <?php if ( $audio_url ) : ?>
            <li><a href="<?php echo $audio_url; ?>">Audio MP3</a></li>
          <?php endif; ?>
        </ul>
      </section>
    </div>

  </article>
<?php endwhile; ?>

<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
<script>
  $('ul#tabs').tabs('div#panes > section', { history: true});
</script>
