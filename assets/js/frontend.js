var Projects_Manager = {
				animSpeed: 'medium',
				cache: {},
				scroller: {
								obj: {}
				}
}; //Init the primary frontend object

(function($) {
				$(document).ready(function(){
								//Initializes the DOM cache
								Projects_Manager.init_dom_cache();
								
								//Initialize the thumbnail scroller
								Projects_Manager.init_thumb_scroller();
								
								//Binds events to page elements
								Projects_Manager.bind_events();
                                                                
                                                                
                                                                var num_slides = $(window).width() < 767 ? 2 : 6;
                                                                //Bind two-row project preview slider (for project detail page)
                                                                $('.project-slider-double').bxSlider({
                                                                    slideWidth: 200,
                                                                    minSlides: num_slides,
                                                                    maxSlides: num_slides,
                                                                    slideMargin: 10,
                                                                    pager: false,
                                                                    nextSelector: '.project-slider-double-wrapper .arrow-next',
                                                                    prevSelector: '.project-slider-double-wrapper .arrow-prev',
                                                                    nextText: '<img src="' + KSAND.json.theme_path_uri + '/assets/images/icons/arrow-next-orange.png" alt="Next" />',
                                                                    prevText: '<img src="' + KSAND.json.theme_path_uri + '/assets/images/icons/arrow-prev-orange.png" alt="Previous" />'
                                                                  });
				});
				
				/**********************************************************************
					* Caches DOM elements for faster access
					*********************************************************************/
				Projects_Manager.init_dom_cache = function(){
								//Cache elements from the project manager grid
								Projects_Manager.cache.container = $('.project-manager-container');
								Projects_Manager.cache.list_items = Projects_Manager.cache.container.find('ul li');
								
								//Cache elements from the project detail page
								Projects_Manager.cache.detail = $('#project-detail-body');
								Projects_Manager.cache.scroller = $('.project-manager-scroller .project-manager-scroller-content');
								Projects_Manager.cache.scroller_preview = Projects_Manager.cache.scroller.find('.project-manager-scroller-preview');
								Projects_Manager.cache.scroller_list = Projects_Manager.cache.scroller.find('ul');
								Projects_Manager.cache.thumb_list = $('#project-detail-body .project-detail-sidebar ul.project-manager-horizontal-list');
				}
				
				/**********************************************************************
					* Initializes the thumbnail scroller for the project pages on resize
					*********************************************************************/
				Projects_Manager.init_thumb_scroller = function(){
								//Set the width of the scroller
								Projects_Manager.cache.scroller_list.width((Projects_Manager.cache.scroller_list.find('li').length*Projects_Manager.cache.scroller_list.find('li:first-of-type').width())+(Projects_Manager.cache.scroller_list.find('li').length*45));
								
								//Position the scroller underneat the logo
								if ($('body').hasClass('single-project')) Projects_Manager.cache.scroller.parent('div.project-manager-scroller').css({ top: Projects_Manager.cache.detail.prev('h2').offset().top + Projects_Manager.cache.detail.prev('h2').height() });
								
								//Verify we are on the our work page
								if (!$('body').hasClass('page-our-work')){
												//Check if the slider has been initialized
												if ($('.bx-wrapper').length==0){
																//Init the slider
																Projects_Manager.scroller.obj = Projects_Manager.cache.scroller_list.bxSlider({
																				nextSelector: '.project-manager-scroller-content .arrow-next',
																				prevSelector: '.project-manager-scroller-content .arrow-prev',
																				nextText: '<img src="' + KSAND.json.theme_path_uri + '/assets/images/icons/arrow-next.png" alt="Next" />',
																				prevText: '<img src="' + KSAND.json.theme_path_uri + '/assets/images/icons/arrow-prev.png" alt="Previous" />'
																});
												} else {
																//Reload the slider
																Projects_Manager.scroller.obj.reloadSlider();
												}
								}
				}
				
				/**********************************************************************
					* Initializes the thumbnail scroller for the project pages on resize
					*********************************************************************/
				Projects_Manager.bind_events = function(){
								//Bind the thumbnail list click event
								$('#project-detail-body .project-detail-sidebar ul li a.thumb-scroller').on('click', function(e){
												//Open the scroller with the desired image preset
												Projects_Manager.scroller.open(e, $(this).data('key'), true);
												
												//Prevent default action
												e.preventDefault();
												return false;
								});
								
								//Bind the click event for the larger project picture
								$('#project-detail-body div.project-detail-text a.project-detail-trigger').on('click', function(e){
												//Find the number of slides in the scroller
												var slides = $('.project-manager-scroller').find('ul li').length - 2;
												
												//Open the scroller with the desired image preset
												Projects_Manager.scroller.open(e, slides-1, true);
												
												//Prevent default action
												e.preventDefault();
												return false;
								});
								
								//Bind the click handler for the scroller close button
								$('.close-x-container').on('click', Projects_Manager.scroller.close);
                                                                //Bind Escape key down to close the scroller
                                                                $(document).keyup(function(e) { 
                                                                    if (e.which == 27) Projects_Manager.scroller.close(e);
                                                                });
				}
				
				/**********************************************************************
					* Opens the scroller
					*********************************************************************/
				Projects_Manager.scroller.open = function(e, project_key, ajax, custom_obj){
								//Process any optional parameters
								project_key = typeof project_key=='undefined' ? 0 : project_key;
								ajax = typeof ajax=='undefined' ? false : ajax;
								
								//Expand the scroller width
								Projects_Manager.cache.scroller.parent('div').animate({ width: '100%' }, Projects_Manager.animSpeed, function(){
												//Fade in the logo, if one exists
												$('.project-manager-scroller .project-manager-scroller-content img.project-manager-scroller-logo').show();
																
												//Expand the scroller height
												$(this).animate({ height: $(window).height() + 'px' }, Projects_Manager.animSpeed, function(){
																
																//Position and fade in the scroller images
                                                                                                                                //Scroller Height
                                                                                                                                var scroller_height = Projects_Manager.cache.scroller.parent('div').height();
                                                                                                                                Projects_Manager.cache.scroller.css('height', scroller_height);
                                                                                                                                if ($(window).height() <= 600) {
                                                                                                                                    var img_height = scroller_height * 0.5; //Make main image 50% of available scroller height on small devices
                                                                                                                                } else {
                                                                                                                                    var img_height = scroller_height * 0.7; //Make main image 70% of available scroller height
                                                                                                                                }
                                                                                                                                
                                                                                                                                Projects_Manager.cache.scroller_list.find('li a:last-of-type').css('height',img_height);
																Projects_Manager.cache.scroller_list.parents('div.project-manager-scroller-content-body').css({ marginLeft: Math.abs((Projects_Manager.cache.scroller.parent('div').width()/2)-(Projects_Manager.cache.scroller_list.parent('div').width()/2)-100) }).show();
                                                                                                });
                                                                //Bind Left and Right arrow Keys
                                                                $(window).bind("keyup", function(e){
                                                                        if(e.keyCode === 37) {
                                                                            jQuery(".bx-prev").click();
                                                                        } else if (e.keyCode === 39) {
                                                                            jQuery(".bx-next").click(); 
                                                                        }
                                                                    });
								});
								
								//Determine if a custom scroller object was passed
								if (typeof custom_obj!='undefined'){
												//Skip to passed slide using custom object
												custom_obj.goToSlide(project_key);
								} else {
												//Skip to passed slide
												Projects_Manager.scroller.obj.goToSlide(project_key);
								}
								
								//Prevent default action
								if (typeof e!='undefined') e.preventDefault();
								return false;
				}
				
				/**********************************************************************
					* Closes the scroller
					*********************************************************************/
				Projects_Manager.scroller.close = function(e){
								//Retract the height
								Projects_Manager.cache.scroller.parent('div').animate({ height: 0 }, Projects_Manager.animSpeed);
								
                                                                $(window).unbind( "keyup" );
                                                                
								//Prevent default action
								e.preventDefault();
								return false;
				}
				
				/**********************************************************************
					* Changes the image in the scroller preview
					*********************************************************************/
				Projects_Manager.scroller.change_img = function(img_src, fade){
								//Process optional parameters
								fade = typeof fade=='undefined' ? true : fade;
								
								//Check if we are fading
								if (fade){
												//Fade out the preview image
												Projects_Manager.cache.scroller_preview.find('img').fadeOut(Projects_Manager.animSpeed, function(){
																//Set the image source and fade it in
																$(this).attr('src', img_src).fadeIn(Projects_Manager.animSpeed);
												});
								} else {
												//Hide the preview image, set the new one and show it
												Projects_Manager.cache.scroller_preview.find('img').hide.attr('src', img_src).show();
								}
				}
}(jQuery));