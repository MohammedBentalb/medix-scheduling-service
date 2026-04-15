<?php

namespace App\Exceptions;

use Exception;

class SlotNotAvailableException extends Exception {
    protected $message = 'This time slot is already booked';
    protected $code    = 409;

    public function __construct(string $message = '', int $code = 0) {
        parent::__construct($message ?: $this->message, $code ?: $this->code);
    }
}