<?php

namespace App\Notification\Application\Service;

use App\Notification\Domain\Entity\Notification;
use App\Notification\Domain\Repository\NotificationRepository;
use App\User\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class Notifier
{
    /**
     * @var NotificationRepository
     */
    private NotificationRepository $notificationRepository;

    private UserRepository $userRepository;

    private EntityManagerInterface $entityManager;

    /**
     * @param NotificationRepository $notificationRepository
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(NotificationRepository $notificationRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function sendNotification($name, $type, $path): void
    {

        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $notification = new Notification();
            $notification->setName($name);
            $notification->setReadNotification(false);
            $notification->setType($type);
            $notification->setPath($path);
            $user->addNotification($notification);

            $this->entityManager->persist($notification);
        }
        $this->entityManager->flush();
    }

}
