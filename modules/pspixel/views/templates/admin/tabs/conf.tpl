{*
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*}

{* selected done *}
<form id="submitConf" name="submitConf" action="#" method="post">
<h3><i class="icon-book"></i> {l s='Configuration' mod='pspixel'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small></h3>
<div id="wizard" class="swMain">
	<div class="clearfix"></div>
	<div class="panel-group" id="accordion">
		<div class="alert alert-info">
			<h4 class="media-heading"><p>{l s='Welcome to your module configuration !' mod='pspixel'}</p></h4>
			{l s='The Official Facebook Pixel module will help you to implement an analysis tool into your website pages and track different types of events that occur when your clients visit your website and take an action.' mod='pspixel'}
			<br><br>
			{l s='Those are the events tracked on your website :' mod='pspixel'}<br>
			<ul>
				<li><b>PageView </b>: {l s='all pages' mod='pspixel'}</li>
				<li><b>ViewContent </b>: {l s='when a product page is viewed' mod='pspixel'}</li>
				<li><b>ViewCategory </b>: {l s='when a category page is viewed' mod='pspixel'}</li>
				<li><b>ViewCMS </b>: {l s='when a CMS page (a page with content : general condition of use, About us, Our stores, etc.)' mod='pspixel'}</li>
				<li><b>AddToCart </b>: {l s='when a product is added to the cart' mod='pspixel'}</li>
				<li><b>InitiateCheckout </b>: {l s='when a customer reaches his shopping cart summary page' mod='pspixel'}</li>
				<li><b>AddPaymentInfo </b>: {l s='when a customer reaches the last step of the order funnel ' mod='pspixel'}</li>
				<li><b>Search </b>: {l s='when a customer makes a search ' mod='pspixel'}</li>
				<li><b>Purchase </b>: {l s='when a customer makes a purchase (success or failure) ' mod='pspixel'}</li>
			</ul><br>
			{l s='All those events can then be analyzed in your Facebook Ads manager.' mod='pspixel'}
		</div>

		<div id="collapse1">
			<div class="table-responsive clearfix">
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Pixel ID' mod='pspixel'}
					</label>
					<div class="col-sm-6">
						<input type="text" minlength="15" maxlength="16" class="form-control" id="PS_PIXEL_ID" name="PS_PIXEL_ID" placeholder="{l s='Your facebook pixel ID' mod='pspixel'}" value="{if isset($id_pixel) && !empty($id_pixel)}{$id_pixel|escape:'htmlall':'UTF-8'}{/if}" maxlength="16"/>
						<p class="help-block">&nbsp;</p>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>


	</div>
	<div class="panel-footer">
		<div class="btn-group pull-right">
			<button name="submitPixel" type="submit" class="btn btn-default" value="1"><i class="process-icon-save"></i> {l s='Save'  mod='pspixel'}</button>
		</div>
	</div>
</form>

</div>



{*
	Advance Options V1.2.0
	Data	Parameter	Example	Format Guideline
	Email 	em 	jsmith@example.com	Lower case email address of person
	First Name 	fn	john 	Lower case first name
	Last Name	ln	smith	Lower case last name
	Phone	ph	16505551212	Phone number, only digits with country code, area code, and number
	Gender	ge	m	Either f or m, if unknown, leave blank
	Date of Birth	db	19911226	Date of birth year, month, date, such as 19911226 for December 26, 1991
	City	ct	menlopark	City in lower case with spaces removed
	State	st	ca	2 letter state code
	Zip	zp	94035	5 digit zip code
*}
