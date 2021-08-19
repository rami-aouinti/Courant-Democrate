<?php

declare(strict_types=1);

namespace App\Controller\Api\UserGroup;

use App\Entity\User;
use App\Entity\UserGroup;
use App\Resource\UserGroupResource;
use App\Resource\UserResource;
use App\Rest\ResponseHandler;
use App\Security\RolesService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * Class UsersController
 *
 * @OA\Tag(name="UserGroup Management")
 *
 * @package App\Controller\Api\UserGroup
 */
class UsersController
{
    public function __construct(
        private UserResource $userResource,
        private ResponseHandler $responseHandler,
    ) {
    }

    /**
     * List specified user group users, accessible only for 'ROLE_ADMIN' users.
     *
     * @ParamConverter(
     *      "userGroup",
     *      class="App\Resource\UserGroupResource",
     *  )
     *
     * @OA\Response(
     *     response=200,
     *     description="User group users",
     *     @OA\JsonContent(
     *         ref=@Model(
     *             type=User::class,
     *             groups={"User", "User.userGroups", "User.roles", "UserGroup", "UserGroup.role"},
     *         ),
     *     ),
     * )
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
     * @OA\Response(
     *     response=404,
     *     description="User Group not found",
     *  )
     *
     * @throws Throwable
     */
    #[Route(
        path: '/user_group/{userGroup}/users',
        requirements: [
            'userGroup' => '%app.uuid_v1_regex%',
        ],
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(RolesService::ROLE_ADMIN)]
    #[ParamConverter(
        data: 'userGroup',
        class: UserGroupResource::class,
    )]
    public function __invoke(Request $request, UserGroup $userGroup): Response
    {
        return $this->responseHandler
            ->createResponse($request, $this->userResource->getUsersForGroup($userGroup), $this->userResource);
    }
}
