<?php
	// Add custom tables into Database
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$slideshow_tbl = $wpdb->prefix."slideshow";
	$sql = "CREATE TABLE $slideshow_tbl (
			id int(10) NOT NULL AUTO_INCREMENT,
			name varchar(100) NOT NULL,
			image text NULL,
			description text,
			status int(1) NOT NULL,
			slider_position int(3) NOT NULL DEFAULT '0',
			PRIMARY KEY (id)
		) $charset_collate;";

	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	dbDelta($sql);
?>