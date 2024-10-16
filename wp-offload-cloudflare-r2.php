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
 * Check if WP Offload Media is active, if not, deactivate this plugin.
 */
function wp_offload_r2_check_dependencies() {
    // Check if either WP Offload Media free or pro version is active.
    if (
        ! is_plugin_active( 'amazon-s3-and-cloudfront/amazon-s3-and-cloudfront.php' ) &&
        ! is_plugin_active( 'amazon-s3-and-cloudfront-pro/amazon-s3-and-cloudfront-pro.php' )
    ) {
        // Deactivate this plugin if neither version of WP Offload Media is active.
        deactivate_plugins( plugin_basename( __FILE__ ) );

        // Display an admin notice to inform the user
        add_action( 'admin_notices', function() {
            ?>
            <div class="notice notice-error">
                <p><?php _e( 'WP Offload Cloudflare R2 has been deactivated because WP Offload Media (either free or pro) is not installed or active.', 'wp-offload-cloudflare-r2' ); ?></p>
            </div>
            <?php
        });
    }
}
add_action('admin_init', 'wp_offload_r2_check_dependencies');

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

/**
 * Create a symbolic link for the R2 icon.
 */
function create_symbolic_link_for_r2_icon() {
    $target_file = plugin_dir_path(__FILE__) . 'assets/r2.svg';
    $offload_plugin_dir = WP_PLUGIN_DIR . '/' . plugin_dir_path( 'amazon-s3-and-cloudfront-pro/amazon-s3-and-cloudfront-pro.php' );
    $link_location = $offload_plugin_dir . 'assets/img/icon/provider/storage/r2.svg';
    if (!file_exists($link_location)) {
       symlink($target_file, $link_location);
    }
}
add_action('init', 'create_symbolic_link_for_r2_icon');