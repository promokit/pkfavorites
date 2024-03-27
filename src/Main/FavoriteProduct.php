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

namespace Promokit\Module\Pkfavorites\Main;

use Promokit\Module\Pkfavorites\Db\DbQueries;
use Promokit\Module\Pkfavorites\Product\ProductBuilder;
use Context;
use Hook;

class FavoriteProduct extends \ObjectModel
{
    public $id;

    public $id_product;

    public $id_customer;

    public $id_shop;

    public $isLogged;

    private $dbQuery;

    public $def = [
        'table' => 'favorite_product',
        'primary' => 'id_favorite_product',
        'classname' => 'FavoriteProduct',
        'fields' => [
            'id_product' =>  ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'id_customer' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'id_shop' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
        ],
    ];

    // TODO: CHECK THIRD PARTY MODULES WHO USE THIS CLASS
    public function __construct(int $idProduct = 0)
    {
        $this->dbQuery = new DbQueries;
        $this->productBuilder = new ProductBuilder;
        
        $this->id_product = $idProduct;
        $this->id_customer = (int)Context::getContext()->customer->id ?? 0;
        $this->id_shop = (int)Context::getContext()->shop->id;
        $this->isLogged = (bool)Context::getContext()->customer->isLogged();
    }

    public function getInstance()
    {
        $instance = clone $this;

        unset($instance->def);
        unset($instance->id_shop_list);
        unset($instance->productBuilder);

        return $instance;
    }

    public function addToFavorites(): bool
    {
        $result = $this->addToCookies();

        if ($this->isLogged) {
            $result &= $this->addToDb();
        }

        if ($result) {
            Hook::exec('actionFavoritesUpdate', []);
            return true;
        }

        return false;
    }

    public function removeFromFavorites(): bool
    {
        $result = $this->removeFromCookies();

        if ($this->isLogged) {
            $result &= $this->removeFromDb();
        }

        if ($result) {
            Hook::exec('actionFavoritesUpdate', []);
            return true;
        }

        return false;
    }

    public function addToCookies(): bool
    {
        if (!$this->productBuilder->isValidProduct($this->id_product)) {
            return false;
        }

        $list = $this->getFavoritesFromCookies();

        // Remove the product if it already exists in the list
        $list = array_diff($list, [$this->id_product]);

        // Add the product to the beginning of the list
        array_unshift($list, $this->id_product);

        $result = $this->setFavoritesToCookies($list);

        return !empty($result);
    }

    public function addToDb(): bool
    {
        $isValid = $this->productBuilder->isValidProduct($this->id_product);
        
        return !$this->isSavedInDb() && $isValid ? (bool)$this->add() : true;
    }

    public function removeFromCookies()
    {
        $list = $this->getFavoritesFromCookies();

        $index = array_search($this->id_product, $list);

        if ($index !== false) {
            unset($list[$index]);
        }

        $this->setFavoritesToCookies($list);
    }

    public function removeFromDb(): bool
    {
        $this->id = $this->dbQuery->getInstanceId($this->id_shop, $this->id_customer, $this->id_product);

        return $this->productBuilder->isValidProduct($this->id_product) ? (bool)$this->delete() : false;
    }

    public function isSavedInDb(): bool
    {
        return in_array($this->id_product, $this->getCustomerFavoritesFormDb());
    }

    public function isSavedInCookies(): bool
    {
        return in_array($this->id_product, $this->getFavoritesFromCookies());
    }

    public function saveCookiesToDb(): bool
    {
        $result = false;
        $list = $this->getFavoritesFromCookies();

        if (empty($list)) {
            return $result;
        }
    
        foreach ($list as $id) {
            $favorite = new self((int)$id);
            $result &= (bool)$favorite->addToDb();
        }

        return (bool)$result;
    }

    public function setFavoritesToCookies(array $list): string
    {
        $favorites = implode(',', $list);
        return Context::getContext()->cookie->favorites = $favorites;
    }

    public function getFavoritesFromCookies(): array
    {
        $favorites = Context::getContext()->cookie->favorites;
        return $favorites ? explode(',', $favorites) : [];
    }

    public function getCustomerFavoritesFormDb(): array
    {
        $favorites = $this->dbQuery->getCustomerFavoritesFormDb($this->id_shop, $this->id_customer);

        $ids = array_map(function($result) {
            return $result['id_product'];
        }, $favorites);

        return $ids;
    }

    public function getOverallNumber(): int
    {
        $overallNumber = (int)$this->dbQuery->getOverallNumber($this->id_shop, $this->id_product);
        $customerState = $this->isLogged ? 0 : (int)$this->isSavedInCookies();

        return $overallNumber + $customerState;
    }

    public function getProductsForTemplate(): array
    {
        $ids = $this->getFavoritesFromCookies();
        $products = $this->productBuilder->getProductsForTemplate($ids);

        return $products;
    }
}
