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

{if ($snippets_complete == "1")}
<div itemscope itemtype="http://schema.org/Product" id="av_snippets_product_tag">
   <meta itemprop="description" content="{$product_description|escape:'htmlall':'UTF-8'|strip_tags}">
   <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
      <meta itemprop="priceCurrency" content="EUR">
      <meta itemprop="price" content="{$product_price}">
      <link itemprop="availability" href="http://schema.org/InStock" />
   </span>
   <meta itemprop="name" content="{$product_name|escape:'htmlall':'UTF-8'}" />
   <meta itemprop="description" content="{$product_description|escape:'htmlall':'UTF-8'}" />
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

   <!-- netreviews product widget new -->
   {if ($widgetlight eq '2')}
   <div class="netreviewsProductWidgetNew" {if $snippets_active} itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" {/if}>
   <img src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/{l s='Sceau_100_en.png' mod='netreviews'}" class="netreviewsProductWidgetNewLogo"/>
   <div class="netreviewsProductWidgetNewRatingWrapper">
      <div class="netreviewsProductWidgetNewRatingInner" style="width:{$av_rate_percent|escape:'htmlall':'UTF-8'}%"></div>
   </div>
   <div class="netreviewsProductWidgetNewRate">
      <span {if $snippets_active} itemprop="ratingValue" {/if} class="ratingValue">{$av_rate|escape:'htmlall':'UTF-8'}</span>/<span {if $snippets_active} itemprop="bestRating" {/if} class="bestRating">5</span>
   </div>
   {if $snippets_active} 
   <meta itemprop= "worstRating" content= "1">
   {/if}
   <a href="javascript:void(0)" id="AV_button" class="netreviewsProductWidgetNewLink">{l s='See the reviews' mod='netreviews'} 
   (<span {if $snippets_active} itemprop="reviewCount" {/if}>{$av_nb_reviews|escape:'htmlall':'UTF-8'}</span>)
   </a> 
</div>

<!--  netreviews product widget -->
{elseif ($widgetlight eq '3') }
<div class="av_product_award" {if $snippets_active} itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" {/if}>
    <div id="top">
       <div class="ratingWrapper">
          <div class="ratingInner" style="width:{$av_rate_percent|escape:'htmlall':'UTF-8'}%"></div>
       </div>
       <div class="ratingText">
          {if $snippets_active} 
          <meta itemprop= "ratingValue" content= "{$av_rate|escape:'htmlall':'UTF-8'}">
          <meta itemprop= "bestRating" content= "5">
          <meta itemprop= "worstRating" content= "1">
          {/if}
          <span {if $snippets_active} itemprop="reviewCount"{/if} class="reviewCount">
          {$av_nb_reviews|escape:'htmlall':'UTF-8'}
          </span>
          {if $av_nb_reviews > 1}
          {l s='reviews' mod='netreviews'}
          {else}
          {l s='review' mod='netreviews'}
          {/if}
       </div>
    </div>
    <div id="bottom"><a href="javascript:void(0)" id="AV_button">{l s='See the reviews' mod='netreviews'}</a></div>
    <img id="sceau" src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/{l s='Sceau_100_en.png' mod='netreviews'}" />
</div>

<!--  netreviews stars -->
{elseif ($widgetlight eq '1') }
<div class="av_product_award light"  {if $snippets_active} itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" {/if} >
    <a href="javascript:void(0)" id="AV_button">
       <div id="top">
          <div class="netreviewsProductWidgetNewRatingWrapper">
             <div class="netreviewsProductWidgetNewRatingInner" style="width:{$av_rate_percent|escape:'htmlall':'UTF-8'}%"></div>
          </div>
          <div id="slide">
             {if $snippets_active} 
             <meta itemprop="ratingValue" content="{$av_rate|escape:'htmlall':'UTF-8'}">
             <meta itemprop="worstRating" content="1">
             <meta itemprop="bestRating" content="5">
             {/if}
             <span  {if $snippets_active} itemprop="reviewCount" {/if} class="reviewCount">
             {$av_nb_reviews|escape:'htmlall':'UTF-8'}
             </span>
             {if $av_nb_reviews > 1}
             {l s='reviews' mod='netreviews'}
             {else}
             {l s='review' mod='netreviews'}
             {/if}
          </div>
       </div>
    </a>
</div>
{/if}

{if ($snippets_complete == "1")}
</div> <!-- End product --> 
{/if}