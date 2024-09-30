<?php

use BigBrother\enums\ReportStatus;
use yii\data\Pagination;

/* @var $reportedContent BigBrother\models\Report[] */
/* @var $pagination Pagination */
/* @var $search array */

function isChecked(array $search, $status) : bool
{
    return in_array($status, $search['status']);
}

?>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>BigBrother</strong></div>
        <div class="panel-body">
            <p><?= Yii::t('BigBrotherModule.base', 'Welcome to the admin only area.') ?></p>

            <form>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="status[]" value="<?= ReportStatus::Open ?>" <?= isChecked($search, ReportStatus::Open) ? 'checked="checked"' : '' ?>/><?= ReportStatus::toString(ReportStatus::Open) ?>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="status[]" value="<?= ReportStatus::Closed ?>" <?= isChecked($search, ReportStatus::Closed) ? 'checked="checked"' : '' ?>/><?= ReportStatus::toString(ReportStatus::Closed) ?>
                    </label>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>

            <?= $this->render('/admin/reportContentAdminGrid', ['isAdmin' => 1, 'reportedContent' => $reportedContent, 'pagination' => $pagination]) ?>
        </div>
    </div>
</div>
