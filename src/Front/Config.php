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
declare(strict_types=1);

namespace Promokit\Module\Pkfavorites\Front;

use Promokit\Module\Pkfavorites\Db\Db;

class Config
{
    public static function get(): array
    {
        return (new Db)->getConfig();
    }

    public static function set(array $config): array
    {
        return (new Db)->setConfig($config);
    }
}