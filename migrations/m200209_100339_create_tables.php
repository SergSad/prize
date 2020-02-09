<?php

use yii\db\Migration;

/**
 * Class m200209_100339_create_tables
 */
class m200209_100339_create_tables extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {

		$tableOptions = null;

		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		/** Пользователи */
		$this->createTable('{{%user}}', [
			'id'                   => $this->primaryKey(),
			'username'             => $this->string()->notNull()->unique(),
			'auth_key'             => $this->string(32)->notNull(),
			'password_hash'        => $this->string()->notNull(),
			'password_reset_token' => $this->string()->unique(),
			'email'                => $this->string()->notNull()->unique(),
			'status'               => $this->smallInteger()->notNull()->defaultValue(10),
			'accounts_points'      => $this->integer()->notNull()->defaultValue(0),
			'created_at'           => $this->integer()->notNull(),
			'updated_at'           => $this->integer()->notNull(),
		], $tableOptions);
		/** -- -- -- */

		/** Призы пользователей */
		$this->createTable('{{%user_prizes}}', [
			'id'         => $this->primaryKey(),
			'user_id'    => $this->integer()->notNull(),
			'prize_type' => $this->string()->notNull()
		]);

		$this->addForeignKey('fk-user_prizes[user]', 'user_prizes', 'user_id', 'user', 'id', 'restrict', 'restrict');
		/** -- -- -- */

		/** Список физических продуктов */
		$this->createTable('product', [
			'id'   => $this->primaryKey(),
			'name' => $this->string()->notNull()
		]);

		$this->batchInsert('product', ['name'], static::PRODUCTS);
		/** -- -- -- */

		/** Физический приз */
		$this->createTable('{{%prize_physical}}', [
			'user_prize_id' => $this->integer()->notNull(),
			'product_id'    => $this->integer()->notNull(),
		]);
		$this->addPrimaryKey('pk-prize_physical[user_prize_id]', 'prize_physical', 'user_prize_id');

		$this->addForeignKey('fk-prize_physical[user_prize]', 'prize_physical', 'user_prize_id', 'user_prizes', 'id', 'restrict', 'restrict');
		$this->addForeignKey('fk-prize_physical[product]', 'prize_physical', 'product_id', 'product', 'id', 'restrict', 'restrict');
		/** -- -- -- */

		/** Бонусный приз */
		$this->createTable('{{%prize_bonus}}', [
			'user_prize_id' => $this->integer()->notNull(),
			'value'         => $this->integer()->notNull(),
		]);
		$this->addPrimaryKey('pk-prize_bonus[user_prize_id]', 'prize_bonus', 'user_prize_id');
		$this->addForeignKey('fk-prize_bonus[user_prize]', 'prize_bonus', 'user_prize_id', 'user_prizes', 'id', 'restrict', 'restrict');
		/** -- -- -- */

		/** Денежный приз */
		$this->createTable('{{%prize_money}}', [
			'user_prize_id' => $this->integer()->notNull(),
			'value'         => $this->integer()->notNull(),
		]);
		$this->addPrimaryKey('pk-prize_money[user_prize_id]', 'prize_money', 'user_prize_id');
		$this->addForeignKey('fk-prize_money[user_prize]', 'prize_money', 'user_prize_id', 'user_prizes', 'id', 'restrict', 'restrict');
		/** -- -- -- */

	}

	/** Записи, которые будут добавлены в таблицу. */
	private const PRODUCTS = [
		['Колбаса'],
		['Хлеб'],
		['Молоко'],
		['Шпроты'],
		['Конфеты'],
		['Кукуруза'],
		['Масло'],
		['Макароны'],
	];

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('{{%prize_money}}');
		$this->dropTable('{{%prize_bonus}}');
		$this->dropTable('{{%prize_physical}}');
		$this->dropTable('{{%product}}');
		$this->dropTable('{{%user_prizes}}');
		$this->dropTable('{{%user}}');
	}
}
