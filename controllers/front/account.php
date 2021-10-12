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

class pkfavoritesAccountModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function init()
    {
        parent::init();
    }

    public function initContent()
    {
        parent::initContent();

        $favoriteProduct = new FavoriteProduct();
        $this->context->smarty->assign(
          'favoriteProducts', $favoriteProduct->getProductsForTemplate($this->context->customer->id)
        );

        $this->setTemplate($this->module->templates['products']);
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();

        $breadcrumb['links'][] = [
            'title' => $this->trans('favorites', [], 'Modules.Pkfavorites.Shop'),
            'url' => $this->context->link->getModuleLink($this->module->name, $this->module->controllers[0]),
        ];

        return $breadcrumb;
    }
}