<?php 
  // Refresh the app is the refresh form was submitted.
  if(isset($_POST['refresh'])) {
    $result = appy_download_app();

    if( !empty($result) ) {
      $refresh_error = $result;
    } else {
      $refreshed = true;
    }
  }
?>

<div class="wrap">
  <h2>Appy Connector Settings</h2>

  <?php if ($refreshed): ?>
    <div id="setting-error-settings_updated" class="updated settings-error"> 
      <p>
        <strong>Content updated.</strong>
      </p>
    </div>
  <?php endif; ?>


  <?php if ($refresh_error): ?>
    <div id="setting-error-settings_updated" class="updated settings-error"> 
      <p>
        <strong><?php echo $refresh_error ?></strong>
      </p>
    </div>
  <?php endif; ?>

  <p>In order to start using Appy Connector, you need to define your Hotel ID and your API Key. Content will be updated every hour.</p>
  <form method="post" action="options.php">
    <?php settings_fields('appy_settings_group'); ?>
    <?php $settings = get_option('appy_options'); ?>

    <table class="form-table">
      <tbody>

        <tr>
          <th><label for="appy_options[appy_api_url]">Appy API URL: </label></th>
          <td>
            <input type="text" name="appy_options[appy_api_url]" value="<?php echo $settings['appy_api_url']; ?>" class="code" />
            <p class="description">The Appy API URL.</p>
          </td>
        </tr>

        <tr>
          <th><label for="appy_options[appy_id]">Your Appy ID : </label></th>
          <td>
            <input type="text" name="appy_options[appy_id]" value="<?php echo $settings['appy_id']; ?>" class="code" />
            <p class="description">The unique identifier of your hotel. It can be found in AppyHotel backend and should be composed of 7 digits.</p>
          </td>
        </tr>

        <tr valign="top"><th><label for="appy_options[appy_api_key]">Your API Key : </label></th>
          <td>
            <input type="text" name="appy_options[appy_api_key]" value="<?php echo $settings['appy_api_key']; ?>" class="regular-text code"/>
            <p class="description">If you don't have an API Key, contact <a mailto="support@appyhotel.com">support@appyhotel.com.</a></p>
          </td>
        </tr>

        <tr valign="top"><th><label for="appy_options[appy_pride_enabled]">Enable 'Pride' feature : </label></th>
          <td>
            <input type="checkbox" name="appy_options[appy_pride_enabled]" value="1"<?php checked( 1 == $settings['appy_pride_enabled'] ); ?> />
          </td>
        </tr>

        <tr valign="top"><th><label for="appy_options[appy_blog_enabled]">Enable Blog : </label></th>
          <td>
            <input type="checkbox" name="appy_options[appy_blog_enabled]" value="1"<?php checked( 1 == $settings['appy_blog_enabled'] ); ?> />
          </td>
        </tr>

        <tr valign="top"><th><label for="appy_options[appy_facebook_widget_enabled]">Enable Facebook Widget on Home page : </label></th>
          <td>
            <input type="checkbox" name="appy_options[appy_facebook_widget_enabled]" value="1"<?php checked( 1 == $settings['appy_facebook_widget_enabled'] ); ?> />
          </td>
        </tr>

        <tr valign="top"><th><label for="appy_options[appy_twitter_widget_enabled]">Enable Twitter Widget on Home page : </label></th>
          <td>
            <input type="checkbox" name="appy_options[appy_twitter_widget_enabled]" value="1"<?php checked( 1 == $settings['appy_twitter_widget_enabled'] ); ?> />
          </td>
        </tr>

        <tr>
          <th>
            <!-- <label for="appy_options[appy_booking_enabled]">Enable Appy Booking : </label> -->
          </th>
          <td>
            <!--
            <?php $checked = ( $settings['appy_booking_enabled'] == "appy_booking_enabled" ) ? 'checked' : ''; ?>
            <input type="checkbox" name="appy_options[appy_booking_enabled]" value="appy_booking_enabled" <?php echo $checked ?> />
            <p class="description">Enable or disable appy booking.</p>
            -->
            <input type="hidden" name="appy_options[appy_booking_enabled]" value="" />
          </td>
        </tr>
      </tbody>
    </table>

   <?php submit_button(); ?>
  </form>

  <?php if(appy_config_set() == true): ?>
    <form method="post">
      <table class="form-table">
        <tbody>
          <tr>
            <th>Last refresh at : </th>
            <td><?php echo(appy_get_last_refresh()); ?></td>
          </tr>
        </tbody>
      </table>
      <input type="hidden"  name="refresh" id="refresh" value="true" />
      <input type='submit' value='Refresh Now' class="button button-primary"/>
    </form>
  <?php endif; ?>
</div>
