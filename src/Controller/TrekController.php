<?php

namespace App\Controller;

use App\Entity\Trek;
use App\Repository\LevelRepository;
use App\Repository\StatusRepository;
use App\Repository\TrekRepository;
use App\Validator\TrekValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TrekController extends AbstractController{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * Retourne la liste des treks
     *
     * @Route("/api/treks", methods={"GET"})
     *
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function listTrek(Request $request, NormalizerInterface $normalizer): JsonResponse
    {
        (bool) $isEnabled= $request->query->get("isEnabled");
        $idLevels = $request->query->get("idLevels");
        (float) $price = $request->query->get("price");
        (float) $duration = $request->query->get("duration");
        (string) $search = $request->query->get("search");

        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");

        $qb =  $trekRepository->listTrek(
            $isEnabled,
            $idLevels,
            $price,
            $duration,
            $search
        );

        $groups = [
            'id',
            'trek',
            'trek:status',
            'status',
            'trek:level',
            'level',
            'trek:book',
            'book'
        ];

        $normalizeData = $normalizer->normalize(
            $qb,
            'json',
            ['groups' => $groups]
        );

        return $this->json($normalizeData);
    }

    /**
     * Retourne un trek par rapport à son UUID
     *
     * @Route("/api/treks/{idTrek}", methods={"GET"})
     *
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function getTrek(Request $request, NormalizerInterface $normalizer): JsonResponse
    {
        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");

        $trek = $trekRepository->find($request->attributes->get('idTrek'));

        $groups = [
            'id',
            'trek',
            'trek:status',
            'status',
            'trek:level',
            'level',
        ];

        $normalizeData = $normalizer->normalize(
            $trek,
            'json',
            ['groups' => $groups]
        );

        return $this->json($normalizeData);
    }

    /**
     * Crée un trek
     *
     * @Route("/api/trek", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createTrek(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        /** @var LevelRepository $levelRepository */
        $levelRepository = $this->entityManager->getRepository("App\Entity\Level");
        $level = $levelRepository->find($data['level']);

        /** @var StatusRepository $statusRepository */
        $statusRepository = $this->entityManager->getRepository("App\Entity\Status");
        $statut = $statusRepository->findOneBy(['isEnabled' => 'true']);

        $trek = new trek();

        $trek
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setDuration($data['duration'])
            ->setPrice($data['price'])
            ->setDistance($data['distance'])
            ->setStatus($statut)
            ->setLevel($level)
        ;

        $this->entityManager->persist($trek);
        $this->entityManager->flush();

        return  $this->json($trek);
    }

    /**
     * éditer un trek
     *
     * @Route("/api/trek/{idTrek}", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editTrek(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");
        $trek = $trekRepository->find($request->attributes->get('idTrek'));

        /** @var LevelRepository $levelRepository */
        $levelRepository = $this->entityManager->getRepository("App\Entity\Level");
        $level = $levelRepository->find($data['level']);

        /** @var StatusRepository $statusRepository */
        $statusRepository = $this->entityManager->getRepository("App\Entity\Status");
        $statut = $statusRepository->findOneBy(['isEnabled' => 'true']);

        $trek
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setDuration($data['duration'])
            ->setPrice($data['price'])
            ->setDistance($data['distance'])
            ->setStatus($statut)
            ->setLevel($level)
        ;
        $this->entityManager->persist($trek);
        $this->entityManager->flush();

        return  $this->json($trek);
    }

    /**
     * Retourne un trek par rapport à son UUID
     *
     * @Route("/api/trek/{idTrek}", methods={"DELETE"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function removeTrek(Request $request): JsonResponse
    {
        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");

        $trek = $trekRepository->find($request->attributes->get('idTrek'));

        $this->entityManager->remove($trek);
        $this->entityManager->flush();

        return $this->json("remove object");
    }
}