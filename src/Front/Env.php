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

use Tools;
use Media;
use Module;
use Context;
use Pkfavorites;
use Promokit\Module\Pkfavorites\Front\Config;
use Promokit\Module\Pkfavorites\Main\FavoriteProduct;

class Env extends Module
{
    protected $context;

    public function __construct()
    {
        $this->context = Context::getContext();
    }

    public function set($params = [])
    {
        $idProduct = (isset($params['product_page']) && $params['product_page'])
            ? (int) Tools::getValue('id_product')
            : (int) ($params['product_id'] ?? 0);

        $favorite = new FavoriteProduct();
        $favorite->id_product = $idProduct;

        $isFavorite = $favorite->isSavedInCookies();
        $overallNumber = $favorite->getOverallNumber();

        $this->context->smarty->assign([
            'config' => Config::get(),
            'is_standalone' => Pkfavorites::IS_STANDALONE,
            'idProduct' => $idProduct,
            'isProductPage' => isset($params['product_page']) && $params['product_page'],
            'isFavorite' => $isFavorite,
            'overallNumber' => $overallNumber
        ]);
    }
}