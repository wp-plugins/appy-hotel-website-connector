<?php

function set_permalink_structure() {
  global $wp_rewrite;
  $wp_rewrite->set_permalink_structure('/%category%/%postname%/');
}

function appy_create_menus() {
  appy_delete_previous();

  $langs_array = appy_get_languages() ;
  foreach ( $langs_array as $lang_obj ) {
    $lang = $lang_obj -> code ;

    $wp_appy_menu_name = 'appy-menus-nav' . '-'.$lang ;

    $menu_object = wp_get_nav_menu_object( $wp_appy_menu_name );

    if( $menu_object )
      wp_delete_nav_menu($wp_appy_menu_name);

    $wp_menu_id = wp_create_nav_menu($wp_appy_menu_name);

    appy_create_home($wp_menu_id, $lang);

    $appy_menus = appy_get_menus($lang);
    for ($i=0; $i < sizeof($appy_menus); $i++) { 
      appy_create_menu($wp_menu_id, $i + 1, $appy_menus[$i]);
    }

    appy_create_other_pages(sizeof($appy_menus), $wp_menu_id, $lang) ;

    $locations = get_theme_mod('nav_menu_locations');
    $key = 'appy-primary' . '-' . $lang ;
    $locations[$key] = $wp_menu_id;
    set_theme_mod('nav_menu_locations', $locations);
  }

}

function appy_delete_previous() {
  global $wpdb;
  $del_pages_meta = $wpdb->query("DELETE FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type LIKE 'page')") ;
  $del_menus_meta = $wpdb->query("DELETE FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type LIKE 'nav_menu_item')") ;
  $del_pages = $wpdb->delete( 'wp_posts', array( 'post_type' => 'page') );
  $del_menus = $wpdb->delete( 'wp_posts', array( 'post_type' => 'nav_menu_item') );
}

function appy_create_categories() {
  $parent_category_id = wp_create_category('blog');
  $supported_languages = array( 'en', 'fr', 'de', 'he', 'it', 'ru', 'sk', 'es', 'th' ); 
  foreach($supported_languages as $supported_language) {
    wp_create_category($supported_language, $parent_category_id);
  }
}

function appy_create_home($wp_menu_id, $lang) {
  $appy_home_page = appy_create_wp_page( 'Home', "[appy_home lang='".$lang."']", 0, -1);

  $nice_url = site_url() . '/' . $lang . '/' . 'home' . '/' ;
  
  $wp_appy_home_item_id = appy_create_wp_menu_item( $wp_menu_id, appy_translate('welcome', $lang), $appy_home_page->ID, 0, -1, $lang, $nice_url );
}

function appy_create_other_pages($pos, $wp_menu_id, $lang) {
  $gallery_page = appy_create_wp_page( 'Gallery', "[appy_gallery lang='".$lang."']", 0, $pos++);
  $gallery_url = site_url() . '/' . $lang . '/' . 'gallery' . '/' ;
  appy_create_wp_menu_item( $wp_menu_id, appy_translate('gallery', $lang), $gallery_page->ID, 0, sizeof( wp_get_nav_menu_items($wp_menu_id)) + 1, $lang, $gallery_url );

  $feedback_page = appy_create_wp_page( 'Contact Us', "[appy_feedback lang='".$lang."']", 0, $pos++);
  $feedback_url = site_url() . '/' . $lang . '/' . 'contact' . '/' ;
  appy_create_wp_menu_item( $wp_menu_id, appy_translate('contact_us', $lang), $feedback_page->ID, 0, sizeof( wp_get_nav_menu_items($wp_menu_id)) + 1, $lang, $feedback_url );

  appy_create_wp_page( 'Contact', "[appy_contact lang='".$lang."']", 0, $pos++);

  //ABOUT US Section
  $appy_about_page = appy_create_wp_page( 'About', "[appy_about lang='".$lang."']", 0, $pos++);
  $about_url = site_url() . '/' . $lang . '/' . 'about' . '/' ;
  appy_create_wp_menu_item( $wp_menu_id, appy_translate('about', $lang), $appy_about_page->ID, 0, sizeof( wp_get_nav_menu_items($wp_menu_id)) + 1 , $lang, $about_url);

  if(appy_get_blog_enabled()) {
    $appy_blog_page = appy_create_wp_page( appy_translate('blog', $lang), "[appy_blog lang='".$lang."']", 0, $pos++);
    $blog_url = site_url() . '/' . $lang . '/' . 'blog' . '/' ;
    appy_create_wp_menu_item( $wp_menu_id, appy_translate('blog', $lang), $appy_blog_page->ID, 0, sizeof( wp_get_nav_menu_items($wp_menu_id)) + 1 , $lang, $blog_url);
  }

  if( appy_booking_enabled() ) {
    appy_create_wp_page( 'Checkout', "[appy_checkout lang='".$lang."']", 0, $pos++);
  }

  update_option( 'page_for_posts', $appy_blog_page->ID );
}

function appy_create_menu($wp_menu_id, $pos, $appy_menu) {
    $lang = $appy_menu->language_code ;
    $appy_menu_page = appy_create_wp_page( $appy_menu->title, "[appy_menu id='{$appy_menu->id}' lang='{$appy_menu->language_code}']", 0, $pos);
    if( !$appy_menu->featured ) {
      $nice_url = site_url() . '/' . $appy_menu->language_code . '/' . sanitize_title($appy_menu->title) . '/' ;
      $wp_appy_menu_item_id = appy_create_wp_menu_item( $wp_menu_id, $appy_menu->title, $appy_menu_page->ID, 0, $pos, $lang, $nice_url );
    }

    for ($j=0; $j < sizeof($appy_menu->pages); $j++) {
      $appy_page = $appy_menu->pages[$j];
      $appy_content_page = appy_create_wp_page( $appy_page->title, "[appy_page id='{$appy_page->id}' menu_id='{$appy_menu->id}' lang='{$appy_menu->language_code}']", $appy_menu_page->ID, $j + 1);

      $nice_url = site_url() . '/' . $appy_menu->language_code . '/' . sanitize_title($appy_menu->title) . '/' . sanitize_title($appy_page->title) . '/' ;

      if( !$appy_menu->featured ) {
        $wp_appy_content_item_id = appy_create_wp_menu_item( $wp_menu_id, $appy_page->title, $appy_content_page->ID, $wp_appy_menu_item_id, $j + 1, $appy_menu->language_code, $nice_url );
      } else { //featured page links
        $wp_postmeta = add_post_meta($appy_content_page->ID, '_menu_item_url', $nice_url) ;
      }
    }
}

function appy_create_wp_page( $title, $content, $parent, $pos ) {

    $wp_appy_page_id = wp_insert_post(array(
      'post_title'      => $title,
      'comment_status'  => 'closed',
      'ping_status'     => 'closed',
      'post_name'       => sanitize_title($title),
      'post_content'    => $content,
      'post_type'       => 'page',
      'post_parent'     => $parent,
      'post_status'     => 'publish',
      'post_author'     => 1,
      'menu_order'      => $pos
    ), true);

    //return get_page_by_title( $title );
    $res = new stdClass;
    $res->ID = $wp_appy_page_id ;
    return $res ;
}

function appy_create_wp_menu_item( $wp_menu_id, $title, $page_id, $parent, $pos, $culture, $url='TO_FIX' ) {
    return wp_update_nav_menu_item($wp_menu_id, 0, array(
        'menu-item-title'     => $title,
        'menu-item-object-id' => $page_id,
        'menu-item-parent-id' => $parent,
        'menu-item-position'  => $pos,
        'menu-item-url'       => $url,
        'menu-item-object'    => 'page',
        'menu-item-status'    => 'publish'
    ));
}
