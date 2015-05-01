<?php
				global $wpdb;
				
				//DB Constants
				define('PROJECTS_MANAGER_DB_TABLE', $wpdb->prefix.'projects_manager');
				define('PROJECTS_MANAGER_CLIENTS_DB_TABLE', $wpdb->prefix.'projects_manager_clients');
				
				//Post type constants
				define('PROJECTS_MANAGER_POST_TYPE', 'project');
				define('PROJECTS_MANAGER_TERM', 'project_type');
?>