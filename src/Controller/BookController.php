<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Trek;
use App\Repository\LevelRepository;
use App\Repository\StatusRepository;
use App\Repository\TrekRepository;
use App\Validator\TrekValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BookController extends AbstractController{

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
     * Créer une reservation
     *
     * @Route("/api/books", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createBook(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");
        $trek = $trekRepository->find($data['trek']);


        $book = new Book();

        $book
            ->setDate(new DateTime($data['date']))
            ->setTrek($trek)
        ;

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return  $this->json($book);
    }
}