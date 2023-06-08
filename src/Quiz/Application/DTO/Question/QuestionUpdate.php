<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Question;


/**
 * Class QuestionUpdate
 *
 * @package App\Question
 */
class QuestionUpdate extends Question
{

    protected string $siteName = '';

    protected string $sidebarTheme = '';

    protected string $sidebarColor = '';

}
