<?php global $ksand, $wp_images; ?>

<div class="project-manager-container">
  <ul>
    <?php
				//Loop through the projects
				foreach($projects as $project){
								//Get the dimensions of the featured image for this project
								list($width, $height) = getimagesize($ksand->utils->url_to_fs_path($project->meta['project-manager-logo']));
								
								//Get the first image if it exists
								$thumb_filename = isset($project->meta['project-manager-images'][0]) ? $project->meta['project-manager-images'][0] : $project->meta['img_src'][0];
								?>
								<li data-sort-order="<?php echo $project->meta['project-manager-sort-order']; ?>">
										<div class="project-manager-card<?php echo $transition ? ' project-manager-transition-'.$transition_type : ''; ?>">
											<a class="project-manager-front" href="<?php echo get_permalink($project->ID); ?>" title="<?php echo $project->post_title; ?>">
												<div class="project-manager-image-container">
													<div class="project-manager-image project-manager-image-<?php echo $height>$width ? 'high' : 'long'; ?>" style="background-image: url('<?php echo $project->meta['project-manager-logo']; ?>');"></div>
												</div>
												</a>
												<a class="project-manager-back project-type-<?php echo implode(' project-type-', $project->meta['type_slugs']); ?>" href="<?php echo get_permalink($project->ID); ?>" title="<?php echo $project->post_title; ?>" style="background: url('<?php echo $thumb_filename; ?>') no-repeat center center; background-size: contain; background-color: #fff; display: block;"><span><span><?php echo $project->post_title; ?></span></span> </a> </div>
								</li>
								<?php
								}
				?>
  </ul>
</div>
