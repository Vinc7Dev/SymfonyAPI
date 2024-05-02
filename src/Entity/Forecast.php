<?php

namespace App\Entity;

use App\Repository\ForecastRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForecastRepository::class)]
class Forecast
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $forecast_days = null;
    #[ORM\Column]
    private ?float $latitude = null;
    #[ORM\Column]
    private ?float $longitude = null;
    #[ORM\Column(type: Types::ARRAY)]
    private array $forecast_time_hourly = [];
    #[ORM\Column(type: Types::ARRAY)]
    private array $forecast_temperature_hourly = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_added = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_created = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }


    public function getForecastDays(): ?int
    {
        return $this->forecast_days;
    }

    public function setForecastDays(int $forecast_days): static
    {
        $this->forecast_days = $forecast_days;

        return $this;
    }

    public function getForecastTimeHourly(): array
    {
        return $this->forecast_time_hourly;
    }

    public function setForecastTimeHourly(array $forecast_time_hourly): static
    {
        $this->forecast_time_hourly = $forecast_time_hourly;

        return $this;
    }

    public function getForecastTemperatureHourly(): array
    {
        return $this->forecast_temperature_hourly;
    }

    public function setForecastTemperatureHourly(array $forecast_temperature_hourly): static
    {
        $this->forecast_temperature_hourly = $forecast_temperature_hourly;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->date_added;
    }

    public function setDateAdded(): static
    {
        $this->date_added = new \DateTimeImmutable();

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(): static
    {
        $this->date_created = new \DateTimeImmutable();

        return $this;
    }
}
