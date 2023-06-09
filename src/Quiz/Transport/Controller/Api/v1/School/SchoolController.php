<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1\School;

use App\General\Transport\Rest\Controller;
use App\General\Transport\Rest\ResponseHandler;
use App\General\Transport\Rest\Traits\Actions;
use App\Quiz\Application\DTO\School\SchoolCreate;
use App\Quiz\Application\DTO\School\SchoolPatch;
use App\Quiz\Application\DTO\School\SchoolUpdate;
use App\Quiz\Application\Resource\SchoolResource;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class SchoolController
 *
 * @OA\Tag(name="Quiz Management")
 *
 * @package App\Quiz
 *
 * @method SchoolResource getResource()
 * @method ResponseHandler getResponseHandler()
 */
#[AsController]
#[Route(
    path: '/v1/school',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class SchoolController extends Controller
{
    use Actions\User\CountAction;
    use Actions\User\FindAction;
    use Actions\User\FindOneAction;
    use Actions\User\IdsAction;
    use Actions\User\CreateAction;
    use Actions\User\PatchAction;
    use Actions\User\UpdateAction;

    public function __construct(
        SchoolResource $resource,
    ) {
        parent::__construct($resource);
    }

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => SchoolCreate::class,
        Controller::METHOD_UPDATE => SchoolUpdate::class,
        Controller::METHOD_PATCH => SchoolPatch::class,
    ];
}
