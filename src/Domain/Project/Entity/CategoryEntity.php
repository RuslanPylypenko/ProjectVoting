<?php

namespace App\Domain\Project\Entity;

use App\Infrastructure\Repository\ProjectCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectCategoryRepository::class)]
class CategoryEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: ProjectEntity::class, mappedBy: 'categories')]
    private Collection $projects;

    //=============================================

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    //=============================================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    //=============================================

    /**
     * @return Collection<int, ProjectEntity>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(ProjectEntity $project): void
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addCategory($this);
        }
    }

    public function removeProjectEntity(ProjectEntity $project): void
    {
        if ($this->projects->removeElement($project)) {
            $project->removeCategory($this);
        }
    }

    //=============================================
}
