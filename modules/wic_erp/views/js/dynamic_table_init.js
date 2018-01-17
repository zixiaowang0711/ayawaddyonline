/**
* Module My Easy ERP Web In Color 
* 
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.6
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @package Wic ERP
*  @copyright Copyright &copy; 2014, Web In Color
*  @license   http://www.webincolor.fr
*/

function fnFormatDetails(oTable, pTr) {
	var aData = oTable.fnGetData(pTr);
	var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
	sOut += '<tr><td>Rendering engine:</td><td>' + aData[1] + ' ' + aData[4] + '</td></tr>';
	sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
	sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
	sOut += '</table>';
	return sOut;
}
$(document).ready(function () {
	var nCloneTh = document.createElement('th');
	$('.dataTableHidden thead tr').each(function () {
		this.insertBefore(nCloneTh.cloneNode(true), this.childNodes[0]);
	});

	var nCloneTd = document.createElement('td');
	nCloneTd.innerHTML = '<i class="icon-plus"></i>';
	nCloneTd.className = "center hidden-table-info";
	$('.dataTableHidden tbody tr').each(function () {
		this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
	});

	$('.dataTable').each(function () {
		var $oTable = $(this).dataTable({
			"aoColumnDefs": [{
				"bSortable": false,
				"aTargets": [0,1,2,3,4,5,6]
			}],
			"aaSorting": []
		});

		$(this).children().each(function () {
			$(this).find('.sorting').append('<i class="icon-sort pull-right"></i>');
			$(this).find('.sorting_asc').append('<i class="icon-sort-alpha-asc pull-right"></i>');
			$(this).find('.sorting_desc').append('<i class="icon-sort-alpha-desc pull-right"></i>');
		});

		$(document).on('click', '.dataTableHidden tbody td', function () {
			var $pTr = $(this).parents('tr')[0];
			if ($oTable.fnIsOpen($pTr)) {
				$oTable.fnClose($pTr);
			} else {
				$oTable.fnOpen($pTr, fnFormatDetails($oTable, $pTr), 'details');
			}
		});
	});

	$(document).on('click', '.dataTableHidden tbody td', function () {
		$(this).children().toggleClass("icon-minus");
	});

	$(document).on('click', '.dataTable thead th', function () {

		$(this).parents('thead').each(function () {
			$(this).find('i').removeClass('icon-sort-alpha-asc icon-sort-amount-asc').addClass('icon-sort');
			$(this).find('i').removeClass('icon-sort-alpha-desc icon-sort-amount-desc').addClass('icon-sort');
		});

		$(this).find('i').toggleClass(function() {
			if ($( this ).parent().is(".number")) 
			{
				asc_icon = 'icon-sort-amount-asc';
				desc_icon = 'icon-sort-amount-desc';
			} else {
				asc_icon = 'icon-sort-alpha-asc';
				desc_icon = 'icon-sort-alpha-desc';
			}

			if ($( this ).parent().is(".sorting_asc")) {
				$(this).removeClass(desc_icon);
				return asc_icon;
			} else {
				$(this).removeClass(asc_icon);
				return desc_icon;
			}

		});
	});
});