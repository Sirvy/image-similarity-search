<?php

declare(strict_types=1);

namespace App\Presenters\Api\Dto;

class HistogramDto
{
    public array $red;

    public array $green;

    public array $blue;

    public array $y;

    public array $u;

    public array $v;

    /**
     * @param array $red
     * @param array $green
     * @param array $blue
     * @param array $y
     * @param array $u
     * @param array $v
     */
    public function __construct(array $red, array $green, array $blue, array $y, array $u, array $v)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->y = $y;
        $this->u = $u;
        $this->v = $v;
    }


}