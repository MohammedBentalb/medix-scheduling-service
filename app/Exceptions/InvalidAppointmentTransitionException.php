<?php

namespace App\Exceptions;

use Exception;

class InvalidAppointmentTransitionException extends Exception {
    protected $message = 'This status transition is not allowed for the current state';
    protected $code = 422;

    public function __construct(string $message = '', int $code = 0) {
        parent::__construct($message ?: $this->message, $code ?: $this->code);
    }
}
