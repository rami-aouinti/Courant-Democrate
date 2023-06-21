<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\Entity;

use App\Project\Shared\Domain\Aggregate\AggregateRoot;
use App\Project\Shared\Domain\Event\Projects\ProjectInformationWasChangedEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectOwnerWasChangedEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectParticipantWasAddedEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectParticipantWasRemovedEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectStatusWasChangedEvent;
use App\Project\Shared\Domain\Event\Projects\ProjectWasCreatedEvent;
use App\Project\Shared\Domain\Event\Requests\RequestStatusWasChangedEvent;
use App\Project\Shared\Domain\Event\Requests\RequestWasCreatedEvent;
use App\Project\Shared\Domain\ValueObject\Owner;
use App\Project\Shared\Domain\ValueObject\Participants;
use App\Project\Shared\Domain\ValueObject\Projects\ActiveProjectStatus;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectStatus;
use App\Project\Shared\Domain\ValueObject\Requests\RequestStatus;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;
use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Projects\Domain\Exception\InsufficientPermissionsToChangeProjectParticipantException;
use App\Project\Projects\Domain\ValueObject\ProjectInformation;
use App\Project\Projects\Domain\ValueObject\ProjectTaskId;
use App\Project\Projects\Domain\ValueObject\ProjectTasks;
use App\Project\Projects\Domain\ValueObject\RequestId;
use App\Project\Projects\Domain\ValueObject\Requests;
use Exception;

final class Project extends AggregateRoot
{
    public function __construct(
        private readonly ProjectId $id,
        private ProjectInformation $information,
        private ProjectStatus      $status,
        private Owner              $owner,
        private Participants       $participants,
        private ProjectTasks       $tasks,
        private Requests           $requests
    ) {
    }

    public static function create(
        ProjectId $id,
        ProjectInformation $information,
        Owner $owner
    ): self {
        $status = new ActiveProjectStatus();
        $project = new self(
            $id,
            $information,
            $status,
            $owner,
            new Participants(),
            new ProjectTasks(),
            new Requests()
        );

        $project->registerEvent(new ProjectWasCreatedEvent(
            $id->value,
            $information->name->value,
            $information->description->value,
            $information->finishDate->getValue(),
            (string) $status->getScalar(),
            $owner->userId
        ));

        return $project;
    }

    public function changeInformation(
        ProjectInformation $information,
        string $currentUserId
    ): void {
        $this->status->ensureAllowsModification();
        $this->owner->ensureIsOwner($currentUserId);

        $this->information = $information;

        $this->registerEvent(new ProjectInformationWasChangedEvent(
            $this->id->value,
            $information->name->value,
            $information->description->value,
            $information->finishDate->getValue()
        ));
    }

    public function changeStatus(ProjectStatus $status, string $currentUserId): void
    {
        $this->status->ensureCanBeChangedTo($status);
        $this->owner->ensureIsOwner($currentUserId);

        $this->status = $status;

        $this->registerEvent(new ProjectStatusWasChangedEvent(
            $this->id->value,
            (string) $status->getScalar()
        ));
    }

    /**
     * @throws Exception
     */
    public function removeParticipant(string $participantId, string $currentUserId): void
    {
        $this->status->ensureAllowsModification();
        if (!$this->owner->isOwner($currentUserId) && !($participantId === $currentUserId)) {
            throw new InsufficientPermissionsToChangeProjectParticipantException($participantId, $this->id->value);
        }
        $this->participants->ensureIsParticipant($participantId);
        $this->tasks->ensureDoesUserHaveTask($participantId);

        $this->participants = $this->participants->remove($participantId);

        $this->registerEvent(new ProjectParticipantWasRemovedEvent(
            $this->id->value,
            $participantId
        ));
    }

    /**
     * @throws Exception
     */
    public function changeOwner(Owner $owner, string $currentUserId): void
    {
        $this->status->ensureAllowsModification();
        $this->owner->ensureIsOwner($currentUserId);

        $this->owner->ensureIsNotOwner($owner->userId);
        $this->participants->ensureIsNotParticipant($owner->userId);
        $this->tasks->ensureDoesUserHaveTask($this->owner->userId);

        $this->owner = $owner;

        $this->registerEvent(new ProjectOwnerWasChangedEvent(
            $this->id->value,
            $this->owner->userId
        ));
    }

    public function createRequest(
        RequestId $id,
        string $userId,
    ): Request {
        $this->status->ensureAllowsModification();

        $request = Request::create($id, $userId);

        $this->ensureIsUserAlreadyInProject($userId);
        $this->requests->ensureUserDoesNotHavePendingRequest($userId, $this->id);

        $this->requests = $this->requests->add($request);

        $this->registerEvent(new RequestWasCreatedEvent(
            $this->id->value,
            $request->getId()->value,
            $userId,
            (string) $request->getStatus()->getScalar(),
            $request->getChangeDate()->getValue()
        ));

        return $request;
    }

    public function changeRequestStatus(
        RequestId $id,
        RequestStatus $status,
        string $currentUserId
    ): void {
        $this->status->ensureAllowsModification();
        $this->owner->ensureIsOwner($currentUserId);
        $this->requests->ensureRequestExists($id);

        /** @var Request $request */
        $request = $this->requests->getCollection()->get($id->getHash());
        $request->changeStatus($status);

        if ($status->isConfirmed()) {
            $this->addParticipant($request->getUserId());
        }

        $this->registerEvent(new RequestStatusWasChangedEvent(
            $this->id->value,
            $request->getId()->value,
            $request->getUserId(),
            (string) $request->getStatus()->getScalar(),
            $request->getChangeDate()->getValue()
        ));
    }

    public function createTask(ProjectTaskId $id, TaskId $taskId, UserId $ownerId): void
    {
        $task = new ProjectTask($id, $taskId, $ownerId);
        $this->tasks = $this->tasks->add($task);
    }

    public function getId(): ProjectId
    {
        return $this->id;
    }

    public function getInformation(): ProjectInformation
    {
        return $this->information;
    }

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }

    public function getOwner(): Owner
    {
        return $this->owner;
    }

    public function getParticipants(): Participants
    {
        return $this->participants;
    }

    public function getTasks(): ProjectTasks
    {
        return $this->tasks;
    }

    public function getRequests(): Requests
    {
        return $this->requests;
    }

    private function ensureIsUserAlreadyInProject(string $userId): void
    {
        $this->participants->ensureIsNotParticipant($userId);
        $this->owner->ensureIsNotOwner($userId);
    }

    private function addParticipant(string $participantId): void
    {
        $this->participants = $this->participants->add($participantId);
        $this->registerEvent(new ProjectParticipantWasAddedEvent(
            $this->id->value,
            $participantId
        ));
    }
}
