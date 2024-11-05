<?php

namespace App\Domain\Project\Entity;

use App\Domain\Project\Enum\ProjectStatus;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Shared\Address\Address;
use App\Domain\Shared\Enum\Category;
use App\Domain\User\Entity\UserEntity;
use App\Domain\Vote\Entity\VoteEntity;
use App\Infrastructure\Repository\ProjectsRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

#[ORM\Entity(repositoryClass: ProjectsRepository::class)]
#[ORM\Table(name: 'projects')]
class ProjectEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\ManyToOne(targetEntity: SessionEntity::class)]
    #[ORM\JoinColumn(name: 'session_id', referencedColumnName: 'id', nullable: false)]
    private SessionEntity $session;

    #[ORM\Embedded(class: Address::class, columnPrefix: 'address_')]
    private Address $address;

    #[ORM\Column(type: Types::DECIMAL)]
    private Money $budget;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private Category $category;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private ProjectStatus $status;

    #[ORM\ManyToOne(targetEntity: UserEntity::class)]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id', nullable: false)]
    private UserEntity $author;

    #[ORM\OneToMany(targetEntity: VoteEntity::class, mappedBy: 'project')]
    private Collection $votes;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $rejectedReason = null;

    #[ORM\ManyToOne(targetEntity: UserEntity::class)]
    #[ORM\JoinColumn(name: 'rejected_by_id', referencedColumnName: 'id', nullable: true)]
    private ?UserEntity $rejectedBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $rejectedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $updatedAt;

    public function __construct(
        string $title,
        string $description,
        Category $category,
        Address $address,
        Money $budget,
        UserEntity $author,
        SessionEntity $session,
    )
    {
        $this->description = $description;
        $this->title = $title;
        $this->author = $author;
        $this->address = $address;
        $this->budget = $budget;
        $this->category = $category;

        $this->session = $session;

        $this->votes = new ArrayCollection();

        $this->status = ProjectStatus::PENDING;

        $this->createdAt = $now = new DateTime();
        $this->updatedAt = $now;
    }

    //=============================================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }

    public function getRejectedReason(): ?string
    {
        return $this->rejectedReason;
    }

    public function getRejectedBy(): ?UserEntity
    {
        return $this->rejectedBy;
    }

    public function getRejectedAt(): ?DateTime
    {
        return $this->rejectedAt;
    }

    public function getAuthor(): UserEntity
    {
        return $this->author;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getBudget(): Money
    {
        return $this->budget;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function getSession(): SessionEntity
    {
        return $this->session;
    }

    //=============================================

    public function addVote(VoteEntity $vote): void
    {
        $this->votes[] = $vote;
    }

    //=============================================

    public function approve(): void
    {
        if ($this->isApproved()) {
            throw new \DomainException('Status already approved!');
        }

        $this->status = ProjectStatus::APPROVED;
    }

    public function reject(string $reason, DateTime $rejectedAt, ?UserEntity $by = null): void
    {
        if ($this->isRejected()) {
            throw new \DomainException('Status already rejected!');
        }

        $this->status = ProjectStatus::REJECTED;
        $this->rejectedReason = $reason;
        $this->rejectedBy = $by;
        $this->rejectedAt = $rejectedAt;
    }

    public function winner(): void
    {
        if ($this->isWinner()) {
            throw new \DomainException('Status already winner!');
        }
        $this->status = ProjectStatus::WINNER;
    }

    public function notWinner(): void
    {
        if ($this->isNotWinner()) {
            throw new \DomainException('Status already not a winner!');
        }
        $this->status = ProjectStatus::NOT_A_WINNER;
    }

    public function isApproved(): bool
    {
        return $this->status === ProjectStatus::APPROVED;
    }

    public function isWinner(): bool
    {
        return $this->status === ProjectStatus::WINNER;
    }

    public function isNotWinner(): bool
    {
        return $this->status === ProjectStatus::NOT_A_WINNER;
    }

    public function inReview(): bool
    {
        return $this->status === ProjectStatus::IN_REVIEW;
    }

    public function isPending(): bool
    {
        return $this->status === ProjectStatus::PENDING;
    }
    public function isVoting(): bool
    {
        return $this->status === ProjectStatus::VOTING;
    }

    public function isRejected(): bool
    {
        return $this->status === ProjectStatus::REJECTED;
    }

    public function setStatus(ProjectStatus $status): void
    {
        if (in_array($status, [ProjectStatus::REJECTED, ProjectStatus::APPROVED])) {
            throw new \InvalidArgumentException('You must use spec method');
        }

        if ($status === $this->status) {
            throw new \DomainException('Status already setted!');
        }

        $this->status = $status;
    }

    //=============================================

}
