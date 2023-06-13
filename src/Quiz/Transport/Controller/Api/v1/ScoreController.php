<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1;

use App\General\Domain\Utils\JSON;
use App\Quiz\Domain\Entity\Quiz;
use App\Quiz\Domain\Entity\Score;
use App\Quiz\Domain\Repository\Interfaces\QuizRepositoryInterface;
use App\Quiz\Domain\Repository\ScoreRepository;
use App\Quiz\Infrastructure\Repository\QuizRepository;
use App\Quiz\Transport\Form\ScoreType;
use App\Role\Application\Security\RolesService;
use App\User\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class IndexController
 *
 * @OA\Tag(name="Quiz Application")
 *
 * @package App\User
 */
#[AsController]
readonly class ScoreController
{

    /**
     * Get current user profile data, accessible only for 'IS_AUTHENTICATED_FULLY' users.
     *
     * @OA\Response(
     *     response=200,
     *     description="Quiz data",
     *     @OA\JsonContent(
     *         ref=@Model(
     *             type=Quiz::class,
     *             groups={"set.UserQuiz"},
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
        path: '/v1/score',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function index(ScoreRepository $scoreRepository, SerializerInterface $serializer): JsonResponse
    {

        $scores = $scoreRepository->findAll();

        $result = [];
        foreach ($scores as $key => $score) {
            $result[$key]['id'] = $score->getUser()->getId();
            $result[$key]['username'] = $score->getUser()->getUsername();
            $result[$key]['photo'] = $score->getUser()->getPhoto();
            $result[$key]['score'] = $score->getScore();
            $result[$key]['level'] = $score->getLevel();
        }


        /** @var array<string, string|array<string, string>> $output */
        $output = JSON::decode(
            $serializer->serialize(
                $result,
                'json',
                [
                    'groups' => Quiz::SET_USER_QUIZ,
                ]
            ),
            true,
        );

        return new JsonResponse($output);
    }
}
