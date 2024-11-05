<?php

namespace App\Application\Session\Command\Create;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class SubmissionRequirements
{
    #[Assert\Type('integer')]
    #[Assert\NotBlank()]
    #[SerializedName('min_age')]
    public int $minAge;

    #[Assert\All([
        new Assert\Type("integer")
    ])]
    #[Assert\NotBlank()]
    public array $categories;

    #[Assert\Type('integer')]
    #[Assert\NotBlank()]
    #[SerializedName('min_budget')]
    public int $minBudget;

    #[Assert\Type('integer')]
    #[Assert\NotBlank()]
    #[SerializedName('max_budget')]
    public int $maxBudget;

    #[Assert\Type("bool")]
    #[Assert\NotBlank()]
    #[SerializedName('only_residents')]
    public bool $onlyResidents;
}