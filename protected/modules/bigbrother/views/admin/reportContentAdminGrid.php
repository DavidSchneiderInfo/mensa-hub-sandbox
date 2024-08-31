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
        <?= Yii::t('ReportcontentModule.base', 'There is no content reported for review.') ?>
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
                'label' => 'Content',
                'value' => function (Report $report) {
                    $link = Html::a(
                        '<i aria-hidden="true" class="fa fa-eye"></i>',
                        [
                            '/BigBrother/admin/view',
                            'id' => $report->id
                        ],
                        [
                            'class' => 'btn btn-sm btn-primary tt',
                            'title' => Yii::t('ReportcontentModule.base', 'Review'),
                            'data-ui-loader' => '1'
                        ]
                    );
                    return substr($report->message, 0, 60) . $link;
                }
            ],

            // Reason
            [
                'class' => DataColumn::class,
                'label' => 'Reason',
                'options' => ['style' => 'width:120px;'],
                'format' => 'raw',
                'value' => function (Report $report) {
                    return '<strong>' . Html::encode($report->getReason()) . '</strong>';
                }
            ],

            // actions
            [
                'class' => DataColumn::class,
                'format' => 'raw',
                'options' => ['style' => 'width:85px;'],
                'value' => function ($report) use ($isAdmin) {
                    $approve = Html::a(
                        '<i class="fa fa-check-square-o"></i>', ['/BigBrother/report/appropriate', 'id' => $report->id, 'admin' => $isAdmin],
                        ['data-method' => 'POST', 'class' => 'btn btn-success btn-sm tt', 'data-original-title' => 'Approve']
                    );

                    $review = Html::a('<i aria-hidden="true" class="fa fa-eye"></i>', $report->content->getUrl(), [
                        'class' => 'btn btn-sm btn-primary tt',
                        'title' => Yii::t('ReportcontentModule.base', 'Review'),
                        'data-ui-loader' => '1'
                    ]);

                    return $approve . ' ' . $review;
                }
            ],
        ],
    ]); ?>
<?php endif; ?>
