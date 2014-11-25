<?php $sp = admin_url() . 'admin.php?page=appy-connector-application-settings' ; ?>

<div class="jumbotron" style="background-color:#fff;">

	<div class="container">

	<?php if($_SERVER['PHP_SELF'] == '/wp-admin/theme-install.php') { ?>

		<div class="row">
			<div class="col-md-2">
				<a href="http://www.appyhotel.com" target="_blank">
					<img src="<?php echo APPY_ASSET_DIR;?>img/logo-256x256.png" width="100" class="img-rounded">
				</a>
			</div>
			<div class="col-md-8 text-center">
				<h3>How to install your theme</h3>
				<p>
					To install your theme:
					<ol>
						<li>If you've not done so yet, download your selected theme: 
							<a href="http://www.appyhotel.com/wp-content/uploads/2014/10/wpappyorca.zip" target="_blank">Orca</a> / <a href="http://www.appyhotel.com/wp-content/uploads/2014/10/wpappydolphin.zip" target="_blank">Dolphin</a>
						</li>

						<li>Locate the theme zip file in your download folder.</li>
						
						<li>Install the theme zip file to your Wordpress installation.</li>

						<li>Activate it.</li>
					</ol>
				</p>
			</div>
			<div class="col-md-2">
				&nbsp;
			</div>
		</div>

	<?php } else if($_SERVER['PHP_SELF'] == '/wp-admin/update.php') { ?>
		<div class="row">
			<div class="col-md-2">
				<a href="http://www.appyhotel.com" target="_blank">
					<img src="<?php echo APPY_ASSET_DIR;?>img/logo-256x256.png" width="100" class="img-rounded">
				</a>
			</div>
			<div class="col-md-8 text-center">
				<h4>Activate your theme</h4>
			</div>
			<div class="col-md-2">
				&nbsp;
			</div>
		</div>

	<?php } else if($_SERVER['PHP_SELF'] == '/wp-admin/themes.php') { ?>
		<div class="row">
			<div class="col-md-2">
				<a href="http://www.appyhotel.com" target="_blank">
					<img src="<?php echo APPY_ASSET_DIR;?>img/logo-256x256.png" width="100" class="img-rounded">
				</a>
			</div>
			<div class="col-md-8 text-center">
				<h3>Congratulations! Your theme is running.</h3>
				<p>
					Go to Appy Hotel Website Connector plugin <a href="/wp-admin/admin.php?page=appy-connector-application-settings">settings page</a>.
				</p>
			</div>
			<div class="col-md-2">
				&nbsp;
			</div>
		</div>


	<?php } else { ?>
		<div class="row">
			<div class="col-md-2">
				<a href="http://www.appyhotel.com" target="_blank">
					<img src="<?php echo APPY_ASSET_DIR;?>img/logo-256x256.png" width="100" class="img-rounded">
				</a>
			</div>
			<div class="col-md-8 text-center">
				<h3>Thanks for installing Appy Hotel Website Connector!</h3>
				<a href="<?php echo $sp; ?>" class="btn btn-lg btn-success" role="button">Let's Go !</a>
			</div>
			<div class="col-md-2">
				&nbsp;
			</div>
		</div>

	<?php } ?>
	</div>

</div>