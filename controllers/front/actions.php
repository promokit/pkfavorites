<?php
/**
* Promokit Favorites Module
*
* @package   alysum
* @version   2.3.0
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
        (Tools::getValue('process') === 'add') ? $this->processAdd() : $this->processRemove();

        $products = $this->favorite->getProductsForTemplate($this->context->customer->id);

        header('Content-Type: application/json');

        die(json_encode([
            'miniproducts' => $this->module->standalone ? false : $this->renderContent($products, $this->module->templates['part_miniproducts']),
            'products' => $this->renderContent($products, $this->module->templates['part_products']),
            'products_number' => count($products)
        ]));
    }

    /**
     * Remove favorite product
     */
    public function processRemove()
    {
        $this->context->customer->isLogged() ? $this->favorite->removeFromDb() : $this->favorite->removeFromCookies();
    }

    /**
     * Add favorite product
     */
    public function processAdd()
    {
        $this->context->customer->isLogged() ? $this->favorite->addToDb() : $this->favorite->addToCookies();
    }

    public function renderContent($products, $template)
    {
        $this->context->smarty->assign(['products' => $products]);
        return $this->module->fetch($template);
    }
}