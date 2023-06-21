<?php

declare(strict_types=1);

namespace App\Resume\Transport\Controller\Api\v1\Resume;

use App\General\Domain\Utils\JSON;
use App\Resume\Transport\Controller\Api\v1\Resume\Knp\Snappy\Pdf;
use App\Role\Application\Security\RolesService;
use App\User\Domain\Entity\User;
use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;


/**
 * Class IndexController
 *
 * @OA\Tag(name="Resume")
 *
 * @package App\Resume
 */
#[AsController]
readonly class IndexController
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
     * @param User $loggedInUser
     * @param \Knp\Snappy\Pdf $knpSnappyPdf
     * @return PdfResponse
     */
    #[Route(
        path: '/v1/resume',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function __invoke(User $loggedInUser, \Knp\Snappy\Pdf $knpSnappyPdf): PdfResponse
    {
        $knpSnappyPdf->generate(array('https://www.google.fr', 'https://www.knplabs.com', 'https://www.google.com'), 'file.pdf');

        print_r($knpSnappyPdf);
        return new PdfResponse(
            $knpSnappyPdf,
            'file.pdf'
        );

    }
}
