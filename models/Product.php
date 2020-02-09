<?php

declare(strict_types=1);

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int             $id
 * @property string          $name
 * @property boolean          $exist
 *
 * @property PrizePhysical[] $prizePhysicals
 */
class Product extends \yii\db\ActiveRecord {
	const ATTR_ID    = 'id';
	const ATTR_NAME  = 'name';
	const ATTR_EXIST = 'exist';

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'product';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name', 'exist'],'required'],
			[['name'],'string','max' => 255],
			['exist','boolean'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'   => 'ID',
			'name' => 'Name',
		];
	}

	/**
	 * Gets query for [[PrizePhysicals]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getPrizePhysicals() {
		return $this->hasMany(PrizePhysical::className(), ['product_id' => 'id']);
	}
}
