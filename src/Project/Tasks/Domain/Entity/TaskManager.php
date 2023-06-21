<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Entity;

use App\Project\Tasks\Domain\Collection\TaskLinkCollection;
use App\Project\Tasks\Domain\Exception\InsufficientPermissionsToChangeTaskException;
use App\Project\Tasks\Domain\Exception\TaskUserNotExistException;
use App\Project\Tasks\Domain\ValueObject\ActiveTaskStatus;
use App\Project\Tasks\Domain\ValueObject\TaskInformation;
use App\Project\Tasks\Domain\ValueObject\TaskManagerId;
use App\Project\Tasks\Domain\ValueObject\Tasks;
use App\Project\Tasks\Domain\ValueObject\TaskStatus;
use App\Project\Shared\Domain\Aggregate\AggregateRoot;
use App\Project\Shared\Domain\Event\Tasks\TaskInformationWasChangedEvent;
use App\Project\Shared\Domain\Event\Tasks\TaskLinkWasAddedEvent;
use App\Project\Shared\Domain\Event\Tasks\TaskLinkWasDeletedEvent;
use App\Project\Shared\Domain\Event\Tasks\TaskStatusWasChangedEvent;
use App\Project\Shared\Domain\Event\Tasks\TaskWasCreatedEvent;
use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Owner;
use App\Project\Shared\Domain\ValueObject\Participants;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectStatus;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;
use App\Project\Shared\Domain\ValueObject\Users\UserId;

final class TaskManager extends AggregateRoot
{
    public function __construct(
        private TaskManagerId $id,
        private ProjectId $projectId,
        private ProjectStatus $status,
        private Owner $owner,
        private DateTime $finishDate,
        private Participants $participants,
        private Tasks $tasks
    ) {
    }

    public function createTask(
        TaskId $id,
        TaskInformation $information,
        string $ownerId,
        string $currentUserId
    ): Task {
        $this->status->ensureAllowsModification();

        $status = new ActiveTaskStatus();
        $task = new Task(
            $id,
            $information,
            $ownerId,
            $status,
            new TaskLinkCollection()
        );

        $this->ensureCanChangeTask($task->getOwnerId(), $currentUserId);
        if (!$this->owner->isOwner($ownerId) && !$this->participants->isParticipant($ownerId)) {
            throw new TaskUserNotExistException($ownerId);
        }

        $this->tasks = $this->tasks->add($task);
        $this->tasks->ensureIsFinishDateGreaterThanTaskDates($task->getId(), $this->finishDate);

        $this->registerEvent(new TaskWasCreatedEvent(
            $this->id->value,
            $this->projectId->value,
            $task->getId()->value,
            $information->name->value,
            $information->brief->value,
            $information->description->value,
            $information->startDate->getValue(),
            $information->finishDate->getValue(),
            $ownerId,
            (string) $status->getScalar()
        ));

        return $task;
    }

    public function changeTaskInformation(
        TaskId $taskId,
        TaskInformation $information,
        string $currentUserId
    ): void {
        $this->status->ensureAllowsModification();
        $this->tasks->ensureTaskExists($taskId);

        /** @var Task $task */
        $task = $this->tasks->getCollection()->get($taskId->getHash());
        $this->ensureCanChangeTask($task->getOwnerId(), $currentUserId);

        $task->changeInformation($information);
        $this->tasks->ensureIsFinishDateGreaterThanTaskDates($task->getId(), $this->finishDate);

        $this->registerEvent(new TaskInformationWasChangedEvent(
            $this->id->value,
            $this->projectId->value,
            $task->getId()->value,
            $information->name->value,
            $information->brief->value,
            $information->description->value,
            $information->startDate->getValue(),
            $information->finishDate->getValue(),
        ));
    }

    public function changeTaskStatus(TaskId $taskId, TaskStatus $status, string $currentUserId): void
    {
        $this->status->ensureAllowsModification();
        $this->tasks->ensureTaskExists($taskId);

        /** @var Task $task */
        $task = $this->tasks->getCollection()->get($taskId->getHash());
        $this->ensureCanChangeTask($task->getOwnerId(), $currentUserId);
        $task->changeStatus($status);

        $this->registerEvent(new TaskStatusWasChangedEvent(
            $this->id->value,
            $this->projectId->value,
            $task->getId()->value,
            (string) $status->getScalar()
        ));
    }

    public function createTaskLink(
        TaskId $fromTaskId,
        TaskId $toTaskId,
        string $currentUserId
    ): void {
        $this->status->ensureAllowsModification();
        $this->tasks->ensureTaskExists($fromTaskId);
        $this->tasks->ensureTaskExists($toTaskId);

        /** @var Task $task */
        $task = $this->tasks->getCollection()->get($fromTaskId->getHash());
        $this->ensureCanChangeTask($task->getOwnerId(), $currentUserId);
        $task->addLink($toTaskId);

        /** @var Task $task */
        $task = $this->tasks->getCollection()->get($toTaskId->getHash());
        $task->addLink($fromTaskId);

        $this->registerEvent(new TaskLinkWasAddedEvent(
            $this->id->value,
            $fromTaskId->value,
            $toTaskId->value
        ));
        $this->registerEvent(new TaskLinkWasAddedEvent(
            $this->id->value,
            $toTaskId->value,
            $fromTaskId->value
        ));
    }

    public function deleteTaskLink(
        TaskId $fromTaskId,
        TaskId $toTaskId,
        string $currentUserId
    ): void {
        $this->status->ensureAllowsModification();
        $this->tasks->ensureTaskExists($fromTaskId);
        $this->tasks->ensureTaskExists($toTaskId);

        /** @var Task $task */
        $task = $this->tasks->getCollection()->get($fromTaskId->getHash());
        $this->ensureCanChangeTask($task->getOwnerId(), $currentUserId);
        $task->deleteLink($toTaskId);

        /** @var Task $task */
        $task = $this->tasks->getCollection()->get($toTaskId->getHash());
        $task->deleteLink($fromTaskId);

        $this->registerEvent(new TaskLinkWasDeletedEvent(
            $this->id->value,
            $fromTaskId->value,
            $toTaskId->value
        ));
        $this->registerEvent(new TaskLinkWasDeletedEvent(
            $this->id->value,
            $toTaskId->value,
            $fromTaskId->value
        ));
    }

    public function getId(): TaskManagerId
    {
        return $this->id;
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }

    public function getOwner(): Owner
    {
        return $this->owner;
    }

    public function getFinishDate(): DateTime
    {
        return $this->finishDate;
    }

    public function getParticipants(): Participants
    {
        return $this->participants;
    }

    public function getTasks(): Tasks
    {
        return $this->tasks;
    }

    public function changeStatus(ProjectStatus $status): void
    {
        if ($status->isClosed()) {
            /** @var Task $task */
            foreach ($this->tasks->getCollection() as $task) {
                $task->closeIfCan();
            }
        }
        $this->status = $status;
    }

    public function changeOwner(Owner $owner): void
    {
        $this->owner = $owner;
    }

    public function removeParticipant(string $participantId): void
    {
        $this->participants = $this->participants->remove($participantId);
    }

    public function addParticipant(string $participantId): void
    {
        $this->participants = $this->participants->add($participantId);
    }

    public function changeFinishDate(DateTime $date): void
    {
        /** @var Task $task */
        foreach ($this->tasks->getCollection() as $task) {
            $task->limitDatesIfNeed($date);
        }
        $this->finishDate = $date;
    }

    private function ensureCanChangeTask(string $taskOwnerId, string $currentUserId): void
    {
        if (!($this->owner == $currentUserId) && !($taskOwnerId == $currentUserId)) {
            throw new InsufficientPermissionsToChangeTaskException();
        }
    }
}
