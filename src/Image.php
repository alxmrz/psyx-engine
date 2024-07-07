<?php

namespace PXEngine;

use SDL2\SDLPoint;
use SDL2\SDLRect;
use SDL2\SDLRendererFlip;

class Image extends RenderType
{
    private string $imagePath;
    private SDLRect $rect;
    private SDLRect $source;
    private float $angle;
    private ?SDLPoint $center;
    private SDLRendererFlip $flip;

    public function __construct(
        string $imagePath,
        SDLRect $rect,
        SDLRect $source = null,
        float $angle = 0.0,
        SDLPoint $center = null,
        SDLRendererFlip $flip = SDLRendererFlip::SDL_FLIP_NONE,
    ) {
        parent::__construct($rect->getX(), $rect->getY(), $rect->getWidth(), $rect->getHeight());
        $this->imagePath = $imagePath;
        $this->rect = $rect;
        $this->source = $source;
        $this->angle = $angle;
        $this->center = $center;
        $this->flip = $flip;
    }

    public function display(Renderer $renderer): void
    {
        $renderer->displayImage($this->rect, $this->imagePath, $this->source, $this->angle, $this->center, $this->flip);
    }

    public function setRotationAngle(float $angle): void
    {
        $this->angle = $angle;
    }
}