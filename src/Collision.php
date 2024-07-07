<?php

namespace PsyXEngine;

class Collision
{
    public int $x;
    public int $y;
    public int $width;
    public int $height;

    public function __construct(int $x, int $y, int $width, int $height)
    {
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

    public function isCollidedWith(Collision $collision): bool
    {
        return $this->x < $collision->x + $collision->width &&
            $this->x + $this->width > $collision->x &&
            $this->y < $collision->y + $collision->height &&
            $this->y + $this->height > $collision->y;
    }

    private function isCollidedByPoint(int $x, int $y): bool
    {
        return $x >= $this->x  && $x <= ($this->x + $this->width)
            && $y >= $this->y && $y <= ($this->y + $this->height);
    }
}