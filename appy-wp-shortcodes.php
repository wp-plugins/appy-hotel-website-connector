<?php

function appy_load_template($template_name) {
  $common = "/templates/{$template_name}.php";

  ob_start();
  // Try to load templates from current theme
  $theme_template = get_template_directory() . $common;

  if(is_file($theme_template) == true) {
    load_template( $theme_template );
  } else {
    load_template( dirname( __FILE__ ) . $common );
  }
  return ob_get_clean();
}

function appy_home_content( $atts ) {
  $culture = get_query_var('category_name') ;
  $welcome = appy_get_welcome(appy_get_app(), $culture) ;
  set_query_var('app', appy_get_app());
  set_query_var('culture', $culture);
  set_query_var('welcome', $welcome) ;
  return appy_load_template("appy_home");
}
add_shortcode( 'appy_home', 'appy_home_content' );


function appy_menu_content( $atts ) {
  $culture = get_query_var('category_name') ;
  set_query_var('app', appy_get_app());
  set_query_var('menu', appy_get_menu(intval($atts['id']), $culture));
  set_query_var('culture', $culture);
  return appy_load_template("appy_menu");
}
add_shortcode( 'appy_menu', 'appy_menu_content' );


function appy_page_content( $atts ) {
  $culture = get_query_var('category_name') ;
  set_query_var('menu', appy_get_menu(intval($atts['menu_id']), $culture));
  set_query_var('ap', appy_get_page_object(intval($atts['id']), intval($atts['menu_id']), $culture));
  set_query_var('videos', appy_get_page_videos(appy_get_page_object(intval($atts['id']), intval($atts['menu_id']), $culture)));
  return appy_load_template("appy_page");
}
add_shortcode( 'appy_page', 'appy_page_content' );


function appy_survey_content() {
  set_query_var('app', appy_get_app());
  return appy_load_template("appy_survey");
}
add_shortcode( 'appy_survey', 'appy_survey_content' );


function appy_feedback_content() {
  set_query_var('app', appy_get_app());
  return appy_load_template("appy_feedback");
}
add_shortcode( 'appy_feedback', 'appy_feedback_content' );


function appy_contact_content() {
  set_query_var('app', appy_get_app());
  return appy_load_template("appy_contact");
}
add_shortcode( 'appy_contact', 'appy_contact_content' );

function appy_gallery_content( $atts ) {
  $culture = get_query_var('category_name') ;
  set_query_var('app', appy_get_app());
  set_query_var('culture', $culture) ;
  // set_query_var('images', appy_get_page_images($culture, '', 'ah_large'));
  return appy_load_template("appy_gallery");
}
add_shortcode( 'appy_gallery', 'appy_gallery_content' );

function appy_about_content( $atts ) {
  $culture = get_query_var('category_name') ;
  set_query_var('app', appy_get_app());
  set_query_var('culture', $culture) ;
  $about_obj = appy_get_about_us($culture) ;
  set_query_var('about_obj', $about_obj) ;
  return appy_load_template("appy_about");
}
add_shortcode( 'appy_about', 'appy_about_content' );

function appy_blog_content() {
  set_query_var('app', appy_get_app());
  return appy_load_template("appy_blog");
}
add_shortcode( 'appy_blog', 'appy_blog_content' );

function appy_checkout_content() {
  set_query_var('app', appy_get_app());
  return appy_load_template("appy_checkout");
}
add_shortcode( 'appy_checkout', 'appy_checkout_content' );
