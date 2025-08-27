<?php

namespace Domain\Ports;

interface DisposableEmailService
{
    /** Devuelve true si el dominio del email está bloqueado (desechables). */
    public function isDisposable( string $emailDomain ): bool;
}