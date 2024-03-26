<?php

namespace App\Entity;

use App\Repository\RecipeProductsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Jms;

#[ORM\Entity(repositoryClass: RecipeProductsRepository::class)]
#[ORM\Table(name: 'recipes_products')]
class RecipeProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Jms\Type('integer')]
    #[ORM\Column]
    #[ORM\ManyToMany(targetEntity: Recipe::class)]
    #[ORM\JoinColumn(name: 'recipe_id', referencedColumnName: 'id')]
    #[Assert\GreaterThan(0, message: 'recipe_id must be int greater than 0')]
    #[Assert\NotBlank]
    private ?int $recipeId = null;

    #[Jms\Type('integer')]
    #[ORM\Column]
    #[ORM\ManyToMany(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0, message: 'product_id must be int greater than 0')]
    private ?int $productId = null;

    #[Jms\Type('integer')]
    #[ORM\Column(type: 'decimal', scale: 1, precision: 6)]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0, message: 'quantity must be int greater than 0')]
    private ?float $quantity = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string', message: 'This value must be string')]
    private ?string $unitOfMeasurement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipeId(): ?int
    {
        return $this->recipeId;
    }

    public function setRecipeId(?int $recipeId): void
    {
        $this->recipeId = $recipeId;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): void
    {
        $this->productId = $productId;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getUnitOfMeasurement(): ?string
    {
        return $this->unitOfMeasurement;
    }

    public function setUnitOfMeasurement(?string $unitOfMeasurement): void
    {
        $this->unitOfMeasurement = $unitOfMeasurement;
    }
}
