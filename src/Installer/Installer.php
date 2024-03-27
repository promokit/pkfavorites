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

namespace Promokit\Module\Pkfavorites\Installer;

use Hook;
use Module;
use Promokit\Module\Pkfavorites\Db\Db;
use Promokit\Module\Pkfavorites\Db\DbQuery;
use Promokit\Module\Pkfavorites\FileManager\FileManager;

class Installer
{
    private $db;
    private $dbQuery;
    private $module;
    private $configFile;

    public function __construct(Module $module)
    {
        $this->db = new Db;
        $this->dbQuery = new DbQuery;
        $this->module = $module;
        $this->configFile = dirname(__FILE__) . '/../../config/default.json';
    }

    /**
     * Module's installation entry point.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function install(): bool
    {
        return $this->__registerHooks()
            && $this->__importDefaultConfig()
            && $this->dbQuery->createTable();
    }

    public function uninstall(): bool
    {
        return $this->dbQuery->deleteTable();
    }

    private function __importDefaultConfig(): bool
    {
        $configJson = $this->__readConfigFile();

        if (!$configJson) {
            return false;
        }

        $response = $this->db->setConfig($configJson);

        return $response['success'];
    }

    private function __readConfigFile()
    {
        $fileManager = new FileManager;
        $data = $fileManager->readFile($this->configFile);

        return isset($data['error']) ? false : json_decode($data, true);
    }

    private function __registerHooks(): bool
    {
        return $this->module->registerHook($this->module::HOOK_LIST);
    }
}
