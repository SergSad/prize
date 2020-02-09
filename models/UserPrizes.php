<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "user_prizes".
 *
 * @property int         $id
 * @property int         $user_id
 * @property string      $prize_type
 * @property boolean     $is_send
 *
 * @property ActiveQuery $prize
 * @property User        $user
 */
class UserPrizes extends \yii\db\ActiveRecord {
	const ATTR_ID         = 'id';
	const ATTR_USER_ID    = 'user_id';
	const ATTR_PRIZE_TYPE = 'prize_type';
	const ATTR_IS_SEND    = 'is_send';

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'user_prizes';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			// Сюда надо константы подставить
			[['user_id','prize_type'],'required'],
			[['user_id'],'integer'],
			[['prize_type'],'string','max' => 255],
			[['user_id'],'exist','skipOnError' => true,	'targetClass' => User::className(),
				'targetAttribute' => ['user_id' => 'id']
			],
			['is_send','boolean'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		// Сюда тоже константы
		return [
			'id'         => 'ID',
			'user_id'    => 'User ID',
			'prize_type' => 'Prize Type',
			'is_send'    => 'Is Send',
		];
	}

	/**
	 * @return ActiveQuery
	 * @throws \Exception
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function getPrize() {
		switch ($this->prize_type) {
			case PrizeBonus::TYPE:
				return $this->hasOne(PrizeBonus::class, ['user_prize_id' => 'id']);
				break;
			case PrizeMoney::TYPE:
				return $this->hasOne(PrizeMoney::class, ['user_prize_id' => 'id']);
				break;
			case PrizePhysical::TYPE:
				return $this->hasOne(PrizePhysical::class, ['user_prize_id' => 'id']);
				break;
		}

		throw new \Exception('Неизвестный тип приза');
	}

	/**
	 * Gets query for [[User]].
	 *
	 * @return ActiveQuery
	 */
	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
}
