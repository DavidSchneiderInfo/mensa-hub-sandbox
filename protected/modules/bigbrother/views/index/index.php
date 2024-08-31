<?php

use humhub\widgets\Button;

// Register our module assets, this could also be done within the controller
\BigBrother\assets\Assets::register($this);

$displayName = (Yii::$app->user->isGuest) ? Yii::t('BigBrotherModule.base', 'Guest') : Yii::$app->user->getIdentity()->displayName;

// Add some configuration to our js module
$this->registerJsConfig("BigBrother", [
    'username' => (Yii::$app->user->isGuest) ? $displayName : Yii::$app->user->getIdentity()->username,
    'text' => [
        'hello' => Yii::t('BigBrotherModule.base', 'Hi there {name}!', ["name" => $displayName])
    ]
])

?>

<div class="panel-heading"><strong>BigBrother</strong> <?= Yii::t('BigBrotherModule.base', 'overview') ?></div>

<div class="panel-body">
    <p><?= Yii::t('BigBrotherModule.base', 'Hello World!') ?></p>

    <?=  Button::primary(Yii::t('BigBrotherModule.base', 'Say Hello!'))->action("BigBrother.hello")->loader(false); ?></div>
