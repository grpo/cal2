<?php

namespace App\Dto;

class ProductDto
{
    private ?int $id = null;

    private ?string $name = null;

    private ?string $brand = null;

    private ?string $description = null;

    private ?string $protein = null;

    private ?string $carbs = null;

    private ?string $fat = null;

    private ?string $amount = null;

    private ?string $measurementUnit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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

    public function getProtein(): ?string
    {
        return $this->protein;
    }

    public function setProtein(?string $protein): void
    {
        $this->protein = $protein;
    }

    public function getCarbs(): ?string
    {
        return $this->carbs;
    }

    public function setCarbs(?string $carbs): void
    {
        $this->carbs = $carbs;
    }

    public function getFat(): ?string
    {
        return $this->fat;
    }

    public function setFat(?string $fat): void
    {
        $this->fat = $fat;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): void
    {
        $this->amount = $amount;
    }

    public function getMeasurementUnit(): ?string
    {
        return $this->measurementUnit;
    }

    public function setMeasurementUnit(?string $measurementUnit): void
    {
        $this->measurementUnit = $measurementUnit;
    }
}
