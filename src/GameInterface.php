<?php

namespace PXEngine;

interface GameInterface
{
    public function init(GameObjects $gameObjects): void;

    public function update(?Event $event = null): void;
}