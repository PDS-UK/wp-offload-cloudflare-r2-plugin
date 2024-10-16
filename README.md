# WP Offload Cloudflare R2

## Description
This plugin adds support for Cloudflare R2 storage to WP Offload Media, allowing media files to be stored and served from Cloudflare's R2 storage service. 

### Features:
- Seamlessly integrates Cloudflare R2 as a storage provider in WP Offload Media.
- Automatically serves media from Cloudflare's R2 service with a configurable public domain for delivery.
- Symbolic link creation for custom Cloudflare R2 icons.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/wp-offload-cloudflare-r2-plugin` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Ensure the following configuration variables are set in your `wp-config.php` file:

```php
define('R2_DOMAIN', 'your-r2-url.r2.dev');
define('R2_ENDPOINT', 'https://your-r2-endpoint.r2.cloudflarestorage.com');
