<?php

namespace PsyXEngine;

use Closure;
use SplObjectStorage;

class GameObjects extends SplObjectStorage
{
    public function update(Event $event = null): void
    {
        $checkPairs = [];
        /** @var GameObject $gameObject */
        foreach ($this as $gameObject) {
            $gameObject->update();

            if ($gameObject->needDestroy()) {
                $this->detach($gameObject);
            }

            if ($event instanceof KeyPressedEvent) {
                $gameObject->onButtonPressed($event, $this);
            }

            if (!$gameObject->isMovable()) {
                continue;
            }

            foreach (clone $this as $gameObject1) {
                if ($gameObject === $gameObject1) {
                    continue;
                }

                if (isset($checkPairs["{$gameObject->getId()}:{$gameObject1->getId()}"])
                    || isset($checkPairs["{$gameObject1->getId()}:{$gameObject->getId()}"])
                ) {
                    continue;
                }

                if ($gameObject->isCollidable() && $gameObject1->isCollidable() && $gameObject->getCollision()->isCollidedWith($gameObject1->getCollision())) {
                    $gameObject->onCollision($gameObject1, $this);
                    $gameObject1->onCollision($gameObject, $this);
                }

                $checkPairs["{$gameObject->getId()}:{$gameObject1->getId()}"] = 1;
                $checkPairs["{$gameObject1->getId()}:{$gameObject->getId()}"] = 1;
            }
        }
    }

    public function filter(Closure $filter): GameObjects
    {
        $result = new GameObjects();

        foreach ($this as $gameObject) {
            if ($filter($gameObject)) {
                $result->attach($gameObject);
            }
        }

        return $result;
    }
}
