{*
* Promokit Favorites Module
*
* @package   alysum
* @version   2.3.0
* @author    https://promokit.eu
* @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
* @license   You only can use module, nothing more!
*}

{assign var=controller_url value="<a href=\"{$link->getModuleLink($pkModuleName, 'account')}\">"}
{assign var=favorites value=['pkfavorites' => [
'add' => "{$link->getModuleLink($pkModuleName, 'actions', ['process' => 'add'])}",
'remove' => "{$link->getModuleLink($pkModuleName, 'actions', ['process' => 'remove'], true)}",
'phrases' => [
    'title' => "{l s='favorites' d='Modules.Pkfavorites.Shop'}",
    'add' => "{l s='Add to favorites' d='Modules.Pkfavorites.Shop'}",
    'remove' => "{l s='Remove from favorites' d='Modules.Pkfavorites.Shop'}",
    'added' => "{l s='The product has been added to your [1]favorites[/1]' sprintf=['[1]' => {$controller_url nofilter}, '[/1]' => '</a>'] d='Modules.Pkfavorites.Shop'}",
    'removed' => "{l s='The product has been removed from [1]favorites[/1]' sprintf=['[1]' => {$controller_url nofilter}, '[/1]' => '</a>'] d='Modules.Pkfavorites.Shop'}"
]]]}

{$pkMedia->addJsDef($favorites)}