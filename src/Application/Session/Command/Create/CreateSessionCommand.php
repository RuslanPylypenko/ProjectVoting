<?php

namespace App\Application\Session\Command\Create;

use App\Domain\Session\Enum\StageName;
use App\Infrastructure\Application\Command\CommandInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CreateSessionCommand implements CommandInterface
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 255)]
    public string $name;

    #[SerializedName('submission_requirements')]
    #[Assert\Valid]
    public SubmissionRequirements $submissionRequirements;

    #[SerializedName('voting_requirements')]
    public VotingRequirements $votingRequirements;

    #[SerializedName('winner_requirements')]
    public WinnerRequirements $winnerRequirements;

    #[Assert\All([
        new Assert\Collection([
            'fields' => [
                'name' => [
                    new Assert\NotBlank(),
                    new Assert\Choice(choices: StageName::VALUES, message: 'Invalid name.'),
                ],
                'start_date' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^\d{2}-\d{2}-\d{4}$/',
                        'message' => 'The date must be in the format DD-MM-YYYY.',
                    ]),
                ],
                'end_date' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^\d{2}-\d{2}-\d{4}$/',
                        'message' => 'The date must be in the format DD-MM-YYYY.',
                    ]),
                ],
            ],
            'allowMissingFields' => false,
        ]),
    ]),
    ]
    public array $stages;

    public function __construct(
        string $name,
        array $stages,
        SubmissionRequirements $submissionRequirements,
        VotingRequirements $votingRequirements,
        WinnerRequirements $winnerRequirements,
    ) {
        $this->name = $name;
        $this->stages = $stages;
        $this->submissionRequirements = $submissionRequirements;
        $this->votingRequirements = $votingRequirements;
        $this->winnerRequirements = $winnerRequirements;
    }
}
