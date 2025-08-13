/**
 * Initialize Swagger UI with static OpenAPI specification
 */
(function($) {
    'use strict';

    // Static OpenAPI specification
    const openApiSpec = {
        "openapi": "3.0.0",
        "info": {
            "title": "My REST Blog API",
            "description": "Interactive API documentation for My REST Blog",
            "version": "1.0.0"
        },
        "servers": [{
            "url": myRestBlogVars.restUrl.replace(/\/$/, ''), // Remove trailing slash
            "description": "WordPress REST API"
        }],
        "paths": {
            "/hello": {
                "get": {
                    "tags": ["Hello"],
                    "summary": "Get greeting",
                    "description": "Returns a greeting message",
                    "parameters": [{
                        "name": "name",
                        "in": "query",
                        "description": "Name to greet",
                        "required": false,
                        "schema": {
                            "type": "string",
                            "default": "Vinay"
                        }
                    }],
                    "responses": {
                        "200": {
                            "description": "Successful response",
                            "content": {
                                "application/json": {
                                    "schema": {
                                        "type": "object",
                                        "properties": {
                                            "message": { "type": "string" },
                                            "status": { "type": "string" }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            },
            "/posts": {
                "get": {
                    "tags": ["Posts"],
                    "summary": "Get posts",
                    "description": "Returns a list of blog posts",
                    "parameters": [{
                        "name": "per_page",
                        "in": "query",
                        "description": "Number of posts to return",
                        "required": false,
                        "schema": {
                            "type": "integer",
                            "default": 5,
                            "minimum": 1,
                            "maximum": 20
                        }
                    }, {
                        "name": "category",
                        "in": "query",
                        "description": "Filter by category slug",
                        "required": false,
                        "schema": {
                            "type": "string"
                        }
                    }],
                    "responses": {
                        "200": {
                            "description": "List of posts"
                        }
                    }
                }
            }
        }
    };

    // Initialize Swagger UI with the static spec
    $(document).ready(function() {
        const ui = SwaggerUIBundle({
            spec: openApiSpec,
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout",
            requestInterceptor: function(request) {
                if (!request.headers) request.headers = {};
                request.headers['X-WP-Nonce'] = myRestBlogVars.nonce;
                return request;
            },
            onComplete: function() {
                $('.swagger-ui .topbar').remove();
                $('.swagger-ui .information-container').remove();
                
                $('.swagger-ui .wrapper').prepend(
                    '<div class="swagger-ui-wrap">' +
                    '   <h2>My REST Blog API Documentation</h2>' +
                    '   <p>Interactive documentation for the My REST Blog API. Click on the endpoints below to test them.</p>' +
                    '</div>'
                );
            }
        });

        // Add some basic styling
        const style = document.createElement('style');
        style.textContent = `
            .swagger-ui .info { margin: 20px 0; }
            .swagger-ui .info hgroup.main { margin: 0 0 20px; }
            .swagger-ui .info .title { 
                color: #3b4151;
                font-size: 24px;
                margin: 0 0 10px;
            }
            .swagger-ui .opblock { margin-bottom: 15px; }
            .swagger-ui .btn.execute { background: #49cc90; }
        `;
        document.head.appendChild(style);
    });

})(jQuery);
