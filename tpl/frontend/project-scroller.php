<?php global $projects_manager; ?>
<div class="project-manager-scroller">
	<div class="project-manager-scroller-content">
		<div class="project-manager-scroller-content-body">
			<div class="project-manager-scroller-list-container">
				<ul>
					<?php
					//Loop over the thumbnails
					foreach($post->related as $key=>$project){
						//Get the dimensions of the image
						list($img_width, $img_height) = getimagesize($ksand->utils->url_to_fs_path($project->meta['project-manager-logo']));
						
						//Show the image
						?>
						<li>
							<h3><a href="<?php echo get_permalink($project->ID); ?>" title="<?php echo $project->post_title; ?>" style="background: url('<?php echo $project->meta['project-manager-logo']; ?>') no-repeat center center; background-color: #fff; background-size: contain; display: block; height: 130px; margin: 20px auto 20px; text-indent: -9999px; width: 30%;"><?php echo $project->post_title; ?></a></h3>
							<a class="bg-image" href="<?php echo get_permalink($project->ID); ?>" title="<?php echo $project->post_title; ?>" style="background: url('<?php echo $project->meta['project-manager-images'][0]; ?>') no-repeat center center;" data-key="<?php echo $key; ?>"><?php echo $project->post_title; ?></a>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
			<a href="#prev-image" class="icon-arrow arrow-prev" title="Previous"></a>
			<a href="#next-image" class="icon-arrow arrow-next" title="Next"></a>
			<a href="#close-scroller" class="close-x-container"><div class="close-x"><div class="close-x-outer"><div class="close-x-inner"></div></div></div></a>
		</div>
	</div>
</div>