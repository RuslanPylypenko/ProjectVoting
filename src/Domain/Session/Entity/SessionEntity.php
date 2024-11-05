<?php

namespace App\Domain\Session\Entity;

use App\Domain\City\Entity\CityEntity;
use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\Requirement\SubmissionRequirements;
use App\Domain\Session\Entity\Requirement\VotingRequirements;
use App\Domain\Session\Entity\Requirement\WinnerRequirements;
use App\Domain\Session\Enum\StageName;
use App\Infrastructure\Repository\SessionRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
#[ORM\Table(name: 'sessions')]
class SessionEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: CityEntity::class, inversedBy: 'sessions')]
    #[ORM\JoinColumn(name: 'city_id', referencedColumnName: 'id', nullable: false)]
    private CityEntity $city;

    #[ORM\OneToOne(targetEntity: WinnerRequirements::class, mappedBy: 'session', cascade: ['persist'])]
    private WinnerRequirements $winnerRequirements;

    #[ORM\OneToOne(targetEntity: SubmissionRequirements::class, mappedBy: 'session', cascade: ['persist'])]
    private SubmissionRequirements $submissionRequirements;

    #[ORM\OneToOne(targetEntity: VotingRequirements::class, mappedBy: 'session', cascade: ['persist'])]
    private VotingRequirements $votingRequirements;

    #[ORM\OneToMany(targetEntity: ProjectEntity::class, mappedBy: 'session', cascade: ['persist', 'remove'])]
    private Collection $projects;

    /** @var Collection<Stage> */
    #[ORM\OneToMany(targetEntity: Stage::class, mappedBy: 'session', cascade: ['persist', 'remove'])]
    private Collection $stages;

    public function __construct(
        string $name,
        CityEntity $city,
        array $stages,
        WinnerRequirements $winnerRequirements,
        VotingRequirements $votingRequirements,
        SubmissionRequirements $submissionRequirements,
    )
    {
        $this->city = $city;
        $this->name = $name;

        $this->projects = new ArrayCollection();

        $this->setStages($stages);
        $this->setWinnerRequirements($winnerRequirements);
        $this->setVotingRequirements($votingRequirements);
        $this->setSubmissionRequirements($submissionRequirements);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSubmissionRequirements(): SubmissionRequirements
    {
        return $this->submissionRequirements;
    }

    public function getCity(): CityEntity
    {
        return $this->city;
    }

    public function getVotingRequirements(): VotingRequirements
    {
        return $this->votingRequirements;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function getActiveStage(DateTime $date): Stage
    {
        foreach ($this->stages as $stage) {
            if ($stage->isActive($date)) {
                return $stage;
            }
        }

        return $this->stages->last();
    }

    /**
     * @return Collection<ProjectEntity>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function getWinnerRequirements(): WinnerRequirements
    {
        return $this->winnerRequirements;
    }

    public function setWinnerRequirements(WinnerRequirements $winnerRequirements): void
    {
        $this->winnerRequirements = $winnerRequirements;
        $winnerRequirements->setSession($this);
    }

    public function setVotingRequirements(VotingRequirements $votingRequirements): void
    {
        $this->votingRequirements = $votingRequirements;
        $votingRequirements->setSession($this);
    }

    public function setSubmissionRequirements(SubmissionRequirements $submissionRequirements): void
    {
        $this->submissionRequirements = $submissionRequirements;
        $submissionRequirements->setSession($this);
    }

    /**
     * @param Stage[] $stages
     */
    public function setStages(array $stages): void
    {
        $indexedStages = [];
        foreach ($stages as $stage) {
            $indexedStages[$stage->getName()->value] = $stage;
        }

        if (!empty($diff = array_diff(StageName::VALUES, array_keys($indexedStages)))) {
            throw new \DomainException(sprintf(
                'The following stages are not exists: %s. Valid stages are: %s.',
                implode(', ', $diff),
                implode(', ', StageName::VALUES)
            ));
        }

        $previousStage = null;
        foreach (StageName::VALUES as $stageValue) {
            if (!array_key_exists($stageValue, $indexedStages)) {
                throw new \DomainException(sprintf('Session stage "%s" not found in indexed stages.', $stageValue));
            }

            $stage = $indexedStages[$stageValue];

            // Check if the previous stage ended before the current stage starts
            if ($previousStage && $previousStage->getEndDate() >= $stage->getStartDate()) {
                throw new \DomainException(sprintf(
                    'The previous session stage "%s" must end before the start of the current stage "%s".',
                    $previousStage->getName()->value,
                    $stage->getName()->value
                ));
            }

            // Validate that the start date is before the end date
            if ($stage->getStartDate() >= $stage->getEndDate()) {
                throw new \DomainException(sprintf(
                    'Invalid date range for stage "%s": start date "%s" must be earlier than end date "%s".',
                    $stage->getName()->value,
                    $stage->getStartDate()->format('Y-m-d'),
                    $stage->getEndDate()->format('Y-m-d')
                ));
            }

            // Update the previous stage
            $previousStage = $stage;
        }

        $this->stages = new ArrayCollection(array_values($indexedStages));
    }
}