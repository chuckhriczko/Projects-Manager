<section id="project-manager-logo">
				<label class="project-manager-logo-container" for="project-manager-upload-logo">
								<?php if (isset($logo) && !empty($logo)){ ?>
												<p>Note: This image appears on top of the project detail page.</p>
												<img src="<?php echo $logo; ?>" />
												<a href="#remove-logo" title="Remove Logo">Remove Logo</a>
								<?php } else { ?>
												<p>You haven't uploaded a logo for this project yet.</p>
												<a href="#add-logo" title="Add Logo">Add Logo</a>
								<?php } ?>
				</label>
				<input type="hidden" name="project-manager-logo" value="<?php echo isset($logo) ? $logo : ''; ?>" />
</section>