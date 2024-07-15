<?php

namespace PsyXEngine;

use SDL2\PixelBuffer;
use SDL2\SDLTexture;

class PixelTexture extends RenderType
{
    private PixelBuffer $pixels;
    private ?SDLTexture $texture = null;
    public int $width;
    public int $height;

    public function __construct(PixelBuffer $pixelBuffer, int $width, int $height)
    {
        $this->pixels = $pixelBuffer;
        $this->width = $width;
        $this->height = $height;
    }

    public function display(Renderer $renderer): void
    {
        if ($this->texture === null) {
            // TODO: need to destroy the texture somehow
            $this->texture = $renderer->createEmptyTexture($this->width, $this->height);
        }

        $renderer->updateTexture($this->texture, $this->pixels, $this->width);
    }
}