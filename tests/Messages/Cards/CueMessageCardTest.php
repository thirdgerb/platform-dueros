<?php

/**
 * Class CueMessageCardTest
 * @package Commune\Test\Studio\DuerOS\Messages\Cards
 */

namespace Commune\Test\Studio\DuerOS\Messages\Cards;

use Baidu\Duer\Botsdk\Card\StandardCard;
use Baidu\Duer\Botsdk\Nlu;
use Baidu\Duer\Botsdk\Request;
use Baidu\Duer\Botsdk\Response;
use Baidu\Duer\Botsdk\Session;
use Commune\Chatbot\App\Messages\QA\Confirm;
use Commune\Platform\DuerOS\Messages\Cards\CueWordsCard;
use PHPUnit\Framework\TestCase;

class CueMessageCardTest extends TestCase
{

    public function testStandardCard()
    {
        $card = new StandardCard();
        $card->setTitle($title = 'title');
        $card->setContent($content = 'content');
        $card->addCueWords($words = ['a', 'b', 'c']);

        $data = $card->getData();
        $this->assertEquals($title, $data['title']);
        $this->assertEquals($content, $data['content']);
        $this->assertEquals($words, $data['cueWords']);
    }

    public function testResponse()
    {
        $res = new Response(
            new Request($this->getStubRequest()),
            new Session([]),
            new Nlu([])
        );

        $card = new StandardCard();
        $card->setTitle($title = 'title');
        $card->setContent($content = 'content');
        $card->addCueWords($words = ['a', 'b', 'c']);

        $output = $res->build(['card' => $card]);
        $data = json_decode($output, true);

        $this->assertEquals('title', $data['response']['card']['title']);
        $this->assertEquals('content', $data['response']['card']['content']);
        $this->assertEquals($words, $data['response']['card']['cueWords']);
    }

    public function testQuestionCard()
    {
        $confirm = new Confirm('test');

        $card = new CueWordsCard('', $confirm->getQuery(), $confirm->getSuggestions());

        $dCard = $card->toDuerOSCard();
        $dCard->setTitle('title');

        $data = $dCard->getData();

        $this->assertEquals('title', $data['title']);
        $this->assertEquals('test', $data['content']);
    }

    protected function getStubRequest() : array
    {
        $data = <<<EOF
{ 
    "version": "2.0",
    "session": {
        "new": 1,
        "sessionId": "{{STRING}}",
        "attributes": {
            "{{STRING}}": "{{STRING}}"
        }
    },
    "context": {
        "System": {
            "user": {
                "userId": "{{STRING}}",
                "accessToken": "{{STRING}}"
            },
            "application": {
                "applicationId": "{{STRING}}"
            },
            "device": {
                "deviceId": "{{STRING}}",
                "supportedInterfaces": {
                    "VoiceInput": {},
                    "VoiceOutput": {},
                    "AudioPlayer": {},
                    "VideoPlayer": {
                        "preferedVideoCodec": [
                            "{{ENUM}}"
                        ],
                        "preferedAudioCodec": [
                            "{{ENUM}}"
                        ],
                        "preferedBitrate": "{{ENUM}}",
                        "preferedWidth": 32,
                        "preferedHeight": 32
                    },
                    "Display": {}
                }
            }
        }
    },
    "request": {
        "type": "LaunchRequest",
        "requestId": "{{STRING}}",
        "timestamp": "{{STRING}}"
    }
}
EOF;

        $value = json_decode($data,true);
        return $value;
    }
}