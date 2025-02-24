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
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Check if WP Offload Media is active, if not, deactivate this plugin.
 */
function wp_offload_r2_check_dependencies()
{
    if (
        ! is_plugin_active('amazon-s3-and-cloudfront/amazon-s3-and-cloudfront.php') &&
        ! is_plugin_active('amazon-s3-and-cloudfront-pro/amazon-s3-and-cloudfront-pro.php')
    ) {
        // Deactivate this plugin if WP Offload Media is not active.
        deactivate_plugins(plugin_basename(__FILE__));

        // Display an admin notice to inform the user.
        add_action('admin_notices', function () {
            ?>
            <div class="notice notice-error">
                <p><?php _e('WP Offload Cloudflare R2 has been deactivated because WP Offload Media (free or pro) is not installed or active.', 'wp-offload-cloudflare-r2'); ?></p>
            </div>
            <?php
        });
    }
}
add_action('admin_init', 'wp_offload_r2_check_dependencies');

/**
 * Register Cloudflare R2 provider for WP Offload Media.
 */
function wp_offload_add_cloudflare_r2_provider($providers)
{
    if (!class_exists('DeliciousBrains\WP_Offload_Media\Providers\Storage\Cloudflare_Provider')) {
        require_once plugin_dir_path(__FILE__) . 'includes/cloudflare-provider.php';
    }

    // Add Cloudflare R2 to the list of storage providers.
    $providers[DeliciousBrains\WP_Offload_Media\Providers\Storage\Cloudflare_Provider::get_provider_key_name()] = 'DeliciousBrains\WP_Offload_Media\Providers\Storage\Cloudflare_Provider';

    return $providers;
}
add_filter('as3cf_storage_provider_classes', 'wp_offload_add_cloudflare_r2_provider');

/**
 * Create a symbolic link for the R2 icon.
 */
function wp_offload_create_symbolic_link_for_r2_icon()
{
    $target_file = plugin_dir_path(__FILE__) . 'assets/r2.svg';
    $offload_plugin_dir = WP_PLUGIN_DIR . '/amazon-s3-and-cloudfront-pro/assets/img/icon/provider/storage/';
    $link_location = $offload_plugin_dir . 'r2.svg';

    if (! file_exists($link_location)) {
        symlink($target_file, $link_location);
    }
}
add_action('init', 'wp_offload_create_symbolic_link_for_r2_icon');

/**
 * Modify gzip MIME types to exclude SVG from being gzipped.
 */
function wp_offload_modify_gzip_mime_types($mime_types)
{
    unset($mime_types['svg']);

    return $mime_types;
}
add_filter('as3cf_gzip_mime_types', 'wp_offload_modify_gzip_mime_types', 10);


/**
 * This filter allows your limit specific mime types of files that
 * can be uploaded to the bucket. They will still be uploaded to the
 * WordPress media library but ignored from the offload process.
 *
 * @handles `as3cf_allowed_mime_types`
 *
 * @param array $types
 *
 * @return array
 */
function wp_offload_allowed_mime_types( $types ) {
    $types['svg'] = 'image/svg+xml';

    return $types;
}
add_filter( 'as3cf_allowed_mime_types', 'wp_offload_allowed_mime_types', 10, 1 );
