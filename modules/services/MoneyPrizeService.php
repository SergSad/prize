<?php

declare(strict_types=1);

namespace app\modules\services;

use app\models\PrizeMoney;
use app\models\User;
use app\modules\component\Settings;
use Throwable;
use Yii;
use yii\db\Exception;

class MoneyPrizeService {

	/**
	 * @param $userId
	 *
	 * @return bool
	 * @throws Exception
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public static function sendMoneyToBank($userId) {
		// Отправить деньги
		// Тут мы отправляем запрос в банк
		// и если он ответит что все ок то сохраняем у себя ок
		/** @var User $userModel */
		$userModel          = User::findOne([User::ATTR_ID => $userId]);
		$userPrize          = $userModel->userPrizes;
		$userPrize->is_send = true;

		if (false === $userPrize->save()) {
			throw new Exception($userPrize);
		}

		return true;
	}

	/**
	 * @throws Exception
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public static function convertToBonus() {
		$transaction = Yii::$app->db->beginTransaction();
		try {
			/** @var User $userModel */
			$userModel = Yii::$app->user->identity;
			$userPrize = $userModel->userPrizes;
			$prize     = $userPrize->getPrize();

			if ($prize instanceof PrizeMoney) {
				$userModel->accounts_points = $prize->value * (int)Settings::get(Settings::ATTR_COEFFICIENT);
				$userPrize->is_send         = true;
			}

			if (false === $userModel->save()) {
				throw new Exception($userModel);
			}

			if (false === $userPrize->save()) {
				throw new Exception($userPrize);
			}

			$transaction->commit();
		}
		catch (Throwable $e) {
			$transaction->rollBack();
			throw $e;
		}
	}
}