<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1\Session;

use App\General\Transport\Rest\Controller;
use App\General\Transport\Rest\ResponseHandler;
use App\General\Transport\Rest\Traits\Actions;
use App\Quiz\Application\DTO\Session\SessionCreate;
use App\Quiz\Application\DTO\Session\SessionPatch;
use App\Quiz\Application\DTO\Session\SessionUpdate;
use App\Quiz\Application\Resource\SessionResource;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class SessionController
 *
 * @OA\Tag(name="Quiz Management")
 *
 * @package App\Quiz
 *
 * @method SessionResource getResource()
 * @method ResponseHandler getResponseHandler()
 */
#[AsController]
#[Route(
    path: '/v1/Session',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class SessionController extends Controller
{
    use Actions\User\CountAction;
    use Actions\User\FindAction;
    use Actions\User\FindOneAction;
    use Actions\User\IdsAction;
    use Actions\User\CreateAction;
    use Actions\User\PatchAction;
    use Actions\User\UpdateAction;

    public function __construct(
        SessionResource $resource,
    ) {
        parent::__construct($resource);
    }

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => SessionCreate::class,
        Controller::METHOD_UPDATE => SessionUpdate::class,
        Controller::METHOD_PATCH => SessionPatch::class,
    ];
}
