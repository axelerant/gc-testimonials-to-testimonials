<?php
/*
	Copyright 2015 Axelerant

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once ABSPATH . 'wp-admin/includes/plugin.php';

if ( ! function_exists( 'aihr_notice_version' ) ) {
	function aihr_notice_version( $required_name, $required_version, $item_name ) {
		$text = sprintf( __( 'Plugin "%2$s" has been deactivated. Please install and activate "%3$s" version %1$s or newer before activating "%2$s".' ), $required_version, $item_name, $required_name );

		$content  = '<div class="error"><p>';
		$content .= $text;
		$content .= '</p></div>';

		echo $content;
	}
}


function gct2t_requirements_check() {
	if ( ! class_exists( GCT2T_REQ_CLASS ) ) {
		add_action( 'admin_notices', 'gct2t_notice_version' );

		return false;
	}

	return true;
}


function gct2t_notice_version() {
	aihr_notice_version( GCT2T_REQ_NAME, GCT2T_REQ_VERSION, GCT2T_NAME );

	deactivate_plugins( GCT2T_BASE );
}

?>
