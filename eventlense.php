<?php
/**
 * Event-Lense
 *
 * @package     Event-Lense
 * @author      Centric Data
 * @copyright   2021 Centric Data
 * @license     GPL-2.0-or-later
 *
*/
/*
Plugin Name:        Event-Lense
Plugin URI:         https://github.com/Centric-Data/eventlense
Description:        This is to list all updated events for ZLC on any page using the shortcode
Version:            1.0
Requires at least:  5.2
Author:             Centric Data
Author URI:         https://github.com/Centric-Data
Text Domain:        eventlense
*/
/*
Event-Lense is free software: you can redistribute it and/or modify it under the terms of GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.

Event-Lense is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Contact-Lense Form.
*/
/* Exit if directly accessed */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define variable for path to this plugin file.
define( 'EL_LOCATION', dirname( __FILE__ ) );
define( 'EL_LOCATION_URL' , plugins_url( '', __FILE__ ) );
define( 'EL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 *
 */
class EventLense
{
  // Add hooks
  public function __construct() {
    // Create Meta Boxes in Custom Post Type centric_documents
    add_action( 'add_meta_boxes', array( $this, 'el_custom_meta_boxes' ) );

    // Register Custom Boxes
    add_action( 'add_meta_boxes', array ( $this, 'el_custom_meta_boxes' ) );
  }

  // Enqueue Scripts
  public function el_load_assets(){
    wp_enqueue_style( 'evelentlense-css', EL_PLUGIN_URL . 'css/eventlense.css', [], time(), 'all' );
    wp_enqueue_script( 'eventlense-js', EL_PLUGIN_URL . 'js/eventlense.js', ['jquery'], time(), 1 );
  }
    // Create meta Boxes
    public function el_custom_meta_boxes(){
      add_meta_box( 'events_fields', __( 'Event Details','eventlense' ), array( $this, 'el_render_details' ), 'post', 'advanced', 'high' );
    }

    // Render Meta-boxes html
    public function el_render_details( $post ){
      echo "Events Details";
    }

    // Render shortcode

    // Register a route

    // Create a CPT
}
new EventLense;

?>
