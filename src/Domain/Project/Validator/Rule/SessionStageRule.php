<?php

namespace App\Domain\Project\Validator\Rule;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Exception\VoteRuleValidationException;

class SessionStageRule implements ProjectRulesInterface
{
    public function validate(ProjectEntity $project): void
    {
        if ($project->getSession()->getActiveStage(new \DateTime())->isSubmission()) {
            return;
        }

        throw new VoteRuleValidationException('Session must me on Submission stage');
    }
}
