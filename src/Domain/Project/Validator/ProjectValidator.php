<?php

namespace App\Domain\Project\Validator;

use App\Domain\Project\Entity\ProjectEntity;

class ProjectValidator
{
    public function validate(ProjectEntity $project, array $rules): void
    {
        foreach ($rules as $rule) {
            $rule->validate($project);
        }
    }
}