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


<div class="tab-pane tab_media" id="idTabavisverifies">
{if ($snippets_complete == "1")}
<div itemscope itemtype="http://schema.org/Product">
<meta itemprop="description" content="{$product_description|strip_tags|escape:'htmlall':'UTF-8'}">
   <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
      <meta itemprop="priceCurrency" content="EUR">
      <meta itemprop="price" content="{$product_price}">
      <link itemprop="availability" href="http://schema.org/InStock" />
   </span>
   <meta itemprop="name" content="{$product_name|escape:'htmlall':'UTF-8'}" />
   <meta itemprop="image" content="{$url_image|escape:'htmlall':'UTF-8'}" />
   {if $product_url} 
      <meta itemprop="url" content="{$product_url|escape:'htmlall':'UTF-8'}" />
   {/if}         
   {if $product_id} 
      <meta itemprop="productID" content="{$product_id|escape:'htmlall':'UTF-8'}" />
   {/if}    
   {if $sku} 
       <meta itemprop="sku" content="{$sku|escape:'htmlall':'UTF-8'}" />
   {/if}    
   {if $brand_name} 
       <meta itemprop="brand" content="{$brand_name|escape:'htmlall':'UTF-8'}" />
   {/if}       
   {if $mpn} 
       <meta itemprop="mpn" content="{$mpn|escape:'htmlall':'UTF-8'}" />
   {/if}    
   {if $gtin_ean} 
       <meta itemprop="gtin8" content="{$gtin_ean|escape:'htmlall':'UTF-8'}" />
   {/if}   
   {if $gtin_upc} 
       <meta itemprop="gtin12" content="{$gtin_upc|escape:'htmlall':'UTF-8'}" />
   {/if}  
{/if}

{literal}
	<style type="text/css">
		.groupAvis{
			display: none;
		}
	</style>
{/literal}
	<div id="headerAV">{l s='Product Reviews' mod='netreviews'}</div>
	<div id="under-headerAV"  {if $snippets_active} itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" {/if} style="background: url({$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/{l s='Sceau_100_en.png' mod='netreviews'}) no-repeat #f1f1f1;background-size:45px 45px;background-repeat:no-repeat;">
     {if $snippets_active} 
          <meta itemprop= "ratingValue" content= "{$average_rate|floatval}">
          <meta itemprop= "bestRating" content= "5">
          <meta itemprop= "worstRating" content= "1">
     {/if}
	   <ul id="aggregateRatingAV">
	      <li>
	         <b>
	         {l s='Number of Reviews' mod='netreviews'}
	         </b> : 
                  <span {if $snippets_active} itemprop="reviewCount"{/if} class="reviewCount">
                   {$count_reviews|escape:'htmlall':'UTF-8'}
                  </span>
	      </li>
	      <li>
	         <b>{l s='Average Grade' mod='netreviews'}</b> : {$average_rate|floatval} /5 

           <!-- netreviews product widget new /netreviews stars -->
            {if ($widgetlight eq '2' || $widgetlight eq '1' )}
                     <div class="netreviewsProductWidgetNewRatingWrapper">
                         <div class="netreviewsProductWidgetNewRatingInner" style="width:{$average_rate_percent|escape:'htmlall':'UTF-8'}%"></div>
                      </div>
           <!--  netreviews product widget -->
            {elseif ($widgetlight eq '3') }
                    <div class="ratingWrapper">
                            <div class="ratingInner" style="width:{$average_rate_percent|escape:'htmlall':'UTF-8'}%"></div>
                    </div>
            {/if}

	      </li>
	   </ul>
	   <ul id="certificatAV">
	      <li><a href="{$url_certificat|escape:'htmlall':'UTF-8'}" target="_blank" class="display_certificat_review" >{l s='Show the attestation of Trust' mod='netreviews'}</a></li>
	   </ul>
	   <div class="clear"></div>
	</div>

	<div id="ajax_comment_content">

		{assign var = 'i' value = 1}
		{assign var = 'first' value = true}

		{foreach from=$reviews key=k_review item=review}
			{if $i == 1 && !$first}
				<span class="groupAvis">
			{/if}
			<div class="reviewAV">
				<ul class="reviewInfosAV">
					<li style="text-transform:capitalize">{$review['customer_name']|escape:'htmlall':'UTF-8'}</li>
					<li>&nbsp;{l s='the' mod='netreviews'} {$review['horodate']|escape:'htmlall':'UTF-8'}</li>
					<li class="rateAV">
  		   <!-- netreviews product widget new /netreviews stars -->
            {if ($widgetlight eq '2' || $widgetlight eq '1' )}
             <div class="netreviewsProductWidgetNewRatingWrapper">
                 <div class="netreviewsProductWidgetNewRatingInner" style="width:{$review['rate_percent']|escape:'htmlall':'UTF-8'}%"></div>
              </div>
           <!--  netreviews product widget -->
            {elseif ($widgetlight eq '3') }
                <img src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/etoile{$review['rate']|escape:'htmlall':'UTF-8'}.png" width="80" height="15" alt=""/> 
            {/if}
					{$review['rate']|escape:'htmlall':'UTF-8'}/5
					</li>
				</ul>

				<div class="triangle-border top">{$review['avis']|escape:'htmlall':'UTF-8'}</div>

			{if $review['discussion']}
				{foreach from=$review['discussion'] key=k_discussion item=discussion}

				<div class="triangle-border top answer" {if $k_discussion > 0} review_number={$review['id_product_av']|escape:'htmlall':'UTF-8'} style= "display: none" {/if}>

					<span>&rsaquo; {l s='Comment from' mod='netreviews'}  <b style="text-transform:capitalize;">{$discussion['origine']|escape:'htmlall':'UTF-8'}</b> {l s='the' mod='netreviews'} {$discussion['horodate']|escape:'htmlall':'UTF-8'}</span>
					<p class="answer-bodyAV">{$discussion['commentaire']|escape:'htmlall':'UTF-8'}</p>

				</div>

				{/foreach}

				{if $k_discussion > 0}
					<a href="javascript:switchCommentsVisibility('{$review['id_product_av']|escape:'htmlall':'UTF-8'}')" id="display{$review['id_product_av']|escape:'htmlall':'UTF-8'}" class="display-all-comments" review_number={$review['id_product_av']|escape:'htmlall':'UTF-8'} >{l s='Show exchanges' mod='netreviews'}</a>

					<a href="javascript:switchCommentsVisibility('{$review['id_product_av']|escape:'htmlall':'UTF-8'}')" style="display: none;" id="hide{$review['id_product_av']|escape:'htmlall':'UTF-8'}" class="display-all-comments" review_number={$review['id_product_av']|escape:'htmlall':'UTF-8'} >{l s='Hide exchanges' mod='netreviews'}</a>
					</a>
			  	{/if}
			{/if}

			</div>
			{if $i == $avisverifies_nb_reviews && !$first}
				</span>
				{$i = 1}
			{else}
                {if $i == $avisverifies_nb_reviews && $first}
                    {$first = false}
                    {$i = 1}
                {else}
                    {$i = $i + 1}
                {/if}
			{/if}

		{/foreach}


	</div>
	<img src="{if $is_https}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}modules/netreviews/views/img/pagination-loader.gif" id="av_loader" style="display:none" alt="" />
	{if $count_reviews > $avisverifies_nb_reviews}
		<a href="#" id="av_load_comments" class="av-btn-morecomment" rel="1">{l s='More reviews...' mod='netreviews' }</a>
	{/if}

{literal}
<script data-keepinline="true" type="text/javascript">


function avLoadCommentsPagination() {


    if(typeof jQuery !== 'undefined') {

        $('#av_load_comments').live("click", function(){

            vnom_group = {/literal}{if !empty({$nom_group})}{$nom_group}{else}0{/if}{literal} ;
            vid_shop = {/literal}{if !empty({$id_shop|escape:'htmlall':'UTF-8'})}{$id_shop|escape:'htmlall':'UTF-8'}{else}0{/if}{literal} ;

            counted_reviews = {/literal}{$count_reviews|escape:'htmlall':'UTF-8'}{literal};
            maxpage = Math.ceil(counted_reviews / {/literal}{$avisverifies_nb_reviews|escape:'htmlall':'UTF-8'}{literal}) ;

            console.log(counted_reviews);
            console.log(maxpage);

            if($('.groupAvis:hidden').first().length !== 0){
                $('.groupAvis:hidden').first().css({ visibility: "visible", display: "block" });

                console.log($(this).attr('rel'));
                console.log(maxpage);

                $(this).attr('rel',parseInt($(this).attr('rel')) + 1 );
                if(maxpage == parseInt($(this).attr('rel')) && $('.groupAvis:hidden').length === 0){
                    $(this).hide();
                }

                return false;
            }

            $.ajax({
                url: "{/literal}{if $is_https}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}{literal}modules/netreviews/ajax-load.php",
                type: "POST",
                data: {p : $(this).attr('rel'), id_product : $('input[name="id_product"]').val(), count_reviews : counted_reviews, id_shop : vid_shop, nom_group : vnom_group, avisverifies_nb_reviews : {/literal}{$avisverifies_nb_reviews}{literal}},
                beforeSend: function() {
                    backup_content = $("#ajax_comment_content").html();
                   // $("#ajax_comment_content").slideUp().empty();
                   $('#av_loader').show();
                },
                success: function( html ){
                  //  $("#ajax_comment_content").empty();
                  $('#av_loader').hide();
                    $("#ajax_comment_content").append(html);
                    $('#av_load_comments').attr('rel', parseInt($('#av_load_comments').attr('rel')) + 1);
                    if(maxpage == parseInt($('#av_load_comments').attr('rel')) && $('.groupAvis:hidden').length === 0){
                        $('#av_load_comments').hide();
                    }
                },
                error: function ( jqXHR, textStatus, errorThrown ){
                    alert('something went wrong...');
                    $("#ajax_comment_content").html( backup_content );
                }
            });
            return false;
        });

    }
    else {
        setTimeout(function(){avLoadCommentsPagination();},1000);
    }
        
}

avLoadCommentsPagination();


</script>
{/literal}
    {if ($snippets_complete == "1")}
    </div> <!-- End product --> 
    {/if}
</div>
