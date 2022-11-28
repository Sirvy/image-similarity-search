<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Utils\FileSystem;

class IndexPresenter extends Presenter
{
    public function actionDefault(): void
    {
        FileSystem::createDir('files');
    }
}