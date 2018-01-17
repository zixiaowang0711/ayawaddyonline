{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{block name='product_attachments'}
  <section class="product-attachments">
    <div class="row">
    {foreach from=$product.attachments item=attachment}
      <div class="attachment col-12 col-lg-6 mb-3">
        <div class="light-box-bg">
          <h5><a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">{$attachment.name}</a></h5>
          <p>{$attachment.description}</p>
          <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
            <i class="material-icons">&#xE2C4;</i> {l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
          </a>
        </div>
      </div>
    {/foreach}
    </div>
  </section>
{/block}
