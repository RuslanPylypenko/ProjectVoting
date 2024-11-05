<?php

namespace App\Domain\City\Entity;

use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Shared\Address\Address;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'cities')]
class CityEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $title;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $slug;

    #[ORM\Embedded(class: Address::class, columnPrefix: 'address_')]
    private Address $address;

    #[ORM\OneToMany(targetEntity: SessionEntity::class, mappedBy: 'city', cascade: ['persist', 'remove'])]
    private Collection $sessions;

    //=============================================

    public function __construct(
        string $title,
        string $slug,
        Address $address,
    ) {
        $this->address = $address;
        $this->title = $title;
        $this->slug = $slug;

        $this->sessions = new ArrayCollection();
    }

    //=============================================

    public function getId(): int
    {
        return $this->id;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getCurrentSession(?DateTime $date = null): SessionEntity
    {
        $date = $date ?? new DateTime();
        foreach ($this->sessions as $session) {
            if ($session->getActiveStage($date)) {
                return $session;
            }
        }

        return $this->sessions->last();
    }
}
