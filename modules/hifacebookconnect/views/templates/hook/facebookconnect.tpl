{**
* 2013 - 2017 HiPresta
*
* MODULE Facebook Connect
*
* @version   1.1.0
* @author    HiPresta <suren.mikaelyan@gmail.com>
* @link      http://www.hipresta.com
* @copyright HiPresta 2017
* @license   PrestaShop Addons license limitation
*
*}

{if isset($hi_sc_fb_on) && $hi_sc_fb_on}
	{if $hi_sc_fb_button_size == 'big'}
		<div id="fb-root"></div>
		<a onclick="fb_login();" class="hisc-button hisc-fb-button onclick-btn">
			<span class="hisc-button-text">
				<span>{l s='Sign in with Facebook' mod='hifacebookconnect'}</span>
			</span>
			<span class="hisc-button-icon">
				<span></span>
			</span>
		</a>
	{else}
		<a onclick="fb_login();" class="hisc-s-btn hisc-fb-s-btn onclick-btn" title="{l s='Sign in with Facebook' mod='hifacebookconnect'}"><span><span></span></span></a>
	{/if}
{/if}



