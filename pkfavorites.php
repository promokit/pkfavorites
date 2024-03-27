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

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

use Promokit\Module\Pkfavorites\Hooks\ModuleRoutes;
use Promokit\Module\Pkfavorites\Hooks\DisplayHeader;
use Promokit\Module\Pkfavorites\Hooks\DisplayMoreButtons;
use Promokit\Module\Pkfavorites\Hooks\DisplayProductButton;
use Promokit\Module\Pkfavorites\Hooks\DisplayMyAccountBlock;
use Promokit\Module\Pkfavorites\Hooks\DisplayCustomerAccount;
use Promokit\Module\Pkfavorites\Hooks\DisplayBackOfficeHeader;
use Promokit\Module\Pkfavorites\Hooks\DisplayProductButtonFixed;
use Promokit\Module\Pkfavorites\Main\FavoriteProduct;
use Promokit\Module\Pkfavorites\Installer\Installer;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class Pkfavorites extends Module
{
    const IS_STANDALONE = false; // disable for Alysum theme
    const HOOK_LIST = [
        'displayBackOfficeHeader',
        'displayHeader',
        'displayMoreButtons',
        'displayProductButton',
        'displayProductButtonFixed',
        'displayCustomerAccount',
        'displayMyAccountBlock',
        'actionAuthentication',
        'moduleRoutes',
    ];

    public function __construct()
    {
        $this->name = 'pkfavorites';
        $this->tab = 'front_office_features';
        $this->version = '3.0.0';
        $this->author = 'promokit.eu';
        $this->need_instance = 0;
        $this->controllers = ['account'];
        $this->adminRoute = 'admin_pkfavorites_configuration_get';

        parent::__construct();

        $this->displayName = 'Promokit Favorites';
        $this->description = 'A fast way for your customers to save a product to favorites list';
        $this->ps_versions_compliancy = ['min' => '1.7.8', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        Shop::isFeatureActive() && Shop::setContext(Shop::CONTEXT_ALL);

        return parent::install() && (new Installer($this))->install();
    }

    public function uninstall()
    {
        return parent::uninstall() && (new Installer($this))->uninstall();
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function getContent()
    {
        Tools::redirectAdmin(
            SymfonyContainer::getInstance()->get('router')->generate($this->adminRoute)
        );
    }

    public function hookDisplayCustomerAccount($params = [])
    {
        return $this->executeHook(__FUNCTION__, $params);
    }

    public function hookDisplayMyAccountBlock($params = [])
    {
        return $this->executeHook(__FUNCTION__, $params);
    }

    public function hookDisplayProductButton($params = [])
    {
        return $this->executeHook(__FUNCTION__, $params);
    }

    public function hookDisplayProductButtonFixed($params = [])
    {
        return $this->executeHook(__FUNCTION__, $params);
    }

    public function hookDisplayMoreButtons($params = [])
    {
        return $this->executeHook(__FUNCTION__, $params);
    }

    public function hookDisplayHeader($params = [])
    {
        return $this->executeHook(__FUNCTION__, $params);
    }

    public function hookDisplayBackOfficeHeader()
    {
        return $this->executeHook(__FUNCTION__);
    }

    public function hookActionAuthentication($params = [])
    {
        return $this->executeHook(__FUNCTION__, $params);
    }

    public function hookModuleRoutes()
    {
        return $this->executeHook(__FUNCTION__);
    }

    private function executeHook($initiator, $params = [])
    {
        $className = str_replace('hook', '', lcfirst(substr($initiator, 4)));
        $class = "\\Promokit\\Module\\Pkfavorites\\Hooks\\$className";
        $hook = new $class($this, $params);

        return $hook->init();
    }
}
