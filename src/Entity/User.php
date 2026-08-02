<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: 'username')]
#[UniqueEntity(fields: 'idpIdentifier')]
class User implements UserInterface {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $idpIdentifier;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $username;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $firstname;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $lastname;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = ['ROLE_USER'];

    /**
     * @var Collection<Borrower>
     */
    #[ORM\ManyToMany(targetEntity: Borrower::class)]
    #[ORM\JoinTable]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(onDelete: 'CASCADE')]
    private Collection $associatedBorrowers;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
        $this->associatedBorrowers = new ArrayCollection();
    }

    public function getIdpIdentifier(): Uuid {
        return $this->idpIdentifier;
    }

    public function setIdpIdentifier(Uuid $idpIdentifier): User {
        $this->idpIdentifier = $idpIdentifier;
        return $this;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): User {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): User {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): User {
        $this->email = $email;
        return $this;
    }

    public function setRoles(array $roles): User {
        $this->roles = $roles;
        return $this;
    }

    public function getRoles(): array {
        return $this->roles;
    }

    public function eraseCredentials(): void { }

    public function getUserIdentifier(): string {
        return $this->username;
    }

    public function addAssociatedBorrower(Borrower $borrower): void {
        $this->associatedBorrowers->add($borrower);
    }

    public function removeAssociatedBorrower(Borrower $borrower): void {
        $this->associatedBorrowers->removeElement($borrower);
    }

    /**
     * @return Collection<Borrower>
     */
    public function getAssociatedBorrowers(): Collection {
        return $this->associatedBorrowers;
    }

    public function __toString(): string {
        return $this->username;
    }

    public function __serialize(): array {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername()
        ];
    }

    public function __unserialize(array $serialized) {
        $this->id = $serialized['id'];
        $this->username = $serialized['username'];
    }
}