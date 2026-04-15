<?php

namespace App;

enum AppointmentTypeEnum : string {
    
    case INPERSON = 'INPERSON';
    case MEET = 'MEET';

    public static function values(): array{
        return array_column(self::cases(), 'value');
    }
}
