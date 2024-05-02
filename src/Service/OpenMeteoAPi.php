<?php

namespace App\Service;


use App\Entity\Forecast;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenMeteoAPi
{
    private HttpClientInterface $client;
    private EntityManagerInterface $em;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }

    // Get all forecasts and update them
    public function update(Forecast $forecast): void
    {
        // Get an new forecast
        $forecast_data = $this->get(
            $forecast->getLatitude(),
            $forecast->getLongitude(),
            $forecast->getForecastDays()
        );

        // Store an new forecast record
        $forecast->setForecastTemperatureHourly($forecast_data["hourly"]["temperature_2m"]);
        $forecast->setForecastTimeHourly($forecast_data["hourly"]["time"]);

        // Update timestamp
        $forecast->setDateAdded();

        // Flush
        $this->em->flush();
    }


    // Store the data in an database
    public function create(
        int $forecast_days,
        array $hourlyTemperature,
        array $hourlyTimes,
        float $longitude,
        float $latitude
    ): void {
        $forecast = new Forecast();

        // Set forecast days
        $forecast->setForecastDays($forecast_days);

        // Lat- and longitude
        $forecast->setLatitude($latitude);
        $forecast->setLongitude($longitude);

        // Tempratures
        $forecast->setForecastTemperatureHourly($hourlyTemperature);

        // Forecast times requested
        $forecast->setForecastTimeHourly($hourlyTimes);

        // Dates
        $forecast->setDateCreated();
        $forecast->setDateAdded();

        // Add to db
        $this->em->persist($forecast);
        $this->em->flush();
    }

    // Get Forecast for the requested days
    public function get(float $latitude, float $longitude, int $forecast_days): array
    {
        // Request the data
        $request = $this->client->request(
            'GET',
            "https://api.open-meteo.com/v1/forecast",
            [
                // Get attributes
                "query" => [
                    "forecast_days" => $forecast_days,
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                    "hourly" => "temperature_2m",
                ],
            ]
        );


        return json_decode($request->getContent(), 1);
    }

    // Return bool to check if the forecast for the gps cords already exists
    public function find(int $days, float $longitude, float $latitude): bool
    {
        $forecast = $this->em->getRepository(Forecast::class)
            ->findOneBy([
                "forecast_days" => $days,
                "latitude" => $latitude,
                "longitude" => $longitude,
            ]);

        return (bool)$forecast;
    }
}