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

namespace Promokit\Module\Pkfavorites\Admin;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Db;

class Install extends \Module
{
    protected $fav_table = _DB_PREFIX_.'favorite_product';

    public function createTable()
    {
        return (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'.$this->fav_table.'` (
            `id_favorite_product` int(10) unsigned NOT NULL auto_increment,
            `id_product` int(10) unsigned NOT NULL,
            `id_customer` int(10) unsigned NOT NULL,
            `id_shop` int(10) unsigned NOT NULL,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_favorite_product`))
            ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    public function deleteTable()
    {
        return (bool)Db::getInstance()->execute('DROP TABLE IF EXISTS `'.$this->fav_table.'`');
    }
}