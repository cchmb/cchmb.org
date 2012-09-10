        <div class="entry-meta">
          <?php
            printf( __( '<strong>Speaker:</strong> %1$s<br/><strong>Date:</strong> %2$s', 'pdx' ),
              // author
              sprintf( '<span class="author entry-author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
                get_author_posts_url( get_the_author_meta( 'ID' ) ),
                sprintf( esc_attr__( 'View all posts by %s', 'twentyten' ), get_the_author() ),
                get_the_author()
              ),

              // date
              sprintf( '<time datetime="%1$s" class="entry-date">%2$s</time>',
                esc_attr( get_the_time('c') ),
                get_the_date()
              )
            );
          ?>
        </div><!-- .entry-meta -->
