<form class="form-horizontal defaultForm" method="post" action="{$action}">
	<div class="panel">
		<div class="panel-heading">
			{l s='Facebook Connect' mod='hifacebookconnect'}
		</div>
		<div class="form-wrapper">
			<div class="form-group">
				<label class="control-label col-lg-3">
					{l s='Enable Facebook Login ?' mod='hifacebookconnect'}
				</label>
				<div class="col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="hi_fb_on" id="hi_fb_on_on" value="1" {if $hi_fb_on == 1}checked="checked"{/if} >
						<label for="hi_fb_on_on">
							{l s='yes' mod='hifacebookconnect'}
						</label>
						<input type="radio" name="hi_fb_on" value="0" id="hi_fb_on_off" {if $hi_fb_on == 0}checked="checked"{/if}>
						<label for="hi_fb_on_off">
							{l s='no' mod='hifacebookconnect'}
						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3">
					{l s='Redirect After Login' mod='hifacebookconnect'}
				</label>
				<div class="col-lg-3">
					<select name="hi_fb_login_page" >
						<option value="no_redirect" {if $hi_login_page == 'no_redirect'}selected="selected"{/if}>
							{l s='No redirect' mod='hifacebookconnect'}
						</option>
						<option value="authentication_page" {if $hi_login_page == 'authentication_page'}selected="selected"{/if}>
							{l s='My Account page' mod='hifacebookconnect'}
						</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3" for="hi_fb_app_id">
					{l s='Facebook App ID' mod='hifacebookconnect'}
				</label>
				<div class="col-lg-6">
					<input value="{$hi_fb_app_id}" type="text" placeholder="{l s='Facebook app ID' mod='hifacebookconnect'}" name="hi_fb_app_id" id="hi_fb_app_id">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3">
					{l s='Positions to display' mod='hifacebookconnect'}
				</label>
				<div class="col-lg-6">
					<label class="control-label hi_fb_position_label">
						{l s='Top' mod='hifacebookconnect'}
					</label>
					<a href="#" class="hi_fb_position {if $hi_fb_position_top == 1}chcked{/if}">
						<i class="icon-check-empty {if $hi_fb_position_top == 1}icon-check-sign{/if}"></i>
						<input type="hidden" name="hi_fb_position_top" value="{$hi_fb_position_top}">
					</a>
					<br/>
					<label class="control-label hi_fb_position_label">
						{l s='Right Block' mod='hifacebookconnect'}
					</label>
					<a href="#" class="hi_fb_position {if $hi_fb_position_right == 1}chcked{/if}">
						<i class="icon-check-empty {if $hi_fb_position_right == 1}icon-check-sign{/if}"></i>
						<input type="hidden" name="pf_sc_fb_position_right" value="{$hi_fb_position_right}">
					</a>
					<br/>
					<label class="control-label hi_fb_position_label">
						{l s='Left Block' mod='hifacebookconnect'}
					</label>
					<a href="#" class="hi_fb_position {if $hi_fb_position_left == 1}chcked{/if}">
						<i class="icon-check-empty {if $hi_fb_position_left == 1}icon-check-sign{/if}"></i>
						<input type="hidden" name="hi_fb_position_left" value="{$hi_fb_position_left}">
					</a>
					<br/>
					<label class="control-label hi_fb_position_label">
						{l s='Custom' mod='hifacebookconnect'}
					</label>
					<a href="#" class="hi_fb_position {if $hi_fb_position_custom == 1}chcked{/if}">
						<i class="icon-check-empty {if $hi_fb_position_custom == 1}icon-check-sign{/if}"></i>
						<input type="hidden" name="hi_fb_position_custom" value="{$hi_fb_position_custom}">
					</a>
					<p class="help-block">
						{l s='Add {hook h="hiFacebookConnect"} to your page tpl file where you want to display.' mod='hifacebookconnect'}
					</p>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<button type="submit" class="btn btn-default pull-right" name="hi_fb_submit">
				<i class="process-icon-save"></i>
				{l s='Save' mod='hifacebookconnect'}
			</button>
		</div>
	</div>
</form>
<form class="form-horizontal defaultForm" method="post" action="/">
	<div class="panel">
		<div class="panel-heading">
			{l s='Check our modules' mod='hifacebookconnect'}
		</div>
		<div class="row">
			<div class="col-lg-4 col-md-6 col-sm-6">
				<a class="addons-style-module-link" href="http://codecanyon.net/item/prestashop-popup-notification-social-connect/5748727?ref=shoppresta" target="_blank">
					<div class="media addons-style-module panel">
						<div class="media-body addons-style-media-body">
							<h4 class="media-heading addons-style-media-heading">{l s='Popup Notification + Social Connect' mod='hifacebookconnect'}</h4>
						</div>
						<div class="addons-style-theme-preview center-block">
							<img class="addons-style-img_preview-theme" src="http://hipresta.com/images/pn.jpg">
							<p class="btn btn-default">
								<i class="icon-shopping-cart"></i>
								$20
							</p>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6">
				<a class="addons-style-module-link" href="http://codecanyon.net/item/facebook-shop-tab/11023691?ref=shoppresta" target="_blank">
					<div class="media addons-style-module panel">
						<div class="media-body addons-style-media-body">
							<h4 class="media-heading addons-style-media-heading">{l s='Facebook Shop Tab' mod='hifacebookconnect'}</h4>
						</div>
						<div class="addons-style-theme-preview center-block">
							<img class="addons-style-img_preview-theme" src="http://hipresta.com/images/shoptab.jpg">
							<p class="btn btn-default">
								<i class="icon-shopping-cart"></i>
								$18
							</p>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6">
				<a class="addons-style-module-link" href="http://addons.prestashop.com/en/slideshows-prestashop-modules/20410-carousels-pack-14-in-1.html" target="_blank">
					<div class="media addons-style-module panel">
						<div class="media-body addons-style-media-body">
							<h4 class="media-heading addons-style-media-heading">{l s='Carousels Pack (14 in 1)' mod='hifacebookconnect'}</h4>
						</div>
						<div class="addons-style-theme-preview center-block">
							<img class="addons-style-img_preview-theme" src="http://hipresta.com/images/cp.jpg">
							<p class="btn btn-default">
								<i class="icon-shopping-cart"></i>
								€29.99
							</p>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6">
				<a class="addons-style-module-link" href="http://codecanyon.net/item/social-connect-prestashop-module/11829477?ref=shoppresta" target="_blank">
					<div class="media addons-style-module panel">
						<div class="media-body addons-style-media-body">
							<h4 class="media-heading addons-style-media-heading">{l s='Social Connect (15 in 1)' mod='hifacebookconnect'}</h4>
						</div>
						<div class="addons-style-theme-preview center-block">
							<img class="addons-style-img_preview-theme" src="http://hipresta.com/images/sc.png">
							<p class="btn btn-default">
								<i class="icon-shopping-cart"></i>
								$17
							</p>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6">
				<a class="addons-style-module-link" href="https://addons.prestashop.com/en/product.php?id_product=20091" target="_blank">
					<div class="media addons-style-module panel">
						<div class="media-body addons-style-media-body">
							<h4 class="media-heading addons-style-media-heading">{l s='Out of Stock Notification' mod='hifacebookconnect'}</h4>
						</div>
						<div class="addons-style-theme-preview center-block">
							<img class="addons-style-img_preview-theme" src="http://hipresta.com/images/oosn.jpg">
							<p class="btn btn-default">
								<i class="icon-shopping-cart"></i>
								€29.99
							</p>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6">
				<a class="addons-style-module-link" href="http://codecanyon.net/item/prestashop-facebook-like-voucher/7028501?ref=shoppresta" target="_blank">
					<div class="media addons-style-module panel">
						<div class="media-body addons-style-media-body">
							<h4 class="media-heading addons-style-media-heading">{l s='Facebook Like Voucher' mod='hifacebookconnect'}</h4>
						</div>
						<div class="addons-style-theme-preview center-block">
							<img class="addons-style-img_preview-theme" src="http://hipresta.com/images/fblv.jpg">
							<p class="btn btn-default">
								<i class="icon-shopping-cart"></i>
								$17
							</p>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</form>
<form class="form-horizontal defaultForm" method="post" action="/">
	<div class="panel">
		<div class="panel-heading">
			{l s='How to create Facebook APP' mod='hifacebookconnect'}
		</div>
		<div class="form-wrapper">
			<div class="form-group">
				<iframe width="420" height="315" src="https://www.youtube.com/embed/zDo4kyFvMYo" frameborder="0" allowfullscreen></iframe>
			</div>
		</div>
	</div>
</form>
<script>
	$(document).ready(function(){
		$(".hi_fb_position").on("click", function(){
			if($(this).hasClass("checked")){
				$(this).removeClass("checked");
				$(this).find("i").removeClass("icon-check-sign");
				$(this).find("input").val(0);
			}else{
				$(this).addClass("checked");
				$(this).find("i").addClass("icon-check-sign");
				$(this).find("input").val(1);
			}
			return false;
		});
		$(".hi_fb_position_label").on("click", function(){
			$(this).next().click();
		});
	});
</script>
<style type="text/css">
	.addons-style-img_preview-theme{
		max-width: 100%;
	}
	.addons-style-theme-preview > p {
		margin-top: 10px;
	}
</style>