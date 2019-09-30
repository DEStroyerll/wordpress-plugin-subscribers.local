<?php

if( ! defined('WP_UNINSTALL_PLUGIN') ) exit;

global $wpdb;
$drop_db = "DROP TABLE IF EXISTS `dn_subscribers`";
$wpdb->query( $drop_db );