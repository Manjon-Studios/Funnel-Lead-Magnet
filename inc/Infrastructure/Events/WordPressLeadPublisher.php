<?php

namespace Infrastructure\Events;

use Domain\Ports\LeadPublisher;
use Domain\Entities\Lead;

final class WordPressLeadPublisher implements LeadPublisher {
    public function publishCreated(Lead $lead): void {
        do_action('flm_lead_created', [
            'email'      => $lead->email->value(),
            'first_name' => $lead->firstName,
            'status'     => $lead->status,
            'ip_hash'    => $lead->ipHash,
            'user_agent' => $lead->userAgent,
            'consent_at' => $lead->consentAtUtc,
            'source'     => $lead->source,
        ]);
    }
}