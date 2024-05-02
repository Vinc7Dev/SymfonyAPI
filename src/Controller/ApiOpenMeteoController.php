<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Service\OpenMeteoAPi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route("/api")]
class ApiOpenMeteoController extends AbstractController
{
    private OpenMeteoAPi $openMeteoAPi;
    private EntityManagerInterface $em;

    public function __construct(OpenMeteoAPi $openMeteoAPi, EntityManagerInterface $em)
    {
        $this->openMeteoAPi = $openMeteoAPi;
        $this->em = $em;
    }


    // Forecast for 7 days
    #[Route('/forecast', name: 'api_meteo_forecast', methods: "GET")]
    public function forecast(Request $request): JsonResponse
    {
        // Query (Params)
        $latitude = $request->query->get("latitude");
        $longitude = $request->query->get("longitude");

        $type = $request->query->get("type");


        // IF days is equal to 'week' we want 7 days, else we want 1, and 1 is for an day
        $days = ($type == "week" ? 7 : 1);


        // Request a forecast
        $forecast = $this->openMeteoAPi->get(
            $latitude,
            $longitude,
            $days
        );


        // Check if forecast exists (bool)
        $forecastCheck = $this->openMeteoAPi->find(
            $days,
            $longitude,
            $latitude,

        );


        if ($forecastCheck) {
            // Find forecast in database
            $forecast = $this->em->getRepository(Forecast::class)
                ->findOneBy([
                    "forecast_days" => $days,
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                ]);

            $this->openMeteoAPi->update($forecast);
        } else {
            // Add forecast to database
            $this->openMeteoAPi->create(
                $days,
                $forecast["hourly"]["temperature_2m"],
                $forecast["hourly"]["time"],
                $longitude,
                $latitude
            );
        }

        return $this->json($forecast);
    }

    #[Route('/forecast/update/all', name: 'app_api_open_meteo_all', methods: "GET")]
    public function updateall(): JsonResponse
    {
        $forecasts = $this->em->getRepository(Forecast::class)
            ->findAll();

        $totalForecasts = 0;

        foreach ($forecasts as $forecast) {
            $this->openMeteoAPi->update($forecast);
            $totalForecasts++;
        }


        return $this->json(["updated" => $totalForecasts]);
    }
}
