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
        $price = $request->query->get("price");

        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");

        $qb =  $trekRepository->listTrek(
            $isEnabled,
            $idLevels,
            $price
        );

        $groups = [
            'id',
            'trek',
            'trek:status',
            'status',
            'trek:level',
            'level',
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

//        $this->trekValidator->verifyDataTrek($data);

        /** @var LevelRepository $levelRepository */
        $levelRepository = $this->entityManager->getRepository("App\Entity\Level");
        $level = $levelRepository->find('f6553fb4-83cb-42ba-9828-2fc37ca16649');

        /** @var StatusRepository $statusRepository */
        $statusRepository = $this->entityManager->getRepository("App\Entity\Status");
        $status = $statusRepository->find('fef1e5e9-497f-4869-a838-5190c031c268');

        $trek = new trek();

        $trek
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setDuration($data['duration'])
            ->setPrice($data['price'])
            ->setStatus($status)
            ->setLevel($level)
        ;
        $this->entityManager->persist($trek);
        $this->entityManager->flush();

        return  $this->json($trek->getId());
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

//        $this->trekValidator->verifyDataTrek($data);

        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\trek");
        $trek = $trekRepository->find($data['id']);

        /** @var LevelRepository $levelRepository */
        $levelRepository = $this->entityManager->getRepository("App\Entity\Level");
        $level = $levelRepository->find($data['level']);

        /** @var StatusRepository $statusRepository */
        $statusRepository = $this->entityManager->getRepository("App\Entity\Status");
        $status = $statusRepository->find('fef1e5e9-497f-4869-a838-5190c031c268');

        $trek
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setDuration($data['duration'])
            ->setPrice($data['price'])
            ->setStatus($status)
            ->setLevel($level)
        ;
        $this->entityManager->persist($trek);
        $this->entityManager->flush();

        return  $this->json($trek->getId());
    }
}