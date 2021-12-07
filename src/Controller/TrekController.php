<?php

namespace App\Controller;

use App\Entity\Trek;
use App\Repository\TrekRepository;
use App\Validator\TrekVaValidator;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
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
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     *  Retourne la liste des améliorations QVT
     *
     * @Route("/api/list-trek", methods={"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listTrek(Request $request): JsonResponse
    {
        (bool) $isEnabled= $request->query->get("isEnabled");

        dump($isEnabled);

        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");

        $qb =  $trekRepository->listTrek(
            $isEnabled
        );

        return $this->json($qb);
    }

    /**
     *  Retourne la liste des améliorations QVT
     *
     * @Route("/api/list-trek-enabled", methods={"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listTrekEnabled(Request $request): JsonResponse
    {
        dump($request);

        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");

        $qb =  $trekRepository->listTrek(
            true
        );

        return $this->json($qb);
    }
}