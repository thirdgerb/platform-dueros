<?php

/**
 * Class MessageTest
 * @package Commune\Test\Studio\DuerOS\Messages
 */

namespace Commune\Test\Studio\DuerOS\Messages;


use Commune\Chatbot\App\Messages\ConvoMsgTesting;
use Commune\Platform\DuerOS\Messages\Cards\CueWordsCard;
use Commune\Platform\DuerOS\Messages\Directives\OrdinalDirective;
use Commune\Platform\DuerOS\Messages\Directives\SelectIntentDirective;

class MessageTest extends ConvoMsgTesting
{
    protected $classes = [
        CueWordsCard::class,
        OrdinalDirective::class,
        SelectIntentDirective::class,
        //SelectSlotDirective::class,
    ];


}