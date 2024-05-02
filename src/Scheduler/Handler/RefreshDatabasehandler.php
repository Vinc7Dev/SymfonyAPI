<?php

namespace App\Scheduler\Handler;

use App\Entity\Forecast;
use App\Scheduler\Message\RefreshDatabase;
use App\Service\OpenMeteoAPi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RefreshDatabasehandler
{

    private EntityManagerInterface $em;
    private OpenMeteoAPi $openMeteoAPi;

    public function __construct(EntityManagerInterface $em, OpenMeteoAPi $openMeteoAPi)
    {
        $this->em = $em;
        $this->openMeteoAPi = $openMeteoAPi;
    }


    public function __invoke(RefreshDatabase $message) : void
    {
        // Get all forecasts
        $forecasts = $this->em->getRepository(Forecast::class)
            ->findAll();

        // Update them all
        foreach ($forecasts as $forecast) {
            $this->openMeteoAPi->update($forecast);
        }
    }

}