<?php
/*
Plugin Name: W2 Calculator
Plugin URI: http://www.themightymo.com
Description: Styles for the Magic Day Calculator
Author: The Mighty Mo! Design Co.
Version: 1.0
Author URI: http://www.themightymo.com
*/




function w2_scripts() {
    wp_register_style('w2_styles', plugins_url( 'css/w2.css', __FILE__ ),'',time());
    wp_enqueue_style('w2_styles');
}
  
add_action( 'wp_enqueue_scripts', 'w2_scripts' );