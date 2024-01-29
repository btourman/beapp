<?php

namespace App\Controller;

use App\Dto\CityDto;
use App\Entity\City;
use App\Entity\Station;
use App\Repository\CityRepository;
use App\Service\MergeService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/city', format: 'json')]
class CityController extends AbstractController implements LoggerAwareInterface
{
    private LoggerInterface $logger;

    #[Route('', name: 'city_index', methods: ['GET'])]
    public function index(CityRepository $cityRepository, LoggerInterface $logger): JsonResponse
    {
        $logger->info('Get all city');

        $cities = $cityRepository->findAll();

        return $this->json($cities, headers: ['X-Total-Count' => count($cities)]);
    }

    #[Route('', name: 'city_new', methods: ['POST'])]
    public function new(#[MapRequestPayload] City $city, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->persist($city);
        $entityManager->flush();

        return $this->json($city);
    }

    #[Route('/{id}', name: 'city_show', methods: ['GET'])]
    public function show(City $city): JsonResponse
    {
        return $this->json($city);
    }

    #[Route('/{id}', name: 'city_edit', methods: ['PUT', 'PATCH'])]
    public function edit(#[MapRequestPayload] CityDto $cityDto, Uuid $id, EntityManagerInterface $entityManager, CityRepository $cityRepository, MergeService $mergeService): JsonResponse
    {
        $this->logger->error('Edit city '.$id);

        $updatedObject = $cityRepository->find($id);

        $mergeService->mergeObjects($cityDto, $updatedObject);

        $this->logger->info($updatedObject->getName());

        if (!$updatedObject->isStatus()) {
            $updatedObject->getStations()->forAll(function ($id, Station $station) {
                $station->setNumberOfAvailableBicycles(0);
                $station->setStatus(false);
            });
        }

        $entityManager->flush();

        return $this->json($updatedObject);
    }

    #[Route('/{id}', name: 'city_delete', methods: ['DELETE'])]
    public function delete(City $city, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($city);
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
