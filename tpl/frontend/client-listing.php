<?php global $ksand; ?>
<div class="project-manager-clients-container">
				<ul>
								<?php
												//Loop through the projects
												foreach($clients as $client){
																?>
																<li data-id="<?php echo $client->id; ?>"><?php echo $client->name; ?></li>
																<?php
												}
								?>
				</ul>
</div>