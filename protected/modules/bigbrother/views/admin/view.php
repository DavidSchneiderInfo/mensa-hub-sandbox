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
                    if($report->comment_id) {
                        echo $report->message;
                    } else {
                        $content = Content::findOne(['id'=>$report->content_id]);
                        switch(get_class($content->getModel())) {
                            case Poll::class:
                                $poll = json_decode($report->message, true);
                                if(is_array($poll)) {
                                    echo array_key_exists('question', $poll) ? "Question: " . $poll['question'] . "<br/>" : '';
                                    echo array_key_exists('description', $poll) ? "Description: " . $poll['description'] . "<br/>" : '';
                                    if(array_key_exists('answers', $poll)) {
                                        echo "Answers:<br/><ul>";
                                        foreach ($poll['answers'] as $answer)
                                        {
                                            echo "<li>".$answer."</li>";
                                        }
                                        echo "</ul>";
                                    }
                                } else {
                                    echo "<pre>";
                                    var_dump($poll, $report);
                                    echo "</pre>";
                                }
                                break;
                            default:
                                echo $report->message;
                                break;
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>
