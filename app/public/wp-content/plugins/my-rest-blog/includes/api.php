<?php
/**
 * REST API endpoints for My REST Blog
 */

// Register custom REST API endpoints
add_action('rest_api_init', function () {
    // Simple hello endpoint
    register_rest_route('myplugin/v1', '/hello', array(
        'methods' => 'GET',
        'callback' => 'my_rest_blog_hello_world',
        'permission_callback' => '__return_true',
        'args' => array(
            'name' => array(
                'description' => 'Name to include in the greeting',
                'type' => 'string',
                'default' => 'Vinay',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));

    // Get recent posts endpoint
    register_rest_route('myplugin/v1', '/posts', array(
        'methods' => 'GET',
        'callback' => 'my_rest_blog_get_posts',
        'permission_callback' => '__return_true',
        'args' => array(
            'per_page' => array(
                'description' => 'Number of posts to return',
                'type' => 'integer',
                'default' => 5,
                'sanitize_callback' => 'absint',
                'validate_callback' => function($param) {
                    return is_numeric($param) && $param > 0 && $param <= 20;
                },
            ),
            'category' => array(
                'description' => 'Category slug to filter by',
                'type' => 'string',
                'sanitize_callback' => 'sanitize_title',
            ),
        ),
    ));

    // Get post by ID
    register_rest_route('myplugin/v1', '/posts/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'my_rest_blog_get_post',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'validate_callback' => 'is_numeric',
                'required' => true,
            ),
            'include_content' => array(
                'description' => 'Whether to include the full post content',
                'type' => 'boolean',
                'default' => false,
            ),
        ),
    ));
});

/**
 * Hello World endpoint callback
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|WP_REST_Response
 */
function my_rest_blog_hello_world($request) {
    $name = $request->get_param('name');
    
    return new WP_REST_Response(array(
        'message' => sprintf('Hello %s! Your REST API is working 🎉', esc_html($name)),
        'status' => 'success',
        'data' => array(
            'timestamp' => current_time('mysql'),
            'version' => MY_REST_BLOG_VERSION,
        ),
    ), 200);
}

/**
 * Get recent posts
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|WP_REST_Response
 */
function my_rest_blog_get_posts($request) {
    $per_page = $request->get_param('per_page');
    $category = $request->get_param('category');
    
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    if (!empty($category)) {
        $args['category_name'] = $category;
    }
    
    $query = new WP_Query($args);
    $posts = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $post_data = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'date' => get_the_date('c'),
                'author' => get_the_author(),
                'featured_image' => get_the_post_thumbnail_url() ?: null,
                'categories' => wp_list_pluck(get_the_category(), 'name'),
                'tags' => wp_list_pluck(get_the_tags() ?: array(), 'name'),
                'url' => get_permalink(),
            );
            
            $posts[] = $post_data;
        }
        wp_reset_postdata();
        
        return new WP_REST_Response(array(
            'status' => 'success',
            'count' => count($posts),
            'data' => $posts,
        ), 200);
    }
    
    return new WP_Error(
        'no_posts',
        'No posts found',
        array('status' => 404)
    );
}

/**
 * Get single post by ID
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|WP_REST_Response
 */
function my_rest_blog_get_post($request) {
    $post_id = $request->get_param('id');
    $include_content = $request->get_param('include_content');
    
    $post = get_post($post_id);
    
    if (!$post || 'publish' !== $post->post_status) {
        return new WP_Error(
            'post_not_found',
            'Post not found',
            array('status' => 404)
        );
    }
    
    $response = array(
        'id' => $post->ID,
        'title' => get_the_title($post),
        'date' => get_the_date('c', $post),
        'modified' => $post->post_modified_gmt,
        'author' => get_the_author_meta('display_name', $post->post_author),
        'featured_image' => get_the_post_thumbnail_url($post->ID, 'full'),
        'categories' => wp_list_pluck(get_the_category($post->ID), 'name'),
        'tags' => wp_list_pluck(get_the_tags($post->ID) ?: array(), 'name'),
        'url' => get_permalink($post->ID),
    );
    
    if ($include_content) {
        $response['content'] = apply_filters('the_content', $post->post_content);
    }
    
    return new WP_REST_Response(array(
        'status' => 'success',
        'data' => $response,
    ), 200);
}
