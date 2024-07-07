<?php

namespace PsyXEngine;

use Closure;
use SplObjectStorage;

class GameObjects extends \ArrayObject
{
    public function add(GameObject $object): void
    {
        $this->append($object);
    }

    public function deleteAtIndex(mixed $index): void
    {
        $this->offsetUnset($index);
    }

    public function update(Event $event = null): void
    {
        $checkPairs = [];
        /** @var GameObject $gameObject */
        foreach ($this as  $key => $gameObject) {
            $gameObject->update();

            if ($gameObject->needDestroy()) {
                $this->deleteAtIndex($key);
            }

            if ($event instanceof KeyPressedEvent) {
                $gameObject->onButtonPressed($event, $this);
            }

            $mouseCollision = $event instanceof ClickEvent
                ? new Collision($event->coords[0], $event->coords[1], 1, 1)
                : null;
            if ($mouseCollision && $gameObject->isCollidable()
                && $gameObject->getCollision()->isCollidedWith($mouseCollision)) {
                $gameObject->onClick($event);
            }

            if (!$gameObject->isMovable()) {
                continue;
            }

            foreach ($this as $gameObject1) {
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
                $result->add($gameObject);
            }
        }

        return $result;
    }
}
