<?php

declare(strict_types=1);

namespace App\Event\Transport\Command\Event;

use App\General\Transport\Command\HelperConfigure;
use App\General\Transport\Command\Traits\SymfonyStyleTrait;
use App\Role\Application\Security\RolesService;
use App\Role\Domain\Repository\Interfaces\RoleRepositoryInterface;
use App\Event\Application\DTO\Event\EventCreate as EventDto;
use App\Event\Application\Resource\EventResource;
use App\Event\Transport\Form\Type\Console\EventType;
use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Class CreateUserCommand
 *
 * @package App\User
 */
#[AsCommand(
    name: self::NAME,
    description: 'Console command to create event to database',
)]
class CreateEventCommand extends Command
{
    use SymfonyStyleTrait;

    final public const NAME = 'event:create';
    private const PARAMETER_NAME = 'name';
    private const PARAMETER_DESCRIPTION = 'description';

    /**
     * @var array<int, array<string, string>>
     */
    private static array $commandParameters = [
        [
            self::PARAMETER_NAME => 'title',
            self::PARAMETER_DESCRIPTION => 'Title',
        ],
        [
            self::PARAMETER_NAME => 'description',
            self::PARAMETER_DESCRIPTION => 'Description',
        ],
        [
            self::PARAMETER_NAME => 'allDays',
            self::PARAMETER_DESCRIPTION => 'All Days',
        ],
        [
            self::PARAMETER_NAME => 'backgroundColor',
            self::PARAMETER_DESCRIPTION => 'backgroundColor',
        ],
        [
            self::PARAMETER_NAME => 'borderColor',
            self::PARAMETER_DESCRIPTION => 'borderColor',
        ],
        [
            self::PARAMETER_NAME => 'textColor',
            self::PARAMETER_DESCRIPTION => 'textColor',
        ],
        [
            self::PARAMETER_NAME => 'className',
            self::PARAMETER_DESCRIPTION => 'className',
        ],
        [
            self::PARAMETER_NAME => 'start',
            self::PARAMETER_DESCRIPTION => 'Start',
        ],
        [
            self::PARAMETER_NAME => 'end',
            self::PARAMETER_DESCRIPTION => 'End',
        ],
    ];

    /**
     * Constructor
     *
     * @param \App\Role\Infrastructure\Repository\RoleRepository $roleRepository
     *
     * @throws LogicException
     */
    public function __construct(
        private readonly EventResource $eventResource,
        private readonly RolesService $rolesService,
        private readonly RoleRepositoryInterface $roleRepository,
    ) {
        parent::__construct();
    }

    public function getRolesService(): RolesService
    {
        return $this->rolesService;
    }

    protected function configure(): void
    {
        parent::configure();

        HelperConfigure::configure($this, self::$commandParameters);
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     *
     * {@inheritdoc}
     *
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);
        // Check that roles exists
        /** @var FormHelper $helper */
        $helper = $this->getHelper('form');
        /** @var EventDto $dto */
        $dto = $helper->interactUsingForm(EventType::class, $input, $output);
        // Create new user
        $this->eventResource->create($dto);

        if ($input->isInteractive()) {
            $io->success('Event created - have a nice day');
        }

        return 0;
    }
}
