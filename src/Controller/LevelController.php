<?php

namespace App\Controller;

use App\Entity\Trek;
use App\Repository\LevelRepository;
use App\Repository\TrekRepository;
use App\Validator\TrekVaValidator;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LevelController extends AbstractController{
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
     * Retourne la liste des levels
     *
     * @Route("/api/levels", methods={"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listLevel(Request $request): JsonResponse
    {
        /** @var LevelRepository $levelRepository */
        $levelRepository = $this->entityManager->getRepository("App\Entity\Level");

        $qb =  $levelRepository->listLevel();

        return $this->json($qb);
    }
}