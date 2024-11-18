<?php

namespace App\Application\Http\Session\Command\Create;

use App\Domain\Shared\Enum\Category;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class SubmissionRequirements
{
    #[Assert\NotBlank()]
    #[Assert\Positive()]
    #[SerializedName('min_age')]
    public int $minAge;

    #[Assert\All([
        new Assert\NotBlank(),
        new Assert\Type('string'),
        new Assert\Choice(
            choices: Category::VALUES,
            message: 'The category "{{ value }}" is not a valid choice.'
        ),
    ])]
    public array $categories;

    #[Assert\Type('integer')]
    #[Assert\NotBlank()]
    #[SerializedName('min_budget')]
    public int $minBudget;

    #[Assert\Type('integer')]
    #[Assert\NotBlank()]
    #[SerializedName('max_budget')]
    public int $maxBudget;

    #[Assert\Type('bool')]
    #[Assert\NotBlank()]
    #[SerializedName('only_residents')]
    public bool $onlyResidents;

    public function __construct(
        int $minAge,
        array $categories,
        int $minBudget,
        int $maxBudget,
        bool $onlyResidents,
    ) {
        $this->categories = $categories;
        $this->maxBudget = $maxBudget;
        $this->minAge = $minAge;
        $this->minBudget = $minBudget;
        $this->onlyResidents = $onlyResidents;
    }
}
