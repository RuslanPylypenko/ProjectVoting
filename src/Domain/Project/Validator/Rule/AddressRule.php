<?php

namespace App\Domain\Project\Validator\Rule;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Exception\ProjectRuleValidationException;
use App\Domain\Shared\Address\Address;

class AddressRule implements ProjectRulesInterface
{
    public function __construct(private Address $requiredAddress)
    {
    }

    public function validate(ProjectEntity $project): void
    {
        if ($project->getAddress()->isSameCity($this->requiredAddress)
            && ($project->getAuthor()->getLivingAddress()->isSameCity($this->requiredAddress)
                || $project->getAuthor()->getRegistrationAddress()->isSameCity($this->requiredAddress))) {
            return;
        }

        throw new ProjectRuleValidationException('Project address does not match the required address criteria.');
    }
}
