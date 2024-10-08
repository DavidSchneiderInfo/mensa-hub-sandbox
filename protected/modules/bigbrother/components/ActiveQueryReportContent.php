<?php

namespace BigBrother\components;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\Content;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;

class ActiveQueryReportContent extends ActiveQuery
{
    public function readable(?ContentContainerActiveRecord $container = null): self
    {
        if (Yii::$app->user->isGuest) {
            $this->andWhere(new Expression('FALSE'));
        }

        $this->joinWith('content')->andWhere(['content.state' => Content::STATE_PUBLISHED]);

        if ($container !== null) {
            $this->andWhere(['content.contentcontainer_id' => $container->contentcontainer_id])
                ->andWhere(['not', ['content.created_by' => Yii::$app->user->id]]);
        }

        return $this;
    }
}
