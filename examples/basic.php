<?php

use PXEngine\Engine;
use PXEngine\Event;
use PXEngine\GameInterface;
use PXEngine\GameObjects;

require_once __DIR__ . '/../vendor/autoload.php';

$game = new class implements GameInterface {

    public function init(GameObjects $gameObjects): void {}

    public function update(?Event $event = null): void {}
};

$engine = new Engine();

$engine->setWindowTitle('PHPacman');
$engine->setWindowWidth(900);
$engine->setWindowHeight(600);
$engine->displayDebugInfo();

$engine->run($game);