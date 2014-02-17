<?php
/**
 * Plugin Name: Testimonials – GC Testimonials Migrator
 * Plugin URI: http://wordpress.org/plugins/gc-testimonials-to-testimonials/
 * Description: Migrate GC Testimonials entries to Testimonials by Aihrus custom post types.
 * Version: 1.2.1
 * Author: Michael Cannon
 * Author URI: http://aihr.us/resume/
 * License: GPLv2 or later
 * Text Domain: gc-testimonials-to-testimonials
 * Domain Path: /languages
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

define( 'GCT2T_BASE', plugin_basename( __FILE__ ) );
define( 'GCT2T_DIR', plugin_dir_path( __FILE__ ) );
define( 'GCT2T_DIR_INC', GCT2T_DIR . 'includes/' );
define( 'GCT2T_DIR_LIB', GCT2T_DIR_INC . 'libraries/' );
define( 'GCT2T_NAME', 'GC Testimonials to Testimonials by Aihrus' );
define( 'GCT2T_REQ_BASE', 'testimonials-widget/testimonials-widget.php' );
define( 'GCT2T_REQ_BASE_PREM', 'testimonials-widget-premium/testimonials-widget-premium.php' );
define( 'GCT2T_REQ_NAME', 'Testimonials by Aihrus' );
define( 'GCT2T_REQ_SLUG', 'testimonials-widget' );
define( 'GCT2T_REQ_VERSION', '2.18.1' );
define( 'GCT2T_VERSION', '1.2.1' );

if ( defined( 'TW_DIR_LIB' ) ) {
	define( 'GCT2T_DIR_LIB_ALT', TW_DIR_LIB );
} else {
	define( 'GCT2T_DIR_LIB_ALT', WP_PLUGIN_DIR . '/testimonials-widget/includes/libraries/' );
}

require_once GCT2T_DIR_INC . 'requirements.php';

if ( ! gct2t_requirements_check() ) {
	return false;
}

require_once GCT2T_DIR_INC . 'class-gc-testimonials-to-testimonials.php';

add_action( 'plugins_loaded', 'gc_testimonials_to_testimonials_init', 99 );


/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
if ( ! function_exists( 'gc_testimonials_to_testimonials_init' ) ) {
	function gc_testimonials_to_testimonials_init() {
		if ( ! is_admin() )
			return;

		if ( ! function_exists( 'add_screen_meta_link' ) )
			require_once GCT2T_DIR_LIB . 'screen-meta-links.php';

		if ( Gc_Testimonials_to_Testimonials::version_check() ) {
			global $Gc_Testimonials_to_Testimonials;
			if ( is_null( $Gc_Testimonials_to_Testimonials ) )
				$Gc_Testimonials_to_Testimonials = new Gc_Testimonials_to_Testimonials();

			global $Gc_Testimonials_to_Testimonials_Settings;
			if ( is_null( $Gc_Testimonials_to_Testimonials_Settings ) )
				$Gc_Testimonials_to_Testimonials_Settings = new Gc_Testimonials_to_Testimonials_Settings();
		}
	}
}


register_activation_hook( __FILE__, array( 'Gc_Testimonials_to_Testimonials', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'Gc_Testimonials_to_Testimonials', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Gc_Testimonials_to_Testimonials', 'uninstall' ) );

?>
