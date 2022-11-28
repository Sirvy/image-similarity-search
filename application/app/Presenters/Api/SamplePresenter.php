<?php

declare(strict_types=1);

namespace App\Presenters\Api;

use App\Services\SaveImageService;
use JetBrains\PhpStorm\NoReturn;
use Nette\Application\AbortException;
use Nette\FileNotFoundException;
use Nette\Http\FileUpload;
use Nette\Utils\ImageException;
use Nette\Utils\UnknownImageFileException;

class SamplePresenter extends AbstractApiPresenter
{
    public function __construct(
        private readonly SaveImageService $saveImageService,
    )
    {
        parent::__construct();
    }

    /**
     * @throws AbortException
     */
    #[NoReturn] protected function handlePost()
    {
        $files = $this->getRequest()->getFiles();

        if (count($files) === 0) {
            throw new FileNotFoundException('No File');
        }

        /** @var FileUpload $file */
        $file = reset($files);

        try {
            $result = $this->saveImageService->saveImage($file);
        } catch (UnknownImageFileException $e) {
            $this->sendJson('Error while creating image: ' . $e->getMessage());
        } catch (ImageException $e) {
            $this->sendJson('An error occurred: ' . $e->getMessage());
        }

        $this->sendJson($result);
    }

}