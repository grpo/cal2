<?php

namespace App\Entity;

use App\Repository\RecipeProductsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeProductsRepository::class)]
class RecipeProducts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[ORM\ManyToMany(targetEntity: Recipe::class)]
    #[ORM\JoinColumn(name: 'recipe_id', referencedColumnName: 'id')]
    private int $recipeId;
    #[ORM\Column]
    #[ORM\ManyToMany(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private int $productId;
    #[ORM\Column]
    private int $quantity;
    #[ORM\Column]
    private int $unitOfMeasurement;
    public function getId(): ?int
    {
        return $this->id;
    }
}
