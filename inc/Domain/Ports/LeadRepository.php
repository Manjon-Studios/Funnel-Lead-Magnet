<?php

namespace Domain\Ports;

use \Domain\Entities\Lead;

interface LeadRepository
{
    public function save( Lead $lead ): void;
    public function existsByEmail( string $email ): bool;
}