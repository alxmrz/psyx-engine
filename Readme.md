# PsyX engine

PHP 2D game engine.

It is created on top of `php-sdl2` library, so you need to install `SDL2` libraries by yourself (`libSDL2, libSDL2_image, libSDL2_mixer, libSDL2_ttf`).
Also make sure that the `FFI` module is enabled in your PHP configuration.

To start using you need to create a class that implements `GameInterface` and pass in to `Engine::run` method.
```php

$game = new class implements GameInterface {

    public function init(GameObjects $gameObjects): void {}

    public function update(?Event $event = null): void {}
};

$engine = new Engine();

$engine->setWindowTitle('MyGame');
$engine->setWindowWidth(900);
$engine->setWindowHeight(600);

$engine->run($game);
```

Everything in the game is instance of `GameObject` class (even ui).
Extend it and add to `GameObjects` instance (see `GameInterface::init`) to attach it to the game.
```php
class Player extends GameObject
{
    public function __construct(SDLRect $rect, SDLColor $color)
    {
        $this->renderType = new Rectangle(
            $rect->getX(),
            $rect->getY(),
            $rect->getWidth(),
            $rect->getHeight(),
            $color
        );
        $this->collision = new Collision(
            $rect->getX()
            $rect->getY()
            $rect->getWidth()
            $rect->getHeight()
        );
    }

   // Called every tick of the game loop
   public function update() {}
}

...

$gameObjects->add(new Player(...))
```

You need to specify property `renderType` of your game object in order to the engine could display it on the screen.
Available render types: 
- `Rectandle`
- `Text`
- `Image`

You can specify `collision` property in order to the collisions of objects started to work.

Audio module is also available and can be used in the way:
```php

$audio = new Audio();

$audio->playChunk(PATH_TO_AUDIO_FILE);
```

Tested under Linux only.

Projects made with the engine:
- https://github.com/alxmrz/deminer - a clone of Minesweeper
- https://github.com/alxmrz/phpacman - a clone of Pac-man 
- https://github.com/alxmrz/theroom - demo of semi-3d abilities of the engine
- https://github.com/alxmrz/dino-run - a clone of Chrome's dino game 