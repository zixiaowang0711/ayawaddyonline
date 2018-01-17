<!--
* 2012-2017 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2017 NetReviews SAS
*  @version   Release: $Revision: 7.4.0
*  @license   NetReviews
*  @date      01/09/2017
*  International Registered Trademark & Property of NetReviews SAS
-->
    {if $av_site_rating_avis != 0}

         {if ($rs_choice eq "2")}
            <script type="application/ld+json">
                {
                "@context": "http://schema.org/",
                "@type": "Website",
                "name": "{$name_site|escape:'htmlall':'UTF-8'}",
                "description": "{$category->description|strip_tags|escape:'htmlall':'UTF-8'}",
                    "aggregateRating": { 
                    "@type": "AggregateRating", 
                    "ratingValue": "{$av_site_rating_rate|escape:'htmlall':'UTF-8'}", 
                    "ratingCount": "{$av_site_rating_avis|escape:'htmlall':'UTF-8'}",
                    "worstRating": "1", 
                    "bestRating": "5"
                    } 
                }
            </script>


        {elseif ($rs_choice eq "1")}
                <div id="netreviews_global_website_review" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Webpage" >
                 <span  itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <meta content="{$name_site|escape:'htmlall':'UTF-8'}" itemprop="name" />
                <span class="bandeauServiceClientAvisNoteGros">
                    <meta content="{$av_site_rating_rate|escape:'htmlall':'UTF-8'}" itemprop="ratingValue"/>
                    <meta content="5" itemprop="bestRating" />
                    <meta content="1" itemprop="worstRating" >
                </span>
                    <meta content="{$av_site_rating_avis|escape:'htmlall':'UTF-8'}" itemprop="reviewCount" >
                </span>
                </div>
        {/if}

    {/if}





