<?php

namespace App\Exceptions;

use Exception;

class PatientTimeConflictException extends Exception {
    protected $message = 'You already have an appointment at this time';
    protected $code = 409;

    public function __construct(string $message = '', int $code = 0) {
        parent::__construct($message ?: $this->message, $code ?: $this->code);
    }
}
