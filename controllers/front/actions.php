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
declare(strict_types=1);

use Promokit\Module\Pkfavorites\Main\FavoriteProduct;
use Promokit\Module\Pkfavorites\Front\Resources;

class pkfavoritesActionsModuleFrontController extends ModuleFrontController
{
    private $favorite;

    public function init()
    {
        parent::init();

        $this->favorite = new FavoriteProduct((int)Tools::getValue('id_product') ?? 0);
    }

    public function postProcess()
    {
        parent::postProcess();

        (Tools::getValue('process') === 'add') ? $this->favorite->addToFavorites() : $this->favorite->removeFromFavorites();

        $favorites = $this->favorite->getProductsForTemplate();

        $miniproducts = $this->renderContent(
            $favorites, 
            Resources::template('part_miniproducts', $this->module->name)
        );
        $products = $this->renderContent(
            $favorites, 
            Resources::template('part_products', $this->module->name)
        );
        
        header('Content-Type: application/json');

        die(json_encode([
            'overallFavorites' => $this->favorite->getOverallNumber(),
            'customerFavorites' => count($favorites),
            'miniproducts' => $this->module::IS_STANDALONE ? false : $miniproducts,
            'products' => $products,
        ]));
    }

    public function renderContent($products, $template)
    {
        $this->context->smarty->assign([
            'products' => $products
        ]);

        return $this->module->fetch($template);
    }
}