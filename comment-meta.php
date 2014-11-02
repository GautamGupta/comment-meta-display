<?php /*

**************************************************************************
Plugin Name: Comment Meta Display
Plugin URI: http://wordpress.org/extend/plugins/comment-meta-display/
Description: Adds a box to the edit comments page which shows the Comment Meta.
Author: SparkWeb Interactive, Inc.
Version: 1.1
Author URI: http://www.soapboxdave.com/

**************************************************************************

Copyright (C) 2011 SparkWeb Interactive, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

**************************************************************************/

function cme_admin_init() {
	if (isset($_REQUEST['action'])) {
		if ($_REQUEST['action'] == "editcomment") {
			add_meta_box('cme-meta-box', __('Comment Meta'), 'cme_comment_meta_box', 'comment', 'normal');
		}
	}
}
add_action('admin_init', 'cme_admin_init', 99);

function cme_comment_meta_box($comment) {
	global $wpdb;
	$comment_id = $comment->comment_ID;

	echo '<div class="cme-details" style="margin: 13px;">';

	$comment_meta = $wpdb->get_results("SELECT * FROM $wpdb->commentmeta WHERE comment_id = $comment_id", ARRAY_A);
	if ($comment_meta) :
		foreach ($comment_meta as $entry) :

			if ( is_serialized( $entry['meta_value'] ) ) {
				if ( is_serialized_string( $entry['meta_value'] ) ) {
					$entry['meta_value'] = maybe_unserialize( $entry['meta_value'] );
				} else {
					$entry['meta_value'] = array_map( 'strip_tags', unserialize( $entry['meta_value'] ) );
					$entry['meta_value'] = '<pre>' . print_r( $entry['meta_value'], true ) . '</pre>';
				}
			}

			$entry['meta_key'] = esc_attr($entry['meta_key']);
			$entry['meta_id'] = (int) $entry['meta_id'];

			echo "<div style=\"overflow: auto; clear: both;\">\n";
			echo "<span style=\"float: left; width: 25%;\">" . $entry['meta_key'] . "</span>";
			echo "<span style=\"float: left; width: 70%;\">" . $entry['meta_value'] . "</span>\n";
			echo "</div>" . "\n";


		endforeach;
		echo '<div style="clear:both"></div>' . "\n\n";

	else :
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		$("#cme-meta-box").hide();
	});
	</script>
	<?php
	endif;
	?>

</div>

	<?php



}
