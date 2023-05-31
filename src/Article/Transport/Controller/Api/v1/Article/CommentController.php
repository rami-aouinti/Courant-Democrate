<?php

declare(strict_types=1);

namespace App\Article\Transport\Controller\Api\v1\Article;

use App\General\Transport\Rest\Controller;
use App\General\Transport\Rest\ResponseHandler;
use App\General\Transport\Rest\Traits\Actions;
use App\Article\Application\DTO\Comment\CommentCreate;
use App\Article\Application\DTO\Comment\CommentPatch;
use App\Article\Application\DTO\Comment\CommentUpdate;
use App\Article\Application\Resource\CommentResource;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class CommentController
 *
 * @OA\Tag(name="Article Management")
 *
 * @package App\Article
 *
 * @method CommentResource getResource()
 * @method ResponseHandler getResponseHandler()
 */
#[AsController]
#[Route(
    path: '/v1/comment',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class CommentController extends Controller
{
    use Actions\Comment\CountAction;
    use Actions\Comment\FindAction;
    use Actions\Comment\FindOneAction;
    use Actions\Comment\IdsAction;
    use Actions\Comment\CreateAction;
    use Actions\Comment\PatchAction;
    use Actions\Comment\UpdateAction;

    public function __construct(
        CommentResource $resource,
    ) {
        parent::__construct($resource);
    }

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => CommentCreate::class,
        Controller::METHOD_UPDATE => CommentUpdate::class,
        Controller::METHOD_PATCH => CommentPatch::class,
    ];
}
