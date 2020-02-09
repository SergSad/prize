<?php

declare(strict_types=1);

namespace app\modules\prize;

/**
 * Interface Для призов
 */
interface PrizeInterface {
	/**
	 * @return string
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function getType(): string;

	/**
	 * @param int $id
	 *
	 * @return bool
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function save(int $id): bool;
}