<?php

namespace App\Controller;

use App\Dto\StationDto;
use App\Entity\Station;
use App\Repository\CityRepository;
use App\Repository\StationRepository;
use App\Service\MergeService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/station', format: 'json')]
class StationController extends AbstractController implements LoggerAwareInterface
{
    private LoggerInterface $logger;

    #[Route('', name: 'station_index', methods: ['GET'])]
    public function index(StationRepository $stationRepository, #[MapQueryParameter] string $cityId = null): JsonResponse
    {
        if ($cityId) {
            $this->logger->info('[Station] Get all stations filter by city id '.$cityId);
            $stations = $stationRepository->findBy(['city' => $cityId]);
        } else {
            $this->logger->info('[Station] Get all stations');
            $stations = $stationRepository->findAll();
        }

        return $this->json($stations, headers: ['X-Total-Count' => count($stations)]);
    }

    #[Route('', name: 'station_new', methods: ['POST'])]
    public function new(#[MapRequestPayload] Station $station, CityRepository $cityRepository, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $this->logger->info('[Station] Create new station');

        $city = $cityRepository->find($request->getPayload()->get('city'));
        $station->setCity($city);

        $entityManager->persist($station);
        $entityManager->flush();

        return $this->json($station);
    }

    #[Route('/{id}', name: 'station_show', methods: ['GET'])]
    public function show(Station $station): JsonResponse
    {
        $this->logger->info('[Station] Get station '.$station->getId());
        return $this->json($station);
    }

    #[Route('/{id}', name: 'station_edit', methods: ['PUT', 'PATCH'])]
    public function edit(#[MapRequestPayload] StationDto $stationDto, Uuid $id, EntityManagerInterface $entityManager, StationRepository $stationRepository, CityRepository $cityRepository, MergeService $mergeService): JsonResponse
    {
        $this->logger->info('[Station] Edit station '.$id);

        $station = $stationRepository->find($id);

        $mergeService->mergeObjects($stationDto, $station);

        if (!$station->isStatus()) {
            $station->setNumberOfAvailableBicycles(0);
        }

        if ($stationDto->getCity()) {
            $city = $cityRepository->find($stationDto->getCity());
            $station->setCity($city);
        }

        $entityManager->flush();

        return $this->json($station);
    }

    #[Route('/{id}', name: 'station_delete', methods: ['DELETE'])]
    public function delete(Station $station, EntityManagerInterface $entityManager): Response
    {
        $this->logger->info('[Station] Delete station '.$station->getId());
        $entityManager->remove($station);
        $entityManager->flush();

        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
