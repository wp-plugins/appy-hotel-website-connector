<?php
// MOVE this 'constants' elsewhere!!!!!
  $appy_plugin_name = 'Appy Hotel Website Connector' ;

?>



<?php 
  if(isset($_GET['settings-updated']) && $settings['first-install']!=true  && $refreshed!=true ) {
    if($_GET['settings-updated']==true) {
      $save_ok = true ;
    }
  }
?>


<div class="wrap" style="background-color:#fff; padding:0 24px;">

  <div class="jumbotron" style="text-align: center; background-color: rgb(255, 255, 255); margin-bottom: 0px; padding-bottom: 0px;">

    <?php if ($save_ok==true) { ?>
     <div id="save-panel" class="panel panel-success collapse">
      <div class="panel-body bg-success">
        <h1><span class="label label-success">Cool!</span></h1>
        <br/>
        <p>Your settings have been saved!</p>
      </div>
    </div>     
    <?php } ?>

      <div class="row">
        <div class="col-md-12">
          <a href="http://www.appyhotel.com/appy-hotel-website-connector/" target="_blank">
            <img src="<?php echo APPY_ASSET_DIR;?>img/logo-connector_white.png" width="256" class="img-rounded">
          </a>
          <p><a href="http://www.appyhotel.com/" target="_blank">Appy Hotel</a> is a comprehensive digital marketing platform that enables hotels to build and manage their own apps and websites.<br/>
            This plugin enables the publication of data entered via Appy Hotel's proprietary <a href="https://happy.appyhotel.com/login">backend</a> directly to any Wordpress based website.</p>
          <p>Use the plugin in tandem with our provided <a href="http://www.appyhotel.com/appy-hotel-website-connector/" target="_blank">themes</a>, or create your own.</p>
        </div>
      </div>
  </div>
  

  <form name="appy-connector-form" method="post" action="options.php" onsubmit="return appyValidateForm();">
    <?php settings_fields('appy_settings_group'); ?>
    <?php $settings = get_option('appy_options'); ?>

    <div class="jumbotron text-center" style="background-color: rgb(255, 255, 255); padding-top: 0px;">
      
      <div class="row">
        <div class="col-md-12">
        <hr class="whch"/>
          <p class="lead text-uppercase">- Account Settings -</p>
        <hr class="whch"/>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div class="panel border-1">
            <div class="panel-body">
              <div>
                <p>Your Appy Hotel ID</p>
              </div>

              <div id="appy-id-form-group" class="form-group">
                <label class="control-label hidden" for="input-appy-id">Please, check the ID you've just typed in...</label>
                <div class="input-group input-group-lg">
                  <span class="input-group-addon"><div class="dashicons dashicons-admin-users"></div></span>
                  <input type="text" name="appy_options[appy_id]" value="<?php echo $settings['appy_id']; ?>" size="7" maxlength="7" id="input-appy-id" class="form-control"
                     placeholder="Your Appy Hotel ID" style="font-size:1.9em; text-align:center; padding-left:0; padding-right:36px;"/>
                </div>
              </div>
              <div>
                <span class="text-muted"><em>Find it at the top of your Appy Hotel dashboard&nbsp;</em></span>
                <a href="#" alt="show me how" data-toggle="collapse" data-target="#img-explain-appyid">
                  <div class="dashicons dashicons-format-image"></div>
                </a>
              </div>
              <div id="img-explain-appyid" class="collapse" style="overflow-x:hidden;"><br/><img width="1000" src='<?php echo APPY_ASSET_DIR ; ?>/wp_connector_appyID.png'></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div class="panel border-1">
            <div class="panel-body">
              <div>
                <p>Your Appy API Key</p>
              </div>
              <div id="appy-key-form-group" class="form-group">
                <label class="control-label hidden" for="input-appy-key">Please, check the API Key you've just typed in...</label>
                <div class="input-group input-group-lg">
                  <span class="input-group-addon"><div class="dashicons dashicons-admin-network"></div></span>
                  <input type="text" name="appy_options[appy_api_key]" value="<?php echo $settings['appy_api_key']; ?>" maxlength="32" size="29" id="input-appy-key" class="form-control"
                   placeholder="Your API Key" style="font-size:1.9em; text-align:center; padding-left:0; padding-right:36px;"/>
                </div>
              </div>
              <div>
                <span class="text-muted"><em>To get your API key please access your <a href="https://happy.appyhotel.com/login" target="_blank">Appy Hotel Dashboard</a> and go to <u>Settings</u> > <u>Platform</u></em></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 text-center">
          <input type='submit' class="btn btn-lg btn-success text-uppercase" value='Save Settings'>
        </div>
      </div>

    </div>

  </form>

</div>
