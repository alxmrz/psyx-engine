<?php

use PsyXEngine\Engine;
use PsyXEngine\Event;
use PsyXEngine\GameInterface;
use PsyXEngine\GameObjects;

require_once __DIR__ . '/../vendor/autoload.php';

$game = new class implements GameInterface {

    public function init(GameObjects $gameObjects): void {}

    public function update(?Event $event = null): void {}
};

$engine = new Engine();

$engine->setWindowTitle('Basic');
$engine->setWindowWidth(900);
$engine->setWindowHeight(600);
$engine->displayDebugInfo();

$engine->run($game);