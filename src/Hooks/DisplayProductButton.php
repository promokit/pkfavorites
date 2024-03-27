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

use Module;
use Context;
use Promokit\Module\Pkfavorites\Front\Env;
use Promokit\Module\Pkfavorites\Front\Config;
use Promokit\Module\Pkfavorites\Front\Resources;

class DisplayProductButton extends Module
{
    protected $module;
    protected $params;
    protected $env;
    private $templateName = 'button';
    private $hookName = 'displayProductButton';

    public function __construct(Module $module, array $params)
    {
        $this->params = $params;
        $this->module = $module;
        $this->env = new Env();
    }

    public function init()
    {
        $config = Config::get();

        if ((isset($config['button_hook']) && ($config['button_hook'] === $this->hookName)) || !isset($config['button_hook'])) {
            $this->params['product_page'] = false;
            
            $this->env->set($this->params);

            return $this->module->fetch(
                Resources::template($this->templateName, $this->module->name)
            );
        }
    }
}