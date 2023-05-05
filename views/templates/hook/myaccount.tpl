{*
* Promokit Favorites Module
*
* @package   alysum
* @version   2.3.0
* @author    https://promokit.eu
* @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
* @license   You only can use module, nothing more!
*}

<a class="col-lg-4 col-md-6 col-sm-6 col-xs-12" id="favorites-link" href="{$link->getModuleLink('pkfavorites', 'account')}">
    <span class="link-item">
        <svg class="svgic">
            <use href="{_THEME_IMG_DIR_}lib.svg#like-stroke"></use>
        </svg>
        <span>{l s='My favorite products' d='Modules.Pkfavorites.Shop'}</span>
    </span>
</a>