<?php

namespace App\Domain\User\Entity;

use App\Domain\Shared\Address\Address;
use App\Domain\Vote\Entity\VoteEntity;
use App\Infrastructure\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\Table(name: 'users')]
class UserEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(name: 'email', type: Types::STRING)]
    private string $email;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $birthDate;

    #[ORM\Column(name: 'password_hash', type: Types::STRING, length: 100)]
    private string $passwordHash;

    #[ORM\Embedded(class: Address::class, columnPrefix: 'living_address_')]
    private Address $livingAddress;

    #[ORM\Embedded(class: Address::class, columnPrefix: 'registration_address_')]
    private Address $registrationAddress;

    #[ORM\OneToMany(targetEntity: VoteEntity::class, mappedBy: 'user')]
    private Collection $votes;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $updatedAt;

    public function __construct(
        string $name,
        string $email,
        string $password,
        Address $livingAddress,
        Address $registeredAddress,
        \DateTime $birthDate,
    ) {
        $this->email = $email;
        $this->name = $name;

        $this->birthDate = $birthDate;
        $this->livingAddress = $livingAddress;
        $this->registrationAddress = $registeredAddress;

        $this->votes = new ArrayCollection();

        $this->createdAt = $now = new \DateTime();
        $this->updatedAt = $now;

        $this->setPassword($password);
    }

    public function setPassword(string $password): self
    {
        $this->passwordHash = sodium_crypto_pwhash_str(
            $password,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
        );

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->passwordHash;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAge(): int
    {
        return (new \DateTime())->diff($this->birthDate)->y;
    }

    public function getLivingAddress(): Address
    {
        return $this->livingAddress;
    }

    public function getRegistrationAddress(): Address
    {
        return $this->registrationAddress;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function getBirthDate(): \DateTime
    {
        return $this->birthDate;
    }
}
