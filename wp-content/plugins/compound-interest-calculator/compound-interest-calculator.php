<?php
/*
Plugin Name: Compound Interest Calculator
Plugin URI: http://www.themightymo.com
Description: Creates a slider below a designated dropbox field
Author: The Mighty Mo! Design Co.
Version: 1
Author URI: http://www.themightymo.com
*/




function chart_adding_scripts() {
    //wp_register_script('script_slider', get_stylesheet_directory_uri() . '/slider/js/jquery-ui.js', array('jquery'));
    //wp_register_style('css_slider', get_stylesheet_directory_uri() . '/slider/css/jquery-ui.css');
    
    wp_register_script('script_chart', plugins_url( 'chart/Chart.bundle.js', __FILE__ ), array('jquery'),time());
    wp_register_style('css_chart', plugins_url( 'chart/Chart.css', __FILE__ ),'',time());
    wp_register_script('load_slider', plugins_url( 'js/load-slider-gf.js', __FILE__ ), array('jquery'),time());
        
    wp_enqueue_style('css_chart');
    wp_enqueue_script('script_chart');
    wp_enqueue_script('load_slider');
}
  
add_action( 'wp_enqueue_scripts', 'chart_adding_scripts' ); 


// Get rid of decimals via https://www.organicweb.com.au/19771/wordpress/gravity-forms-price-rounding/
add_filter( 'gform_currencies', 'chart_update_currency' );
function chart_update_currency( $currencies ) {
   if ( is_page(75) ) {
	$currencies['USD'] = array(
		'name' => __( 'U.S. Dollar', 'gravityforms' ),
		'symbol_left' => '$',
		'symbol_right' => '',
		'symbol_padding' => ' ',
		'thousand_separator' => ',',
		'decimal_separator' => '.',
		'decimals' => 0
	);
	 
	return $currencies;
  }else{
       $currencies['USD'] = array(
		'name' => __( 'U.S. Dollar', 'gravityforms' ),
		'symbol_left' => '$',
		'symbol_right' => '',
		'symbol_padding' => ' ',
		'thousand_separator' => ',',
		'decimal_separator' => '.',
		'decimals' => 2
	);
	 
	return $currencies;
       
   }
}