<?php

namespace app\models;

use app\modules\component\Settings;

class Prizes {

	/**
	 * Получить список доступных типов призов
	 *
	 * @return array
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public static function getAvailablePrizesType(){
		$types = [PrizeBonus::TYPE];
		if(null !== Product::findOne([Product::ATTR_EXIST => true])){
			$types[] = PrizePhysical::TYPE;
		}
		if(0 < (int) Settings::get(Settings::ATTR_MONEY)){
			$types[] = PrizeMoney::TYPE;
		}

		return $types;
	}

}