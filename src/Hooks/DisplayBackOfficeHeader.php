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

namespace Promokit\Module\Pkfavorites\Hooks;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Tools;
use Module;
use Context;

class DisplayBackOfficeHeader extends Module
{
    protected $context;

    public function __construct(Module $module)
    {
        $this->module = $module;
        $this->context = Context::getContext();
    }

    public function init()
    {
        if (!defined('_PS_ADMIN_DIR_') || (Tools::getValue('configure') !== $this->module->name)) {
            return;
        }

        $admin_webpath = str_ireplace(_PS_CORE_DIR_, '', _PS_ADMIN_DIR_);
        $admin_webpath = preg_replace('/^' . preg_quote(DIRECTORY_SEPARATOR, '/') . '/', '', $admin_webpath);

        $this->context->controller->addCSS(__PS_BASE_URI__ . $admin_webpath . '/themes/new-theme/public/theme.css', 'all', 1);
        $this->context->controller->addCSS($this->module->_path . 'views/assets/css/admin.css', 'all');
    }
}