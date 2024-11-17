<?php

namespace App\Domain\Project\Enum;

enum ProjectStatus: int
{
    case PENDING = 0;         // Очікування
    case APPROVED = 1;        // Затверджено
    case REJECTED = 2;        // Відхилено
    case VOTING = 3;          // На голосуванні
    case WINNER = 4;          // Переможець
    case NOT_A_WINNER = 5;    // Не переможець (недостатньо голосів)
    case IN_REVIEW = 6;       // На перевірці
    case COMPLETED = 7;       // Завершено

    public function getLabel(): string
    {
        return match ($this) {
            ProjectStatus::PENDING => 'Pending',
            ProjectStatus::APPROVED => 'Approved',
            ProjectStatus::REJECTED => 'Rejected',
            ProjectStatus::VOTING => 'Voting',
            ProjectStatus::WINNER => 'Winner',
            ProjectStatus::NOT_A_WINNER => 'Not a Winner',
            ProjectStatus::IN_REVIEW => 'In Review',
            ProjectStatus::COMPLETED => 'Completed',
        };
    }
}
