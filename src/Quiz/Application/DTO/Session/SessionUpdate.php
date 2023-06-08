<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Session;


/**
 * Class SessionUpdate
 *
 * @package App\Session
 */
class SessionUpdate extends Session
{

    protected string $siteName = '';

    protected string $sidebarTheme = '';

    protected string $sidebarColor = '';

}
