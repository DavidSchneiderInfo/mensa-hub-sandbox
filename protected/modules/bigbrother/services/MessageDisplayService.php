<?php

declare(strict_types=1);

namespace BigBrother\services;

use BigBrother\models\Report;
use humhub\modules\content\models\Content;
use humhub\modules\polls\models\Poll;
use humhub\modules\post\models\Post;

class MessageDisplayService
{
    public function __construct(Report $report)
    {
        $content = Content::findOne(['id'=>$report->content_id]);

    }
}
