<?php

namespace Infrastructure\Http\Guards;

use WP_REST_Request;

final class NonceGuard {
    public static function verify( WP_REST_Request $req, string $action ): bool {
        $nonce = $req->get_header('X-FLM-Nonce') ?: (string) $req->get_param('_flm_nonce');
        return $nonce && wp_verify_nonce($nonce, $action);
    }
}