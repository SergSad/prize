<?php

declare(strict_types=1);

namespace app\modules\prize;

use app\models\PrizeMoney;
use app\modules\component\Settings;
use Throwable;
use yii\db\Exception;

class MoneyPrize implements PrizeInterface {
	/** @var PrizeMoney */
	private $prize;

	/** @var int Количество денег */
	private $moneyLimit;

	/**
	 * {@inheritDoc}
	 */
	public function __construct() {
		$this->prize = new PrizeMoney();
		$this->moneyLimit = (int) Settings::get(Settings::ATTR_MONEY);
		$min = (int) Settings::get(Settings::ATTR_MONEY_MIN);
		$max = (int) Settings::get(Settings::ATTR_MONEY_MAX);

		// Если денег меньше чем максимальное число интервала, то отдаем все деньги.
		// Ну тут уже на выбор
		if ($this->moneyLimit > $max) {
			$this->prize->value = rand($min, $max);
		}
		else {
			$this->prize->value = $this->moneyLimit;
		}

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
	 * @throws Exception
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function save(int $userPriceId): bool {
		$transaction = PrizeMoney::getDb()->beginTransaction();
		try {
			$this->prize->user_prize_id = $userPriceId;

			if (false === $this->prize->save()) {
				throw new Exception($this->prize);
			}

			$value = $this->moneyLimit - $this->prize->value;

			Settings::set(Settings::ATTR_MONEY, (string) $value);

			$transaction->commit();
		}
		catch (Throwable $e) {
			$transaction->rollBack();
			throw $e;
		}

		return true;
	}
}