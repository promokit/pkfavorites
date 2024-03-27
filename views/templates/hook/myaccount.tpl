{*
 * Promokit Favorites Module
 *
 * @package   alysum
 * @version   3.0.0
 * @author    https://promokit.eu
 * @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
 * @license   You only can use module, nothing more!
 *}

<a class="col-lg-4 col-md-6 col-sm-6 col-xs-12 favorites-link" href="{$link->getModuleLink('pkfavorites', 'account')}">
    <span class="link-item">
        {include file="components/svg-icon.tpl" id="like-stroke"}
        <span>{l s='My favorite products' d='Modules.Pkfavorites.Shop'}</span>
    </span>
</a>