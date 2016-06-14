<?php
/*
Plugin Name: SP Gold Calculator
Plugin URI: http://dlgresults.com/#
Description: A gold calculator for wordpress
Author: Anthony Brown
Version: 1.0.9
Author URI: http://dlgresults.com
*/
global $dlg_gold_calculator;
$dlg_gold_calculator = "1.0.9";

add_action('admin_menu', 'dlg_gold_calculator_menu');


include_once 'functions.php';
include_once 'shortcode.php';

define('SP_GOLD_XML_URL',"http://changetoyoururl.com/xml.php");

//js files



function dlg_gold_calculator_init() {
	if (!is_admin()) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('goldCalc', '' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-gold-calculator/js/gold_calc.php'); 
		
		
	}
}
//css files
function dlg_gold_calculator_load_css(){
	if (!is_admin()) {
echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-gold-calculator/style.css" />';
	}
	
}
add_action('wp_head', 'dlg_gold_calculator_load_css');	



add_action('init', 'dlg_gold_calculator_init');


function dlg_gold_calculator_install() {
   global $wpdb;
   global $dlg_gold_calculator ;

   $table_name = $wpdb->prefix . "dlg_portfolio";
      
 $sql = '';

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
 if ( get_option( 'dlg_gold_calculator_version') == '') {
      update_option("dlg_gold_calculator_currency",'USD');
	 update_option("dlg_gold_calculator_money_position",'Before');
	  update_option("dlg_gold_calculator_money_symbol",'$');
	 update_option("dlg_gold_calculator_rate",85);
	  update_option("dlg_gold_calculator_version",$dlg_gold_calculator );
 }
}
register_activation_hook(__FILE__,'dlg_gold_calculator_install');




function dlg_gold_calculator_menu() {
	add_menu_page('DLG Gold Calculator', 'DLG Gold Calculator', 'manage_options', 'gold-calculator', 'dlg_gold_calculator_options');

}
function dlg_gold_calculator_add() {
	//echo addPortfolioForm();
}
function dlg_gold_calculator_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	
	if($_POST['submit-gc-options'] != ""){
		 update_option("dlg_gold_calculator_currency",$_POST['dlg_gold_calculator_currency']);
	 update_option("dlg_gold_calculator_money_position",$_POST['dlg_gold_calculator_money_position']);
	  update_option("dlg_gold_calculator_money_symbol",$_POST['dlg_gold_calculator_money_symbol']);
	 update_option("dlg_gold_calculator_rate", $_POST['rate']);
	 echo '<div style="color:green;font-weight:bold;padding:10px;font-size:16px;">Updated Options!</div>';
	}
	
	echo dlg_gold_admin_page();
	
}

?>