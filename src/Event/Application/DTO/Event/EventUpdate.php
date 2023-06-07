<?php

declare(strict_types=1);

namespace App\Event\Application\DTO\Event;


/**
 * Class EventUpdate
 *
 * @package App\Event
 */
class EventUpdate extends Event
{

    protected string $siteName = '';

    protected string $sidebarTheme = '';

    protected string $sidebarColor = '';

}
