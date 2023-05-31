<?php

declare(strict_types=1);

namespace App\Setting\Transport\Controller\Api\v1\Setting;

use App\General\Transport\Rest\Controller;
use App\General\Transport\Rest\ResponseHandler;
use App\General\Transport\Rest\Traits\Actions;
use App\Setting\Application\DTO\Setting\SettingCreate;
use App\Setting\Application\DTO\Setting\SettingPatch;
use App\Setting\Application\DTO\Setting\SettingUpdate;
use App\Setting\Application\Resource\SettingResource;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class SettingController
 *
 * @OA\Tag(name="Setting Management")
 *
 * @package App\User
 *
 * @method SettingResource getResource()
 * @method ResponseHandler getResponseHandler()
 */
#[AsController]
#[Route(
    path: '/v1/site',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class SettingController extends Controller
{
    use Actions\Setting\CountAction;
    use Actions\Setting\FindAction;
    use Actions\Setting\FindOneAction;
    use Actions\Setting\IdsAction;
    use Actions\Setting\CreateAction;
    use Actions\Setting\PatchAction;
    use Actions\Setting\UpdateAction;

    public function __construct(
        SettingResource $resource,
    ) {
        parent::__construct($resource);
    }

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => SettingCreate::class,
        Controller::METHOD_UPDATE => SettingUpdate::class,
        Controller::METHOD_PATCH => SettingPatch::class,
    ];
}
