<?php

namespace App\Domain\Project\Enum;

enum ProjectAction: int
{
    case CREATE = 1;
    case UPDATE = 2;
}