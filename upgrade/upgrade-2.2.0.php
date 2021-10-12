<?php
/**
 * Promokit AMP Module
 *
 * @author    https://promokit.eu
 * @copyright Copyright Ⓒ 2022 promokit.eu <@email:support@promokit.eu>
 * @license   You only can use module, nothing more!
 * @version   2.2.0
 * @package   alysum
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_2_0($module)
{
    $module->registerHook('displayProductButtonFixed');
    $module->registerHook('displayBackOfficeHeader');
    return true;
}