<?php

namespace App\Domain\Project\Validator\Rule;

use App\Domain\Session\Entity\SessionEntity;

class RuleFactory
{
    public function createRulesForSubmission(SessionEntity $session): array
    {
        $requirements = $session->getSubmissionRequirements();

        return [
            new SessionStageRule(),
            new AgeRule($requirements->getMinAge()),
            new AddressRule($session->getCity()->getAddress()),
            new CategoryRule($requirements->getCategories()),
            new BudgetRule($requirements->getMinBudget(), $requirements->getMaxBudget()),
        ];
    }
}
