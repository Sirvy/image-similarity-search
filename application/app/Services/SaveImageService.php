<?php

declare(strict_types=1);

namespace App\Services;

use App\Presenters\Api\Dto\ImageResponseDto;
use App\Presenters\Api\Factory\HistogramDtoFactory;
use Nette\Http\FileUpload;
use Nette\Utils\Image;
use Nette\Utils\ImageException;
use Nette\Utils\UnknownImageFileException;

class SaveImageService
{
    public function __construct(
        private readonly ImageHistogramService $histogramService,
        private readonly HistogramDtoFactory   $histogramDtoFactory
    )
    {
    }

    /**
     * @throws ImageException
     * @throws UnknownImageFileException
     */
    public function saveImage(FileUpload $file): ImageResponseDto
    {
        if (!$file->isImage() || !$file->isOk()) {
            throw new ImageException('Not an image.');
        }

        $fileName = $file->getSanitizedName();

        $image = Image::fromFile($file->getTemporaryFile());

        $imageName = "files/{$fileName}";

        $image->save($imageName);

        $histogram = $this->histogramService->createHistogramFromImage($image);

        return new ImageResponseDto(0, $imageName, $this->histogramDtoFactory->createFromHistogram($histogram));
    }
}