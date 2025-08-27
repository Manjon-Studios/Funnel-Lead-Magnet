<?php

namespace Infrastructure\Http\Controllers;

use Application\UseCases\OptInLead;
use Infrastructure\Http\Validation\OptinRequest;
use Infrastructure\Http\Guards\NonceGuard;
use Infrastructure\Http\Guards\RateLimiter;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;


final class OptinController {
    public function __construct(
        private OptInLead $usecase
    ) {}

    public function store(WP_REST_Request $req): WP_REST_Response|WP_Error {

        if (strtoupper($req->get_method()) !== 'POST') {
            $r = new WP_REST_Response(['error' => 'method_not_allowed'], 405);
            $r->header('Allow', 'POST'); return $r;
        }

        $raw = (string) $req->get_body();

        if ($raw !== '' && strlen($raw) > 64 * 1024) {
            return new WP_Error('payload_too_large', 'Payload demasiado grande', ['status' => 413]);
        }

        if (!NonceGuard::verify($req, 'flm_optin')) {
            return new WP_Error('bad_nonce', 'Nonce inválido', ['status' => 403]);
        }

        if (!empty((string) $req->get_param('website'))) {
            return new WP_Error('honeypot_triggered', 'Honeypot activado', ['status' => 422]);
        }

        $ip   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ipCt = RateLimiter::hit('flm_rl_ip_' . sha1($ip), 60);
        if ($ipCt > 5) {
            $r = new WP_REST_Response(['error' => 'rate_limited_ip'], 429);
            $r->header('Retry-After', '60'); return $r;
        }

        $email = strtolower(trim((string) $req->get_param('email')));
        if (!is_email($email)) {
            return new WP_Error('invalid_email_format', 'Email inválido', ['status' => 422]);
        }

        $emCt = RateLimiter::hit('flm_rl_em_' . sha1($email), 3600);
        if ($emCt > 3) {
            $r = new WP_REST_Response(['error' => 'rate_limited_email'], 429);
            $r->header('Retry-After', '3600'); return $r;
        }

        $dto = OptinRequest::toDTO($req);

        try {
            ($this->usecase)($dto);
        } catch (\DomainException $e) {
            $code = $e->getMessage();
            $map  = [
                'disposable_email_blocked' => ['status' => 422, 'error' => 'disposable_email_blocked'],
                'invalid_email'            => ['status' => 422, 'error' => 'invalid_email_format'],
                'email_already_exists'     => ['status' => 409, 'error' => 'email_conflict'],
            ];
            $info = $map[$code] ?? ['status' => 422, 'error' => 'domain_error'];
            return new WP_Error($info['error'], $code, ['status' => $info['status']]);
        }

        return new WP_REST_Response(['ok' => true], 200);
    }
}