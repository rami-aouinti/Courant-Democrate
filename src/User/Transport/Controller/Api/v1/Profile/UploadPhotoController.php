<?php

declare(strict_types=1);

namespace App\User\Transport\Controller\Api\v1\Profile;

use App\General\Domain\Utils\JSON;
use App\Role\Application\Security\RolesService;
use App\User\Domain\Entity\User;
use App\User\Transport\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class IndexController
 *
 * @OA\Tag(name="Profile")
 *
 * @package App\User
 */
#[AsController]
readonly class UploadPhotoController
{
    /**
     * @param SerializerInterface $serializer
     * @param RolesService $rolesService
     */
    public function __construct(
        private SerializerInterface $serializer,
        private RolesService        $rolesService,
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
     * @throws JsonException
     */
    #[Route(
        path: '/v1/user/photo',
        methods: [Request::METHOD_POST],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        User $loggedInUser,
        FileUploader $fileUploader
    ): JsonResponse
    {
        $uploadedFile = $request->files->get('photo');
        if ($uploadedFile) {
            $brochureFileName = $fileUploader->upload($uploadedFile);
            //$loggedInUser->setPhoto($brochureFileName);
            $loggedInUser->setPhoto($brochureFileName);
        }
        //$loggedInUser->setPhoto('husjjjjj');
        print_r($request->request->get('photo'));
        $em->persist($loggedInUser);
        $em->flush();
        return new JsonResponse(array('name' => $loggedInUser->getPhoto()));


    }
}
