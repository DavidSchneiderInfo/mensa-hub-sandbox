<?php

use humhub\modules\polls\models\Poll;
use humhub\widgets\Link;
use humhub\libs\Html;

/* @var $report BigBrother\models\Report */
$isAdmin = Yii::$app->user->isAdmin();
?>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Meta</strong></div>
        <div class="panel-body">
            <table class="table-bordered table-striped table-condensed" style="width: 100%;">
                <tr>
                    <td>
                        <strong>
                            Reported Author
                        </strong>
                    </td>
                    <td>
                        <?php echo Link::asLink(
                            $report->content->createdBy->displayName,
                            '/u/'.$report->content->createdBy->username
                        ); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            Reported by
                        </strong>
                    </td>
                    <td>

                        <?php echo Link::asLink(
                            $report->createdBy->displayName,
                            '/u/'.$report->createdBy->username
                        ); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            Created at:
                        </strong>
                    </td>
                    <td>
                        <?php echo \humhub\libs\DateHelper::parseDateTime($report->created_at, 'Y-m-d, H:i'); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            Updated by
                        </strong>
                    </td>
                    <td>

                        <?php
                        echo $report->updatedBy
                            ? Link::asLink(
                                $report->updatedBy->displayName,
                                '/u/'.$report->updatedBy->username
                            )
                            : '';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            Updated at
                        </strong>
                    </td>
                    <td>
                        <?php echo \humhub\libs\DateHelper::parseDateTime($report->updated_at, 'Y-m-d, H:i'); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            Status:
                        </strong>
                    </td>
                    <td>
                        <?php echo $report->getStatus(); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            Content Type:
                        </strong>
                    </td>
                    <td>
                        <?php echo basename(str_replace('\\', '/', $report->getType())); ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="panel-heading">
            <strong>Content</strong>
        </div>
        <div class="panel-body">
            <div class="fa-border borderDanger">
                <?php
                    switch($report->getType()) {
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
                ?>
            </div>
            <?php
            echo Html::a(
                'Show current content', [$report->content->getUrl()],
                ['class' => 'btn btn-warning btn-sm', 'data-original-title' => 'Show current content']
            );
            ?>
        </div>
        <div class="panel-heading">
            <strong>Actions</strong>
        </div>
        <div class="panel-body">
            <table class="table table-condensed">
                <tr>
                    <td>
                        <?php
                        echo Html::a(
                            'Deescalate <i class="fa fa-check-square-o"></i>', ['/BigBrother/report/appropriate', 'id' => $report->id, 'admin' => $isAdmin],
                            ['data-method' => 'POST', 'class' => 'btn btn-success btn-sm tt', 'data-original-title' => 'Deescalate']
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $report->status == \BigBrother\enums\ReportStatus::Closed
                            ? Html::a(
                                'Open the ticket <i class="fa fa-check-square-o"></i>', ['/BigBrother/admin/open', 'id'=>$report->id],
                                ['data-method' => 'POST', 'class' => 'btn btn-primary btn-sm tt', 'data-original-title' => 'Open the ticket']
                            )
                            : Html::a(
                                'Close the ticket <i class="fa fa-check-square-o"></i>', ['/BigBrother/admin/close', 'id'=>$report->id],
                                ['data-method' => 'POST', 'class' => 'btn btn-primary btn-sm tt', 'data-original-title' => 'Close the ticket']
                            );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo Html::a(
                            'Something else<i class="fa fa-check-square-o"></i>', ['', 'id'=>$report->id], ['class' => 'btn btn-primary btn-sm tt']
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo Html::a(
                            'Something else<i class="fa fa-check-square-o"></i>', ['', 'id'=>$report->id], ['class' => 'btn btn-primary btn-sm tt']
                        );
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
