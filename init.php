<?php
/*
Plugin Name: Projects Manager
Plugin URI: http://www.objectunoriented.com/projects/projects-manager
Description: Allows adding and managing of projects to a Wordpress website
Version: 1.0
Author: Charles Hriczko
Author URI: http://www.objectunoriented.com
License: GPLv2
*/
require_once('lib/constants.php');
require_once('lib/projects_manager.class.php');

//Create the database table if it does not exist
function projects_manager_activate(){
				global $wpdb;
				
				//Add main table if it does not exist
				$wpdb->query('
								CREATE TABLE IF NOT EXISTS `'.PROJECTS_MANAGER_DB_TABLE.'` (
												`id` int(11) NOT NULL AUTO_INCREMENT,
												`wp_id` int(11) NOT NULL,
												`type` enum(\'services\',\'tools\') NOT NULL DEFAULT \'services\',
												`title` text NOT NULL,
												PRIMARY KEY (`id`)
								) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
				');
				
				//Add clients table if it does not exist
				$wpdb->query('
								CREATE TABLE IF NOT EXISTS `'.PROJECTS_MANAGER_CLIENTS_DB_TABLE.'` (
												`id` int(11) NOT NULL AUTO_INCREMENT,
												`name` text NOT NULL,
												PRIMARY KEY (`id`)
								) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
				');
}
register_activation_hook(__FILE__, 'projects_manager_activate');

//Instantiate our class
$projects_manager = new Projects_Manager();
?>