<?php

use humhub\libs\Html;
use humhub\modules\content\models\Content;
use humhub\modules\polls\models\Poll;

/* @var $report BigBrother\models\Report */

?>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>BigBrother</strong> - Review reported content</div>
        <div class="panel-body">
            <strong>Reported Content</strong>:<br/>
            <div class="well">
                <?php
                    $content = Content::findOne(['id'=>$report->content_id]);
                    switch(get_class($content->getModel())) {
                        case Poll::class:
                            $poll = json_decode($report->message);
                            echo "Question: " . $poll->question . "<br/>";
                            echo "Description: " . $poll->description . "<br/>";
                            echo "Answers:<br/><ul>";
                            foreach ($poll->answers as $answer)
                            {
                                echo "<li>".$answer."</li>";
                            }
                            echo "</ul>";
                            break;
                        default:
                            echo $report->message;
                            break;
                    }
                ?>
            </div>
        </div>
    </div>
</div>
