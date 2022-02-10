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

class PriceController extends AbstractController{
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
     * Retourne le prix max et le prix min
     *
     * @Route("/api/prices", methods={"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPrices(Request $request): JsonResponse
    {
        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");

        $treks =  $trekRepository->findAll();

        $maxPrice = 0;

        foreach ($treks as $trek) {
            if ($trek->getPrice() > $maxPrice) {
                $maxPrice = $trek->getPrice();
            }
        }

        $minPrice = $maxPrice;

        foreach ($treks as $trek) {
            if($trek->getPrice() < $minPrice) {
                $minPrice  = $trek->getPrice();
            }
        }

        $data = [
            'maxPrice' => $maxPrice,
            'minPrice' => $minPrice
        ];

        return $this->json($data);
    }
}