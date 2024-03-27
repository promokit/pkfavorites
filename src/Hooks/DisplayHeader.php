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

use Media;
use Module;
use Context;
use Pkfavorites;

class DisplayHeader extends Module
{
    protected $context;
    protected $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
        $this->context = Context::getContext();
    }

    public function init()
    {
        $name = $this->module->name;

        $this->context->controller->addJqueryPlugin('jgrowl');

        $this->context->controller->registerStylesheet(
            $name,
            "modules/{$name}/views/assets/css/styles.css",
            ['priority' => 150]
        );
        
        $this->context->controller->registerJavascript(
            $name,
            "modules/{$name}/views/assets/js/scripts.js",
            ['priority' => 420, 'attributes' => 'defer', 'position' => 'bottom']
        );

        if (Pkfavorites::IS_STANDALONE) {
            $this->context->controller->registerStylesheet(
                "{$name}-standalone",
                'modules/' . $this->name . '/views/assets/css/standalone.css',
                ['media' => 'all', 'priority' => 151]
            );
        }

        $aOpen = "<a href=\"{$this->context->link->getModuleLink($name, $this->module->controllers[0])}\">";

        Media::addJsDef([
            'pkfavorites' => [
                'add' => $this->context->link->getModuleLink($name, 'actions', ['process' => 'add']),
                'remove' => $this->context->link->getModuleLink($name, 'actions', ['process' => 'remove'], true),
                'phrases' => [
                    'title' => $this->trans('favorites', [], 'Modules.Pkfavorites.Shop'),
                    'add' => $this->trans('Add to favorites', [], 'Modules.Pkfavorites.Shop'),
                    'remove' => $this->trans('Remove from favorites', [], 'Modules.Pkfavorites.Shop'),
                    'added' => $this->trans('The product has been added to your %b%favorites%e%', ['%b%' => $aOpen, '%e%' => '</a>'], 'Modules.Pkfavorites.Shop'),
                    'removed' => $this->trans('The product has been removed from %b%favorites%e%', ['%b%' => $aOpen, '%e%' => '</a>'], 'Modules.Pkfavorites.Shop'),
                ]
            ]
        ]);
    }
}