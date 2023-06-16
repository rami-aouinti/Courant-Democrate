<?php

declare(strict_types=1);

namespace App\Article\Transport\Controller\Api\v1\Article;

use App\Article\Application\Service\PostService;
use App\Article\Domain\Entity\Comment;
use App\Article\Domain\Entity\Post;
use App\General\Domain\Utils\JSON;
use App\Role\Application\Security\RolesService;
use App\User\Domain\Entity\User;
use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class IndexController
 *
 * @OA\Tag(name="Article")
 *
 * @package App\Article
 */
#[AsController]
class IndexController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly PostService $postService,
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
        path: '/v1/article',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    #[Cache(smaxage: 10)]
    public function __invoke(): JsonResponse
    {
        $posts = $this->postService->getPosts();

        $categories = [];
        foreach ($posts as $key => $post) {
            $categories[$key] = $this->serializer->normalize($post, null, [
                'groups' => Post::SET_USER_ARTICLE,
            ]);
            $categories[$key]['comments'] = $post->getComments();
            $categories[$key]['tags'] = $post->getTags();
        }

        /** @var array<string, string|array<string, string>> $output */
        $output = JSON::decode(
            $this->serializer->serialize(
                $categories,
                'json',
                [
                    'groups' => Post::SET_USER_ARTICLE,
                ]
            ),
            true,
        );

        return new JsonResponse($output);
    }

    /**
     * NOTE: The ParamConverter mapping is required because the route parameter
     * (postSlug) doesn't match any of the Doctrine entity properties (slug).
     *
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html#doctrine-converter
     */
    #[Route('/v1/comment/{postSlug}/new', name: 'comment_new', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function commentNew(
        User $loggedInUser,
        Request $request,
        EntityManagerInterface $entityManager,
        string $postSlug
    ): JsonResponse {
        $comment = new Comment();
        $comment->setContent($request->request->get('comment'));
        $comment->setAuthor($loggedInUser);
        $post = $this->postService->getPost($postSlug);
        $post->addComment($comment);

        $entityManager->persist($comment);
        $entityManager->flush();

        return new JsonResponse(array('comment' => $comment->getContent()));
    }
}
