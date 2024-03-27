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
use Promokit\Module\Pkfavorites\Front\Env;
use Promokit\Module\Pkfavorites\Front\Resources;

class DisplayMoreButtons extends Module
{
    protected $module;
    protected $params;
    protected $env;
    private $templateName = 'button';

    public function __construct(Module $module, array $params)
    {
        $this->params = $params;
        $this->module = $module;
        $this->env = new Env();
    }

    public function init()
    {
        $this->params['product_page'] = true;

        $this->env->set($this->params);

        return $this->module->fetch(
            Resources::template($this->templateName, $this->module->name)
        );
    }
}