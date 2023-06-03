<?php

declare(strict_types=1);

namespace App\Project\Projects\Domain\ValueObject;

use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Projects\Domain\Collection\ProjectTaskCollection;
use App\Project\Projects\Domain\Entity\ProjectTask;
use App\Project\Projects\Domain\Exception\UserHasProjectTaskException;
use Exception;

final class ProjectTasks
{
    private ProjectTaskCollection $tasks;

    public function __construct(?ProjectTaskCollection $items = null)
    {
        if (null === $items) {
            $this->tasks = new ProjectTaskCollection();
        } else {
            $this->tasks = $items;
        }
    }

    /**
     * @throws Exception
     */
    public function ensureDoesUserHaveTask(UserId $userId): void
    {
        /** @var ProjectTask $task */
        foreach ($this->tasks as $task) {
            if ($task->getOwnerId()->isEqual($userId)) {
                throw new UserHasProjectTaskException($userId->value);
            }
        }
    }

    public function add(ProjectTask $task): self
    {
        $result = new self();
        $result->tasks = $this->tasks->add($task);

        return $result;
    }

    public function getCollection(): ProjectTaskCollection
    {
        return $this->tasks;
    }
}
