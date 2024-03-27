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

namespace Promokit\Module\Pkfavorites\Product;

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use ProductPresenterFactory;
use ProductAssembler;
use Validate;
use Context;
use Product;

class ProductBuilder
{
    public function isValidProduct($idProduct)
    {
        $product = new Product($idProduct);
        return Validate::isLoadedObject($product);
    }

    public function getProductsForTemplate($ids): array
    {
        $favorites = [];

        if (empty($ids)) {
            return $favorites;
        }

        foreach ($ids as $id) {
          $product = new Product((int)$id, true, Context::getContext()->language->id, Context::getContext()->shop->id);

          if (Validate::isLoadedObject($product) && isset($product->name[Context::getContext()->language->id])) {          
              $product = [(array)$product];
              $product[0]['id_product'] = $product[0]['id'];
              $favorites[$id] = $this->prepareBlocksProducts($product);
          }
        }

        return $favorites;
    }

    public function prepareBlocksProducts($block)
    {
        $products_for_template = [];
        $assembler = new ProductAssembler(Context::getContext());
        $presenterFactory = new ProductPresenterFactory(Context::getContext());
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                Context::getContext()->link
            ),
            Context::getContext()->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            Context::getContext()->getTranslator()
        );

        foreach ($block as $key => $rawProduct) {
            $products_for_template[$key] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                Context::getContext()->language
            );
        }

        return !empty($products_for_template) ? $products_for_template[0] : [];
    }
}
