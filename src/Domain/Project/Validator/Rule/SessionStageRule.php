<?php

namespace App\Domain\Project\Validator\Rule;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Exception\VoteRuleValidationException;

class SessionStageRule implements ProjectRulesInterface
{
    public function validate(ProjectEntity $project): void
    {
        $stage = $project->getSession()->getActiveStage($project->getCreatedAt());
        if ($stage->isSubmission()) {
            return;
        }

        throw new VoteRuleValidationException('Session must me on Submission stage');
    }
}
