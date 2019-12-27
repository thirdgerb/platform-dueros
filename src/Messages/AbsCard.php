<?php

/**
 * Class AbsCard
 * @package Commune\Platform\DuerOS\Messages
 */

namespace Commune\Platform\DuerOS\Messages;


use Commune\Chatbot\Framework\Messages\AbsMessage;

abstract class AbsCard extends AbsMessage
{
    abstract public function getType() : string;

    abstract public function toCardArray() : array;

    public function getMessageType(): string
    {
        return self::class;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function getText(): string
    {
        return '';
    }

    public function toMessageData(): array
    {
        return $this->toCardArray();
    }
}