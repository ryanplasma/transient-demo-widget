<?php 
/*
Plugin Name: Transient Demo Widget
Plugin URI: https://wordplas.com
Description: Show how transients are used by creating a widget to display github repo info in the sidebar.
Version: 0.0.1
Author: Ryan Plas
Author URI: https://wordplas.com
*/

add_action( 'widgets_init', function(){
  register_widget( 'Transient_Demo_Widget' );
});

class Transient_Demo_Widget extends WP_Widget {

  /**
   * Sets up the widgets name etc
   */
  public function __construct() {
    $widget_ops = array( 
      'classname' => 'transient_demo_widget',
      'description' => 'Transient Demo Widget',
    );
    parent::__construct( 'transient_demo_widget', 'Transient Demo Widget', $widget_ops );
  }

  /**
   * Outputs the content of the widget
   *
   * @param array $args
   * @param array $instance
   */
  public function widget( $args, $instance ) {
    $output = '';
    $output .= '<h1>Github Repos</h1>';
    $output .= '<ul>';
    $output .= '<li><a href=#>Repo 1</a></li>';
    $output .= '<li><a href=#>Repo 2</a></li>';
    $output .= '<li><a href=#>Repo 3</a></li>';
    $output .= '</ul>';

    echo $output;
  }
}
