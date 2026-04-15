<?php

namespace App\Exceptions;

use Exception;

class DoctorNotAvailableException extends Exception {
    protected $message = 'This doctor is not available for booking';
    protected $code = 422;

    public function __construct(string $message = '', int $code = 0) {
        parent::__construct($message ?: $this->message, $code ?: $this->code);
    }
}