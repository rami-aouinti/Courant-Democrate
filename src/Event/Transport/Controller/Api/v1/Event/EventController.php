<?php

declare(strict_types=1);

namespace App\Event\Transport\Controller\Api\v1\Event;

use App\General\Transport\Rest\Controller;
use App\General\Transport\Rest\ResponseHandler;
use App\General\Transport\Rest\Traits\Actions;
use App\Event\Application\DTO\Event\EventCreate;
use App\Event\Application\DTO\Event\EventPatch;
use App\Event\Application\DTO\Event\EventUpdate;
use App\Event\Application\Resource\EventResource;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class EventController
 *
 * @OA\Tag(name="Event Management")
 *
 * @package App\Event
 *
 * @method EventResource getResource()
 * @method ResponseHandler getResponseHandler()
 */
#[AsController]
#[Route(
    path: '/v1/event',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class EventController extends Controller
{
    use Actions\Setting\CountAction;
    use Actions\Setting\FindAction;
    use Actions\Setting\FindOneAction;
    use Actions\Setting\IdsAction;
    use Actions\Setting\CreateAction;
    use Actions\Setting\PatchAction;
    use Actions\Setting\UpdateAction;

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => EventCreate::class,
        Controller::METHOD_UPDATE => EventUpdate::class,
        Controller::METHOD_PATCH => EventPatch::class,
    ];

    public function __construct(
        EventResource $resource,
    ) {
        parent::__construct($resource);
    }
}
