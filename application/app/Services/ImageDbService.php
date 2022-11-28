<?php

declare(strict_types=1);

namespace App\Services;

use App\Presenters\Api\Dto\ImageResponseDto;
use Nette\Database\Explorer;
use Nette\Utils\Json;

class ImageDbService
{
    const TABLE_NAME = 'images';

    public function __construct(
        private readonly Explorer $db
    )
    {
    }

    public function getAllImages(): array
    {
        return $this->db->table(self::TABLE_NAME)->fetchAll();
    }

    public function insertAllImageResponseDtos(array $imageResponseDtos): void
    {
        $toInsert = [];
        foreach ($imageResponseDtos as $imageResponseDto) {
            $toInsert[] = [
                'filename' => $imageResponseDto->filename,
                'histogram' => Json::encode([
                    'red' => $imageResponseDto->histogram->red,
                    'green' => $imageResponseDto->histogram->green,
                    'blue' => $imageResponseDto->histogram->blue,
                    'y' => $imageResponseDto->histogram->y,
                    'u' => $imageResponseDto->histogram->u,
                    'v' => $imageResponseDto->histogram->v,
                ]),
            ];
        }
        $this->db->table(self::TABLE_NAME)->insert($toInsert);
    }

    public function insertImageResponseDto(ImageResponseDto $imageResponseDto): void
    {
        $this->db->table(self::TABLE_NAME)->insert([
            'filename' => $imageResponseDto->filename,
            'histogram' => Json::encode([
                'red' => $imageResponseDto->histogram->red,
                'green' => $imageResponseDto->histogram->green,
                'blue' => $imageResponseDto->histogram->blue,
                'y' => $imageResponseDto->histogram->y,
                'u' => $imageResponseDto->histogram->u,
                'v' => $imageResponseDto->histogram->v,
            ]),
        ]);
    }

    public function deleteAll(): void
    {
        $this->db->table(self::TABLE_NAME)->delete();
    }
}