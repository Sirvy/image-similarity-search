<?php

declare(strict_types=1);

namespace App\Utils;

class Histogram
{
    private array $red;

    private array $green;

    private array $blue;

    private array $y;

    private array $u;

    private array $v;

    public function __construct()
    {
        $this->red = array_fill(0, 256, 0);
        $this->green = array_fill(0, 256, 0);
        $this->blue = array_fill(0, 256, 0);
        $this->y = array_fill(0, 256, 0);
        $this->u = array_fill(0, 256, 0);
        $this->v = array_fill(0, 256, 0);
    }

    public function increaseRed(int $volume): void
    {
        $this->red[$volume]++;
    }

    public function increaseGreen(int $volume): void
    {
        $this->green[$volume]++;
    }

    public function increaseBlue(int $volume): void
    {
        $this->blue[$volume]++;
    }

    public function getRed(): array
    {
        return $this->red;
    }

    public function getGreen(): array
    {
        return $this->green;
    }

    public function getBlue(): array
    {
        return $this->blue;
    }

    public function increaseY(int $volume): void
    {
        $this->y[$volume]++;
    }

    public function increaseU(int $volume): void
    {
        $this->u[$volume]++;
    }

    public function increaseV(int $volume): void
    {
        $this->v[$volume]++;
    }

    public function getY(): array
    {
        return $this->y;
    }

    public function getU(): array
    {
        return $this->u;
    }

    public function getV(): array
    {
        return $this->v;
    }
}