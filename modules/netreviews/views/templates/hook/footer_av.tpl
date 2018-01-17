<!--
* 2012-2017 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2017 NetReviews SAS
*  @version   Release: $Revision: 7.4.1
*  @license   NetReviews
*  @date      26/09/2017
*  International Registered Trademark & Property of NetReviews SAS
-->
 {if ($rs_choice eq "2")}
    <script type="application/ld+json">
          {
          "@context": "http://schema.org/",
          "@type": "Product",
          "name": "{$product_name|escape:'htmlall':'UTF-8'}",
          "description": "{$product_description|strip_tags|escape:'htmlall':'UTF-8'}",
           "offers":
      [
          {
              "@type": "Offer",
              "priceCurrency": "EUR",
              "price": "{$product_price}",
              "availability": "http://schema.org/InStock",
              "name": "{$product_name|escape:'htmlall':'UTF-8'}"
          }
      ],
          "aggregateRating": { 
          "@type": "AggregateRating", 
          "ratingValue": "{$average_rate|escape:'htmlall':'UTF-8'}", 
          "reviewCount": "{$count_reviews|escape:'htmlall':'UTF-8'}",
          "worstRating": "1", 
          "bestRating": "5"
          } 
          }
      </script>
 {else}
<div itemscope itemtype="http://schema.org/Product" id="av_snippets_block">
   <meta itemprop="name" content="{$product_name|escape:'htmlall':'UTF-8'}" />
   <meta itemprop="description" content="{$product_description|strip_tags|escape:'htmlall':'UTF-8'}" />
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
   {if $mpn} 
        <meta itemprop="mpn" content="{$mpn|escape:'htmlall':'UTF-8'}" />
   {/if}    
   {if $gtin_ean} 
        <meta itemprop="gtin8" content="{$gtin_ean|escape:'htmlall':'UTF-8'}" />
   {/if}   
   {if $gtin_upc} 
        <meta itemprop="gtin12" content="{$gtin_upc|escape:'htmlall':'UTF-8'}" />
   {/if}  
   <div id="av_snippets_left">
      <img src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/{l s='Sceau_100_en.png' mod='netreviews'}" width="30"/>
   </div>
   <div id="av_snippets_right">
      <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
         <meta itemprop="priceCurrency" content="EUR">
         <meta itemprop="price" content="{$product_price}">
         <link itemprop="availability" href="http://schema.org/InStock" />
      </span>
      {l s='Evaluation of' mod='netreviews'} <span itemprop="name">{$product_name|escape:'htmlall':'UTF-8'}</span> 
      <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
         <div>
            <span itemprop="ratingValue">{$average_rate|escape:'htmlall':'UTF-8'}</span>/<span itemprop="bestRating">5</span> {l s='out of' mod='netreviews'} <span itemprop="reviewCount">{$count_reviews|escape:'htmlall':'UTF-8'}</span> {l s='reviews' mod='netreviews'} 
            <div class="netreviewsProductWidgetNewRatingWrapper" style="vertical-align: text-bottom;">
               <div class="netreviewsProductWidgetNewRatingInner" style="width:{$average_rate_percent|escape:'htmlall':'UTF-8'}%"></div>
            </div>
         </div>
      </div>
   </div>
</div>
{/if}