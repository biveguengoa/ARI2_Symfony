<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 30, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Username must be at least {{ limit }} characters long',
        maxMessage: 'Username title cannot be longer than {{ limit }} characters',
    )]
    private string $username;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 8,
        minMessage: 'Password must be at least {{ limit }} characters long'
    )]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'theUser', targetEntity: Book::class, orphanRemoval: true)]
    private Collection $userBooks;

    #[ORM\OneToMany(mappedBy: 'borrowedBy', targetEntity: Book::class)]
    private Collection $booksBorrowed;

    public function __construct()
    {
        $this->userBooks = new ArrayCollection();
        $this->booksBorrowed = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getUserBooks(): Collection
    {
        return $this->userBooks;
    }

    public function addUserBook(Book $userBook): static
    {
        if (!$this->userBooks->contains($userBook)) {
            $this->userBooks->add($userBook);
            $userBook->setTheUser($this);
        }

        return $this;
    }

    public function removeUserBook(Book $userBook): static
    {
        if ($this->userBooks->removeElement($userBook)) {
            // set the owning side to null (unless already changed)
            if ($userBook->getTheUser() === $this) {
                $userBook->setTheUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooksBorrowed(): Collection
    {
        return $this->booksBorrowed;
    }

    public function addBooksBorrowed(Book $booksBorrowed): static
    {
        if (!$this->booksBorrowed->contains($booksBorrowed)) {
            $this->booksBorrowed->add($booksBorrowed);
            $booksBorrowed->setBorrowedBy($this);
            $booksBorrowed->setAvailable(false);
            $booksBorrowed->setBorrowedAt(new \DateTimeImmutable());
        }

        return $this;
    }

    public function removeBooksBorrowed(Book $booksBorrowed): static
    {
        if ($this->booksBorrowed->removeElement($booksBorrowed)) {
            // set the owning side to null (unless already changed)
            if ($booksBorrowed->getBorrowedBy() === $this) {
                $booksBorrowed->setBorrowedBy(null);
                $booksBorrowed->setAvailable(true);
                $booksBorrowed->setBorrowedAt(null);
            }
        }

        return $this;
    }
}
