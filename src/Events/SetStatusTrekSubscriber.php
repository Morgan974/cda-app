<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Status;
use App\Entity\Trek;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SetStatusTrekSubscriber implements EventSubscriberInterface {


    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['SetStatusTrek', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     */
    public function SetStatusTrek(ViewEvent $event) {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($result instanceof Trek && $method === "POST") {
            /** @var StatusRepository $statusRepository */
            $statusRepository = $this->manager->getRepository(Status::class);
            $status = $statusRepository->findOneBy(array('isEnabled' => true));

            $result->setStatus($status);
        }
    }
}