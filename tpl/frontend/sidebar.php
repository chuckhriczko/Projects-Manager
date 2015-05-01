<aside class="project-detail-sidebar">
				<?php
				//Verify we have tools associated with this project
				if (count($post->meta['tools'])>0){
				?>
				<h3>Services:</h3>
				<ul>
						<?php
						//Loop over the services
						foreach($post->meta['services'] as $service){
										?><li><?php echo $service; ?></li><?php
						}
						?>
				</ul>
				<?php
				}
	
				//Verify we have tools associated with this project
				if (count($post->meta['tools'])>0){
								?>
								<h3>Tools:</h3>
								<ul>
								<?php
												//Loop over the services
												foreach($post->meta['tools'] as $tool){
																?><li><?php echo $tool; ?></li><?php
												}
								?>
								</ul>
								<?php
				}
	
				//Verify we have other projects for this client
				if (count($post->related)>1){ //$post always returns itself as related
                                                                
								?>
                                                                
								<h3>Other projects for this client:</h3>
								<ul class="project-manager-horizontal-list">
								<?php
								//Loop over the services
								foreach($post->related as $key=>$project){
												//Verify this is not the current project
												if ($project->ID!=$post->ID){ ?>
																<li>
																				<a href="<?php echo get_permalink($project->ID); ?>" class="thumb-scroller" title="<?php echo $project->post_title; ?>" style="background: url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($project->ID)); ?>') no-repeat center center; background-size: contain; background-color: #fff; display: block; text-indent: -9999px;"><?php echo $project->post_title; ?></a>
																</li>
												<?php
												}
								}
								?></ul><?php
				}
				?>
</aside>