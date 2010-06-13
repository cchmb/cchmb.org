<?php
/**
 * Template Name: Sermons Page
 */

query_posts( array(
	'post_type' => 'sermon',
	'paged' => get_query_var('paged'),
));
?>
 
<?php get_header(); ?>

		<div id="container">
			<div id="content" role="main">

			<?php
			/* Run the loop to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-index.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'index' );
			?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
