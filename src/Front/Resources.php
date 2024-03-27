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

use Pkfavorites;
use Configuration;

class Resources
{
    public static function template(string $id, string $moduleName): string
    {
        $path = 'module:%s/views/templates/hook/';

        $templates = [
            'header' => 'header.tpl',
            'button' => 'button.tpl',
            'products' => 'products.tpl',
            'myaccount' => 'myaccount.tpl',
            'part_products' => '_partials/products.tpl',
            'part_miniproducts' => '_partials/miniproducts.tpl'
        ];

        return sprintf("{$path}{$templates[$id]}", $moduleName);
    }
}