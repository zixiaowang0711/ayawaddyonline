{**
 * Prestashop module for Oct8ne
 * @category  Prestashop
 * @category  Module
 * @author    Prestaquality.com
 * @copyright 2016 Prestaquality
 * @license   Commercial license see license.txt
 * Support by mail  : info@prestaquality.com
*}

<script type="text/javascript">
    var oct8ne = document.createElement("script");
    oct8ne.type = "text/javascript";
    oct8ne.src =
            (document.location.protocol == "https:" ? "https://" : "http://")
            + 'static.oct8ne.com/api/v2/oct8ne-api-2.3.js'
            + '?' + (Math.round(new Date().getTime() / 86400000));
    oct8ne.async = true;
    oct8ne.license = "{$oct8neLicense|escape:'htmlall':'UTF-8'}";
    oct8ne.baseUrl = "{$oct8neBaseUrl|escape:'htmlall':'UTF-8'}";
    oct8ne.checkoutUrl = "{$oct8neCheckOutSuccessUrl|escape:'htmlall':'UTF-8'}";
    oct8ne.loginUrl = "{$oct8neLoginUrl|escape:'htmlall':'UTF-8'}";
    oct8ne.checkoutSuccessUrl = "{$oct8neorderConfirmationUrl|escape:'htmlall':'UTF-8'}";
    oct8ne.locale = "{$oct8neLocale|escape:'htmlall':'UTF-8'}";
    oct8ne.currencyCode = "{$oct8neCurrencyCode|escape:'htmlall':'UTF-8'}";

    oct8ne.onProductAddedToCart = function(productId) {

        if (typeof ajaxCart != 'undefined'){

            ajaxCart.refresh();
        }

    };


    {if isset($oct8ne_product_id)}

    oct8ne.currentProduct = {
        id: "{$oct8ne_product_id|escape:'htmlall':'UTF-8'}",
        thumbnail: "{$oct8ne_product_thumb|escape:'htmlall':'UTF-8'}"
    };
    {/if}

    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(oct8ne, s);
</script>
