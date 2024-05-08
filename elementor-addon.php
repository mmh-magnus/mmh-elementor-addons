<?php
/**
 * Plugin Name: MMH Elementor Addon
 * Description: MMH widgets for Elementor.
 * Version:     1.0.0
 * Author:      Magnús Már
 * Author URI:  https://mmh.is/
 * Text Domain: elementor-addon
 *
 * Requires Plugins: elementor
 * Elementor tested up to: 3.21.0
 * Elementor Pro tested up to: 3.21.0
 */

function register_mmh_elementor_widget( $widgets_manager ) {
    require_once( __DIR__ . '/widgets/woocommerce-out-of-stock.php' );
    require_once( __DIR__ . '/widgets/woocommerce-products-list.php' );
    require_once( __DIR__ . '/widgets/woocommerce-main-image.php' );
    require_once( __DIR__ . '/widgets/woocommerce-thumbnails.php' );

    // Register widgets
    $widgets_manager->register_widget_type( new \Elementor_Woocommerce_Out_Of_Stock() );
    $widgets_manager->register_widget_type( new \Elementor_Woocommerce_Products_List() );
    $widgets_manager->register_widget_type( new \Elementor_Woocommerce_Main_Image() );
    $widgets_manager->register_widget_type( new \Elementor_Woocommerce_Thumbnails() );
}


// Hook the widget registration function
add_action( 'elementor/widgets/widgets_registered', 'register_mmh_elementor_widget' );
