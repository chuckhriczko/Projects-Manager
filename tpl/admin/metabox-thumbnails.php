<div id="project-manager-thumbnail-container">
<?php
				if (isset($thumbnails) && !empty($thumbnails)){
								foreach($thumbnails as $key=>$thumbnail){
												?>
												<div class="project-manager-thumbnail">
																<img src="<?php echo $thumbnail; ?>" width="128" height="128" />
																<input type="hidden" id="project-manager-thumbnail-url" name="project-manager-thumbnail-url[]" value="<?php echo $thumbnail; ?>" />
																<a href="#remove-thumbnail">Remove Thumbnail</a>
												</div>
												<?php
								}
				}
?>
<div class="project-manager-thumbnail"><a href="#thumbnail">Add Thumbnail</a></div>
<br class="clear" />
</div>