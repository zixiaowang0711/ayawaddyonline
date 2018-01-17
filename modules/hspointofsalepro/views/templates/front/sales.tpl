{**
* RockPOS - Point of Sale for PrestaShop
*
* @author    Hamsa Technologies
* @copyright Hamsa Technologies
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

 <!DOCTYPE html>
 <html>
    <head>
        <title>{$title|escape:'htmlall':'UTF-8'}</title>
        <link rel="stylesheet" href="{$css_path|escape:'htmlall':'UTF-8'}apps/sales.css" type="text/css" media="all" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <link rel="stylesheet" href="{$css_path|escape:'htmlall':'UTF-8'}font-awesome.min.css" type="text/css" media="all" />
        <script src="{$js_path|escape:'htmlall':'UTF-8'}jquery.min.js"></script>
        <script src="{$js_path|escape:'htmlall':'UTF-8'}pdfmake/pdfmake.min.js"></script>
        <script src="{$js_path|escape:'htmlall':'UTF-8'}pdfmake/vfs_fonts.js"></script>
        <script src="{$js_path|escape:'htmlall':'UTF-8'}jquery.scannerdetection.js"></script>
        {if $ready_to_go_mixpanel}
            <script src="{$js_path|escape:'htmlall':'UTF-8'}mixpanel.js"></script>
        {/if}
    </head>
    <body>
        <div id="content"></div>
    </body>
    <script src="{$js_path|escape:'htmlall':'UTF-8'}apps/sales.js?v={$file_version|escape:'htmlall':'UTF-8'}"></script>
    <script src="{$js_path|escape:'htmlall':'UTF-8'}rsvp-3.1.0.min.js"></script>
    <script src="{$js_path|escape:'htmlall':'UTF-8'}sha-256.min.js"></script>
    <script src="{$js_path|escape:'htmlall':'UTF-8'}qz-tray.js"></script>
 </html>
