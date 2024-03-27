{*
 * Promokit Favorites Module
 *
 * @package   alysum
 * @version   3.0.0
 * @author    https://promokit.eu
 * @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
 * @license   You only can use module, nothing more!
 *}

{extends file='page.tpl'}

{block name="page_content"}
<div class="favorites-list col-xs-12">
	<h2 class="page-title">
		{l s='My favorite products' d='Modules.Pkfavorites.Shop'}
	</h2>
	{if $favorites}
		<div class="view_grid">
			<div class="product_list">
				<div class="grid-container">
				{foreach from=$favorites item=product}
					{include file="catalog/_partials/miniatures/product.tpl" product=$product}
				{/foreach}
				</div>
			</div>
		</div>
	{/if}
	<p class="elementor-alert elementor-alert-info pk-no-products">
		{l s='No favorite products have been determined just yet' d='Modules.Pkfavorites.Shop'}
	</p>
</div>
{/block}