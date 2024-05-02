<?php

namespace App\Enum;

enum UserQuizStatus: string
{
    case QUEUED = 'queued';
    case STARTED = 'started';
    case FINISHED = 'finished';

    public static function toArray(): array
    {
        $values = [];
        foreach (self::cases() as $enum) {
            $values[$enum->name] = $enum->value;
        }

        return $values;
    }
}
