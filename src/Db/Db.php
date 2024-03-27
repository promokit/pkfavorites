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

use Context;
use Configuration;
use Promokit\Module\Pkfavorites\ResponseHandler\ResponseHandler;

/**
 * Class Db
 * @package Promokit\Module\Pkfavorites\Db
 */
class Db
{
    private $context;
    private $dbkey = 'PKFAVORITES_CONFIG';
    private $table = _DB_PREFIX_.'favorite_product';

    public function __construct()
    {
        $this->context = Context::getContext();
    }

    // TODO: add caching
    public function getConfig(): array
    {
        $config = [];
        $config_serialized = Configuration::get($this->dbkey);

        if (!empty($config_serialized)) {
            try {
                $config = unserialize($config_serialized);
            } catch (\Throwable $th) {
                return [];
            }
        }

        return $config;
    }

    public function setConfig(array $data): array
    {
        if (!is_array($data)) {
            return ResponseHandler::error('Broken app config data');
        }

        $serializedData = serialize($data);

        $group = is_null($this->context->shop->id_shop_group) ? 1 : $this->context->shop->id_shop_group;

        if (!Configuration::updateValue($this->dbkey, $serializedData, false, $group, $this->context->shop->id)) {
            return ResponseHandler::error('Unable to update configuration in database');
        }

        return ResponseHandler::success('Configuration saved successfully');
    }
}
