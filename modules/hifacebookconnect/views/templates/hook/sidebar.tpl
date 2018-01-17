{if isset($hi_fb_on) && $hi_fb_on}
	<div id="fb-root"></div>
	<a onclick="fb_login();" class="hi-fb-button">
		<span class="hi-fb-button-text">
			<span>{l s='Sign in with Facebook' mod='hifacebookconnect'}</span>
		</span>
		<span class="hi-fb-button-icon">
			<span></span>
		</span>	
	</a>
{/if}