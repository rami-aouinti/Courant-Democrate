<?php

declare(strict_types=1);

namespace App\Notification\Transport\Controller\Api\v1\Notification;

use App\Notification\Application\Service\Notifier;
use App\Notification\Domain\Repository\NotificationRepository;
use App\User\Domain\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class IndexController
 *
 * @OA\Tag(name="Notification")
 *
 * @package App\Notification
 */
#[AsController]
class IndexController
{
    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly Notifier $notifier
    ) {
    }

    /**
     * Get current user profile data, accessible only for 'IS_AUTHENTICATED_FULLY' users.
     *
     * @OA\Response(
     *     response=200,
     *     description="User profile data",
     *     @OA\JsonContent(
     *         ref=@Model(
     *             type=User::class,
     *             groups={"set.UserProfile"},
     *         ),
     *     ),
     *  )
     * @OA\Response(
     *     response=401,
     *     description="Invalid token (not found or expired)",
     *     @OA\JsonContent(
     *         type="object",
     *         example={"code": 401, "message": "JWT Token not found"},
     *         @OA\Property(property="code", type="integer", description="Error code"),
     *         @OA\Property(property="message", type="string", description="Error description"),
     *     ),
     * )
     *
     * @param User $loggedInUser
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param NotificationRepository $notificationRepository
     * @return JsonResponse
     */
    #[Route(
        path: '/v1/readNotification',
        methods: [Request::METHOD_PUT],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function readNotification(
        User $loggedInUser,
        EntityManagerInterface $entityManager,
        NotificationRepository $notificationRepository
    ): JsonResponse
    {
        $notifications = $notificationRepository->findBy([
           'user' => $loggedInUser,
           'readNotification'  => 0
        ]);;

        foreach ($notifications as $notification) {
            $notification->setReadNotification(true);
        }
        $entityManager->flush();
        return new JsonResponse(array('Notification' => 'readed'));
    }
}
