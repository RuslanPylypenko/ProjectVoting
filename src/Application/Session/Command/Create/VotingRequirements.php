<?php

namespace App\Application\Session\Command\Create;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class VotingRequirements
{
    #[Assert\Type('integer')]
    #[Assert\NotBlank()]
    #[SerializedName('max_votes')]
    public int $maxVotes;

    #[Assert\Type('integer')]
    #[Assert\NotBlank()]
    #[SerializedName('min_age')]
    public int $minAge;

    #[Assert\Type("bool")]
    #[Assert\NotBlank()]
    #[SerializedName('only_residents')]
    public bool $onlyResidents;
}