<?php
	// Remove custom tables from Database
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$slideshow_tbl = $wpdb->prefix."slideshow";
	$sql = "DROP TABLE IF EXISTS $slideshow_tbl;";
	$wpdb->query($sql);
?>