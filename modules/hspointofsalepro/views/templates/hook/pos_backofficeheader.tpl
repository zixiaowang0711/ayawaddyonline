{**
* RockPOS - Point of Sale for PrestaShop
*
* @author    Hamsa Technologies
* @copyright Hamsa Technologies
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<script type="text/javascript">
    var salesLink = "{$sales_link|escape:'quotes':'UTF-8'}";
    var title = '{$title|escape:'htmlall':'UTF-8'}';
    var label = '{$label|escape:'htmlall':'UTF-8'}';
    $(document).ready(addPosLink);
    function addPosLink() {
        var newI = document.createElement('i');
        var newLi = document.createElement('li');
        var newDiv = document.createElement('div');
        var newA = document.createElement('a');
        newI.setAttribute("class", "process-icon-cart");
        newDiv.appendChild(document.createTextNode(label));
        newA.setAttribute("class", "toolbar_btn  pointer");
        newA.setAttribute("href", salesLink);
        newA.setAttribute("title", title);
        newA.setAttribute("target", "blank");
        newA.appendChild(newI);
        newA.appendChild(newDiv);
        newLi.appendChild(newA);
        var toolbar = document.getElementById("toolbar-nav");
        toolbar.insertBefore(newLi, toolbar.childNodes[0]);
}
</script>

