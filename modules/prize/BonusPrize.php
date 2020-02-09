<?php

declare(strict_types=1);

namespace app\modules\prize;

use app\models\PrizeBonus;
use app\models\User;
use app\modules\component\Settings;
use Throwable;
use Yii;
use yii\db\Exception;

class BonusPrize implements PrizeInterface {
	/** @var PrizeBonus */
	private $prize;

	/**
	 * {@inheritDoc}
	 */
	public function __construct() {
		$this->prize        = new PrizeBonus();
		$min                = (int)Settings::get(Settings::ATTR_BONUS_MIN);
		$max                = (int)Settings::get(Settings::ATTR_BONUS_MAX);
		$this->prize->value = rand($min, $max);
	}

	/**
	 * @return string
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function getType(): string {
		return $this->prize::TYPE;
	}

	/**
	 * @param int $userPriceId
	 *
	 * @return bool
	 * @throws Throwable
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function save(int $userPriceId): bool {
		$transaction = PrizeBonus::getDb()->beginTransaction();
		try {
			$this->prize->user_prize_id = $userPriceId;

			if (false === $this->prize->save()) {
				throw new Exception($this->prize);
			}

			/** @var User $userModel */
			$userModel                  = Yii::$app->user->identity;
			$userModel->accounts_points = $userModel->accounts_points + $this->prize->value;
			if (false === $userModel->save()) {
				throw new Exception($userModel);
			}

			$transaction->commit();
		}
		catch (Throwable $e) {
			$transaction->rollBack();
			throw $e;
		}

		return true;
	}
}