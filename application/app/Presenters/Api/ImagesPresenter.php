<?php

declare(strict_types=1);

namespace App\Presenters\Api;

use App\Presenters\Api\Dto\ImageResponseDto;
use App\Presenters\Api\Factory\HistogramDtoFactory;
use App\Services\ImageDbService;
use App\Services\SaveImageService;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Nette\Application\AbortException;
use Nette\Utils\ImageException;
use Nette\Utils\UnknownImageFileException;

class ImagesPresenter extends AbstractApiPresenter
{
    public function __construct(
        private readonly SaveImageService    $saveImageService,
        private readonly ImageDbService      $imageDbService,
        private readonly HistogramDtoFactory $histogramDtoFactory
    )
    {
        parent::__construct();
    }

    /**
     * @throws AbortException
     * @throws Exception
     */
    #[NoReturn] protected function handlePost(): void
    {
        $files = $this->getRequest()->getFiles();

        if (count($files) < 1) {
            throw new Exception('No File');
        }

        $savedImages = [];

        foreach ($files as $file) {
            try {
                $imageResponseDto = $this->saveImageService->saveImage($file);
            } catch (UnknownImageFileException $e) {
                $this->sendJson('Error while creating image: ' . $e->getMessage());
            } catch (ImageException $e) {
                $this->sendJson('An error occurred: ' . $e->getMessage());
            }

            $savedImages[] = $imageResponseDto;
        }

        $this->imageDbService->insertAllImageResponseDtos($savedImages);

        $this->sendJson($this->getAllImages());
    }

    /**
     * @throws AbortException
     */
    #[NoReturn] protected function handleGet(): void
    {
        $this->sendJson($this->getAllImages());
    }

    /**
     * @throws AbortException
     */
    #[NoReturn] protected function handleDelete()
    {
        $this->imageDbService->deleteAll();
        $this->sendJson('All images deleted');
    }

    private function getAllImages(): array
    {
        $images = $this->imageDbService->getAllImages();
        $result = [];
        foreach ($images as $image) {
            $result[] = new ImageResponseDto($image->id, $image->filename, $this->histogramDtoFactory->createFromJson($image->histogram));
        }

        return $result;
    }
}