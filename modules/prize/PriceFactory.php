<?php

declare(strict_types=1);

namespace app\modules\prize;

use app\models\UserPrizes;
use Yii;
use yii\db\Exception;

abstract class PriceFactory {
	/**
	 * @return PrizeInterface
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	abstract public function getPrize(): PrizeInterface;

	/**
	 * Сгенерировать приз
	 *
	 * @throws Exception
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function generatePrize() {
		$product = $this->getPrize();
		$userId  = Yii::$app->getUser()->id;

		$userPrize = UserPrizes::findOne([UserPrizes::ATTR_USER_ID => $userId]);
		if (null !== $userPrize) {
			return $userPrize;
		}

		$userPrize             = new UserPrizes();
		$userPrize->user_id    = Yii::$app->getUser()->id;
		$userPrize->prize_type = $product->getType();

		if (false === $userPrize->save()) {
			throw new Exception($userPrize);
		}

		$product->save($userPrize->id);

		return true;
	}

}