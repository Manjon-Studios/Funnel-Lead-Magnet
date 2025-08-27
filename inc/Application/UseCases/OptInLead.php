<?php

namespace Application\UseCases;

use Application\DTO\OptInLeadDTO;
use Domain\Entities\Lead;
use Domain\Enums\LeadStatus;
use Domain\Ports\DisposableEmailService;
use Domain\Ports\LeadPublisher;
use Domain\Ports\LeadRepository;
use Domain\ValueObjects\Email;
use DomainException;

final readonly class OptInLead {
    public function __construct(
        private LeadRepository         $repo,
        private DisposableEmailService $disposable,
        private LeadPublisher          $publisher
    ) {}

    /**
     * @throws DomainException on business violations.
     */
    public function __invoke( OptInLeadDTO $dto ): Lead
    {
        $email = new Email($dto->email);

        // Política de bloque de desechables (opcional fuerte)
        $domain = substr(strrchr($email->value(), '@') ?: '', 1);
        if ( $domain && $this->disposable->isDisposable( $domain ) ) {
            throw new DomainException('disposable_email_blocked');
        }

        // Idempotencia soft: podrías impedir duplicados si lo deseas
        if ( $this->repo->existsByEmail( $email->value() ) ) {
            // No es error de validación estricta; depende del negocio.
            // throw new \DomainException('email_already_exists');
        }

        $lead = new Lead(
            email: $email,
            firstName: $dto->firstName,
            status: $dto->doubleOptIn ? LeadStatus::PENDING : LeadStatus::ACTIVE,
            ipHash: $dto->ipHash,
            userAgent: $dto->userAgent,
            consentAtUtc: gmdate('Y-m-d H:i:s'),
            source: $dto->source
        );

        $this->repo->save( $lead );
        $this->publisher->publishCreated( $lead );

        return $lead;
    }
}