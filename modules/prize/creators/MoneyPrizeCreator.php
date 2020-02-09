<?php

declare(strict_types=1);

namespace app\modules\prize\creators;

use app\modules\prize\MoneyPrize;
use app\modules\prize\PriceFactory;
use app\modules\prize\PrizeInterface;

class MoneyPrizeCreator extends PriceFactory {

	public function getPrize(): PrizeInterface {
		return new MoneyPrize();
	}

}