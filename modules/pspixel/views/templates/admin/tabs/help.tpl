{*
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*}

<div class="tab-pane panel col-lg-12 right-panel" id="help">
    <h3>
        <i class="fa fa-question-circle"></i> {l s='Help for the module' mod='pspixel'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
    </h3>
    <div class="helpContentParent">
        

    <div class="helpContentRight-sub">
        <div class="tab-pane panel" id="faq">
            <div class="panel-heading"><i class="icon-question"></i> {l s='FAQ' mod='pspixel'}</div>
            {foreach from=$apifaq item=categorie name='faq'}
                <span class="faq-h1">{$categorie->title|escape:'htmlall':'UTF-8'}</span>
                <ul>
                    {foreach from=$categorie->blocks item=QandA}
                        {if !empty($QandA->question)}
                            <li>
                                <span class="faq-h2"><i class="icon-info-circle"></i> {$QandA->question|escape:'htmlall':'UTF-8'}</span>
                                <p class="faq-text hide">
                                    {$QandA->answer|escape:'htmlall':'UTF-8'|replace:"\n":"<br />"}
                                </p>
                            </li>
                        {/if}
                    {/foreach}
                </ul>
                {if !$smarty.foreach.faq.last}<hr/>{/if}
            {/foreach}
        </div>
        <br />
        {l s='You couldn\'t find any answer to your question ?' mod='pspixel'}
        <b><a href="https://addons.prestashop.com/contact-form.php" target="_blank">{l s='Contact us on PrestaShop Addons' mod='pspixel'}</a></b>
    </div>
</div>

</div>

