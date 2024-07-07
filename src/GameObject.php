<?php

namespace PXEngine;

class GameObject
{
    protected RenderType $renderType;
    protected ?Collision $collision = null;
    /**
     * @var true
     */
    private bool $needDestroy = false;

    public function onCollision(GameObject $gameObject, GameObjects $gameObjects): void
    {

    }

    public function onClick(ClickEvent $event): void
    {
    }

    public function onButtonPressed(KeyPressedEvent $event, GameObjects $gameObjects): void
    {

    }

    public function update(): void
    {

    }

    public function getRenderType(): RenderType
    {
        return $this->renderType;
    }

    public function getCollision(): ?Collision
    {
        return $this->collision;
    }

    public function isCollidable(): bool
    {
        return $this->collision !== null;
    }

    public function destroy(): void
    {
        $this->needDestroy = true;
    }

    public function needDestroy(): bool
    {
        return $this->needDestroy;
    }

    public function getId(): string
    {
        return spl_object_hash($this);
    }

    public function isMovable(): bool
    {
        return false;
    }

    public function isDisplayable(): bool
    {
        return true;
    }
}