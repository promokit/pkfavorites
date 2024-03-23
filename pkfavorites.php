<?php

/**
 * Promokit Favorites Module
 *
 * @package   alysum
 * @version   2.4.0
 * @author    https://promokit.eu
 * @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
 * @license   You only can use module, nothing more!
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

use Promokit\Module\Pkfavorites\Main\FavoriteProduct as FavoriteProduct;
use Promokit\Module\Pkfavorites\Admin\Form as AdminForm;
use Promokit\Module\Pkfavorites\Admin\Install;

class Pkfavorites extends Module
{
    const DBKEY = 'PKFAVORITES_CONFIG';
    const HOOK_LIST = [
        'displayBackOfficeHeader',
        'displayHeader',
        'displayMoreButtons',
        'displayProductButton',
        'displayProductButtonFixed',
        'displayCustomerAccount',
        'displayMyAccountBlock',
        'moduleRoutes',
        'displaySvgIcon',
    ];
    const DEFAULTS = [
        'button_hook' => 'displayProductButton',
        'button_position' => 'pktopright',
        'overall_number' => false,
    ];

    public $config;

    public function __construct()
    {
        $this->name = 'pkfavorites';
        $this->tab = 'front_office_features';
        $this->version = '2.4.0';
        $this->author = 'promokit.eu';
        $this->need_instance = 0;
        $this->controllers = ['account'];

        parent::__construct();

        $this->displayName = 'Promokit Favorites';
        $this->description = 'A fast way for your customers to save a product to favorites list';
        $this->ps_versions_compliancy = ['min' => '1.7.8', 'max' => _PS_VERSION_];

        $this->favorite = new FavoriteProduct();
        $this->standalone = false;
        $this->templates = [
            'svg' => 'module:' . $this->name . '/views/templates/hook/_partials/svg.tpl',
            'header' => 'module:' . $this->name . '/views/templates/hook/header.tpl',
            'button' => 'module:' . $this->name . '/views/templates/hook/button.tpl',
            'products' => 'module:' . $this->name . '/views/templates/hook/products.tpl',
            'myaccount' => 'module:' . $this->name . '/views/templates/hook/myaccount.tpl',
            'part_products' => 'module:' . $this->name . '/views/templates/hook/_partials/products.tpl',
            'part_miniproducts' => 'module:' . $this->name . '/views/templates/hook/_partials/miniproducts.tpl'
        ];

        $this->config = $this->getConfig();

        $this->checkCustomer();
    }

    public function install()
    {
        Shop::isFeatureActive() && Shop::setContext(Shop::CONTEXT_ALL);

        $install = new Install;

        return parent::install()
            && $this->registerHook(self::HOOK_LIST)
            && $this->setConfig(self::DEFAULTS)
            && $install->createTable();
    }

    public function uninstall()
    {
        $install = new Install;

        return parent::uninstall()
            && $install->deleteTable()
            && Configuration::deleteByName(self::DBKEY);
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function setConfig($config)
    {
        return Configuration::updateValue(self::DBKEY, serialize($config));
    }

    public function getConfig()
    {
        $dbData = unserialize(Configuration::get(self::DBKEY));
        return array_merge(self::DEFAULTS, $dbData ? $dbData : []);
    }

    public function getContent()
    {
        if (!Tools::isSubmit('submitPkfavoritesModule')) {
            return $this->renderForm();
        }

        $config = self::DEFAULTS;

        foreach ($config as $key => $value) {
            $config[$key] = Tools::getValue($key);
        }

        $output = $this->setConfig($config)
            ? $this->displayConfirmation($this->trans('Settings updated', [], 'Modules.Pkfavorites.Admin'))
            : $this->displayError($this->trans('Unable to update settings', [], 'Modules.Pkfavorites.Admin'));

        return $output . $this->renderForm();
    }

    protected function renderForm()
    {
        $values = $this->getConfig();
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPkfavoritesModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $values,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        $begin = '<div id="force-new-theme" class="row justify-content-center form-horizontal"><div class="col-xl-10"><div class="card"><h3 class="card-header"><i class="material-icons">mode_edit</i>Favorites Settings</h3><div class="card-block row"><div class="card-text">';
        $end = '</div></div></div></div></div>';

        $form = new AdminForm();

        return $begin . $helper->generateForm([$form->getForm($values)]) . $end;
    }

    public function hookDisplayCustomerAccount($params = [])
    {
        $params['product_page'] = false;
        $this->setTemplateVariables($params);

        return $this->fetch($this->templates['myaccount']);
    }

    public function hookDisplayMyAccountBlock($params = [])
    {
        $params['product_page'] = false;
        $this->setTemplateVariables($params);

        return $this->fetch($this->templates['myaccount']);
    }

    public function hookDisplayProductButton($params = [])
    {
        if ((isset($this->config['button_hook']) && ($this->config['button_hook'] == 'displayProductButton')) || !isset($this->config['button_hook'])) {
            $params['product_page'] = false;
            $this->setTemplateVariables($params);

            return $this->fetch($this->templates['button']);
        }
    }

    public function hookDisplayProductButtonFixed($params = [])
    {
        if (isset($this->config['button_hook']) && ($this->config['button_hook'] == 'displayProductButtonFixed')) {
            $params['product_page'] = false;
            $this->setTemplateVariables($params);

            $this->context->controller->registerStylesheet("{$this->name}-fixed", 'modules/' . $this->name . '/views/assets/css/fixed.css', ['media' => 'all', 'priority' => 151]);

            return $this->fetch($this->templates['button']);
        }
    }

    public function hookDisplayMoreButtons($params = [])
    {
        $params['product_page'] = true;
        $this->setTemplateVariables($params);

        return $this->fetch($this->templates['button']);
    }

    public function hookDisplayHeader($params)
    {
        $jsFile = 'modules/' . $this->name . '/views/assets/js/scripts' . (_PS_MODE_DEV_ ? '' : '.min') . '.js';

        $this->context->controller->addJqueryPlugin('jgrowl');
        $this->context->controller->registerJavascript($this->name, $jsFile, ['position' => 'bottom', 'priority' => 420, 'attributes' => 'defer']);
        $this->context->controller->registerStylesheet($this->name, 'modules/' . $this->name . '/views/assets/css/styles.css', ['media' => 'all', 'priority' => 150]);
        if ($this->standalone) {
            $this->context->controller->registerStylesheet("{$this->name}-standalone", 'modules/' . $this->name . '/views/assets/css/standalone.css', ['media' => 'all', 'priority' => 151]);
        }

        $this->smarty->assign([
            'is_standalone' => $this->standalone,
            'svg_tpl' => $this->templates['svg'],
            'pkModuleName' => $this->name,
            'pkMedia' => new Media
        ]);

        return $this->fetch($this->templates['header']) . (($this->standalone === true) ? $this->fetch($this->templates['svg']) : '');
    }

    public function hookDisplaySvgIcon($params)
    {
        $this->smarty->assign([
            'is_standalone' => $this->standalone
        ]);

        return $this->fetch($this->templates['svg']);
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (!defined('_PS_ADMIN_DIR_') || (Tools::getValue('configure') !== $this->name)) {
            return;
        }

        $this->admin_webpath = str_ireplace(_PS_CORE_DIR_, '', _PS_ADMIN_DIR_);
        $this->admin_webpath = preg_replace('/^' . preg_quote(DIRECTORY_SEPARATOR, '/') . '/', '', $this->admin_webpath);

        $this->context->controller->addCSS(__PS_BASE_URI__ . $this->admin_webpath . '/themes/new-theme/public/theme.css', 'all', 1);
        $this->context->controller->addCSS($this->_path . 'views/assets/css/admin.css', 'all');
    }

    public function getFavorites()
    {
        return $this->favorite->getProductsForTemplate($this->context->customer->id);
    }

    public function setTemplateVariables($params = [])
    {
        $id_product = $params['product_page']
            ? (int)Tools::getValue('id_product')
            : (isset($params['product_id']) ? (int)$params['product_id'] : false);


        $isFavorite = $this->favorite->isCustomerFavoriteProduct($this->context->customer->id, $id_product);
        $overallNumber = $this->favorite->countOverallNumber($this->context->customer->id, $id_product, $isFavorite);

        $this->context->smarty->assign([
            'config' => $this->config,
            'is_standalone' => $this->standalone,
            'pkModuleName' => $this->name,
            'idProduct' => $id_product,
            'isProductPage' => $params['product_page'],
            'isFavorite' => $isFavorite,
            'overallNumber' => $overallNumber
        ]);
    }

    public function checkCustomer()
    {
        if (!isset($this->context->customer) || !$this->context->customer->isLogged()) {
            return;
        }
        // copy favorite products from cookies to database
        $list = $this->favorite->getFavoritesFromCookies();

        if (empty($list)) return;

        foreach ($list as $product_id) {
            $this->favorite->addProductToFavorites($product_id);
        }
    }

    public function hookModuleRoutes()
    {
        return [
            'module-' . $this->name . '-' . $this->controllers[0] => [
                'controller' => $this->controllers[0],
                'rule'       => 'favorites',
                'keywords'   => [],
                'params'     => [
                    'fc'     => 'module',
                    'module' => $this->name,
                ],
            ]
        ];
    }
}
