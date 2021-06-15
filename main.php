<?php
/**
 * Plugin Name: Product Add Ons
 * Description: This plugin adds a shortcode you could use to display the products in a list
 * Version: 1.0
 * Author: Kent Matsui
 * Author URI: https://github.com/RkentMatsui/
 */
function enqueue_add_on_scripts(){
    add_action( 'wp_enqueue_scripts', 'wc_product_add_on_enqueue' );
    function wc_product_add_on_enqueue() {
        $ajax_cart_en = 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' );
        wp_enqueue_script(
            'cart-addon', // name your script so that you can attach other scripts and de-register, etc.
            $template_stack[] =plugin_dir_url( __FILE__ ) . 'assets/js/main.js', // this is the location of your script file
            array('jquery') // this array lists the scripts upon which your script depends
        );
        if( 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' )){
            wp_enqueue_script(
                'cart-addon-ajax', // name your script so that you can attach other scripts and de-register, etc.
                $template_stack[] =plugin_dir_url( __FILE__ ) . 'assets/js/ajax-cart.js', // this is the location of your script file
                array('jquery') // this array lists the scripts upon which your script depends
            );
        }
        wp_localize_script( 'cart-addon', 'cartajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce('ajax-nonce') ) );
        wp_enqueue_style( 'wc_add_ons', plugin_dir_url(__FILE__). 'assets/css/main.css' );
    }
    
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    enqueue_add_on_scripts();
    require_once 'includes/shortcode.php';
}