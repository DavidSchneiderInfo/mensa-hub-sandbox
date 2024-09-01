<?php

namespace BigBrother\controllers;

use BigBrother\models\Report;
use BigBrother\services\ReportMessageService;
use humhub\modules\admin\components\Controller;
use humhub\modules\comment\models\Comment;
use humhub\modules\content\models\Content;
use humhub\widgets\ModalClose;
use Yii;
use yii\helpers\Url;

class ReportController extends Controller
{
    /**
     * Render admin only page
     *
     * @return string
     */
    public function actionIndex()
    {
        $contentId = (int)Yii::$app->request->get('contentId');
        $commentId = Yii::$app->request->get('commentId');
        $userId = (int)Yii::$app->user->id;
        $message = $commentId == null
            ? $this->getContentMessage($contentId)
            : Comment::findOne(['id'=>$commentId])->message;

        $model = Report::findOne(['content_id' => $contentId, 'comment_id' => $commentId, 'message' => $message]);
        if($model === null) {
            $model = new Report();
            $model->content_id = $contentId;
            $model->comment_id = $commentId;
            $model->created_by = $userId;
            $model->message = $message;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ModalClose::widget(['success' => 'Content successfully reported.']);
        }

        return $this->renderAjax('index', [
            'model' => $model,
        ]);
    }

    private function getContentMessage(int $contentId): mixed
    {
        $content = Content::findOne(['id' => $contentId]);

        $service = new ReportMessageService($content);

        return $service->getMessage();
    }

    public function actionAppropriate()
    {
        $this->forcePostRequest();

        $reportId = Yii::$app->request->get('id');
        $report = Report::findOne(['id' => $reportId]);

        $container = $report->content->getContainer();

        if ($report->canDelete(Yii::$app->user->getIdentity())) {
            $report->delete();
        } else {
            $this->view->warn('Could not delete Report!');

        }

        if (Yii::$app->request->get('admin')) {
            return $this->htmlRedirect(Url::to(['/BigBrother/admin']));
        } else {
            return $this->htmlRedirect($container->createUrl('/BigBrother/space-admin'));
        }
    }
}

