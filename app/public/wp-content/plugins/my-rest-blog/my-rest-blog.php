<?php
/*
Plugin Name: My REST Blog
Plugin URI: https://github.com/vinaychavada/my-rest-blog
Description: A WordPress plugin with custom REST API endpoints for blog posts and interactive API documentation.
Version: 1.1.0
Author: Vinay Chavada
Author URI: https://github.com/vinaychavada
License: GPL2
Text Domain: my-rest-blog
*/

// Security check
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MY_REST_BLOG_VERSION', '1.1.0');
define('MY_REST_BLOG_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MY_REST_BLOG_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class My_REST_Blog {
    /**
     * Initialize the plugin
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     */
    private function includes() {
        // Include API files
        require_once MY_REST_BLOG_PLUGIN_DIR . 'includes/api.php';
        
        // Include admin files
        if (is_admin()) {
            require_once MY_REST_BLOG_PLUGIN_DIR . 'includes/class-swagger-ui.php';
        }
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Activation hook
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Deactivation hook
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Add any activation code here
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Add any deactivation code here
        flush_rewrite_rules();
    }
}

// Initialize the plugin
function my_rest_blog_init() {
    static $instance = null;

    if (is_null($instance)) {
        $instance = new My_REST_Blog();
    }

    return $instance;
}

// Start the plugin
my_rest_blog_init();
