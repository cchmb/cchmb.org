      <nav id="nav" class="main-nav" role="navigation">
        <h1 class="assistive-text"><?php _e('Menu', 'pdx'); ?></h1>

        <form id="searchform" method="get" action="/">
          <label for="s" class="assistive-text">Search</label>
          <input type="search" name="s" id="s" placeholder="Search" />
          <input type="submit" id="searchsubmit" value="Search" />
        </form>

        <?php wp_nav_menu( array( 'container' => '', 'theme_location' => 'primary' ) ); ?>
      </nav>
