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
    // Add assets (js, css)
    add_action( 'wp_enqueue_scripts', array( $this, 'el_load_assets' ) );

    // Create Meta Boxes in Custom Post Type centric_documents
    add_action( 'add_meta_boxes', array( $this, 'el_custom_meta_boxes' ) );

    // Register Custom Boxes
    add_action( 'add_meta_boxes', array ( $this, 'el_custom_meta_boxes' ) );

    // Add shortcode
    add_shortcode( 'event-lense', array( $this, 'el_load_shortcode' ) );

    // Register a CPT
    add_action( 'init', array( $this, 'el_events_cpt' ) );

    // CPT Custom Columns
    add_filter( 'manage_centric_events_posts_columns', array( $this, 'el_events_columns' ) );

    // Save Meta Box Data
    add_action( 'save_post', array( $this, 'el_save_meta_box' ) );

    // Fetch Meta Data
    add_action( 'manage_centric_events_posts_custom_column', array( $this, 'el_custom_column_data' ), 10, 2 );

    // Register REST Route
    add_filter( 'rest_route_for_post', array( $this, 'el_rest_route_cpt' ), 10, 2 );
  }

  // Enqueue Scripts
  public function el_load_assets(){
    wp_enqueue_style( 'evelentlense-css', EL_PLUGIN_URL . 'css/eventlense.css', [], time(), 'all' );
    wp_enqueue_style( 'tailwind-css', EL_PLUGIN_URL . 'css/tailwind.min.css', [], '2.2.16', 'all' );
    wp_enqueue_script( 'eventlense-js', EL_PLUGIN_URL . 'js/eventlense.js', ['jquery'], time(), 1 );
  }
    // Create meta Boxes
    public function el_custom_meta_boxes(){
      add_meta_box( 'events_fields', __( 'Event Details','eventlense' ), array( $this, 'el_render_details' ), 'centric_events', 'advanced', 'high' );
    }

    // Render Meta-boxes html
    public function el_render_details( $post ){
      include( EL_LOCATION . '/inc/box_forms.php' );
    }

    // Render shortcode
    public function el_load_shortcode(){
      include( EL_LOCATION . '/inc/shortcodehtml.php' );
    }

    // Register a route
    public function el_rest_route_cpt( $route, $post ){
      if ( $post->post_type === 'centric_events' ) {
        $route = '/wp/v2/events/' . $post->ID;
      }
      return $route;
    }

    // Create a CPT
    public function el_events_cpt(){
      $labels = array(
        'name'        =>  _x( 'Events', 'Post type general name', 'eventlense' ),
        'singular'    =>  _x( 'Event', 'Post type singular', 'eventlense' ),
        'menu_name'   =>  _x( 'Events', 'Admin Menu Text', 'eventlense' ),
        'name_admin_bar'  =>  _x( 'Event', 'Add New on Toolbar', 'eventlense' ),
        'add_new'         =>  __( 'Add New', 'eventlense' ),
        'add_new_item'    =>  __( 'Add New Event', 'eventlense' ),
        'new_item'        =>  __( 'New Event' ),
        'edit_item'       =>  __( 'Edit Event', 'eventlense' ),
        'view_item'       =>  __( 'View Event', 'eventlense' ),
        'all_items'       =>  __( 'All Events', 'eventlense' ),
      );
      $args   = array(
        'labels'                =>  $labels,
        'public'                =>  true,
        'has_archive'           =>  'centric_events',
        'rewrite'               =>  array(
          'slug'                =>  'centric_events/%events%',
          'with_front'          =>  FALSE
        ),
        'hierarchical'          =>  false,
        'show_in_rest'          =>  true,
        'rest_base'             =>  'documents',
        'rest_controller_class' =>  'WP_REST_Posts_Controller',
        'supports'              =>  array( 'title', 'editor', 'thumbnail' ),
        'capability_type'       =>  'post',
        'menu_icon'             =>  'dashicons-calendar'
      );
      register_post_type( 'centric_events', $args );
    }

    // Custom Events CPT Columns
    public function el_events_columns( $columns ){
      $newColumns = array();
        $newColumns['title'] = 'Event Title';
        $newColumns['details'] = 'Event Description';
        $newColumns['venue']    = 'Event Venue';
        $newColumns['time']     = 'Event Time';
        $newColumns['date']     = 'Date';

        return $newColumns;
    }

    // Save data from meta boxes
    public function el_save_meta_box( $post_id ){
      if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
      if( $parent_id = wp_is_post_revision( $post_id ) ){
        $post_id = $parent_id;
      }
      $fields = [
        'event_time',
        'event_day',
        'event_venue',
        'event_link'
      ];
      foreach ( $fields as $field ) {
        if( array_key_exists( $field, $_POST ) ){
          update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
      }
    }

    // Fetch and populate events data
    public function el_custom_column_data( $column, $post_id ){
      switch ( $column ) {
        case 'details':
          echo get_the_excerpt();
          break;
        case 'venue':
          $venue = get_post_meta( get_the_ID(), 'event_venue', true );
          echo $venue;
          break;
        case 'time':
          $time = get_post_meta( get_the_ID(), 'event_time', true );
          echo $time;
          break;
        default:
          // code...
          break;
      }
    }
}
new EventLense;

?>
