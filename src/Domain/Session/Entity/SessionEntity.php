<?php

namespace App\Domain\Session\Entity;

use App\Domain\City\Entity\CityEntity;
use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\Requirement\SubmissionRequirements;
use App\Domain\Session\Entity\Requirement\VotingRequirements;
use App\Domain\Session\Entity\Requirement\WinnerRequirements;
use App\Infrastructure\Repository\SessionRepository;
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

    /** @var Collection<string, Stage> */
    #[ORM\OneToMany(targetEntity: Stage::class, mappedBy: 'session', cascade: ['persist', 'remove'], indexBy: 'name')]
    private Collection $stages;

    public function __construct(
        string $name,
        CityEntity $city,
        array $stages,
        WinnerRequirements $winnerRequirements,
        VotingRequirements $votingRequirements,
        SubmissionRequirements $submissionRequirements,
    ) {
        $this->city = $city;
        $this->name = $name;

        $this->projects = new ArrayCollection();

        $this->stages = new ArrayCollection();
        foreach ($stages as $stage) {
            $this->addStage($stage);
        }

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

    public function getActiveStage(\DateTime $date): Stage
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

    public function addStage(Stage $stage): void
    {
        if (!$this->stages->contains($stage)) {
            $this->stages[] = $stage;
            $stage->setSession($this);
        }
    }

    public function removeStage(Stage $stage): void
    {
        if ($this->stages->removeElement($stage)) {
            if ($stage->getSession() === $this) {
                $stage->setSession(null);
            }
        }
    }

    public function getStartDate(): \DateTime
    {
        return $this->stages->first()->getStartDate();
    }

    public function getEndDate(): \DateTime
    {
        return $this->stages->last()->getEndDate();
    }
}
