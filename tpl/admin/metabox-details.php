<label for="project-manager-featured" class="inline"><input type="checkbox" id="project-manager-featured" name="project-manager-featured" value="1"<?php echo isset($data['project-manager-featured']) && $data['project-manager-featured']==1 ? ' checked="checked"' : ''; ?> /> Featured Project?</label>

<label for="project-manager-client">Project's Client</label>
<select id="project-manager-client" name="project-manager-client">
				<option value="0">Select a client...</option>
				<?php
				//Loop through the clients
				foreach($clients as $client){
								?><option value="<?php echo $client->id; ?>"<?php echo isset($data['project-manager-client']) && !empty($data['project-manager-client']) && $data['project-manager-client']==$client->id ? ' selected="selected"' : ''; ?>><?php echo stripslashes($client->name); ?></option><?php
				}
				?>
</select>

<label for="project-manager-description">Project Description</label>
<input type="text" id="project-manager-description" name="project-manager-description" value="<?php echo isset($data['project-manager-description']) ? $data['project-manager-description'] : ''; ?>" />

<label for="project-manager-url">Project URL</label>
<input type="text" id="project-manager-url" name="project-manager-url" value="<?php echo isset($data['project-manager-url']) ? $data['project-manager-url'] : ''; ?>" />

<label for="project-manager-sort-order">Sort Order</label>
<input type="text" id="project-manager-sort-order" name="project-manager-sort-order" value="<?php echo isset($data['project-manager-sort-order']) ? $data['project-manager-sort-order'] : $new_sort_order; ?>" />

<div class="project-manager-tabbed-list">
				<label for="project-manager-services">Services</label>
				<input type="text" id="project-manager-services" name="project-manager-services" value="" />
				<button type="button" id="project-manager-services-add" name="project-manager-services-add">Add</button>
				<ul id="project-manager-services-list">
								<?php
								//Loop through the services array
								foreach($data['services'] as $service){
												?>
												<li>
																<a href="#remove" title="Remove"><?php echo $service; ?><div class="close-x small"><div class="close-x-outer"><div class="close-x-inner"></div></div></div></a>
																<input type="hidden" name="project-manager-services-hidden[]" value="<?php echo $service; ?>" />
												</li>
												<?php
								}
								?>
				</ul>
				<br class="cl" />
</div>

<div class="project-manager-tabbed-list">
				<label for="project-manager-tools">Tools</label>
				<input type="text" id="project-manager-tools" name="project-manager-tools" value="" />
				<button type="button" id="project-manager-tools-add" name="project-manager-tools-add">Add</button>
				<ul id="project-manager-tools-list" class="project-manager-tabbed-list">
								<?php
								//Loop through the tools array
								foreach($data['tools'] as $tool){
												?>
												<li>
																<a href="#remove" title="Remove"><?php echo $tool; ?><div class="close-x small"><div class="close-x-outer"><div class="close-x-inner"></div></div></div></a>
																<input type="hidden" name="project-manager-tools-hidden[]" value="<?php echo $tool; ?>" />
												</li>
												<?php
								}
								?>
				</ul>
				<br class="cl" />
</div>