<?php

namespace App\Domain\Session\Entity\Requirement;

use App\Domain\Session\Entity\SessionEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class BaseSessionRequirementEntity
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: SessionEntity::class)]
    #[ORM\JoinColumn(name: 'session_id', referencedColumnName: 'id', nullable: false)]
    private readonly SessionEntity $session;

    public function __construct(
        SessionEntity $session,
    ) {
        $this->session = $session;
    }

    public function getSession(): SessionEntity
    {
        return $this->session;
    }
}