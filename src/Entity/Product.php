<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $brand = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', length: 3, precision: 1)]
    #[Assert\NotBlank]
    #[Assert\LessThan(100)]
    #[Assert\PositiveOrZero]
    #[Type('float')]
    private ?float $protein = null;

    #[ORM\Column(type: 'decimal', length: 3, precision: 1)]
    #[Assert\NotBlank]
    #[Assert\LessThan(100)]
    #[Assert\PositiveOrZero]
    #[Type('float')]
    private ?float $carbs = null;

    #[ORM\Column(type: 'decimal', length: 3, precision: 1)]
    #[Assert\NotBlank]
    #[Assert\LessThan(100)]
    #[Assert\PositiveOrZero]
    #[Type('float')]
    private ?float $fat = null;

    #[ORM\Column(type: 'decimal', length: 3, precision: 1)]
    #[Assert\NotBlank]
    #[Assert\LessThan(100)]
    #[Assert\PositiveOrZero]
    #[Type('float')]
    private ?float $sugar = null;

    #[ORM\Column(type: 'decimal', length: 5, precision: 1)]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    #[Type('integer')]
    private ?int $amount = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $isLiquid = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): void
    {
        $this->brand = $brand;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getProtein(): ?float
    {
        return $this->protein;
    }

    public function setProtein(?float $protein): void
    {
        $this->protein = $protein;
    }

    public function getCarbs(): ?float
    {
        return $this->carbs;
    }

    public function setCarbs(?float $carbs): void
    {
        $this->carbs = $carbs;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat(?float $fat): void
    {
        $this->fat = $fat;
    }

    public function getSugar(): ?float
    {
        return $this->sugar;
    }

    public function setSugar(?float $sugar): void
    {
        $this->sugar = $sugar;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    public function getIsLiquid(): bool
    {
        return $this->isLiquid;
    }

    public function setIsLiquid(bool $isLiquid): void
    {
        $this->isLiquid = $isLiquid;
    }
}
