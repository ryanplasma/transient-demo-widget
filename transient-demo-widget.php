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
    // Store the initial time in seconds
    $time1 = microtime();

    // If the 'github_repos' transient is not set, get the data from the API
    // and store it in a transient.  'get_transient()' returns false if the
    // transient is not set.
    if ( false === ($repos = get_transient( 'github_repos' ))) {
      // The API endpoint for getting my repo data
      $url = "https://api.github.com/users/ryanplasma/repos";
      // Use wp_remote_get to fetch the data from that endpoint
      $response = wp_remote_get( $url );
      // Get the body of the response from that endpoint
      $body = wp_remote_retrieve_body( $response );
      // Decode the body json into an array
      $repos = json_decode( $body );

      // Store the data in a transient for next time.  Expire the data
      // 1 day from when it is set so that it get re-fetched.
      set_transient( 'github_repos', $repos, DAY_IN_SECONDS);
    }

    // Store the end time in seconds
    $time2 = microtime();

    // Output the widget markup
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

    // Add the time it took to fetch the repo data to the bottom of the widget
    $benchmark = $time2 - $time1;
    $output .= '<p>Time: ' . $benchmark . ' seconds';

    echo $output;
  }
}
