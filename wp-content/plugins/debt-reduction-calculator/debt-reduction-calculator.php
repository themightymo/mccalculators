<?php
/*
Plugin Name: Debt Reduction Calculator
Plugin URI: http://www.themightymo.com
Description: Styles for the Debt Reduction Calculator
Author: The Mighty Mo! Design Co.
Version: 1.0
Author URI: http://www.themightymo.com
*/




function drc_scripts() {
	wp_register_script('drc-js', plugins_url( 'files/mm.min.js', __FILE__ ) , '', '', true);
    wp_enqueue_script('drc-js');
}
  
add_action( 'wp_enqueue_scripts', 'drc_scripts' );


function show_drc_calculator( $atts ) {
  extract( shortcode_atts( array(
    'file' => plugins_url('files/index.html',__FILE__ )
  ), $atts ) );
	
	$drc_content = file_get_contents ($file);
  /*return @file_get_contents($file);*/
	return $drc_content;
}	
  
add_shortcode( 'show_drc_calc', 'show_drc_calculator' );