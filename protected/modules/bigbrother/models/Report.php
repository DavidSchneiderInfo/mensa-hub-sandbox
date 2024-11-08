<?php

namespace BigBrother\models;

use BigBrother\enums\ReportStatus;
use humhub\components\ActiveRecord;
use humhub\modules\comment\models\Comment;
use humhub\modules\content\permissions\ManageContent;
use BigBrother\components\ActiveQueryReportContent;
use BigBrother\helpers\Permission;
//use BigBrother\notifications\NewReportAdmin;
use humhub\modules\space\models\Membership;
use humhub\modules\user\models\Group;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\content\models\Content;
use Yii;
use yii\base\InvalidArgumentException;

/**
 * This is the model class for table "report_content".
 *
 * The followings are the available columns in table 'report_content':
 * @property integer $id
 * @property integer $content_id
 * @property integer $comment_id
 * @property integer $reason
 * @property string $message
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property User $user
 * @property Content $content
 */
class Report extends ActiveRecord
{
    const REASON_NOT_BELONG = 1;
    const REASON_OFFENSIVE = 2;
    const REASON_SPAM = 3;
    const REASON_INCORRECT = 4;
    const REASON_FILTER = 10;

    /**
     *
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'reported_content';
    }


    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['content_id', 'reason'], 'required'],
            [['reason'], function ($attribute, $params, $validator) {
                $content = Content::findOne(['id' => $this->content_id]);
                $user = User::findOne(['id' => $this->created_by]);
                if (!$content || !$user || !$content->canView($user)) {
                    throw new InvalidArgumentException('Content or User cannot be null and must be visible!');
                }

                if (!empty($this->comment_id)) {
                    $comment = Comment::findOne(['id' => $this->comment_id]);
                    if (!$comment) {
                        throw new InvalidArgumentException('Comment not found!');
                    }
                    if (!Permission::canReportComment($comment, $user)) {
                        $this->addError('reason', 'You cannot report this comment!');
                    }
                } elseif (!Permission::canReportContent($content->getModel(), $user)) {
                    $this->addError('reason', 'You cannot report this content!');
                }
            }]
        ];
    }


    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'reason' => 'For what reason do you want to report this content?'
        ];
    }

    public function beforeSave($insert)
    {
        $content = Content::findOne(['id' => $this->content_id]);
        $contentContainer = $content->container;

        if (!empty($this->comment_id)) {
            /** @var Comment $comment */
            $comment = Comment::find()->where(['id' => $this->comment_id])->one();
            if (!$comment || $comment->getContent()->id != $this->content_id) {
                throw new \Exception('Specified comment is not linked to given content');
            }
        }

        $noCreator = empty($this->created_by);
        $beforeSave = parent::beforeSave($insert);
        if ($noCreator) {
            $this->created_by = null;
        }
        return $beforeSave;
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            if ($this->content->container instanceof Space) {
                $query = Membership::getSpaceMembersQuery($this->content->container)
                    ->andWhere(['IN', 'group_id', [Space::USERGROUP_OWNER, Space::USERGROUP_ADMIN, Space::USERGROUP_MODERATOR]]);
            } else {
                $query = Group::getAdminGroup()->getUsers();
            }

//            $notification = new NewReportAdmin;
//            $notification->source = $this;
//            $notification->originator = (!empty($this->created_by)) ? User::findOne(['id' => $this->created_by]) : null;
//            $notification->sendBulk($query);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new ActiveQueryReportContent(get_called_class());
    }

    public function getReason(): string
    {
        return $this->getReasons()[$this->reason] ?? '';
    }

    public function getStatus(): string
    {
        return ReportStatus::toString($this->status);
    }

    public function getReasons($selectable = false): array
    {
        $reasons = [];

        if ($this->content->container instanceof Space) {
            $reasons[Report::REASON_NOT_BELONG] = 'Wrong Space';
        }

        $reasons[Report::REASON_INCORRECT] = 'Misleading';
        $reasons[Report::REASON_OFFENSIVE] = 'Offensive';
        $reasons[Report::REASON_SPAM] = 'Spam';

        if ($selectable) {
            return $reasons;
        }

        $reasons[Report::REASON_FILTER] = 'Profanity Filter';

        return $reasons;
    }

    public function canDelete(?User $user = null)
    {
        if ($user === null) {
            return false;
        }

        if (Permission::canManageReports($this->content->container, $user)) {
            return true;
        }

        return false;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getContent()
    {
        return $this->hasOne(Content::class, ['id' => 'content_id']);
    }

    public function getComment()
    {
        return $this->hasOne(Comment::class, ['id' => 'comment_id']);
    }

    public function getType(): string
    {
        return $this->comment_id != null
            ? Comment::class
            : get_class($this->content->getModel());
    }
}

?>
