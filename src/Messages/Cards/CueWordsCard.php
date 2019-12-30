<?php

/**
 * Class QuestionCard
 * @package Commune\Platform\DuerOS\Messages\Cards
 */

namespace Commune\Platform\DuerOS\Messages\Cards;


use Baidu\Duer\Botsdk\Card\BaseCard;
use Baidu\Duer\Botsdk\Card\StandardCard;
use Commune\Platform\DuerOS\Messages\AbsCard;
use Commune\Support\Uuid\HasIdGenerator;
use Commune\Support\Uuid\IdGeneratorHelper;

class CueWordsCard extends AbsCard implements HasIdGenerator
{
    use IdGeneratorHelper;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string[]
     */
    protected $suggestions = [];

    /**
     * CueWordsCard constructor.
     * @param string $title
     * @param string $content
     * @param string[] $suggestions
     */
    public function __construct(string $title, string $content, array $suggestions)
    {
        $this->title = $title;
        $this->content = $content;
        $this->suggestions = $suggestions;
        parent::__construct();
    }

    public function __sleep(): array
    {
        $fields =  parent::__sleep();
        return array_merge($fields, ['title', 'content', 'suggestions']);
    }


    public function toCardArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'cueWords' => $this->suggestions
        ];
    }

    public function toDuerOSCard(): BaseCard
    {
        // sdk 开发不太符合语法规范, 有条件自己重做一个算了.
        $card = new StandardCard();
        $card->addCueWords($this->suggestions);
        $card->setTitle($this->title);
        $card->setContent($this->content);
        return $card;
    }

    public static function mock()
    {
        return new static('title', 'content', ['a', 'b']);
    }


}