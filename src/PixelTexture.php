<?php

namespace PsyXEngine;

use SDL2\PixelBuffer;
use SDL2\SDLTexture;
use SDL2\SDLRect;

class PixelTexture extends RenderType
{
    private PixelBuffer $pixels;
    private ?SDLTexture $texture = null;
    public int $width;
    public int $height;
    private ?SDLRect $destRect = null;

    public function __construct(PixelBuffer $pixelBuffer, int $width, int $height, ?SDLRect $destRect = null)
    {
        $this->pixels = $pixelBuffer;
        $this->width = $width;
        $this->height = $height;
        $this->destRect = $destRect;
    }

    public function display(Renderer $renderer): void
    {
        if ($this->texture === null) {
            // TODO: need to destroy the texture somehow
            $this->texture = $renderer->createEmptyTexture($this->width, $this->height);
        }

        $renderer->updateTexture($this->texture, $this->pixels, $this->width, $this->destRect);
    }
}