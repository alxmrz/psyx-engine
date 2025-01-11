<?php

namespace PsyXEngine;

use SDL2\LibSDL2;
use SDL2\LibSDL2Image;
use SDL2\LibSDL2TTF;
use SDL2\PixelBuffer;
use SDL2\SDLColor;
use SDL2\SDLPoint;
use SDL2\SDLRect;
use SDL2\SDLRenderer;
use SDL2\SDLRendererFlip;
use SDL2\SDLTexture;

class Renderer
{
    private LibSDL2 $sdl;
    private Window $window;
    private SDLRenderer $renderer;
    private LibSDL2TTF $ttf;
    private array $fonts = [];

    /**
     * @var SDLTexture[]
     */
    private array $textures = [];
    private LibSDL2Image $imager;

    public ?PixelBuffer $pixBuffer = null;

    public function __construct(Window $window, LibSdl2 $sdl, LibSDL2TTF $ttf, LibSDL2Image $imager)
    {
        $this->sdl = $sdl;
        $this->ttf = $ttf;
        $this->imager = $imager;

        $this->window = $window;
    }

    public function __destruct()
    {
        foreach ($this->fonts as $font) {
            $this->ttf->TTF_CloseFont($font);
        }
        $this->ttf->TTF_Quit();
    }

    public function init(): int
    {
        $renderer = $this->sdl->SDL_CreateRenderer($this->window->getWindow(), -1, 2);
        if ($renderer === null) {
            echo "ERROR ON INIT: " . $this->sdl->SDL_GetError();

            return 0;
        }

        $this->renderer = $renderer;
        $this->ttf->TTF_Init();

        return 1;
    }

    public function render(GameObjects $gameObjects): void
    {
        if ($this->sdl->SDL_RenderClear($this->renderer) < 0) {
            printf("Cant clear renderer: %s\n", $this->sdl->SDL_GetError());
            $this->window->close();

            exit();
        }

        $this->renderScene();
        $this->renderGameObjects($gameObjects);
        $this->sdl->SDL_RenderPresent($this->renderer);
    }

    /**
     * @param GameObjects $gameObjects
     * @return void
     */
    public function renderGameObjects(GameObjects $gameObjects): void
    {
        foreach ($gameObjects as $gameObject) {
            if (!$gameObject->isDisplayable()) {
                continue;
            }

            $gameObject->getRenderType()->display($this);
        }
    }

    public function destroy(): void
    {
        $this->sdl->SDL_DestroyRenderer($this->renderer);
    }

    private function renderScene(): void
    {
        $this->sdl->SDL_SetRenderDrawColor($this->renderer, 160, 160, 160, 0);

        $mainRect = new SDLRect(0, 0, $this->window->getWidth(), $this->window->getHeight());

        if ($this->sdl->SDL_RenderFillRect($this->renderer, $mainRect) < 0) {
            echo "ERROR ON INIT: " . $this->sdl->SDL_GetError();
            $this->window->close();
        }
    }

    public function fillRect(int $x, int $y, int $width, int $height, SDLColor $color): void
    {
        $this->sdl->SDL_SetRenderDrawColor($this->renderer, $color->r, $color->g, $color->b, $color->a);

        $mainRect = new SDLRect($x, $y, $width, $height);

        if ($this->sdl->SDL_RenderFillRect($this->renderer, $mainRect) < 0) {
            echo "ERROR ON INIT: " . $this->sdl->SDL_GetError();
            $this->window->close();
        }
    }

    public function createEmptyTexture(int $width, int $height): SDLTexture
    {
        $SDL_PIXELFORMAT_ARGB8888 = 372645892;
            $SDL_TEXTUREACCESS_STATIC = 0;

        return $this->sdl->SDL_CreateTexture(
            $this->renderer,
            $SDL_PIXELFORMAT_ARGB8888,
            $SDL_TEXTUREACCESS_STATIC,
            $width,
            $height
        );
    }

    public function updateTexture(SDLTexture $texture, PixelBuffer $pixelBuffer, int $width, ?SDLRect $destRect = null): void
    {
        $this->sdl->SDL_UpdateTexture($texture, null, $pixelBuffer, $width * 4);
        $this->sdl->SDL_RenderCopy($this->renderer, $texture->getSdlTexture(), NULL, $destRect);
    }

    public function destroyTexture(SDLTexture $texture): void
    {
        $this->sdl->SDL_DestroyTexture($texture->getSdlTexture());
    }


    public function displayText(int $x, int $y, int $width, int $height, SDLColor $color, string $text, int $size = 24): void
    {
        $sans = $this->getFont(__DIR__ . '/../resources/Sans.ttf', $size);

        $surfaceMessage = $this->ttf->TTF_RenderText_Solid($sans, $text, $color);
        if ($surfaceMessage === null) {
            printf("Can't create title surface: %s\n", $this->sdl->SDL_GetError());
            $this->ttf->TTF_CloseFont($sans);
            $this->ttf->TTF_Quit();
            $this->window->close();

            exit();
        }

        $textureMessage = $this->sdl->SDL_CreateTextureFromSurface($this->renderer, $surfaceMessage);
        if (!$textureMessage) {
            printf("Can't create texture: %s\n", $this->sdl->SDL_GetError());
            $this->sdl->SDL_FreeSurface($surfaceMessage);
            $this->ttf->TTF_CloseFont($sans);
            $this->ttf->TTF_Quit();
            $this->window->close();

            exit();
        }

        $messageRect = new SDLRect($x, $y, $width, $height);

        if ($this->sdl->SDL_RenderCopy($this->renderer, $textureMessage, null, $messageRect) !== 0) {
            printf("Error on copy: %s\n", $this->sdl->SDL_GetError());

            $this->sdl->SDL_FreeSurface($surfaceMessage);
            $this->ttf->TTF_CloseFont($sans);
            $this->ttf->TTF_Quit();
            $this->window->close();

            exit();
        }

        $this->sdl->SDL_DestroyTexture($textureMessage);
        $this->sdl->SDL_FreeSurface($surfaceMessage);
    }

    public function displayImage(
        SDLRect $rect,
        string $image,
        SDLRect $source = null,
        float $angle = 0.0,
        SDLPoint $center = null,
        SDLRendererFlip $flip = SDLRendererFlip::SDL_FLIP_NONE,
    ): void
    {
        $image = $this->imager->IMG_Load($image);
        if ($image === null) {
            printf("Can't open image: %s\n", $this->sdl->SDL_GetError());
            $this->window->close();

            exit();
        }

        $textureMessage = $this->sdl->SDL_CreateTextureFromSurface($this->renderer, $image);
        if (!$textureMessage) {
            printf("Can't create texture: %s\n", $this->sdl->SDL_GetError());
            $this->sdl->SDL_FreeSurface($image);

            $this->window->close();

            exit();
        }

        if ($this->sdl->SDL_RenderCopyEx($this->renderer, $textureMessage, $source, $rect, $angle, $center, $flip) !== 0) {
            printf("Error on copy: %s\n", $this->sdl->SDL_GetError());

            $this->sdl->SDL_FreeSurface($image);
            $this->window->close();

            exit();
        }

        $this->sdl->SDL_DestroyTexture($textureMessage);
        $this->sdl->SDL_FreeSurface($image);
        
    }

    private function getFont(string $path, int $size): object
    {
        if (!isset($this->fonts[$size])) {
            $sans = $this->ttf->TTF_OpenFont($path,$size);
            if ($sans === null) {
                printf("Can't create font: %s\n", $this->sdl->SDL_GetError());
                $this->ttf->TTF_Quit();
                $this->window->close();

                exit();
            }

            $this->fonts[$size] = $sans;
        }

        return $this->fonts[$size];
    }
}