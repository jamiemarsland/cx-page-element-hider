<?php
/*
Plugin Name: Canvas Hide Page Elements
Plugin URI: http://www.pragmatic-web.co.uk/
Description: A simple plugin to add a per-page control to Canvas that lets you hide page elements on certain pages
Version: 1.3
Author: Pragmatic
Author Email: d@pragmatic-web.co.uk
Author URI: http://pragmatic-web.co.uk
License:

  Copyright 2014 David Lockie (d@pragmatic-web.co.uk)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

require_once( 'class-pootlepress-updater.php');

// Hide footer using CSS
function hide_canvas_footer_css() { ?>
    <style type="text/css">
        #footer {
            display:none !important;
        }
    </style>
<?php }

// Hide page title using CSS
function hide_canvas_post_title_css() { ?>
    <style type="text/css">
        article header,
        .tribe-events-single-event-title,
        .product_title {
            display: none !important;
        }
    </style>
<?php }


function hide_nav_in_logo_inside_nav() {
    ?>
<style>
    #navigation .nav_section.first,
    #navigation .nav_section.third
    {
        display: none;
    }
</style>
<?php
}

// Add the front end action
function hide_canvas_nav_action() {
    global $post;
    $post_meta = get_post_meta( $post->ID );

    $hide_woo_top_navigation    = false;
    $hide_woo_logo              = false;
    $hide_woo_nav               = false;
    $hide_woo_header_widgetized = false;
    $hide_woo_breadcrumbs       = false;
    $hide_woo_post_title        = false;
    $hide_woo_footer_sidebars   = false;
    $hide_woo_footer            = false;

    if ( isset( $post_meta['_hide_woo_top_navigation'] ) ) { $hide_woo_top_navigation       = $post_meta['_hide_woo_top_navigation'][0]; }
    if ( isset( $post_meta['_hide_woo_logo'] ) ) { $hide_woo_logo                           = $post_meta['_hide_woo_logo'][0]; }
    if ( isset( $post_meta['_hide_woo_header_widgetized'] ) ) { $hide_woo_header_widgetized = $post_meta['_hide_woo_header_widgetized'][0]; }    
    if ( isset( $post_meta['_hide_woo_nav'] ) ) { $hide_woo_nav                             = $post_meta['_hide_woo_nav'][0]; }
    if ( isset( $post_meta['_hide_woo_breadcrumbs'] ) ) { $hide_woo_breadcrumbs             = $post_meta['_hide_woo_breadcrumbs'][0]; }
    if ( isset( $post_meta['_hide_woo_post_title'] ) ) { $hide_woo_post_title               = $post_meta['_hide_woo_post_title'][0]; }
    if ( isset( $post_meta['_hide_woo_footer_sidebars'] ) ) { $hide_woo_footer_sidebars     = $post_meta['_hide_woo_footer_sidebars'][0]; }
    if ( isset( $post_meta['_hide_woo_footer'] ) ) { $hide_woo_footer                       = $post_meta['_hide_woo_footer'][0]; }

    if ( $hide_woo_top_navigation == 'true' ) {
        remove_action( 'woo_top', 'woo_top_navigation', 10 );
    }
    
    if ( $hide_woo_logo == 'true' ) {
        remove_action( 'woo_header_inside', 'woo_logo', 10 );
    }

    if ( $hide_woo_header_widgetized == 'true' ) {
        remove_action( 'woo_header_inside', 'woo_header_widgetized' );
    }
    
    if ( $hide_woo_nav == 'true' ) {
        remove_action( 'woo_header_after', 'woo_nav', 10 );
        add_action('woo_head', 'hide_nav_in_logo_inside_nav');
    }

    if ( $hide_woo_breadcrumbs == 'true' ) {
        remove_action( 'woo_loop_before', 'woo_breadcrumbs', 10 );
    }

    if ( $hide_woo_post_title == 'true' ) {
        add_action( 'woo_head', 'hide_canvas_post_title_css' );
    }

    if ( $hide_woo_footer_sidebars == 'true' ) {
        remove_action( 'woo_footer_top', 'woo_footer_sidebars', 30 );
    }

    if ( $hide_woo_footer == 'true' ) {
        add_action( 'woo_head', 'hide_canvas_footer_css' );
    }

}
add_action( 'template_redirect', 'hide_canvas_nav_action' );

if( !function_exists( 'woo_metaboxes_add' ) ) {
    
    function woo_metaboxes_add( $woo_metaboxes ) {

        $woo_metaboxes[] = array(
            'name'    => '_hide_woo_top_navigation',
            'std'     => 'No',
            'label'   => 'Hide top nav?',
            'type'    => 'checkbox',
            'desc'    => '',
            'options' => array(
                '0' => 'No',
                '1' => 'Yes',
            )
        );

        $woo_metaboxes[] = array(
            'name'    => '_hide_woo_logo',
            'std'     => 'No',
            'label'   => 'Hide logo and strapline?',
            'type'    => 'checkbox',
            'desc'    => '',
            'options' => array(
                '0' => 'No',
                '1' => 'Yes',
            )
        );

        $woo_metaboxes[] = array(     
            'name'    => '_hide_woo_header_widgetized',
            'std'     => 'No',
            'label'   => 'Hide header widget area?',
            'type'    => 'checkbox',
            'desc'    => '',
            'options' => array(
                '0' => 'No',
                '1' => 'Yes',
            )
        );

        $woo_metaboxes[] = array(     
            'name'    => '_hide_woo_nav',
            'std'     => 'No',
            'label'   => 'Hide primary nav?',
            'type'    => 'checkbox',
            'desc'    => '',
            'options' => array(
                '0' => 'No',
                '1' => 'Yes',
            )
        );

        $woo_metaboxes[] = array(     
            'name'    => '_hide_woo_breadcrumbs',
            'std'     => 'No',
            'label'   => 'Hide breadcrumbs?',
            'type'    => 'checkbox',
            'desc'    => '',
            'options' => array(
                '0' => 'No',
                '1' => 'Yes',
            )
        );

        $woo_metaboxes[] = array(     
            'name'    => '_hide_woo_post_title',
            'std'     => 'No',
            'label'   => 'Hide page title?',
            'type'    => 'checkbox',
            'desc'    => '',
            'options' => array(
                '0' => 'No',
                '1' => 'Yes',
            )
        );

        $woo_metaboxes[] = array(     
            'name'    => '_hide_woo_footer_sidebars',
            'std'     => 'No',
            'label'   => 'Hide footer widget areas?',
            'type'    => 'checkbox',
            'desc'    => '',
            'options' => array(
                '0' => 'No',
                '1' => 'Yes',
            )
        );

        $woo_metaboxes[] = array(     
            'name'    => '_hide_woo_footer',
            'std'     => 'No',
            'label'   => 'Hide footer?',
            'type'    => 'checkbox',
            'desc'    => '',
            'options' => array(
                '0' => 'No',
                '1' => 'Yes',
            )
        );

        return $woo_metaboxes;
    }
    
}

add_action('init', 'pp_peh_updater');
function pp_peh_updater()
{
    if (!function_exists('get_plugin_data')) {
        include(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    $data = get_plugin_data(__FILE__);
    $wptuts_plugin_current_version = $data['Version'];
    $wptuts_plugin_remote_path = 'http://www.pootlepress.com/?updater=1';
    $wptuts_plugin_slug = plugin_basename(__FILE__);
    new Pootlepress_Updater ($wptuts_plugin_current_version, $wptuts_plugin_remote_path, $wptuts_plugin_slug);
}
