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
    public function index(StationRepository $stationRepository, LoggerInterface $logger, #[MapQueryParameter] string $cityId = null): JsonResponse
    {
        $logger->info('Get all stations');

        if ($cityId) {
            return $this->json($stationRepository->findBy(['city' => $cityId]));
        }

        return $this->json($stationRepository->findAll());
    }

    #[Route('', name: 'station_new', methods: ['POST'])]
    public function new(#[MapRequestPayload] Station $station, CityRepository $cityRepository, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $city = $cityRepository->find($request->getPayload()->get('city'));
        $station->setCity($city);

        $entityManager->persist($station);
        $entityManager->flush();

        return $this->json($station);
    }

    #[Route('/{id}', name: 'station_show', methods: ['GET'])]
    public function show(Station $station): JsonResponse
    {
        return $this->json($station);
    }

    #[Route('/{id}', name: 'station_edit', methods: ['PUT', 'PATCH'])]
    public function edit(#[MapRequestPayload] StationDto $stationDto, Uuid $id, EntityManagerInterface $entityManager, StationRepository $stationRepository, CityRepository $cityRepository, MergeService $mergeService): JsonResponse
    {
        $this->logger->error('Edit station '.$id);

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
