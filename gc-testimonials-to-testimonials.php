<?php
/**
 * Plugin Name: GC Testimonials to Testimonials by Aihrus
 * Plugin URI: http://wordpress.org/plugins/gc-testimonials-to-testimonials/
 * Description: Migrate GC Testimonials entries to Testimonials by Aihrus custom post types.
 * Version: 1.1.2
 * Author: Michael Cannon
 * Author URI: http://aihr.us/resume/
 * License: GPLv2 or later
 */


/**
 * Copyright 2013 Michael Cannon (email: mc@aihr.us)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

define( 'GCT2T_AIHR_VERSION', '1.0.1' );
define( 'GCT2T_BASE', plugin_basename( __FILE__ ) );
define( 'GCT2T_DIR', plugin_dir_path( __FILE__ ) );
define( 'GCT2T_DIR_LIB', GCT2T_DIR . '/lib' );
define( 'GCT2T_NAME', 'GC Testimonials to Testimonials by Aihrus' );
define( 'GCT2T_REQ_BASE', 'testimonials-widget/testimonials-widget.php' );
define( 'GCT2T_REQ_NAME', 'Testimonials by Aihrus' );
define( 'GCT2T_REQ_SLUG', 'testimonials-widget' );
define( 'GCT2T_REQ_VERSION', '2.17.2' );
define( 'GCT2T_VERSION', '1.1.2' );

require_once GCT2T_DIR_LIB . '/requirements.php';

if ( ! gct2t_requirements_check() ) {
	return false;
}

require_once GCT2T_DIR_LIB . '/aihrus/class-aihrus-common.php';
require_once GCT2T_DIR_LIB . '/class-gc-testimonials-to-testimonials-settings.php';


class Gc_Testimonials_to_Testimonials extends Aihrus_Common {
	const BASE    = GCT2T_BASE;
	const ID      = 'gc-testimonials-to-testimonials';
	const SLUG    = 'gct2t_';
	const VERSION = GCT2T_VERSION;

	const GCT_PT = 'testimonial';

	private static $post_types;

	public static $class = __CLASS__;
	public static $menu_id;
	public static $notice_key;
	public static $scripts = array();
	public static $settings_link;
	public static $styles        = array();
	public static $styles_called = false;

	public static $post_id;


	public function __construct() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'init', array( __CLASS__, 'init' ) );
	}


	public static function admin_init() {
		self::update();

		add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action_links' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );

		self::$settings_link = '<a href="' . get_admin_url() . 'edit.php?post_type=' . Testimonials_Widget::PT . '&page=' . Gc_Testimonials_to_Testimonials_Settings::ID . '">' . __( 'Settings', 'gc-testimonials-to-testimonials' ) . '</a>';
	}


	public static function admin_menu() {
		self::$menu_id = add_submenu_page( 'edit.php?post_type=' . Testimonials_Widget::PT, esc_html__( 'GC Testimonials to Testimonials Migrator', 'gc-testimonials-to-testimonials' ), esc_html__( 'GCT Migrator', 'gc-testimonials-to-testimonials' ), 'manage_options', self::ID, array( __CLASS__, 'user_interface' ) );

		add_action( 'admin_print_scripts-' . self::$menu_id, array( __CLASS__, 'scripts' ) );
		add_action( 'admin_print_styles-' . self::$menu_id, array( __CLASS__, 'styles' ) );

		add_screen_meta_link(
			'gct2t_settings_link',
			esc_html__( 'GC Testimonials to Testimonials Settings', 'gc-testimonials-to-testimonials' ),
			admin_url( 'edit.php?post_type=' . Testimonials_Widget::PT . '&page=' . Gc_Testimonials_to_Testimonials_Settings::ID ),
			self::$menu_id,
			array( 'style' => 'font-weight: bold;' )
		);
	}


	public static function init() {
		load_plugin_textdomain( self::ID, false, 'gc-testimonials-to-testimonials/languages' );

		add_action( 'wp_ajax_ajax_process_post', array( __CLASS__, 'ajax_process_post' ) );

		self::set_post_types();
	}


	public static function plugin_action_links( $links, $file ) {
		if ( self::BASE == $file ) {
			array_unshift( $links, self::$settings_link );

			$link = '<a href="' . get_admin_url() . 'edit.php?post_type=' . Testimonials_Widget::PT . '&page=' . self::ID . '">' . esc_html__( 'Migrate', 'gc-testimonials-to-testimonials' ) . '</a>';
			array_unshift( $links, $link );
		}

		return $links;
	}


	public static function activation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
	}


	public static function deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
	}


	public static function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;

		global $wpdb;
		
		require_once GCT2T_DIR_LIB . '/class-gc-testimonials-to-testimonials-settings.php';

		$delete_data = gct2t_get_option( 'delete_data', false );
		if ( $delete_data ) {
			delete_option( Gc_Testimonials_to_Testimonials_Settings::ID );
			$wpdb->query( 'OPTIMIZE TABLE `' . $wpdb->options . '`' );
		}
	}


	public static function plugin_row_meta( $input, $file ) {
		if ( self::BASE != $file )
			return $input;

		$disable_donate = tw_get_option( 'disable_donate' );
		if ( $disable_donate )
			return $input;

		$links = array(
			self::$donate_link,
		);

		global $TW_Premium;
		if ( ! isset( $TW_Premium ) )
			$links[] = TW_PREMIUM_LINK;

		$input = array_merge( $input, $links );

		return $input;
	}


	public static function set_post_types() {
		self::$post_types   = array();
		self::$post_types[] = self::GCT_PT;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.ExitExpression)
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function user_interface() {
		// Capability check
		if ( ! current_user_can( 'manage_options' ) )
			wp_die( self::$post_id, esc_html__( "Your user account doesn't have permission to access this.", 'gc-testimonials-to-testimonials' ) );

?>

<div id="message" class="updated fade" style="display:none"></div>

<div class="wrap wpsposts">
	<div class="icon32" id="icon-tools"></div>
	<h2><?php _e( 'GC Testimonials to Testimonials Migrator', 'gc-testimonials-to-testimonials' ); ?></h2>

<?php
		// If the button was clicked
		if ( ! empty( $_POST[ self::ID ] ) || ! empty( $_REQUEST['posts'] ) ) {
			// Form nonce check
			check_admin_referer( self::ID );

			// Create the list of image IDs
			if ( ! empty( $_REQUEST['posts'] ) ) {
				$posts = explode( ',', trim( $_REQUEST['posts'], ',' ) );
				$posts = array_map( 'intval', $posts );
			} else {
				$posts = self::get_posts_to_process();
			}

			$count = count( $posts );
			if ( ! $count ) {
				echo '	<p>' . _e( 'All done. No posts needing processing found.', 'gc-testimonials-to-testimonials' ) . '</p></div>';
				return;
			}

			$posts = implode( ',', $posts );
			self::show_status( $count, $posts );
		} else {
			// No button click? Display the form.
			self::show_greeting();
		}
?>
	</div>
<?php
	}


	public static function get_posts_to_process() {
		global $wpdb;

		$query = array(
			'post_type' => self::$post_types,
			'orderby' => 'post_modified',
			'order' => 'DESC',
		);

		$include_ids = gct2t_get_option( 'posts_to_import' );
		if ( $include_ids ) {
			$query[ 'post__in' ] = str_getcsv( $include_ids );
		} else {
			$query['posts_per_page'] = 1;
			$query['meta_query']     = array(
				array(
					'key' => 'TBD',
					'value' => '',
					'compare' => '!=',
				),
			);
			unset( $query['meta_query'] );
		}

		$skip_ids = gct2t_get_option( 'skip_importing_post_ids' );
		if ( $skip_ids )
			$query[ 'post__not_in' ] = str_getcsv( $skip_ids );

		$results  = new WP_Query( $query );
		$query_wp = $results->request;

		$limit = gct2t_get_option( 'limit' );
		if ( $limit )
			$query_wp = preg_replace( '#\bLIMIT 0,.*#', 'LIMIT 0,' . $limit, $query_wp );
		else
			$query_wp = preg_replace( '#\bLIMIT 0,.*#', '', $query_wp );

		$posts = $wpdb->get_col( $query_wp );

		return $posts;
	}


	public static function show_greeting() {
?>
	<form method="post" action="">
<?php wp_nonce_field( self::ID ); ?>

	<p><?php _e( 'Use this tool for migrating GC Testimonials entries to Testimonials custom post types.', 'gc-testimonials-to-testimonials' ); ?></p>

	<p><?php _e( 'This migration is not reversible. Backup your database beforehand or be prepared to delete each migrated testimonial manually.', 'gc-testimonials-to-testimonials' ); ?></p>

	<p><?php printf( esc_html__( 'Please review your %s before proceeding.', 'gc-testimonials-to-testimonials' ), self::$settings_link ); ?></p>

	<p><?php _e( 'To begin, just press the button below.', 'gc-testimonials-to-testimonials' ); ?></p>

	<p><input type="submit" class="button hide-if-no-js" name="<?php echo self::ID; ?>" id="<?php echo self::ID; ?>" value="<?php _e( 'Migrate GC Testimonials to Testimonials', 'gc-testimonials-to-testimonials' ) ?>" /></p>

	<noscript><p><em><?php _e( 'You must enable Javascript in order to proceed!', 'gc-testimonials-to-testimonials' ) ?></em></p></noscript>

	</form>
<?php
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function show_status( $count, $posts ) {
		echo '<p>' . esc_html__( 'Please be patient while this script run. This can take a while, up to a minute per post. Do not navigate away from this page until this script is done or the import will not be completed. You will be notified via this page when the import is completed.', 'gc-testimonials-to-testimonials' ) . '</p>';

		echo '<p>' . sprintf( esc_html__( 'Estimated time required to import is %1$s minutes.', 'gc-testimonials-to-testimonials' ), ( $count * 1 ) ) . '</p>';

		$text_goback = ( ! empty( $_GET['goback'] ) ) ? sprintf( __( 'To go back to the previous page, <a href="%s">click here</a>.', 'gc-testimonials-to-testimonials' ), 'javascript:history.go(-1)' ) : '';

		$text_failures = sprintf( __( 'All done! %1$s posts were successfully processed in %2$s seconds and there were %3$s failures. To try importing the failed posts again, <a href="%4$s">click here</a>. %5$s', 'gc-testimonials-to-testimonials' ), "' + rt_successes + '", "' + rt_totaltime + '", "' + rt_errors + '", esc_url( wp_nonce_url( admin_url( 'tools.php?page=' . self::ID . '&goback=1' ) ) . '&posts=' ) . "' + rt_failedlist + '", $text_goback );

		$text_nofailures = sprintf( esc_html__( 'All done! %1$s posts were successfully processed in %2$s seconds and there were no failures. %3$s', 'gc-testimonials-to-testimonials' ), "' + rt_successes + '", "' + rt_totaltime + '", $text_goback );
?>

	<noscript><p><em><?php _e( 'You must enable Javascript in order to proceed!', 'gc-testimonials-to-testimonials' ) ?></em></p></noscript>

	<div id="wpsposts-bar" style="position:relative;height:25px;">
		<div id="wpsposts-bar-percent" style="position:absolute;left:50%;top:50%;width:300px;margin-left:-150px;height:25px;margin-top:-9px;font-weight:bold;text-align:center;"></div>
	</div>

	<p><input type="button" class="button hide-if-no-js" name="wpsposts-stop" id="wpsposts-stop" value="<?php _e( 'Abort Migrating Posts', 'gc-testimonials-to-testimonials' ) ?>" /></p>

	<h3 class="title"><?php _e( 'Status', 'gc-testimonials-to-testimonials' ) ?></h3>

	<p>
		<?php printf( esc_html__( 'Total Testimonials: %s', 'gc-testimonials-to-testimonials' ), $count ); ?><br />
		<?php printf( esc_html__( 'Testimonials Migrated: %s', 'gc-testimonials-to-testimonials' ), '<span id="wpsposts-debug-successcount">0</span>' ); ?><br />
		<?php printf( esc_html__( 'Migration Failures: %s', 'gc-testimonials-to-testimonials' ), '<span id="wpsposts-debug-failurecount">0</span>' ); ?>
	</p>

	<ol id="wpsposts-debuglist">
		<li style="display:none"></li>
	</ol>

	<script type="text/javascript">
	// <![CDATA[
		jQuery(document).ready(function($){
			var i;
			var rt_posts = [<?php echo esc_attr( $posts ); ?>];
			var rt_total = rt_posts.length;
			var rt_count = 1;
			var rt_percent = 0;
			var rt_successes = 0;
			var rt_errors = 0;
			var rt_failedlist = '';
			var rt_resulttext = '';
			var rt_timestart = new Date().getTime();
			var rt_timeend = 0;
			var rt_totaltime = 0;
			var rt_continue = true;

			// Create the progress bar
			$( "#wpsposts-bar" ).progressbar();
			$( "#wpsposts-bar-percent" ).html( "0%" );

			// Stop button
			$( "#wpsposts-stop" ).click(function() {
				rt_continue = false;
				$( '#wpsposts-stop' ).val( "<?php echo esc_html__( 'Stopping, please wait a moment.', 'gc-testimonials-to-testimonials' ); ?>" );
			});

			// Clear out the empty list element that's there for HTML validation purposes
			$( "#wpsposts-debuglist li" ).remove();

			// Called after each import. Updates debug information and the progress bar.
			function WPSPostsUpdateStatus( id, success, response ) {
				$( "#wpsposts-bar" ).progressbar( "value", ( rt_count / rt_total ) * 100 );
				$( "#wpsposts-bar-percent" ).html( Math.round( ( rt_count / rt_total ) * 1000 ) / 10 + "%" );
				rt_count = rt_count + 1;

				if ( success ) {
					rt_successes = rt_successes + 1;
					$( "#wpsposts-debug-successcount" ).html(rt_successes);
					$( "#wpsposts-debuglist" ).append( "<li>" + response.success + "</li>" );
				}
				else {
					rt_errors = rt_errors + 1;
					rt_failedlist = rt_failedlist + ',' + id;
					$( "#wpsposts-debug-failurecount" ).html(rt_errors);
					$( "#wpsposts-debuglist" ).append( "<li>" + response.error + "</li>" );
				}
			}

			// Called when all posts have been processed. Shows the results and cleans up.
			function WPSPostsFinishUp() {
				rt_timeend = new Date().getTime();
				rt_totaltime = Math.round( ( rt_timeend - rt_timestart ) / 1000 );

				$( '#wpsposts-stop' ).hide();

				if ( rt_errors > 0 ) {
					rt_resulttext = '<?php echo $text_failures; ?>';
				} else {
					rt_resulttext = '<?php echo $text_nofailures; ?>';
				}

				$( "#message" ).html( "<p><strong>" + rt_resulttext + "</strong></p>" );
				$( "#message" ).show();
			}

			// Regenerate a specified image via AJAX
			function WPSPosts( id ) {
				$.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {
						action: "ajax_process_post",
						id: id
					},
					success: function( response ) {
						if ( response.success ) {
							WPSPostsUpdateStatus( id, true, response );
						}
						else {
							WPSPostsUpdateStatus( id, false, response );
						}

						if ( rt_posts.length && rt_continue ) {
							WPSPosts( rt_posts.shift() );
						}
						else {
							WPSPostsFinishUp();
						}
					},
					error: function( response ) {
						WPSPostsUpdateStatus( id, false, response );

						if ( rt_posts.length && rt_continue ) {
							WPSPosts( rt_posts.shift() );
						}
						else {
							WPSPostsFinishUp();
						}
					}
				});
			}

			WPSPosts( rt_posts.shift() );
		});
	// ]]>
	</script>
<?php
	}


	/**
	 * Migrate a single post ID (this is an AJAX handler)
	 *
	 * @SuppressWarnings(PHPMD.ExitExpression)
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function ajax_process_post() {
		error_reporting( 0 ); // Don't break the JSON result
		header( 'Content-type: application/json' );
		self::$post_id = intval( $_REQUEST['id'] );

		$post = get_post( self::$post_id, ARRAY_A );
		if ( ! $post || ! in_array( $post['post_type'], self::$post_types )  )
			die( json_encode( array( 'error' => sprintf( esc_html__( 'Failed Migration: %s is incorrect post type.', 'gc-testimonials-to-testimonials' ), esc_html( self::$post_id ) ) ) ) );

		$result = self::migrate_item( self::$post_id, $post );
		if ( is_numeric( $result ) )
			die( json_encode( array( 'success' => sprintf( __( '&quot;<a href="%1$s" target="_blank">%2$s</a>&quot; GC Testimonial ID %3$s was successfully migrated to Testimonials %6$s &quot;<a href="%4$s" target="_blank">%5$s</a>&quot;.', 'gc-testimonials-to-testimonials' ), get_permalink( self::$post_id ), esc_html( get_the_title( self::$post_id ) ), self::$post_id, get_permalink( $result ), esc_html( get_the_title( $result ) ), $result ) ) ) );
		else
			die( json_encode( array( 'error' => sprintf( __( '&quot;<a href="%1$s" target="_blank">%2$s</a>&quot; Unable to be migrated.', 'gc-testimonials-to-testimonials' ), get_permalink( self::$post_id ), esc_html( get_the_title( self::$post_id ) ) ) ) ) );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function migrate_item( $post_id, $post ) {
		global $wpdb;

		$migrated_key  = '_' . Testimonials_Widget::PT;
		$migrated_args = array(
			'post_type' => Testimonials_Widget::PT,
			'meta_query' => array(
				array(
					'key' => $migrated_key,
					'value' => $post_id,
					'type' => 'NUMERIC',
				)
			)
		);

		$migrated = new WP_Query( $migrated_args );
		if ( $migrated->have_posts() ) {
			$migrated->the_post();

			return get_the_ID();
		}

		unset( $post['ID'] );
		unset( $post['guid'] );
		$post['post_type'] = Testimonials_Widget::PT;

		$new_post_id = wp_insert_post( $post, true );
		$post_meta   = get_post_custom( $post_id );

		$fields = array(
			'client_name' => 'testimonials-widget-client-name',
			// 'client_photo' => 'featured-image',
			'company_name' => 'testimonials-widget-company',
			'company_website' => 'testimonials-widget-url',
			'email' => 'testimonials-widget-email',
		);
		foreach ( $fields as $field => $target ) {
			if ( isset( $post_meta[ $field ][ 0 ] ) )
				add_post_meta( $new_post_id, $target, $post_meta[ $field ][ 0 ] );
		}

		$categories = wp_get_object_terms( $post_id, 'testimonial-category' );
		if ( ! empty( $categories ) ) {
			$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );
			if ( ! $use_cpt_taxonomy )
				$tax_cat = 'category';
			else
				$tax_cat = Testimonials_Widget::$cpt_category;

			foreach ( $categories as $category ) {
				$term = term_exists( $category->name, $tax_cat );
				if ( ! is_array( $term ) )
					$term = wp_insert_term( $category->name, $tax_cat );

				if ( ! is_array( $term ) )
					continue;

				$term_id = intval( $term['term_id'] );
				if ( $term_id )
					wp_set_object_terms( $new_post_id, $term_id, $tax_cat, true );
			}
		}	

		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		if ( $thumbnail_id ) {
			$src   = wp_get_attachment_url( $thumbnail_id );
			$file  = basename( $src );
			
			$image_id = self::add_media( $new_post_id, $src );
		}

		add_post_meta( $new_post_id, $migrated_key, $post_id );

		return $new_post_id;
	}


	public static function notice_1_0_0() {
		$text = sprintf( __( 'If your Migrate GC Testimonials to Testimonials display has gone to funky town, please <a href="%s">read the FAQ</a> about possible CSS fixes.', 'gc-testimonials-to-testimonials' ), 'https://aihrus.zendesk.com/entries/23722573-Major-Changes-Since-2-10-0' );

		self::notice_updated( $text );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function notice_donate( $disable_donate = null, $item_name = null ) {
		$disable_donate = tw_get_option( 'disable_donate' );

		parent::notice_donate( $disable_donate, GCT2T_NAME );
	}


	public static function update() {
		$prior_version = gct2t_get_option( 'admin_notices' );
		if ( $prior_version ) {
			if ( $prior_version < '1.0.0' )
				add_action( 'admin_notices', array( __CLASS__, 'notice_1_0_0' ) );

			if ( $prior_version < self::VERSION )
				do_action( 'gct2t_update' );

			gct2t_set_option( 'admin_notices' );
		}

		// display donate on major/minor version release
		$donate_version = gct2t_get_option( 'donate_version', false );
		if ( ! $donate_version || ( $donate_version != self::VERSION && preg_match( '#\.0$#', self::VERSION ) ) ) {
			add_action( 'admin_notices', array( __CLASS__, 'notice_donate' ) );
			gct2t_set_option( 'donate_version', self::VERSION );
		}
	}


	public static function scripts( $atts = array() ) {
		if ( is_admin() ) {
			wp_enqueue_script( 'jquery' );

			wp_register_script( 'jquery-ui-progressbar', plugins_url( 'js/jquery.ui.progressbar.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget' ), '1.10.3' );
			wp_enqueue_script( 'jquery-ui-progressbar' );

			add_action( 'admin_footer', array( 'Gc_Testimonials_to_Testimonials', 'get_scripts' ) );
		}

		do_action( 'gct2t_scripts', $atts );
	}


	public static function styles() {
		if ( is_admin() ) {
			wp_register_style( 'jquery-ui-progressbar', plugins_url( 'css/redmond/jquery-ui-1.10.3.custom.min.css', __FILE__ ), false, '1.10.3' );
			wp_enqueue_style( 'jquery-ui-progressbar' );

			add_action( 'admin_footer', array( 'Gc_Testimonials_to_Testimonials', 'get_styles' ) );
		}

		do_action( 'gct2t_styles' );
	}


	public static function version_check() {
		$valid_version = true;

		$valid_base = true;
		if ( ! is_plugin_active( GCT2T_REQ_BASE ) ) {
			$valid_base = false;
		} elseif ( ! defined( 'TW_VERSION' ) ) {
			$valid_base = false;
		} elseif ( ! version_compare( TW_VERSION, GCT2T_REQ_VERSION, '>=' ) ) {
			$valid_base = false;
		}

		if ( ! $valid_base ) {
			$valid_version = false;
			self::set_notice( 'gct2t_notice_version' );
		}

		if ( ! $valid_version ) {
			deactivate_plugins( self::BASE );
			self::check_notices();
		}

		return $valid_version;
	}


	public static function call_scripts_styles( $atts ) {
		self::scripts( $atts );
		self::styles();
	}


	public static function get_scripts() {
		if ( empty( self::$scripts ) )
			return;

		foreach ( self::$scripts as $script )
			echo $script;
	}


	public static function get_styles() {
		if ( empty( self::$styles ) )
			return;

		if ( empty( self::$styles_called ) ) {
			echo '<style>';

			foreach ( self::$styles as $style )
				echo $style;

			echo '</style>';

			self::$styles_called = true;
		}
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function do_load() {
		$do_load = false;
		if ( ! empty( $GLOBALS['pagenow'] ) && in_array( $GLOBALS['pagenow'], array( 'edit.php', 'options.php', 'plugins.php' ) ) ) {
			$do_load = true;
		} elseif ( ! empty( $_REQUEST['page'] ) && Gc_Testimonials_to_Testimonials_Settings::ID == $_REQUEST['page'] ) {
			$do_load = true;
		} elseif ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$do_load = true;
		}

		return $do_load;
	}


	public static function get_defaults( $single_view = false ) {
		if ( empty( $single_view ) )
			return apply_filters( 'gct2t_defaults', gct2t_get_options() );
		else
			return apply_filters( 'gct2t_defaults_single', gct2t_get_options() );
	}


}


register_activation_hook( __FILE__, array( 'Gc_Testimonials_to_Testimonials', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'Gc_Testimonials_to_Testimonials', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Gc_Testimonials_to_Testimonials', 'uninstall' ) );


add_action( 'plugins_loaded', 'gc_testimonials_to_testimonials_init', 99 );


/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
function gc_testimonials_to_testimonials_init() {
	if ( ! is_admin() )
		return;

	if ( ! function_exists( 'add_screen_meta_link' ) )
		require_once GCT2T_DIR_LIB . '/screen-meta-links.php';

	if ( Gc_Testimonials_to_Testimonials::version_check() ) {
		global $Gc_Testimonials_to_Testimonials;
		if ( is_null( $Gc_Testimonials_to_Testimonials ) )
			$Gc_Testimonials_to_Testimonials = new Gc_Testimonials_to_Testimonials();

		global $Gc_Testimonials_to_Testimonials_Settings;
		if ( is_null( $Gc_Testimonials_to_Testimonials_Settings ) )
			$Gc_Testimonials_to_Testimonials_Settings = new Gc_Testimonials_to_Testimonials_Settings();
	}
}


?>
