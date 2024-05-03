<?php

namespace App\Enum;

enum UserQuizStatus: string
{
    case QUEUED = 'queued';
    case STARTED = 'started';
    case FINISHED = 'finished';

    public static function validToBeTaken(UserQuizStatus $userQuizStatus): bool
    {
        return in_array($userQuizStatus, [self::QUEUED, self::STARTED], true);
    }
}
