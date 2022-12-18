<?php

declare(strict_types=1);

namespace App\Utils;

class ThreeColorObject
{
    public array $c1;
    public array $c2;
    public array $c3;

    /**
     * @param array $c1
     * @param array $c2
     * @param array $c3
     */
    public function __construct(array $c1, array $c2, array $c3)
    {
        $this->c1 = $c1;
        $this->c2 = $c2;
        $this->c3 = $c3;
    }


}