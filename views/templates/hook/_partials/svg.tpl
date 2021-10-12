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
{capture name="svg_icon" assign="svg_icon"}
<symbol id="si-like-stroke" viewBox="0 0 18 18"><path d="M8.492,1.709 C7.558,0.642 6.114,-0.015 4.670,-0.015 C2.038,-0.015 -0.001,1.956 -0.001,4.499 C-0.001,7.618 2.887,10.163 7.303,13.937 C8.492,15.004 8.492,15.004 8.492,15.004 L9.681,13.937 C14.013,10.079 16.986,7.536 16.986,4.499 C16.986,1.956 14.947,-0.015 12.314,-0.015 C10.870,-0.015 9.427,0.642 8.492,1.709 ZM8.492,12.789 L8.407,12.707 C4.331,9.177 1.698,6.878 1.698,4.499 C1.698,2.858 2.972,1.627 4.670,1.627 C5.944,1.627 7.218,2.447 7.728,3.596 C9.342,3.596 9.342,3.596 9.342,3.596 C9.766,2.447 11.040,1.627 12.314,1.627 C14.013,1.627 15.287,2.858 15.287,4.499 C15.287,6.878 12.654,9.177 8.577,12.789 L8.492,12.789 Z"/></symbol>
<symbol id="si-love" viewBox="0 0 552 552">
<path style="transform: translate(20px, 20px);" d="M376,30c-27.783,0-53.255,8.804-75.707,26.168c-21.525,16.647-35.856,37.85-44.293,53.268 c-8.437-15.419-22.768-36.621-44.293-53.268C189.255,38.804,163.783,30,136,30C58.468,30,0,93.417,0,177.514 c0,90.854,72.943,153.015,183.369,247.118c18.752,15.981,40.007,34.095,62.099,53.414C248.38,480.596,252.12,482,256,482 s7.62-1.404,10.532-3.953c22.094-19.322,43.348-37.435,62.111-53.425C439.057,330.529,512,268.368,512,177.514 C512,93.417,453.532,30,376,30z"/>
</symbol>
{/capture}
{capture name="svg_begin" assign="svg_begin"}
<!--noindex--><svg class="pk-svg-library" style="display: none" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs>
{/capture}
{capture name="svg_end" assign="svg_end"}
</defs></svg><!--/noindex-->
{/capture}

{if isset($is_standalone) && $is_standalone}
{sprintf("%s $svg_icon %s", "$svg_begin", "$svg_end") nofilter}
{else}
{$svg_icon nofilter}
{/if}
{/strip}