<?php

/**
 * Contains all the logic to communicate with Appy's API.
 * The only public function is 'get_app' which download the last hotel's json
 * from the API and store it in the database.
 *
 * Example :
 * $c = new AppyApiConnector();
 * $result = $c->get_app();
 *
 * $result will contain an error message if something went wrong,
 * or will be null if everything went fine.
 *
 * @since appy_connector (1.0.0)
 */
class AppyApiConnector
{
  /** @var object */
  private $result;

  /** @var array */
  private $errors;

  /**
   * Class constructor. Initialize the $error property with a list of error messages.
   *
   * @since appy_connector (1.0.0)
   */
  public function __construct() {
    $this->errors = array(
      400 => "It seems that your API Key is not valid ! Contact support@appyhotel.com for more information.",
      401 => "It seems that your API Key is not valid ! Contact support@appyhotel.com for more information.",
      404 => "We could not find an app with the appy id " . appy_id() . ".",
      500 => "Oops. Something broke in the API. We have been notified about the issue and will take a look asap !",
      503 => "It's seems that our server is offline.");
  }


  /**
   * Download the last hotel's data and call the 'save' method if the API sent back 200.
   *
   * @since appy_connector (1.0.0)
   *
   * @return string The error if something went wrong, null if the data was successfuly updated.
   */
  public function get_app() {
    $path = '/' . APPY_API_TENANT . '/' . appy_id();
    $this->get_appy_api($path);
    $status = $this->result->status->code;
    if( empty($status)) {
      $status = $this->http_code;
    }

    // Log the request result.
    error_log('Call to ' . $this->appy_api_url($path) . ' returned http status ' . $status . '.');

    // Log a warning if the API is, or will be, deprecated.
    $this->check_deprecated();

    if($status == '200')
      $this->appy_save();

    return $this->errors[$status];
  }

  /**
   * Save the downloaded data in the database, replacing the existing values.
   *
   * @since appy_connector (1.0.0)
   *
   */
  private function appy_save() {
    global $wpdb;
    $table_name = $wpdb->prefix . APPY_TABLE_NAME;
    $content = json_encode($this->result->data);
    $wpdb->replace( $table_name, array( 'appy_id' => appy_id(), 'time' => current_time('mysql'), 'content' => $content, 'binary_content' => serialize(json_decode($content) )) );
  }

  /**
   * Check if the API is deprecated and log a warning if that's the case.
   *
   * @since appy_connector (1.0.0)
   *
   */
  private function check_deprecated()
  {
    if($this->api_status()->deprecated == 'true')
    {
      error_log('This version of Appy API has been deprecated on ' . $this->api_status()->deprecation_date . '.');
      error_log($this->api_status()->message);
    }
  }

  /**
   * Get the API Status from the json.
   *
   * @since appy_connector (1.0.0)
   *
   * @return object An object with the fields 'deprecated', 'deprecation_date' and 'message'.
   */
  private function api_status()
  {
    return $this->result->api_status;
  }

  /**
   * Format the url by adding appending the url, path and the api key.
   * @param string $path, the path to append to the url. Ex: /hotels/x
   *
   * @since appy_connector (1.0.0)
   *
   * @return string The url for the request.
   */
  private function appy_api_url($path)
  {
    //return appy_get_api_url() . $path . '?key=' . appy_get_key();
    return appy_get_api_url() . $path . '?key=' . appy_get_key() . '&web_client_domain=' . $_SERVER['HTTP_HOST'];
  }

  /**
   * Make the call to the API with cURL and store the result in $result.
   * @param string $path, the path to append to the url. Ex: /hotels/x
   *
   * @since appy_connector (1.0.0)
   */
  private function get_appy_api($path)
  {
    $ch = curl_init( $this->appy_api_url($path)  );

    $options = array(
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => array('Content-type: application/json'),
      CURLOPT_HEADER => false,
      CURLOPT_ENCODING => "gzip"
    );

    curl_setopt_array( $ch, $options );
    $this->result = json_decode(curl_exec($ch));
    $this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  }

}
