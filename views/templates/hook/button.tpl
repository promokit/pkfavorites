{*
* Promokit Favorites Module
*
* @package   alysum
* @version   2.3.0
* @author    https://promokit.eu
* @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
* @license   You only can use module, nothing more!
*}
{assign var='overall' value=''}
{assign var='printButtonText' value=''}
{assign var='btnClasses' value=['favoritesButton', 'flex-container', 'align-items-center', 'favorites-button']}
{if isset($config.button_position)}
    {append var='btnClasses' value=$config.button_position}
{/if}

{if isset($is_standalone) && $is_standalone}
    {append var='btnClasses' value='fav-standalone'}
    {append var='btnClasses' value='btn'}
{/if}

{if isset($isFavorite) && $isFavorite}
    {assign var='action' value='remove'}
    {assign var='btnText' value="{l s='Remove from favorites' d='Modules.Pkfavorites.Shop'}"}
    {append var='btnClasses' value='icon_checked'}
{else}
    {assign var='action' value='add'}
    {assign var='btnText' value="{l s='Add to favorites' d='Modules.Pkfavorites.Shop'}"}
{/if}

{if isset($isProductPage) && $isProductPage}
    {assign var='printButtonText' value="<span>{$btnText}</span>"}
    {append var='btnClasses' value='icon-button'}
{else}
    {if isset($config.overall_number) && ($config.overall_number == 1)}
        {assign var='printButtonText' value="<i>{$overallNumber}</i>"}
    {/if}
    {append var='btnClasses' value='btn'}
{/if}

<a href="#" class="{' '|implode:$btnClasses}" data-pid="{$idProduct}" data-action="{$action}" title="{$btnText}"
    aria-label="{$btnText}" role="button">
    <svg class="svgic">
        <use href="{_THEME_IMG_DIR_}lib.svg#love"></use>
    </svg>
    {$printButtonText nofilter}
</a>