<?php
/*
Plugin Name: Reiseblog Widgets
Plugin URI: http://www.reisetiger.net/reiseblog-widgets-wordpress-plugin
Description: N&uuml;tzliche Widgets speziell f&uuml;r Reiseblogs: Reise-Countdown, Reise-Schn&auml;ppchen, Reise-Gutscheine... Die Widgets k&ouml;nnen beliebig in der Sidebar platziert werden. Per Shortcode kann man die Inhalte auch direkt in Seiten oder Beitr&auml;ge einf&uuml;gen.
Version: 1.4.9
Author: Reisetiger
Author URI: http://www.reisetiger.net
License: GPL2
*/

// Abbruch, wenn direkt aufgerufen
if ( ! defined( 'ABSPATH' ) )
	exit;

	define( 'REISEBLOGWIDGETS_VERSION', '0.1' );
	define( 'REISEBLOGWIDGETS_DIR', plugin_dir_path( __FILE__ ) );
	define( 'REISEBLOGWIDGETS_URL', plugin_dir_url( __FILE__ ) );

include( plugin_dir_path( __FILE__ ) . 'includes/widget-reisetiger.php');
include( plugin_dir_path( __FILE__ ) . 'includes/widget-reisecountdown.php');

function googlePlusAutor(){
$option_string = get_option('reiseblogwidgets');
$option = array();
$option = json_decode($option_string, true);
if (!is_singular()) {
echo '<link href="https://plus.google.com/'.stripslashes($option['gplusid']).'/" rel="publisher" />';
} else {
echo '<link href="https://plus.google.com/'.stripslashes($option['gplusid']).'?rel=author" rel="author" />';
}
}
add_action('wp_head', 'googlePlusAutor');
?>
<?php
add_action('admin_menu', 'reiseblogwidgets_menu');
function reiseblogwidgets_menu() {
	add_options_page('Reiseblog Widgets Einstellungen', 'Reiseblog Widgets', 'manage_options', 'reiseblogwidgets', 'reiseblogwidgets_options');
}

function reiseblogwidgets_options () {
	$option_name = 'reiseblogwidgets';
	if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	if( isset($_POST['gplusid'])) {
		$option = array();
		$option['gplusid'] = esc_html($_POST['gplusid']);
		update_option($option_name, json_encode($option));
		$outputa .= '<div class="updated"><p><strong>'.__('Einstellungen gespeichert.', 'menu' ).'</strong></p></div>';
	}
	$option = array();
	$option_string = get_option($option_name);
	if ($option_string===false) {
		$option = array();
		$option['gplusid'] = array('gplusid'=>true);
		$option_string = get_option($option_name);
	}
	$option = json_decode($option_string, true);
	$outputa .= '
	<div class="wrap">
		<h2>Reiseblog Widgets Einstellungen</h2>
    <h3>Widgets zur Sidebar hinzuf&uuml;gen</h3>
    <p>Prima, das Reiseblog Widgets Plugin l&auml;uft! <br>Gehe bitte zu <a href="'. get_admin_url( null, '/widgets.php' ) .'">Widgets</a> um ein Reiseblog-Widget zu deiner Sidebar hinzuzuf&uuml;gen oder dessen Einstellungen zu bearbeiten. </p>
    <p>Du kannst die Widgets beliebig in deinen Blog-Sidebars platzieren. Hier gibt es momentan nichts weiter zu tun.</p>
    <a href="'. get_admin_url( null, '/widgets.php' ) .'" class="button-primary" >Widgets verwalten</a>
	</div>';
	echo $outputa; 
}