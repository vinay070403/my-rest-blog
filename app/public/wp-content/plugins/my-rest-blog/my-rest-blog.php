<?php
/*
Plugin Name: My REST Blog
Plugin URI: https://github.com/vinaychavada/my-rest-blog
Description: A WordPress plugin with custom REST API endpoints for blog posts.
Version: 1.0
Author: Vinay Chavada
License: GPL2
*/

// Security check
if (!defined('ABSPATH')) exit;

// Include API file
require_once plugin_dir_path(__FILE__) . 'includes/api.php';
