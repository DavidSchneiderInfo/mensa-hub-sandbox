<?php

use BigBrother\models\Report;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;

/**
 * @var $model Report
 */

?>

<?php ModalDialog::begin(['header' => '<strong>Report</strong> Content']); ?>
<?php $form = ActiveForm::begin(['id' => 'report-content-form']); ?>
<div class="modal-body">
    <?= $form->field($model, 'content_id')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'comment_id')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'reason')->radioList($model->getReasons(true)); ?>
</div>
<div class="modal-footer">
    <?= ModalButton::submitModal(null, 'Send') ?>
    <?= ModalButton::cancel() ?>
</div>
<?php ActiveForm::end() ?>
<?php ModalDialog::end(); ?>
