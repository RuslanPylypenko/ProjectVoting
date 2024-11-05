<?php

namespace App\Application\Session\Command\Create;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;


class WinnerRequirements
{
    #[Assert\Type('integer')]
    #[Assert\NotBlank()]
    #[SerializedName('max_winners')]
    public int $maxWinners;

    #[Assert\Type('integer')]
    #[Assert\NotBlank()]
    #[SerializedName('min_votes')]
    public int $minVotes;
}