<?php
require_once('projects_manager_model.class.php');
/*******************************************************************************
 * Define our initial class
 ******************************************************************************/
class Projects_Manager {
				//Instantiate our private variables
				private $model;
				
				//Instantiate our public variables
				public $plugin_path, $plugin_uri, $post, $project;
				
				/*******************************************************************************
				 * Instantiate our constructor
				 ******************************************************************************/
				public function __construct(){
								//Call the init function
								$this->init();
				}
				
				/*******************************************************************************
				 * Perform initialization functions
				 ******************************************************************************/
				public function init(){
								//Instantiate our model
								$this->model = new Projects_Manager_Model();
								
								//Init paths
								$this->plugin_path = plugin_dir_path(__FILE__).'../';
								$this->plugin_uri = plugin_dir_url(__FILE__).'../';
								
								//Init hooks
								$this->init_hooks();
								
								//Init filters
								$this->init_filters();
								
								//Init shortcodes
								$this->init_shortcodes();
				}
				
				/*******************************************************************************
				 * Initializes the hooks for the plugin
				 ******************************************************************************/
				public function init_hooks(){
								//Add custom post type
								add_action('init', array(&$this, 'register_custom_post_type'));
								
								//Add custom taxonomies for our custom post type
								add_action('init', array(&$this, 'register_custom_taxonomies'));
								
								//Add custom menu entries to the custom post type menu
								add_action('admin_menu', array(&$this, 'admin_menu'));
								
								//Get our post object during the wp_head action
								add_action('wp', array(&$this, 'wp_head'));
								
								//Include scripts and styles for the admin
								add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
								
								//Include scripts and styles for the frontend
								add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));
								
								//Add meta boxes to the custom post type editor screen
								add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'), 1);
								
								//Save the meta data when a post is saved
								add_action('save_post', array(&$this, 'save_post'));
								
								//Add custom columns to the post listing page for our custom post type
								add_action('manage_posts_custom_column', array(&$this, 'manage_pages_custom_column'), 10, 2);
								
								//Set up Ajax action for pages that need to retrieve the data on demand
								add_action('wp_ajax_get_projects', array(&$this, 'ajax_get_projects'));
								add_action('wp_ajax_nopriv_get_projects', array(&$this, 'ajax_get_projects'));
								
								//Set up Ajax action for adding clients
								add_action('wp_ajax_add_client', array(&$this, 'ajax_add_client'));
								add_action('wp_ajax_nopriv_add_client', array(&$this, 'ajax_add_client'));
				}
				
				/*******************************************************************************
				 * Initializes the filters for the plugin
				 ******************************************************************************/
				public function init_filters(){
								//Add messages for the projects post type
								add_filter('post_updated_messages', array(&$this, 'post_updated_messages'));
								
								//Filter for projects listing
								add_filter('manage_project_columns' , array(&$this, 'manage_project_columns'));
								
								//Create filter for overriding default template when displaying single project
								add_filter('template_include', array(&$this, 'template_include'));
				}
				
				/*******************************************************************************
				 * Initializes the shortcodes
				 ******************************************************************************/
				public function init_shortcodes(){
								//Add a shortcode for displaying the projects
								add_shortcode('project_manager_display', array(&$this, 'shortcode_project_manager_display'));
								
								//Add a shortcode for displaying the clients
								add_shortcode('project_manager_display_clients', array(&$this, 'shortcode_project_manager_display_clients'));
				}
				
				/*******************************************************************************
				 * Adds custom post types to the WP DB
				 ******************************************************************************/
				public function register_custom_post_type(){
								register_post_type(PROJECTS_MANAGER_POST_TYPE, array(
												'labels' => array(
																'name'               => 'Projects',
																'singular_name'      => 'Project',
																'menu_name'          => 'Projects',
																'name_admin_bar'     => 'Project',
																'add_new'            => 'Add New',
																'add_new_item'       => 'Add New Project',
																'new_item'           => 'New Project',
																'edit_item'          => 'Edit Project',
																'view_item'          => 'View Project',
																'all_items'          => 'All Projects',
																'search_items'       => 'Search Projects',
																'parent_item_colon'  => 'Parent Projects:',
																'not_found'          => 'No projects found.',
																'not_found_in_trash' => 'No projects found in Trash.',
												),
												'hierarchical' => false,
												'public' => true,
												'show_ui' => true,
												'show_admin_column' => true,
												'show_in_nav_menus' => false,
												'show_tagcloud' => false,
												'menu_icon' => 'dashicons-hammer',
												'rewrite' => array('slug' => 'projects'),
												'supports' => array('title', 'editor', 'thumbnail')
								));
				}
				
				/*******************************************************************************
				 * Registers custom taxonomies such as categories for our custom post type
				 ******************************************************************************/
				public function register_custom_taxonomies(){
								//Register the initial taxonomy
								register_taxonomy(PROJECTS_MANAGER_TERM, PROJECTS_MANAGER_POST_TYPE, array(
												'labels'                => array(
																'name'                       => 'Project Types',
																'singular_name'              => 'Project Type'
												),
												'query_var'             => true,
												'rewrite'               => array('slug' => PROJECTS_MANAGER_TERM),
												'hierarchical' => true,
												'public' => true,
												'show_ui' => true,
												'show_admin_column' => true,
												'show_in_nav_menus' => false,
												'show_tagcloud' => false
								));
								
								//Add the default "Miscellaneous" term
								wp_insert_term('Miscellaneous', PROJECTS_MANAGER_TERM);
				}
				
				/*******************************************************************************
				 * Adds custom menu entries to our custom post type
				 ******************************************************************************/
				public function admin_menu(){
								//Add the clients submenu to our custom post type
								add_submenu_page('edit.php?post_type='.PROJECTS_MANAGER_POST_TYPE, 'Clients', 'Clients', 'edit_posts', 'clients', array(&$this, 'submenu_clients'));
				}
				
				/*******************************************************************************
				 * Adds messages for the custom post type
				 ******************************************************************************/
				public function post_updated_messages($messages){
								$post = get_post();
								$post_type = get_post_type($post);
								$post_type_object = get_post_type_object($post_type);
							 if ($post_type==PROJECTS_MANAGER_POST_TYPE){
												$messages[PROJECTS_MANAGER_POST_TYPE] = array(
																0  => '', // Unused. Messages start at index 1.
																1  => 'Project updated.',
																2  => 'Custom field updated.',
																3  => 'Custom field deleted.',
																4  => 'Project updated.',
																5  => isset($_GET['revision']) ? sprintf('Project restored to revision from %s', wp_post_revision_title((int) $_GET['revision'], false)) : false,
																6  => 'Project published.',
																7  => 'Project saved.',
																8  => 'Project submitted.',
																9  => sprintf(
																				'Project scheduled for: <strong>%1$s</strong>.',
																				// translators: Publish box date format, see http://php.net/date
																				date_i18n('M j, Y @ G:i'), strtotime($post->post_date)),
																10 => 'Project draft updated.'
												);
											
												if ($post_type_object->publicly_queryable){
																$permalink = get_permalink($post->ID);
																
																$view_link = sprintf(' <a href="%s">%s</a>', esc_url($permalink), 'View project');
																$messages[$post_type][1] .= $view_link;
																$messages[$post_type][6] .= $view_link;
																$messages[$post_type][9] .= $view_link;
																
																$preview_permalink = add_query_arg('preview', 'true', $permalink);
																$preview_link = sprintf(' <a target="_blank" href="%s">%s</a>', esc_url($preview_permalink), 'Preview project');
																$messages[$post_type][8]  .= $preview_link;
																$messages[$post_type][10] .= $preview_link;
												}
								}
								
								return $messages;
				}
				
				/*******************************************************************************
				 * Add custom columns to the post listing for our custom post type
				 ******************************************************************************/
				public function manage_project_columns($columns){
								//Add the new items to the array
								return array_merge($columns, array(
												'featured' => 'Featured',
												'url' => 'URL'
								));
				}
				
				/*******************************************************************************
				 * Adds content to the columns added in the function above
				 ******************************************************************************/
				public function manage_pages_custom_column($column, $post_id){
								//Get the post object for this post ID
								$post = get_post($post_id);
								
								//Check which column is being rendered
								switch($column){
												case 'featured':
																$featured = get_post_meta($post_id, 'project_url', true);
																echo $featured==1 ? 'Yes' : 'No';
																break;
												case 'url':
																echo get_post_meta($post_id, 'project_url', true);
																break;
								}
				}
				
				/*******************************************************************************
				 * Override default display template
				 ******************************************************************************/
				public function template_include($template){
								global $post;
								
								//Generate paths for different templates
								$plugin_tpl = $this->plugin_path.'/tpl/frontend/project-detail.php';
								$theme_tpl = get_template_directory().'/projects-manager/tpl/project-detail.php';
								$theme_single_tpl = get_template_directory().'/single.php';
								
								//Determine which template to override
								switch(basename($template)){
												case 'single.php':
																//Set the template depending on which file exists first. Order is as follows:
																//1 - Theme template file
																//2 - Plugin template file
																//3 - Default theme single.php page
																$template = file_exists($theme_tpl) ? $theme_tpl : (file_exists($plugin_tpl) ? $plugin_tpl : $theme_single_tpl);
																break;
								}
                                                                
								return $template;
				}
				
				/*******************************************************************************
				 * Puts the post in a global plugin variable and get attachments
				 ******************************************************************************/
				public function wp_head(){
								global $wp_query, $post;
								
								//Set the post object
								$this->post = isset($wp_query->post) ? $wp_query->post : new stdClass();
								if (isset($this->post->ID)){
												//Get the post meta data
												$this->post->meta = $this->model->get_project_meta($this->post->ID);
												
												//Get other projects by this client
												if (isset($this->post->meta['project-manager-client']) && !empty($this->post->meta['project-manager-client'])) {
												    $this->post->related = $this->model->get_projects_by_client_id($this->post->meta['project-manager-client']);
												} else { $this->post->related = array(); }
												
												
												//Remove this post from the related posts
												foreach($this->post->related as $key=>$related){
																//Get the metadata for this project
																$this->post->related[$key]->meta = $this->model->get_project_meta($related->ID);
												}
												
												//Reindex the array keys
												$this->post->related = array_values($this->post->related);
												
												//Set the global post object to be the plugin's post object
												$post = $this->post;
								}
    }
				
				/*******************************************************************************
				 * Registers scripts and styles to be placed in the admin header
				 ******************************************************************************/
				public function admin_enqueue_scripts(){
								//Set the script dependencies
								$deps = array('jquery');
								
								//Enqueue scripts
								wp_enqueue_media();
								wp_enqueue_script('project-manager-admin-script', $this->plugin_uri.'assets/js/admin.js', $deps);
								
								//Register styles
								wp_enqueue_style('project-manager-admin-style', $this->plugin_uri.'assets/css/admin.css');
				}
				
				/*******************************************************************************
				 * Registers scripts and styles to be placed in the frontend header
				 ******************************************************************************/
				public function wp_enqueue_scripts(){
								//Set the script dependencies
								$deps = array('jquery');
								
								//Enqueue the styles after they're registered
								wp_enqueue_style('project-manager-frontend-bxslider-style', '//cdn.jsdelivr.net/bxslider/4.1.1/jquery.bxslider.css');
								wp_enqueue_style('project-manager-frontend-style', $this->plugin_uri.'assets/css/frontend.css');
								
								//Enqueue the scripts after they're registered
								wp_enqueue_script('project-manager-frontend-bxslider-script', '//cdn.jsdelivr.net/bxslider/4.1.1/jquery.bxslider.min.js', $deps);
								wp_enqueue_script('project-manager-frontend-script', $this->plugin_uri.'assets/js/frontend.js', $deps);
				}
				
				/*******************************************************************************
				 * Adds custom meta boxes to the custom post type editor screen
				 ******************************************************************************/
				public function add_meta_boxes(){
								add_meta_box('project-manager-details', 'Project Details', array(&$this, 'display_meta_box_details'), 'project', 'side', 'core');
								add_meta_box('project-manager-logo', 'Project Logo', array(&$this, 'display_meta_box_logo'), 'project', 'side', 'core');
								add_meta_box('project-manager-images', 'Project Images', array(&$this, 'display_meta_box_images'), 'project', 'normal', 'core');
								add_meta_box('project-manager-thumbnails', 'Project Thumbnails', array(&$this, 'display_meta_box_thumbnails'), 'project', 'normal', 'core');
				}
				
				/*******************************************************************************
				 * Displays the custom meta boxes
				 ******************************************************************************/
				public function display_meta_box_details($post){
								global $wpdb;
								
								//Init data array
								$data = array('services' => array(), 'tools' => array());
								
								//Get meta data
								$meta = get_post_meta($post->ID);
								
								//Loop through all meta keys
								foreach($meta as $key=>$val){
												$data[$key] = $val[0];
								}
								
								//New sort order
								$new_sort_order = $this->model->get_new_sort_order();
								
								//Get the services and tools from the projects manager table
								$services_tools = $wpdb->get_results('SELECT title, type FROM '.PROJECTS_MANAGER_DB_TABLE.' WHERE wp_id = '.$post->ID.' ORDER BY title ASC, type ASC');
								
								//Loop through the DB results
								foreach($services_tools as $key=>$service_tool){
												//Place the service or tool in the appropriate array
												array_push($data[$service_tool->type], $service_tool->title);
								}
								
								//Get the clients
								$clients = $this->model->get_clients();
								
								//Include the template
								include($this->plugin_path.'tpl/admin/metabox-details.php');
				}
				
				/*******************************************************************************
				 * Displays the logo meta box
				 ******************************************************************************/
				public function display_meta_box_logo($post){
								//Init data array
								$logo = get_post_meta($post->ID, 'project-manager-logo', true);
								
								//Process data
								$logo = !empty($logo) && is_array($logo) ? $logo[0] : $logo;
								
								//Include the template
								include($this->plugin_path.'tpl/admin/metabox-logo.php');
				}
				
				/*******************************************************************************
				 * Displays the custom meta boxes
				 ******************************************************************************/
				public function display_meta_box_images($post){
								//Init data array
								$data = array(
												'images' => get_post_meta($post->ID, 'project-manager-images', true),
												'featured-image' => get_post_meta($post->ID, 'project-manager-featured-image', true)
								);
								
								//Process data
								$data['featured-image'] = !empty($data['featured-image']) ? $data['featured-image'][0] : $data['featured-image'];
								
								//Include the template
								include($this->plugin_path.'tpl/admin/metabox-images.php');
				}
				
				/*******************************************************************************
				 * Displays the custom meta boxes
				 ******************************************************************************/
				public function display_meta_box_thumbnails($post){
								//Init data array
								$thumbnails = get_post_meta($post->ID, 'project-manager-thumbnails', true);
								
								//Include the template
								include($this->plugin_path.'tpl/admin/metabox-thumbnails.php');
				}
				
				/*******************************************************************************
				 * Saves the meta box data when a post is saved
				 ******************************************************************************/
				public function save_post($post_id, $post_obj = ''){
								//If the post object is not set, get it from the provided post ID
								$post_obj = empty($post_obj) ? get_post($post_id) : $post_obj;
								
								//Make sure this is for our custom post type
								if ($post_obj->post_type=='project'){
												//Update the post meta data
												if (isset($_POST['project-manager-featured']) && !empty($_POST['project-manager-featured'])) update_post_meta($post_id, 'project-manager-featured', $_POST['project-manager-featured']); else update_post_meta($post_id, 'project-manager-featured', 0);
												if (isset($_POST['project-manager-description']) && !empty($_POST['project-manager-description'])) update_post_meta($post_id, 'project-manager-description', $_POST['project-manager-description']);
												if (isset($_POST['project-manager-url']) && !empty($_POST['project-manager-url'])) update_post_meta($post_id, 'project-manager-url', $_POST['project-manager-url']);
												if (isset($_POST['project-manager-sort-order']) && !empty($_POST['project-manager-sort-order'])) update_post_meta($post_id, 'project-manager-sort-order', $_POST['project-manager-sort-order']); else update_post_meta($post_id, 'project-manager-sort-order', 0);
												if (isset($_POST['project-manager-images']) && !empty($_POST['project-manager-images'])) update_post_meta($post_id, 'project-manager-images', $_POST['project-manager-images']); else update_post_meta($post_id, 'project-manager-images', '');
												if (isset($_POST['project-manager-featured-image']) && !empty($_POST['project-manager-featured-image'])) update_post_meta($post_id, 'project-manager-featured-image', $_POST['project-manager-featured-image']);
												if (isset($_POST['project-manager-thumbnail-url']) && !empty($_POST['project-manager-thumbnail-url'])) update_post_meta($post_id, 'project-manager-thumbnails', $_POST['project-manager-thumbnail-url']); else update_post_meta($post_id, 'project-manager-thumbnails', '');
												if (isset($_POST['project-manager-logo']) && !empty($_POST['project-manager-logo'])) update_post_meta($post_id, 'project-manager-logo', $_POST['project-manager-logo']); else update_post_meta($post_id, 'project-manager-logo', '');
												if (isset($_POST['project-manager-client']) && !empty($_POST['project-manager-client'])) update_post_meta($post_id, 'project-manager-client', $_POST['project-manager-client']); else update_post_meta($post_id, 'project-manager-client', '');
												
												//Save the services and tools information
												$this->model->save_post($post_id, $_POST);
								}
				}
				
				/*******************************************************************************
				 * Template tag for returning project types
				 ******************************************************************************/
				public function get_project_types(){
								return $this->model->get_project_types();
				}
				
				/*******************************************************************************
				 * Template tag for returning projects
				 ******************************************************************************/
				public function get_projects($project_id = 0, $numposts = -1, $featured = false, $sort = 'ASC', $orderby = 'meta_value_num'){
								return $project_id==0 ? $this->model->get_all_projects($numposts, $featured, $sort) : $this->model->get_project_by_id($project_id);
				}
				
				/*******************************************************************************
				 * Template tag for returning projects by project type
				 ******************************************************************************/
				public function get_projects_by_type($type, $numposts = -1){
								return $this->model->get_projects_by_type($type, $numposts);
				}
				
				/*******************************************************************************
				 * Template tag for returning current featured projects
				 ******************************************************************************/
				public function get_featured_projects(){
								return $this->model->get_featured_projects();
				}
				
				/*******************************************************************************
				 * Action for retrieving projects through Ajax
				 ******************************************************************************/
				public function ajax_get_projects(){
								//Process POST data
								$project_id = isset($_POST['project_id']) ? $_POST['project_id'] : 0;
								$numposts = isset($_POST['numposts']) ? $_POST['numposts'] : -1;
								$featured = isset($_POST['featured']) ? $_POST['featured'] : false;
								$sort = isset($_POST['sort']) ? $_POST['sort'] : 'ASC';
								
								//Get the project information
								$projects = isset($project_id) && !empty($project_id) ? $this->model->get_project_by_id($project_id) : $this->model->get_all_projects($numposts, $featured, $sort);
								
								//Send the Ajax response with the project information
								wp_send_json($projects);
				}
				
				/*******************************************************************************
				 * Action for retrieving projects by client ID through Ajax
				 ******************************************************************************/
				public function ajax_get_projects_by_client_id(){
								//Process POST data
								$client_id = isset($_POST['client_id']) ? $_POST['client_id'] : 0;
								$numposts = isset($_POST['numposts']) ? $_POST['numposts'] : -1;
								
								//Get the project information
								$projects = $this->model->get_client_by_client_id($client_id, $numposts);
								
								//Send the Ajax response with the project information
								wp_send_json($projects);
				}
				
				/*******************************************************************************
				 * Action for adding clients through Ajax
				 ******************************************************************************/
				public function ajax_add_client(){
								global $wpdb;
								
								//Process POST data
								$name = isset($_POST['name']) ? $_POST['name'] : '';
								
								//Add client
								if (isset($_POST['name']) && !empty($_POST['name'])){
												//Add the client to the DB
												if ($wpdb->insert(PROJECTS_MANAGER_CLIENTS_DB_TABLE, array('name' => $_POST['name']))){
																//Get the insert ID if successful
																$insert_id = $wpdb->insert_id;
												} else {
																$insert_id = 0;
												}
								} else {
												$insert_id = 0;
								}
								
								//Send the Ajax response with the project information
								echo $insert_id;
				}
				
				/*******************************************************************************
				 * Shortcode for displaying projects
				 ******************************************************************************/
				public function shortcode_project_manager_display($atts){
								//Extract the shortcode attributes
								extract(shortcode_atts(array(
												'numposts'        => -1,
												'template'        => 'list',
												'type'            => '',
												'id'              => '',
												'featured'        => 'false',
												'sort'            => 'ASC',
												'orderby'         => 'meta_value_num',
												'size'            => 'normal',
												'scroller'        => 'false',
												'transition'      => 'true',
												'transition_type' => 'flip' //Options are fade and flip
								), $atts));
								
								//Process the boolean variables
								$featured = ($featured=='false' ? false : ($featured=='true' ? true : false));
								$scroller = ($scroller=='false' ? false : ($scroller=='true' ? true : false));
								$transition = ($transition=='false' ? false : ($transition=='true' ? true : false));
								
								//Determine if we are looking for projects by type
								if (!empty($type)){ //Search by type
                                                                    $projects = $this->get_projects_by_type($type, $numposts);
								} elseif (!empty($id)){ //Search by id
                                                                    $projects = $this->get_projects($id, $numposts);
								} else { //Just search
                                                                    $projects = $this->get_projects(0, $numposts, $featured, $sort, $orderby);
								}
								
								//Check if there is a template in the theme directory
								$tpl_path = get_template_directory().'/projects-manager/tpl/project-listing-'.$template.'-'.$size.'.php';
								$plugin_path = $this->plugin_path.'/tpl/frontend/project-listing-'.$template.'-'.$size.'.php';
								
								//Begin the output buffer so we can save the template HTML as a variable
								ob_start();
								
								//Include the template file
								include(file_exists($tpl_path) ? $tpl_path : $plugin_path);
								
								//Save the contents of the output buffer to a variable
								$html = ob_get_contents();
								
								//Close the output buffer and clear it
								ob_end_clean();
								
								return $html;
				}
				
				/*******************************************************************************
				 * Shortcode for displaying clients
				 ******************************************************************************/
				public function shortcode_project_manager_display_clients($atts){
								//Extract the shortcode attributes
								extract(shortcode_atts(array(
												'numposts'    => 0,
												'id'          => 0
								), $atts));
								
								//Get clients
								$clients = $this->model->get_clients($id, $numposts);
								
                                                                
								//Check if there is a template in the theme directory
								$tpl_path = get_template_directory().'/projects-manager/tpl/client-listing.php';
								$plugin_path = $this->plugin_path.'/tpl/frontend/client-listing.php';
								
								//Begin the output buffer so we can save the template HTML as a variable
								ob_start();
								
								//Include the template file
								include(file_exists($tpl_path) ? $tpl_path : $plugin_path);
								
								//Save the contents of the output buffer to a variable
								$html = ob_get_contents();
								
								//Close the output buffer and clear it
								ob_end_clean();
								
								return $html;
				}
				
				/*******************************************************************************
				 * Displays the clients admin page
				 ******************************************************************************/
				public function submenu_clients(){
								global $wpdb;
								
								//Get all clients
								$clients = $this->model->get_clients();
								
								//Include the template
								include($this->plugin_path.'tpl/admin/page-clients.php');
				}
				
				/*******************************************************************************
				 * Prints out a formatted variable
				 ******************************************************************************/
				public function print_r($var, $echo = true){
								//Generate HTML
								$html = '<pre>'.$print_r($var, true).'</pre>';
								
								//Return or echo new text
								if ($echo) echo $html; else return $html;
				}
				
				/********************************************************************************************
					* Converts a URL to a filesystem path
					********************************************************************************************/
				public function url_to_fs_path($url){
								return $_SERVER['DOCUMENT_ROOT'].str_replace($_SERVER['SERVER_NAME'], '', str_replace('http://', '', $url));
				}
}
?>
