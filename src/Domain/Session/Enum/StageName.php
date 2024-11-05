<?php

namespace App\Domain\Session\Enum;

enum StageName: string
{
    case SUBMISSION = 'Project Submission';
    case VOTING = 'Voting';
    case WINNER = 'Winner Determination';

    public const VALUES = [
        self::SUBMISSION->value,
        self::VOTING->value,
        self::WINNER->value,
    ];
}