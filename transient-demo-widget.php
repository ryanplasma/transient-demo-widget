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
    $time1 = microtime();

    if ( false === ($repos = get_transient( 'github_repos' ))) {
      $url = "https://api.github.com/users/ryanplasma/repos";
      $response = wp_remote_get( $url );
      $body = wp_remote_retrieve_body( $response );
      $repos = json_decode( $body );

      set_transient( 'github_repos', $repos, DAY_IN_SECONDS);
    }

    $time2 = microtime();

    $output = '';
    $output .= '<h1>Github Repos</h1>';
    $output .= '<ul>';

    foreach ($repos as $repo) {
      $output .= '<li>';
      $output .= '<a href=' . $repo->html_url . '>';
      $output .= $repo->name;
      $output .= '</a>';
      $output .= '</li>';
    }

    $output .= '</ul>';

    $benchmark = $time2 - $time1;
    $output .= '<p>Time: ' . $benchmark . ' seconds';

    echo $output;
  }
}
