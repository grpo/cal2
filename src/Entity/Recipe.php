<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $recipeName;
    #[ORM\Column]
    private string $description;
    #[ORM\Column]
    private string $preparationInstructions;

    public function getId(): ?int
    {
        return $this->id;
    }
}
