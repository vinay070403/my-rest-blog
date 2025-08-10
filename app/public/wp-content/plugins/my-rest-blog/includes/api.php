<?php
// Register custom REST API endpoint
add_action('rest_api_init', function () {
    register_rest_route('myplugin/v1', '/hello', array(
        'methods' => 'GET',
        'callback' => 'myplugin_hello_world',
    ));
});

// Callback function
function myplugin_hello_world() {
    return array(
        'message' => 'Hello Vinay! Your REST API is working 🎉',
        'status'  => 'success'
    );
}
