<?php

/**
 * Appy Connector Plugin API.
 *
 * Contains all the methods to access the hotel's data.
 */


$app = null;
/**
 * Retrieve the hotel's data from the database.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object The decoded json of the hotel's data.
 */
function appy_get_app() {

  global $wpdb;

  $table_name = $wpdb->prefix . APPY_TABLE_NAME;
  $appy_id = appy_id();
  $app_row = $wpdb->get_row( "SELECT binary_content FROM $table_name WHERE appy_id = $appy_id" );
  $app = unserialize($app_row->binary_content);
  return $app;
}

function appy_get_app_details($app, $lang='en', $prop=null) {
  $app = appy_get_app();
  if($prop=="contact_email") {
    $res = $app->details->en->$prop ;
  } else if($prop=="phone") {
    $res = $app->details->en->$prop ;
  } else {
    $res = $app->details->$lang->$prop ;
  }
  return $res;
  /*return (isset($app->details->$lang->$prop)) ? $app->details->$lang->$prop : $app->details->en->$prop ;*/
}

function appy_get_location($app = null) {
  $app = appy_get_app();
  return $app->geo->city . ', ' . $app->geo->country;
}

function appy_get_address_lines($app = null, $lang = 'en') {
  $app = appy_get_app();
  $address_lines = '' ;
  $lines = array( 'address_line_one', 'address_line_two', 'address_line_three' ) ;
  foreach ($lines as $k => $line) {
    $address_lines .= $app->details->$lang->$line . '<br/>' ;
  }
  $out = substr($address_lines, 0, -5) ;
  return $out ;
}

function appy_get_address_lines_array($app = null, $lang = 'en') {
  $app = appy_get_app();
  return array($app->details->$lang->address_line_one, $app->details->$lang->address_line_two, $app->details->$lang->address_line_three);
}


function appy_get_address_line_one($app = null, $lang = null) {
  $app = appy_get_app();
  return appy_get_app_details($app, $lang, 'address_line_one') ;
}

function appy_get_address_line_two($app = null, $lang = null) {
  $app = appy_get_app();
  return appy_get_app_details($app, $lang, 'address_line_two') ;
}

function appy_get_address_line_three($app = null, $lang = null) {
  $app = appy_get_app();
  return appy_get_app_details($app, $lang, 'address_line_three') ;
}

function appy_get_phone($app = null, $lang = null) {
  $app = appy_get_app();
  return appy_get_app_details($app, $lang, 'phone') ;
}

function appy_get_phone_booking_url($app = null, $lang = null) {
  $app = appy_get_app();
  return appy_get_app_details($app, $lang, array('booking_url', 'phone')) ;
}

function appy_get_tablet_booking_url($app = null, $lang = 'en') {
  $app = appy_get_app();
  return (($app->details->$lang->booking_url->tablet)?$app->details->$lang->booking_url->tablet:$app->details->en->booking_url->tablet) ;
}

function appy_get_phone_action_url($page) {
  return $page->action_url->phone;
}

function appy_get_tablet_action_url($page) {
  return $page->action_url->phone;
}

function appy_page_with_action_url($page) {
  return appy_get_phone_action_url($page) || appy_get_tablet_action_url($page);
}

function appy_get_email() {
  $lang = get_query_var('category_name');
  $app = appy_get_app();
  $res = appy_get_app_details($app, $lang, 'contact_email') ;
  return $res;
}

function appy_get_app_store_link() {
  return "https://itunes.apple.com/th/app/appyhotel/id529390457?mt=8";
}

function appy_get_play_store_link() {
  return "https://play.google.com/store/apps/details?id=com.appyhotel.appyhotel";
}

function appy_get_twitter($language_code = 'en') {
  $p = appy_get_app()->details->$language_code->twitter_handle ;
  if($p != null) {
    return "//twitter.com/" . $p ;
  } else {
    return null;
  }
}

function appy_get_facebook($language_code = 'en') {
  $p = appy_get_app()->details->$language_code->facebook_page_url ;
  if($p != null) {
    return "//facebook.com/" . $p ;
  } else {
    return null;
  }
}

function appy_get_appyhotel() {
  return "http://www.appyhotel.com";
}

function appy_get_currency_symbol($app = null) {
  if($app == null) {
    $app = appy_get_app();
  }
  if( $app->currency_symbol ) {
    return $app->currency_symbol;
  } else {
    return $app->currency;
  }
}

/**
 * Retrieve the time of the last update.
 *
 * @since appy_connector (1.0.0)
 *
 * @return time Time of the last update.
 */
function appy_get_last_refresh() {
  global $wpdb;

  $table_name = $wpdb->prefix . APPY_TABLE_NAME;
  $appy_id = appy_id();
  $result = $wpdb->get_row( "SELECT time FROM $table_name WHERE appy_id = $appy_id" );
  return $result->time;
}

/**
 * Get the geo information about the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object Geo fields
 *   lat                    
 *   lng                    
 *   geocoder_lat           
 *   geocoder_lng           
 *   dst_offset             
 *   raw_offset
 *   timezone_id
 *   timezone_name
 *   city
 *   city_id
 *   admin
 *   admin_id
 *   country
 *   country_id
 *
 */
function appy_get_geo() {
  return appy_get_app()->geo;
}

/**
 * Get the theme information about the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object Theme fields          
 *   logo
 *   background_color
 *   text_color
 *   text_highlight_color
 *   background
 *   background_sff
 *   thumbnail
 *
 */
function appy_get_theme() {
  return appy_get_app()->theme;
}

/**
 * Get the plan information about the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object Theme fields          
 *   allow_search_by_name
 *   allow_directory
 *   allow_logo_badge
 *   allow_room_control
 *
 */
function appy_get_plan() {
  return appy_get_app()->plan;
}

/**
 * Get the languages information about the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return array Languages, each language has the following fields   
 *   id
 *   code
 *   name
 *   default
 *
 */
function appy_get_languages() {
  $l = appy_get_app()->languages ;
  if (!is_null($l)) {
    return $l;
  } else {
    return array('en') ;
  }
  
}

/**
 * Get the details of the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object Details 
 * language_code:
 * language
 * default_language
 * website
 * phone
 * fax
 * facebook_page_url
 * twitter_handle
 * address_line_one
 * address_line_two
 * address_line_three
 * welcome_line_one
 * welcome_line_two
 * welcome_line_three
 * siteminder_id
 * booking_url
 * menus
 * surveys
 * contacts
 *
 */
function appy_get_content($language_code = 'en') {
  //return appy_get_app()->details->$language_code;
}

/**
 * Get the booking url info of the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object booking_url 
 * phone
 * tablet
 *
 */
function appy_get_booking_url($language_code) {
  return appy_get_app()->details->$language_code->booking_url;
}

/**
 * Get the surveys info of the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object Details 
 * phone
 * tablet
 *
 */
function appy_get_surveys($language_code) {
  return appy_get_app()->details->$language_code->surveys;
}

/**
 * Get the contacts info of the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return array Contacts 
 * id
 * name
 * area
 * number
 * description
 *
 */
function appy_get_contacts($language_code) {
  return appy_get_app()->details->$language_code->contacts;
}

/**
 * Get the menu specified by $id.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object Menu
 *
 */

function appy_get_menu($id=0, $language_code = null) {
  foreach(appy_get_app()->details->$language_code->menus as $menu) {
    if($menu->id == $id) {
      $menu->numChildren = count($menu->pages) ;
      return $menu;
    }
  }
  return false ;
}

function appy_get_menus($language_code = null) {
  return appy_get_app()->details->$language_code->menus ;
}


function appy_get_menu_by_name($title=null, $language_code=null) {
  $res = appy_get_app()->details->$language_code->menus ;
  foreach($res as $menu) {
    if(strtolower($menu->title) == strtolower($title) && $menu->language_code==$language_code) {
      return $menu;
    }
  }
  return null;
}

/**
 * Get the featured menu.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object Menu
 */
function appy_get_featured_menu($language_code = 'en') {
  $app = appy_get_app() ;
  $res = $app->details->$language_code->menus ;
  if(!is_null($res)) {
    foreach($res as $menu) {
      if($menu->featured == true) {
        return $menu;
      }
    }
  }
}

function appy_get_images_on_home($language_code = null, $size = 'ah_large') {
  $app = appy_get_app() ;
  $images_arr = array() ;
  $images_arr[0] = $app->theme->background->$size ;
  $about_arr = $app->details->$language_code->about_us->pictures ;
  foreach ($about_arr as $k => $v) {
    $images_arr[] = $v->image->$size ;
  }
  return $images_arr ;
}

function appy_get_about_us($language_code=null) {
  $app = appy_get_app() ;
  $about_obj = $app->details->$language_code->about_us ;
  return $about_obj ;
}


function appy_get_images_on_page($page_kind=null, $ids=array(), $language_code = null, $size = 'ah_large') {
  $app = appy_get_app() ;
  $images_arr = array() ;
  $toFetch_arr = array() ;
  switch ($page_kind) {
    case 'home':
      $toFetch_arr = $app->details->$language_code->about_us->pictures ;
      break;
    
    default:
      foreach ($app->details->$language_code->menus as $menu) {
        if($menu->id == $ids["menu_id"]) {
          foreach ($menu->pages as $page) {
            if ($page->id == $ids["id"]) {
                  $toFetch_arr = $page->media;
            }
          }
        }
      }
      break;
  }
  if ( count($toFetch_arr)<1 ) {
    $images_arr[0] = $app->theme->background->$size ;
  } else {
    foreach ($toFetch_arr as $k => $v) {
      $imgObj = new stdClass ;
      $imgObj->th = $v->image->ah_small ;
      $imgObj->full = $v->image->$size ;
      $images_arr[] = $imgObj ;
    }
  }
  return $images_arr ;
}


/**
 * Get the pages of the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return array Pages 
 * id
 * language_code
 * language
 * default_language
 * title
 * description
 * content
 * cover_image
 * thumbnail
 * media
 * category
 * price
 * unit
 * siteminder
 * action_url
 * items  
 *
 */
function appy_get_pages($language_code=null, $menu_id=0) {
  $menus = appy_get_app()->details->$language_code->menus ;
  foreach ($menus as $k => $menu) {
    if ($menu->id == $menu_id) {
      return $menu->pages ;
    }
  }
  return 'ERROR' ;
}

/**
 * Get the page specified by $id.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object Page
 *
 */
function appy_get_page_object($id, $menu_id, $language_code = null) {
  $menus = appy_get_app()->details->$language_code->menus ;
  foreach ($menus as $k => $menu) {
    if ($menu->id == $menu_id) {
      foreach ($menu->pages as $kk => $page) {
        if($page->id == $id) {
          $page->prev_link = '../' . sanitize_title($menu->pages[$kk-1]->title) ;
          $page->next_link = '../' . sanitize_title($menu->pages[$kk+1]->title) ;
          $page->position = $kk+1 ;
          return $page ;
        }
      }
    }
  }
  return 'ERROR' ;
}

function appy_get_page_position($id, $menu_id, $language_code = null) {
  $pages = appy_get_app()->details->$language_code->menus->$menu_id->pages ;
  for($i = 0; $i < count($pages); $i++) {
    if($pages[$i]->id == $id) {
      return $i + 1;
    }
  }
  return null;
}

/**
 * Get the items of the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return array Items 
 * id
 * language_code
 * language
 * default_language
 * title
 * description
 * thumbnail
 * category
 * price
 * unit
 *
 */
function appy_get_items($language_code, $menu_id, $page_id) {
  $menus = appy_get_app()->details->$language_code->menus;
  foreach($menus as $menu) {
    if($menu->id == $menu_id && $menu->language_code == $language_code) {
      foreach($menu->pages as $page) {
        if($page->id == $page_id)
          return $page->items;
      }
    }
  }
}

/**
 * Get every page image.
 *
 * @since appy_connector (1.0.0)
 *
 * @return array Images
 * url
 * page_title
 * page_description
 *
 */
function appy_get_page_images($language_code = null, $page_id = null, $size="ah_large") {
  $app = appy_get_app() ; 
  $images = array();
  $menus = $app->details->$language_code->menus;
  foreach($menus as $menu) {
    foreach(appy_get_pages($menu->language_code, $menu->id) as $page) {
      if( empty($page_id) || $page->id == $page_id ) {
        foreach($page->media as $medium) {
          if($medium->type == "image") {
            $image = new stdClass();
            $image->full = $medium->image->$size;
            $image->thumb = $medium->image->ah_small;
            $image->page_title = $page->title;
            $image->page_description = $page->description;
            $images[] = $image;
          }
        }
      }
    }
  }
  return $images;
}

/**
 * Get images grouped by pages into the gallery
 *
 */
function appy_get_gallery_images($language_code = null, $size="ah_large") {
  $app = appy_get_app() ; 
  $sections = array();
  $menus = $app->details->$language_code->menus;
  foreach($menus as $menu) {
    $section = new stdClass() ;
    $section->title = $menu->title ;
    $images = array() ;
    foreach($menu->pages as $page) {
      foreach($page->media as $medium) {
        if($medium->type == "image") {
          $image = new stdClass();
          $image->full = $medium->image->$size;
          $image->thumb = $medium->image->ah_small;
          $images[] = $image;
        }
      }
      
    }
    $section->images = $images ;
    $sections[] = $section;
  }
  return $sections;
}

/**
 * Get one page videos.
 *
 * @since appy_connector (1.0.0)
 *
 * @return array Videos
 * id
 * type
 * video_id
 * host
 *
 */
function appy_get_page_videos($page, $language_code = null) {
  $videos = array();

  $services = array('youku' => 'http://player.youku.com/embed/', 'youtube' => 'http://www.youtube.com/embed/');

  foreach($page->media as $medium) {
    if($medium->video_id) {
      $medium->url = $services[$medium->host] . $medium->video_id;
      $videos[] = $medium;
    }
  }

  return $videos;
}

/**
 * Get the latitude and longitude information for the hotel.
 *
 * @since appy_connector (1.0.0)
 *
 * @return object latlng fields
 *   lat
 *   lng
 *
 */
function appy_get_latlng() {
  $latlng = new stdClass();

  if( appy_get_app()->geo->lat ) {
    $latlng->lat = appy_get_app()->geo->lat;
  } else {
    $latlng->lat = appy_get_app()->geo->geocoder_lat;
  }

  if( appy_get_app()->geo->lng ) {
    $latlng->lng = appy_get_app()->geo->lng;
  } else {
    $latlng->lng = appy_get_app()->geo->geocoder_lng;
  }

  return $latlng;
}

function appy_get_welcome($app = null, $lang = null) {
  $welcome_lines = array ('one', 'two', 'three') ;
  $welcome = '' ;
  $l = '' ;
  foreach ($welcome_lines as $line) {
    $l = 'welcome_line_' . $line ;
    $str = appy_get_app_details($app, $lang, $l) ;
    if (strlen($str)>2) {
      $welcome .= $str . '<br/>' ;
    }
  }
  $welcome = substr($welcome, 0, -5) ;
  return $welcome ;
}

function appy_translate ($word, $lang) {
  $dictionary = appy_load_translations();
  if ( !array_key_exists($lang, $dictionary) ) {
    $lang = 'en' ;
  }
  return $dictionary[$lang][$word] ;
}

function appy_get_time($app = null) {
  if($app == null) {
    $app = appy_get_app();
  }
  date_default_timezone_set("UTC");
  return date("H:i", time() + $app->geo->raw_offset);
}
