<?php

namespace Application\DTO;

final class OptInLeadDTO {
    public function __construct(
        public string $email,
        public ?string $firstName,
        public ?string $ipHash,
        public ?string $userAgent,
        public ?string $source,
        public bool $doubleOptIn = true
    ) {}
}