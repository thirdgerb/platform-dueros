<?php

/**
 * Class AbsDirective
 * @package Commune\Platform\DuerOS\Messages
 */

namespace Commune\Platform\DuerOS\Messages;


use Commune\Chatbot\Framework\Messages\AbsConvoMsg;

abstract class AbsDirective extends AbsConvoMsg
{
    abstract public function getType() : string;

    abstract public function toDirectiveArray() : array;

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
        return $this->toDirectiveArray();
    }
}