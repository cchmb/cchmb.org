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
				<?php get_template_part( 'loop', 'sermons' ); ?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
