<?php

namespace App\Domain\Project\Entity;

use App\Domain\Project\Enum\ProjectAction;
use App\Domain\User\Entity\UserEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'project_history')]
class ProjectHistoryEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'history')]
    private ?ProjectEntity $project = null;

    #[ORM\Column(type: Types::SMALLINT, enumType: ProjectAction::class, options: ['unsigned' => true])]
    private ProjectAction $action;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $field;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $oldValue;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $newValue;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $createdAt;

    #[ORM\ManyToOne]
    private ?UserEntity $initiator = null;

    // =============================================

    public function __construct(
        ProjectAction $action,
        ?string $field = null,
        ?string $oldValue = null,
        ?string $newValue = null,
        ?UserEntity $initiator = null,
        ?\DateTime $createdAt = null
    )
    {
        $this->action = $action;
        $this->field = $field;
        $this->newValue = $newValue;
        $this->oldValue = $oldValue;
        $this->initiator = $initiator;

        $this->createdAt = $createdAt ?? new \DateTime();
    }

    public static function createAction(?UserEntity $user = null): static
    {
        return new self(action: ProjectAction::CREATE, initiator: $user);
    }

    public static function updateAction(string $field, mixed $oldValue, mixed $newValue, ?UserEntity $user = null): static
    {
        return new self(action: ProjectAction::CREATE, field: $field, oldValue: $oldValue, newValue: $newValue, initiator: $user);
    }

    // =============================================


    public function getProject(): ?ProjectEntity
    {
        return $this->project;
    }

    public function setProject(ProjectEntity $project): void
    {
        $this->project = $project;
    }
}