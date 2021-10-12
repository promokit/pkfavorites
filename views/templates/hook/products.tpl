{*
* Promokit Favorites Module
*
* @package   alysum
* @version   2.3.0
* @author    https://promokit.eu
* @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
* @license   You only can use module, nothing more!
*}

{extends file='page.tpl'}

{block name="page_content"}
<div id="favorites-list" class="col-xs-12">
	<h2 class="page-title">{l s='My favorite products' d='Modules.Pkfavorites.Shop'}</h2>
	{if $favoriteProducts}
		<div class="view_grid">
			<div class="product_list">
				<div class="flex-container grid-container">
				{foreach from=$favoriteProducts item=favoriteProduct}
					{include file="catalog/_partials/miniatures/product.tpl" product=$favoriteProduct}
				{/foreach}
				</div>
			</div>
		</div>
	{else}
		<p class="elementor-alert elementor-alert-info">
			{l s='No favorite products have been determined just yet' d='Modules.Pkfavorites.Shop'}
		</p>
	{/if}
</div>
{/block}