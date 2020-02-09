<?php

declare(strict_types=1);

namespace app\modules\prize;

use app\models\PrizePhysical;
use app\models\Product;
use Throwable;
use yii\db\Exception;

class PhysicalPrize implements PrizeInterface {

	/** @var PrizePhysical */
	private $prize;

	/** @var Product */
	private $product;

	/**
	 * {@inheritDoc}
	 */
	public function __construct() {
		$this->prize             = new PrizePhysical();
		$this->product           = Product::find()
			->where([Product::ATTR_EXIST => true])
			->orderBy('RAND()')
			->one()
		;
		$this->prize->product_id = $this->product->id;
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
		$transaction = PrizePhysical::getDb()->beginTransaction();
		try {
			$this->prize->user_prize_id = $userPriceId;

			if (false === $this->prize->save()) {
				throw new Exception($this->prize);
			}

			$this->product->exist = false;
			if (false === $this->product->save()) {
				throw new Exception($this->product);
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