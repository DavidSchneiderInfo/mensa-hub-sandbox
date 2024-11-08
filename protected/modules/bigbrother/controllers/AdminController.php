<?php

namespace BigBrother\controllers;

use BigBrother\enums\ReportStatus;
use BigBrother\models\Report;
use humhub\modules\admin\components\Controller;
use yii\data\Pagination;
use Yii;
use yii\db\Exception;

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
        $search = array_merge(Yii::$app->session->get('bigbrother.search') ?? [
            'status'=> [
                ReportStatus::Open,
            ],
        ], Yii::$app->request->queryParams);

        if(array_key_exists('status', $search)) {
            $query->where(['in', 'status', $search['status']]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $query->offset($pagination->offset)->limit($pagination->limit);

        return $this->render('index', [
            'reportedContent' => $query->all(),
            'pagination' => $pagination,
            'search' => $search,
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

    /**
     * @throws Exception
     */
    public function actionClose()
    {
        $reportId = Yii::$app->request->get('id');
        $report = Report::findOne(['id' => $reportId]);
        $report->status = ReportStatus::Closed;
        $report->save();

        return $this->redirect('/BigBrother/admin/index');
    }

    /**
     * @throws Exception
     */
    public function actionOpen()
    {
        $reportId = Yii::$app->request->get('id');
        $report = Report::findOne(['id' => $reportId]);
        $report->status = ReportStatus::Open;
        $report->save();

        return $this->redirect(['/BigBrother/admin/view', 'id'=> $reportId]);
    }

    /**
     * @throws Exception
     */
    public function actionRemove()
    {
        $reportId = Yii::$app->request->get('id');
        $report = Report::findOne(['id' => $reportId]);

        $report->content->delete();

        $report->status = ReportStatus::Removed;
        $report->save();

        return $this->redirect('/BigBrother/admin/index');
    }
}

