<?php

use humhub\libs\Html;

/* @var $report BigBrother\models\Report */

?>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>BigBrother</strong> - Review reported content</div>
        <div class="panel-body">
            <strong>Reported Content</strong>:<br/>
            <div class="well">
                <?php echo Html::encode($report->message); ?>
            </div>
        </div>
    </div>
</div>
