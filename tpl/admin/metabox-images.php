<?php if (count($data['images']>0) && !empty($data['images'][0])){ ?>
				<p>Click an image to set it as the featured image for this project.</p>
				<section id="project-manager-images-list">
								<?php
								foreach($data['images'] as $key=>$image){
												?>
												<label class="project-manager-image-container" for="project-manager-upload-image-<?php echo $key; ?>">
																<input type="radio" id="project-manager-upload-image_<?php echo $key; ?>" name="project-manager-featured-image[]" value="<?php echo $key; ?>"<?php echo $key==$data['featured-image'] && $data['featured-image']!='' ? ' checked="checked"' : ''; ?> />
																<img src="<?php echo $image; ?>" alt="<?php echo $image; ?>" width="128" height="128" />
																<a href="#remove" title="Remove Image" data-key="<?php echo $key; ?>">Remove</a>
																<input type="hidden" name="project-manager-images[]" value="<?php echo $image; ?>" />
												</label>
												<?php
								}
								?>
				</section>
<?php } else { ?>
				<p>You haven't uploaded any images for this project yet.</p>
				<section id="project-manager-images-list"></section>
<?php } ?>
<input id="project-manager-images-btn-submit" class="button project-manager-upload-image-btn" type="button" value="Add Image(s)" />
<input id="project-manager-images-btn-remove-all" class="button" type="button" value="Remove All Images" />