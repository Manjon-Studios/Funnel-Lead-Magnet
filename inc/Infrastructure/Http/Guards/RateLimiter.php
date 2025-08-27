<?php

namespace Infrastructure\Http\Guards;

final class RateLimiter {
    public static function hit(string $key, int $ttl): int {
        $count = (int) get_transient($key);
        $count++;
        set_transient($key, $count, $ttl);
        return $count;
    }
}