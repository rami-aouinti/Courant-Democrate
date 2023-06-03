<?php

declare(strict_types=1);

namespace App\Project\Tasks\Domain\Entity;

use App\Project\Tasks\Domain\Collection\TaskLinkCollection;
use App\Project\Tasks\Domain\Exception\TaskLinkAlreadyExistsException;
use App\Project\Tasks\Domain\Exception\TasksOfTaskLinkAreEqualException;
use App\Project\Tasks\Domain\Exception\TaskStartDateGreaterThanFinishDateException;
use App\Project\Tasks\Domain\ValueObject\ClosedTaskStatus;
use App\Project\Tasks\Domain\ValueObject\TaskInformation;
use App\Project\Tasks\Domain\ValueObject\TaskLink;
use App\Project\Tasks\Domain\ValueObject\TaskStatus;
use App\Project\Shared\Domain\Collection\Hashable;
use App\Project\Shared\Domain\Exception\TaskLinkNotExistException;
use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;
use App\Project\Shared\Domain\ValueObject\Users\UserId;

final class Task implements Hashable
{
    public function __construct(
        private TaskId $id,
        private TaskInformation $information,
        private UserId $ownerId,
        private TaskStatus $status,
        private TaskLinkCollection $links
    ) {
        $this->ensureFinishDateGreaterThanStart();
    }

    public function changeInformation(TaskInformation $information): void
    {
        $this->status->ensureAllowsModification();
        $this->information = $information;
        $this->ensureFinishDateGreaterThanStart();
    }

    public function changeStatus(TaskStatus $status): void
    {
        $this->status->ensureCanBeChangedTo($status);
        $this->status = $status;
    }

    public function addLink(TaskId $taskId): void
    {
        $this->ensureIsDifferentTask($taskId);
        $taskLink = new TaskLink($taskId);
        $this->ensureLinkDoesNotExist($taskLink);
        $this->links = $this->links->add($taskLink);
    }

    public function deleteLink(TaskId $taskId): void
    {
        $this->ensureIsDifferentTask($taskId);
        $taskLink = new TaskLink($taskId);
        $this->ensureLinkExists($taskLink);
        $this->links = $this->links->remove($taskLink);
    }

    public function closeIfCan(): void
    {
        $newStatus = new ClosedTaskStatus();
        if ($this->status->canBeChangedTo($newStatus)) {
            $this->status = $newStatus;
        }
    }

    public function limitDatesIfNeed(DateTime $date): void
    {
        $newStartDate = $this->information->startDate;
        if ($this->information->startDate->isGreaterThan($date)) {
            $newStartDate = $date;
        }
        $newFinishDate = $this->information->startDate;
        if ($this->information->finishDate->isGreaterThan($date)) {
            $newFinishDate = $date;
        }
        $this->information = new TaskInformation(
            $this->information->name,
            $this->information->brief,
            $this->information->description,
            $newStartDate,
            $newFinishDate,
        );
    }

    public function getId(): TaskId
    {
        return $this->id;
    }

    public function getInformation(): TaskInformation
    {
        return $this->information;
    }

    public function getOwnerId(): UserId
    {
        return $this->ownerId;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function getLinks(): TaskLinkCollection
    {
        return $this->links;
    }

    public function getHash(): string
    {
        return $this->id->getHash();
    }

    public function isEqual(object $other): bool
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        return $this->id->isEqual($other->id) &&
            $this->information->isEqual($other->information) &&
            $this->status->isEqual($other->status) &&
            $this->ownerId->isEqual($other->ownerId);
    }

    private function ensureFinishDateGreaterThanStart()
    {
        $info = $this->information;
        if ($this->information->startDate->isGreaterThan($info->finishDate)) {
            throw new TaskStartDateGreaterThanFinishDateException($info->startDate->getValue(), $info->finishDate->getValue());
        }
    }

    private function ensureLinkDoesNotExist(TaskLink $link): void
    {
        if ($this->links->exists($link)) {
            throw new TaskLinkAlreadyExistsException($this->id->value, $link->toTaskId->value);
        }
    }

    private function ensureLinkExists(TaskLink $link): void
    {
        if (!$this->links->exists($link)) {
            throw new TaskLinkNotExistException($this->id->value, $link->toTaskId->value);
        }
    }

    private function ensureIsDifferentTask(TaskId $toTaskId)
    {
        if ($this->id->isEqual($toTaskId)) {
            throw new TasksOfTaskLinkAreEqualException($toTaskId->value);
        }
    }
}
