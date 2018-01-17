<!--
* 2012-2017 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2017 NetReviews SAS
*  @version   Release: $Revision: 7.4.2
*  @license   NetReviews
*  @date      16/10/2017
*  International Registered Trademark & Property of NetReviews SAS
-->
{if $oldversion}
<div class="bootstrap">
{/if}
<div class="content bootstrap av_backoffice"> 
	<div class="row netreviews_bo_wrapper well">
		<div class="col-lg-12 col-sm-12 col-xs-12">
			<a href="#"  data-toggle="tooltip" title="{l s='Module Version' mod='netreviews'} {$version|escape:'htmlall':'UTF-8'}"><img class="av_display_center" alt="" src="{$av_path|escape:'htmlall':'UTF-8'}views/img/{l s='logo_full_en.png' mod='netreviews'}"/>
		</a>
		</div>
		<hr />
		<div class="nv_contents" >
				<div class="col-lg-8 col-sm-12 col-xs-12 ">
					<div class="av_padding20">
					<h1>{l s='Increase your sales through customer reviews' mod='netreviews'}</h1>
					<p> {l s='Verified-Reviews is a trusted third party specialised in the collection,' mod='netreviews'}
					{l s='moderation and publication of post-purchase customers reviews about a brand, products and stores.' mod='netreviews'}
					{l s='Displaying reviews on our certificate, your product pages and store locator will improve your visibility and credibility that will attract more visitors, convert them more easily and win their loyalty.' mod='netreviews'}
					{l s='An ethical model that will increase your sales and help your e-reputation.' mod='netreviews'}</p>

					<p>{l s='In partnership with' mod='netreviews'} :</p>
					<img src="{$av_path|escape:'htmlall':'UTF-8'}views/img/prestashop_partner_logo_new.png" width="200" alt="">
					<img src="{$av_path|escape:'htmlall':'UTF-8'}views/img/prestashop_partner_logo_shadow.png" style="float:right;width: 225px;" alt="">
					<p><img src="{$av_path|escape:'htmlall':'UTF-8'}views/img/NFS_Avis-en-ligne.png" width="40" alt=""> {l s='Our services are approved by AFNOR certification' mod='netreviews'}</p>
					</div>
				</div>
				<div  class="col-lg-4 col-sm-12 col-xs-12">
					<div class="av_padding20">
					<ul class="av-list-stars">
						<li>{l s='Give your customer a voice' mod='netreviews'}</li>
						<li>{l s='Improve your SEO with Rich Snippets' mod='netreviews'}</li>
						<li>{l s='Boost your Adwords campaign by gaining star ratings from our partner' mod='netreviews'}<img src="{$av_path|escape:'htmlall':'UTF-8'}views/img/google-adwords.png" width="100" alt=""></li>
						<li>{l s='Control your e-reputation' mod='netreviews'}</li>
						<li>{l s='Increase your sales up to 25%' mod='netreviews'}</li>
						<li>{l s='Build customer loyalty' mod='netreviews'}</li>
					</ul>
					</div>
				</div>
				<div  class="col-lg-12 col-sm-12 col-xs-12 av_padding20" style="text-align: center;border;border-top: 2px solid #f9f9f9;">
					<a href="{l s='url_avisverifies_track' mod='netreviews'}" class="btn btn-lg av_display_center" style="color:white;" role="button" target="_blank">{l s='Start now' mod='netreviews'}</a>
					<i class="av_display_center av_padding20">{l s='No commitment, free trial for 15 days' mod='netreviews'}</i>
				</div>
			</div>
			
		</div>

	</div> <!-- END row module_title-->

	<div class="clearfix"> </div>
	
	<form method="post" action="{$url_back|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="form-horizontal">
	<!-- START configuration -->
	<div class="panel col-lg-12">
		<div class="panel-heading"><i class="icon-cogs"></i> {l s='Configuration' mod='netreviews'}</div>
		<div class='config panel-body' >
			<p>{l s='The Module Verified Reviews allows you to show verified product reviews on your product urls, to show the Widget Verified Reviews and to collect automatically verified customer reviews via Email after each single order.' mod='netreviews'}</p>
			<p class="alert alert-info">{l s='Attention : It is obligatory to register first on' mod='netreviews'} <a href="{l s='url_avisverifies_track' mod='netreviews'}" target="_blank">{l s='www.verified-reviews.com' mod='netreviews'}</a>
			 {l s='to start your free trial period' mod='netreviews'}.
			{l s='Please check your' mod='netreviews'} <a href="{l s='url_avisverifies_track' mod='netreviews'}" target="_blank">{l s='customer area on verified-reviews.com' mod='netreviews'}</a> {l s='to see your login data' mod='netreviews'}</p>
			
				<div class="col-lg-12 col-sm-12 col-xs-12">
				<!-- If not multilangual -->
				<div class="{if $current_multilingue_checked  == 'checked'} hidden {/if} configuration_labels" id="av_configuration">
						<div class="form-group">
							<label class="control-label col-lg-3 col-sm-2 col-xs-12"><b>{l s='Secret Key' mod='netreviews'}</b></label>
							<div class="col-lg-9 col-sm-10 col-xs-12">
								<div class="form-group col-lg-6 col-sm-12 col-xs-12">
								<input type="text" name="avisverifies_clesecrete" id="avisverifies_clesecrete" value="{$current_avisverifies_clesecrete['root']|escape:'htmlall':'UTF-8'}"/>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-lg-3 col-sm-2 col-xs-12"><b>{l s='ID Website' mod='netreviews'}</b></label>
							<div class="col-lg-9 col-sm-10 col-xs-12">
								<div class="form-group col-lg-6 col-sm-12 col-xs-12">
								<input type="text" name="avisverifies_idwebsite" id="avisverifies_idwebsite" value="{$current_avisverifies_idwebsite['root']|escape:'htmlall':'UTF-8'}"/>
								</div>
							</div>
						</div>
				</div>
					<!-- If multilangual -->
						<div class="row {if $current_multilingue_checked  != 'checked'} hidden {/if} configuration_labels" id="av_multilanguage_configuration">
							{foreach from=$languages key=id item=lang}
							<div class="language col-lg-6 col-sm-12 col-xs-12">

							<div class="form-group col-lg-12 col-sm-12 col-xs-12">
								<div class="col-lg-1 col-sm-1 col-xs-1">
										<img class="img-thumbnail img_flag" src="{$base_url}img/l/{$lang.id_lang|escape:'htmlall':'UTF-8'}.jpg" alt="{$lang.name|escape:'htmlall':'UTF-8'}"/>
								</div>
								 <label class="control-label namelanguage  col-lg-11 col-sm-11 col-xs-11">{$lang.name|escape:'htmlall':'UTF-8'}</label>
							</div>

							<div class="form-group col-lg-12 col-sm-12 col-xs-12">
								<label class="control-label col-lg-3 col-sm-4 col-xs-12"><b>{l s='Secret Key' mod='netreviews'}</b></label>
								<div class="col-lg-9 col-sm-8 col-xs-12">
									<div class="form-group col-lg-10 col-sm-12 col-xs-12">
									<input type="text" name="avisverifies_clesecrete_{$lang.iso_code|escape:'htmlall':'UTF-8'}" id="avisverifies_clesecrete_{$lang.iso_code}" value="{$current_avisverifies_clesecrete[$lang.iso_code]|escape:'htmlall':'UTF-8'}"/>
									</div>
								</div>
							</div>

							<div class="form-group col-lg-12 col-sm-12 col-xs-12">
								<label class="control-label col-lg-3 col-sm-4 col-xs-12"><b>{l s='ID Website' mod='netreviews'}</b></label>
								<div class="col-lg-9 col-sm-8 col-xs-12">
									<div class="form-group col-lg-10 col-sm-12 col-xs-12">
									<input type="text" name="avisverifies_idwebsite_{$lang.iso_code|escape:'htmlall':'UTF-8'}" id="avisverifies_idwebsite_{$lang.iso_code}" value="{$current_avisverifies_idwebsite[$lang.iso_code]|escape:'htmlall':'UTF-8'}"/>
									</div>
								</div>
							</div>

								</div>
							{/foreach}
						</div>
 
			<div class="row">
				<div class="form-group">
				<!-- multilanguage configurations START-->
					<label class="col-lg-3 col-sm-4 col-xs-12 control-label">
					<span class="label-tooltip" data-toggle="tooltip" data-html="true" title="{l s='To enable this option, your review requests will be sent in the according language' mod='netreviews'}" data-original-title=""><b> {l s='Enable the multilingual configuration' mod='netreviews'}</b></span></label>
					<div class="col-lg-9 col-sm-8 col-xs-12">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="avisverifies_multilingue" id="avisverifies_multilingue_on" value="checked"  {if ($current_multilingue_checked eq "checked")} checked="checked"{/if}>
							<label for="avisverifies_multilingue_on" class="radioCheck">
								 {l s='Yes' d='Admin.Global'}
							</label>
							<input type="radio" name="avisverifies_multilingue" id="avisverifies_multilingue_off" value="0" {if ($current_multilingue_checked eq "0")} checked="checked"{/if}>
							<label for="avisverifies_multilingue_off" class="radioCheck">
								 {l s='No' d='Admin.Global'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
			</div><!-- multilanguage configurations END-->
			
			<div class="clearfix"> </div>
		</div>

			</div>
				<div class="panel-footer">
				<button type="submit" name="submit_configuration" id="submit_configuration" class="button pointer btn btn-default pull-right">
							<i class="process-icon-save"></i> {l s='Save' mod='netreviews'}
				</button>
				</div>
	</div>  
		<!-- END configuration -->

	<div class="panel col-lg-12 col-sm-12 col-xs-12">
		<div class="panel-heading"><i class="icon-cogs"></i> {l s='Export my orders' mod='netreviews'}</div>
		<div class='panel-body'>
			<div class="row">
				<p class="alert alert-info">{l s='Export your recently received orders to collect immediately your first customer reviews and to show your attestation Verified Reviews.' mod='netreviews'}
				</p>
				<ul>
					<li>{l s='Without Product Reviews : Your customers will only be asked for their reviews regarding the order (obligatory)' mod='netreviews'}</li>
					<li>{l s='With Product Reviews : Your customers will be asked for their review regarding the order (obligatory) AND regarding the purchased products as well' mod='netreviews'}</li>
				</ul>
			</div>
			
				 <div>
						<div class="row form-group col-lg-12 col-sm-12 col-xs-12">
							<label for="duree" class="control-label col-lg-3 col-sm-4 col-xs-12"><b>{l s='Since' mod='netreviews'}</b></label>
							<div class="col-lg-9 col-sm-8 col-xs-12">
								<select id="duree" name="duree" class="col-lg-6 col-sm-12 col-xs-12">
									<option value="1w">{l s='1 week' mod='netreviews'}</option>
									<option value="2w">{l s='2 weeks' mod='netreviews'}</option>
									<option value="1m">{l s='1 month' mod='netreviews'}</option>
									<option value="2m">{l s='2 months' mod='netreviews'}</option>
									<option value="3m">{l s='3 months' mod='netreviews'}</option>
									<option value="4m">{l s='4 months' mod='netreviews'}</option>
									<option value="5m">{l s='5 months' mod='netreviews'}</option>
									<option value="6m">{l s='6 months' mod='netreviews'}</option>
									<option value="7m">{l s='7 months' mod='netreviews'}</option>
									<option value="8m">{l s='8 months' mod='netreviews'}</option>
									<option value="9m">{l s='9 months' mod='netreviews'}</option>
									<option value="10m">{l s='10 months' mod='netreviews'}</option>
									<option value="11m">{l s='11 months' mod='netreviews'}</option>
									<option value="12m">{l s='12 months' mod='netreviews'}</option>
								</select>
							</div>
					 </div>
					
					 <div class="row form-group col-lg-12 col-sm-12 col-xs-12">
						<label class="col-lg-3 col-sm-4 col-xs-12 control-label"><b>{l s='Collect Product Reviews' mod='netreviews'}</b></label>
						<div class="col-lg-9 col-sm-8 col-xs-12">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="productreviews" id="productreviews_on" value="1">
								<label for="productreviews_on" class="radioCheck">
									 {l s='Yes' d='Admin.Global'}
								</label>
								<input type="radio" name="productreviews" id="productreviews_off" value="0" checked="checked">
								<label for="productreviews_off" class="radioCheck">
									 {l s='No' d='Admin.Global'}
								</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
					</div>
					
					<div class="row form-group col-lg-12 col-sm-12 col-xs-12">
						<label class="col-lg-3 col-sm-4 col-xs-12 control-label"><b>{l s='Export orders with status' mod='netreviews'}</b></label>	
						  <div class="col-lg-9 col-sm-8 col-xs-12">
								<div class="btn_av_small">
									<a href="javascript:cocheToute();" class="btn btn-sm">{l s='Check all' mod='netreviews'} </a> 
									<a href="javascript:decocheToute();" class="btn btn-sm"> {l s='Uncheck all' mod='netreviews'}</a>
								</div>
								{foreach from=$order_statut_list item=state}
									<div class="checkbox">
									  <label><input class="cbOrderstates" name="orderstates[]" type="checkbox" value="{$state['id_order_state']|escape:'htmlall':'UTF-8'}" />{$state['name']|escape:'htmlall':'UTF-8'}</label>
									</div>
								{/foreach}
						 </div>
					</div>

					</div> <!-- end row -->
				</div>
				<div class="panel-footer">
				<button type="submit" name="submit_export" id="submit_export"  class="button pointer btn btn-default pull-right">
							<i class="process-icon-save"></i> {l s='Export' mod='netreviews'}
				</button>
				</div>
	</div>
	
	<div class="clearfix"> </div>
	<!-- Design START -->
	  <div class="panel col-lg-12 col-sm-12 col-xs-12">
			<div class="panel-heading"><i class="icon-cogs"></i>  {l s='Design' mod='netreviews'}</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-lg-3 col-sm-4 col-xs-12"><b>{l s='Choose the design of the stars on product pages' mod='netreviews'}</b></label>
					<div class="col-lg-9 col-sm-8 col-xs-12">
						<div class="radio">
							 <label><input type="radio" name="avisverifies_lightwidget" id="avisverifies_lightwidget_1" value="1" {if ($current_lightwidget_checked eq '1' or  !$current_lightwidget_checked)} checked="checked" {/if}> {l s='simple stars' mod='netreviews'}</label>
						 </div>
					<div class="radio">
						<label><input type="radio" name="avisverifies_lightwidget" id="avisverifies_lightwidget_2" value="2" {if ($current_lightwidget_checked eq '2')} checked="checked" {/if}> {l s='Product reviews widget (by default)' mod='netreviews'}</label>
					</div>
					<div class="radio">
						<label><input type="radio" name="avisverifies_lightwidget" id="avisverifies_lightwidget_3" value="3" {if ($current_lightwidget_checked eq '3')} checked="checked" {/if}> {l s='Product reviews widget (classic design)' mod='netreviews'}</label>
					</div>
				</div>
			</div>
	
			  <div class="form-group">
				<label class="control-label col-lg-3 col-sm-4 col-xs-12">
					<span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="">
					<b>{l s='Set the number of reviews product displayed' mod='netreviews'}</b>
					</span>
				</label>
				<label class="control-label col-lg-9 col-sm-8 col-xs-12">
					<span class="col-lg-1 col-sm-4 col-xs-4">
						<input type="text" class="numbersOnly form-control"  name="avisverifies_nb_reviews" id="avisverifies_nb_reviews" value="{if $avisverifies_nb_reviews}{$avisverifies_nb_reviews|escape:'htmlall':'UTF-8'} {else}20{/if}" />
					</span>
				</label>
			</div> 

		<!-- display category stars -->
			<div class="form-group">
				<label class="col-lg-3 col-sm-4 col-xs-12 control-label"><span class="label-tooltip" data-toggle="tooltip" data-html="true" title="{l s='the stars do not appear ? please click here to find the solution' mod='netreviews'}" data-original-title=""><a href="javascript:productliststars_show();"><b>{l s='Show star rating on the category listing page' mod='netreviews'}</b></a></span></label>
				<div class="col-lg-3 col-sm-8 col-xs-12">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="avisverifies_star_productlist" id="avisverifies_star_productlist_on" value="1"{if ($current_starproductlist_checked eq "1")} checked="checked"{/if}>
						<label for="avisverifies_star_productlist_on" class="radioCheck">
							<i class="color_success"></i> {l s='Yes' d='Admin.Global'}
						</label>
						<input type="radio" name="avisverifies_star_productlist" id="avisverifies_star_productlist_off" value="0"{if ($current_starproductlist_checked eq "0" or !$current_starproductlist_checked)} checked="checked"{/if}>
						<label for="avisverifies_star_productlist_off" class="radioCheck">
							<i class="color_danger"></i> {l s='No' d='Admin.Global'}
						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
				<div class="col-lg-6">
					 <p id="show_howtoaddstars"></p>
				</div>
			</div>      
		</div>

			<div class="panel-footer">
			<button type="submit"  name="submit_advanced" id="submit_advanced_design" class="button pointer btn btn-default pull-right">
						<i class="process-icon-save"></i> {l s='Save' mod='netreviews'}
			</button>
			</div>
		</div>

<!-- Design END -->


 <div class="clearfix"> </div>
	<!-- Rich snippet START -->
	  <div class="panel col-lg-12 col-sm-12 col-xs-12">
			<div class="panel-heading"><i class="icon-cogs"></i>  {l s='Google Rich Snippets' mod='netreviews'}</div>
			<div class="panel-body">
	   
			<div class="form-group">
							<label class="col-lg-3 col-sm-4 col-xs-12 control-label"> <b> {l s='Enable Rich Snippets' mod='netreviews'} </b></label>
							<div class="col-lg-9 col-sm-8 col-xs-12">
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="netreviews_snippets_site" id="current_snippets_site_checked_on" value="1"{if ($current_snippets_site_checked eq "1")} checked="checked"{/if}>
									<label for="current_snippets_site_checked_on" class="radioCheck">
										<i class="color_success"></i> {l s='Yes' d='Admin.Global'}
									</label>
									<input type="radio" name="netreviews_snippets_site" id="current_snippets_site_checked_off" value="0"{if ($current_snippets_site_checked eq "0" or !$current_snippets_site_checked)} checked="checked"{/if}>
									<label for="current_snippets_site_checked_off" class="radioCheck">
										<i class="color_danger"></i> {l s='No' d='Admin.Global'}
									</label>
									<a class="slide-button btn"></a>
								</span>
							</div>                            
			</div>  
	
			</div>
			<div class="panel-footer">
			<button type="submit"  name="submit_advanced" id="submit_advanced_rs" class="button pointer btn btn-default pull-right">
						<i class="process-icon-save"></i> {l s='Save' mod='netreviews'}
			</button>
			</div>
		</div>

 <div class="clearfix"> </div>

<!-- Debug START -->
	<div class="panel col-lg-12">
		<div class="panel-heading"><i class="icon-cogs"></i> 
			<a data-toggle="collapse" href="#collapse1" class="label-tooltip" data-toggle="tooltip" data-html="true" title="{l s='Show the advanced options, please contact us to use those tools' mod='netreviews'}" data-original-title="" >{l s='Debug' mod='netreviews'}</a>
		</div>

		<div id="collapse1" class="panel-collapse collapse">
			<div class="panel-body">
				<fieldset class="form-group row">
					<legend class="col-form-legend col-sm-2">{l s='Advanced actions' mod='netreviews'}</legend>
					<div class="col-sm-10">
						<div class="form-group">
						<label class="control-label col-lg-4 col-sm-6 col-xs-12"><b>{l s='Change the position of the stars on product page'}</b></label>
						<div class="col-lg-8 col-sm-6 col-xs-12">
						<div class="radio">
						<label><input type="radio" name="avisverifies_extra_option" id="avisverifies_extra_option_0" value="0" {if ($avisverifies_extra_option eq '0' or !avisverifies_extra_option)} checked="checked" {/if}> Extraright (version < 1.7) </label>
						</div>
						<div class="radio">
						<label><input type="radio" name="avisverifies_extra_option" id="avisverifies_extra_option_1" value="1" {if ($avisverifies_extra_option eq '1')} checked="checked" {/if}> Extraleft (version < 1.7) </label>
						</div>
						<div class="radio">
						<label><input type="radio" name="avisverifies_extra_option" id="avisverifies_extra_option_2" value="2" {if ($avisverifies_extra_option eq '2')} checked="checked" {/if}> DisplayProductButtons (all versions) </label>
						</div>
						<div class="radio">
						<label><input type="radio" name="avisverifies_extra_option" id="avisverifies_extra_option_3" value="3" {if ($avisverifies_extra_option eq '3')} checked="checked" {/if}> no or 
								<a href="javascript:extrahook_show();"><b>personalized hook</b></a><p id="show_extrahook"></p>
							</label>
						</div>
						</div>
						</div>

						{if ($current_snippets_site_checked eq "1")}
							 <div class="form-group">
									<label class="control-label col-lg-4"><b>{l s='Choose the position of the rich snippet integration on product page' mod='netreviews'}</b></label>
									<div class="col-lg-8">
										<div class="radio">
											 <label><input type="radio" name="netreviews_snippets_produit" id="current_snippets_produit_checked_2" value="2" {if ($current_snippets_produit_checked eq '2' or !$current_snippets_produit_checked)} checked="checked" {/if}> Extraright - AggregateRating (Default) </label>
										 </div>
									<div class="radio">
										<label><input type="radio" name="netreviews_snippets_produit" id="current_snippets_produit_checked_3" value="3" {if ($current_snippets_produit_checked eq '3')} checked="checked" {/if}> Extraright - Product </label>
									</div>
									<div class="radio">
										<label><input type="radio" name="netreviews_snippets_produit" id="current_snippets_produit_checked_1" value="1" {if ($current_snippets_produit_checked eq '1')} checked="checked" {/if}> Footer - Product </label>
									</div>
									<div class="radio">
										<label><input type="radio" name="netreviews_snippets_produit" id="current_snippets_produit_checked_4" value="4" {if ($current_snippets_produit_checked eq '4')} checked="checked" {/if}> Tabcontent - AggregateRating </label>
									</div>
									<div class="radio">
										<label><input type="radio" name="netreviews_snippets_produit" id="current_snippets_produit_checked_5" value="5" {if ($current_snippets_produit_checked eq '5')} checked="checked" {/if}> Tabcontent - Product </label>
									</div>
								</div>
							</div>
					
					<!-- Global Rich Snippets/category Rich Snippets -->
							 <div class="form-group">
									<label class="control-label col-lg-4"><b>{l s='Global & category rich snippet' mod='netreviews'}</b></label>
									<div class="col-lg-8">
									<div class="radio">
											 <label><input type="radio" name="netreviews_snippets_liste_produit" id="current_snippets_liste_produit_checked_0" value="0" {if ($current_snippets_liste_produit_checked eq '0' or !current_snippets_liste_produit_checked )} checked="checked" {/if}> no (Default) </label>
									</div>
									<div class="radio">
										<label><input type="radio" name="netreviews_snippets_liste_produit" id="current_snippets_liste_produit_checked_1" value="1" {if ($current_snippets_liste_produit_checked eq '1')} checked="checked" {/if}> Microdata </label>
									</div>
									<div class="radio">
										<label><input type="radio" name="netreviews_snippets_liste_produit" id="current_snippets_liste_produit_checked_2" value="2" {if ($current_snippets_liste_produit_checked eq '2')} checked="checked" {/if}> JSON-LD </label>
									</div>
								</div>
							</div>
						{/if}

						<div class="form-group">
						<label class="control-label col-lg-4 col-sm-7 col-xs-12">
							<span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="">
								<b>{l s='Set the number of products in one review request' mod='netreviews'}</b>
							</span>
						</label>
						<label class="control-label col-lg-8 col-sm-5 col-xs-12">
							<span class="col-lg-1 col-sm-4 col-xs-4">
							<input type="text" class="form-control numbersOnly"  name="avisverifies_nb_products" id="avisverifies_nb_products" value="{if $avisverifies_nb_products}{$avisverifies_nb_products|escape:'htmlall':'UTF-8'} {else}{/if}" />
							</span>
						</label>
						</div> 


						<div class="form-group">
							<label class="col-lg-4 col-sm-7 col-xs-12 control-label">
								<b>{l s='Use order doublecheck' mod='netreviews'} (ActionOrderStatusPostUpdate)</b>
								</label>
							<div class="col-lg-8 col-sm-5 col-xs-12">
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="avisverifies_orders_doublecheck" id="avisverifies_orders_doublecheck_on" value="1"{if ($avisverifies_orders_doublecheck eq "1")} checked="checked"{/if}>
									<label for="avisverifies_orders_doublecheck_on" class="radioCheck">
										<i class="color_success"></i> {l s='Yes' d='Admin.Global'}
									</label>
									<input type="radio" name="avisverifies_orders_doublecheck" id="avisverifies_orders_doublecheck_off" value="0"{if ($avisverifies_orders_doublecheck eq "0" or !$avisverifies_orders_doublecheck)} checked="checked"{/if}>
									<label for="avisverifies_orders_doublecheck_off" class="radioCheck">
										<i class="color_danger"></i> {l s='No' d='Admin.Global'}
									</label>
									<a class="slide-button btn"></a>
								</span>
							</div>
						</div>      

						<div class="form-group">
							<label class="col-lg-4 col-sm-7 col-xs-12 control-label">
								<b>{l s='Purge all orders for this shop' mod='netreviews'}	({$shop_name|escape:'htmlall':'UTF-8'})</b>
							</label>
							<div class="col-lg-8 col-sm-5 col-xs-12">
								<input type="submit"  name="submit_purge" id="submit_purge" value="{l s='Purged' mod='netreviews'}" class="btn btn-danger">
							</div>
						</div>

						<ul class="list-group col-lg-3 col-sm-6 col-xs-12 pull-right">
							<li class="list-group-item">Reviews : {$debug_nb_reviews|escape:'htmlall':'UTF-8'}</li>
							<li class="list-group-item">Average reviews : {$debug_nb_reviews_average|escape:'htmlall':'UTF-8'}</li>
							<li class="list-group-item">Orders pending : {$debug_nb_orders_not_flagged|escape:'htmlall':'UTF-8'}</li>
							<li class="list-group-item">Orders getted : {$debug_nb_orders_flagged|escape:'htmlall':'UTF-8'}</li>
							<li class="list-group-item">Orders all : {$debug_nb_orders_all|escape:'htmlall':'UTF-8'}</li>
						</ul>
					</div> 
				</fieldset>
			</div>     <!-- pannel body end -->

			
			<div class="panel-footer">
				<button type="submit"  name="submit_advanced" id="submit_advanced_debug" class="button pointer btn btn-default pull-right">
				<i class="process-icon-save"></i> {l s='Save' mod='netreviews'}
				</button>
			</div>
		</div> <!-- collapse END -->
			{l s='Module Version' mod='netreviews'}  {$version|escape:'htmlall':'UTF-8'}
	</div> <!-- Debug END -->

</form>

</div> <!-- END avisverifies_module content -->

{if $oldversion}
</div> 
{literal}
	<script language=javascript>
		$("a[href='#collapse1']").click(function(){ $("#collapse1").toggle(); });
	</script>
{/literal}
{/if}

{literal}
	<script language=javascript>
		$(".switch").change(function(){
			if($("#avisverifies_multilingue_on").attr("checked")){
				$(".configuration_labels").removeClass("hidden");
				$("#av_configuration").addClass("hidden");
			}else{
				$(".configuration_labels").removeClass("hidden");
				$("#av_multilanguage_configuration").addClass("hidden");
			}
	 	})

		function cocheToute(){
			$('.cbOrderstates').each(function () {
				$(this).attr('checked', true);
			});
		}
	   function decocheToute(){
			$('.cbOrderstates').each(function () {
				$(this).attr('checked', false);
			});
		}	

		function productliststars_show(){
			$('#show_howtoaddstars').html("Please add <b>{hook h='displayProductListReviews' product=$product}</b> in your <u><b>product-list.tpl</b></u> file");
		}

		function extrahook_show(){
			$('#show_extrahook').html("Please add <b>{hook h='Extra_netreviews'}</b> in your <u><b>product.tpl</b></u> file");
		}

		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();   
		});

		$('.numbersOnly').keyup(function () { 
		    this.value = this.value.replace(/[^0-9\.]/g,'');
		    if( this.value >300){
		    	  this.value =Math.trunc(this.value/10);
		    }
		});
	</script>
{/literal}