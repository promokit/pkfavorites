{*
* Promokit Favorites Module
*
* @package   alysum
* @version   3.0.0
* @author    https://promokit.eu
* @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
* @license   You only can use module, nothing more!
*}

{if isset($products) && (!empty($products))}
{foreach from=$products item=product}
    {include file="catalog/_partials/miniatures/product.tpl" product=$product}
{/foreach}
{/if}