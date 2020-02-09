<?php

declare(strict_types=1);

namespace app\modules\component;

use app\models\Settings as SettingsDb;
use Throwable;
use yii\base\BaseObject;

/**
 * Class Settings
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class Settings extends BaseObject {
	const ATTR_BONUS_MIN   = 'bonus_min';
	const ATTR_BONUS_MAX   = 'bonus_max';
	const ATTR_MONEY_MIN   = 'money_min';
	const ATTR_MONEY_MAX   = 'money_max';
	const ATTR_MONEY       = 'money';
	const ATTR_COEFFICIENT = 'coefficient';

	/**
	 * @param string $name
	 * @param string $value
	 *
	 * @return bool
	 * @throws Throwable
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public static function set(string $name, string $value) {
		try {
			$setting = SettingsDb::findOne([SettingsDb::ATTR_NAME => $name]);

			// -- Если записи с таким именем нет, создать новую запись
			if (null === $setting) {
				$setting       = new SettingsDb();
				$setting->name = $name;
			}

			$setting->value = $value;

			return $setting->save();
		}
		catch (Throwable $e) {
			throw $e;
		}
	}

	/**
	 * @param string $name
	 *
	 * @return false|string|null
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public static function get(string $name) {
		return SettingsDb::find()
			->select(SettingsDb::ATTR_VALUE)
			->where([SettingsDb::ATTR_NAME => $name])
			->scalar();
	}

}
