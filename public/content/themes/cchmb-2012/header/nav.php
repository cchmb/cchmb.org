      <nav id="nav" class="main-nav" role="navigation">
        <h1><a href="#footer"><?php _e('Menu', 'pdx'); ?></a></h1>

        <form id="searchform" method="get" action="/">
          <label for="s" class="assistive-text">Search</label>
          <input type="search" name="s" id="s" placeholder="Search" />
          <input type="submit" id="searchsubmit" value="Search" />
        </form>

        <?php wp_nav_menu( array( 'container' => '', 'theme_location' => 'primary' ) ); ?>
      </nav>
