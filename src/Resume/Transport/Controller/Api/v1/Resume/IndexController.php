<?php

declare(strict_types=1);

namespace App\Resume\Transport\Controller\Api\v1\Resume;

use App\Pdf\Application\Service\PdfService;
use App\Role\Application\Security\RolesService;
use App\User\Domain\Entity\User;
use Dompdf\Dompdf;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use setasign\Fpdi\Tcpdf\Fpdi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class IndexController
 *
 * @OA\Tag(name="Resume")
 *
 * @package App\Resume
 */
#[AsController]
class IndexController extends AbstractController
{

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
     */
    #[Route(
        path: '/v1/resume',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function __invoke(User $loggedInUser, PdfService $pdfService): Response
    {
        $data = [];
        $data['name'] = 'test';
        $data['path'] = "pdf/test.pdf";
        $pdfService->createDocument($loggedInUser, $data);

        /** @var Dompdf $dompdf */
        return new Response (
            "Success",
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }

    private function imageToBase64($path) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
