<?php

namespace App\Domain\Session\Entity;

use App\Domain\City\Entity\CityEntity;
use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\Requirement\SubmissionRequirements;
use App\Domain\Session\Entity\Requirement\VotingRequirements;
use App\Domain\Session\Entity\Requirement\WinnerRequirements;
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

    #[ORM\OneToOne(targetEntity: WinnerRequirements::class, inversedBy: 'session')]
    #[ORM\JoinColumn(name: 'winner_requirements_id', referencedColumnName: 'session_id', nullable: false)]
    private WinnerRequirements $winnerRequirements;

    #[ORM\OneToOne(targetEntity: SubmissionRequirements::class, inversedBy: 'session')]
    #[ORM\JoinColumn(name: 'submission_requirements_id', referencedColumnName: 'session_id', nullable: false)]
    private SubmissionRequirements $submissionRequirements;

    #[ORM\OneToOne(targetEntity: VotingRequirements::class, inversedBy: 'session')]
    #[ORM\JoinColumn(name: 'voting_requirements_id', referencedColumnName: 'session_id', nullable: false)]
    private VotingRequirements $votingRequirements;

    #[ORM\OneToMany(targetEntity: ProjectEntity::class, mappedBy: 'session', cascade: ['persist', 'remove'])]
    private readonly Collection $projects;

    /** @var Collection<Stage>  */
    #[ORM\OneToMany(targetEntity: Stage::class, mappedBy: 'session', cascade: ['persist', 'remove'])]
    private Collection $stages;

    public function __construct(
        string $name,
        CityEntity $city,
        WinnerRequirements $winnerRequirements,
        SubmissionRequirements $submissionRequirements,
        VotingRequirements $votingRequirements
    )
    {
        $this->city = $city;
        $this->name = $name;
        $this->winnerRequirements = $winnerRequirements;
        $this->submissionRequirements = $submissionRequirements;
        $this->votingRequirements = $votingRequirements;

        $this->stages = new ArrayCollection();
        $this->projects = new ArrayCollection();
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

    public function addStage(Stage $stage): void
    {
        $this->stages[] = $stage;
    }

    public function getActiveStage(DateTime $date): Stage
    {
        foreach ($this->stages as $stage) {
            if($stage->isActive($date)) {
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
}