<?php

declare(strict_types=1);

namespace App\Presenters\Api;

use Nette\Application\UI\Presenter;

abstract class AbstractApiPresenter extends Presenter
{
    public function actionDefault()
    {
        switch ($this->getRequest()->getMethod()) {
            case 'POST':
                $this->handlePost();
                break;
            case 'GET':
                $this->handleGet();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
        }
    }

    protected function handlePost()
    {
    }

    protected function handleGet()
    {
    }

    protected function handleDelete()
    {
    }
}