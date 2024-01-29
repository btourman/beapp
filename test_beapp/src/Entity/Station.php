<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StationRepository::class)]
class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private float $longitude;

    #[ORM\Column]
    private float $latitude;

    #[ORM\Column(type: Types::TEXT)]
    private string $address;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    private int $capacity;

    #[ORM\Column(name: 'number_of_available_bicycles')]
    #[Assert\LessThanOrEqual(propertyPath:"capacity")]
    #[Assert\GreaterThanOrEqual(0)]
    private int $numberOfAvailableBicycles;

    #[ORM\Column]
    private bool $status;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'stations')]
    #[ORM\JoinColumn(nullable: false)]
    private City $city;

    public function __construct()
    {
        $this->status = false;
        $this->numberOfAvailableBicycles = 0;
        $this->capacity = 0;
    }

    public function getId(): Uuid
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

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getNumberOfAvailableBicycles(): int
    {
        return $this->numberOfAvailableBicycles;
    }

    public function setNumberOfAvailableBicycles(int $numberOfAvailableBicycles): static
    {
        $this->numberOfAvailableBicycles = $numberOfAvailableBicycles;

        return $this;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): static
    {
        $this->city = $city;

        return $this;
    }
}
