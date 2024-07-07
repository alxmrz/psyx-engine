<?php

namespace PsyXEngine;

use SDL2\KeyCodes;

class KeyPressedEvent extends Event
{

    private int $keyCode;

    public function __construct(int $keyCode)
    {
        $this->keyCode = $keyCode;
    }

    public function isSpacePressed(): bool
    {
        return $this->keyCode === KeyCodes::SDLK_SPACE;
    }

    public function isLeftArrowKeyPressed(): bool
    {
        return $this->keyCode === KeyCodes::SDLK_LEFT;
    }

    public function isRightArrowKeyPressed(): bool
    {
        return $this->keyCode === KeyCodes::SDLK_RIGHT;
    }

    public function isUpArrowKeyPressed(): bool
    {
        return $this->keyCode === KeyCodes::SDLK_UP;
    }

    public function isDownArrowKeyPressed(): bool
    {
        return $this->keyCode === KeyCodes::SDLK_DOWN;
    }
}