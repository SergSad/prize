<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_prizes".
 *
 * @property int $id
 * @property int $user_id
 * @property string $prize_type
 *
 * @property PrizeBonus $prizeBonus
 * @property PrizeMoney $prizeMoney
 * @property PrizePhysical $prizePhysical
 * @property User $user
 */
class UserPrizes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_prizes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'prize_type'], 'required'],
            [['user_id'], 'integer'],
            [['prize_type'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'prize_type' => 'Prize Type',
        ];
    }

    /**
     * Gets query for [[PrizeBonus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrizeBonus()
    {
        return $this->hasOne(PrizeBonus::className(), ['user_prize_id' => 'id']);
    }

    /**
     * Gets query for [[PrizeMoney]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrizeMoney()
    {
        return $this->hasOne(PrizeMoney::className(), ['user_prize_id' => 'id']);
    }

    /**
     * Gets query for [[PrizePhysical]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrizePhysical()
    {
        return $this->hasOne(PrizePhysical::className(), ['user_prize_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
