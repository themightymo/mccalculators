<?php
/*
Plugin Name: Magic Day Calculator
Plugin URI: http://www.themightymo.com
Description: Styles for the Magic Day Calculator
Author: The Mighty Mo! Design Co.
Version: 1.0
Author URI: http://www.themightymo.com
*/




function mdc_scripts() {
    wp_register_style('mdc_styles', plugins_url( 'css/mdc.css', __FILE__ ),'',time());
    wp_enqueue_style('mdc_styles');
}
  
add_action( 'wp_enqueue_scripts', 'mdc_scripts' );