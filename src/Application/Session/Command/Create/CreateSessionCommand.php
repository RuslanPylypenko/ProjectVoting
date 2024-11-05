<?php

namespace App\Application\Session\Command\Create;

use App\Infrastructure\Application\Command\CommandInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CreateSessionCommand implements CommandInterface
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 255)]
    public string $name;

    #[SerializedName('submission_requirements')]
    public SubmissionRequirements $submissionRequirements;

    #[SerializedName('voting_requirements')]
    public VotingRequirements $votingRequirements;

    #[SerializedName('winner_requirements')]
    public WinnerRequirements $winnerRequirements;
}