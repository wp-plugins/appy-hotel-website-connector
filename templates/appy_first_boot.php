<div class="modal fade" id="themeSelectionModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="text-align:center;">
	<div class="modal-dialog modal-lg">
		<div class="jumbotron" style="text-align:center; background-color:#fff;">
			<div class="row">
				<div class="col-md-12 text-center">
					<a href="http://www.appyhotel.com" target="_blank">
						<img src="<?php echo APPY_ASSET_DIR;?>img/logo-connector_white.png" width="256" class="img-rounded">
					</a>
					<p class="lead">Choose which of our themes you wish to use for your website (they're free):</p>
				</div>
			</div>


			<div class="container">

				<div class="row">
					<div class="col-md-6">
						<div class="thumbnail">
							<img src="<?php echo APPY_ASSET_DIR ;?>img/hotel-website-theme-wordpress-dolphin-450x333.jpg" alt="Appy Hotel Orca Theme">
							<div class="caption">
								<h3 class="text-muted">Dolphin</h3>
								<p>Hotel website WordPress theme</p>
								<p>
									<a id="dolphin-download-button" href="http://www.appyhotel.com/wp-content/uploads/2014/10/wpappydolphin.zip" class="btn btn-primary" role="button" target="_blank">Get it!</a>
									<a href="http://dolphin.appyhotel.com" target="_blank"><span class="small">Preview</span></a>
								</p>
								<span id="dolphin-download-message"></span>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="thumbnail">
							<img src="<?php echo APPY_ASSET_DIR ;?>img/hotel-website-theme-wordpress-orca-450x333.jpg" alt="Appy Hotel Orca Theme">
							<div class="caption">
								<h3 class="text-muted">Orca</h3>
								<p>Hotel website WordPress theme</p>
								<p>
									<a id="orca-download-button" href="http://www.appyhotel.com/wp-content/uploads/2014/10/wpappyorca.zip" class="btn btn-primary" role="button" target="_blank">Get it!</a>
									<a href="http://orca.appyhotel.com" target="_blank"><span class="small">Preview</span></a>
								</p>
								<span id="orca-download-message"></span>
							</div>
						</div>
					</div>

					</div>

				</div>

			
			<div>
				<button type="button" class="btn btn-lg btn-success" data-toggle="modal" data-target="#accountModal" data-dismiss="modal">Continue</button>
				<br/>
				<a href="#" data-toggle="modal" data-target="#accountModal" data-dismiss="modal">Skip</a>
			</div>

		</div>


	</div>
</div>


<!-- data-dismiss="modal" -->
<div class="modal fade" id="accountModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="text-align:center;">
	<div class="modal-dialog modal-lg">
		<div class="jumbotron" style="text-align:center; background-color:#fff;">

  <form name="appy-connector-form" method="post" action="options.php">
    <?php settings_fields('appy_settings_group'); ?>
    <?php $settings = get_option('appy_options'); ?>

			<div class="container">

				<div class="row">
					<div class="col-md-12 text-center">
						<a href="http://www.appyhotel.com" target="_blank">
							<img src="<?php echo APPY_ASSET_DIR;?>img/logo-connector_white.png" width="256" class="img-rounded">
						</a>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
			            <p>The plugin must be associated with an Appy Hotel account.</p>
			            <p>Please enter your Appy Hotel ID and API key below, <br/>or use the following details for demo purposes:</p>
		            
			            <p class="text-center">
				            <span class="noo-bg-success">Demo Appy ID: 
				            	<strong class="text-muted"><span id="appy-demo-id" data-toggle="tooltip" data-placement="right" title="click to use" data-container="body">1111111</span></strong>
				            </span>
				            <br/>
			    	        <span class="noo-bg-success">Demo Appy API key: 
			    	        	<strong class="text-muted"><span id="appy-demo-key" data-toggle="tooltip" data-placement="right" title="click to use" data-container="body">4376e6151806990639949f8b95abf59d</span></strong>
			    	        </span>
		    	        </p>
					</div>

					<div class="col-md-12 text-center"><hr/></div>
				</div>


				<div class="row">
					<div class="col-md-5">
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><div class="dashicons dashicons-admin-users"></div></span>
							<input type="text" name="appy_options[appy_id]" value="<?php echo $settings['appy_id']; ?>" size="7" maxlength="7" id="input-appy-id" class="form-control"
								 data-toggle="tooltip" data-placement="top" title="i.e. '1111111'" data-container="body" placeholder="Your Appy Hotel ID"/>
						</div>
						<div>
							<span class="description">Find it at the top of your Appy Hotel dashboard&nbsp;</span>
							<a href="#" alt="show me how" data-toggle="collapse" data-target="#img-explain-appyid">
								<div class="dashicons dashicons-format-image"></div>
							</a>
							
						</div>
					</div>

					<div class="col-md-5">
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><div class="dashicons dashicons-admin-network"></div></span>
							<input type="text" name="appy_options[appy_api_key]" value="<?php echo $settings['appy_api_key']; ?>" maxlength="32" size="29" id="input-appy-key" class="form-control"
								 data-toggle="tooltip" data-placement="top" title="i.e. 'ac4422d713f3ce4f74c8de...'" data-container="body" placeholder="Your API Key"/>
						</div>
						<div>
							<span class="description">
								To get your API key please <a href="http://www.appyhotel.com/appy-hotel-website-connector/#apikey" target="_blank">apply</a>.
							</span>
						</div>
					</div>

					<div class="col-md-2">
						<input type="hidden" name="appy_options[first-install]" id="first-install" value="true" />
						<input type='submit' class="btn btn-lg btn-success" value='GO!'>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">&nbsp;</div>
					<div id="img-explain-appyid" class="collapse col-md-12" style="overflow-x:hidden;"><img width="1000" src='<?php echo APPY_ASSET_DIR ; ?>/wp_connector_appyID.png'></div>
				</div>

			</div>


	</form>
		</div>
	</div>
</div>