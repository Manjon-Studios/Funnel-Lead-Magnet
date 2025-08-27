<?php

namespace Infrastructure\Http\Validation;

use WP_REST_Request;
use Application\DTO\OptInLeadDTO;

final class OptinRequest {
    public static function toDTO( WP_REST_Request $req ): OptInLeadDTO
    {
        $email = strtolower(trim((string) $req->get_param('email')));
        $first = preg_replace('/\s+/', ' ', trim((string) $req->get_param('first_name')));
        $source= (string) $req->get_param('source');

        $ip  = $_SERVER['REMOTE_ADDR']     ?? '';
        $ua  = $_SERVER['HTTP_USER_AGENT'] ?? $req->get_header('user_agent') ?? '';

        $ipHash = $ip ? hash('sha256', $ip) : null;

        return new OptInLeadDTO(
            email: $email,
            firstName: $first ?: null,
            ipHash: $ipHash,
            userAgent: $ua ?: null,
            source: $source ?: null,
            doubleOptIn: true
        );
    }
}