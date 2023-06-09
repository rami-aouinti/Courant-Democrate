<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Controller\Api\v1\Configuration;

use App\General\Transport\Rest\Controller;
use App\General\Transport\Rest\ResponseHandler;
use App\General\Transport\Rest\Traits\Actions;
use App\Quiz\Application\DTO\Configuration\ConfigurationCreate;
use App\Quiz\Application\DTO\Configuration\ConfigurationPatch;
use App\Quiz\Application\DTO\Configuration\ConfigurationUpdate;
use App\Quiz\Application\Resource\ConfigurationResource;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class ConfigurationController
 *
 * @OA\Tag(name="Quiz Management")
 *
 * @package App\Quiz
 *
 * @method ConfigurationResource getResource()
 * @method ResponseHandler getResponseHandler()
 */
#[AsController]
#[Route(
    path: '/v1/configuration',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class ConfigurationController extends Controller
{
    use Actions\User\CountAction;
    use Actions\User\FindAction;
    use Actions\User\FindOneAction;
    use Actions\User\IdsAction;
    use Actions\User\CreateAction;
    use Actions\User\PatchAction;
    use Actions\User\UpdateAction;

    public function __construct(
        ConfigurationResource $resource,
    ) {
        parent::__construct($resource);
    }

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => ConfigurationCreate::class,
        Controller::METHOD_UPDATE => ConfigurationUpdate::class,
        Controller::METHOD_PATCH => ConfigurationPatch::class,
    ];
}
