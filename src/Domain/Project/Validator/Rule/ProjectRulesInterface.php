<?php

namespace App\Domain\Project\Validator\Rule;

use App\Domain\Project\Entity\ProjectEntity;

interface ProjectRulesInterface
{
    public function validate(ProjectEntity $project): void;
}