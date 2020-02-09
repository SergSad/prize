<?php

use app\models\PrizeMoney;
use app\models\User;
use app\models\UserPrizes;
use app\modules\component\Settings;
use app\modules\services\MoneyPrizeService;
use Codeception\Test\Unit;

class ConvertTest extends Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
	}

	protected function _after() {
	}

	// tests
	public function testSomeFeature() {
		$user = $this->createUser();
		Yii::$app->user->login($user);
		$userPrize = $this->createPrize($user->id);

		$prize                = new PrizeMoney();
		$prize->user_prize_id = $userPrize->id;
		$prize->value         = 2500;
		$prize->save();

		$value = 2500 * (int)Settings::get(Settings::ATTR_COEFFICIENT);

		MoneyPrizeService::convertToBonus();

		$user->refresh();
		$userPrize->refresh();

		$this->assertEquals($value, $user->accounts_points);
		$this->assertEquals(true, $userPrize->is_send);

		return true;
	}

	private function createUser() {
		$user                = new User();
		$user->username      = 'test';
		$user->auth_key      = 'test';
		$user->password_hash = 'test';
		$user->email         = 'test@mail.ru';
		$user->save();

		return $user;
	}

	private function createPrize($user_id) {
		$userPrize             = new UserPrizes();
		$userPrize->user_id    = $user_id;
		$userPrize->prize_type = PrizeMoney::TYPE;
		$userPrize->save();

		return $userPrize;
	}
}