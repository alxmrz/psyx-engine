<?php

namespace PXEngine;

use SDL2\LibSDL2;
use SDL2\LibSDL2Mixer;

class Audio
{
    private LibSDL2Mixer $mixer;
    private array $loadedChunks = [];

    public function __construct()
    {
        $this->mixer = LibSDL2Mixer::load();
    }

    public function playChunk(string $audioPath): void
    {
        if ($this->mixer->Mix_OpenAudio(44100, LibSDL2Mixer::DEFAULT_FORMAT, 2, 2048) === 0) {
            $chunk = $this->loadChunk($audioPath);
            $this->mixer->Mix_PlayChannel(-1, $chunk, 0);
        } else {
            printf("ERROR ON open audio: %s\n", LibSDL2::load()->SDL_GetError());
        }
    }

    public function isMusicPlaying(): bool
    {
        return (bool)$this->mixer->Mix_PlayingMusic();
    }

    public function isChannelPlaying(int $channel = -1): bool
    {
        return (bool)$this->mixer->Mix_Playing($channel);
    }

    private function loadChunk(string $audioPath)
    {
        if (!isset($this->loadedChunks[$audioPath])) {
            $this->loadedChunks[$audioPath] = $this->mixer->Mix_LoadWAV($audioPath, LibSDL2::load());
        }

        return $this->loadedChunks[$audioPath];
    }
}