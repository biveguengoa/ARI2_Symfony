<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book {

    public const CATEGORIES = [
        'Science-Fiction' => 'Science-Fiction',
        'Mystère/Thriller' => 'Mystère/Thriller',
        'Romance' => 'Romance',
        'Fantasy' => 'Fantasy',
        'Poésie' => 'Poésie',
        'Non-Fiction' => 'Non-Fiction',
        'Aventure' => 'Aventure',
        'Manga' => 'Manga',
        'BD' => 'BD',
        'Autres' => 'Autres',
    ];
    

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Book title must be at least {{ limit }} characters long',
        maxMessage: 'Book title cannot be longer than {{ limit }} characters',
    )]
    private string $title;

    #[ORM\Column]
    #[Assert\NotNull]
    private bool $available;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $borrowedAt = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    #[Assert\Choice(
        choices : Book::CATEGORIES, 
        message: 'Choose valid categories',
        multiple: true,
        min : 1,
        minMessage : 'You must select at least {{ limit }} category',
    )]
    private array $categories;

    #[ORM\ManyToOne(inversedBy: 'userBooks')]
    private User $theUser;

    #[ORM\ManyToOne(inversedBy: 'booksBorrowed')]
    private ?User $borrowedBy = null;

    public function __construct() {
        $this->categories = [];
        $this->available = true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }


    public function formatCategories(): string
    {
        $category_toString = "";
        for ($i = 0; $i < count($this->categories); ++$i) {
            if ($i === count($this->categories) -1) {
                $category_toString .= $this->categories[$i];
            }
            else {
                $category_toString .= $this->categories[$i] .", ";
            }
        }
        return $category_toString;
    }

    public function getCategories(): array {
        return $this->categories;
    }

    public function setCategories(array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBorrowedAt(): \DateTimeImmutable
    {
        return $this->borrowedAt;
    }

    public function setBorrowedAt(?\DateTimeImmutable $borrowedAt): static
    {
        $this->borrowedAt = $borrowedAt;

        return $this;
    }

    public function getTheUser(): ?User
    {
        return $this->theUser;
    }

    public function setTheUser(?User $theUser): static
    {
        $this->theUser = $theUser;

        return $this;
    }

    public function getBorrowedBy(): ?User
    {
        return $this->borrowedBy;
    }

    public function setBorrowedBy(?User $borrowedBy): static
    {
        $this->borrowedBy = $borrowedBy;

        return $this;
    }

}
