<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\PrizeMoney;
use app\models\UserPrizes;
use app\modules\services\MoneyPrizeService;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class HelloController extends Controller {

	/**
	 * Отправлять денежные призы на счета пользователей, которые еще не были отправлены пачками по N штук.
	 *
	 * @param string $n
	 *
	 * @return int
	 * @throws \yii\db\Exception
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function actionIndex($n = '10') {
		/** @var UserPrizes $prize */
		foreach (UserPrizes::find()->where([
			UserPrizes::ATTR_PRIZE_TYPE => PrizeMoney::TYPE,
			UserPrizes::ATTR_IS_SEND    => false
		])->each($n) as $prize) {
			MoneyPrizeService::sendMoneyToBank($prize->user_id);
		}

		return ExitCode::OK;
	}
}
