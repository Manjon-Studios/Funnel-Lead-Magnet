<?php

namespace Infrastructure\Persistence;

use wpdb;
use Domain\Ports\LeadRepository;
use Domain\Entities\Lead;

final class WpdbLeadRepository implements LeadRepository {
    public function __construct(private wpdb $db) {}

    public function save(Lead $lead): void {
        $table = $this->db->prefix . 'flm_leads';
        $this->db->insert($table, [
            'email'      => $lead->email->value(),
            'first_name' => $lead->firstName,
            'status'     => $lead->status,
            'ip_hash'    => $lead->ipHash,
            'user_agent' => $lead->userAgent,
            'consent_at' => $lead->consentAtUtc,
            'source'     => $lead->source,
        ], ['%s','%s','%s','%s','%s','%s','%s']);
    }

    public function existsByEmail(string $email): bool {
        $table = $this->db->prefix . 'flm_leads';
        $sql   = $this->db->prepare("SELECT 1 FROM $table WHERE email=%s LIMIT 1", $email);
        return (bool) $this->db->get_var($sql);
    }
}