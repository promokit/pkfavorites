{*
* Promokit Favorites Module
*
* @package   alysum
* @version   2.3.0
* @author    https://promokit.eu
* @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
* @license   You only can use module, nothing more!
*}
{strip}
{assign var='overall' value=''}
{assign var='printButtonText' value=''}
{assign var='btnClasses' value=['favoritesButton', 'flex-container', 'align-items-center']}
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
    {append var='btnClasses' value='flex-container'}
    {append var='btnClasses' value='align-items-center'}
{else}
    {append var='btnClasses' value='btn'}
    {if isset($config.overall_number) && ($config.overall_number == 1)}
        {assign var='overall' value=$overallNumber}
    {/if}
{/if}

<a href="#" class="{' '|implode:$btnClasses}" data-pid="{$idProduct}" data-action="{$action}" title="{$btnText}" aria-label="{$btnText}" role="button">
  {$overall}
  <svg class="svgic"><use xlink:href="#si-love"></use></svg>
  {$printButtonText nofilter}
</a>
{/strip}