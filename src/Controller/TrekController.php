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

class TrekController extends AbstractController{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TrekValidator
     */
    private $trekValidator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param TrekValidator $trekValidator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TrekValidator $trekValidator
    ) {
        $this->entityManager = $entityManager;
        $this->trekValidator = $trekValidator;
    }

    /**
     *  Retourne la liste des améliorations QVT
     *
     * @Route("/api/treks", methods={"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listTrek(Request $request): JsonResponse
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

        return $this->json($qb);
    }

    /**
     *  Retourne la liste des améliorations QVT
     *
     * @Route("/api/treks/{idTrek}", methods={"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTrek(Request $request): JsonResponse
    {
        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");

        $trek = $trekRepository->find($request->attributes->get('idTrek'));

        return $this->json($trek);
    }

    /**
     *  Retourne la liste des améliorations QVT
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
     *  Retourne la liste des améliorations QVT
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