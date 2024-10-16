<?php
/**
 * Plugin Name: WP Offload Cloudflare R2
 * Description: Adds Cloudflare R2 storage support to WP Offload Media.
 * Version: 1.0.0
 * Author: Cameron Stephen
 * Text Domain: wp-offload-cloudflare-r2
 * Plugin URI: https://printdatasolutions.co.uk
 * Update URI: https://printdatasolutions.co.uk
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Cloudflare R2 provider for WP Offload Media.
 */
add_filter( 'as3cf_storage_provider_classes', 'wp_offload_add_cloudflare_r2_provider' );

function wp_offload_add_cloudflare_r2_provider( $providers ) {
    // Include the Cloudflare R2 Provider class
    if ( ! class_exists( 'Cloudflare_Provider' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/cloudflare-provider.php';
    }

    // Add the Cloudflare R2 Provider to the list of available storage providers
    $providers[ DeliciousBrains\WP_Offload_Media\Providers\Storage\Cloudflare_Provider::get_provider_key_name() ] = 'DeliciousBrains\WP_Offload_Media\Providers\Storage\Cloudflare_Provider';

    return $providers;
}
function create_symbolic_link_for_r2_icon() {
    $target_file = plugin_dir_path(__FILE__) . 'assets/r2.svg';
    $offload_plugin_dir = WP_PLUGIN_DIR . '/' . plugin_dir_path( 'amazon-s3-and-cloudfront-pro/amazon-s3-and-cloudfront-pro.php' );
    $link_location = $offload_plugin_dir . 'assets/img/icon/provider/storage/r2.svg';
    if (!file_exists($link_location)) {
       symlink($target_file, $link_location);
    }
}
add_action('init', 'create_symbolic_link_for_r2_icon');