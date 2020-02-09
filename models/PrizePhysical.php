<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prize_physical".
 *
 * @property int $user_prize_id
 * @property int $product_id
 *
 * @property Product $product
 * @property UserPrizes $userPrize
 */
class PrizePhysical extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prize_physical';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_prize_id', 'product_id'], 'required'],
            [['user_prize_id', 'product_id'], 'integer'],
            [['user_prize_id'], 'unique'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'product_id' => 'Product ID',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
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
