<?php

declare(strict_types=1);

namespace app\modules\prize\creators;

use app\modules\prize\PhysicalPrize;
use app\modules\prize\PriceFactory;
use app\modules\prize\PrizeInterface;

class PhysicalPrizeCreator extends PriceFactory {

	public function getPrize(): PrizeInterface {
		return new PhysicalPrize();
	}

}