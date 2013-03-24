      <nav id="site-navigation" class="navigation-main" role="navigation">
        <a href="#site-navigation" id="menu-toggle"><?php _e('Menu', 'pdx'); ?></a>

        <form id="searchform" method="get" action="/">
          <label for="s" class="assistive-text">Search</label>
          <input type="search" name="s" id="s" placeholder="Search" />
          <input type="submit" id="searchsubmit" value="Search" />
        </form>

        <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
      </nav>
