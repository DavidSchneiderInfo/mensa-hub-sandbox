<?php

namespace  BigBrother;

use BigBrother\enums\ReportStatus;
use BigBrother\helpers\Permission;
use BigBrother\models\Report;
use BigBrother\widgets\ReportContentLink;
use humhub\modules\comment\widgets\CommentControls;
use humhub\modules\ui\menu\MenuLink;
use Yii;
use yii\helpers\Url;

class Events
{
    /**
     * The menu entry in the Administration menu. This will open the module config page.
     *
     * @param $event
     */
    public static function onAdminMenuInit($event)
    {
        $count = Report::find()
            ->where(['status'=>ReportStatus::Open])
            ->count();
        $badge = $count>0
            ? '<div class="label label-danger label-notifications">'.$count.'</div>'
            : '';
        $event->sender->addItem([
            'label' => 'BigBrother '.$badge,
            'url' => Url::to(['/BigBrother/admin']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-exclamation-triangle"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'BigBrother' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 99999,
        ]);
    }

    /**
     * Menu entry for a comment
     *
     * @param $event
     * @return void
     */
    public static function onCommentControlsInit($event)
    {
        /** @var CommentControls $menu */
        $menu = $event->sender;

        if (!Permission::canReportComment($menu->comment)) {
            return;
        }

        $menu->addEntry(new MenuLink([
            'label' => 'Report',
            'icon' => 'fa-exclamation-triangle',
            'url' => '#',
            'htmlOptions' => [
                'data-action-click' => 'ui.modal.load',
                'data-action-click-url' => Url::to([
                    '/BigBrother/report', 'contentId' => $menu->comment->content->id,
                    'commentId' => $menu->comment->id,
                ])
            ],
            'sortOrder' => 1000,
        ]));
    }

    public static function onWallEntryControlsInit($event)
    {
        $event->sender->addWidget(ReportContentLink::class, [
            'record' => $event->sender->object
        ]);
    }
}
