<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $brand;

    #[ORM\Column(length: 10)]
    private string $description;

    #[ORM\Column(length: 10)]
    private string $protein;

    #[ORM\Column(length: 10)]
    private string $carbs;

    #[ORM\Column(length: 10)]
    private string $fat;

    #[ORM\Column(length: 10)]
    private string $amount;

    #[ORM\Column(length: 10)]
    private string $measurementUnit;


    public function getId(): int
    {
        return $this->id;
    }
}
