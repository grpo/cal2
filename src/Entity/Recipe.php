<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'recipes')]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $recipeName = null;
    #[ORM\Column(nullable: true)]
    private ?string $description = null;
    #[ORM\Column(nullable: true)]
    private ?string $preparationInstructions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipeName(): ?string
    {
        return $this->recipeName;
    }

    public function setRecipeName(?string $recipeName): void
    {
        $this->recipeName = $recipeName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPreparationInstructions(): ?string
    {
        return $this->preparationInstructions;
    }

    public function setPreparationInstructions(?string $preparationInstructions): void
    {
        $this->preparationInstructions = $preparationInstructions;
    }
}
