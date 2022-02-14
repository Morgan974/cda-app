<?php

namespace App\Controller;

use App\Repository\TrekRepository;
use App\Validator\TrekVaValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DurationController extends AbstractController{
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
     * Retourne la durée max et la duration min
     *
     * @Route("/api/duration", methods={"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPrices(Request $request): JsonResponse
    {
        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");

        $treks =  $trekRepository->findAll();

        $maxDuration = 0;

        foreach ($treks as $trek) {
            if ($trek->getDuration() > $maxDuration) {
                $maxDuration = $trek->getDuration();
            }
        }

        $minDuration = $maxDuration;

        foreach ($treks as $trek) {
            if($trek->getDuration() < $minDuration) {
                $minDuration  = $trek->getDuration();
            }
        }

        $data = [
            'maxDuration' => $maxDuration,
            'minDuration' => $minDuration
        ];

        return $this->json($data);
    }
}