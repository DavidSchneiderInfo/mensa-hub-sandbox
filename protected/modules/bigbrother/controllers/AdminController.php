<?php

namespace BigBrother\controllers;

use BigBrother\models\Report;
use humhub\modules\admin\components\Controller;
use yii\data\Pagination;
use Yii;

class AdminController extends Controller
{
    /**
     * Render admin only page
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Report::find()->readable();
        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $query->offset($pagination->offset)->limit($pagination->limit);

        return $this->render('index', [
            'reportedContent' => $query->all(),
            'pagination' => $pagination,
        ]);
    }

    public function actionView()
    {
        $reportId = Yii::$app->request->get('id');
        $report = Report::findOne(['id' => $reportId]);

        return $this->render('view', [
            'report' => $report,
        ]);
    }
}

