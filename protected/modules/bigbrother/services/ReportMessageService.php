<?php

declare(strict_types=1);

namespace BigBrother\services;

use humhub\modules\content\models\Content;
use humhub\modules\polls\models\Poll;
use humhub\modules\post\models\Post;

class ReportMessageService
{
    private ?Content $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    public function getMessage(): string
    {
        $model = $this->content->getModel();
        return match (get_class($model)) {
            Post::class => $model->getAttribute('message'),
            Poll::class => $this->getPollMessage($model),
            default => "Unimplemented model " . get_class($model),
        };
    }

    protected function getPollMessage(Poll $poll): string
    {
        $answers = [];
        foreach ($poll->answers as $pollAnswer)
        {
            /* @var $pollAnswer \humhub\modules\polls\models\PollAnswer */
            $answers[] = $pollAnswer->answer;
        }

        return json_encode([
            'question' => $poll->question,
            'description' => $poll->description,
            'answers' => $answers
        ]);
    }
}
