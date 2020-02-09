<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prize_bonus".
 *
 * @property int $user_prize_id
 * @property int $value
 *
 * @property UserPrizes $userPrize
 */
class PrizeBonus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prize_bonus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_prize_id', 'value'], 'required'],
            [['user_prize_id', 'value'], 'integer'],
            [['user_prize_id'], 'unique'],
            [['user_prize_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserPrizes::className(), 'targetAttribute' => ['user_prize_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_prize_id' => 'User Prize ID',
            'value' => 'Value',
        ];
    }

    /**
     * Gets query for [[UserPrize]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserPrize()
    {
        return $this->hasOne(UserPrizes::className(), ['id' => 'user_prize_id']);
    }
}
