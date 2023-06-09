<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1\Quiz;

use App\General\Transport\Rest\Controller;
use App\General\Transport\Rest\ResponseHandler;
use App\General\Transport\Rest\Traits\Actions;
use App\Quiz\Application\DTO\Quiz\QuizCreate;
use App\Quiz\Application\DTO\Quiz\QuizPatch;
use App\Quiz\Application\DTO\Quiz\QuizUpdate;
use App\Quiz\Application\Resource\QuizResource;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class QuizController
 *
 * @OA\Tag(name="Quiz Management")
 *
 * @package App\Quiz
 *
 * @method QuizResource getResource()
 * @method ResponseHandler getResponseHandler()
 */
#[AsController]
#[Route(
    path: '/v1/quiz',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class QuizController extends Controller
{
    use Actions\User\CountAction;
    use Actions\User\FindAction;
    use Actions\User\FindOneAction;
    use Actions\User\IdsAction;
    use Actions\User\CreateAction;
    use Actions\User\PatchAction;
    use Actions\User\UpdateAction;

    public function __construct(
        QuizResource $resource,
    ) {
        parent::__construct($resource);
    }

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => QuizCreate::class,
        Controller::METHOD_UPDATE => QuizUpdate::class,
        Controller::METHOD_PATCH => QuizPatch::class,
    ];
}
