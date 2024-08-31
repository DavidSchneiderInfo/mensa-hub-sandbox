<?php

use yii\data\Pagination;

/* @var $reportedContent BigBrother\models\Report[] */
/* @var $pagination Pagination */

?>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>BigBrother</strong></div>

        <div class="panel-body">
            <p><?= Yii::t('BigBrotherModule.base', 'Welcome to the admin only area.') ?></p>
            <?= $this->render('/admin/reportContentAdminGrid', ['isAdmin' => 1, 'reportedContent' => $reportedContent, 'pagination' => $pagination]) ?>
        </div>
    </div>
</div>
