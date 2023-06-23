<?php

declare(strict_types=1);

namespace App\Politic\Transport\Controller\Api\v1\Office;

use App\General\Transport\Rest\Controller;
use App\General\Transport\Rest\ResponseHandler;
use App\General\Transport\Rest\Traits\Actions;
use App\Politic\Application\DTO\Office\OfficeCreate;
use App\Politic\Application\DTO\Office\OfficePatch;
use App\Politic\Application\DTO\Office\OfficeUpdate;
use App\Politic\Application\Resource\OfficeResource;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class OfficeController
 *
 * @OA\Tag(name="Office Management")
 *
 * @package App\Office
 *
 * @method OfficeResource getResource()
 * @method ResponseHandler getResponseHandler()
 */
#[AsController]
#[Route(
    path: '/v1/office',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class OfficeController extends Controller
{
    use Actions\Admin\CountAction;
    use Actions\Admin\FindAction;
    use Actions\Admin\FindOneAction;
    use Actions\Admin\IdsAction;
    use Actions\Root\CreateAction;
    use Actions\Root\PatchAction;
    use Actions\Root\UpdateAction;

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => OfficeCreate::class,
        Controller::METHOD_UPDATE => OfficeUpdate::class,
        Controller::METHOD_PATCH => OfficePatch::class,
    ];

    public function __construct(
        OfficeResource $resource,
    ) {
        parent::__construct($resource);
    }
}
