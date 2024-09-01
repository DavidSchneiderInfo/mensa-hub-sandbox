<?php

use BigBrother\models\Report;
use humhub\modules\ui\view\components\View;
use humhub\widgets\GridView;
use yii\data\ArrayDataProvider;
use humhub\modules\user\widgets\Image as UserImage;
use yii\data\Pagination;
use yii\grid\DataColumn;
use humhub\libs\Html;

/* @var $this View */
/* @var $reportedContent BigBrother\models\Report[] */
/* @var $isAdmin boolean */
/* @var $pagination Pagination */

?>
<?php if (empty($reportedContent)) : ?>
    <br/>
    <p class="alert alert-success">
        There is no content reported for review.
    </p>
<?php else : ?>
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider(['allModels' => $reportedContent]),
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            // User info
            [
                'class' => DataColumn::class,
                'format' => 'raw',
                'label' => 'Author',
                'value' => function ($report) {
                    return UserImage::widget(['user' => $report->content->createdBy, 'width' => 34]);
                }
            ],
            // Message
            [
                'class' => DataColumn::class,
                'format' => 'raw',
                'label' => 'Reporter',
                'value' => function (Report $report) {
                    return UserImage::widget(['user' => $report->createdBy, 'width' => 34]);
                }
            ],

            // Reason
            [
                'class' => DataColumn::class,
                'label' => 'Reason',
                'format' => 'raw',
                'value' => function (Report $report) {
                    return '<strong>' . Html::encode($report->getReason()) . '</strong>';
                }
            ],

            // actions
            [
                'class' => DataColumn::class,
                'format' => 'raw',
                'label' => 'Actions',
                'value' => function ($report) use ($isAdmin) {
                    $approve = Html::a(
                        '<i class="fa fa-check-square-o"></i>', ['/BigBrother/report/appropriate', 'id' => $report->id, 'admin' => $isAdmin],
                        ['data-method' => 'POST', 'class' => 'btn btn-success btn-sm tt', 'data-original-title' => 'Approve']
                    );

                    $review = Html::a('<i aria-hidden="true" class="fa fa-eye"></i>', $report->content->getUrl(), [
                        'class' => 'btn btn-sm btn-primary tt',
                        'title' => 'Review current',
                        'data-ui-loader' => '1'
                    ]);

                    $showReport = Html::a(
                        '<i aria-hidden="true" class="fa fa-history"></i>',
                        [
                            '/BigBrother/admin/view',
                            'id' => $report->id
                        ],
                        [
                            'class' => 'btn btn-sm btn-primary tt',
                            'title' => 'Review reported',
                            'data-ui-loader' => '1'
                        ]
                    );

                    return $approve . ' ' . $review . ' ' . $showReport;
                }
            ],
        ],
    ]); ?>
<?php endif; ?>
