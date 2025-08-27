<?php

namespace Domain\ValueObjects;

final class Email
{
    private string $value;

    public function __construct( string $value ) {
        $value = strtolower( trim($value) );

        if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) )
        {
            throw new \InvalidArgumentException('invalid_email');
        }

        $this->value = $value;
    }
    public function value(): string
    {
        return $this->value;
    }
}