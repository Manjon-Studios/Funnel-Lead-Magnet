<?php

namespace Infrastructure\Http\Routes;

use Infrastructure\Http\Controllers\OptinController;

final class OptinRoutes {
    public function __construct(
        private OptinController $controller
    ) {}

    public function register(): void {
        register_rest_route('flm/v1', '/optin', [
            'methods'  => 'POST',
            'callback' => [$this->controller, 'store'],
            'permission_callback' => '__return_true',
            'args' => [
                'email'      => ['required' => true, 'sanitize_callback'    => 'sanitize_email'],
                'first_name' => ['required' => false, 'sanitize_callback'   => 'sanitize_text_field'],
                'website'    => ['required' => false, 'sanitize_callback'   => 'sanitize_text_field'],
                '_flm_nonce' => ['required' => false, 'sanitize_callback'   => 'sanitize_text_field'],
                'source'     => ['required' => false, 'sanitize_callback'   => 'sanitize_text_field'],
            ],
        ]);
    }
}