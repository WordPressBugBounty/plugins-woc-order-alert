<?php

/*
	Plugin Name: Order Notification for WooCommerce
	Plugin URI: https://stackwc.com/plugins/woc-order-alert/
	Description: Play sound as notification instantly on new order in your WooCommerce store.
	Version: 3.6.3
	Author: StackWC
	Author URI: https://stackwc.com/
	Text Domain: woc-order-alert
	License: GPLv3 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
	WC requires at least: 7.2
    WC tested up to: 10.5
    Requires Plugins: woocommerce
*/
global $wpdb;
defined( 'ABSPATH' ) || exit;
defined( 'OLISTENER_PLUGIN_URL' ) || define( 'OLISTENER_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
defined( 'OLISTENER_PLUGIN_DIR' ) || define( 'OLISTENER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'OLISTENER_PLUGIN_FILE' ) || define( 'OLISTENER_PLUGIN_FILE', plugin_basename( __FILE__ ) );
defined( 'OLISTENER_PLUGIN_LINK' ) || define( 'OLISTENER_PLUGIN_LINK', 'https://stackwc.com/plugins/woc-order-alert/' );
defined( 'OLISTENER_TICKET_URL' ) || define( 'OLISTENER_TICKET_URL', 'https://stackwc.com/support/' );
defined( 'OLISTENER_DOCS_URL' ) || define( 'OLISTENER_DOCS_URL', 'https://stackwc.com/plugins/woc-order-alert/' );
defined( 'OLISTENER_CONTACT_URL' ) || define( 'OLISTENER_CONTACT_URL', 'https://stackwc.com/support/' );
defined( 'OLISTENER_REVIEW_URL' ) || define( 'OLISTENER_REVIEW_URL', 'https://wordpress.org/support/plugin/woc-order-alert/reviews/' );
defined( 'OLISTENER_DATA_TABLE' ) || define( 'OLISTENER_DATA_TABLE', $wpdb->prefix . 'woocommerce_order_listener' );
defined( 'OLISTENER_PLUGIN_VERSION' ) || define( 'OLISTENER_PLUGIN_VERSION', '3.6.3' );
if ( !function_exists( 'olistener_is_plugin_active' ) ) {
    function olistener_is_plugin_active(  $plugin  ) {
        return ( function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin ) : in_array( $plugin, apply_filters( 'active_plugins', (array) get_option( 'active_plugins', array() ) ) ) || is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) );
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
    }

}
if ( !olistener_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    return;
}
if ( !function_exists( 'olistener_declare_woocommerce_compatibility' ) ) {
    function olistener_declare_woocommerce_compatibility() {
        if ( class_exists( '\\Automattic\\WooCommerce\\Utilities\\FeaturesUtil' ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'analytics', __FILE__, true );
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'product_block_editor', __FILE__, true );
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'product_block_templates', __FILE__, true );
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'product_editor', __FILE__, true );
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'product_custom_fields', __FILE__, true );
        }
    }

}
add_action( 'before_woocommerce_init', 'olistener_declare_woocommerce_compatibility' );
if ( !function_exists( 'olistener_pro_cleanup' ) ) {
    function olistener_pro_cleanup() {
        wcoa_fs()->add_action( 'after_uninstall', 'wcoa_fs_uninstall_cleanup' );
    }

}
register_deactivation_hook( __FILE__, 'olistener_pro_cleanup' );
if ( !class_exists( 'Olistener_main' ) ) {
    /**
     * Class Olistener_main
     */
    class Olistener_main {
        protected static $_instance = null;

        protected static $_script_version = null;

        /**
         * Olistener_main constructor.
         */
        function __construct() {
            self::$_script_version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ? current_time( 'U' ) : OLISTENER_PLUGIN_VERSION );
            $this->loading_scripts();
            $this->loading_functions_classes();
        }

        /**
         * @return \Olistener_main
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Loading Functions and Classes
         */
        function loading_functions_classes() {
            require_once OLISTENER_PLUGIN_DIR . 'includes/class-hooks.php';
            require_once OLISTENER_PLUGIN_DIR . 'includes/class-functions.php';
            require_once OLISTENER_PLUGIN_DIR . 'includes/functions.php';
            require_once OLISTENER_PLUGIN_DIR . 'includes/class-plugin-settings.php';
        }

        /**
         * Admin Scripts
         */
        function admin_scripts() {
            wp_enqueue_script(
                // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
                'olistener-admin',
                plugins_url( '/assets/admin/js/scripts.js', __FILE__ ),
                array('jquery', 'jquery-migrate'),
                self::$_script_version
            );
            wp_localize_script( 'olistener-admin', 'olistener', array(
                'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
                'confirmText' => esc_html__( 'Are you really want to reset the notifier?', 'woc-order-alert' ),
                'interval'    => olistener()->get_interval(),
            ) );
            wp_enqueue_style( 'tool-tip', OLISTENER_PLUGIN_URL . 'assets/tool-tip.min.css' );
            // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
            wp_enqueue_style( 'olistener-admin', OLISTENER_PLUGIN_URL . 'assets/admin/css/style.css', self::$_script_version );
            // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
        }

        /**
         * Loading Scripts
         */
        function loading_scripts() {
            add_action( 'admin_enqueue_scripts', array($this, 'admin_scripts') );
        }

    }

}
if ( !function_exists( 'wpdk_init_olistener' ) ) {
    function wpdk_init_olistener() {
        if ( !function_exists( 'get_plugins' ) ) {
            include_once ABSPATH . '/wp-admin/includes/plugin.php';
        }
        if ( !class_exists( 'WPDK\\Client' ) ) {
            require_once plugin_dir_path( __FILE__ ) . 'includes/wp-dev-kit/classes/class-client.php';
        }
        global $olistener_wpdk;
        $olistener_wpdk = new WPDK\Client(
            esc_html( 'Order Notification for WooCommerce' ),
            'woc-order-alert',
            36,
            __FILE__
        );
    }

}
/**
 * @global \WPDK\Client $olistener_wpdk
 */
global $olistener_wpdk;
if ( !function_exists( 'wcoa_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wcoa_fs() {
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
        global $wcoa_fs;
        if ( !isset( $wcoa_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
            $wcoa_fs = fs_dynamic_init( array(
                'id'               => '18996',
                'slug'             => 'woc-order-alert',
                'premium_slug'     => 'woc-order-alert-pro',
                'type'             => 'plugin',
                'public_key'       => 'pk_b77a9468217d8ee52cb14f8aa7949',
                'is_premium'       => false,
                'premium_suffix'   => 'Pro',
                'has_addons'       => false,
                'has_paid_plans'   => true,
                'menu'             => array(
                    'first-path' => 'plugins.php',
                    'contact'    => false,
                    'support'    => false,
                ),
                'is_live'          => true,
                'is_org_compliant' => true,
            ) );
        }
        return $wcoa_fs;
    }

    // Init Freemius.
    wcoa_fs();
    // Signal that SDK was initiated.
    do_action( 'wcoa_fs_loaded' );
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
}
wpdk_init_olistener();
Olistener_main::instance();