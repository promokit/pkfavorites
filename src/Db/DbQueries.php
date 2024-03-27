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

namespace Promokit\Module\Pkfavorites\Db;

use Db;
use DbQuery;

/**
 * Class DbQueries
 * @package Promokit\Module\Pkfavorites\Db
 */
class DbQueries
{
    private $table = 'favorite_product';

    public function getQueryInstance()
    {
        $query = new DbQuery;
        $query->from($this->table);

        return $query;
    }

    public function createTable(): bool
    {
        return (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'.$this->table.'` (
            `id_favorite_product` int(10) unsigned NOT NULL auto_increment,
            `id_product` int(10) unsigned NOT NULL,
            `id_customer` int(10) unsigned NOT NULL,
            `id_shop` int(10) unsigned NOT NULL,
            PRIMARY KEY (`id_favorite_product`))
            ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    public function deleteTable(): bool
    {
        return (bool)Db::getInstance()->execute("DROP TABLE IF EXISTS `{$this->table}`");
    }
    
    public function getOverallNumber(int $idShop, int $idProduct): int
    {
        $query = $this->getQueryInstance();
        $query->select('COUNT(*)');
        $query->where('id_product = '.$idProduct);
        $query->where('id_shop = '.$idShop);

        return (int)Db::getInstance()->getValue($query);
    }

    public function getCustomerFavoritesFormDb(int $idShop, int $idCustomer): array
    {
        $query = $this->getQueryInstance();
        $query->select('id_product');
        $query->where('id_customer = '.$idCustomer);
        $query->where('id_shop = '.$idShop);

        return Db::getInstance()->executeS($query);
    }

    public function getInstanceId(int $idShop, int $idCustomer, int $idProduct): int
    {
        $query = $this->getQueryInstance();
        $query->select('id_favorite_product');
        $query->where('id_customer = '.$idCustomer);
        $query->where('id_product = '.$idProduct);
        $query->where('id_shop = '.$idShop);

        return (int)Db::getInstance()->getValue($query);
    }
}
