<?php

declare(strict_types=1);

namespace App\Article\Transport\Controller\Api\v1\Article;

use App\General\Transport\Rest\Controller;
use App\General\Transport\Rest\ResponseHandler;
use App\General\Transport\Rest\Traits\Actions;
use App\Article\Application\DTO\Post\PostCreate;
use App\Article\Application\DTO\Post\PostPatch;
use App\Article\Application\DTO\Post\PostUpdate;
use App\Article\Application\Resource\PostResource;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class PostController
 *
 * @OA\Tag(name="Article Management")
 *
 * @package App\Article
 *
 * @method PostResource getResource()
 * @method ResponseHandler getResponseHandler()
 */
#[AsController]
#[Route(
    path: '/v1/post',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class PostController extends Controller
{
    use Actions\Post\CountAction;
    use Actions\Post\FindAction;
    use Actions\Post\FindOneAction;
    use Actions\Post\IdsAction;
    use Actions\Post\CreateAction;
    use Actions\Post\PatchAction;
    use Actions\Post\UpdateAction;

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => PostCreate::class,
        Controller::METHOD_UPDATE => PostUpdate::class,
        Controller::METHOD_PATCH => PostPatch::class,
    ];

    public function __construct(
        PostResource $resource,
    ) {
        parent::__construct($resource);
    }


}
