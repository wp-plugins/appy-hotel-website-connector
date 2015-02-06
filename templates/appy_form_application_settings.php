<?php
  settings_fields('appy_settings_group');
  $settings = get_option('appy_options');
  if ( !strlen($settings['appy_id']) || !strlen($settings['appy_api_key']) ) {
    appy_connector_first_boot();
  } else {
?>

<?php 
  // MOVE this 'constants' elsewhere!!!!!
  $appy_plugin_name = 'Appy Hotel Website Connector' ;
  if(isset($_POST['refresh'])) {
    $result = appy_download_app();
    if( !empty($result) ) {
      $refresh_error = $result;
    } else {
      $refreshed = true;
    }
  } else if(isset($_POST['reset'])) {
    appy_reset_wordpress();
  }

  if(isset($_GET['settings-updated']) && $settings['first-install']!=true  && $refreshed!=true ) {
    if($_GET['settings-updated']==true) {
      $save_ok = true ;
    }
  }

?>

<div id="loader" class="hidden">
  <div id="loader-figure" class="jumbotron" style="text-align: center; background: transparent;">
    <div class="row">
      <div class="col-md-4 col-md-offset-4" style="background-color: rgb(255, 255, 255);">
        <div class="panel border-1">
          <div class="panel-body">
            <img src="<?php echo APPY_ASSET_DIR;?>img/logo-connector_white.png" width="160" class="img-rounded">
            <!-- <p>is loading your contents</p> --><br/>
            <img src="<?php echo APPY_ASSET_DIR;?>img/ajax-loader.gif">
            <p>Please Wait</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="wrap" style="background-color:#fff; padding:0 24px;">
  
  <div class="jumbotron" style="text-align: center; background-color: rgb(255, 255, 255); margin-bottom: 0px; padding-bottom: 0px;">

    <?php if($settings['first-install']==true) { ?>
    <div id="install-panel" class="panel panel-success">
      <div class="panel-body bg-success">
        <h1><span class="label label-success">Cool!</span></h1>
        <br/>
        <p>Thank you for having installed <?php echo $appy_plugin_name; ?>! Choose your settings, save, refresh, and you make it!</p>
      </div>
    </div>
    <?php } ?>

    <?php if ($save_ok==true) { ?>
     <div id="save-panel" class="panel panel-success collapse">
      <div class="panel-body bg-success">
        <h1><span class="label label-success">Cool!</span></h1>
        <br/>
        <p>Your settings have been saved!</p>
      </div>
    </div>     
    <?php } ?>

    <?php if ($refreshed) { ?>
     <div id="refresh-panel" class="panel panel-success collapse">
      <div class="panel-body bg-success">
        <h1><span class="label label-success">Cool!</span></h1>
        <br/>
        <p>Your contents have been refreshed!</p>
      </div>
    </div>
    <?php } ?>

      <div class="row">
        <div class="col-md-12">
          <a href="http://www.appyhotel.com" target="_blank">
            <img src="<?php echo APPY_ASSET_DIR;?>img/logo-connector_white.png" width="256" class="img-rounded">
          </a>
          <p><a href="http://www.appyhotel.com/">Appy Hotel</a> is a comprehensive digital marketing platform that enables hotels to build and manage their own apps and websites.<br/>
              This plugin enables the publication of data entered via Appy Hotel's proprietary <a href="https://happy.appyhotel.com/login">backend</a> directly to any Wordpress based website.</p>
          <p>Use the plugin in tandem with our provided <a href="http://www.appyhotel.com/appy-hotel-website-connector/" target="_blank">themes</a>, or create your own.</p>
        </div>
      </div>
  </div>
  

  <form name="appy-connector-form" method="post" action="options.php">
    <?php settings_fields('appy_settings_group'); ?>
    <?php $settings = get_option('appy_options'); ?>

    <div class="jumbotron text-center" style="background-color: rgb(255, 255, 255); padding-top: 0px;">

      <div class="row">
        <div class="col-md-12">
        <hr class="whch"/>
          <p class="lead text-uppercase">- Application Settings -</p>
        <hr class="whch"/>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <input type='submit' class="btn btn-lg btn-success text-uppercase" value='Save Settings'>
        </div>
        <div class="row"><div class="col-md-12"><hr class="whch"/></div></div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="panel bottom-6 border-1">
            <div class="panel-body">
              <div class="switch-wrapper">
                <input type="checkbox" data-label="Twitter Widget" name="appy_options[appy_twitter_widget_enabled]" value="1"<?php checked( 1 == $settings['appy_twitter_widget_enabled'] ); ?> />
              </div>
            </div>
          </div>
          <span class="text-muted"><em>Check this to display a Twitter widget with your hotel’s latest tweets on your website. 
            Connect your hotel’s Twitter handle via your Appy Hotel dashboard. Head to <u>Settings</u> > <u>Hotel Details</u>.</em></span>
          <hr class="whch"/>
        </div>

        <div class="col-md-4">
          <div class="panel bottom-6 border-1">
            <div class="panel-body">
              <div class="switch-wrapper">
                <input type="checkbox" data-label="Facebook Widget" name="appy_options[appy_facebook_widget_enabled]" value="1"<?php checked( 1 == $settings['appy_facebook_widget_enabled'] ); ?> />
              </div>
            </div>
          </div>
          <span class="text-muted"><em>Check this to display a Facebook widget connected to your hotel’s page on your website.<br/>
            Connect your hotel’s Facebook page via your Appy Hotel dashboard. Head to  <u>Settings</u> > <u>Hotel Details</u></em></span>
          <hr class="whch"/>
        </div>

        <div class="col-md-4">
          <div class="panel bottom-6 border-1">
            <div class="panel-body">
              <div class="switch-wrapper">
                <input type="checkbox" data-label="Blog Widget" name="appy_options[appy_blog_enabled]" id="appy_blog_switch" value="1"<?php checked( 1 == $settings['appy_blog_enabled'] ); ?> />
              </div>
            </div>
          </div>
          <span class="text-muted"><em>Check this to enable display of a blog your hotel’s website. The blog is managed from within your Wordpress dashboard.</em></span>
          <span class="text-muted"><em><b>Note:</b>&nbsp;&nbsp;To display a post you must select , from the page category menu, for which languages it should display&nbsp;
          <a href="#"  data-toggle="collapse" data-target="#img-explain-blog"><div class="dashicons dashicons-format-image"></div></a></em></span>
          <div id="img-explain-blog" class="collapse" style="overflow-x:hidden;"><img src='<?php echo APPY_ASSET_DIR ; ?>img/wp_connector_blog.png'></div>
          <hr class="whch"/>
        </div>

      </div>

      <div class="row">

        <div class="col-md-4">
          <div class="panel bottom-6 border-1">
            <div class="panel-body">
              <div class="switch-wrapper">
                <input type="checkbox" data-label="Pride Widget" name="appy_options[appy_pride_enabled]" id="appy_pride_switch" value="1"<?php checked( 1 == $settings['appy_pride_enabled'] ); ?> />
              </div>
            </div>
          </div>
          <span class="text-muted"><em>Check this to enable display of the Pride Widget on your hotel’s website</em></span>
          <hr class="whch"/>
        </div>

        <div class="col-md-4 col-md-offset-4">
          <div class="panel bottom-6 border-1">
            <div class="panel-body">
              <img src="<?php echo APPY_ASSET_DIR;?>img/adv_sample.jpg" width="100%" class="img-rounded">
            </div>
          </div>
        </div>
      
      </div>

      <input type="hidden" name="appy_options[first-install]" id="first-install" value='' />
      <input type="hidden" name="appy_options[appy_id]" value="<?php echo $settings['appy_id']; ?>"/>
      <input type="hidden" name="appy_options[appy_api_key]" value="<?php echo $settings['appy_api_key']; ?>"/>

</form>

      <?php if(appy_config_set() == true) { ?>
      <form method="post">
      <div class="row"><div class="col-md-12"><hr class="whch"/></div></div>
      
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div id="refresh-form-panel" class="panel bottom-6 border-1">
            <div class="panel-body">
              <div style="margin-bottom:12px;">
                <span>Last refresh on 
                  <strong><?php echo(appy_time(appy_get_last_refresh())); ?></strong>
                </span>
              </div>
              <div class="loading">
                <input type="hidden" name="refresh" id="refresh" value="true" />
                <input id="refresh-button" type="submit" value='Force Refresh' class="btn btn-lg btn-primary text-uppercase"/>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row"><div class="col-md-12"><hr class="whch"/></div></div>
      </form>

      <form method="post">
        <div class="row"><div class="col-md-12"></div></div>
        
        <div class="row">
          <div class="col-md-6 col-md-offset-3">
            <div id="reset-form-panel" class="panel bottom-6 border-1">
              <div class="panel-body">
                <div style="margin-bottom:12px;">
                  <span>Reset my Wordpress Installation</span>
                  <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    This will delete all the pages in your current installation. (But not the posts.)
                  </div>
                </div>
                <div class="loading">
                  <input type="hidden" name="reset" id="reset" value="true" />
                  <input id="reset-button" type="submit" value='Reset' class="btn btn-lg btn-danger text-uppercase"/>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row"><div class="col-md-12"><hr class="whch"/></div></div>
      </form>
      <?php } ?>


    </div>
 

</div>

<?php } /* closes the 'if' about 1st boot */ ?> 


<?php
  function appy_time($mysqldatetime){
    $phpdate = strtotime( $mysqldatetime );
    $formatted_ts = date( 'l, dS F Y @H:i:s (e)', $phpdate );
    return $formatted_ts ;
  }
?>