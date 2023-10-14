<?php
/**
 * Plugin Name: Slideshow
 * Plugin URI: 
 * Description: This is a slideshow plugin
 * Version: 1.0
 * Requires at least: 5.0
 * Requires PHP: 5.6.20
 * Author: Shailesh Gajare
 * Author URI: 
 * License: MT
 * License URI:
 * Text Domain: slideshow
 * Domain Path: /languages
 */

// If this file is called directly then it will abort execution
if(!defined('WPINC')){ die; }

// Define Sidekick Quote plugin directory path
define('SLIDESHOW_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

// Define Sidekick Quote plugin directory url
define('SLIDESHOW_PLUGIN_DRI_URL', plugin_dir_url(__FILE__));

// Define Placeholder Image URL for admin
define('SLIDESHOW_PLUGIN_PLACEHOLDER_IMAGE_URL', SLIDESHOW_PLUGIN_DRI_URL . 'admin/images/placeholder.png');

/**
 * function for check WordPsess Version is 6.3.1 & above.
 * @author Shailesh Gajare
 * @param function name
 * @return Error Message at new page and back to wordpress plugin page
 */
register_activation_hook( __FILE__ , 'slideshow_plugin_activate' );

function slideshow_plugin_activate(){
    // Check WordPsess Version is 5.0 or above or not
    function is_version($operator = '>', $version = '5.0'){
        global $wp_version;
        return version_compare( $wp_version, $version, $operator );
    }
    
    if(is_version() != 1){
        // Custom Error message in WordPress admin style
        wp_die('Sorry, but this plugin requires WordPress Version 5.0 above to be installed and active. <br><a href="'.admin_url('plugins.php').'">&laquo; Return to Plugins List</a>');
    } else {
        // Call installation PHP file for create required tables into database.
        require('slideshow_install.php');
    }
}

/**
 * function for Remove Custom Tables & Remove other options values.
 * @author Shailesh Gajare
 * @return true OR false - plugin deactivation
 */
register_uninstall_hook( __FILE__ , 'slideshow_plugin_uninstall');
function slideshow_plugin_uninstall(){
    require('slideshow_uninstall.php');
}

/**
 * Enqueue CSS & JS into WordPress admin part
 * @author Shailesh Gajare
 */
add_action('admin_enqueue_scripts', 'slideshow_wp_admin_style_scripts_loader');
function slideshow_wp_admin_style_scripts_loader(){

    wp_enqueue_style('fonts_awesome_style', plugin_dir_url(__FILE__).'admin/inc/font-awesome-5.css');
    wp_enqueue_style('datatable_wp_admin_style', plugin_dir_url(__FILE__).'admin/inc/data-tables.min.css');
    wp_enqueue_script('datatable_wp_admin_script', plugin_dir_url(__FILE__).'admin/inc/data-tables.min.js', array('jquery'), '', true);   
    wp_enqueue_script('datatable_slideshow_admin_script',plugin_dir_url(__FILE__).'admin/inc/Sortable.min.js', array('jquery'), '', true);
     
    wp_enqueue_script('datatable_fghtrwp_admin_script',plugin_dir_url(__FILE__).'admin/inc/jquery-sortable.js', array('jquery'), '', true);
    wp_enqueue_script('datatable_slideshow_admin_script',"https://code.jquery.com/jquery-1.12.4.js", '', true);
    wp_enqueue_script('datatable_slideshow_admin_script',"https://code.jquery.com/ui/1.12.1/jquery-ui.js", array('jquery'), '', true);

    wp_enqueue_style('custom_wp_admin_style', plugin_dir_url(__FILE__).'admin/inc/slideshow-custom-style.css');
    wp_enqueue_script('custom_wp_admin_script', plugin_dir_url(__FILE__).'admin/inc/slideshow-custom-script.js', array('jquery'), '', true);
}

// Include file into admin part
include('admin/slideshow_admin_functions.php');

// Include file into front-end part
include('front/slideshow_shorcode.php');

/**
 * function for display errors while submitting the forms at admin panel
 * @author Shailesh Gajare 
 * @param 1) Error Array
 * @return HTML
 */
function show_errors($error_arr, $show = true) {
    $error = '<div class="notice notice-error is-dismissible">';
    foreach ($error_arr as $key => $value) {
        $error .= '<p> '.$value.' </p>';
    }
    $error .= '</div>';

    if($show == true) {
        echo $error;
    } else {
        return $error;
    }
}
	
?>