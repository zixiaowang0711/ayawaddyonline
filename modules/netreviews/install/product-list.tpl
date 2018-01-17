<!--
* 2012-2017 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2017 NetReviews SAS
*  @version   Release: $Revision: 7.4.2
*  @license   NetReviews
*  @date      16/10/2017
*  International Registered Trademark & Property of NetReviews SAS
*  Please add this code to /themes/ your current theme /product-list.tpl
-->

<!-- START Netreviews Category stars -->
        {if isset($product.av_rate) && !empty($product.av_rate)}
            <a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.av_nb_reviews} avis" class="av_category_stars">
                <div class="{if $product.design} netreviewsProductWidgetNewRatingWrapper {else} ratingWrapper {/if}">
                    <div class="{if $product.design} netreviewsProductWidgetNewRatingInner {else} ratingInner {/if}" style="width:{$product.av_rate_percent|escape:'htmlall':'UTF-8'}%"></div>
                 </div>  
                <span>
                    {$product.av_nb_reviews}
                     {if $product.av_nb_reviews > 1}
                        {$product.l.reviews}
                    {else}
                        {$product.l.review}
                    {/if}
                </span>
            </a>
        {/if}
<!-- END Netreviews Category stars -->