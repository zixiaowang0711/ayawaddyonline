/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

$( document ).ready(function() {
	$('#block_type_selectbox').change(function() {
		if (this.value == blocktype_product)
			$('.block_type_static_html').slideUp(400, function(){
				$('.block_type_product').slideDown();
			});
		else {	//Custom HTML Block
			$('.block_type_product').slideUp(400, function(){
				$('.block_type_static_html').slideDown();
			});
		}			
	});

	$('#product_filter_selectbox').change(function() {
		if (this.value == products_selected)
			$('.filter_selected_products').slideDown().addClass('block_type_product');
		else {
			$('.filter_selected_products').slideUp().removeClass('block_type_product');
		}
		if (this.value == products_category)
			$('.filter_select_category').slideDown().addClass('block_type_product');
		else {
			$('.filter_select_category').slideUp().removeClass('block_type_product');
		}
	});

	$('#layout_selectbox').trigger('change');
	$('#block_type_selectbox').trigger('change');
	$('#product_filter_selectbox').trigger('change');

	aInitTableDnD('zonecolumnblocks', 'zonecolumnblock');
});