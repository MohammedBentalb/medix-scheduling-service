<?php

namespace App\Exceptions;

use Exception;

class SlotOutsideScheduleException extends Exception {
    protected $message = "The requested slot does not fall within the doctor's schedule";
    protected $code = 422;

    public function __construct(string $message = '', int $code = 0) {
        parent::__construct($message ?: $this->message, $code ?: $this->code);
    }
}
