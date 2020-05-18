<?php
/**
 * Migrate from Sermon Browser 2 Plugin.
 *
 * Based on Risen theme migration from ChurchThemes.
 *
 * @package    Church_Theme_Content
 * @subpackage Admin
 * @copyright  Copyright (c) 2018, ChurchThemes.com, Will Norris
 * @link       https://github.com/cchmb/cchmb.org
 * @license    GPLv2 or later
 * @since      2.1
 */

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*******************************************
 * PAGE
 *******************************************/

/**
 * Add page under Tools
 *
 * @since 2.1
 */
function ctcx_migrate_sb2_page() {

	// Add page.
	$page_hook = add_management_page(
		esc_html__( 'Sermon Browser 2 to Church Content Plugin', 'church-theme-content' ), // Page title.
		esc_html__( 'Sermon Browser to Church Content', 'church-theme-content' ), // Menu title.
		'switch_themes', // Capability (can manage Appearance > Widgets).
		'ctc-migrate-sb2', // Menu Slug.
		'ctcx_migrate_sb2_page_content' // Callback for displaying page content.
	);

}

add_action( 'admin_menu', 'ctcx_migrate_sb2_page' );

/**
 * Page content
 *
 * @since 2.1
 */
function ctcx_migrate_sb2_page_content() {

	?>
	<div class="wrap">

		<h2><?php esc_html_e( 'Sermon Browser 2 to Church Content Plugin', 'church-theme-content' ); ?></h2>

		<?php

		// Show results if have them.
		if ( ctcx_migrate_sb2_have_results() ) {

			ctcx_migrate_sb2_show_results();

			// Don't show content below.
			return;

		}

		?>

		<p>

			<?php

			echo wp_kses(
				sprintf(
					__( 'Click "Make Compatible" to make <b>sermons</b>, <b>series</b>, and <b>staff</b> in the Sermon Browser 2 plugin compatible with the <a href="%1$s" target="_blank">Church Content plugin</a>.', 'church-theme-content' ),
					ctc_ctcom_url( 'church-content', [ 'utm_campaign' => 'migrate-sb2' ] )
				),
				[
					'b' => [],
					'a' => [
						'href' => [],
						'target' => [],
					],
				]
			);

			?>

		</p>

		<p>

			<?php

			echo wp_kses(
				sprintf(
					__( 'This will not modify your content used by Sermon Browser. Instead, it will modify a copy of the content to be compatible with the Church Content plugin. This is a safeguard to ensure you can switch back to Sermon Browser. In any case, <a href="%1$s" target="_blank">make a full website backup</a> before running this tool and switching themes to be extra safe.', 'church-theme-content' ),
					ctc_ctcom_url( 'migrate-risen-backup' )
				),
				[
					'b' => [],
					'em' => [],
					'a' => [
						'href' => [],
						'target' => [],
					],
				]
			);

			?>

		</p>

		<form method="post">

			<?php wp_nonce_field( 'ctcx_migrate_sb2', 'ctcx_migrate_sb2_nonce' ); ?>

			<?php

			// Button arguments.
			$button_args = [
				'onclick' => "var button = this; setTimeout( function() { button.disabled = true; button.value=' " . esc_attr( __( "Processing. Please wait...", 'church-theme-content' ) ) . "' }, 10 ) ;return true;",
			];

			// WordPress version is too old.
			// wp_insert_post()'s meta_input argument requires WordPress 4.4+.
			if ( version_compare( get_bloginfo( 'version' ), '4.4', '<' ) ) {

				// Disable button.
				$button_args['disabled'] = 'disabled';

				// Show message.
				echo '<p><i>';
				echo wp_kses(
					__( '<strong>Update WordPress:</strong> Please update WordPress to the latest version before running this tool.', 'church-theme-content' ),
					[
						'strong' => [],
						'i' => [],
					]
				);
				echo '</i></p>';

			}

			// Show button.
			submit_button( esc_html( 'Make Compatible', 'church-theme-content' ), 'primary', 'submit', true, $button_args );

			?>

		</form>

		<?php if ( ! empty( $ctcx_migrate_sb2_results ) ) : ?>
			<p id="ctc-migrate-sb2-results">
				<?php echo wp_kses_post( $ctcx_migrate_sb2_results ); ?>
			</p>
			<br/>
		<?php endif; ?>

	</div>

	<?php

}

/**
 * Have results to show?
 *
 * @since 2.1
 * @global string $ctcx_migrate_sb2_results
 * @return bool True if have import results to show
 */
function ctcx_migrate_sb2_have_results() {

	global $ctcx_migrate_sb2_results;

	if ( ! empty( $ctcx_migrate_sb2_results ) ) {
		return true;
	}

	return false;

}

/**
 * Show results
 *
 * This is shown in place of page's regular content.
 *
 * @since 2.1
 * @global string $ctcx_migrate_sb2_results
 */
function ctcx_migrate_sb2_show_results() {

	global $ctcx_migrate_sb2_results;

	?>

	<h2 class="title"><?php echo esc_html( 'Finished', 'church-theme-content' ); ?></h2>

	<p>

		<?php

		echo wp_kses(
			sprintf(
				__( 'Your <b>sermons</b>, <b>events</b>, <b>locations</b> and <b>staff</b> in the Sermon Browser plugin have been made compatible with the <a href="%1$s" target="_blank">Church Content plugin</a>. Now you can switch to a newer theme from <a href="%2$s" target="_blank">ChurchThemes.com</a>. Read the <a href="%3$s" target="_blank">Switching from Sermon Browser</a> guide for additional instructions.', 'church-theme-content' ),
				ctc_ctcom_url( 'church-content', [ 'utm_campaign' => 'migrate-sb2' ] ),
				ctc_ctcom_url( 'home', [ 'utm_campaign' => 'migrate-sb2' ] ),
				ctc_ctcom_url( 'migrate-sb2' )
			),
			[
				'b' => [],
				'a' => [ 
					'href' => [],
					'target' => [],
				],
			]
		);

		?>

	</p>

	<p id="ctc-migrate-sb2-results">

		<?php

		$results = $ctcx_migrate_sb2_results;

		echo $results;

		?>

	</p>

	<?php

}

/*******************************************
 * PROCESSING
 *******************************************/

/**
 * Process button submission.
 *
 * @since 2.1
 */
function ctcx_migrate_sb2_submit() {

	// Check nonce for security since form was submitted.
	// check_admin_referer prints fail page and dies.
	if ( ! empty( $_POST['submit'] ) && check_admin_referer( 'ctcx_migrate_sb2', 'ctcx_migrate_sb2_nonce' ) ) {

		// Process content.
		ctcx_migrate_sb2_process();

	}

}

add_action( 'load-tools_page_ctc-migrate-sb2', 'ctcx_migrate_sb2_submit' );

/**
 * Add hooks to make Sermon Browser series and preachers appear as taxonomies
 * on sermons. In Sermon Browser, these are managed as custom post types, but
 * making them look like taxonomies simplifies the migration process.
 */
function ctcx_migrate_sb2_prepare_taxonomies() {
	register_taxonomy('mbsb_series', 'mbsb_sermon', [
		'labels' => [
			'name' => 'Sermon Series'
		],
		'hierarchical' => true
	]);
	register_taxonomy('mbsb_preacher', 'mbsb_sermon', [
		'labels' => [
			'name' => 'Preacher'
		],
		'hierarchical' => true
	]);

	// make series and preacher appear as taxonomies on sermon
	add_filter('get_terms', function($terms, $taxonomies, $args, $query) {
		if ( ! empty($query->query_vars['object_ids']) ) {
			return $terms;
		}
		foreach($taxonomies as $taxonomy) {
			if ($taxonomy == 'mbsb_series' OR $taxonomy == 'mbsb_preacher') {
				$posts = get_posts( [
					'posts_per_page'   => -1,
					'post_type'       => $taxonomy,
					'post_status'      => 'publish',
				] );
				foreach($posts as $post) {
					$term = new WP_Term((object) [
						'term_id' => ctcx_migrate_sb2_id($post->ID, $taxonomy),
						'name' => $post->post_title,
						'slug' => $post->post_name,
						'taxonomy' => $taxonomy,
					]);
					$terms[] = $term;
				}
			}
		}
		return $terms;
	}, 10, 4);

	add_filter( 'get_object_terms', function($terms, $object_ids, $taxonomies, $args ) {
		foreach($taxonomies as $taxonomy) {
			if ($taxonomy == 'mbsb_series' OR $taxonomy == 'mbsb_preacher') {
				$key = substr($taxonomy, 5);
				$post_id = get_post_meta($object_ids[0], $key, true);
				$post = get_post($post_id);
				$term = new WP_Term((object) [
					'term_id' => ctcx_migrate_sb2_id($post->ID, $taxonomy),
					'name' => $post->post_title,
					'slug' => $post->post_name,
					'taxonomy' => $taxonomy,
				]);
				$terms[] = $term;
			}
		}
		return $terms;
	}, 10, 4);
}

/**
 * Process content conversion.
 *
 * @since 2.1
 * @global string $ctcx_migrate_sb2_results
 */
function ctcx_migrate_sb2_process() {

	global $ctcx_migrate_sb2_results;

	// Prevent interruption.
	set_time_limit( 0 );
	ignore_user_abort( true );

	// Begin results.
	$results = '';

	// Post types.
	$post_types = [
		'mbsb_sermon' => [
			'ctc_post_type' => 'ctc_sermon',
			'attachments' => [
				'media.cchmb.org' => '_ctc_sermon_audio',
				'youtube.com' => '_ctc_sermon_video',
				'docs.google.com' => '_ctcx_sermon_slides',
			],
			'taxonomies' => [
				'mbsb_series'                        => 'ctc_sermon_series',
				'mbsb_preacher'                      => 'ctc_sermon_speaker',
			],
		]
	];

	ctcx_migrate_sb2_prepare_taxonomies();

	// Loop post types.
	foreach ( $post_types as $post_type => $post_type_data ) {

		// Get taxonomies for post type.
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );

		// Loop taxonomies.
		$terms_map = []; // map old ID to new ID for this post type.
		foreach ( $taxonomies as $taxonomy => $taxonomy_object ) {

			// Get taxonomy terms.
			$terms = get_terms( $taxonomy );

			// Taxonomy name.
			$results .= '<h4>' . esc_html( $taxonomy_object->label ) . ' (' . esc_html( count( $terms ) ) . ')</h4>';

			// Loop terms.
			foreach ( $terms as $term ) {

				$results .= '<div>' . esc_html( $term->name ) . '</div>';

				// Insert term if not already added to new taxonomy.
				$term_id = ctcx_migrate_sb2_duplicate_post_term( $term, $post_type_data );

				// Update terms map (old ID's to new).
				if ( $term_id ) {
					$terms_map[ $taxonomy ][ $term->term_id ] = $term_id;
				}

			}

			// Loop terms again (now that all added and have IDs) to set parents.
			foreach ( $terms as $term ) {

				// Has parent?
				if ( ! empty( $term->parent ) ) {

					// Get new term ID.
					$term_id = $terms_map[ $taxonomy ][ $term->term_id ];

					// Get parent's new term ID.
					$parent_id = $terms_map[ $taxonomy ][ $term->parent ];

					// Set parent.
					if ( $parent_id ) {

						wp_update_term( $term_id, $post_type_data['taxonomies'][ $term->taxonomy ], [
							'parent' => $parent_id,
						] );

					}

				}

			}

		}

		// Get posts.
		$posts = get_posts( [
			'posts_per_page'   => -1,
			'post_type'        => $post_type,
			'post_status'      => 'publish',
		] );

		// Post type data.
		$post_type_object = get_post_type_object( $post_type );
		$post_type_label = $post_type_object->labels->name;

		// Post type name.
		$results .= '<h4>' . esc_html( $post_type_label ) . ' (' . esc_html( count( $posts ) ) . ')</h4>';

		// Loop posts.
		foreach ( $posts as $post ) {

			$post_id = ctcx_migrate_sb2_duplicate_post( $post, $post_type_data, $terms_map );

			$results .= '<div>' . esc_html( $post->post_title ) . '</div>';

		}

	}

	// Additional.
	$results .= '<h4>Miscellaneous</h4>';

		// Set Google Maps API Key.
		if ( function_exists( 'risen_option' ) ) {

			// Get key from Theme Options.
			$google_maps_api_key = risen_option( 'gmaps_api_key' );

			// Set key in Church Content settings.
			if ( $google_maps_api_key ) {

				// Update option.
				ctc_update_setting( 'google_maps_api_key', $google_maps_api_key );

				// Results.
				$results .= '<div>' . __( 'Google Maps API Key set', 'church-theme-content' ) . '</div>';

			}

		}

	// Make results available for display.
	$ctcx_migrate_sb2_results = $results;

}

/**
 * Duplicate post (as new post type).
 *
 * @since 2.1
 * @param object $original_term Original term to duplicate.
 * @param string $post_type_data Array with data for handling duplication.
 * @return int $term_id New term's ID.
 */
function ctcx_migrate_sb2_duplicate_post_term( $original_term, $post_type_data ) {

	$term_id = 0;

	// Get new taxonomy.
	$new_taxonomy = ! empty( $post_type_data['taxonomies'][ $original_term->taxonomy ] ) ? $post_type_data['taxonomies'][ $original_term->taxonomy ] : false;

	// Have new taxonomy.
	if ( $new_taxonomy ) {

		// Term not already added (otherwise get ID).
		$term = term_exists( $original_term->name, $new_taxonomy );
		if ( ! ( 0 !== $term && null !== $term ) ) {

			// Duplicate as new term of new taxonomy (or update if was already converted).
			// Won't add it already exists (but won't update either).
			$term = wp_insert_term( $original_term->name, $new_taxonomy, [
				'description' => $original_term->description,
				'slug' => $original_term->slug,
			] );

		}

		// Get new term ID.
		$term_id = isset( $term['term_id'] ) ? $term['term_id'] : 0;

	}

	// Return new ID.
	return $term_id;

}

/**
 * Duplicate post (as new post type).
 *
 * @since 2.1
 * @param object $post Original post to duplicate.
 * @param string $post_type_data Array with data for handling duplication.
 * @param string $terms_map Array mapping original term ID to new term ID.
 * @return int $post_id New post's ID.
 */
function ctcx_migrate_sb2_duplicate_post( $original_post, $post_type_data, $terms_map ) {

	// Original post ID.
	$original_post_id = $original_post->ID;

	// Get post if was already converted, so can update instead of adding again.
	$converted_post = get_page_by_path( $original_post->post_name, OBJECT, $post_type_data['ctc_post_type'] );
	$post_id = isset( $converted_post->ID ) ? $converted_post->ID : 0; // 0 causes wp_insert_post() to make new post versus updating existing.

	// Duplicate as new post type (or update if was already converted).
	$post = $original_post;
	$post->post_type = $post_type_data['ctc_post_type']; // use new post type.
	$post->ID = $post_id; // update if was already added so can run this tool again safely.
	$post->meta_input = ctcx_migrate_sb2_meta_input( $original_post_id, $post_type_data['attachments'] ); // copy post meta.
	$post->tax_input = isset( $post_type_data['taxonomies'] ) ? ctcx_migrate_sb2_tax_input( $original_post_id, $post_type_data['taxonomies'], $terms_map ) : []; // set taxonomy terms.
	unset( $post->guid ); // generate a new GUID.
	$post_id = wp_insert_post( $post ); // add or update and get post ID if new.

	// Set featured image.
	$thumbnail_id = get_post_thumbnail_id( $original_post_id );
	if ( $thumbnail_id ) {
		set_post_thumbnail( $post_id, $thumbnail_id );
	}

	// Procesing after save.
	switch ( $post_type_data['ctc_post_type'] ) {

		case 'ctc_sermon' :

			// Update the enclosure for this sermon.
			ctc_do_enclose( $post_id );

			break;

		case 'ctc_event' :

			// Correct event to update hidden event DATETIME fields, etc.
			ctc_correct_event( $post_id );

			break;

	}

	return $post_id;

}

/*******************************************
 * HELPERS
 *******************************************/

/**
 * Build meta_input array.
 *
 * Return array to let wp_insert_post() set custom fields.
 *
 * @since 2.1
 * @param int $post_id Post ID to get meta for.
 * @param array $attachment_keys Array of attachment keys.
 * @return array $meta_input Custom fields as array (key / value pairs).
 */
function ctcx_migrate_sb2_meta_input( $post_id, $attachment_keys ) {

	$meta_input = [];

	$attachments = get_post_meta( $post_id, 'attachments', false );
	foreach ($attachments as $attachment) {
		$value = $attachment["url"];
		foreach ( $attachment_keys as $host => $new_key ) {
			if (strpos($value, $host) != false) {
				$meta_input[ $new_key ] = $value;
			}
		}
	}

	$passages = new mbsb_passages(get_post_meta($post_id, 'passage_start'), get_post_meta($post_id, 'passage_end'));
	if ($passages->present) {
		$meta_input["_ctcx_sermon_passage"] = $passages->get_formatted();
	}

	$meta_input["_original_post_id"] = $post_id;

	return $meta_input;

}

/**
 * Build tax_input array.
 *
 * Return array to let wp_insert_post() set taxonomy terms.
 *
 * @since 2.1
 * @param array $original_post_id Original post's ID.
 * @param array $taxonomies Array of taxonomies.
 * @param array $terms_map Array mapping old ID's to new ID's.
 * @return array $tax_input Array of taxonomies with term IDs.
 */
function ctcx_migrate_sb2_tax_input( $original_post_id, $taxonomies, $terms_map ) {

	$tax_input = [];

	foreach ( $taxonomies as $old_taxonomy => $new_taxonomy ) {

		// Get original post's terms for this taxonomy.
		$terms = wp_get_post_terms( $original_post_id, $old_taxonomy );

		// Loop terms.
		foreach ( $terms as $term ) {

			// Hierarchical? Use IDs.
			if ( is_taxonomy_hierarchical( $old_taxonomy ) && ! empty( $terms_map[ $old_taxonomy ][ $term->term_id ] ) ) {

				// Add new term's ID to array.
				$tax_input[ $new_taxonomy ][] = $terms_map[ $old_taxonomy ][ $term->term_id ];

			}

			// Non-hierarchical, like tags (use names instead of IDs).
			elseif ( ! empty( $term->name ) ) {

				// Add new term's name to array.
				$tax_input[ $new_taxonomy ] = $term->name;

			}

		}

	}

	return $tax_input;
}

// Manufacture IDs for our fake series and preacher taxonomies so that they
// don't conflict with any real terms.
function ctcx_migrate_sb2_id($id, $taxonomy) {
	switch ($taxonomy) {
		case 'mbsb_series':
			return $id + 100000;
		case 'mbsb_preacher':
			return $id + 200000;
	}
	return $id;
}
