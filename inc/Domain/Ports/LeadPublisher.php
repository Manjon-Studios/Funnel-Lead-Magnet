<?php

namespace Domain\Ports;

use Domain\Entities\Lead;

interface LeadPublisher
{
    public function publishCreated( Lead $lead ): void;
}