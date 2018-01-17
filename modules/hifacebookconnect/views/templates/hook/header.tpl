{**
* 2013 - 2017 HiPresta
*
* MODULE Facebook Connect
*
* @version   1.1.0
* @author    HiPresta <suren.mikaelyan@gmail.com>
* @link      http://www.hipresta.com
* @copyright HiPresta 2017
* @license   PrestaShop Addons license limitation
*
*}

<script type="text/javascript">
    {literal}
        var secure_key = '{/literal}{$secure_key|escape:'htmlall':'UTF-8'}{literal}';
        var redirect = '{/literal}{$login_page|escape:'htmlall':'UTF-8'}{literal}';
        var authentication_page = '{/literal}{$authentication_page|escape:'htmlall':'UTF-8'}{literal}';
        var hi_sc_fb_front_controller_dir = '{/literal}{$hi_sc_fb_front_controller_dir nofilter}{literal}';
        var hi_sc_fb_module_dir = '{/literal}{$hi_sc_fb_module_dir|escape:'htmlall':'UTF-8'}{literal}';
        var hi_sc_fb_base_url = '{/literal}{$hi_sc_fb_base_url|escape:'htmlall':'UTF-8'}{literal}';
        var facebook_id = '{/literal}{$facebook_id|escape:'htmlall':'UTF-8'}{literal}';
        var sc_fb_loader = '{/literal}{$sc_fb_loader|escape:'htmlall':'UTF-8'}{literal}';
    {/literal}
</script>

{if $hi_sc_fb_on && $sdk_on}
    <script type="text/javascript">
    {literal}
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&version=v2.10&appId={/literal}{$facebook_id|escape:'htmlall':'UTF-8'}{literal}";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    {/literal}
</script>
{/if}