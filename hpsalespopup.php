<?php
/*
Plugin Name: WHMCS Live Sales Notification(Recent Sales Popup) WP Plugin
Plugin URI: https://www.hostpinnacle.com/whmcs-sales-popup
Description: This plugin displays popups for recent sales pulled from WHMCS store to display on your WordPress site. This popups could be real sales or dummy(fake) sales.
Author: HostPinnacle
Author URI: https://www.hostpinnacle.com
Version: 1.0
*/

function hpsalespop_install() {
    if(get_option( 'hpsalespop_url' ) == "false"){
   	add_option( 'hpsalespop_url', 'input your WHMCS System URL here', '', 'yes' );
    }
 
}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__,'hpsalespop_install');

//add settings link on plugins page
function hpsales_settings_link( $links ) {
    $links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/admin.php?page=hpsalespopup' ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'
	), $links );
	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'hpsales_settings_link' );

//Register a custom menu page.
function hpsalespop_menu_page(){
    add_menu_page( 
        __( 'WHMCS Recent Sales pop-up', 'textdomain' ),
        'WHMCS Recent Sales pop-up',
        'manage_options',
        'hpsalespopup',
        'hpsalespopup_page',
        '',
        6
    ); 
}
add_action( 'admin_menu', 'hpsalespop_menu_page' );
 
 //enque scripts
 function hpsales_assets() {
     $whmcs_raw_url = esc_url(get_option( 'hpsalespop_url' ));
    
    if($whmcs_raw_url !=="input your WHMCS System URL here"){
	wp_register_style( 'hpsales_css', $whmcs_raw_url.'/modules/addons/hpsalespopup/display.css');
	wp_register_script( 'hpsales_js', $whmcs_raw_url.'/modules/addons/hpsalespopup/display.js', array( 'jquery' ), false, true );
	wp_enqueue_style( 'hpsales_css' );
	wp_enqueue_script( 'hpsales_js' );
    }
    
}

add_action( 'wp_enqueue_scripts', 'hpsales_assets' );
/**
 * Display plugin settings page
 */
function hpsalespopup_page(){
    $success_save ='';
    if(isset($_POST['hpsalespop_url'])){
        $whmcs_systemurl_value = sanitize_text_field($_POST['hpsalespop_url']);
        $whmcs_systemurl_value = esc_url($whmcs_systemurl_value);
        update_option( 'hpsalespop_url', $whmcs_systemurl_value );
        $success_save = "<div style='color:green;'>Settings Saved Successfully.</div>";
    }
    echo "<div style='margin-top:5%;'>";
        echo '<h1>WHMCS Recent Sales pop-up settings</h1>';
        echo $success_save;
        $placeholder = esc_url(get_option( 'hpsalespop_url' ));
        echo '
        <form method="post" action="">
           WHMCS System URL: <input type="text" name="hpsalespop_url" style="width:25%;" value="'.$placeholder.'">
            <button type="submit">Save</button>
        </form>';
        $whmcs_systemurl_value = 'many';
    echo "</div>";
}