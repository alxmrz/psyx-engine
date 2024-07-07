<?php

namespace PXEngine\ui;

use PXEngine\Renderer;
use PXEngine\RenderType;
use SDL2\SDLColor;
use SDL2\SDLRect;

class Box extends RenderType
{
    private string $text;
    private SDLColor $color;

    public function __construct(SDLRect $rect, SDLColor $color)
    {
        parent::__construct($rect->getX(), $rect->getY(), $rect->getWidth(), $rect->getHeight());

        $this->color = $color;
    }

    public function display(Renderer $renderer): void
    {
        $renderer->fillRect($this->x, $this->y, $this->width, $this->height, $this->color);
    }
}