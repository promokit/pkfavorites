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

namespace Promokit\Module\Pkfavorites\Main;

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use ProductPresenterFactory;
use ProductAssembler;
use Validate;
use Context;
use Product;
use Db;

class FavoriteProduct extends \ObjectModel
{
	public $id;

	public $id_product;

	public $id_customer;

	public $id_shop;

	public $date_add;

	public $date_upd;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = [
		'table' => 'favorite_product',
		'primary' => 'id_favorite_product',
		'fields' => [
			'id_product' =>	 ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
			'id_customer' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
			'id_shop' =>	 ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
			'date_add' =>	 ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
			'date_upd' =>	 ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
		],
	];

	public function getFavoriteProductsIDs($id_customer)
	{
        if (!Context::getContext()->customer->isLogged())
        {
            return $this->getFavoritesFromCookies();
        } 

        $list = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT id_product 
            FROM '._DB_PREFIX_.'favorite_product 
            WHERE `id_customer` ='.$id_customer.' 
            AND `id_shop` = '.Context::getContext()->shop->id
        );

        $listArr = [];

        if (is_array($list))
        {
            foreach ($list as $prd)
            {
                $listArr[] = $prd['id_product'];
            }
        }

        return $listArr;
	}

	public function getFavoriteProductInstance($id_customer, $id_product)
	{
		$id_favorite_product = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT `id_favorite_product`
			FROM `'._DB_PREFIX_.'favorite_product`
			WHERE `id_customer` = '.(int)$id_customer.'
			AND `id_product` = '.(int)$id_product.'
			AND `id_shop` = '.Context::getContext()->shop->id
		);

		if ($id_favorite_product) {
			return new FavoriteProduct($id_favorite_product);
        }

		return null;
	}

	public static function isCustomerFavoriteProduct($id_customer, $id_product)
	{
		if (!$id_customer)
        {
            return in_array($id_product, self::getFavoritesFromCookies());
        }

		return (bool)Db::getInstance()->getValue('
			SELECT COUNT(*)
			FROM `'._DB_PREFIX_.'favorite_product`
			WHERE `id_customer` = '.(int)$id_customer.'
			AND `id_product` = '.(int)$id_product.'
			AND `id_shop` = '.Context::getContext()->shop->id);
	}

    public function getProductsForTemplate($id_customer)
    {
      $FavoriteProduct = new FavoriteProduct();
      $allFavoritesIDs = $FavoriteProduct->getFavoriteProductsIDs($id_customer);
      $allFavorites = [];
      
      foreach ($allFavoritesIDs as $id)
      {
          $product = new Product((int)$id, true, Context::getContext()->language->id, Context::getContext()->shop->id);

          if (Validate::isLoadedObject($product) && isset($product->name[Context::getContext()->language->id]))
          {                   
              $product = [(array)$product];
              $product[0]['id_product'] = $product[0]['id'];
              $allFavorites[$id] = $FavoriteProduct->prepareBlocksProducts($product);
          }
      }

      unset($product);

      return $allFavorites;
    }

    public function addToFavorites($product_id)
    {
        $product = new Product($product_id);
        // check if product exists
        if (!Validate::isLoadedObject($product) || $this->isCustomerFavoriteProduct(Context::getContext()->cookie->id_customer, $product->id))
        {
            return false;
        }

        $this->id_product = $product->id;
        $this->id_customer = (int)Context::getContext()->cookie->id_customer;
        $this->id_shop = (int)Context::getContext()->shop->id;

        unset($product);

        if ($this->add())
        {
            return true;
        }
        return false;
    }

    public function addToDb()
    {
        if ($this->addToFavorites($this->id_product))
        {
            return $this->addToCookies(); // sync to cookies
        }

        return false;
    }

    public function addToCookies()
    {
        // check if product exists
        if (!$this->isProduct()) return false;

        $list = $this->getFavoritesFromCookies();

        if (in_array($this->id_product, $list))
        {
            foreach ($list as $key => $value)
            {
                if ($this->id_product == $value)
                {
                    unset($list[$key]);
                }
            }
        }

        array_unshift($list, $this->id_product);

        $this->setFavoritesToCookies($list);

        return true;
    }

    public function removeFromDb()
    {
        // check if product exists
        if (!$this->isProduct()) return false;

        $favorite_product = $this->getFavoriteProductInstance(
            Context::getContext()->cookie->id_customer, $this->id_product
        );

        if ($favorite_product && $favorite_product->delete())
        {
            $this->removeFromCookies($this->id_product); // sync to cookies
            return true;
        }

        return false;
    }

    public function removeFromCookies()
    {
        $list = $this->getFavoritesFromCookies(); 

        if (in_array($this->id_product, $list))
        {
            foreach ($list as $key => $value)
            {
                if ($this->id_product == $value)
                {
                    unset($list[$key]);
                }
            }
        }

        $this->setFavoritesToCookies($list);
    }

    public function isProduct() {
        $product = new Product($this->id_product);

        if (!Validate::isLoadedObject($product))
        {
            return false;
        }

        return true;
    }

    public function setFavoritesToCookies($list) {
        Context::getContext()->cookie->favorites = trim(implode(',', $list), ',');
    }

    public function getFavoritesFromCookies() {
        if (isset(Context::getContext()->cookie->favorites))
        {
            return explode(',', Context::getContext()->cookie->favorites);
        }
        return [];
    }

    public function prepareBlocksProducts($block)
    {
        $products_for_template = [];
        $assembler = new ProductAssembler(Context::getContext());
        $presenterFactory = new ProductPresenterFactory(Context::getContext());
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(new ImageRetriever(Context::getContext()->link), Context::getContext()->link, new PriceFormatter(), new ProductColorsRetriever(), Context::getContext()->getTranslator());

        if ($block)
        {
            foreach ($block as $key => $rawProduct)
            {
                $products_for_template[$key] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct), Context::getContext()->language
                );
            }
        }

        unset($block, $assembler, $presenterFactory, $presentationSettings, $presenter);

        return $products_for_template[0];
    }
}
