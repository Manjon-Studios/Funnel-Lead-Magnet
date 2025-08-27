<?php

namespace Domain\Entities;

use Domain\ValueObjects\Email;

final class Lead {
    public function __construct(
        public readonly Email $email,
        public readonly ?string $firstName,
        public string $status,
        public readonly ?string $ipHash,
        public readonly ?string $userAgent,
        public readonly string $consentAtUtc,
        public readonly ?string $source
    ) {}
}