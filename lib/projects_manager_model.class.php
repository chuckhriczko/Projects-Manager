<?php
/*******************************************************************************
 * This model class is used to separate all the data related processes from
 * the plugin logic
 ******************************************************************************/
class Projects_Manager_Model {
				/*******************************************************************************
				 * Get all of the taxonomies associated with our custom post type
				 ******************************************************************************/
				public function get_project_types(){
								return get_terms(PROJECTS_MANAGER_TERM, array(
												'orderby'       => 'name', 
												'order'         => 'ASC',
												'hide_empty'    => false, 
												'hierarchical'  => true
								));
				}
				
				/*******************************************************************************
				 * Get all of the projects
				 ******************************************************************************/
				public function get_all_projects($numposts = -1, $featured = false, $sort = 'ASC', $orderby = 'rand'){
								global $wpdb;

								//Process featured variable
								$featured = is_string($featured) ? $featured=='true' ? true : false : $featured;
								
								//Generate the query parameters
								$args = array(
												'post_type' => 'project',
												'post_status' => 'publish',
												'orderby' => $orderby,
												'order' => $sort,
												'posts_per_page' => $numposts,
												'meta_query' => array(
																array(
																				'key' => 'project-manager-sort-order',
																				'type' => 'numeric'
																)
												)
								);
								
								//Determine whether we want only featured projects
								if ($featured) $args['meta_query'][1] = array(
												'key' => 'project-manager-featured',
												'value' => 1,
												'type' => 'numeric'
								);
								
								//Get the projects
								$projects = new WP_Query($args);
								$projects = $projects->posts;
								
								//Loop through each project
								foreach($projects as $key=>$project){
												//Get project types for this project
												$projects[$key]->types = $this->get_project_types_by_id($project->ID);
												
												//Get meta data for the projects
												$projects[$key]->meta = $this->get_project_meta($project->ID);
												
												//Get the featured image
												$projects[$key]->meta['featured-image'] = wp_get_attachment_url(get_post_thumbnail_id($project->ID));
								}
								
								//If there is only one result, change to a single dimensional array
								$projects = count($projects)==1 ? $projects[0] : $projects;
								
								return $projects;
				}
				
				/*******************************************************************************
				 * Get project by ID
				 ******************************************************************************/
				public function get_project_by_id($post_id = 0){
								//Get project
								$project = get_post($post_id);
								
								//Get project types for this project
								$project->types = $this->get_project_types_by_id($project->ID);
								
								//Get meta data for the project
								$project->meta = $this->get_project_meta($project->ID);
								
								//Get the featured image
								$projects->meta['featured-image'] = wp_get_attachment_url(get_post_thumbnail_id($project->ID));
								
								return $project;
				}
				
				/*******************************************************************************
				 * Get project by type
				 ******************************************************************************/
				public function get_projects_by_type($type_id = 0, $numposts = -1){
								//If the project type is a string, get the ID
								$tax_field = is_int($type_id) ? 'term_id' : 'name';
								
								//Get projects
								$projects = get_posts(array(
												'posts_per_page'   => $numposts,
												'post_type'								=> PROJECTS_MANAGER_POST_TYPE,
												'category'									=> $type_id,
												'meta_key'									=> 'project-manager-sort-order',
												'order_by'									=> 'meta_value_num',
												'order'												=> 'ASC'
								));
								
								//Init return array
								$return_array = array();
								
								//Loop through each project
								foreach($projects as $key=>$project){
												//Get the taxonomy for the current project
												$projects[$key]->terms = wp_get_post_terms($project->ID, PROJECTS_MANAGER_TERM);
												
												//Loop through all the terms
												foreach($projects[$key]->terms as $term){
																//Check if the term is associated with this project
																if ($term->term_id==$type_id){
																				//Get project types for this project
																				$projects[$key]->types = $this->get_project_types_by_id($project->ID);
																				
																				//Get meta data for the projects
																				$projects[$key]->meta = $this->get_project_meta($project->ID);
																				
																				//Get the featured image
																				$projects[$key]->meta['featured-image'] = wp_get_attachment_url(get_post_thumbnail_id($project->ID));
																				
																				//Add this project to the return array
																				array_push($return_array, $projects[$key]);
																				break;
																}
												}
								}
								
								//Return the post(s)
								return $return_array;
				}
				
				/*******************************************************************************
				 * Get featured projects
				 ******************************************************************************/
				public function get_featured_projects(){
								//Init the featured projects array
								$featured = array();
								
								//Get the projects
								$projects = get_posts(array(
												'posts_per_page'   => -1,
												'post_type'        => PROJECTS_MANAGER_POST_TYPE,
												'post_status'      => 'publish',
												'meta_key'									=> 'project-manager-sort-order',
												'order_by'									=> 'meta_value_num',
												'order'												=> 'ASC'
								));
								
								//Loop through projects and add to the featured array if it is featured
								foreach($projects as $key=>$project){
												//Get project types for this project
												$projects[$key]->types = $this->get_project_types_by_id($project->ID);
												
												//Add to featured array if it is featured
												if (get_post_meta($project->ID, 'project_featured', true)==1) array_push($featured, $project);
												
												//Get meta data for the projects
												$projects[$key]->meta = $this->get_project_meta($project->ID);
												
												//Get the featured image
												$projects[$key]->meta['featured-image'] = wp_get_attachment_url(get_post_thumbnail_id($project->ID));
								}
								
								
								//Return the featured array
								return $featured;
				}
				
				/*******************************************************************************
				 * Get featured projects
				 ******************************************************************************/
				public function get_project_types_by_id($id = 0){
								return wp_get_post_terms($id, PROJECTS_MANAGER_TERM);
				}
				
				/*******************************************************************************
				 * Get project metadata
				 ******************************************************************************/
				public function get_project_meta($post_id = 0){
								global $wpdb;
								
								//Get meta data
								$project_meta = get_post_meta($post_id);
												
								//Loop through meta and remove the multidimensional array
								foreach($project_meta as $key=>$meta){
												$project_meta[$key] = $key=='project-manager-images' || $key=='project-manager-thumbnails' ? unserialize(current($meta)) : current($meta);
								}
								
								//Get the featured image
								$project_meta['img_src'] = str_replace('-150x150', '', wp_get_attachment_image_src(get_post_thumbnail_id($post_id)));
								
								//Instantiate the services and tools arrays
								$project_meta['services'] = array();
								$project_meta['tools'] = array();
								
								//Get the services and tools from the projects manager table
								$services_tools = $wpdb->get_results('SELECT title, type FROM '.PROJECTS_MANAGER_DB_TABLE.' WHERE wp_id = '.$post_id.' ORDER BY title ASC, type ASC');
								
								//Loop through the DB results and add to the appropriate arrays
								foreach($services_tools as $key=>$service_tool){
												//Place the service or tool in the appropriate array
												array_push($project_meta[$service_tool->type], $service_tool->title);
								}
								
								//Get the featured image
								$project_meta['featured-image'] = wp_get_attachment_url(get_post_thumbnail_id($post_id));
								
								//Get the project types
								$project_meta['types'] = get_the_terms($post_id, 'project_type');
								
								//Init the type slugs array
								$project_meta['type_slugs'] = array();
								
								//Verify we have project types
								if (!empty($project_meta['types'])){
												//Loop through types and generate the type slugs array
												foreach($project_meta['types'] as $type){
																array_push($project_meta['type_slugs'], $type->slug);
												}
								}
								
								//Get the featured image
								$project_meta['permalink'] = get_permalink($post_id);
								
								return $project_meta;
				}
				
				/*******************************************************************************
				 * Get related projects based on tags
				 ******************************************************************************/
				public function get_related_projects($id, $numposts = -1, $orderby = 'post_date', $order = 'DESC'){
								global $wpdb;
								
								//Init our return array
								$posts = array();
								
								//Get the tags for the passed post ID
								$tags = wp_get_post_tags($id);
								
								//Verify this post has tags associated with it
								if (!empty($tags)){
												//Generate the terms query IN clause
												$in = '(';
												
												//Loop through the tags and generate the rest of the query
												foreach($tags as $key=>$tag){
																//Determine whether we should use the separator
																$separator = count($tags)==($key+1) ? '' : ', ';
																
																$in .= $tag->term_id.$separator;
												}
												
												//Close the terms query IN clause
												$in .= ')';
												
												//Determine if we should limit the number of posts
												$limit = $numposts>0 ? ' LIMIT 0, '.$numposts : '';
												
												//Generate a query to pull all the projects that have the same tags
												$query = '
																SELECT
																				DISTINCT posts.ID,
																				posts.post_date,
																				posts.post_title,
																				posts.post_content,
																				posts.guid AS url
																FROM
																				'.$wpdb->posts.' posts,
																				'.$wpdb->terms.' terms,
																				'.$wpdb->term_relationships.' tr,
																				'.$wpdb->term_taxonomy.' tt
																WHERE
																				posts.ID != '.$id.' AND
																				posts.post_type = "'.PROJECTS_MANAGER_POST_TYPE.'" AND
																				terms.term_id = tt.term_id AND
																				tr.term_taxonomy_id = tt.term_taxonomy_id AND
																				tr.object_id = posts.ID AND
																				terms.term_id IN '.$in.' AND
																				posts.post_status = "publish"
																ORDER BY
																				posts.'.$orderby.' '.$order.$limit;
												
												//Perform query
												$posts = $wpdb->get_results($query);
								}
								
								return $posts;
				}
				
				/*******************************************************************************
				 * Gets the last item sorted by sort order meta key
				 ******************************************************************************/
				public function get_new_sort_order(){
								global $wpdb;
								
								//Get the list of projects sorted by the sort order meta key and limit to one
								return $wpdb->get_var('SELECT meta_value FROM '.$wpdb->postmeta.' postmeta WHERE meta_key = "project-manager-sort-order" ORDER BY meta_value DESC LIMIT 1') + 1;
				}
				
				/*******************************************************************************
				 * Saves the tools and services data to the projects manager DB table
				 ******************************************************************************/
				public function save_post($post_id, $post){
								global $wpdb;
								
								//Make sure services exist
								if (isset($post['project-manager-services-hidden']) && !empty($post['project-manager-services-hidden']) && is_array($_POST['project-manager-services-hidden'])){
												//Delete all the services for this post ID
												$wpdb->delete(PROJECTS_MANAGER_DB_TABLE, array('wp_id' => $post_id, 'type' => 'services'));
												
												//Get the services from the POSTed data
												$services = isset($post['project-manager-services-hidden']) && is_array($post['project-manager-services-hidden']) ? $post['project-manager-services-hidden'] : array();
												
												//Loop through the services
												foreach($services as $service){
																//Add the service
																$wpdb->insert(PROJECTS_MANAGER_DB_TABLE, array('wp_id' => $post_id, 'type' => 'services', 'title' => $service));
												}
								}
								
								//Make sure tools exist
								if (isset($post['project-manager-tools-hidden']) && !empty($post['project-manager-tools-hidden']) && is_array($post['project-manager-tools-hidden'])){
												//Delete all the tools for this post ID
												$wpdb->delete(PROJECTS_MANAGER_DB_TABLE, array('wp_id' => $post_id, 'type' => 'tools'));
												
												//Get the services from the POSTed data
												$tools = isset($post['project-manager-tools-hidden']) && is_array($post['project-manager-tools-hidden']) ? $post['project-manager-tools-hidden'] : array();
												
												//Loop through the tools
												foreach($tools as $tool){
																//Add the tool
																$wpdb->insert(PROJECTS_MANAGER_DB_TABLE, array('wp_id' => $post_id, 'type' => 'tools', 'title' => $tool));
												}
								}
				}
				
				/*******************************************************************************
				 * Gets client information
				 ******************************************************************************/
				public function get_clients($id = 0, $numposts = 0, $orderby = 'name', $sort = 'ASC'){
								global $wpdb;
								
								//Get the list of clients
								return $wpdb->get_results('SELECT * FROM '.PROJECTS_MANAGER_CLIENTS_DB_TABLE.(!empty($id) ? ' WHERE id = '.$id : '').' ORDER BY '.$orderby.' '.$sort.(!empty($numposts) ? ' LIMIT 0, '.$numposts : ''));
				}
				
				/*******************************************************************************
				 * Gets projects based on client id
				 ******************************************************************************/
				public function get_projects_by_client_id($id = 0, $numposts = 0){
								global $wpdb;
								
								//Get the projects
								$projects = new WP_Query(array(
												'post_type' => 'project',
												'post_status' => 'publish',
												'numposts' => $numposts==0 ? -1 : $numposts,
												'meta_query' => array(
																array(
																				'key'   => 'project-manager-client',
																				'value' => $id,
																				'type'  => 'numeric'
																)
												)
								));
								
								//Return the list of projects
								return $projects->posts;
				}
}
?>