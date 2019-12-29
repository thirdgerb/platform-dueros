<?php

/**
 * Class AbsCard
 * @package Commune\Platform\DuerOS\Messages
 */

namespace Commune\Platform\DuerOS\Messages;


use Baidu\Duer\Botsdk\Card\BaseCard;
use Commune\Chatbot\Framework\Messages\AbsMessage;

abstract class AbsCard extends AbsMessage
{
    abstract public function toCardArray() : array;

    abstract public function toDuerOSCard() : BaseCard;

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