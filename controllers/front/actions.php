<?php
/**
* Promokit Favorites Module
*
* @package   alysum
* @version   2.4.0
* @author    https://promokit.eu
* @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
* @license   You only can use module, nothing more!
*/

use Promokit\Module\Pkfavorites\Main\FavoriteProduct as FavoriteProduct;

class pkfavoritesActionsModuleFrontController extends ModuleFrontController
{
    private $favorite;

    public function init()
    {
        parent::init();
        $this->favorite = new FavoriteProduct();
        $this->favorite->id_product = (int)Tools::getValue('id_product');
    }

    public function postProcess()
    {
        (Tools::getValue('process') === 'add') ? $this->favorite->addToFavorites() : $this->favorite->removeFromFavorites();

        $cid = Context::getContext()->customer->id ? Context::getContext()->customer->id : 0;
        $isFavorite = $this->favorite->isCustomerFavoriteProduct($cid, $this->favorite->id_product);
        $products = $this->favorite->getProductsForTemplate($cid);

        header('Content-Type: application/json');

        die(json_encode([
            'miniproducts' => $this->module->standalone ? false : $this->renderContent($products, $this->module->templates['part_miniproducts']),
            'products' => $this->renderContent($products, $this->module->templates['part_products']),
            'products_number' => count($products),
            'overall_number' => (int)$this->favorite->countOverallNumber($cid, $this->favorite->id_product),
            'cid' => $cid,
            'id_product' => $this->favorite->id_product
        ]));
    }

    public function renderContent($products, $template)
    {
        $this->context->smarty->assign(['products' => $products]);
        return $this->module->fetch($template);
    }
}