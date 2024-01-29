<?php

namespace App\Dto;

class StationDto
{
    private ?string $name;

    private ?string $address;

    private ?float $longitude;

    private ?float $latitude;

    #[Assert\GreaterThanOrEqual(0)]
    private ?int $capacity;

    #[Assert\LessThanOrEqual(propertyPath:"capacity")]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $numberOfAvailableBicycles;

    private ?bool $status;

    private ?string $city;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getNumberOfAvailableBicycles(): ?int
    {
        return $this->numberOfAvailableBicycles;
    }

    public function setNumberOfAvailableBicycles(int $numberOfAvailableBicycles): static
    {
        $this->numberOfAvailableBicycles = $numberOfAvailableBicycles;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }
}
