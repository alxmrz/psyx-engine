<?php

namespace PXEngine\ui;

use Closure;
use PXEngine\ClickEvent;
use PXEngine\Collision;
use PXEngine\Text;
use SDL2\SDLColor;
use SDL2\SDLRect;

class Button extends Element
{
    private Closure $onClickCallBack;

    public function __construct(string $message, SDLRect $rect, SDLColor $color, int $size, Closure $onClick)
    {
        $this->collision = new Collision(
            $rect->getX(),
            $rect->getY(),
            $rect->getWidth(),
            $rect->getHeight(),
        );

        $this->renderType = new Text(
            $rect->getX(),
            $rect->getY(),
            $rect->getWidth(),
            $rect->getHeight(),
            $color,
            $message,
            $size
        );
        $this->onClickCallBack = $onClick;
    }

    public function onClick(ClickEvent $event): void
    {
        $callback = $this->onClickCallBack;

        $callback();
    }
}