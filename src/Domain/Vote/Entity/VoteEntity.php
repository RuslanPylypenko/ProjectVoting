<?php

namespace App\Domain\Vote\Entity;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\User\Entity\UserEntity;
use App\Infrastructure\Repository\VotesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VotesRepository::class)]
#[ORM\Table(name: 'project_votes')]
class VoteEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ProjectEntity::class, cascade: ['persist'], inversedBy: 'votes')]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', nullable: false)]
    private readonly ProjectEntity $project;

    #[ORM\ManyToOne(targetEntity: UserEntity::class, inversedBy: 'votes', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private UserEntity $user;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $createdAt;

    // =============================================

    public function __construct(
        ProjectEntity $project,
        UserEntity $user,
        ?\DateTime $createdAt = null,
    ) {
        $this->user = $user;
        $this->setProject($project);

        $this->createdAt = $createdAt ?? new \DateTime();
    }

    public function getProject(): ProjectEntity
    {
        return $this->project;
    }

    public function getUser(): UserEntity
    {
        return $this->user;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setProject(ProjectEntity $project): void
    {
        $this->project = $project;
        $project->addVote($this);
    }

}
