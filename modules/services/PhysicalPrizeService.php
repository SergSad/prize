<?php

declare(strict_types=1);

namespace app\modules\services;

use app\models\User;
use yii\db\Exception;

class PhysicalPrizeService {

	/**
	 * @param $userId
	 *
	 * @return bool
	 * @throws Exception
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function sendItemByMail($userId){
		// Отправить посылку
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
}