<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1;

use App\General\Domain\Utils\JSON;
use App\Quiz\Domain\Entity\Quiz;
use App\Quiz\Domain\Repository\Interfaces\QuizRepositoryInterface;
use App\Quiz\Infrastructure\Repository\QuizRepository;
use App\Role\Application\Security\RolesService;
use App\User\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Doctrine\Persistence\ManagerRegistry;
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
 * @OA\Tag(name="Quiz Application")
 *
 * @package App\User
 */
#[AsController]
readonly class IndexController
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
        path: '/v1/application',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function index(ManagerRegistry $entityManager, SerializerInterface $serializer): JsonResponse
    {
        //$repository = new QuizRepository($entityManager);

        $query = $entityManager->getManager()->createQuery(
            'SELECT p
            FROM App\Quiz\Domain\Entity\Quiz p'
        );



        /** @var array<string, string|array<string, string>> $output */
        $output = JSON::decode(
            $serializer->serialize(
                $query->getResult(),
                'json',
                [
                    'groups' => Quiz::SET_USER_QUIZ,
                ]
            ),
            true,
        );

        return new JsonResponse($output);
    }

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
        path: '/v1/application/test',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function getQuizByCategory(ManagerRegistry $entityManager, SerializerInterface $serializer): JsonResponse
    {
        //$repository = new QuizRepository($entityManager);



        $qb = $entityManager->getManager()->createQueryBuilder();
        $qb
            ->select('q', 'l.id')
            ->from('App\Quiz\Domain\Entity\Quiz', 'q')
            ->leftJoin(
                'App\Quiz\Domain\Entity\Language',
                'l',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'q.language = l.id'
            );



        /** @var array<string, string|array<string, string>> $output */
        $output = JSON::decode(
            $serializer->serialize(
                $qb->getQuery()->getResult(),
                'json',
                [
                    'groups' => Quiz::SET_USER_QUIZ,
                ]
            ),
            true,
        );

        return new JsonResponse($output);
    }

    #[Route(
        path: '/v1/application/test',
        methods: [Request::METHOD_POST],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function create(Request $request)
    {

        print_r($request->request->get('language'));
    }


}
