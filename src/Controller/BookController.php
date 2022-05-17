<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Repository\TrekRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

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
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * Créer une reservation
     *
     * @Route("/api/books", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createBooking(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        /** @var TrekRepository $trekRepository */
        $trekRepository = $this->entityManager->getRepository("App\Entity\Trek");

        $trek = $trekRepository->find($data['trek']);

        $book = new Book();

        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        $book
            ->setBooking(new \DateTime())
            ->setTrek($trek)
            ->addUser($user)
        ;

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return  $this->json($book);
    }

    /**
     * Editer une reservation
     *
     * @Route("/api/books/{idBook}", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editUserBooking(Request $request): JsonResponse
    {
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

    /**
     * Editer une reservation
     *
     * @Route("/api/books/{idBook}", methods={"REMOVE"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function removeUserBooking(Request $request): JsonResponse
    {
        /** @var BookRepository $bookRepository */
        $bookRepository = $this->entityManager->getRepository("App\Entity\Book");
        $book = $bookRepository->find($request->attributes->get('idBook'));

        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        $book
            ->removeUser($user)
        ;

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return  $this->json($book);
    }
}