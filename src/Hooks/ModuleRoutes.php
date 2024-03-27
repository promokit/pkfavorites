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

class ModuleRoutes extends Module
{
    protected $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    public function init(): array
    {
        return [
            'module-' . $this->module->name . '-' . $this->module->controllers[0] => [
                'controller' => $this->module->controllers[0],
                'rule'       => 'favorites',
                'keywords'   => [],
                'params'     => [
                    'fc'     => 'module',
                    'module' => $this->module->name,
                ],
            ]
        ];
    }
}