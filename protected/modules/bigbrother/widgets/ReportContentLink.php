<?php

namespace BigBrother\widgets;

use humhub\components\Widget;
use humhub\libs\Html;
use BigBrother\helpers\Permission;
use humhub\modules\content\components\ContentActiveRecord;
use yii\helpers\Url;

class ReportContentLink extends Widget
{

    /**
     * @var ContentActiveRecord
     */
    public $record;

    /**
     * Executes the widget.
     */
    public function run()
    {
        if (Permission::canReportContent($this->record)) {
            $reportUrl = Url::to(['/BigBrother/report', 'contentId' => $this->record->content->id]);

            return Html::tag('li',
                Html::tag('a',
                    '<i class="fa fa-exclamation-circle"></i>' . 'Report',
                    ['data-action-click' => 'ui.modal.load', 'data-action-click-url' => $reportUrl])
            );
        }
    }

}
