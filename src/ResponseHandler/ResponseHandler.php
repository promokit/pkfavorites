<?php

/**
 * Promokit Favorites
 *
 * @package   Promokit
 * @version   3.0.0
 * @author    https://promokit.eu
 * @copyright Copyright Ⓒ Since 2011 promokit.eu <@email:support@promokit.eu>
 * @license   You only can use the module, nothing more!
 */
declare (strict_types = 1);

namespace Promokit\Module\Pkfavorites\ResponseHandler;

class ResponseHandler
{
	public const ERROR = 'error';
	public const SUCCESS = 'success';

	public static function compose(string $type, string $message = ''): array
	{
		return [
			'type' => $type,
			'error' => $type === self::ERROR,
			'success' => $type === self::SUCCESS,
			'message' => $message,
		];
	}

	public static function error(string $message): array
	{
		return self::compose(self::ERROR, $message);
	}

	public static function success(string $message): array
	{
		return self::compose(self::SUCCESS, $message);
	}
}
