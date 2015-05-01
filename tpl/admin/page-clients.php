<div id="projects-manager-clients">
				<h3>Clients</h3>
				<table class="widefat fixed" cellspacing="0">
								<thead>
												<tr>
																<th id="tbl-projects-manager-clients-name" class="projects-manager-clients-name" scope="col">Name</th>
																<th id="tbl-projects-manager-clients-options" class="projects-manager-clients-options" scope="col">Options</th>
												</tr>
								</thead>
								<tbody>
												<?php
												//Verify we have clients
												if (empty($clients)){
																?><tr><td colspan="2"><h3>No clients were found. Please add a client below.</h3></td></tr><?php
												} else {
																//Loop through the clients
																foreach($clients as $key=>$client){
																				?>
																				<tr<?php echo $key%2==1 ? ' class="alternate"' : ''; ?>>
																								<td class="projects-manager-clients-name"><?php echo stripslashes($client->name); ?></td>
																								<td class="projects-manager-clients-options"><a href="#delete" title="Delete" data-id="<?php echo $client->id; ?>">&#739;</a></td>
																				</tr>
																				<?php
																}
												}
												?>
								</tbody>
								<tfoot>
												<tr>
																<th class="projects-manager-clients-name" scope="col">Name</th>
																<th class="projects-manager-clients-options" scope="col">Options</th>
												</tr>
								</tfoot>
				</table>
				<div class="projects-manager-clients-add">
								<h3>Add Client</h3>
								<label for="projects-manager-clients-name">Name: <input type="text" id="projects-manager-clients-name" name="projects-manager-clients-name" /></label>
								<button type="button" class="button secondary-button" id="projects-manager-client-add-btn">Add Client</button>
				</div>
</div>