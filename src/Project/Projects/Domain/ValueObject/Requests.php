<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\ValueObject;

use App\Project\Shared\Domain\Exception\RequestNotExistsException;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Projects\Domain\Collection\RequestCollection;
use App\Project\Projects\Domain\Entity\Request;
use App\Project\Projects\Domain\Exception\UserAlreadyHasPendingRequestException;

final class Requests
{
    private RequestCollection $requests;

    public function __construct(?RequestCollection $items = null)
    {
        if (null === $items) {
            $this->requests = new RequestCollection();
        } else {
            $this->requests = $items;
        }
    }

    public function ensureUserDoesNotHavePendingRequest(UserId $userId, ProjectId $projectId): void
    {
        /** @var Request $request */
        foreach ($this->requests as $request) {
            if ($request->isPending() && $request->getUserId()->isEqual($userId)) {
                throw new UserAlreadyHasPendingRequestException($userId->value, $projectId->value);
            }
        }
    }

    public function add(Request $request): self
    {
        $result = new self();
        $result->requests = $this->requests->add($request);

        return $result;
    }

    public function ensureRequestExists(RequestId $requestId): void
    {
        if (!$this->requests->hashExists($requestId->getHash())) {
            throw new RequestNotExistsException($requestId->value);
        }
    }

    public function getCollection(): RequestCollection
    {
        return $this->requests;
    }
}
