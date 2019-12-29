<?php

/**
 * Class QuestionCard
 * @package Commune\Platform\DuerOS\Messages\Cards
 */

namespace Commune\Platform\DuerOS\Messages\Cards;


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


    public function toCardArray(): array
    {
        return [
            'type' => 'standard',
            'title' => $this->title,
            'content' => $this->content,
            'cueWords' => $this->suggestions
        ];
    }


}