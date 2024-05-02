<?php

namespace App\Enum;

enum UserQuizStatus: string
{
    case QUEUED = 'queued';
    case STARTED = 'started';
    case FINISHED = 'finished';
}
