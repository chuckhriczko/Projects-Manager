<?php global $ksand, $wp_images, $projects_manager; ?>
<div class="project-manager-container project-manager-small">
  <ul>
    <?php
						//Loop through the projects
						foreach($projects as $key=>$project){
								?>
								<li data-sort-order="<?php echo $project->meta['project-manager-sort-order']; ?>" data-item-id="<?php echo $key; ?>">
										<div class="project-manager-card">
												<a class="project-manager-front<?php echo $scroller ? ' thumb-scroller' : ''; ?>" href="#" title="<?php echo $project->post_title; ?>" data-key="<?php echo $key; ?>" data-project-id="<?php echo $project->ID; ?>" style="background: url('<?php echo $project->meta['project-manager-images'][0]; ?>') no-repeat center center; background-size: contain; display: block;"></a>
										</div>
								</li>
								<?php
						}
				?>
  </ul>
</div>
<div class="project-manager-scroller">
		<div class="project-manager-scroller-content">
				<div class="project-manager-scroller-content-body">
						<div class="project-manager-scroller-list-container">
								<ul>
								<?php
										//Loop through the projects
										foreach($projects as $project){
											//Get the logo image
											$logo = isset($project->meta['project-manager-logo']) && !empty($project->meta['project-manager-logo']) ? $project->meta['project-manager-logo'] : null;
											
											//Convert the URL to a file system path
											$fs_logo = !empty($logo) ? $projects_manager->url_to_fs_path($logo) : $logo;
											
											//Verify the file exists
											$logo = file_exists($fs_logo) ? $logo : null;
												?><li>
																<?php echo !empty($logo) ? '<a href="'.get_permalink($project->ID).'" title="'.$project->post_title.'"><img src="'.$logo.'" class="project-manager-scroller-logo" /></a>' : ''; ?>
																<a href="<?php echo get_permalink($project->ID); ?>" style="background: url('<?php echo $project->meta['project-manager-images'][0]; ?>') no-repeat center center; background-color: #fff; background-size: contain; display: block; height: 600px; margin: 0 auto; text-indent: -9999px; width: 600px;"></a>
												</li><?php
										}
								?>
								</ul>                    
						</div>
						<a title="Previous" class="icon-arrow arrow-prev" href="#prev-image"><a href=""><img alt="Previous" src="/wp-content/themes/ksand/assets/images/icons/arrow-prev.png"></a></a>
						<a title="Next" class="icon-arrow arrow-next" href="#next-image"><a href=""><img alt="Next" src="/wp-content/themes/ksand/assets/images/icons/arrow-next.png"></a></a>
						<a class="close-x-container" href="#close-scroller">
								<div class="close-x">
												<div class="close-x-outer">
														<div class="close-x-inner"></div>
												</div>
								</div>
						</a>
				</div>
		</div>
</div>