<?php

declare(strict_types=1);

namespace App\Controller\Api\Profile;

use App\Entity\User;
use App\Security\RolesService;
use App\Utils\JSON;
use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class IndexController
 *
 * @OA\Tag(name="Profile")
 *
 * @package App\Controller\Api\Profile
 */
class IndexController
{
    public function __construct(
        private SerializerInterface $serializer,
        private RolesService $rolesService,
    ) {
    }

    /**
     * Get current user profile data, accessible only for 'IS_AUTHENTICATED_FULLY' users.
     *
     * @OA\Response(
     *      response=200,
     *      description="User profile data",
     *      @OA\JsonContent(
     *          ref=@Model(
     *              type=User::class,
     *              groups={"set.UserProfile"},
     *          ),
     *      ),
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
     * @throws JsonException
     */
    #[Route(
        path: '/profile',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function __invoke(User $loggedInUser): JsonResponse
    {
        /** @var array<string, string|array<string, string>> $output */
        $output = JSON::decode(
            $this->serializer->serialize($loggedInUser, 'json', ['groups' => User::SET_USER_PROFILE]),
            true,
        );
        /** @var array<int, string> $roles */
        $roles = $output['roles'];
        $output['roles'] = $this->rolesService->getInheritedRoles($roles);

        return new JsonResponse($output);
    }
}
