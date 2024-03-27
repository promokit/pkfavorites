<?php
/**
* Promokit Favorites Module
*
* @package   alysum
* @version   3.0.0
* @author    https://promokit.eu
* @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
* @license   You only can use module, nothing more!
*/

use Promokit\Module\Pkfavorites\Main\FavoriteProduct;
use Promokit\Module\Pkfavorites\Front\Resources;

class pkfavoritesAccountModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $favorites = new FavoriteProduct();
        $favorites->saveCookiesToDb();

        $this->context->smarty->assign(
            'favorites', $favorites->getProductsForTemplate()
        );

        $this->setTemplate(
            Resources::template('products', $this->module->name)
        );
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