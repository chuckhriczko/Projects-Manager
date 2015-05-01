var Projects_Manager_Admin = {
				anim_speed: 'medium',
				cache: {},
				last_uploader: null,
				last_thumb: null
}; //Init the primary admin object

(function($) {
				$(document).ready(function(){
								Projects_Manager_Admin.init_dom_cache();
								Projects_Manager_Admin.init_layout();
								Projects_Manager_Admin.init_uploader_form();
								Projects_Manager_Admin.init_thumbnail_uploader();
								Projects_Manager_Admin.init_logo_uploader();
								Projects_Manager_Admin.init_radios();
								Projects_Manager_Admin.init_tabbed_lists();
								Projects_Manager_Admin.init_add_clients();
				});
				
				/*******************************************************************************
				 * Initializes the DOM cache for faster access
				 ******************************************************************************/
				Projects_Manager_Admin.init_dom_cache = function(){
								Projects_Manager_Admin.cache.details = $('#project-manager-details');
								Projects_Manager_Admin.cache.thumbnails = $('#project-manager-thumbnails');
								Projects_Manager_Admin.cache.images = $('#project-manager-images');
								Projects_Manager_Admin.cache.images_list = $('#project-manager-images-list');
								Projects_Manager_Admin.cache.logo = $('#project-manager-logo');
								Projects_Manager_Admin.cache.tabbed_lists = Projects_Manager_Admin.cache.details.find('.project-manager-tabbed-list');
								Projects_Manager_Admin.cache.clients = $('#projects-manager-clients');
								Projects_Manager_Admin.cache.clients_add = Projects_Manager_Admin.cache.clients.find('.projects-manager-clients-add');
				}
				
				/*******************************************************************************
				 * Initializes any layout changes
				 ******************************************************************************/
				Projects_Manager_Admin.init_layout = function(){
								$('#tagsdiv-project-type').empty().remove();
								$('#project-type-tabs li.hide-if-no-js').empty().remove();
				}
				
				/*******************************************************************************
				 * Initializes the uploader form for the project images section
				 ******************************************************************************/
				Projects_Manager_Admin.init_uploader_form = function(){
								var custom_uploader;
								$('.project-manager-upload-image-btn').click(function(e){												
												//If the uploader object has already been created, reopen the dialog
												if (custom_uploader) {
																custom_uploader.open();
																return;
												}
												
												//Extend the wp.media object
												custom_uploader = wp.media.frames.file_frame = wp.media({
																title: 'Choose Image(s)',
																button: {
																				text: 'Choose Image(s)'
																},
																multiple: true
												});
												
												//When a file is selected, grab the URL and set it as the text field's value
												custom_uploader.on('select', function() {
																//Get the uploader selection
																selection = custom_uploader.state().get('selection');
																
																//Verify the user has selected an image
																if (selection.length>0){
																				//Init our HTML variable
																				var html = '';
																				
																				//Loop through each selection
																				selection.map(function(attachment, index){
																								//Turn the attachment object into a JSON object
																								attachment = attachment.toJSON();
																								
																								//Generate an image container
																								html += '<label class="project-manager-image-container" for="project-manager-upload-image-' + index + '"><input type="radio" id="project-manager-upload-image-' + index + '" name="project-manager-featured-image[]" value="' + attachment.url + '" /><img src="' + attachment.url + '" alt="' + attachment.title + '" width="128" height="128" /><a href="#remove" title="Remove Image">Remove</a><input type="hidden" name="project-manager-images[]" value="' + attachment.url + '" /></label>';
																				});
																				
																				//Add the images to the list
																				Projects_Manager_Admin.cache.images_list.append(html);
																}
												});
												
												//Open the uploader dialog
												custom_uploader.open();
												
												e.preventDefault();
												return false;
								});
								
								//Init remove all button
								Projects_Manager_Admin.cache.images_list.siblings('input#project-manager-images-btn-remove-all').on('click', function(e){
												if (confirm('Are you sure you would like to remove ALL the images from this project?')) Projects_Manager_Admin.cache.images_list.empty();
								});
								
								//Init the remove links for individual images
								Projects_Manager_Admin.cache.images_list.on('click', '.project-manager-image-container a', function(e){
												$(this).parent('label.project-manager-image-container').empty().remove();
												
												e.preventDefault();
												return false;
								});
				}
				
				/*******************************************************************************
				 * Initializes the uploader for the thumbnails section
				 ******************************************************************************/
				Projects_Manager_Admin.init_thumbnail_uploader = function(){
								var custom_uploader;
								$('#project-manager-thumbnail-container').on('click', 'a[href="#thumbnail"]', function(e){
												Projects_Manager_Admin.last_thumb = $(this);
												//If the uploader object has already been created, reopen the dialog
												if (custom_uploader) {
																custom_uploader.open();
																return;
												}
												
												//Extend the wp.media object
												custom_uploader = wp.media.frames.file_frame = wp.media({
																title: 'Choose Image',
																button: {
																				text: 'Choose Image'
																},
																multiple: false
												});
												
												//When a file is selected, grab the URL and set it as the text field's value
												custom_uploader.on('select', function() {
																//Get the uploader selection
																selection = custom_uploader.state().get('selection');
																
																//Verify the user has selected an image
																if (selection.length>0){
																				//Init our HTML variable
																				var html = '';
																				
																				//Loop through each selection
																				selection.map(function(attachment, index){
																								//Turn the attachment object into a JSON object
																								attachment = attachment.toJSON();
																								
																								//Process the width and height
																								var width = attachment.width>128 ? 128 : width,
																												height = attachment.height>128 ? 128 : height;
																								
																								//Generate an image container
																								html += '<img src="' + attachment.url + '" width="' + width + '" height="' + height + '" /><input type="hidden" id="project-manager-thumbnail-url" name="project-manager-thumbnail-url[]" value="' + attachment.url + '" /><a href="#remove-thumbnail">Remove Thumbnail</a>';
																				});
																				
																				//Add the image to the thumbnail container
																				$(Projects_Manager_Admin.last_thumb).parent('div.project-manager-thumbnail').empty().html(html).after('<div class="project-manager-thumbnail"><a href="#thumbnail">Add Thumbnail</a></div>');
																}
												});
												
												//Open the uploader dialog
												custom_uploader.open();
												
												e.preventDefault();
												return false;
								});
								
								//Bind the remove thumbnail link
								$('#project-manager-thumbnail-container').on('click', 'a[href="#remove-thumbnail"]', function(e){
												$(this).parent('div.project-manager-thumbnail').empty().remove();
								});
				}
				
				/*******************************************************************************
				 * Initializes the uploader for the logo section
				 ******************************************************************************/
				Projects_Manager_Admin.init_logo_uploader = function(){
								var custom_uploader;
								$('.project-manager-logo-container').on('click', 'a[href="#add-logo"]', function(e){
												//If the uploader object has already been created, reopen the dialog
												if (custom_uploader) {
																custom_uploader.open();
																return;
												}
												
												//Extend the wp.media object
												custom_uploader = wp.media.frames.file_frame = wp.media({
																title: 'Choose Image',
																button: {
																				text: 'Choose Image'
																},
																multiple: false
												});
												
												//When a file is selected, grab the URL and set it as the text field's value
												custom_uploader.on('select', function() {
																//Get the uploader selection
																selection = custom_uploader.state().get('selection');
																
																//Verify the user has selected an image
																if (selection.length>0){
																				//Init our HTML variable
																				var html = '';
																				
																				//Loop through each selection
																				selection.map(function(attachment, index){
																								//Turn the attachment object into a JSON object
																								attachment = attachment.toJSON();
																								
																								//Process the width and height
																								var width = attachment.width>128 ? 128 : width,
																												height = attachment.height>128 ? 128 : height;
																								
																								//Generate an image container
																								html += '<img src="' + attachment.url + '" /><a href="#remove-logo">Remove Logo</a>';
																								
																								//Set the value of the hidden container
																								Projects_Manager_Admin.cache.logo.find('input[type="hidden"]').val(attachment.url);
																								
																								//Set the HTML of the container
																								Projects_Manager_Admin.cache.logo.find('label').html(html);
																								
																								//Reinit the logo uploader
																								Projects_Manager_Admin.init_logo_uploader();
																				});
																}
												});
												
												//Open the uploader dialog
												custom_uploader.open();
												
												e.preventDefault();
												return false;
								});
								
								//Bind the remove logo link
								$('.project-manager-logo-container').on('click', 'a[href="#remove-logo"]', function(e){
												$(this).parent('.project-manager-logo-container').empty().html('<p>You haven\'t uploaded a logo for this project yet.</p><a href="#add-logo" title="Add Logo">Add Logo</a>');
												
												//Reinit the logo uploader
												Projects_Manager_Admin.init_logo_uploader();
								});
				}
				
				/*******************************************************************************
				 * Initializes the images section's featured radio buttons
				 ******************************************************************************/
				Projects_Manager_Admin.init_radios = function(){
								//Find each image in the image container
								Projects_Manager_Admin.cache.images_list.find('.project-manager-image-container img').on('click', function(){
												//Trigger the radio button click so our POST data is correct
												$(this).siblings('input[type="radio"]').trigger('click');
								});
				}
				
				/*******************************************************************************
				* Binds the list item clicks so they remove the list item
				******************************************************************************/
				Projects_Manager_Admin.bind_list_items = function($list){
								//Refresh the DOM cache
								Projects_Manager_Admin.init_dom_cache();
								
								//Bind the click event on the tabbed list items
								$($list).find('li a').off('click').on('click', function(e){
												//Confirm the user wants to remove this item
												if (confirm('Are you sure you would like to remove "' + $(this).text() + '"?')){
																//Remove the item from the list
																$(this).parent('li').fadeOut(Projects_Manager_Admin.anim_speed, function(){
																				$(this).remove();
																});
												}
												
												//Prevent default action
												if (typeof e.stopPropogation=='function') e.stopPropogation();
												e.preventDefault();
												return false;
								});
				}
				
				/*******************************************************************************
				 * Initializes the tabbed lists for the services and tools sections
				 ******************************************************************************/
				Projects_Manager_Admin.init_tabbed_lists = function(){
								//Loop through each list
								Projects_Manager_Admin.cache.tabbed_lists.each(function(){
												//Cache the textbox
												var $list = $(this),
																$textbox = $list.find('input[type="text"]');
												
												//Find the add button in this list and bind the click event
												$textbox.off('keydown').on('keydown', function(e){
																//Detect which key was pressed down
																switch(e){
																				case 13: //Enter key
																								$(this).next('button').trigger('click');
																								break;
																}
												}).next('button').off('click').on('click', function(){
																//Instantiate the list string
																var $ul = $(this).next('ul'),
																				type = $(this).attr('id');
																
																//Make sure the textbox has a non empty value
																if ($.trim($textbox.val())!=''){
																				//Determine what type this item belongs to
																				type = type.replace('project-manager-', '');
																				type = type.replace('-add', '');
																				
																				//Append the textbox value to the list
																				$ul.append('<li style="display: none;"><a href="#remove" title="Remove">' + $textbox.val() + '</a><input type="hidden" name="project-manager-' + type + '-hidden[]" value="' + $textbox.val() + '" /></li>');
																				
																				//Fade in the new item
																				$ul.find('li:last-of-type').fadeIn(Projects_Manager_Admin.anim_speed);
																				
																				//Clear out textbox
																				$textbox.val('');
																				
																				//Reinitialize the DOM cache and tabbed lists
																				Projects_Manager_Admin.init_dom_cache();
																				Projects_Manager_Admin.init_tabbed_lists();
																}
																
																//Give focus back to the textbox
																$(this).prev('input[type="text"]').trigger('focus');
												});
												
												//Refresh the DOM cache
												Projects_Manager_Admin.init_dom_cache();
												
												//Bind the click event on the tabbed list items
												Projects_Manager_Admin.bind_list_items($(this));
								});
				}
				
				/*******************************************************************************
				 * Initializes the add clients section
				 ******************************************************************************/
				Projects_Manager_Admin.init_add_clients = function(){
								//Bind the click handler for the add button
								Projects_Manager_Admin.cache.clients_add.on('click', 'button', function(e){
												//Make sure the name field has a value
												if ($.trim(Projects_Manager_Admin.cache.clients_add.find('input').val())==''){
																//Show error message
																alert('The name field must not be empty!');
																
																//Prevent default action by not submitting form
																e.preventDefault();
																return false;
												} else {
																//Perform Ajax call
																$.ajax({
																				url: ajaxurl,
																				type: 'post',
																				data: {
																								action: 'add_client',
																								name: Projects_Manager_Admin.cache.clients_add.find('input').val(),
																								description: Projects_Manager_Admin.cache.clients_add.find('textarea').val()
																				},
																				beforeSend: function(){
																								//Disable the fields
																								Projects_Manager_Admin.cache.clients_add.find('input, textarea, button').attr('disabled', true);
																				},
																				error: function(jqXHR, textStatus, errorThrown){
																								alert('There was an error adding the client. Please try again.');
																								
																								if (typeof e=='undefined') e.preventDefault();
																								return false;
																				},
																				success: function(id){
																								//Get handle on the table body
																								var $tbody = Projects_Manager_Admin.cache.clients_add.prev('table').find('tbody');
																								
																								//Remove the no clients message if it exists
																								$tbody.find('tr td').each(function(){
																												//Remove if it contains an h3
																												if ($(this).find('h3').length>0) $(this).parent('tr').empty().remove();
																								});
																								
																								//Append the new client to the table
																								$tbody.append('<tr style="display: none;"><td class="projects-manager-clients-name">' + Projects_Manager_Admin.cache.clients_add.find('input').val() + '</td><td class="projects-manager-clients-options"><a href="#delete" title="Delete" data-id="' + id + '">&#739;</a></td></tr>').find('tr:last-of-type').fadeIn(Projects_Manager_Admin.anim_speed);
																								
																								//Clear the fields
																								Projects_Manager_Admin.cache.clients_add.find('input').val('');
																				},
																				complete: function(){
																								//Re-enable the fields
																								Projects_Manager_Admin.cache.clients_add.find('input, button').attr('disabled', false);
																				}
																});
												}
								});
				}
}(jQuery));