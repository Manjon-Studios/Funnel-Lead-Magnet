<?php

namespace Application\Validation;

use \Application\DTO\CreateLeadDTO;
use \Domain\ValueObjects\Email;

final class CreateLeadValidator {
    public function validate( CreateLeadDTO $dto ): array
    {
        $errors = [];

        if ( strlen( trim($dto->name) ) < 2 ) {
            $errors['name'] = 'INVALID_NAME_LENGTH';
        };

        try {
            new Email( $dto->email );
        } catch (\InvalidArgumentException)
        {
            $errors['email'] = 'INVALID_EMAIL';
        }

        if ( $dto->phone && ! preg_match('/^\+?[0-9\s-]{7,20}$/', $dto->phone) ) {
            $errors['phone'] = 'INVALID_PHONE';
        }

        return $errors;
    }
}