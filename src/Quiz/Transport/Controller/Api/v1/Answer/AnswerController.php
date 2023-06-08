<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1\Answer;

use App\General\Transport\Rest\Controller;
use App\General\Transport\Rest\ResponseHandler;
use App\General\Transport\Rest\Traits\Actions;
use App\Quiz\Application\DTO\Answer\AnswerCreate;
use App\Quiz\Application\DTO\Answer\AnswerPatch;
use App\Quiz\Application\DTO\Answer\AnswerUpdate;
use App\Quiz\Application\Resource\AnswerResource;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class AnswerController
 *
 * @OA\Tag(name="Quiz Management")
 *
 * @package App\Quiz
 *
 * @method AnswerResource getResource()
 * @method ResponseHandler getResponseHandler()
 */
#[AsController]
#[Route(
    path: '/v1/answer',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class AnswerController extends Controller
{
    use Actions\User\CountAction;
    use Actions\User\FindAction;
    use Actions\User\FindOneAction;
    use Actions\User\IdsAction;
    use Actions\User\CreateAction;
    use Actions\User\PatchAction;
    use Actions\User\UpdateAction;

    public function __construct(
        AnswerResource $resource,
    ) {
        parent::__construct($resource);
    }

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => AnswerCreate::class,
        Controller::METHOD_UPDATE => AnswerUpdate::class,
        Controller::METHOD_PATCH => AnswerPatch::class,
    ];
}
