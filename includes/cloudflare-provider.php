<?php

namespace DeliciousBrains\WP_Offload_Media\Providers\Storage;

use Exception;

class Cloudflare_Provider extends AWS_Provider {

    /**
     * @var string
     */
    protected static $provider_name = 'Cloudflare';

    /**
     * @var string
     */
    protected static $provider_short_name = 'Cloudflare R2';

    /**
     * Used in filters and settings.
     *
     * @var string
     */
    protected static $provider_key_name = 'r2';

    /**
     * @var string
     */
    protected static $service_name = 'R2';

    /**
     * @var string
     */
    protected static $service_short_name = 'r2';

    /**
     * Used in filters and settings.
     *
     * @var string
     */
    protected static $service_key_name = 'r2';

    /**
     * Optional override of "Provider Name" + "Service Name" for friendly name for service.
     *
     * @var string
     */
    protected static $provider_service_name = 'Cloudflare R2';

    /**
     * The slug for the service's quick start guide doc.
     *
     * @var string
     */
    protected static $provider_service_quick_start_slug = 'cloudflare-r2-quick-start-guide';

    /**
     * @var array
     */
    protected static $access_key_id_constants = array(
        'AS3CF_R2_ACCESS_KEY_ID',
    );

    /**
     * @var array
     */
    protected static $secret_access_key_constants = array(
        'AS3CF_R2_SECRET_ACCESS_KEY',
    );

    /**
     * @var bool
     */
    protected static $block_public_access_supported = false;

    /**
     * @var bool
     */
    protected static $object_ownership_supported = false;

    /**
     * @var array
     */
    protected static $regions = array(
        'wnam' => 'Western North America',
        'enam' => 'Eastern North America',
        'weur' => 'Western Europe',
        'eeur' => 'Eastern Europe',
        'apac' => 'Asia-Pacific',
    );

    /**
     * @var bool
     */
    protected static $region_required = false;

    /**
     * @var string
     */
    protected static $default_region = 'auto';

    /**
     * @var string
     */
    protected $default_domain = 'r2.cloudflarestorage.com';

    /**
     * @var string
     */
    protected $console_url = 'https://dash.cloudflare.com/';

    /**
     * @var string
     */
    protected $console_url_prefix_param = 'r2/buckets/';

    /**
     * Process the args before instantiating a new client for the provider's SDK.
     *
     * @param array $args
     *
     * @return array
     */
    protected function init_client_args( array $args ) {
        $args['endpoint'] = R2_ENDPOINT;
        return $args;
    }


    /**
     * Get the URL domain for the files.
     *
     * @param string $domain Likely prefixed with region
     * @param string $bucket
     * @param string $region
     * @param int    $expires
     * @param array  $args   Allows you to specify custom URL settings
     *
     * @return string
     */
    protected function url_domain( $domain, $bucket, $region = '', $expires = null, $args = array() ) {
        return R2_DOMAIN;
    }

    /**
     * Process the args before instantiating a new service-specific client.
     *
     * @param array $args
     *
     * @return array
     */
    protected function init_service_client_args( array $args ) {
        return $args;
    }

    /**
     * Update the block public access setting for the given bucket.
     *
     * @param string $bucket
     * @param bool   $block
     */
    public function block_public_access( string $bucket, bool $block ) {
        // Cloudflare R2 doesn't support this, so do nothing.
    }

    /**
     * Update the object ownership enforced setting for the given bucket.
     *
     * @param string $bucket
     * @param bool   $enforce
     */
    public function enforce_object_ownership( string $bucket, bool $enforce ) {
        // Cloudflare R2 doesn't support this, so do nothing.
    }

    /**
     * Create bucket.
     *
     * @param array $args
     *
     * @throws Exception
     */
    public function create_bucket( array $args ) {
        // Cloudflare R2 does not use regions in the same way as AWS, so handle accordingly.
        parent::create_bucket( $args );
    }

    /**
     * Get the region-specific prefix for raw URL.
     *
     * @param string   $region
     * @param null|int $expires
     *
     * @return string
     */
    protected function url_prefix( $region = '', $expires = null ) {
        return 'r2';
    }

    /**
     * Get the suffix param to append to the link to the provider's console.
     *
     * @param string $bucket
     * @param string $prefix
     * @param string $region
     *
     * @return string
     */
    protected function get_console_url_suffix_param( string $bucket = '', string $prefix = '', string $region = '' ): string {
        return $bucket;
    }

    /**
     * Title to be shown for provider's console link.
     *
     * @return string
     */
    public static function get_console_title(): string {
        return _x( 'Cloudflare R2 Console', 'Provider console link text', 'amazon-s3-and-cloudfront' );
    }
}