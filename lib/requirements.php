<?php
/*
	Copyright 2013 Michael Cannon (email: mc@aihr.us)

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

require GCT2T_DIR_LIB . '/aihrus/requirements.php';


function gct2t_requirements_check() {
	$valid_requirements = true;
	if ( ! aihr_check_php( GCT2T_BASE, GCT2T_NAME ) ) {
		$valid_requirements = false;
	}

	if ( ! aihr_check_wp( GCT2T_BASE, GCT2T_NAME ) ) {
		$valid_requirements = false;
	}

	if ( ! is_plugin_active( GCT2T_REQ_BASE ) ) {
		$valid_requirements = false;
		add_action( 'admin_notices', 'gct2t_notice_version' );
	}

	if ( ! $valid_requirements ) {
		deactivate_plugins( GCT2T_BASE );
	}

	return $valid_requirements;
}


function gct2t_notice_version() {
	aihr_notice_version( GCT2T_REQ_BASE, GCT2T_REQ_NAME, GCT2T_REQ_SLUG, GCT2T_REQ_VERSION, GCT2T_NAME );
}

?>
