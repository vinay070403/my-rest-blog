<?php
/**
 * Swagger UI integration for WordPress REST API
 */
class My_REST_Blog_Swagger_UI {
    /**
     * Initialize the class
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_swagger_assets'));
        add_action('rest_api_init', array($this, 'register_swagger_endpoint'));
    }

    /**
     * Add admin menu item
     */
    public function add_admin_menu() {
        add_menu_page(
            'REST API Documentation',
            'API Docs',
            'manage_options',
            'my-rest-blog-api-docs',
            array($this, 'render_swagger_ui'),
            'dashicons-rest-api',
            80
        );
    }

    /**
     * Enqueue Swagger UI assets
     */
    public function enqueue_swagger_assets($hook) {
        if ('toplevel_page_my-rest-blog-api-docs' !== $hook) {
            return;
        }

        // Swagger UI CSS
        wp_enqueue_style(
            'swagger-ui',
            'https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.3.1/swagger-ui.css',
            array(),
            '5.3.1'
        );

        // Swagger UI JS
        wp_enqueue_script(
            'swagger-ui',
            'https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.3.1/swagger-ui-bundle.js',
            array(),
            '5.3.1',
            true
        );

        wp_enqueue_script(
            'swagger-ui-standalone',
            'https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.3.1/swagger-ui-standalone-preset.js',
            array(),
            '5.3.1',
            true
        );

        // Custom JS to initialize Swagger UI
        wp_enqueue_script(
            'my-rest-blog-swagger',
            plugins_url('/js/swagger-init.js', dirname(__FILE__)),
            array('jquery', 'swagger-ui'),
            '1.0.0',
            true
        );

        // Localize script with API URL
        wp_localize_script('my-rest-blog-swagger', 'myRestBlogVars', array(
            'restUrl' => esc_url_raw(rest_url('myplugin/v1/')),
            'nonce' => wp_create_nonce('wp_rest')
        ));
    }

    /**
     * Render Swagger UI container
     */
    public function render_swagger_ui() {
        ?>
        <div class="wrap">
            <h1>My REST Blog API Documentation</h1>
            <p>Interactive API documentation for the My REST Blog plugin. Use the form below to test the API endpoints.</p>
            <div id="swagger-ui"></div>
        </div>
        <?php
    }

    /**
     * Register Swagger JSON endpoint
     */
    public function register_swagger_endpoint() {
        register_rest_route('myplugin/v1', '/swagger.json', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_swagger_spec'),
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ));
    }

    /**
     * Generate Swagger/OpenAPI specification
     */
    public function get_swagger_spec() {
        $swagger = array(
            'openapi' => '3.0.0',
            'info' => array(
                'title' => 'My REST Blog API',
                'description' => 'Interactive API documentation for My REST Blog',
                'version' => '1.0.0',
                'contact' => array(
                    'name' => 'API Support',
                    'url' => 'https://github.com/vinaychavada/my-rest-blog'
                )
            ),
            'servers' => array(
                array(
                    'url' => rest_url('myplugin/v1'),
                    'description' => 'WordPress REST API'
                )
            ),
            'paths' => array(
                '/hello' => array(
                    'get' => array(
                        'tags' => array('Hello'),
                        'summary' => 'Get a greeting message',
                        'description' => 'Returns a friendly greeting message',
                        'parameters' => array(
                            array(
                                'name' => 'name',
                                'in' => 'query',
                                'description' => 'Name to include in the greeting',
                                'required' => false,
                                'schema' => array(
                                    'type' => 'string',
                                    'default' => 'Vinay'
                                )
                            )
                        ),
                        'responses' => array(
                            '200' => array(
                                'description' => 'Successful response',
                                'content' => array(
                                    'application/json' => array(
                                        'schema' => array(
                                            'type' => 'object',
                                            'properties' => array(
                                                'message' => array(
                                                    'type' => 'string',
                                                    'example' => 'Hello Vinay! Your REST API is working 🎉'
                                                ),
                                                'status' => array(
                                                    'type' => 'string',
                                                    'example' => 'success'
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                ),
                '/posts' => array(
                    'get' => array(
                        'tags' => array('Posts'),
                        'summary' => 'Get recent posts',
                        'description' => 'Returns a list of recent blog posts',
                        'parameters' => array(
                            array(
                                'name' => 'per_page',
                                'in' => 'query',
                                'description' => 'Number of posts to return',
                                'required' => false,
                                'schema' => array(
                                    'type' => 'integer',
                                    'default' => 5,
                                    'minimum' => 1,
                                    'maximum' => 20
                                )
                            ),
                            array(
                                'name' => 'category',
                                'in' => 'query',
                                'description' => 'Category slug to filter by',
                                'required' => false,
                                'schema' => array(
                                    'type' => 'string'
                                )
                            )
                        ),
                        'responses' => array(
                            '200' => array(
                                'description' => 'List of posts',
                                'content' => array(
                                    'application/json' => array(
                                        'schema' => array(
                                            'type' => 'object',
                                            'properties' => array(
                                                'status' => array('type' => 'string'),
                                                'count' => array('type' => 'integer'),
                                                'data' => array(
                                                    'type' => 'array',
                                                    'items' => array(
                                                        'type' => 'object',
                                                        'properties' => array(
                                                            'id' => array('type' => 'integer'),
                                                            'title' => array('type' => 'string'),
                                                            'excerpt' => array('type' => 'string'),
                                                            'date' => array('type' => 'string', 'format' => 'date-time'),
                                                            'author' => array('type' => 'string'),
                                                            'featured_image' => array('type' => 'string', 'format' => 'uri', 'nullable' => true),
                                                            'categories' => array('type' => 'array', 'items' => array('type' => 'string')),
                                                            'tags' => array('type' => 'array', 'items' => array('type' => 'string')),
                                                            'url' => array('type' => 'string', 'format' => 'uri')
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            'components' => array(
                'securitySchemes' => array(
                    'wordpress' => array(
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                        'description' => 'WordPress REST API authentication using JWT'
                    )
                )
            )
        );

        return new WP_REST_Response($swagger, 200);
    }
}

// Initialize the class
new My_REST_Blog_Swagger_UI();
