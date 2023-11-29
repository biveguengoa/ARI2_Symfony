<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[UniqueEntity('name')]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: 'Recipe name must be at least {{ limit }} characters long',
        maxMessage: 'Recipe name cannot be longer than {{ limit }} characters',
    )]
    private string $name;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(720)]
    private ?int $duration;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(30)]
    private ?int $nb_person;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThanOrEqual(5)]
    private ?int $difficulty;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private string $description;

    #[ORM\Column]
    #[Assert\NotNull]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToMany(targetEntity: Ingredient::class)]
    private Collection $ingredients;

    public function __construct() {
        $this->createdAt = new \DateTimeImmutable();
        $this->ingredients = new ArrayCollection();
    }

    public function getIngredients(): Collection {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): void {
        $this->ingredients->add($ingredient);
    }

    public function removeIngredient(Ingredient $ingredient): void {
        $this->ingredients->removeElement($ingredient);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getNbPerson(): int
    {
        return $this->nb_person;
    }

    public function setNbPerson(int $nb_person): static
    {
        $this->nb_person = $nb_person;

        return $this;
    }

    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}
