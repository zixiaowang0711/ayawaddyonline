{**
* RockPOS - Point of Sale for PrestaShop
*
* @author    Hamsa Technologies
* @copyright Hamsa Technologies
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<div class="sales_content">
    <span><img src="{$img_path|escape:'htmlall':'UTF-8'}/redirecting.gif"></span>
    <p id="content_redirecting"></p>
</div>


<script type="text/javascript">
    var contentRedirecting = '{$hs_pos_i18n.you_will_be_redirected_automatically_in_span_3_span_seconds_or_click_a_here_a|escape:'htmlall':'UTF-8'}';
    contentRedirecting = contentRedirecting.replace("[span]", "<span id='counter'>").replace("[/span]", "</span>").replace("[a]", "<a href='{$redirect_link|escape:'quotes':'UTF-8'}'>").replace("[/a]", "</a>");
    document.getElementById("content_redirecting").innerHTML = contentRedirecting;
    function countdown() {
        var i = document.getElementById('counter');
        if (parseInt(i.innerHTML) === 0) {
            location.href = '{$redirect_link|escape:'quotes':'UTF-8'}';
            return;
        }
        i.innerHTML = parseInt(i.innerHTML) - 1;
    }
    setInterval(function () {
        countdown();
    }, 1000);
</script>
