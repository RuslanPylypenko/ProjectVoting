<?php

namespace App\Domain\Project\DTO;

use App\Domain\Project\Entity\ProjectEntity;

class ProjectDTO
{
    public int $id;
    public string $title;
    public string $description;

    public function __construct(int $id, string $title, string $description)
    {
        $this->description = $description;
        $this->id = $id;
        $this->title = $title;
    }

    public static function fromEntity(ProjectEntity $project): self
    {
        return new self(
            $project->getId(),
            $project->getTitle(),
            $project->getDescription()
        );
    }
}
