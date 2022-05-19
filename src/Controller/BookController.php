<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Trek;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\LevelRepository;
use App\Repository\StatusRepository;
use App\Repository\TrekRepository;
use App\Validator\TrekValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BookController extends AbstractController{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Security
     */
    private $security;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * Retourne la liste des treks
     *
     * @Route("/api/books", methods={"GET"})
     *
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function listBook(Request $request, NormalizerInterface $normalizer): JsonResponse
    {
        /** @var BookRepository $bookRepository */
        $bookRepository = $this->entityManager->getRepository("App\Entity\Book");

        $qb =  $bookRepository->listBook();

        $groups = [
            'id',
            'book',
            'book:trek',
            'trek:name',
            'trek:description',
            'book:users',
            'user:firstname',
            'user:lastname'
        ];

        $normalizeData = $normalizer->normalize(
            $qb,
            'json',
            ['groups' => $groups]
        );

        return $this->json($normalizeData);
    }

    /**
     * Créer une reservation
     *
     * @Route("/api/books", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function createBook(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");
        $trek = $trekRepository->find($data['trek']);

        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        $book = new Book();

        $book
            ->setDate(new DateTime($data['date']))
            ->setTrek($trek)
            ->addUser($user)
        ;

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return  $this->json($book);
    }

    /**
     * Créer une reservation
     *
     * @Route("/api/books/{idBook}", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function editBook(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        /** @var BookRepository $bookRepository */
        $bookRepository = $this->entityManager->getRepository("App\Entity\Book");
        $book = $bookRepository->find($request->attributes->get('idBook'));

        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        $book
            ->addUser($user)
        ;

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return  $this->json($book);
    }
}