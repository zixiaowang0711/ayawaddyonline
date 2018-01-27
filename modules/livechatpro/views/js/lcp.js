/*
* ProQuality (c) All rights reserved.
*
* DISCLAIMER
*
* Do not edit, modify or copy this file.
* If you wish to customize it, contact us at addons4prestashop@gmail.com.
*
* @author    Andrei Cimpean (ProQuality) <addons4prestashop@gmail.com>
* @copyright 2015-2016 ProQuality
* @license   Do not edit, modify or copy this file
*/

$(document).ready(function()
{
	
/* DATATABLES //////////////////////////////////////////////////////////////////////////////////////////////////// */

/*! DataTables 1.10.9 */
!function(t,e,n){var a=function(a){function r(t){var e,n,o={};a.each(t,function(a){(e=a.match(/^([^A-Z]+?)([A-Z])/))&&-1!=="a aa ai ao as b fn i m o s ".indexOf(e[1]+" ")&&(n=a.replace(e[0],e[2].toLowerCase()),o[n]=a,"o"===e[1]&&r(t[a]))}),t._hungarianMap=o}function o(t,e,i){t._hungarianMap||r(t);var s;a.each(e,function(r){s=t._hungarianMap[r],s===n||!i&&e[s]!==n||("o"===s.charAt(0)?(e[s]||(e[s]={}),a.extend(!0,e[s],e[r]),o(t[s],e[s],i)):e[s]=e[r])})}function i(t){var e=Xt.defaults.oLanguage,n=t.sZeroRecords;!t.sEmptyTable&&n&&"No data available in table"===e.sEmptyTable&&jt(t,t,"sZeroRecords","sEmptyTable"),!t.sLoadingRecords&&n&&"Loading..."===e.sLoadingRecords&&jt(t,t,"sZeroRecords","sLoadingRecords"),t.sInfoThousands&&(t.sThousands=t.sInfoThousands),(t=t.sDecimal)&&Bt(t)}function s(t){if(de(t,"ordering","bSort"),de(t,"orderMulti","bSortMulti"),de(t,"orderClasses","bSortClasses"),de(t,"orderCellsTop","bSortCellsTop"),de(t,"order","aaSorting"),de(t,"orderFixed","aaSortingFixed"),de(t,"paging","bPaginate"),de(t,"pagingType","sPaginationType"),de(t,"pageLength","iDisplayLength"),de(t,"searching","bFilter"),"boolean"==typeof t.sScrollX&&(t.sScrollX=t.sScrollX?"100%":""),t=t.aoSearchCols)for(var e=0,n=t.length;n>e;e++)t[e]&&o(Xt.models.oSearch,t[e])}function l(t){de(t,"orderable","bSortable"),de(t,"orderData","aDataSort"),de(t,"orderSequence","asSorting"),de(t,"orderDataType","sortDataType");var e=t.aDataSort;e&&!a.isArray(e)&&(t.aDataSort=[e])}function u(t){if(!Xt.__browser){var e={};Xt.__browser=e;var n=a("<div/>").css({position:"fixed",top:0,left:0,height:1,width:1,overflow:"hidden"}).append(a("<div/>").css({position:"absolute",top:1,left:1,width:100,overflow:"scroll"}).append(a("<div/>").css({width:"100%",height:10}))).appendTo("body"),r=n.children(),o=r.children();e.barWidth=r[0].offsetWidth-r[0].clientWidth,e.bScrollOversize=100===o[0].offsetWidth&&100!==r[0].clientWidth,e.bScrollbarLeft=1!==Math.round(o.offset().left),e.bBounding=n[0].getBoundingClientRect().width?!0:!1,n.remove()}a.extend(t.oBrowser,Xt.__browser),t.oScroll.iBarWidth=Xt.__browser.barWidth}function c(t,e,a,r,o,i){var s,l=!1;for(a!==n&&(s=a,l=!0);r!==o;)t.hasOwnProperty(r)&&(s=l?e(s,t[r],r,t):t[r],l=!0,r+=i);return s}function f(t,n){var r=Xt.defaults.column,o=t.aoColumns.length,r=a.extend({},Xt.models.oColumn,r,{nTh:n?n:e.createElement("th"),sTitle:r.sTitle?r.sTitle:n?n.innerHTML:"",aDataSort:r.aDataSort?r.aDataSort:[o],mData:r.mData?r.mData:o,idx:o});t.aoColumns.push(r),r=t.aoPreSearchCols,r[o]=a.extend({},Xt.models.oSearch,r[o]),d(t,o,a(n).data())}function d(t,e,r){var e=t.aoColumns[e],i=t.oClasses,s=a(e.nTh);if(!e.sWidthOrig){e.sWidthOrig=s.attr("width")||null;var u=(s.attr("style")||"").match(/width:\s*(\d+[pxem%]+)/);u&&(e.sWidthOrig=u[1])}r!==n&&null!==r&&(l(r),o(Xt.defaults.column,r),r.mDataProp!==n&&!r.mData&&(r.mData=r.mDataProp),r.sType&&(e._sManualType=r.sType),r.className&&!r.sClass&&(r.sClass=r.className),a.extend(e,r),jt(e,r,"sWidth","sWidthOrig"),r.iDataSort!==n&&(e.aDataSort=[r.iDataSort]),jt(e,r,"aDataSort"));var c=e.mData,f=w(c),d=e.mRender?w(e.mRender):null,r=function(t){return"string"==typeof t&&-1!==t.indexOf("@")};e._bAttrSrc=a.isPlainObject(c)&&(r(c.sort)||r(c.type)||r(c.filter)),e.fnGetData=function(t,e,a){var r=f(t,e,n,a);return d&&e?d(r,e,t,a):r},e.fnSetData=function(t,e,n){return x(c)(t,e,n)},"number"!=typeof c&&(t._rowReadObject=!0),t.oFeatures.bSort||(e.bSortable=!1,s.addClass(i.sSortableNone)),t=-1!==a.inArray("asc",e.asSorting),r=-1!==a.inArray("desc",e.asSorting),e.bSortable&&(t||r)?t&&!r?(e.sSortingClass=i.sSortableAsc,e.sSortingClassJUI=i.sSortJUIAscAllowed):!t&&r?(e.sSortingClass=i.sSortableDesc,e.sSortingClassJUI=i.sSortJUIDescAllowed):(e.sSortingClass=i.sSortable,e.sSortingClassJUI=i.sSortJUI):(e.sSortingClass=i.sSortableNone,e.sSortingClassJUI="")}function h(t){if(!1!==t.oFeatures.bAutoWidth){var e=t.aoColumns;bt(t);for(var n=0,a=e.length;a>n;n++)e[n].nTh.style.width=e[n].sWidth}e=t.oScroll,(""!==e.sY||""!==e.sX)&&pt(t),Ot(t,null,"column-sizing",[t])}function p(t,e){var n=S(t,"bVisible");return"number"==typeof n[e]?n[e]:null}function g(t,e){var n=S(t,"bVisible"),n=a.inArray(e,n);return-1!==n?n:null}function b(t){return S(t,"bVisible").length}function S(t,e){var n=[];return a.map(t.aoColumns,function(t,a){t[e]&&n.push(a)}),n}function v(t){var e,a,r,o,i,s,l,u,c,f=t.aoColumns,d=t.aoData,h=Xt.ext.type.detect;for(e=0,a=f.length;a>e;e++)if(l=f[e],c=[],!l.sType&&l._sManualType)l.sType=l._sManualType;else if(!l.sType){for(r=0,o=h.length;o>r;r++){for(i=0,s=d.length;s>i&&(c[i]===n&&(c[i]=_(t,i,e,"type")),u=h[r](c[i],t),u||r===h.length-1)&&"html"!==u;i++);if(u){l.sType=u;break}}l.sType||(l.sType="string")}}function m(t,e,r,o){var i,s,l,u,c,d,h=t.aoColumns;if(e)for(i=e.length-1;i>=0;i--){d=e[i];var p=d.targets!==n?d.targets:d.aTargets;for(a.isArray(p)||(p=[p]),s=0,l=p.length;l>s;s++)if("number"==typeof p[s]&&0<=p[s]){for(;h.length<=p[s];)f(t);o(p[s],d)}else if("number"==typeof p[s]&&0>p[s])o(h.length+p[s],d);else if("string"==typeof p[s])for(u=0,c=h.length;c>u;u++)("_all"==p[s]||a(h[u].nTh).hasClass(p[s]))&&o(u,d)}if(r)for(i=0,t=r.length;t>i;i++)o(i,r[i])}function D(t,e,r,o){var i=t.aoData.length,s=a.extend(!0,{},Xt.models.oRow,{src:r?"dom":"data",idx:i});s._aData=e,t.aoData.push(s);for(var l=t.aoColumns,u=0,c=l.length;c>u;u++)l[u].sType=null;return t.aiDisplayMaster.push(i),e=t.rowIdFn(e),e!==n&&(t.aIds[e]=s),(r||!t.oFeatures.bDeferRender)&&P(t,i,r,o),i}function y(t,e){var n;return e instanceof a||(e=a(e)),e.map(function(e,a){return n=R(t,a),D(t,n.data,a,n.cells)})}function _(t,e,a,r){var o=t.iDraw,i=t.aoColumns[a],s=t.aoData[e]._aData,l=i.sDefaultContent,a=i.fnGetData(s,r,{settings:t,row:e,col:a});if(a===n)return t.iDrawError!=o&&null===l&&(Pt(t,0,"Requested unknown parameter "+("function"==typeof i.mData?"{function}":"'"+i.mData+"'")+" for row "+e,4),t.iDrawError=o),l;if(a!==s&&null!==a||null===l){if("function"==typeof a)return a.call(s)}else a=l;return null===a&&"display"==r?"":a}function T(t,e,n,a){t.aoColumns[n].fnSetData(t.aoData[e]._aData,a,{settings:t,row:e,col:n})}function C(t){return a.map(t.match(/(\\.|[^\.])+/g)||[""],function(t){return t.replace(/\\./g,".")})}function w(t){if(a.isPlainObject(t)){var e={};return a.each(t,function(t,n){n&&(e[t]=w(n))}),function(t,a,r,o){var i=e[a]||e._;return i!==n?i(t,a,r,o):t}}if(null===t)return function(t){return t};if("function"==typeof t)return function(e,n,a,r){return t(e,n,a,r)};if("string"==typeof t&&(-1!==t.indexOf(".")||-1!==t.indexOf("[")||-1!==t.indexOf("("))){var r=function(t,e,o){var i,s;if(""!==o){s=C(o);for(var l=0,u=s.length;u>l;l++){if(o=s[l].match(he),i=s[l].match(pe),o){if(s[l]=s[l].replace(he,""),""!==s[l]&&(t=t[s[l]]),i=[],s.splice(0,l+1),s=s.join("."),a.isArray(t))for(l=0,u=t.length;u>l;l++)i.push(r(t[l],e,s));t=o[0].substring(1,o[0].length-1),t=""===t?i:i.join(t);break}if(i)s[l]=s[l].replace(pe,""),t=t[s[l]]();else{if(null===t||t[s[l]]===n)return n;t=t[s[l]]}}}return t};return function(e,n){return r(e,n,t)}}return function(e){return e[t]}}function x(t){if(a.isPlainObject(t))return x(t._);if(null===t)return function(){};if("function"==typeof t)return function(e,n,a){t(e,"set",n,a)};if("string"==typeof t&&(-1!==t.indexOf(".")||-1!==t.indexOf("[")||-1!==t.indexOf("("))){var e=function(t,r,o){var i,o=C(o);i=o[o.length-1];for(var s,l,u=0,c=o.length-1;c>u;u++){if(s=o[u].match(he),l=o[u].match(pe),s){if(o[u]=o[u].replace(he,""),t[o[u]]=[],i=o.slice(),i.splice(0,u+1),s=i.join("."),a.isArray(r))for(l=0,c=r.length;c>l;l++)i={},e(i,r[l],s),t[o[u]].push(i);else t[o[u]]=r;return}l&&(o[u]=o[u].replace(pe,""),t=t[o[u]](r)),(null===t[o[u]]||t[o[u]]===n)&&(t[o[u]]={}),t=t[o[u]]}i.match(pe)?t[i.replace(pe,"")](r):t[i.replace(he,"")]=r};return function(n,a){return e(n,a,t)}}return function(e,n){e[t]=n}}function I(t){return se(t.aoData,"_aData")}function A(t){t.aoData.length=0,t.aiDisplayMaster.length=0,t.aiDisplay.length=0,t.aIds={}}function F(t,e,a){for(var r=-1,o=0,i=t.length;i>o;o++)t[o]==e?r=o:t[o]>e&&t[o]--;-1!=r&&a===n&&t.splice(r,1)}function L(t,e,a,r){var o,i=t.aoData[e],s=function(n,a){for(;n.childNodes.length;)n.removeChild(n.firstChild);n.innerHTML=_(t,e,a,"display")};if("dom"!==a&&(a&&"auto"!==a||"dom"!==i.src)){var l=i.anCells;if(l)if(r!==n)s(l[r],r);else for(a=0,o=l.length;o>a;a++)s(l[a],a)}else i._aData=R(t,i,r,r===n?n:i._aData).data;if(i._aSortData=null,i._aFilterData=null,s=t.aoColumns,r!==n)s[r].sType=null;else{for(a=0,o=s.length;o>a;a++)s[a].sType=null;j(t,i)}}function R(t,e,r,o){var i,s,l,u=[],c=e.firstChild,f=0,d=t.aoColumns,h=t._rowReadObject,o=o!==n?o:h?{}:[],p=function(t,e){if("string"==typeof t){var n=t.indexOf("@");-1!==n&&(n=t.substring(n+1),x(t)(o,e.getAttribute(n)))}},g=function(t){(r===n||r===f)&&(s=d[f],l=a.trim(t.innerHTML),s&&s._bAttrSrc?(x(s.mData._)(o,l),p(s.mData.sort,t),p(s.mData.type,t),p(s.mData.filter,t)):h?(s._setter||(s._setter=x(s.mData)),s._setter(o,l)):o[f]=l),f++};if(c)for(;c;)i=c.nodeName.toUpperCase(),("TD"==i||"TH"==i)&&(g(c),u.push(c)),c=c.nextSibling;else{u=e.anCells,i=0;for(var b=u.length;b>i;i++)g(u[i])}return(e=c?e:e.nTr)&&(e=e.getAttribute("id"))&&x(t.rowId)(o,e),{data:o,cells:u}}function P(t,n,a,r){var o,i,s,l,u,c=t.aoData[n],f=c._aData,d=[];if(null===c.nTr){for(o=a||e.createElement("tr"),c.nTr=o,c.anCells=d,o._DT_RowIndex=n,j(t,c),l=0,u=t.aoColumns.length;u>l;l++)s=t.aoColumns[l],i=a?r[l]:e.createElement(s.sCellType),d.push(i),(!a||s.mRender||s.mData!==l)&&(i.innerHTML=_(t,n,l,"display")),s.sClass&&(i.className+=" "+s.sClass),s.bVisible&&!a?o.appendChild(i):!s.bVisible&&a&&i.parentNode.removeChild(i),s.fnCreatedCell&&s.fnCreatedCell.call(t.oInstance,i,_(t,n,l),f,n,l);Ot(t,"aoRowCreatedCallback",null,[o,f,n])}c.nTr.setAttribute("role","row")}function j(t,e){var n=e.nTr,r=e._aData;if(n){var o=t.rowIdFn(r);o&&(n.id=o),r.DT_RowClass&&(o=r.DT_RowClass.split(" "),e.__rowc=e.__rowc?fe(e.__rowc.concat(o)):o,a(n).removeClass(e.__rowc.join(" ")).addClass(r.DT_RowClass)),r.DT_RowAttr&&a(n).attr(r.DT_RowAttr),r.DT_RowData&&a(n).data(r.DT_RowData)}}function H(t){var e,n,r,o,i,s=t.nTHead,l=t.nTFoot,u=0===a("th, td",s).length,c=t.oClasses,f=t.aoColumns;for(u&&(o=a("<tr/>").appendTo(s)),e=0,n=f.length;n>e;e++)i=f[e],r=a(i.nTh).addClass(i.sClass),u&&r.appendTo(o),t.oFeatures.bSort&&(r.addClass(i.sSortingClass),!1!==i.bSortable&&(r.attr("tabindex",t.iTabIndex).attr("aria-controls",t.sTableId),xt(t,i.nTh,e))),i.sTitle!=r[0].innerHTML&&r.html(i.sTitle),Wt(t,"header")(t,r,i,c);if(u&&W(t.aoHeader,s),a(s).find(">tr").attr("role","row"),a(s).find(">tr>th, >tr>td").addClass(c.sHeaderTH),a(l).find(">tr>th, >tr>td").addClass(c.sFooterTH),null!==l)for(t=t.aoFooter[0],e=0,n=t.length;n>e;e++)i=f[e],i.nTf=t[e].cell,i.sClass&&a(i.nTf).addClass(i.sClass)}function N(t,e,r){var o,i,s,l,u=[],c=[],f=t.aoColumns.length;if(e){for(r===n&&(r=!1),o=0,i=e.length;i>o;o++){for(u[o]=e[o].slice(),u[o].nTr=e[o].nTr,s=f-1;s>=0;s--)!t.aoColumns[s].bVisible&&!r&&u[o].splice(s,1);c.push([])}for(o=0,i=u.length;i>o;o++){if(t=u[o].nTr)for(;s=t.firstChild;)t.removeChild(s);for(s=0,e=u[o].length;e>s;s++)if(l=f=1,c[o][s]===n){for(t.appendChild(u[o][s].cell),c[o][s]=1;u[o+f]!==n&&u[o][s].cell==u[o+f][s].cell;)c[o+f][s]=1,f++;for(;u[o][s+l]!==n&&u[o][s].cell==u[o][s+l].cell;){for(r=0;f>r;r++)c[o+r][s+l]=1;l++}a(u[o][s].cell).attr("rowspan",f).attr("colspan",l)}}}}function k(t){var e=Ot(t,"aoPreDrawCallback","preDraw",[t]);if(-1!==a.inArray(!1,e))dt(t,!1);else{var e=[],r=0,o=t.asStripeClasses,i=o.length,s=t.oLanguage,l=t.iInitDisplayStart,u="ssp"==Ut(t),c=t.aiDisplay;t.bDrawing=!0,l!==n&&-1!==l&&(t._iDisplayStart=u?l:l>=t.fnRecordsDisplay()?0:l,t.iInitDisplayStart=-1);var l=t._iDisplayStart,f=t.fnDisplayEnd();if(t.bDeferLoading)t.bDeferLoading=!1,t.iDraw++,dt(t,!1);else if(u){if(!t.bDestroying&&!B(t))return}else t.iDraw++;if(0!==c.length)for(s=u?t.aoData.length:f,u=u?0:l;s>u;u++){var d=c[u],h=t.aoData[d];if(null===h.nTr&&P(t,d),d=h.nTr,0!==i){var p=o[r%i];h._sRowStripe!=p&&(a(d).removeClass(h._sRowStripe).addClass(p),h._sRowStripe=p)}Ot(t,"aoRowCallback",null,[d,h._aData,r,u]),e.push(d),r++}else r=s.sZeroRecords,1==t.iDraw&&"ajax"==Ut(t)?r=s.sLoadingRecords:s.sEmptyTable&&0===t.fnRecordsTotal()&&(r=s.sEmptyTable),e[0]=a("<tr/>",{"class":i?o[0]:""}).append(a("<td />",{valign:"top",colSpan:b(t),"class":t.oClasses.sRowEmpty}).html(r))[0];Ot(t,"aoHeaderCallback","header",[a(t.nTHead).children("tr")[0],I(t),l,f,c]),Ot(t,"aoFooterCallback","footer",[a(t.nTFoot).children("tr")[0],I(t),l,f,c]),o=a(t.nTBody),o.children().detach(),o.append(a(e)),Ot(t,"aoDrawCallback","draw",[t]),t.bSorted=!1,t.bFiltered=!1,t.bDrawing=!1}}function O(t,e){var n=t.oFeatures,a=n.bFilter;n.bSort&&Tt(t),a?G(t,t.oPreviousSearch):t.aiDisplay=t.aiDisplayMaster.slice(),!0!==e&&(t._iDisplayStart=0),t._drawHold=e,k(t),t._drawHold=!1}function M(t){var e=t.oClasses,n=a(t.nTable),n=a("<div/>").insertBefore(n),r=t.oFeatures,o=a("<div/>",{id:t.sTableId+"_wrapper","class":e.sWrapper+(t.nTFoot?"":" "+e.sNoFooter)});t.nHolding=n[0],t.nTableWrapper=o[0],t.nTableReinsertBefore=t.nTable.nextSibling;for(var i,s,l,u,c,f,d=t.sDom.split(""),h=0;h<d.length;h++){if(i=null,s=d[h],"<"==s){if(l=a("<div/>")[0],u=d[h+1],"'"==u||'"'==u){for(c="",f=2;d[h+f]!=u;)c+=d[h+f],f++;"H"==c?c=e.sJUIHeader:"F"==c&&(c=e.sJUIFooter),-1!=c.indexOf(".")?(u=c.split("."),l.id=u[0].substr(1,u[0].length-1),l.className=u[1]):"#"==c.charAt(0)?l.id=c.substr(1,c.length-1):l.className=c,h+=f}o.append(l),o=a(l)}else if(">"==s)o=o.parent();else if("l"==s&&r.bPaginate&&r.bLengthChange)i=lt(t);else if("f"==s&&r.bFilter)i=q(t);else if("r"==s&&r.bProcessing)i=ft(t);else if("t"==s)i=ht(t);else if("i"==s&&r.bInfo)i=nt(t);else if("p"==s&&r.bPaginate)i=ut(t);else if(0!==Xt.ext.feature.length)for(l=Xt.ext.feature,f=0,u=l.length;u>f;f++)if(s==l[f].cFeature){i=l[f].fnInit(t);break}i&&(l=t.aanFeatures,l[s]||(l[s]=[]),l[s].push(i),o.append(i))}n.replaceWith(o),t.nHolding=null}function W(t,e){var n,r,o,i,s,l,u,c,f,d,h=a(e).children("tr");for(t.splice(0,t.length),o=0,l=h.length;l>o;o++)t.push([]);for(o=0,l=h.length;l>o;o++)for(n=h[o],r=n.firstChild;r;){if("TD"==r.nodeName.toUpperCase()||"TH"==r.nodeName.toUpperCase()){for(c=1*r.getAttribute("colspan"),f=1*r.getAttribute("rowspan"),c=c&&0!==c&&1!==c?c:1,f=f&&0!==f&&1!==f?f:1,i=0,s=t[o];s[i];)i++;for(u=i,d=1===c?!0:!1,s=0;c>s;s++)for(i=0;f>i;i++)t[o+i][u+s]={cell:r,unique:d},t[o+i].nTr=n}r=r.nextSibling}}function U(t,e,n){var a=[];n||(n=t.aoHeader,e&&(n=[],W(n,e)));for(var e=0,r=n.length;r>e;e++)for(var o=0,i=n[e].length;i>o;o++)!n[e][o].unique||a[o]&&t.bSortCellsTop||(a[o]=n[e][o].cell);return a}function E(t,e,n){if(Ot(t,"aoServerParams","serverParams",[e]),e&&a.isArray(e)){var r={},o=/(.*?)\[\]$/;a.each(e,function(t,e){var n=e.name.match(o);n?(n=n[0],r[n]||(r[n]=[]),r[n].push(e.value)):r[e.name]=e.value}),e=r}var i,s=t.ajax,l=t.oInstance,u=function(e){Ot(t,null,"xhr",[t,e,t.jqXHR]),n(e)};if(a.isPlainObject(s)&&s.data){i=s.data;var c=a.isFunction(i)?i(e,t):i,e=a.isFunction(i)&&c?c:a.extend(!0,e,c);delete s.data}c={data:e,success:function(e){var n=e.error||e.sError;n&&Pt(t,0,n),t.json=e,u(e)},dataType:"json",cache:!1,type:t.sServerMethod,error:function(e,n){var r=Ot(t,null,"xhr",[t,null,t.jqXHR]);-1===a.inArray(!0,r)&&("parsererror"==n?Pt(t,0,"Invalid JSON response",1):4===e.readyState&&Pt(t,0,"Ajax error",7)),dt(t,!1)}},t.oAjaxData=e,Ot(t,null,"preXhr",[t,e]),t.fnServerData?t.fnServerData.call(l,t.sAjaxSource,a.map(e,function(t,e){return{name:e,value:t}}),u,t):t.sAjaxSource||"string"==typeof s?t.jqXHR=a.ajax(a.extend(c,{url:s||t.sAjaxSource})):a.isFunction(s)?t.jqXHR=s.call(l,e,u,t):(t.jqXHR=a.ajax(a.extend(c,s)),s.data=i)}function B(t){return t.bAjaxDataGet?(t.iDraw++,dt(t,!0),E(t,J(t),function(e){X(t,e)}),!1):!0}function J(t){var e,n,r,o,i=t.aoColumns,s=i.length,l=t.oFeatures,u=t.oPreviousSearch,c=t.aoPreSearchCols,f=[],d=_t(t);e=t._iDisplayStart,n=!1!==l.bPaginate?t._iDisplayLength:-1;var h=function(t,e){f.push({name:t,value:e})};h("sEcho",t.iDraw),h("iColumns",s),h("sColumns",se(i,"sName").join(",")),h("iDisplayStart",e),h("iDisplayLength",n);var p={draw:t.iDraw,columns:[],order:[],start:e,length:n,search:{value:u.sSearch,regex:u.bRegex}};for(e=0;s>e;e++)r=i[e],o=c[e],n="function"==typeof r.mData?"function":r.mData,p.columns.push({data:n,name:r.sName,searchable:r.bSearchable,orderable:r.bSortable,search:{value:o.sSearch,regex:o.bRegex}}),h("mDataProp_"+e,n),l.bFilter&&(h("sSearch_"+e,o.sSearch),h("bRegex_"+e,o.bRegex),h("bSearchable_"+e,r.bSearchable)),l.bSort&&h("bSortable_"+e,r.bSortable);return l.bFilter&&(h("sSearch",u.sSearch),h("bRegex",u.bRegex)),l.bSort&&(a.each(d,function(t,e){p.order.push({column:e.col,dir:e.dir}),h("iSortCol_"+t,e.col),h("sSortDir_"+t,e.dir)}),h("iSortingCols",d.length)),i=Xt.ext.legacy.ajax,null===i?t.sAjaxSource?f:p:i?f:p}function X(t,e){var a=V(t,e),r=e.sEcho!==n?e.sEcho:e.draw,o=e.iTotalRecords!==n?e.iTotalRecords:e.recordsTotal,i=e.iTotalDisplayRecords!==n?e.iTotalDisplayRecords:e.recordsFiltered;if(r){if(1*r<t.iDraw)return;t.iDraw=1*r}for(A(t),t._iRecordsTotal=parseInt(o,10),t._iRecordsDisplay=parseInt(i,10),r=0,o=a.length;o>r;r++)D(t,a[r]);t.aiDisplay=t.aiDisplayMaster.slice(),t.bAjaxDataGet=!1,k(t),t._bInitComplete||it(t,e),t.bAjaxDataGet=!0,dt(t,!1)}function V(t,e){var r=a.isPlainObject(t.ajax)&&t.ajax.dataSrc!==n?t.ajax.dataSrc:t.sAjaxDataProp;return"data"===r?e.aaData||e[r]:""!==r?w(r)(e):e}function q(t){var n=t.oClasses,r=t.sTableId,o=t.oLanguage,i=t.oPreviousSearch,s=t.aanFeatures,l='<input type="search" class="'+n.sFilterInput+'"/>',u=o.sSearch,u=u.match(/_INPUT_/)?u.replace("_INPUT_",l):u+l,n=a("<div/>",{id:s.f?null:r+"_filter","class":n.sFilter}).append(a("<label/>").append(u)),s=function(){var e=this.value?this.value:"";e!=i.sSearch&&(G(t,{sSearch:e,bRegex:i.bRegex,bSmart:i.bSmart,bCaseInsensitive:i.bCaseInsensitive}),t._iDisplayStart=0,k(t))},l=null!==t.searchDelay?t.searchDelay:"ssp"===Ut(t)?400:0,c=a("input",n).val(i.sSearch).attr("placeholder",o.sSearchPlaceholder).bind("keyup.DT search.DT input.DT paste.DT cut.DT",l?St(s,l):s).bind("keypress.DT",function(t){return 13==t.keyCode?!1:void 0}).attr("aria-controls",r);return a(t.nTable).on("search.dt.DT",function(n,a){if(t===a)try{c[0]!==e.activeElement&&c.val(i.sSearch)}catch(r){}}),n[0]}function G(t,e,a){var r=t.oPreviousSearch,o=t.aoPreSearchCols,i=function(t){r.sSearch=t.sSearch,r.bRegex=t.bRegex,r.bSmart=t.bSmart,r.bCaseInsensitive=t.bCaseInsensitive};if(v(t),"ssp"!=Ut(t)){for(Y(t,e.sSearch,a,e.bEscapeRegex!==n?!e.bEscapeRegex:e.bRegex,e.bSmart,e.bCaseInsensitive),i(e),e=0;e<o.length;e++)z(t,o[e].sSearch,e,o[e].bEscapeRegex!==n?!o[e].bEscapeRegex:o[e].bRegex,o[e].bSmart,o[e].bCaseInsensitive);$(t)}else i(e);t.bFiltered=!0,Ot(t,null,"search",[t])}function $(t){for(var e,n,r=Xt.ext.search,o=t.aiDisplay,i=0,s=r.length;s>i;i++){for(var l=[],u=0,c=o.length;c>u;u++)n=o[u],e=t.aoData[n],r[i](t,e._aFilterData,n,e._aData,u)&&l.push(n);o.length=0,a.merge(o,l)}}function z(t,e,n,a,r,o){if(""!==e)for(var i=t.aiDisplay,a=Q(e,a,r,o),r=i.length-1;r>=0;r--)e=t.aoData[i[r]]._aFilterData[n],a.test(e)||i.splice(r,1)}function Y(t,e,n,a,r,o){var i,a=Q(e,a,r,o),r=t.oPreviousSearch.sSearch,o=t.aiDisplayMaster;if(0!==Xt.ext.search.length&&(n=!0),i=K(t),0>=e.length)t.aiDisplay=o.slice();else for((i||n||r.length>e.length||0!==e.indexOf(r)||t.bSorted)&&(t.aiDisplay=o.slice()),e=t.aiDisplay,n=e.length-1;n>=0;n--)a.test(t.aoData[e[n]]._sFilterRow)||e.splice(n,1)}function Q(t,e,n,r){return t=e?t:Z(t),n&&(t="^(?=.*?"+a.map(t.match(/"[^"]+"|[^ ]+/g)||[""],function(t){if('"'===t.charAt(0))var e=t.match(/^"(.*)"$/),t=e?e[1]:t;return t.replace('"',"")}).join(")(?=.*?")+").*$"),RegExp(t,r?"i":"")}function Z(t){return t.replace(te,"\\$1")}function K(t){var e,n,a,r,o,i,s,l,u=t.aoColumns,c=Xt.ext.type.search;for(e=!1,n=0,r=t.aoData.length;r>n;n++)if(l=t.aoData[n],!l._aFilterData){for(i=[],a=0,o=u.length;o>a;a++)e=u[a],e.bSearchable?(s=_(t,n,a,"filter"),c[e.sType]&&(s=c[e.sType](s)),null===s&&(s=""),"string"!=typeof s&&s.toString&&(s=s.toString())):s="",s.indexOf&&-1!==s.indexOf("&")&&(ge.innerHTML=s,s=be?ge.textContent:ge.innerText),s.replace&&(s=s.replace(/[\r\n]/g,"")),i.push(s);l._aFilterData=i,l._sFilterRow=i.join("  "),e=!0}return e}function tt(t){return{search:t.sSearch,smart:t.bSmart,regex:t.bRegex,caseInsensitive:t.bCaseInsensitive}}function et(t){return{sSearch:t.search,bSmart:t.smart,bRegex:t.regex,bCaseInsensitive:t.caseInsensitive}}function nt(t){var e=t.sTableId,n=t.aanFeatures.i,r=a("<div/>",{"class":t.oClasses.sInfo,id:n?null:e+"_info"});return n||(t.aoDrawCallback.push({fn:at,sName:"information"}),r.attr("role","status").attr("aria-live","polite"),a(t.nTable).attr("aria-describedby",e+"_info")),r[0]}function at(t){var e=t.aanFeatures.i;if(0!==e.length){var n=t.oLanguage,r=t._iDisplayStart+1,o=t.fnDisplayEnd(),i=t.fnRecordsTotal(),s=t.fnRecordsDisplay(),l=s?n.sInfo:n.sInfoEmpty;s!==i&&(l+=" "+n.sInfoFiltered),l+=n.sInfoPostFix,l=rt(t,l),n=n.fnInfoCallback,null!==n&&(l=n.call(t.oInstance,t,r,o,i,s,l)),a(e).html(l)}}function rt(t,e){var n=t.fnFormatNumber,a=t._iDisplayStart+1,r=t._iDisplayLength,o=t.fnRecordsDisplay(),i=-1===r;return e.replace(/_START_/g,n.call(t,a)).replace(/_END_/g,n.call(t,t.fnDisplayEnd())).replace(/_MAX_/g,n.call(t,t.fnRecordsTotal())).replace(/_TOTAL_/g,n.call(t,o)).replace(/_PAGE_/g,n.call(t,i?1:Math.ceil(a/r))).replace(/_PAGES_/g,n.call(t,i?1:Math.ceil(o/r)))}function ot(t){var e,n,a,r=t.iInitDisplayStart,o=t.aoColumns;n=t.oFeatures;var i=t.bDeferLoading;if(t.bInitialised){for(M(t),H(t),N(t,t.aoHeader),N(t,t.aoFooter),dt(t,!0),n.bAutoWidth&&bt(t),e=0,n=o.length;n>e;e++)a=o[e],a.sWidth&&(a.nTh.style.width=yt(a.sWidth));Ot(t,null,"preInit",[t]),O(t),o=Ut(t),("ssp"!=o||i)&&("ajax"==o?E(t,[],function(n){var a=V(t,n);for(e=0;e<a.length;e++)D(t,a[e]);t.iInitDisplayStart=r,O(t),dt(t,!1),it(t,n)},t):(dt(t,!1),it(t)))}else setTimeout(function(){ot(t)},200)}function it(t,e){t._bInitComplete=!0,(e||t.oInit.aaData)&&h(t),Ot(t,"aoInitComplete","init",[t,e])}function st(t,e){var n=parseInt(e,10);t._iDisplayLength=n,Mt(t),Ot(t,null,"length",[t,n])}function lt(t){for(var e=t.oClasses,n=t.sTableId,r=t.aLengthMenu,o=a.isArray(r[0]),i=o?r[0]:r,r=o?r[1]:r,o=a("<select/>",{name:n+"_length","aria-controls":n,"class":e.sLengthSelect}),s=0,l=i.length;l>s;s++)o[0][s]=new Option(r[s],i[s]);var u=a("<div><label/></div>").addClass(e.sLength);return t.aanFeatures.l||(u[0].id=n+"_length"),u.children().append(t.oLanguage.sLengthMenu.replace("_MENU_",o[0].outerHTML)),a("select",u).val(t._iDisplayLength).bind("change.DT",function(){st(t,a(this).val()),k(t)}),a(t.nTable).bind("length.dt.DT",function(e,n,r){t===n&&a("select",u).val(r)}),u[0]}function ut(t){var e=t.sPaginationType,n=Xt.ext.pager[e],r="function"==typeof n,o=function(t){k(t)},e=a("<div/>").addClass(t.oClasses.sPaging+e)[0],i=t.aanFeatures;return r||n.fnInit(t,e,o),i.p||(e.id=t.sTableId+"_paginate",t.aoDrawCallback.push({fn:function(t){if(r){var e,a=t._iDisplayStart,s=t._iDisplayLength,l=t.fnRecordsDisplay(),u=-1===s,a=u?0:Math.ceil(a/s),s=u?1:Math.ceil(l/s),l=n(a,s),u=0;for(e=i.p.length;e>u;u++)Wt(t,"pageButton")(t,i.p[u],u,l,a,s)}else n.fnUpdate(t,o)},sName:"pagination"})),e}function ct(t,e,n){var a=t._iDisplayStart,r=t._iDisplayLength,o=t.fnRecordsDisplay();return 0===o||-1===r?a=0:"number"==typeof e?(a=e*r,a>o&&(a=0)):"first"==e?a=0:"previous"==e?(a=r>=0?a-r:0,0>a&&(a=0)):"next"==e?o>a+r&&(a+=r):"last"==e?a=Math.floor((o-1)/r)*r:Pt(t,0,"Unknown paging action: "+e,5),e=t._iDisplayStart!==a,t._iDisplayStart=a,e&&(Ot(t,null,"page",[t]),n&&k(t)),e}function ft(t){return a("<div/>",{id:t.aanFeatures.r?null:t.sTableId+"_processing","class":t.oClasses.sProcessing}).html(t.oLanguage.sProcessing).insertBefore(t.nTable)[0]}function dt(t,e){t.oFeatures.bProcessing&&a(t.aanFeatures.r).css("display",e?"block":"none"),Ot(t,null,"processing",[t,e])}function ht(t){var e=a(t.nTable);e.attr("role","grid");var n=t.oScroll;if(""===n.sX&&""===n.sY)return t.nTable;var r=n.sX,o=n.sY,i=t.oClasses,s=e.children("caption"),l=s.length?s[0]._captionSide:null,u=a(e[0].cloneNode(!1)),c=a(e[0].cloneNode(!1)),f=e.children("tfoot");n.sX&&"100%"===e.attr("width")&&e.removeAttr("width"),f.length||(f=null),u=a("<div/>",{"class":i.sScrollWrapper}).append(a("<div/>",{"class":i.sScrollHead}).css({overflow:"hidden",position:"relative",border:0,width:r?r?yt(r):null:"100%"}).append(a("<div/>",{"class":i.sScrollHeadInner}).css({"box-sizing":"content-box",width:n.sXInner||"100%"}).append(u.removeAttr("id").css("margin-left",0).append("top"===l?s:null).append(e.children("thead"))))).append(a("<div/>",{"class":i.sScrollBody}).css({position:"relative",overflow:"auto",width:r?yt(r):null}).append(e)),f&&u.append(a("<div/>",{"class":i.sScrollFoot}).css({overflow:"hidden",border:0,width:r?r?yt(r):null:"100%"}).append(a("<div/>",{"class":i.sScrollFootInner}).append(c.removeAttr("id").css("margin-left",0).append("bottom"===l?s:null).append(e.children("tfoot")))));var e=u.children(),d=e[0],i=e[1],h=f?e[2]:null;return r&&a(i).on("scroll.DT",function(){var t=this.scrollLeft;d.scrollLeft=t,f&&(h.scrollLeft=t)}),a(i).css(o&&n.bCollapse?"max-height":"height",o),t.nScrollHead=d,t.nScrollBody=i,t.nScrollFoot=h,t.aoDrawCallback.push({fn:pt,sName:"scrolling"}),u[0]}function pt(t){var e,n,r,o,i,s=t.oScroll,l=s.sX,u=s.sXInner,c=s.sY,s=s.iBarWidth,f=a(t.nScrollHead),d=f[0].style,h=f.children("div"),g=h[0].style,b=h.children("table"),h=t.nScrollBody,S=a(h),v=h.style,m=a(t.nScrollFoot).children("div"),D=m.children("table"),y=a(t.nTHead),_=a(t.nTable),T=_[0],C=T.style,w=t.nTFoot?a(t.nTFoot):null,x=t.oBrowser,I=x.bScrollOversize,A=[],F=[],L=[],R=function(t){t=t.style,t.paddingTop="0",t.paddingBottom="0",t.borderTopWidth="0",t.borderBottomWidth="0",t.height=0};_.children("thead, tfoot").remove(),o=y.clone().prependTo(_),y=y.find("tr"),n=o.find("tr"),o.find("th, td").removeAttr("tabindex"),w&&(r=w.clone().prependTo(_),e=w.find("tr"),r=r.find("tr")),l||(v.width="100%",f[0].style.width="100%"),a.each(U(t,o),function(e,n){i=p(t,e),n.style.width=t.aoColumns[i].sWidth}),w&&gt(function(t){t.style.width=""},r),f=_.outerWidth(),""===l?(C.width="100%",I&&(_.find("tbody").height()>h.offsetHeight||"scroll"==S.css("overflow-y"))&&(C.width=yt(_.outerWidth()-s)),f=_.outerWidth()):""!==u&&(C.width=yt(u),f=_.outerWidth()),gt(R,n),gt(function(t){L.push(t.innerHTML),A.push(yt(a(t).css("width")))},n),gt(function(t,e){t.style.width=A[e]},y),a(n).height(0),w&&(gt(R,r),gt(function(t){F.push(yt(a(t).css("width")))},r),gt(function(t,e){t.style.width=F[e]},e),a(r).height(0)),gt(function(t,e){t.innerHTML='<div class="dataTables_sizing" style="height:0;overflow:hidden;">'+L[e]+"</div>",t.style.width=A[e]},n),w&&gt(function(t,e){t.innerHTML="",t.style.width=F[e]},r),_.outerWidth()<f?(e=h.scrollHeight>h.offsetHeight||"scroll"==S.css("overflow-y")?f+s:f,I&&(h.scrollHeight>h.offsetHeight||"scroll"==S.css("overflow-y"))&&(C.width=yt(e-s)),(""===l||""!==u)&&Pt(t,1,"Possible column misalignment",6)):e="100%",v.width=yt(e),d.width=yt(e),w&&(t.nScrollFoot.style.width=yt(e)),!c&&I&&(v.height=yt(T.offsetHeight+s)),l=_.outerWidth(),b[0].style.width=yt(l),g.width=yt(l),u=_.height()>h.clientHeight||"scroll"==S.css("overflow-y"),c="padding"+(x.bScrollbarLeft?"Left":"Right"),g[c]=u?s+"px":"0px",w&&(D[0].style.width=yt(l),m[0].style.width=yt(l),m[0].style[c]=u?s+"px":"0px"),S.scroll(),!t.bSorted&&!t.bFiltered||t._drawHold||(h.scrollTop=0)}function gt(t,e,n){for(var a,r,o=0,i=0,s=e.length;s>i;){for(a=e[i].firstChild,r=n?n[i].firstChild:null;a;)1===a.nodeType&&(n?t(a,r,o):t(a,o),o++),a=a.nextSibling,r=n?r.nextSibling:null;i++}}function bt(e){var n,r,o,i=e.nTable,s=e.aoColumns,l=e.oScroll,u=l.sY,c=l.sX,f=l.sXInner,d=s.length,g=S(e,"bVisible"),v=a("th",e.nTHead),m=i.getAttribute("width"),D=i.parentNode,y=!1;for(o=e.oBrowser,l=o.bScrollOversize,(n=i.style.width)&&-1!==n.indexOf("%")&&(m=n),n=0;n<g.length;n++)r=s[g[n]],null!==r.sWidth&&(r.sWidth=vt(r.sWidthOrig,D),y=!0);if(l||!y&&!c&&!u&&d==b(e)&&d==v.length)for(n=0;d>n;n++)(g=p(e,n))&&(s[g].sWidth=yt(v.eq(n).width()));else{d=a(i).clone().css("visibility","hidden").removeAttr("id"),d.find("tbody tr").remove();var _=a("<tr/>").appendTo(d.find("tbody"));for(d.find("thead, tfoot").remove(),d.append(a(e.nTHead).clone()).append(a(e.nTFoot).clone()),d.find("tfoot th, tfoot td").css("width",""),v=U(e,d.find("thead")[0]),n=0;n<g.length;n++)r=s[g[n]],v[n].style.width=null!==r.sWidthOrig&&""!==r.sWidthOrig?yt(r.sWidthOrig):"";if(e.aoData.length)for(n=0;n<g.length;n++)y=g[n],r=s[y],a(mt(e,y)).clone(!1).append(r.sContentPadding).appendTo(_);if(y=a("<div/>").css(c||u?{position:"absolute",top:0,left:0,height:1,right:0,overflow:"hidden"}:{}).append(d).appendTo(D),c&&f?d.width(f):c?(d.css("width","auto"),d.width()<D.clientWidth&&d.width(D.clientWidth)):u?d.width(D.clientWidth):m&&d.width(m),c){for(n=f=0;n<g.length;n++)r=s[g[n]],u=o.bBounding?v[n].getBoundingClientRect().width:a(v[n]).outerWidth(),f+=null===r.sWidthOrig?u:parseInt(r.sWidth,10)+u-a(v[n]).width();d.width(yt(f)),i.style.width=yt(f)}for(n=0;n<g.length;n++)r=s[g[n]],(o=a(v[n]).width())&&(r.sWidth=yt(o));i.style.width=yt(d.css("width")),y.remove()}m&&(i.style.width=yt(m)),!m&&!c||e._reszEvt||(i=function(){a(t).bind("resize.DT-"+e.sInstance,St(function(){h(e)}))},l?setTimeout(i,1e3):i(),e._reszEvt=!0)}function St(t,e){var a,r,o=e!==n?e:200;return function(){var e=this,i=+new Date,s=arguments;a&&a+o>i?(clearTimeout(r),r=setTimeout(function(){a=n,t.apply(e,s)},o)):(a=i,t.apply(e,s))}}function vt(t,n){if(!t)return 0;var r=a("<div/>").css("width",yt(t)).appendTo(n||e.body),o=r[0].offsetWidth;return r.remove(),o}function mt(t,e){var n=Dt(t,e);if(0>n)return null;var r=t.aoData[n];return r.nTr?r.anCells[e]:a("<td/>").html(_(t,n,e,"display"))[0]}function Dt(t,e){for(var n,a=-1,r=-1,o=0,i=t.aoData.length;i>o;o++)n=_(t,o,e,"display")+"",n=n.replace(Se,""),n.length>a&&(a=n.length,r=o);return r}function yt(t){return null===t?"0px":"number"==typeof t?0>t?"0px":t+"px":t.match(/\d$/)?t+"px":t}function _t(t){var e,r,o,i,s,l,u=[],c=t.aoColumns;e=t.aaSortingFixed,r=a.isPlainObject(e);var f=[];for(o=function(t){t.length&&!a.isArray(t[0])?f.push(t):a.merge(f,t)},a.isArray(e)&&o(e),r&&e.pre&&o(e.pre),o(t.aaSorting),r&&e.post&&o(e.post),t=0;t<f.length;t++)for(l=f[t][0],o=c[l].aDataSort,e=0,r=o.length;r>e;e++)i=o[e],s=c[i].sType||"string",f[t]._idx===n&&(f[t]._idx=a.inArray(f[t][1],c[i].asSorting)),u.push({src:l,col:i,dir:f[t][1],index:f[t]._idx,type:s,formatter:Xt.ext.type.order[s+"-pre"]});return u}function Tt(t){var e,n,a,r,o=[],i=Xt.ext.type.order,s=t.aoData,l=0,u=t.aiDisplayMaster;for(v(t),r=_t(t),e=0,n=r.length;n>e;e++)a=r[e],a.formatter&&l++,At(t,a.col);if("ssp"!=Ut(t)&&0!==r.length){for(e=0,n=u.length;n>e;e++)o[u[e]]=e;l===r.length?u.sort(function(t,e){var n,a,i,l,u=r.length,c=s[t]._aSortData,f=s[e]._aSortData;for(i=0;u>i;i++)if(l=r[i],n=c[l.col],a=f[l.col],n=a>n?-1:n>a?1:0,0!==n)return"asc"===l.dir?n:-n;return n=o[t],a=o[e],a>n?-1:n>a?1:0}):u.sort(function(t,e){var n,a,l,u,c=r.length,f=s[t]._aSortData,d=s[e]._aSortData;for(l=0;c>l;l++)if(u=r[l],n=f[u.col],a=d[u.col],u=i[u.type+"-"+u.dir]||i["string-"+u.dir],n=u(n,a),0!==n)return n;return n=o[t],a=o[e],a>n?-1:n>a?1:0})}t.bSorted=!0}function Ct(t){for(var e,n,a=t.aoColumns,r=_t(t),t=t.oLanguage.oAria,o=0,i=a.length;i>o;o++){n=a[o];var s=n.asSorting;e=n.sTitle.replace(/<.*?>/g,"");var l=n.nTh;l.removeAttribute("aria-sort"),n.bSortable&&(0<r.length&&r[0].col==o?(l.setAttribute("aria-sort","asc"==r[0].dir?"ascending":"descending"),n=s[r[0].index+1]||s[0]):n=s[0],e+="asc"===n?t.sSortAscending:t.sSortDescending),l.setAttribute("aria-label",e)}}function wt(t,e,r,o){var i=t.aaSorting,s=t.aoColumns[e].asSorting,l=function(t,e){var r=t._idx;return r===n&&(r=a.inArray(t[1],s)),r+1<s.length?r+1:e?null:0};"number"==typeof i[0]&&(i=t.aaSorting=[i]),r&&t.oFeatures.bSortMulti?(r=a.inArray(e,se(i,"0")),-1!==r?(e=l(i[r],!0),null===e&&1===i.length&&(e=0),null===e?i.splice(r,1):(i[r][1]=s[e],i[r]._idx=e)):(i.push([e,s[0],0]),i[i.length-1]._idx=0)):i.length&&i[0][0]==e?(e=l(i[0]),
i.length=1,i[0][1]=s[e],i[0]._idx=e):(i.length=0,i.push([e,s[0]]),i[0]._idx=0),O(t),"function"==typeof o&&o(t)}function xt(t,e,n,a){var r=t.aoColumns[n];Nt(e,{},function(e){!1!==r.bSortable&&(t.oFeatures.bProcessing?(dt(t,!0),setTimeout(function(){wt(t,n,e.shiftKey,a),"ssp"!==Ut(t)&&dt(t,!1)},0)):wt(t,n,e.shiftKey,a))})}function It(t){var e,n,r=t.aLastSort,o=t.oClasses.sSortColumn,i=_t(t),s=t.oFeatures;if(s.bSort&&s.bSortClasses){for(s=0,e=r.length;e>s;s++)n=r[s].src,a(se(t.aoData,"anCells",n)).removeClass(o+(2>s?s+1:3));for(s=0,e=i.length;e>s;s++)n=i[s].src,a(se(t.aoData,"anCells",n)).addClass(o+(2>s?s+1:3))}t.aLastSort=i}function At(t,e){var n,a=t.aoColumns[e],r=Xt.ext.order[a.sSortDataType];r&&(n=r.call(t.oInstance,t,e,g(t,e)));for(var o,i=Xt.ext.type.order[a.sType+"-pre"],s=0,l=t.aoData.length;l>s;s++)a=t.aoData[s],a._aSortData||(a._aSortData=[]),(!a._aSortData[e]||r)&&(o=r?n[s]:_(t,s,e,"sort"),a._aSortData[e]=i?i(o):o)}function Ft(t){if(t.oFeatures.bStateSave&&!t.bDestroying){var e={time:+new Date,start:t._iDisplayStart,length:t._iDisplayLength,order:a.extend(!0,[],t.aaSorting),search:tt(t.oPreviousSearch),columns:a.map(t.aoColumns,function(e,n){return{visible:e.bVisible,search:tt(t.aoPreSearchCols[n])}})};Ot(t,"aoStateSaveParams","stateSaveParams",[t,e]),t.oSavedState=e,t.fnStateSaveCallback.call(t.oInstance,t,e)}}function Lt(t){var e,r,o=t.aoColumns;if(t.oFeatures.bStateSave){var i=t.fnStateLoadCallback.call(t.oInstance,t);if(i&&i.time&&(e=Ot(t,"aoStateLoadParams","stateLoadParams",[t,i]),-1===a.inArray(!1,e)&&(e=t.iStateDuration,!(e>0&&i.time<+new Date-1e3*e)&&o.length===i.columns.length))){for(t.oLoadedState=a.extend(!0,{},i),i.start!==n&&(t._iDisplayStart=i.start,t.iInitDisplayStart=i.start),i.length!==n&&(t._iDisplayLength=i.length),i.order!==n&&(t.aaSorting=[],a.each(i.order,function(e,n){t.aaSorting.push(n[0]>=o.length?[0,n[1]]:n)})),i.search!==n&&a.extend(t.oPreviousSearch,et(i.search)),e=0,r=i.columns.length;r>e;e++){var s=i.columns[e];s.visible!==n&&(o[e].bVisible=s.visible),s.search!==n&&a.extend(t.aoPreSearchCols[e],et(s.search))}Ot(t,"aoStateLoaded","stateLoaded",[t,i])}}}function Rt(t){var e=Xt.settings,t=a.inArray(t,se(e,"nTable"));return-1!==t?e[t]:null}function Pt(e,n,a,r){if(a="DataTables warning: "+(e?"table id="+e.sTableId+" - ":"")+a,r&&(a+=". For more information about this error, please see http://datatables.net/tn/"+r),n)t.console&&console.log&&console.log(a);else if(n=Xt.ext,n=n.sErrMode||n.errMode,e&&Ot(e,null,"error",[e,r,a]),"alert"==n)alert(a);else{if("throw"==n)throw Error(a);"function"==typeof n&&n(e,r,a)}}function jt(t,e,r,o){a.isArray(r)?a.each(r,function(n,r){a.isArray(r)?jt(t,e,r[0],r[1]):jt(t,e,r)}):(o===n&&(o=r),e[r]!==n&&(t[o]=e[r]))}function Ht(t,e,n){var r,o;for(o in e)e.hasOwnProperty(o)&&(r=e[o],a.isPlainObject(r)?(a.isPlainObject(t[o])||(t[o]={}),a.extend(!0,t[o],r)):t[o]=n&&"data"!==o&&"aaData"!==o&&a.isArray(r)?r.slice():r);return t}function Nt(t,e,n){a(t).bind("click.DT",e,function(e){t.blur(),n(e)}).bind("keypress.DT",e,function(t){13===t.which&&(t.preventDefault(),n(t))}).bind("selectstart.DT",function(){return!1})}function kt(t,e,n,a){n&&t[e].push({fn:n,sName:a})}function Ot(t,e,n,r){var o=[];return e&&(o=a.map(t[e].slice().reverse(),function(e){return e.fn.apply(t.oInstance,r)})),null!==n&&(e=a.Event(n+".dt"),a(t.nTable).trigger(e,r),o.push(e.result)),o}function Mt(t){var e=t._iDisplayStart,n=t.fnDisplayEnd(),a=t._iDisplayLength;e>=n&&(e=n-a),e-=e%a,(-1===a||0>e)&&(e=0),t._iDisplayStart=e}function Wt(t,e){var n=t.renderer,r=Xt.ext.renderer[e];return a.isPlainObject(n)&&n[e]?r[n[e]]||r._:"string"==typeof n?r[n]||r._:r._}function Ut(t){return t.oFeatures.bServerSide?"ssp":t.ajax||t.sAjaxSource?"ajax":"dom"}function Et(t,e){var n=[],n=He.numbers_length,a=Math.floor(n/2);return n>=e?n=ue(0,e):a>=t?(n=ue(0,n-2),n.push("ellipsis"),n.push(e-1)):(t>=e-1-a?n=ue(e-(n-2),e):(n=ue(t-a+2,t+a-1),n.push("ellipsis"),n.push(e-1)),n.splice(0,0,"ellipsis"),n.splice(0,0,0)),n.DT_el="span",n}function Bt(t){a.each({num:function(e){return Ne(e,t)},"num-fmt":function(e){return Ne(e,t,ee)},"html-num":function(e){return Ne(e,t,Qt)},"html-num-fmt":function(e){return Ne(e,t,Qt,ee)}},function(e,n){Vt.type.order[e+t+"-pre"]=n,e.match(/^html\-/)&&(Vt.type.search[e+t]=Vt.type.search.html)})}function Jt(t){return function(){var e=[Rt(this[Xt.ext.iApiIndex])].concat(Array.prototype.slice.call(arguments));return Xt.ext.internal[t].apply(this,e)}}var Xt,Vt,qt,Gt,$t,zt={},Yt=/[\r\n]/g,Qt=/<.*?>/g,Zt=/^[\w\+\-]/,Kt=/[\w\+\-]$/,te=RegExp("(\\/|\\.|\\*|\\+|\\?|\\||\\(|\\)|\\[|\\]|\\{|\\}|\\\\|\\$|\\^|\\-)","g"),ee=/[',$£€¥%\u2009\u202F\u20BD\u20a9\u20BArfk]/gi,ne=function(t){return t&&!0!==t&&"-"!==t?!1:!0},ae=function(t){var e=parseInt(t,10);return!isNaN(e)&&isFinite(t)?e:null},re=function(t,e){return zt[e]||(zt[e]=RegExp(Z(e),"g")),"string"==typeof t&&"."!==e?t.replace(/\./g,"").replace(zt[e],"."):t},oe=function(t,e,n){var a="string"==typeof t;return ne(t)?!0:(e&&a&&(t=re(t,e)),n&&a&&(t=t.replace(ee,"")),!isNaN(parseFloat(t))&&isFinite(t))},ie=function(t,e,n){return ne(t)?!0:(ne(t)||"string"==typeof t)&&oe(t.replace(Qt,""),e,n)?!0:null},se=function(t,e,a){var r=[],o=0,i=t.length;if(a!==n)for(;i>o;o++)t[o]&&t[o][e]&&r.push(t[o][e][a]);else for(;i>o;o++)t[o]&&r.push(t[o][e]);return r},le=function(t,e,a,r){var o=[],i=0,s=e.length;if(r!==n)for(;s>i;i++)t[e[i]][a]&&o.push(t[e[i]][a][r]);else for(;s>i;i++)o.push(t[e[i]][a]);return o},ue=function(t,e){var a,r=[];e===n?(e=0,a=t):(a=e,e=t);for(var o=e;a>o;o++)r.push(o);return r},ce=function(t){for(var e=[],n=0,a=t.length;a>n;n++)t[n]&&e.push(t[n]);return e},fe=function(t){var e,n,a,r=[],o=t.length,i=0;n=0;t:for(;o>n;n++){for(e=t[n],a=0;i>a;a++)if(r[a]===e)continue t;r.push(e),i++}return r},de=function(t,e,a){t[e]!==n&&(t[a]=t[e])},he=/\[.*?\]$/,pe=/\(\)$/,ge=a("<div>")[0],be=ge.textContent!==n,Se=/<.*?>/g;Xt=function(t){this.$=function(t,e){return this.api(!0).$(t,e)},this._=function(t,e){return this.api(!0).rows(t,e).data()},this.api=function(t){return new qt(t?Rt(this[Vt.iApiIndex]):this)},this.fnAddData=function(t,e){var r=this.api(!0),o=a.isArray(t)&&(a.isArray(t[0])||a.isPlainObject(t[0]))?r.rows.add(t):r.row.add(t);return(e===n||e)&&r.draw(),o.flatten().toArray()},this.fnAdjustColumnSizing=function(t){var e=this.api(!0).columns.adjust(),a=e.settings()[0],r=a.oScroll;t===n||t?e.draw(!1):(""!==r.sX||""!==r.sY)&&pt(a)},this.fnClearTable=function(t){var e=this.api(!0).clear();(t===n||t)&&e.draw()},this.fnClose=function(t){this.api(!0).row(t).child.hide()},this.fnDeleteRow=function(t,e,a){var r=this.api(!0),t=r.rows(t),o=t.settings()[0],i=o.aoData[t[0][0]];return t.remove(),e&&e.call(this,o,i),(a===n||a)&&r.draw(),i},this.fnDestroy=function(t){this.api(!0).destroy(t)},this.fnDraw=function(t){this.api(!0).draw(t)},this.fnFilter=function(t,e,a,r,o,i){o=this.api(!0),null===e||e===n?o.search(t,a,r,i):o.column(e).search(t,a,r,i),o.draw()},this.fnGetData=function(t,e){var a=this.api(!0);if(t!==n){var r=t.nodeName?t.nodeName.toLowerCase():"";return e!==n||"td"==r||"th"==r?a.cell(t,e).data():a.row(t).data()||null}return a.data().toArray()},this.fnGetNodes=function(t){var e=this.api(!0);return t!==n?e.row(t).node():e.rows().nodes().flatten().toArray()},this.fnGetPosition=function(t){var e=this.api(!0),n=t.nodeName.toUpperCase();return"TR"==n?e.row(t).index():"TD"==n||"TH"==n?(t=e.cell(t).index(),[t.row,t.columnVisible,t.column]):null},this.fnIsOpen=function(t){return this.api(!0).row(t).child.isShown()},this.fnOpen=function(t,e,n){return this.api(!0).row(t).child(e,n).show().child()[0]},this.fnPageChange=function(t,e){var a=this.api(!0).page(t);(e===n||e)&&a.draw(!1)},this.fnSetColumnVis=function(t,e,a){t=this.api(!0).column(t).visible(e),(a===n||a)&&t.columns.adjust().draw()},this.fnSettings=function(){return Rt(this[Vt.iApiIndex])},this.fnSort=function(t){this.api(!0).order(t).draw()},this.fnSortListener=function(t,e,n){this.api(!0).order.listener(t,e,n)},this.fnUpdate=function(t,e,a,r,o){var i=this.api(!0);return a===n||null===a?i.row(e).data(t):i.cell(e,a).data(t),(o===n||o)&&i.columns.adjust(),(r===n||r)&&i.draw(),0},this.fnVersionCheck=Vt.fnVersionCheck;var e=this,r=t===n,c=this.length;r&&(t={}),this.oApi=this.internal=Vt.internal;for(var h in Xt.ext.internal)h&&(this[h]=Jt(h));return this.each(function(){var h,p={},p=c>1?Ht(p,t,!0):t,g=0,b=this.getAttribute("id"),S=!1,v=Xt.defaults,_=a(this);if("table"!=this.nodeName.toLowerCase())Pt(null,0,"Non-table node initialisation ("+this.nodeName+")",2);else{s(v),l(v.column),o(v,v,!0),o(v.column,v.column,!0),o(v,a.extend(p,_.data()));var T=Xt.settings,g=0;for(h=T.length;h>g;g++){var C=T[g];if(C.nTable==this||C.nTHead.parentNode==this||C.nTFoot&&C.nTFoot.parentNode==this){if(g=p.bRetrieve!==n?p.bRetrieve:v.bRetrieve,r||g)return C.oInstance;if(p.bDestroy!==n?p.bDestroy:v.bDestroy){C.oInstance.fnDestroy();break}return void Pt(C,0,"Cannot reinitialise DataTable",3)}if(C.sTableId==this.id){T.splice(g,1);break}}(null===b||""===b)&&(this.id=b="DataTables_Table_"+Xt.ext._unique++);var x=a.extend(!0,{},Xt.models.oSettings,{sDestroyWidth:_[0].style.width,sInstance:b,sTableId:b});x.nTable=this,x.oApi=e.internal,x.oInit=p,T.push(x),x.oInstance=1===e.length?e:_.dataTable(),s(p),p.oLanguage&&i(p.oLanguage),p.aLengthMenu&&!p.iDisplayLength&&(p.iDisplayLength=a.isArray(p.aLengthMenu[0])?p.aLengthMenu[0][0]:p.aLengthMenu[0]),p=Ht(a.extend(!0,{},v),p),jt(x.oFeatures,p,"bPaginate bLengthChange bFilter bSort bSortMulti bInfo bProcessing bAutoWidth bSortClasses bServerSide bDeferRender".split(" ")),jt(x,p,["asStripeClasses","ajax","fnServerData","fnFormatNumber","sServerMethod","aaSorting","aaSortingFixed","aLengthMenu","sPaginationType","sAjaxSource","sAjaxDataProp","iStateDuration","sDom","bSortCellsTop","iTabIndex","fnStateLoadCallback","fnStateSaveCallback","renderer","searchDelay","rowId",["iCookieDuration","iStateDuration"],["oSearch","oPreviousSearch"],["aoSearchCols","aoPreSearchCols"],["iDisplayLength","_iDisplayLength"],["bJQueryUI","bJUI"]]),jt(x.oScroll,p,[["sScrollX","sX"],["sScrollXInner","sXInner"],["sScrollY","sY"],["bScrollCollapse","bCollapse"]]),jt(x.oLanguage,p,"fnInfoCallback"),kt(x,"aoDrawCallback",p.fnDrawCallback,"user"),kt(x,"aoServerParams",p.fnServerParams,"user"),kt(x,"aoStateSaveParams",p.fnStateSaveParams,"user"),kt(x,"aoStateLoadParams",p.fnStateLoadParams,"user"),kt(x,"aoStateLoaded",p.fnStateLoaded,"user"),kt(x,"aoRowCallback",p.fnRowCallback,"user"),kt(x,"aoRowCreatedCallback",p.fnCreatedRow,"user"),kt(x,"aoHeaderCallback",p.fnHeaderCallback,"user"),kt(x,"aoFooterCallback",p.fnFooterCallback,"user"),kt(x,"aoInitComplete",p.fnInitComplete,"user"),kt(x,"aoPreDrawCallback",p.fnPreDrawCallback,"user"),x.rowIdFn=w(p.rowId),u(x),b=x.oClasses,p.bJQueryUI?(a.extend(b,Xt.ext.oJUIClasses,p.oClasses),p.sDom===v.sDom&&"lfrtip"===v.sDom&&(x.sDom='<"H"lfr>t<"F"ip>'),x.renderer?a.isPlainObject(x.renderer)&&!x.renderer.header&&(x.renderer.header="jqueryui"):x.renderer="jqueryui"):a.extend(b,Xt.ext.classes,p.oClasses),_.addClass(b.sTable),x.iInitDisplayStart===n&&(x.iInitDisplayStart=p.iDisplayStart,x._iDisplayStart=p.iDisplayStart),null!==p.iDeferLoading&&(x.bDeferLoading=!0,g=a.isArray(p.iDeferLoading),x._iRecordsDisplay=g?p.iDeferLoading[0]:p.iDeferLoading,x._iRecordsTotal=g?p.iDeferLoading[1]:p.iDeferLoading);var I=x.oLanguage;a.extend(!0,I,p.oLanguage),""!==I.sUrl&&(a.ajax({dataType:"json",url:I.sUrl,success:function(t){i(t),o(v.oLanguage,t),a.extend(!0,I,t),ot(x)},error:function(){ot(x)}}),S=!0),null===p.asStripeClasses&&(x.asStripeClasses=[b.sStripeOdd,b.sStripeEven]);var g=x.asStripeClasses,A=_.children("tbody").find("tr").eq(0);if(-1!==a.inArray(!0,a.map(g,function(t){return A.hasClass(t)}))&&(a("tbody tr",this).removeClass(g.join(" ")),x.asDestroyStripes=g.slice()),T=[],g=this.getElementsByTagName("thead"),0!==g.length&&(W(x.aoHeader,g[0]),T=U(x)),null===p.aoColumns)for(C=[],g=0,h=T.length;h>g;g++)C.push(null);else C=p.aoColumns;for(g=0,h=C.length;h>g;g++)f(x,T?T[g]:null);if(m(x,p.aoColumnDefs,C,function(t,e){d(x,t,e)}),A.length){var F=function(t,e){return null!==t.getAttribute("data-"+e)?e:null};a(A[0]).children("th, td").each(function(t,e){var a=x.aoColumns[t];if(a.mData===t){var r=F(e,"sort")||F(e,"order"),o=F(e,"filter")||F(e,"search");(null!==r||null!==o)&&(a.mData={_:t+".display",sort:null!==r?t+".@data-"+r:n,type:null!==r?t+".@data-"+r:n,filter:null!==o?t+".@data-"+o:n},d(x,t))}})}var L=x.oFeatures;if(p.bStateSave&&(L.bStateSave=!0,Lt(x,p),kt(x,"aoDrawCallback",Ft,"state_save")),p.aaSorting===n)for(T=x.aaSorting,g=0,h=T.length;h>g;g++)T[g][1]=x.aoColumns[g].asSorting[0];if(It(x),L.bSort&&kt(x,"aoDrawCallback",function(){if(x.bSorted){var t=_t(x),e={};a.each(t,function(t,n){e[n.src]=n.dir}),Ot(x,null,"order",[x,t,e]),Ct(x)}}),kt(x,"aoDrawCallback",function(){(x.bSorted||"ssp"===Ut(x)||L.bDeferRender)&&It(x)},"sc"),g=_.children("caption").each(function(){this._captionSide=_.css("caption-side")}),h=_.children("thead"),0===h.length&&(h=a("<thead/>").appendTo(this)),x.nTHead=h[0],h=_.children("tbody"),0===h.length&&(h=a("<tbody/>").appendTo(this)),x.nTBody=h[0],h=_.children("tfoot"),0===h.length&&0<g.length&&(""!==x.oScroll.sX||""!==x.oScroll.sY)&&(h=a("<tfoot/>").appendTo(this)),0===h.length||0===h.children().length?_.addClass(b.sNoFooter):0<h.length&&(x.nTFoot=h[0],W(x.aoFooter,x.nTFoot)),p.aaData)for(g=0;g<p.aaData.length;g++)D(x,p.aaData[g]);else(x.bDeferLoading||"dom"==Ut(x))&&y(x,a(x.nTBody).children("tr"));x.aiDisplay=x.aiDisplayMaster.slice(),x.bInitialised=!0,!1===S&&ot(x)}}),e=null,this};var ve=[],me=Array.prototype,De=function(t){var e,n,r=Xt.settings,o=a.map(r,function(t){return t.nTable});return t?t.nTable&&t.oApi?[t]:t.nodeName&&"table"===t.nodeName.toLowerCase()?(e=a.inArray(t,o),-1!==e?[r[e]]:null):t&&"function"==typeof t.settings?t.settings().toArray():("string"==typeof t?n=a(t):t instanceof a&&(n=t),n?n.map(function(){return e=a.inArray(this,o),-1!==e?r[e]:null}).toArray():void 0):[]};qt=function(t,e){if(!(this instanceof qt))return new qt(t,e);var n=[],r=function(t){(t=De(t))&&(n=n.concat(t))};if(a.isArray(t))for(var o=0,i=t.length;i>o;o++)r(t[o]);else r(t);this.context=fe(n),e&&a.merge(this,e),this.selector={rows:null,cols:null,opts:null},qt.extend(this,this,ve)},Xt.Api=qt,a.extend(qt.prototype,{any:function(){return 0!==this.count()},concat:me.concat,context:[],count:function(){return this.flatten().length},each:function(t){for(var e=0,n=this.length;n>e;e++)t.call(this,this[e],e,this);return this},eq:function(t){var e=this.context;return e.length>t?new qt(e[t],this[t]):null},filter:function(t){var e=[];if(me.filter)e=me.filter.call(this,t,this);else for(var n=0,a=this.length;a>n;n++)t.call(this,this[n],n,this)&&e.push(this[n]);return new qt(this.context,e)},flatten:function(){var t=[];return new qt(this.context,t.concat.apply(t,this.toArray()))},join:me.join,indexOf:me.indexOf||function(t,e){for(var n=e||0,a=this.length;a>n;n++)if(this[n]===t)return n;return-1},iterator:function(t,e,a,r){var o,i,s,l,u,c,f,d=[],h=this.context,p=this.selector;for("string"==typeof t&&(r=a,a=e,e=t,t=!1),i=0,s=h.length;s>i;i++){var g=new qt(h[i]);if("table"===e)o=a.call(g,h[i],i),o!==n&&d.push(o);else if("columns"===e||"rows"===e)o=a.call(g,h[i],this[i],i),o!==n&&d.push(o);else if("column"===e||"column-rows"===e||"row"===e||"cell"===e)for(f=this[i],"column-rows"===e&&(c=we(h[i],p.opts)),l=0,u=f.length;u>l;l++)o=f[l],o="cell"===e?a.call(g,h[i],o.row,o.column,i,l):a.call(g,h[i],o,i,l,c),o!==n&&d.push(o)}return d.length||r?(t=new qt(h,t?d.concat.apply([],d):d),e=t.selector,e.rows=p.rows,e.cols=p.cols,e.opts=p.opts,t):this},lastIndexOf:me.lastIndexOf||function(t,e){return this.indexOf.apply(this.toArray.reverse(),arguments)},length:0,map:function(t){var e=[];if(me.map)e=me.map.call(this,t,this);else for(var n=0,a=this.length;a>n;n++)e.push(t.call(this,this[n],n));return new qt(this.context,e)},pluck:function(t){return this.map(function(e){return e[t]})},pop:me.pop,push:me.push,reduce:me.reduce||function(t,e){return c(this,t,e,0,this.length,1)},reduceRight:me.reduceRight||function(t,e){return c(this,t,e,this.length-1,-1,-1)},reverse:me.reverse,selector:null,shift:me.shift,sort:me.sort,splice:me.splice,toArray:function(){return me.slice.call(this)},to$:function(){return a(this)},toJQuery:function(){return a(this)},unique:function(){return new qt(this.context,fe(this))},unshift:me.unshift}),qt.extend=function(t,e,n){if(n.length&&e&&(e instanceof qt||e.__dt_wrapper)){var r,o,i,s=function(t,e,n){return function(){var a=e.apply(t,arguments);return qt.extend(a,a,n.methodExt),a}};for(r=0,o=n.length;o>r;r++)i=n[r],e[i.name]="function"==typeof i.val?s(t,i.val,i):a.isPlainObject(i.val)?{}:i.val,e[i.name].__dt_wrapper=!0,qt.extend(t,e[i.name],i.propExt)}},qt.register=Gt=function(t,e){if(a.isArray(t))for(var n=0,r=t.length;r>n;n++)qt.register(t[n],e);else for(var o,i,s=t.split("."),l=ve,n=0,r=s.length;r>n;n++){o=(i=-1!==s[n].indexOf("()"))?s[n].replace("()",""):s[n];var u;t:{u=0;for(var c=l.length;c>u;u++)if(l[u].name===o){u=l[u];break t}u=null}u||(u={name:o,val:{},methodExt:[],propExt:[]},l.push(u)),n===r-1?u.val=e:l=i?u.methodExt:u.propExt}},qt.registerPlural=$t=function(t,e,r){qt.register(t,r),qt.register(e,function(){var t=r.apply(this,arguments);return t===this?this:t instanceof qt?t.length?a.isArray(t[0])?new qt(t.context,t[0]):t[0]:n:t})},Gt("tables()",function(t){var e;if(t){e=qt;var n=this.context;if("number"==typeof t)t=[n[t]];else var r=a.map(n,function(t){return t.nTable}),t=a(r).filter(t).map(function(){var t=a.inArray(this,r);return n[t]}).toArray();e=new e(t)}else e=this;return e}),Gt("table()",function(t){var t=this.tables(t),e=t.context;return e.length?new qt(e[0]):t}),$t("tables().nodes()","table().node()",function(){return this.iterator("table",function(t){return t.nTable},1)}),$t("tables().body()","table().body()",function(){return this.iterator("table",function(t){return t.nTBody},1)}),$t("tables().header()","table().header()",function(){return this.iterator("table",function(t){return t.nTHead},1)}),$t("tables().footer()","table().footer()",function(){return this.iterator("table",function(t){return t.nTFoot},1)}),$t("tables().containers()","table().container()",function(){return this.iterator("table",function(t){return t.nTableWrapper},1)}),Gt("draw()",function(t){return this.iterator("table",function(e){"page"===t?k(e):("string"==typeof t&&(t="full-hold"===t?!1:!0),O(e,!1===t))})}),Gt("page()",function(t){return t===n?this.page.info().page:this.iterator("table",function(e){ct(e,t)})}),Gt("page.info()",function(){if(0===this.context.length)return n;var t=this.context[0],e=t._iDisplayStart,a=t._iDisplayLength,r=t.fnRecordsDisplay(),o=-1===a;return{page:o?0:Math.floor(e/a),pages:o?1:Math.ceil(r/a),start:e,end:t.fnDisplayEnd(),length:a,recordsTotal:t.fnRecordsTotal(),recordsDisplay:r,serverSide:"ssp"===Ut(t)}}),Gt("page.len()",function(t){return t===n?0!==this.context.length?this.context[0]._iDisplayLength:n:this.iterator("table",function(e){st(e,t)})});var ye=function(t,e,n){if(n){var a=new qt(t);a.one("draw",function(){n(a.ajax.json())})}if("ssp"==Ut(t))O(t,e);else{dt(t,!0);var r=t.jqXHR;r&&4!==r.readyState&&r.abort(),E(t,[],function(n){A(t);for(var n=V(t,n),a=0,r=n.length;r>a;a++)D(t,n[a]);O(t,e),dt(t,!1)})}};Gt("ajax.json()",function(){var t=this.context;return 0<t.length?t[0].json:void 0}),Gt("ajax.params()",function(){var t=this.context;return 0<t.length?t[0].oAjaxData:void 0}),Gt("ajax.reload()",function(t,e){return this.iterator("table",function(n){ye(n,!1===e,t)})}),Gt("ajax.url()",function(t){var e=this.context;return t===n?0===e.length?n:(e=e[0],e.ajax?a.isPlainObject(e.ajax)?e.ajax.url:e.ajax:e.sAjaxSource):this.iterator("table",function(e){a.isPlainObject(e.ajax)?e.ajax.url=t:e.ajax=t})}),Gt("ajax.url().load()",function(t,e){return this.iterator("table",function(n){ye(n,!1===e,t)})});var _e=function(t,e,r,o,i){var s,l,u,c,f,d,h=[];for(u=typeof e,e&&"string"!==u&&"function"!==u&&e.length!==n||(e=[e]),u=0,c=e.length;c>u;u++)for(l=e[u]&&e[u].split?e[u].split(","):[e[u]],f=0,d=l.length;d>f;f++)(s=r("string"==typeof l[f]?a.trim(l[f]):l[f]))&&s.length&&(h=h.concat(s));if(t=Vt.selector[t],t.length)for(u=0,c=t.length;c>u;u++)h=t[u](o,i,h);return fe(h)},Te=function(t){return t||(t={}),t.filter&&t.search===n&&(t.search=t.filter),a.extend({search:"none",order:"current",page:"all"},t)},Ce=function(t){for(var e=0,n=t.length;n>e;e++)if(0<t[e].length)return t[0]=t[e],t[0].length=1,t.length=1,t.context=[t.context[e]],t;return t.length=0,t},we=function(t,e){var n,r,o,i=[],s=t.aiDisplay;n=t.aiDisplayMaster;var l=e.search;if(r=e.order,o=e.page,"ssp"==Ut(t))return"removed"===l?[]:ue(0,n.length);if("current"==o)for(n=t._iDisplayStart,r=t.fnDisplayEnd();r>n;n++)i.push(s[n]);else if("current"==r||"applied"==r)i="none"==l?n.slice():"applied"==l?s.slice():a.map(n,function(t){return-1===a.inArray(t,s)?t:null});else if("index"==r||"original"==r)for(n=0,r=t.aoData.length;r>n;n++)"none"==l?i.push(n):(o=a.inArray(n,s),(-1===o&&"removed"==l||o>=0&&"applied"==l)&&i.push(n));return i};Gt("rows()",function(t,e){t===n?t="":a.isPlainObject(t)&&(e=t,t="");var e=Te(e),r=this.iterator("table",function(r){var o=e;return _e("row",t,function(t){var e=ae(t);if(null!==e&&!o)return[e];var i=we(r,o);return null!==e&&-1!==a.inArray(e,i)?[e]:t?"function"==typeof t?a.map(i,function(e){var n=r.aoData[e];return t(e,n._aData,n.nTr)?e:null}):(e=ce(le(r.aoData,i,"nTr")),t.nodeName&&-1!==a.inArray(t,e)?[t._DT_RowIndex]:"string"==typeof t&&"#"===t.charAt(0)&&(i=r.aIds[t.replace(/^#/,"")],i!==n)?[i.idx]:a(e).filter(t).map(function(){return this._DT_RowIndex}).toArray()):i},r,o)},1);return r.selector.rows=t,r.selector.opts=e,r}),Gt("rows().nodes()",function(){return this.iterator("row",function(t,e){return t.aoData[e].nTr||n},1)}),Gt("rows().data()",function(){return this.iterator(!0,"rows",function(t,e){return le(t.aoData,e,"_aData")},1)}),$t("rows().cache()","row().cache()",function(t){return this.iterator("row",function(e,n){var a=e.aoData[n];return"search"===t?a._aFilterData:a._aSortData},1)}),$t("rows().invalidate()","row().invalidate()",function(t){return this.iterator("row",function(e,n){L(e,n,t)})}),$t("rows().indexes()","row().index()",function(){return this.iterator("row",function(t,e){return e},1)}),$t("rows().ids()","row().id()",function(t){for(var e=[],n=this.context,a=0,r=n.length;r>a;a++)for(var o=0,i=this[a].length;i>o;o++){var s=n[a].rowIdFn(n[a].aoData[this[a][o]]._aData);e.push((!0===t?"#":"")+s)}return new qt(n,e)}),$t("rows().remove()","row().remove()",function(){var t=this;return this.iterator("row",function(e,a,r){var o=e.aoData,i=o[a];o.splice(a,1);for(var s=0,l=o.length;l>s;s++)null!==o[s].nTr&&(o[s].nTr._DT_RowIndex=s);F(e.aiDisplayMaster,a),F(e.aiDisplay,a),F(t[r],a,!1),Mt(e),a=e.rowIdFn(i._aData),a!==n&&delete e.aIds[a]}),this.iterator("table",function(t){for(var e=0,n=t.aoData.length;n>e;e++)t.aoData[e].idx=e}),this}),Gt("rows.add()",function(t){var e=this.iterator("table",function(e){var n,a,r,o=[];for(a=0,r=t.length;r>a;a++)n=t[a],n.nodeName&&"TR"===n.nodeName.toUpperCase()?o.push(y(e,n)[0]):o.push(D(e,n));return o},1),n=this.rows(-1);return n.pop(),a.merge(n,e),n}),Gt("row()",function(t,e){return Ce(this.rows(t,e))}),Gt("row().data()",function(t){var e=this.context;return t===n?e.length&&this.length?e[0].aoData[this[0]]._aData:n:(e[0].aoData[this[0]]._aData=t,L(e[0],this[0],"data"),this)}),Gt("row().node()",function(){var t=this.context;return t.length&&this.length?t[0].aoData[this[0]].nTr||null:null}),Gt("row.add()",function(t){t instanceof a&&t.length&&(t=t[0]);var e=this.iterator("table",function(e){return t.nodeName&&"TR"===t.nodeName.toUpperCase()?y(e,t)[0]:D(e,t)});return this.row(e[0])});var xe=function(t,e){var a=t.context;a.length&&(a=a[0].aoData[e!==n?e:t[0]])&&a._details&&(a._details.remove(),a._detailsShow=n,a._details=n)},Ie=function(t,e){var n=t.context;if(n.length&&t.length){var a=n[0].aoData[t[0]];if(a._details){(a._detailsShow=e)?a._details.insertAfter(a.nTr):a._details.detach();var r=n[0],o=new qt(r),i=r.aoData;o.off("draw.dt.DT_details column-visibility.dt.DT_details destroy.dt.DT_details"),0<se(i,"_details").length&&(o.on("draw.dt.DT_details",function(t,e){r===e&&o.rows({page:"current"}).eq(0).each(function(t){t=i[t],t._detailsShow&&t._details.insertAfter(t.nTr)})}),o.on("column-visibility.dt.DT_details",function(t,e){if(r===e)for(var n,a=b(e),o=0,s=i.length;s>o;o++)n=i[o],n._details&&n._details.children("td[colspan]").attr("colspan",a)}),o.on("destroy.dt.DT_details",function(t,e){if(r===e)for(var n=0,a=i.length;a>n;n++)i[n]._details&&xe(o,n)}))}}};Gt("row().child()",function(t,e){var r=this.context;if(t===n)return r.length&&this.length?r[0].aoData[this[0]]._details:n;if(!0===t)this.child.show();else if(!1===t)xe(this);else if(r.length&&this.length){var o=r[0],r=r[0].aoData[this[0]],i=[],s=function(t,e){if(a.isArray(t)||t instanceof a)for(var n=0,r=t.length;r>n;n++)s(t[n],e);else t.nodeName&&"tr"===t.nodeName.toLowerCase()?i.push(t):(n=a("<tr><td/></tr>").addClass(e),a("td",n).addClass(e).html(t)[0].colSpan=b(o),i.push(n[0]))};s(t,e),r._details&&r._details.remove(),r._details=a(i),r._detailsShow&&r._details.insertAfter(r.nTr)}return this}),Gt(["row().child.show()","row().child().show()"],function(){return Ie(this,!0),this}),Gt(["row().child.hide()","row().child().hide()"],function(){return Ie(this,!1),this}),Gt(["row().child.remove()","row().child().remove()"],function(){return xe(this),this}),Gt("row().child.isShown()",function(){var t=this.context;return t.length&&this.length?t[0].aoData[this[0]]._detailsShow||!1:!1});var Ae=/^(.+):(name|visIdx|visible)$/,Fe=function(t,e,n,a,r){for(var n=[],a=0,o=r.length;o>a;a++)n.push(_(t,r[a],e));return n};Gt("columns()",function(t,e){t===n?t="":a.isPlainObject(t)&&(e=t,t="");var e=Te(e),r=this.iterator("table",function(n){var r=t,o=e,i=n.aoColumns,s=se(i,"sName"),l=se(i,"nTh");return _e("column",r,function(t){var e=ae(t);if(""===t)return ue(i.length);if(null!==e)return[e>=0?e:i.length+e];if("function"==typeof t){var r=we(n,o);return a.map(i,function(e,a){return t(a,Fe(n,a,0,0,r),l[a])?a:null})}var u="string"==typeof t?t.match(Ae):"";if(!u)return a(l).filter(t).map(function(){return a.inArray(this,l)}).toArray();switch(u[2]){case"visIdx":case"visible":if(e=parseInt(u[1],10),0>e){var c=a.map(i,function(t,e){return t.bVisible?e:null});return[c[c.length+e]]}return[p(n,e)];case"name":return a.map(s,function(t,e){return t===u[1]?e:null})}},n,o)},1);return r.selector.cols=t,r.selector.opts=e,r}),$t("columns().header()","column().header()",function(){return this.iterator("column",function(t,e){return t.aoColumns[e].nTh},1)}),$t("columns().footer()","column().footer()",function(){return this.iterator("column",function(t,e){return t.aoColumns[e].nTf},1)}),$t("columns().data()","column().data()",function(){return this.iterator("column-rows",Fe,1)}),$t("columns().dataSrc()","column().dataSrc()",function(){return this.iterator("column",function(t,e){return t.aoColumns[e].mData},1)}),$t("columns().cache()","column().cache()",function(t){return this.iterator("column-rows",function(e,n,a,r,o){return le(e.aoData,o,"search"===t?"_aFilterData":"_aSortData",n)},1)}),$t("columns().nodes()","column().nodes()",function(){return this.iterator("column-rows",function(t,e,n,a,r){return le(t.aoData,r,"anCells",e)},1)}),$t("columns().visible()","column().visible()",function(t,e){return this.iterator("column",function(r,o){if(t===n)return r.aoColumns[o].bVisible;var i,s,l,u=r.aoColumns,c=u[o],f=r.aoData;if(t!==n&&c.bVisible!==t){if(t){var d=a.inArray(!0,se(u,"bVisible"),o+1);for(i=0,s=f.length;s>i;i++)l=f[i].nTr,u=f[i].anCells,l&&l.insertBefore(u[o],u[d]||null)}else a(se(r.aoData,"anCells",o)).detach();c.bVisible=t,N(r,r.aoHeader),N(r,r.aoFooter),(e===n||e)&&(h(r),(r.oScroll.sX||r.oScroll.sY)&&pt(r)),Ot(r,null,"column-visibility",[r,o,t]),Ft(r)}})}),$t("columns().indexes()","column().index()",function(t){return this.iterator("column",function(e,n){return"visible"===t?g(e,n):n},1)}),Gt("columns.adjust()",function(){return this.iterator("table",function(t){h(t)},1)}),Gt("column.index()",function(t,e){if(0!==this.context.length){var n=this.context[0];if("fromVisible"===t||"toData"===t)return p(n,e);if("fromData"===t||"toVisible"===t)return g(n,e)}}),Gt("column()",function(t,e){return Ce(this.columns(t,e))}),Gt("cells()",function(t,e,r){if(a.isPlainObject(t)&&(t.row===n?(r=t,t=null):(r=e,e=null)),a.isPlainObject(e)&&(r=e,e=null),null===e||e===n)return this.iterator("table",function(e){var o,i,s,l,u,c,f,d=t,h=Te(r),p=e.aoData,g=we(e,h),b=ce(le(p,g,"anCells")),S=a([].concat.apply([],b)),v=e.aoColumns.length;return _e("cell",d,function(t){var r="function"==typeof t;if(null===t||t===n||r){for(i=[],s=0,l=g.length;l>s;s++)for(o=g[s],u=0;v>u;u++)c={row:o,column:u},r?(f=p[o],t(c,_(e,o,u),f.anCells?f.anCells[u]:null)&&i.push(c)):i.push(c);return i}return a.isPlainObject(t)?[t]:S.filter(t).map(function(t,e){if(e.parentNode)o=e.parentNode._DT_RowIndex;else for(t=0,l=p.length;l>t;t++)if(-1!==a.inArray(e,p[t].anCells)){o=t;break}return{row:o,column:a.inArray(e,p[o].anCells)}}).toArray()},e,h)});var o,i,s,l,u,c=this.columns(e,r),f=this.rows(t,r),d=this.iterator("table",function(t,e){for(o=[],i=0,s=f[e].length;s>i;i++)for(l=0,u=c[e].length;u>l;l++)o.push({row:f[e][i],column:c[e][l]});return o},1);return a.extend(d.selector,{cols:e,rows:t,opts:r}),d}),$t("cells().nodes()","cell().node()",function(){return this.iterator("cell",function(t,e,a){return(t=t.aoData[e].anCells)?t[a]:n},1)}),Gt("cells().data()",function(){return this.iterator("cell",function(t,e,n){return _(t,e,n)},1)}),$t("cells().cache()","cell().cache()",function(t){return t="search"===t?"_aFilterData":"_aSortData",this.iterator("cell",function(e,n,a){return e.aoData[n][t][a]},1)}),$t("cells().render()","cell().render()",function(t){return this.iterator("cell",function(e,n,a){return _(e,n,a,t)},1)}),$t("cells().indexes()","cell().index()",function(){return this.iterator("cell",function(t,e,n){return{row:e,column:n,columnVisible:g(t,n)}},1)}),$t("cells().invalidate()","cell().invalidate()",function(t){return this.iterator("cell",function(e,n,a){L(e,n,t,a)})}),Gt("cell()",function(t,e,n){return Ce(this.cells(t,e,n))}),Gt("cell().data()",function(t){var e=this.context,a=this[0];return t===n?e.length&&a.length?_(e[0],a[0].row,a[0].column):n:(T(e[0],a[0].row,a[0].column,t),L(e[0],a[0].row,"data",a[0].column),this)}),Gt("order()",function(t,e){var r=this.context;return t===n?0!==r.length?r[0].aaSorting:n:("number"==typeof t?t=[[t,e]]:a.isArray(t[0])||(t=Array.prototype.slice.call(arguments)),this.iterator("table",function(e){e.aaSorting=t.slice()}))}),Gt("order.listener()",function(t,e,n){return this.iterator("table",function(a){xt(a,t,e,n)})}),Gt(["columns().order()","column().order()"],function(t){var e=this;return this.iterator("table",function(n,r){var o=[];a.each(e[r],function(e,n){o.push([n,t])}),n.aaSorting=o})}),Gt("search()",function(t,e,r,o){var i=this.context;return t===n?0!==i.length?i[0].oPreviousSearch.sSearch:n:this.iterator("table",function(n){n.oFeatures.bFilter&&G(n,a.extend({},n.oPreviousSearch,{sSearch:t+"",bRegex:null===e?!1:e,bSmart:null===r?!0:r,bCaseInsensitive:null===o?!0:o}),1)})}),$t("columns().search()","column().search()",function(t,e,r,o){return this.iterator("column",function(i,s){var l=i.aoPreSearchCols;return t===n?l[s].sSearch:void(i.oFeatures.bFilter&&(a.extend(l[s],{sSearch:t+"",bRegex:null===e?!1:e,bSmart:null===r?!0:r,bCaseInsensitive:null===o?!0:o}),G(i,i.oPreviousSearch,1)))})}),Gt("state()",function(){return this.context.length?this.context[0].oSavedState:null}),Gt("state.clear()",function(){return this.iterator("table",function(t){t.fnStateSaveCallback.call(t.oInstance,t,{})})}),Gt("state.loaded()",function(){return this.context.length?this.context[0].oLoadedState:null}),Gt("state.save()",function(){return this.iterator("table",function(t){Ft(t)})}),Xt.versionCheck=Xt.fnVersionCheck=function(t){
for(var e,n,a=Xt.version.split("."),t=t.split("."),r=0,o=t.length;o>r;r++)if(e=parseInt(a[r],10)||0,n=parseInt(t[r],10)||0,e!==n)return e>n;return!0},Xt.isDataTable=Xt.fnIsDataTable=function(t){var e=a(t).get(0),n=!1;return a.each(Xt.settings,function(t,r){var o=r.nScrollHead?a("table",r.nScrollHead)[0]:null,i=r.nScrollFoot?a("table",r.nScrollFoot)[0]:null;(r.nTable===e||o===e||i===e)&&(n=!0)}),n},Xt.tables=Xt.fnTables=function(t){var e=!1;a.isPlainObject(t)&&(e=t.api,t=t.visible);var n=a.map(Xt.settings,function(e){return!t||t&&a(e.nTable).is(":visible")?e.nTable:void 0});return e?new qt(n):n},Xt.util={throttle:St,escapeRegex:Z},Xt.camelToHungarian=o,Gt("$()",function(t,e){var n=this.rows(e).nodes(),n=a(n);return a([].concat(n.filter(t).toArray(),n.find(t).toArray()))}),a.each(["on","one","off"],function(t,e){Gt(e+"()",function(){var t=Array.prototype.slice.call(arguments);t[0].match(/\.dt\b/)||(t[0]+=".dt");var n=a(this.tables().nodes());return n[e].apply(n,t),this})}),Gt("clear()",function(){return this.iterator("table",function(t){A(t)})}),Gt("settings()",function(){return new qt(this.context,this.context)}),Gt("init()",function(){var t=this.context;return t.length?t[0].oInit:null}),Gt("data()",function(){return this.iterator("table",function(t){return se(t.aoData,"_aData")}).flatten()}),Gt("destroy()",function(e){return e=e||!1,this.iterator("table",function(n){var r,o=n.nTableWrapper.parentNode,i=n.oClasses,s=n.nTable,l=n.nTBody,u=n.nTHead,c=n.nTFoot,f=a(s),l=a(l),d=a(n.nTableWrapper),h=a.map(n.aoData,function(t){return t.nTr});n.bDestroying=!0,Ot(n,"aoDestroyCallback","destroy",[n]),e||new qt(n).columns().visible(!0),d.unbind(".DT").find(":not(tbody *)").unbind(".DT"),a(t).unbind(".DT-"+n.sInstance),s!=u.parentNode&&(f.children("thead").detach(),f.append(u)),c&&s!=c.parentNode&&(f.children("tfoot").detach(),f.append(c)),n.aaSorting=[],n.aaSortingFixed=[],It(n),a(h).removeClass(n.asStripeClasses.join(" ")),a("th, td",u).removeClass(i.sSortable+" "+i.sSortableAsc+" "+i.sSortableDesc+" "+i.sSortableNone),n.bJUI&&(a("th span."+i.sSortIcon+", td span."+i.sSortIcon,u).detach(),a("th, td",u).each(function(){var t=a("div."+i.sSortJUIWrapper,this);a(this).append(t.contents()),t.detach()})),l.children().detach(),l.append(h),u=e?"remove":"detach",f[u](),d[u](),!e&&o&&(o.insertBefore(s,n.nTableReinsertBefore),f.css("width",n.sDestroyWidth).removeClass(i.sTable),(r=n.asDestroyStripes.length)&&l.children().each(function(t){a(this).addClass(n.asDestroyStripes[t%r])})),o=a.inArray(n,Xt.settings),-1!==o&&Xt.settings.splice(o,1)})}),a.each(["column","row","cell"],function(t,e){Gt(e+"s().every()",function(t){return this.iterator(e,function(a,r,o,i,s){t.call(new qt(a)[e](r,"cell"===e?o:n),r,o,i,s)})})}),Gt("i18n()",function(t,e,r){var o=this.context[0],t=w(t)(o.oLanguage);return t===n&&(t=e),r!==n&&a.isPlainObject(t)&&(t=t[r]!==n?t[r]:t._),t.replace("%d",r)}),Xt.version="1.10.9",Xt.settings=[],Xt.models={},Xt.models.oSearch={bCaseInsensitive:!0,sSearch:"",bRegex:!1,bSmart:!0},Xt.models.oRow={nTr:null,anCells:null,_aData:[],_aSortData:null,_aFilterData:null,_sFilterRow:null,_sRowStripe:"",src:null,idx:-1},Xt.models.oColumn={idx:null,aDataSort:null,asSorting:null,bSearchable:null,bSortable:null,bVisible:null,_sManualType:null,_bAttrSrc:!1,fnCreatedCell:null,fnGetData:null,fnSetData:null,mData:null,mRender:null,nTh:null,nTf:null,sClass:null,sContentPadding:null,sDefaultContent:null,sName:null,sSortDataType:"std",sSortingClass:null,sSortingClassJUI:null,sTitle:null,sType:null,sWidth:null,sWidthOrig:null},Xt.defaults={aaData:null,aaSorting:[[0,"asc"]],aaSortingFixed:[],ajax:null,aLengthMenu:[10,25,50,100],aoColumns:null,aoColumnDefs:null,aoSearchCols:[],asStripeClasses:null,bAutoWidth:!0,bDeferRender:!1,bDestroy:!1,bFilter:!0,bInfo:!0,bJQueryUI:!1,bLengthChange:!0,bPaginate:!0,bProcessing:!1,bRetrieve:!1,bScrollCollapse:!1,bServerSide:!1,bSort:!0,bSortMulti:!0,bSortCellsTop:!1,bSortClasses:!0,bStateSave:!1,fnCreatedRow:null,fnDrawCallback:null,fnFooterCallback:null,fnFormatNumber:function(t){return t.toString().replace(/\B(?=(\d{3})+(?!\d))/g,this.oLanguage.sThousands)},fnHeaderCallback:null,fnInfoCallback:null,fnInitComplete:null,fnPreDrawCallback:null,fnRowCallback:null,fnServerData:null,fnServerParams:null,fnStateLoadCallback:function(t){try{return JSON.parse((-1===t.iStateDuration?sessionStorage:localStorage).getItem("DataTables_"+t.sInstance+"_"+location.pathname))}catch(e){}},fnStateLoadParams:null,fnStateLoaded:null,fnStateSaveCallback:function(t,e){try{(-1===t.iStateDuration?sessionStorage:localStorage).setItem("DataTables_"+t.sInstance+"_"+location.pathname,JSON.stringify(e))}catch(n){}},fnStateSaveParams:null,iStateDuration:7200,iDeferLoading:null,iDisplayLength:10,iDisplayStart:0,iTabIndex:0,oClasses:{},oLanguage:{oAria:{sSortAscending:": activate to sort column ascending",sSortDescending:": activate to sort column descending"},oPaginate:{sFirst:"First",sLast:"Last",sNext:"Next",sPrevious:"Previous"},sEmptyTable:"No data available in table",sInfo:"Showing _START_ to _END_ of _TOTAL_ entries",sInfoEmpty:"Showing 0 to 0 of 0 entries",sInfoFiltered:"(filtered from _MAX_ total entries)",sInfoPostFix:"",sDecimal:"",sThousands:",",sLengthMenu:"Show _MENU_ entries",sLoadingRecords:"Loading...",sProcessing:"Processing...",sSearch:"Search:",sSearchPlaceholder:"",sUrl:"",sZeroRecords:"No matching records found"},oSearch:a.extend({},Xt.models.oSearch),sAjaxDataProp:"data",sAjaxSource:null,sDom:"lfrtip",searchDelay:null,sPaginationType:"simple_numbers",sScrollX:"",sScrollXInner:"",sScrollY:"",sServerMethod:"GET",renderer:null,rowId:"DT_RowId"},r(Xt.defaults),Xt.defaults.column={aDataSort:null,iDataSort:-1,asSorting:["asc","desc"],bSearchable:!0,bSortable:!0,bVisible:!0,fnCreatedCell:null,mData:null,mRender:null,sCellType:"td",sClass:"",sContentPadding:"",sDefaultContent:null,sName:"",sSortDataType:"std",sTitle:null,sType:null,sWidth:null},r(Xt.defaults.column),Xt.models.oSettings={oFeatures:{bAutoWidth:null,bDeferRender:null,bFilter:null,bInfo:null,bLengthChange:null,bPaginate:null,bProcessing:null,bServerSide:null,bSort:null,bSortMulti:null,bSortClasses:null,bStateSave:null},oScroll:{bCollapse:null,iBarWidth:0,sX:null,sXInner:null,sY:null},oLanguage:{fnInfoCallback:null},oBrowser:{bScrollOversize:!1,bScrollbarLeft:!1,bBounding:!1,barWidth:0},ajax:null,aanFeatures:[],aoData:[],aiDisplay:[],aiDisplayMaster:[],aIds:{},aoColumns:[],aoHeader:[],aoFooter:[],oPreviousSearch:{},aoPreSearchCols:[],aaSorting:null,aaSortingFixed:[],asStripeClasses:null,asDestroyStripes:[],sDestroyWidth:0,aoRowCallback:[],aoHeaderCallback:[],aoFooterCallback:[],aoDrawCallback:[],aoRowCreatedCallback:[],aoPreDrawCallback:[],aoInitComplete:[],aoStateSaveParams:[],aoStateLoadParams:[],aoStateLoaded:[],sTableId:"",nTable:null,nTHead:null,nTFoot:null,nTBody:null,nTableWrapper:null,bDeferLoading:!1,bInitialised:!1,aoOpenRows:[],sDom:null,searchDelay:null,sPaginationType:"two_button",iStateDuration:0,aoStateSave:[],aoStateLoad:[],oSavedState:null,oLoadedState:null,sAjaxSource:null,sAjaxDataProp:null,bAjaxDataGet:!0,jqXHR:null,json:n,oAjaxData:n,fnServerData:null,aoServerParams:[],sServerMethod:null,fnFormatNumber:null,aLengthMenu:null,iDraw:0,bDrawing:!1,iDrawError:-1,_iDisplayLength:10,_iDisplayStart:0,_iRecordsTotal:0,_iRecordsDisplay:0,bJUI:null,oClasses:{},bFiltered:!1,bSorted:!1,bSortCellsTop:null,oInit:null,aoDestroyCallback:[],fnRecordsTotal:function(){return"ssp"==Ut(this)?1*this._iRecordsTotal:this.aiDisplayMaster.length},fnRecordsDisplay:function(){return"ssp"==Ut(this)?1*this._iRecordsDisplay:this.aiDisplay.length},fnDisplayEnd:function(){var t=this._iDisplayLength,e=this._iDisplayStart,n=e+t,a=this.aiDisplay.length,r=this.oFeatures,o=r.bPaginate;return r.bServerSide?!1===o||-1===t?e+a:Math.min(e+t,this._iRecordsDisplay):!o||n>a||-1===t?a:n},oInstance:null,sInstance:null,iTabIndex:0,nScrollHead:null,nScrollFoot:null,aLastSort:[],oPlugins:{},rowIdFn:null,rowId:null},Xt.ext=Vt={buttons:{},classes:{},errMode:"alert",feature:[],search:[],selector:{cell:[],column:[],row:[]},internal:{},legacy:{ajax:null},pager:{},renderer:{pageButton:{},header:{}},order:{},type:{detect:[],search:{},order:{}},_unique:0,fnVersionCheck:Xt.fnVersionCheck,iApiIndex:0,oJUIClasses:{},sVersion:Xt.version},a.extend(Vt,{afnFiltering:Vt.search,aTypes:Vt.type.detect,ofnSearch:Vt.type.search,oSort:Vt.type.order,afnSortData:Vt.order,aoFeatures:Vt.feature,oApi:Vt.internal,oStdClasses:Vt.classes,oPagination:Vt.pager}),a.extend(Xt.ext.classes,{sTable:"dataTable",sNoFooter:"no-footer",sPageButton:"paginate_button",sPageButtonActive:"current",sPageButtonDisabled:"disabled",sStripeOdd:"odd",sStripeEven:"even",sRowEmpty:"dataTables_empty",sWrapper:"dataTables_wrapper",sFilter:"dataTables_filter",sInfo:"dataTables_info",sPaging:"dataTables_paginate paging_",sLength:"dataTables_length",sProcessing:"dataTables_processing",sSortAsc:"sorting_asc",sSortDesc:"sorting_desc",sSortable:"sorting",sSortableAsc:"sorting_asc_disabled",sSortableDesc:"sorting_desc_disabled",sSortableNone:"sorting_disabled",sSortColumn:"sorting_",sFilterInput:"",sLengthSelect:"",sScrollWrapper:"dataTables_scroll",sScrollHead:"dataTables_scrollHead",sScrollHeadInner:"dataTables_scrollHeadInner",sScrollBody:"dataTables_scrollBody",sScrollFoot:"dataTables_scrollFoot",sScrollFootInner:"dataTables_scrollFootInner",sHeaderTH:"",sFooterTH:"",sSortJUIAsc:"",sSortJUIDesc:"",sSortJUI:"",sSortJUIAscAllowed:"",sSortJUIDescAllowed:"",sSortJUIWrapper:"",sSortIcon:"",sJUIHeader:"",sJUIFooter:""});var Le="",Le="",Re=Le+"ui-state-default",Pe=Le+"css_right ui-icon ui-icon-",je=Le+"fg-toolbar ui-toolbar ui-widget-header ui-helper-clearfix";a.extend(Xt.ext.oJUIClasses,Xt.ext.classes,{sPageButton:"fg-button ui-button "+Re,sPageButtonActive:"ui-state-disabled",sPageButtonDisabled:"ui-state-disabled",sPaging:"dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_",sSortAsc:Re+" sorting_asc",sSortDesc:Re+" sorting_desc",sSortable:Re+" sorting",sSortableAsc:Re+" sorting_asc_disabled",sSortableDesc:Re+" sorting_desc_disabled",sSortableNone:Re+" sorting_disabled",sSortJUIAsc:Pe+"triangle-1-n",sSortJUIDesc:Pe+"triangle-1-s",sSortJUI:Pe+"carat-2-n-s",sSortJUIAscAllowed:Pe+"carat-1-n",sSortJUIDescAllowed:Pe+"carat-1-s",sSortJUIWrapper:"DataTables_sort_wrapper",sSortIcon:"DataTables_sort_icon",sScrollHead:"dataTables_scrollHead "+Re,sScrollFoot:"dataTables_scrollFoot "+Re,sHeaderTH:Re,sFooterTH:Re,sJUIHeader:je+" ui-corner-tl ui-corner-tr",sJUIFooter:je+" ui-corner-bl ui-corner-br"});var He=Xt.ext.pager;a.extend(He,{simple:function(){return["previous","next"]},full:function(){return["first","previous","next","last"]},numbers:function(t,e){return[Et(t,e)]},simple_numbers:function(t,e){return["previous",Et(t,e),"next"]},full_numbers:function(t,e){return["first","previous",Et(t,e),"next","last"]},_numbers:Et,numbers_length:7}),a.extend(!0,Xt.ext.renderer,{pageButton:{_:function(t,n,r,o,i,s){var l,u,c,f=t.oClasses,d=t.oLanguage.oPaginate,h=0,p=function(e,n){var o,c,g,b,S=function(e){ct(t,e.data.action,!0)};for(o=0,c=n.length;c>o;o++)if(b=n[o],a.isArray(b))g=a("<"+(b.DT_el||"div")+"/>").appendTo(e),p(g,b);else{switch(l=null,u="",b){case"ellipsis":e.append('<span class="ellipsis">&#x2026;</span>');break;case"first":l=d.sFirst,u=b+(i>0?"":" "+f.sPageButtonDisabled);break;case"previous":l=d.sPrevious,u=b+(i>0?"":" "+f.sPageButtonDisabled);break;case"next":l=d.sNext,u=b+(s-1>i?"":" "+f.sPageButtonDisabled);break;case"last":l=d.sLast,u=b+(s-1>i?"":" "+f.sPageButtonDisabled);break;default:l=b+1,u=i===b?f.sPageButtonActive:""}null!==l&&(g=a("<a>",{"class":f.sPageButton+" "+u,"aria-controls":t.sTableId,"data-dt-idx":h,tabindex:t.iTabIndex,id:0===r&&"string"==typeof b?t.sTableId+"_"+b:null}).html(l).appendTo(e),Nt(g,{action:b},S),h++)}};try{c=a(n).find(e.activeElement).data("dt-idx")}catch(g){}p(a(n).empty(),o),c&&a(n).find("[data-dt-idx="+c+"]").focus()}}}),a.extend(Xt.ext.type.detect,[function(t,e){var n=e.oLanguage.sDecimal;return oe(t,n)?"num"+n:null},function(t){if(t&&!(t instanceof Date)&&(!Zt.test(t)||!Kt.test(t)))return null;var e=Date.parse(t);return null!==e&&!isNaN(e)||ne(t)?"date":null},function(t,e){var n=e.oLanguage.sDecimal;return oe(t,n,!0)?"num-fmt"+n:null},function(t,e){var n=e.oLanguage.sDecimal;return ie(t,n)?"html-num"+n:null},function(t,e){var n=e.oLanguage.sDecimal;return ie(t,n,!0)?"html-num-fmt"+n:null},function(t){return ne(t)||"string"==typeof t&&-1!==t.indexOf("<")?"html":null}]),a.extend(Xt.ext.type.search,{html:function(t){return ne(t)?t:"string"==typeof t?t.replace(Yt," ").replace(Qt,""):""},string:function(t){return ne(t)?t:"string"==typeof t?t.replace(Yt," "):t}});var Ne=function(t,e,n,a){return 0===t||t&&"-"!==t?(e&&(t=re(t,e)),t.replace&&(n&&(t=t.replace(n,"")),a&&(t=t.replace(a,""))),1*t):-(1/0)};return a.extend(Vt.type.order,{"date-pre":function(t){return Date.parse(t)||0},"html-pre":function(t){return ne(t)?"":t.replace?t.replace(/<.*?>/g,"").toLowerCase():t+""},"string-pre":function(t){return ne(t)?"":"string"==typeof t?t.toLowerCase():t.toString?t.toString():""},"string-asc":function(t,e){return e>t?-1:t>e?1:0},"string-desc":function(t,e){return e>t?1:t>e?-1:0}}),Bt(""),a.extend(!0,Xt.ext.renderer,{header:{_:function(t,e,n,r){a(t.nTable).on("order.dt.DT",function(a,o,i,s){t===o&&(a=n.idx,e.removeClass(n.sSortingClass+" "+r.sSortAsc+" "+r.sSortDesc).addClass("asc"==s[a]?r.sSortAsc:"desc"==s[a]?r.sSortDesc:n.sSortingClass))})},jqueryui:function(t,e,n,r){a("<div/>").addClass(r.sSortJUIWrapper).append(e.contents()).append(a("<span/>").addClass(r.sSortIcon+" "+n.sSortingClassJUI)).appendTo(e),a(t.nTable).on("order.dt.DT",function(a,o,i,s){t===o&&(a=n.idx,e.removeClass(r.sSortAsc+" "+r.sSortDesc).addClass("asc"==s[a]?r.sSortAsc:"desc"==s[a]?r.sSortDesc:n.sSortingClass),e.find("span."+r.sSortIcon).removeClass(r.sSortJUIAsc+" "+r.sSortJUIDesc+" "+r.sSortJUI+" "+r.sSortJUIAscAllowed+" "+r.sSortJUIDescAllowed).addClass("asc"==s[a]?r.sSortJUIAsc:"desc"==s[a]?r.sSortJUIDesc:n.sSortingClassJUI))})}}}),Xt.render={number:function(t,e,n,a,r){return{display:function(o){if("number"!=typeof o&&"string"!=typeof o)return o;var i=0>o?"-":"",o=Math.abs(parseFloat(o)),s=parseInt(o,10),o=n?e+(o-s).toFixed(n).substring(2):"";return i+(a||"")+s.toString().replace(/\B(?=(\d{3})+(?!\d))/g,t)+o+(r||"")}}}},a.extend(Xt.ext.internal,{_fnExternApiFunc:Jt,_fnBuildAjax:E,_fnAjaxUpdate:B,_fnAjaxParameters:J,_fnAjaxUpdateDraw:X,_fnAjaxDataSrc:V,_fnAddColumn:f,_fnColumnOptions:d,_fnAdjustColumnSizing:h,_fnVisibleToColumnIndex:p,_fnColumnIndexToVisible:g,_fnVisbleColumns:b,_fnGetColumns:S,_fnColumnTypes:v,_fnApplyColumnDefs:m,_fnHungarianMap:r,_fnCamelToHungarian:o,_fnLanguageCompat:i,_fnBrowserDetect:u,_fnAddData:D,_fnAddTr:y,_fnNodeToDataIndex:function(t,e){return e._DT_RowIndex!==n?e._DT_RowIndex:null},_fnNodeToColumnIndex:function(t,e,n){return a.inArray(n,t.aoData[e].anCells)},_fnGetCellData:_,_fnSetCellData:T,_fnSplitObjNotation:C,_fnGetObjectDataFn:w,_fnSetObjectDataFn:x,_fnGetDataMaster:I,_fnClearTable:A,_fnDeleteIndex:F,_fnInvalidate:L,_fnGetRowElements:R,_fnCreateTr:P,_fnBuildHead:H,_fnDrawHead:N,_fnDraw:k,_fnReDraw:O,_fnAddOptionsHtml:M,_fnDetectHeader:W,_fnGetUniqueThs:U,_fnFeatureHtmlFilter:q,_fnFilterComplete:G,_fnFilterCustom:$,_fnFilterColumn:z,_fnFilter:Y,_fnFilterCreateSearch:Q,_fnEscapeRegex:Z,_fnFilterData:K,_fnFeatureHtmlInfo:nt,_fnUpdateInfo:at,_fnInfoMacros:rt,_fnInitialise:ot,_fnInitComplete:it,_fnLengthChange:st,_fnFeatureHtmlLength:lt,_fnFeatureHtmlPaginate:ut,_fnPageChange:ct,_fnFeatureHtmlProcessing:ft,_fnProcessingDisplay:dt,_fnFeatureHtmlTable:ht,_fnScrollDraw:pt,_fnApplyToChildren:gt,_fnCalculateColumnWidths:bt,_fnThrottle:St,_fnConvertToWidth:vt,_fnGetWidestNode:mt,_fnGetMaxLenString:Dt,_fnStringToCss:yt,_fnSortFlatten:_t,_fnSort:Tt,_fnSortAria:Ct,_fnSortListener:wt,_fnSortAttachListener:xt,_fnSortingClasses:It,_fnSortData:At,_fnSaveState:Ft,_fnLoadState:Lt,_fnSettingsFromNode:Rt,_fnLog:Pt,_fnMap:jt,_fnBindAction:Nt,_fnCallbackReg:kt,_fnCallbackFire:Ot,_fnLengthOverflow:Mt,_fnRenderer:Wt,_fnDataSource:Ut,_fnRowAttributes:j,_fnCalculateEnd:function(){}}),a.fn.dataTable=Xt,a.fn.dataTableSettings=Xt.settings,a.fn.dataTableExt=Xt.ext,a.fn.DataTable=function(t){return a(this).dataTable(t).api()},a.each(Xt,function(t,e){a.fn.DataTable[t]=e}),a.fn.dataTable};"function"==typeof define&&define.amd?define("datatables",["jquery"],a):"object"==typeof exports?module.exports=a(require("jquery")):jQuery&&!jQuery.fn.dataTable&&a(jQuery)}(window,document),jQuery.fn.dataTable.ext.builder="dt/dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-print-1.0.3,cr-1.2.0,e-1.5.1,ef-display-1.0.0,ef-jqdate-1.11.4,fc-3.1.0,fh-3.0.0,kt-2.0.0,r-1.0.7,rr-1.0.0,sc-1.3.0,se-1.0.1";


/*! AutoFill 2.0.0 *//*
!function(t,e,o){var l=function(l,i){var n=0,s=function(t,e){if(!i.versionCheck||!i.versionCheck("1.10.8"))throw"Warning: AutoFill requires DataTables 1.10.8 or greater";this.c=l.extend(!0,{},i.defaults.autoFill,s.defaults,e),this.s={dt:new i.Api(t),namespace:".autoFill"+n++,scroll:{},scrollInterval:null},this.dom={handle:l('<div class="dt-autofill-handle"/>'),select:{top:l('<div class="dt-autofill-select top"/>'),right:l('<div class="dt-autofill-select right"/>'),bottom:l('<div class="dt-autofill-select bottom"/>'),left:l('<div class="dt-autofill-select left"/>')},background:l('<div class="dt-autofill-background"/>'),list:l('<div class="dt-autofill-list">'+this.s.dt.i18n("autoFill.info","")+"<ul/></div>"),dtScroll:null},this._constructor()};return s.prototype={_constructor:function(){var t=this,o=this.s.dt,i=l("div.dataTables_scrollBody",this.s.dt.table().container());i.length&&(this.dom.dtScroll=i,"static"===i.css("position")&&i.css("position","relative")),this._focusListener(),this.dom.handle.on("mousedown",function(e){return t._mousedown(e),!1}),o.on("destroy.autoFill",function(){o.off(".autoFill"),l(o.table().body()).off(t.s.namespace),l(e.body).off(t.s.namespace)})},_attach:function(t){var e=this.s.dt,o=e.cell(t).index();o&&-1!==e.columns(this.c.columns).indexes().indexOf(o.column)?(this.dom.attachedTo=t,this.dom.handle.appendTo(t)):this._detach()},_actionSelector:function(t){var e=this,o=this.s.dt,i=s.actions,n=[];if(l.each(i,function(e,l){l.available(o,t)&&n.push(e)}),1===n.length&&!1===this.c.alwaysAsk){var a=i[n[0]].execute(o,t);this._update(a,t)}else{var r=this.dom.list.children("ul").empty();n.push("cancel"),l.each(n,function(n,a){r.append(l("<li/>").append('<div class="dt-autofill-question">'+i[a].option(o,t)+"<div>").append(l('<div class="dt-autofill-button">').append(l('<button class="'+s.classes.btn+'">'+o.i18n("autoFill.button","&gt;")+"</button>").on("click",function(){var n=i[a].execute(o,t,l(this).closest("li"));e._update(n,t),e.dom.background.remove(),e.dom.list.remove()}))))}),this.dom.background.appendTo("body"),this.dom.list.appendTo("body"),this.dom.list.css("margin-top",-1*(this.dom.list.outerHeight()/2))}},_detach:function(){this.dom.attachedTo=null,this.dom.handle.detach()},_drawSelection:function(t){var e=this.s.dt,o=this.s.start,i=l(this.dom.start),n=l(t),s={row:n.parent().index(),column:n.index()};if(e.cell(n).any()&&-1!==e.columns(this.c.columns).indexes().indexOf(s.column)){this.s.end=s;var a,e=o.row<s.row?i:n;a=o.row<s.row?n:i,t=o.column<s.column?i:n,i=o.column<s.column?n:i,e=e.position().top,t=t.position().left,o=a.position().top+a.outerHeight()-e,i=i.position().left+i.outerWidth()-t,(n=this.dom.dtScroll)&&(e+=n.scrollTop(),t+=n.scrollLeft()),n=this.dom.select,n.top.css({top:e,left:t,width:i}),n.left.css({top:e,left:t,height:o}),n.bottom.css({top:e+o,left:t,width:i}),n.right.css({top:e,left:t+i,height:o})}},_editor:function(t){var e=this.s.dt,l=this.c.editor;if(l){for(var i={},n=[],s=0,a=t.length;a>s;s++)for(var r=0,d=t[s].length;d>r;r++){var c=t[s][r],u=e.settings()[0].aoColumns[c.index.column],u=u.editField!==o?u.editField:u.mData;if(!u)throw"Could not automatically determine field name. Please see https://datatables.net/tn/11";i[u]||(i[u]={});var h=e.row(c.index.row).id();i[u][h]=c.set,n.push(c.index)}l.bubble(n,!1).multiSet(i).submit()}},_emitEvent:function(t,e){this.s.dt.iterator("table",function(o){l(o.nTable).triggerHandler(t+".dt",e)})},_focusListener:function(){var t=this,o=this.s.dt,i=this.s.namespace,n=null!==this.c.focus?this.c.focus:o.settings()[0].keytable?"focus":"hover";"focus"===n?o.on("key-focus.autoFill",function(e,o,l){t._attach(l.node())}).on("key-blur.autoFill",function(){t._detach()}):"click"===n?(l(o.table().body()).on("click"+i,"td, th",function(){t._attach(this)}),l(e.body).on("click"+i,function(e){l(e.target).parents().filter(o.table().body()).length||t._detach()})):l(o.table().body()).on("mouseenter"+i,"td, th",function(){t._attach(this)}).on("mouseleave"+i,function(){t._detach()})},_mousedown:function(o){var i=this;this.dom.start=this.dom.attachedTo,this.s.start={row:l(this.dom.start).parent().index(),column:l(this.dom.start).index()},l(e.body).on("mousemove.autoFill",function(t){i._mousemove(t)}).on("mouseup.autoFill",function(t){i._mouseup(t)});var n=this.dom.select,s=l(this.s.dt.table().body()).offsetParent();n.top.appendTo(s),n.left.appendTo(s),n.right.appendTo(s),n.bottom.appendTo(s),this._drawSelection(this.dom.start,o),this.dom.handle.css("display","none"),o=this.dom.dtScroll,this.s.scroll={windowHeight:l(t).height(),windowWidth:l(t).width(),dtTop:o?o.offset().top:null,dtLeft:o?o.offset().left:null,dtHeight:o?o.outerHeight():null,dtWidth:o?o.outerWidth():null}},_mousemove:function(t){var e=t.target.nodeName.toLowerCase();"td"!==e&&"th"!==e||(this._drawSelection(t.target,t),this._shiftScroll(t))},_mouseup:function(){l(e.body).off(".autoFill");var t=this.s.dt,o=this.dom.select;o.top.remove(),o.left.remove(),o.right.remove(),o.bottom.remove(),this.dom.handle.css("display","block");var o=this.s.start,i=this.s.end;if(o.row!==i.row||o.column!==i.column){for(var n=this._range(o.row,i.row),o=this._range(o.column,i.column),i=[],s=0;s<n.length;s++)i.push(l.map(o,function(e){return e=t.cell(":eq("+n[s]+")",e+":visible",{page:"current"}),{cell:e,data:e.data(),index:e.index()}}));this._actionSelector(i)}},_range:function(t,e){var o,l=[];if(e>=t)for(o=t;e>=o;o++)l.push(o);else for(o=t;o>=e;o--)l.push(o);return l},_shiftScroll:function(t){var o,l,i,n,s=this,a=this.s.scroll,r=!1,d=t.pageY-e.body.scrollTop,c=t.pageX-e.body.scrollLeft;65>d?o=-5:d>a.windowHeight-65&&(o=5),65>c?l=-5:c>a.windowWidth-65&&(l=5),null!==a.dtTop&&t.pageY<a.dtTop+65?i=-5:null!==a.dtTop&&t.pageY>a.dtTop+a.dtHeight-65&&(i=5),null!==a.dtLeft&&t.pageX<a.dtLeft+65?n=-5:null!==a.dtLeft&&t.pageX>a.dtLeft+a.dtWidth-65&&(n=5),o||l||i||n?(a.windowVert=o,a.windowHoriz=l,a.dtVert=i,a.dtHoriz=n,r=!0):this.s.scrollInterval&&(clearInterval(this.s.scrollInterval),this.s.scrollInterval=null),!this.s.scrollInterval&&r&&(this.s.scrollInterval=setInterval(function(){if(a.windowVert&&(e.body.scrollTop=e.body.scrollTop+a.windowVert),a.windowHoriz&&(e.body.scrollLeft=e.body.scrollLeft+a.windowHoriz),a.dtVert||a.dtHoriz){var t=s.dom.dtScroll[0];a.dtVert&&(t.scrollTop=t.scrollTop+a.dtVert),a.dtHoriz&&(t.scrollLeft=t.scrollLeft+a.dtHoriz)}},20))},_update:function(t,e){if(!1!==t){var o,l=this.s.dt;if(this._emitEvent("preAutoFill",[l,e]),this._editor(e),null!==this.c.update?this.c.update:!this.c.editor){for(var i=0,n=e.length;n>i;i++)for(var s=0,a=e[i].length;a>s;s++)o=e[i][s],o.cell.data(o.set);l.draw(!1)}this._emitEvent("autoFill",[l,e])}}},s.actions={increment:{available:function(t,e){return l.isNumeric(e[0][0].data)},option:function(t){return t.i18n("autoFill.increment",'Increment / decrement each cell by: <input type="number" value="1">')},execute:function(t,e,o){for(var t=1*e[0][0].data,o=1*l("input",o).val(),i=0,n=e.length;n>i;i++)for(var s=0,a=e[i].length;a>s;s++)e[i][s].set=t,t+=o}},fill:{available:function(){return!0},option:function(t,e){return t.i18n("autoFill.fill","Fill all cells with <i>"+e[0][0].data+"</i>")},execute:function(t,e){for(var o=e[0][0].data,l=0,i=e.length;i>l;l++)for(var n=0,s=e[l].length;s>n;n++)e[l][n].set=o}},fillHorizontal:{available:function(t,e){return 1<e.length&&1<e[0].length},option:function(t){return t.i18n("autoFill.fillHorizontal","Fill cells horizontally")},execute:function(t,e){for(var o=0,l=e.length;l>o;o++)for(var i=0,n=e[o].length;n>i;i++)e[o][i].set=e[o][0].data}},fillVertical:{available:function(t,e){return 1<e.length&&1<e[0].length},option:function(t){return t.i18n("autoFill.fillVertical","Fill cells vertically")},execute:function(t,e){for(var o=0,l=e.length;l>o;o++)for(var i=0,n=e[o].length;n>i;i++)e[o][i].set=e[0][i].data}},cancel:{available:function(){return!1},option:function(t){return t.i18n("autoFill.cancel","Cancel")},execute:function(){return!1}}},s.version="2.0.0",s.defaults={alwaysAsk:!1,focus:null,columns:"",update:null,editor:null},s.classes={btn:"btn"},l(e).on("init.dt.autofill",function(t,e){if("dt"===t.namespace){var o=e.oInit.autoFill,n=i.defaults.autoFill;(o||n)&&(n=l.extend({},o,n),!1!==o&&new s(e,n))}}),i.AutoFill=s,i.AutoFill=s};"function"==typeof define&&define.amd?define(["jquery","datatables"],l):"object"==typeof exports?l(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.AutoFill&&l(jQuery,jQuery.fn.dataTable)}(window,document);
*/


/*! Buttons for DataTables 1.0.3 */
!function(t,n,e){var o=function(o,i){var s=0,r=0,u=i.ext.buttons,a=function(t,n){!0===n&&(n={}),o.isArray(n)&&(n={buttons:n}),this.c=o.extend(!0,{},a.defaults,n),n.buttons&&(this.c.buttons=n.buttons),this.s={dt:new i.Api(t),buttons:[],subButtons:[],listenKeys:"",namespace:"dtb"+s++},this.dom={container:o("<"+this.c.dom.container.tag+"/>").addClass(this.c.dom.container.className)},this._constructor()};o.extend(a.prototype,{action:function(t,n){var o=this._indexToButton(t).conf;return n===e?o.action:(o.action=n,this)},active:function(t,n){return this._indexToButton(t).node.toggleClass(this.c.dom.button.active,n===e?!0:n),this},add:function(t,n){if("string"==typeof t&&-1!==t.indexOf("-")){var e=t.split("-");this.c.buttons[1*e[0]].buttons.splice(1*e[1],0,n)}else this.c.buttons.splice(1*t,0,n);return this.dom.container.empty(),this._buildButtons(this.c.buttons),this},container:function(){return this.dom.container},disable:function(t){return this._indexToButton(t).node.addClass(this.c.dom.button.disabled),this},destroy:function(){o("body").off("keyup."+this.s.namespace);var t,n,e,i=this.s.buttons,s=this.s.subButtons;for(t=0,i=i.length;i>t;t++)for(this.removePrep(t),n=0,e=s[t].length;e>n;n++)this.removePrep(t+"-"+n);for(this.removeCommit(),this.dom.container.remove(),s=this.s.dt.settings()[0],t=0,i=s.length;i>t;t++)if(s.inst===this){s.splice(t,1);break}return this},enable:function(t,n){return!1===n?this.disable(t):(this._indexToButton(t).node.removeClass(this.c.dom.button.disabled),this)},name:function(){return this.c.name},node:function(t){return this._indexToButton(t).node},removeCommit:function(){var t,n,e=this.s.buttons,o=this.s.subButtons;for(t=e.length-1;t>=0;t--)null===e[t]&&(e.splice(t,1),o.splice(t,1),this.c.buttons.splice(t,1));for(t=0,e=o.length;e>t;t++)for(n=o[t].length-1;n>=0;n--)null===o[t][n]&&(o[t].splice(n,1),this.c.buttons[t].buttons.splice(n,1));return this},removePrep:function(t){var n,e=this.s.dt;if("number"==typeof t||-1===t.indexOf("-"))n=this.s.buttons[1*t],n.conf.destroy&&n.conf.destroy.call(e.button(t),e,n,n.conf),n.node.remove(),this._removeKey(n.conf),this.s.buttons[1*t]=null;else{var o=t.split("-");n=this.s.subButtons[1*o[0]][1*o[1]],n.conf.destroy&&n.conf.destroy.call(e.button(t),e,n,n.conf),n.node.remove(),this._removeKey(n.conf),this.s.subButtons[1*o[0]][1*o[1]]=null}return this},text:function(t,n){var o=this._indexToButton(t),i=this.c.dom.buttonLiner.tag,s=this.s.dt,r=function(t){return"function"==typeof t?t(s,o.node,o.conf):t};return n===e?r(o.conf.text):(o.conf.text=n,i?o.node.children(i).html(r(n)):o.node.html(r(n)),this)},toIndex:function(t){var n,e,o,i;o=this.s.buttons;var s=this.s.subButtons;for(n=0,e=o.length;e>n;n++)if(o[n].node[0]===t)return n+"";for(n=0,e=s.length;e>n;n++)for(o=0,i=s[n].length;i>o;o++)if(s[n][o].node[0]===t)return n+"-"+o},_constructor:function(){var t=this,e=this.s.dt,i=e.settings()[0];i._buttons||(i._buttons=[]),i._buttons.push({inst:this,name:this.c.name}),this._buildButtons(this.c.buttons),e.on("destroy",function(){t.destroy()}),o("body").on("keyup."+this.s.namespace,function(e){if(!n.activeElement||n.activeElement===n.body){var o=String.fromCharCode(e.keyCode).toLowerCase();-1!==t.s.listenKeys.toLowerCase().indexOf(o)&&t._keypress(o,e)}})},_addKey:function(t){t.key&&(this.s.listenKeys+=o.isPlainObject(t.key)?t.key.key:t.key)},_buildButtons:function(t,n,i){var s=this.s.dt;n||(n=this.dom.container,this.s.buttons=[],this.s.subButtons=[]);for(var r=0,u=t.length;u>r;r++){var a=this._resolveExtends(t[r]);if(a)if(o.isArray(a))this._buildButtons(a,n,i);else{var c=this._buildButton(a,i!==e?!0:!1);if(c){var l=c.node;n.append(c.inserter),i===e?(this.s.buttons.push({node:l,conf:a,inserter:c.inserter}),this.s.subButtons.push([])):this.s.subButtons[i].push({node:l,conf:a,inserter:c.inserter}),a.buttons&&(c=this.c.dom.collection,a._collection=o("<"+c.tag+"/>").addClass(c.className),this._buildButtons(a.buttons,a._collection,r)),a.init&&a.init.call(s.button(l),s,l,a)}}}},_buildButton:function(t,n){var e=this.c.dom.button,i=this.c.dom.buttonLiner,s=this.c.dom.collection,u=this.s.dt,a=function(n){return"function"==typeof n?n(u,c,t):n};if(n&&s.button&&(e=s.button),n&&s.buttonLiner&&(i=s.buttonLiner),t.available&&!t.available(u,t))return!1;var c=o("<"+e.tag+"/>").addClass(e.className).attr("tabindex",this.s.dt.settings()[0].iTabIndex).attr("aria-controls",this.s.dt.table().node().id).on("click.dtb",function(n){n.preventDefault(),!c.hasClass(e.disabled)&&t.action&&t.action.call(u.button(c),n,u,c,t),c.blur()}).on("keyup.dtb",function(n){13===n.keyCode&&!c.hasClass(e.disabled)&&t.action&&t.action.call(u.button(c),n,u,c,t)});return i.tag?c.append(o("<"+i.tag+"/>").html(a(t.text)).addClass(i.className)):c.html(a(t.text)),!1===t.enabled&&c.addClass(e.disabled),t.className&&c.addClass(t.className),t.namespace||(t.namespace=".dt-button-"+r++),i=(i=this.c.dom.buttonContainer)?o("<"+i.tag+"/>").addClass(i.className).append(c):c,this._addKey(t),{node:c,inserter:i}},_indexToButton:function(t){return"number"==typeof t||-1===t.indexOf("-")?this.s.buttons[1*t]:(t=t.split("-"),this.s.subButtons[1*t[0]][1*t[1]])},_keypress:function(t,n){var e,i,s,r;s=this.s.buttons;var u=this.s.subButtons,a=function(e,i){e.key&&(e.key===t?i.click():!o.isPlainObject(e.key)||e.key.key!==t||e.key.shiftKey&&!n.shiftKey||(!e.key.altKey||n.altKey)&&(!e.key.ctrlKey||n.ctrlKey)&&(!e.key.metaKey||n.metaKey)&&i.click())};for(e=0,i=s.length;i>e;e++)a(s[e].conf,s[e].node);for(e=0,i=u.length;i>e;e++)for(s=0,r=u[e].length;r>s;s++)a(u[e][s].conf,u[e][s].node)},_removeKey:function(t){if(t.key){var n=o.isPlainObject(t.key)?t.key.key:t.key,t=this.s.listenKeys.split(""),n=o.inArray(n,t);t.splice(n,1),this.s.listenKeys=t.join("")}},_resolveExtends:function(t){for(var n,e,i=this.s.dt,s=function(n){for(var e=0;!o.isPlainObject(n)&&!o.isArray(n);){if("function"==typeof n){if(n=n(i,t),!n)return!1}else if("string"==typeof n){if(!u[n])throw"Unknown button type: "+n;n=u[n]}if(e++,e>30)throw"Buttons: Too many iterations"}return o.isArray(n)?n:o.extend({},n)},t=s(t);t&&t.extend;){var r=s(u[t.extend]);if(o.isArray(r))return r;n=r.className,t=o.extend({},r,t),n&&t.className!==n&&(t.className=n+" "+t.className);var a=t.postfixButtons;if(a){for(t.buttons||(t.buttons=[]),n=0,e=a.length;e>n;n++)t.buttons.push(a[n]);t.postfixButtons=null}if(a=t.prefixButtons){for(t.buttons||(t.buttons=[]),n=0,e=a.length;e>n;n++)t.buttons.splice(n,0,a[n]);t.prefixButtons=null}t.extend=r.extend}return t}}),a.background=function(t,n,i){i===e&&(i=400),t?o("<div/>").addClass(n).css("display","none").appendTo("body").fadeIn(i):o("body > div."+n).fadeOut(i,function(){o(this).remove()})},a.instanceSelector=function(t,n){if(!t)return o.map(n,function(t){return t.inst});var e=[],i=o.map(n,function(t){return t.name}),s=function(t){if(o.isArray(t))for(var r=0,u=t.length;u>r;r++)s(t[r]);else"string"==typeof t?-1!==t.indexOf(",")?s(t.split(",")):(t=o.inArray(o.trim(t),i),-1!==t&&e.push(n[t].inst)):"number"==typeof t&&e.push(n[t].inst)};return s(t),e},a.buttonSelector=function(t,n){for(var i=[],s=function(t,n){var r,u,a=[];if(o.each(n.s.buttons,function(t,n){null!==n&&a.push({node:n.node[0],name:n.name})}),o.each(n.s.subButtons,function(t,n){o.each(n,function(t,n){null!==n&&a.push({node:n.node[0],name:n.name})})}),r=o.map(a,function(t){return t.node}),o.isArray(t)||t instanceof o)for(r=0,u=t.length;u>r;r++)s(t[r],n);else if(null===t||t===e||"*"===t)for(r=0,u=a.length;u>r;r++)i.push({inst:n,idx:n.toIndex(a[r].node)});else if("number"==typeof t)i.push({inst:n,idx:t});else if("string"==typeof t)if(-1!==t.indexOf(",")){var c=t.split(",");for(r=0,u=c.length;u>r;r++)s(o.trim(c[r]),n)}else if(t.match(/^\d+(\-\d+)?$/))i.push({inst:n,idx:t});else if(-1!==t.indexOf(":name"))for(c=t.replace(":name",""),r=0,u=a.length;u>r;r++)a[r].name===c&&i.push({inst:n,idx:n.toIndex(a[r].node)});else o(r).filter(t).each(function(){i.push({inst:n,idx:n.toIndex(this)})});else"object"==typeof t&&t.nodeName&&(u=o.inArray(t,r),-1!==u&&i.push({inst:n,idx:n.toIndex(r[u])}))},r=0,u=t.length;u>r;r++)s(n,t[r]);return i},a.defaults={buttons:["copy","excel","csv","pdf","print"],name:"main",tabIndex:0,dom:{container:{tag:"div",className:"dt-buttons"},collection:{tag:"div",className:"dt-button-collection"},button:{tag:"a",className:"dt-button",active:"active",disabled:"disabled"},buttonLiner:{tag:"span",className:""}}},a.version="1.0.3",o.extend(u,{collection:{text:function(t){return t.i18n("buttons.collection","Collection")},className:"buttons-collection",action:function(e,i,s,r){e=s.offset(),i=o(i.table().container()),r._collection.addClass(r.collectionLayout).css("display","none").appendTo("body").fadeIn(r.fade),"absolute"===r._collection.css("position")?(r._collection.css({top:e.top+s.outerHeight(),left:e.left}),s=e.left+r._collection.outerWidth(),i=i.offset().left+i.width(),s>i&&r._collection.css("left",e.left-(s-i))):(e=r._collection.height()/2,e>o(t).height()/2&&(e=o(t).height()/2),r._collection.css("marginTop",-1*e)),r.background&&a.background(!0,r.backgroundClassName,r.fade),setTimeout(function(){o(n).on("click.dtb-collection",function(t){o(t.target).parents().andSelf().filter(r._collection).length||(r._collection.fadeOut(r.fade,function(){r._collection.detach()}),a.background(!1,r.backgroundClassName,r.fade),o(n).off("click.dtb-collection"))})},10)},background:!0,collectionLayout:"",backgroundClassName:"dt-button-background",fade:400},copy:function(t,n){return n.preferHtml&&u.copyHtml5?"copyHtml5":u.copyFlash&&u.copyFlash.available(t,n)?"copyFlash":u.copyHtml5?"copyHtml5":void 0},csv:function(t,n){return u.csvHtml5&&u.csvHtml5.available(t,n)?"csvHtml5":u.csvFlash&&u.csvFlash.available(t,n)?"csvFlash":void 0},excel:function(t,n){return u.excelHtml5&&u.excelHtml5.available(t,n)?"excelHtml5":u.excelFlash&&u.excelFlash.available(t,n)?"excelFlash":void 0},pdf:function(t,n){return u.pdfHtml5&&u.pdfHtml5.available(t,n)?"pdfHtml5":u.pdfFlash&&u.pdfFlash.available(t,n)?"pdfFlash":void 0}}),i.Api.register("buttons()",function(t,n){return n===e&&(n=t,t=e),this.iterator(!0,"table",function(e){return e._buttons?a.buttonSelector(a.instanceSelector(t,e._buttons),n):void 0},!0)}),i.Api.register("button()",function(t,n){var e=this.buttons(t,n);return 1<e.length&&e.splice(1,e.length),e}),i.Api.register(["buttons().active()","button().active()"],function(t){return this.each(function(n){n.inst.active(n.idx,t)})}),i.Api.registerPlural("buttons().action()","button().action()",function(t){return t===e?this.map(function(t){return t.inst.action(t.idx)}):this.each(function(n){n.inst.action(n.idx,t)})}),i.Api.register(["buttons().enable()","button().enable()"],function(t){return this.each(function(n){n.inst.enable(n.idx,t)})}),i.Api.register(["buttons().disable()","button().disable()"],function(){return this.each(function(t){t.inst.disable(t.idx)})}),i.Api.registerPlural("buttons().nodes()","button().node()",function(){var t=o();return o(this.each(function(n){t=t.add(n.inst.node(n.idx))})),t}),i.Api.registerPlural("buttons().text()","button().text()",function(t){return t===e?this.map(function(t){return t.inst.text(t.idx)}):this.each(function(n){n.inst.text(n.idx,t)})}),i.Api.registerPlural("buttons().trigger()","button().trigger()",function(){return this.each(function(t){t.inst.node(t.idx).trigger("click")})}),i.Api.registerPlural("buttons().containers()","buttons().container()",function(){var t=o();return o(this.each(function(n){t=t.add(n.inst.container())})),t}),i.Api.register("button().add()",function(t,n){return 1===this.length&&this[0].inst.add(t,n),this.button(t)}),i.Api.register("buttons().destroy()",function(){return this.pluck("inst").unique().each(function(t){t.destroy()}),this}),i.Api.registerPlural("buttons().remove()","buttons().remove()",function(){return this.each(function(t){t.inst.removePrep(t.idx)}),this.pluck("inst").unique().each(function(t){t.removeCommit()}),this});var c;return i.Api.register("buttons.info()",function(t,n,i){var s=this;return!1===t?(o("#datatables_buttons_info").fadeOut(function(){o(this).remove()}),clearTimeout(c),c=null,this):(c&&clearTimeout(c),o("#datatables_buttons_info").length&&o("#datatables_buttons_info").remove(),o('<div id="datatables_buttons_info" class="dt-button-info"/>').html(t?"<h2>"+t+"</h2>":"").append(o("<div/>")["string"==typeof n?"html":"append"](n)).css("display","none").appendTo("body").fadeIn(),i!==e&&0!==i&&(c=setTimeout(function(){s.buttons.info(!1)},i)),this)}),i.Api.register("buttons.exportData()",function(t){if(this.context.length){for(var n=new i.Api(this.context[0]),e=o.extend(!0,{},{rows:null,columns:"",modifier:{search:"applied",order:"applied"},orthogonal:"display",stripHtml:!0,stripNewlines:!0,trim:!0},t),s=function(t){return"string"!=typeof t?t:(e.stripHtml&&(t=t.replace(/<.*?>/g,"")),e.trim&&(t=t.replace(/^\s+|\s+$/g,"")),e.stripNewlines&&(t=t.replace(/\n/g," ")),t)},t=n.columns(e.columns).indexes().map(function(t){return s(n.column(t).header().innerHTML)}).toArray(),r=n.table().footer()?n.columns(e.columns).indexes().map(function(t){return(t=n.column(t).footer())?s(t.innerHTML):""}).toArray():null,u=n.cells(e.rows,e.columns,e.modifier).render(e.orthogonal).toArray(),a=t.length,c=u.length/a,l=Array(c),d=0,f=0;c>f;f++){for(var h=Array(a),b=0;a>b;b++)h[b]=s(u[d]),d++;l[f]=h}return{header:t,footer:r,body:l}}}),o.fn.dataTable.Buttons=a,o.fn.DataTable.Buttons=a,o(n).on("init.dt.dtb",function(t,n){if("dt"===t.namespace){var e=n.oInit.buttons||i.defaults.buttons;e&&!n._buttons&&new a(n,e).container()}}),i.ext.feature.push({fnInit:function(t){var t=new i.Api(t),n=t.init().buttons;return new a(t,n).container()},cFeature:"B"}),a};"function"==typeof define&&define.amd?define(["jquery","datatables"],o):"object"==typeof exports?o(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.Buttons&&o(jQuery,jQuery.fn.dataTable)}(window,document),function(t,n){t.extend(n.ext.buttons,{colvis:function(t,n){return{extend:"collection",text:function(t){return t.i18n("buttons.colvis","Column visibility")},className:"buttons-colvis",buttons:[{extend:"columnsToggle",columns:n.columns}]}},columnsToggle:function(t,n){return t.columns(n.columns).indexes().map(function(t){return{extend:"columnToggle",columns:t}}).toArray()},columnToggle:function(t,n){return{extend:"columnVisibility",columns:n.columns}},columnsVisibility:function(t,n){return t.columns(n.columns).indexes().map(function(t){return{extend:"columnVisibility",columns:t,visibility:n.visibility}}).toArray()},columnVisibility:{columns:null,text:function(t,n,e){return e._columnText(t,e.columns)},className:"buttons-columnVisibility",action:function(t,n,e,o){t=n.column(o.columns),t.visible(void 0!==o.visibility?o.visibility:!t.visible())},init:function(t,n,e){var o=this,i=t.column(e.columns);t.on("column-visibility.dt"+e.namespace,function(t,n,i,s){i===e.columns&&o.active(s)}).on("column-reorder.dt"+e.namespace,function(){var i=t.column(e.columns);n.text(e._columnText(t,e.columns)),o.active(i.visible())}),this.active(i.visible())},destroy:function(t,n,e){t.off("column-visibility.dt"+e.namespace).off("column-reorder.dt"+e.namespace)},_columnText:function(t,n){var e=t.column(n).index();return t.settings()[0].aoColumns[e].sTitle.replace(/\n/g," ").replace(/<.*?>/g,"").replace(/^\s+|\s+$/g,"")}},colvisRestore:{className:"buttons-colvisRestore",text:function(t){return t.i18n("buttons.colvisRestore","Restore visibility")},init:function(t,n,e){e._visOriginal=t.columns().indexes().map(function(n){return t.column(n).visible()}).toArray()},action:function(t,n,e,o){n.columns().every(function(t){this.visible(o._visOriginal[t])})}},colvisGroup:{className:"buttons-colvisGroup",action:function(t,n,e,o){n.columns(o.show).visible(!0),n.columns(o.hide).visible(!1)},show:[],hide:[]}})}(jQuery,jQuery.fn.dataTable),function(t,n){var e=document.createElement("a");n.ext.buttons.print={className:"buttons-print",text:function(t){return t.i18n("buttons.print","Print")},action:function(n,o,i,s){n=o.buttons.exportData(s.exportOptions),i=function(t,n){for(var e="<tr>",o=0,i=t.length;i>o;o++)e+="<"+n+">"+t[o]+"</"+n+">";return e+"</tr>"},o='<table class="'+o.table().node().className+'">',s.header&&(o+="<thead>"+i(n.header,"th")+"</thead>");for(var o=o+"<tbody>",r=0,u=n.body.length;u>r;r++)o+=i(n.body[r],"td");o+="</tbody>",s.footer&&(o+="<thead>"+i(n.footer,"th")+"</thead>");var a=window.open("",""),n=s.title.replace("*",t("title").text());a.document.close();var c="<title>"+n+"</title>";t("style, link").each(function(){var n,o=c,i=t(this).clone()[0];"link"===i.nodeName.toLowerCase()&&(e.href=i.href,n=e.host,-1===n.indexOf("/")&&0!==e.pathname.indexOf("/")&&(n+="/"),i.href=e.protocol+"//"+n+e.pathname+e.search),c=o+i.outerHTML}),t(a.document.head).html(c),t(a.document.body).html("<h1>"+n+"</h1><div>"+s.message+"</div>"+o),s.customize&&s.customize(a),setTimeout(function(){s.autoPrint&&(a.print(),a.close())},250)},title:"*",message:"",exportOptions:{},header:!0,footer:!1,autoPrint:!0,customize:null}}(jQuery,jQuery.fn.dataTable);


/*! ColReorder 1.2.0 *//*
!function(t,e,o){function s(t){for(var e=[],o=0,s=t.length;s>o;o++)e[t[o]]=o;return e}function n(t,e,o){e=t.splice(e,1)[0],t.splice(o,0,e)}function i(t,e,o){for(var s=[],n=0,i=t.childNodes.length;i>n;n++)1==t.childNodes[n].nodeType&&s.push(t.childNodes[n]);e=s[e],null!==o?t.insertBefore(e,s[o]):t.appendChild(e)}t=function(t,r){t.fn.dataTableExt.oApi.fnColReorder=function(e,o,r){var a,l,d,f,h,u,c=e.aoColumns.length;if(h=function(t,e,o){if(t[e]&&"function"!=typeof t[e]){var s=t[e].split("."),n=s.shift();isNaN(1*n)||(t[e]=o[1*n]+"."+s.join("."))}},o!=r)if(0>o||o>=c)this.oApi._fnLog(e,1,"ColReorder 'from' index is out of bounds: "+o);else if(0>r||r>=c)this.oApi._fnLog(e,1,"ColReorder 'to' index is out of bounds: "+r);else{for(d=[],a=0,l=c;l>a;a++)d[a]=a;n(d,o,r);var m=s(d);for(a=0,l=e.aaSorting.length;l>a;a++)e.aaSorting[a][0]=m[e.aaSorting[a][0]];if(null!==e.aaSortingFixed)for(a=0,l=e.aaSortingFixed.length;l>a;a++)e.aaSortingFixed[a][0]=m[e.aaSortingFixed[a][0]];for(a=0,l=c;l>a;a++){for(u=e.aoColumns[a],d=0,f=u.aDataSort.length;f>d;d++)u.aDataSort[d]=m[u.aDataSort[d]];u.idx=m[u.idx]}for(t.each(e.aLastSort,function(t,o){e.aLastSort[t].src=m[o.src]}),a=0,l=c;l>a;a++)u=e.aoColumns[a],"number"==typeof u.mData?(u.mData=m[u.mData],e.oApi._fnColumnOptions(e,a,{})):t.isPlainObject(u.mData)&&(h(u.mData,"_",m),h(u.mData,"filter",m),h(u.mData,"sort",m),h(u.mData,"type",m),e.oApi._fnColumnOptions(e,a,{}));if(e.aoColumns[o].bVisible){for(d=this.oApi._fnColumnIndexToVisible(e,o),f=null,a=o>r?r:r+1;null===f&&c>a;)f=this.oApi._fnColumnIndexToVisible(e,a),a++;for(h=e.nTHead.getElementsByTagName("tr"),a=0,l=h.length;l>a;a++)i(h[a],d,f);if(null!==e.nTFoot)for(h=e.nTFoot.getElementsByTagName("tr"),a=0,l=h.length;l>a;a++)i(h[a],d,f);for(a=0,l=e.aoData.length;l>a;a++)null!==e.aoData[a].nTr&&i(e.aoData[a].nTr,d,f)}for(n(e.aoColumns,o,r),n(e.aoPreSearchCols,o,r),a=0,l=e.aoData.length;l>a;a++)h=e.aoData[a],h.anCells&&n(h.anCells,o,r),"dom"!==h.src&&t.isArray(h._aData)&&n(h._aData,o,r);for(a=0,l=e.aoHeader.length;l>a;a++)n(e.aoHeader[a],o,r);if(null!==e.aoFooter)for(a=0,l=e.aoFooter.length;l>a;a++)n(e.aoFooter[a],o,r);for(new t.fn.dataTable.Api(e).rows().invalidate(),a=0,l=c;l>a;a++)t(e.aoColumns[a].nTh).off("click.DT"),this.oApi._fnSortAttachListener(e,e.aoColumns[a].nTh,a);t(e.oInstance).trigger("column-reorder.dt",[e,{from:o,to:r,mapping:m,iFrom:o,iTo:r,aiInvertMapping:m}])}};var a=function(e,o){var s=new t.fn.dataTable.Api(e).settings()[0];if(s._colReorder)return s._colReorder;!0===o&&(o={});var n=t.fn.dataTable.camelToHungarian;return n&&(n(a.defaults,a.defaults,!0),n(a.defaults,o||{})),this.s={dt:null,init:t.extend(!0,{},a.defaults,o),fixed:0,fixedRight:0,reorderCallback:null,mouse:{startX:-1,startY:-1,offsetX:-1,offsetY:-1,target:-1,targetIndex:-1,fromIndex:-1},aoTargets:[]},this.dom={drag:null,pointer:null},this.s.dt=s,this.s.dt._colReorder=this,this._fnConstruct(),this};return a.prototype={fnReset:function(){for(var t=[],e=0,o=this.s.dt.aoColumns.length;o>e;e++)t.push(this.s.dt.aoColumns[e]._ColReorder_iOrigCol);return this._fnOrderColumns(t),this},fnGetCurrentOrder:function(){return this.fnOrder()},fnOrder:function(t){if(t===o){for(var t=[],e=0,n=this.s.dt.aoColumns.length;n>e;e++)t.push(this.s.dt.aoColumns[e]._ColReorder_iOrigCol);return t}return this._fnOrderColumns(s(t)),this},_fnConstruct:function(){var e,o=this,n=this.s.dt.aoColumns.length,i=this.s.dt.nTable;for(this.s.init.iFixedColumns&&(this.s.fixed=this.s.init.iFixedColumns),this.s.init.iFixedColumnsLeft&&(this.s.fixed=this.s.init.iFixedColumnsLeft),this.s.fixedRight=this.s.init.iFixedColumnsRight?this.s.init.iFixedColumnsRight:0,this.s.init.fnReorderCallback&&(this.s.reorderCallback=this.s.init.fnReorderCallback),e=0;n>e;e++)e>this.s.fixed-1&&e<n-this.s.fixedRight&&this._fnMouseListener(e,this.s.dt.aoColumns[e].nTh),this.s.dt.aoColumns[e]._ColReorder_iOrigCol=e;this.s.dt.oApi._fnCallbackReg(this.s.dt,"aoStateSaveParams",function(t,e){o._fnStateSave.call(o,e)},"ColReorder_State");var r=null;if(this.s.init.aiOrder&&(r=this.s.init.aiOrder.slice()),this.s.dt.oLoadedState&&"undefined"!=typeof this.s.dt.oLoadedState.ColReorder&&this.s.dt.oLoadedState.ColReorder.length==this.s.dt.aoColumns.length&&(r=this.s.dt.oLoadedState.ColReorder),r)if(o.s.dt._bInitComplete)n=s(r),o._fnOrderColumns.call(o,n);else{var a=!1;t(i).on("draw.dt.colReorder",function(){if(!o.s.dt._bInitComplete&&!a){a=!0;var t=s(r);o._fnOrderColumns.call(o,t)}})}else this._fnSetColumnIndexes();t(i).on("destroy.dt.colReorder",function(){t(i).off("destroy.dt.colReorder draw.dt.colReorder"),t(o.s.dt.nTHead).find("*").off(".ColReorder"),t.each(o.s.dt.aoColumns,function(e,o){t(o.nTh).removeAttr("data-column-index")}),o.s.dt._colReorder=null,o.s=null})},_fnOrderColumns:function(e){if(e.length!=this.s.dt.aoColumns.length)this.s.dt.oInstance.oApi._fnLog(this.s.dt,1,"ColReorder - array reorder does not match known number of columns. Skipping.");else{for(var o=0,s=e.length;s>o;o++){var i=t.inArray(o,e);o!=i&&(n(e,i,o),this.s.dt.oInstance.fnColReorder(i,o))}(""!==this.s.dt.oScroll.sX||""!==this.s.dt.oScroll.sY)&&this.s.dt.oInstance.fnAdjustColumnSizing(!1),this.s.dt.oInstance.oApi._fnSaveState(this.s.dt),this._fnSetColumnIndexes(),null!==this.s.reorderCallback&&this.s.reorderCallback.call(this)}},_fnStateSave:function(e){var o,s,n,i=this.s.dt.aoColumns;if(e.ColReorder=[],e.aaSorting){for(o=0;o<e.aaSorting.length;o++)e.aaSorting[o][0]=i[e.aaSorting[o][0]]._ColReorder_iOrigCol;var r=t.extend(!0,[],e.aoSearchCols);for(o=0,s=i.length;s>o;o++)n=i[o]._ColReorder_iOrigCol,e.aoSearchCols[n]=r[o],e.abVisCols[n]=i[o].bVisible,e.ColReorder.push(n)}else if(e.order){for(o=0;o<e.order.length;o++)e.order[o][0]=i[e.order[o][0]]._ColReorder_iOrigCol;for(r=t.extend(!0,[],e.columns),o=0,s=i.length;s>o;o++)n=i[o]._ColReorder_iOrigCol,e.columns[n]=r[o],e.ColReorder.push(n)}},_fnMouseListener:function(e,o){var s=this;t(o).on("mousedown.ColReorder",function(t){t.preventDefault(),s._fnMouseDown.call(s,t,o)})},_fnMouseDown:function(s,n){var i=this,r=t(s.target).closest("th, td").offset(),a=parseInt(t(n).attr("data-column-index"),10);a!==o&&(this.s.mouse.startX=s.pageX,this.s.mouse.startY=s.pageY,this.s.mouse.offsetX=s.pageX-r.left,this.s.mouse.offsetY=s.pageY-r.top,this.s.mouse.target=this.s.dt.aoColumns[a].nTh,this.s.mouse.targetIndex=a,this.s.mouse.fromIndex=a,this._fnRegions(),t(e).on("mousemove.ColReorder",function(t){i._fnMouseMove.call(i,t)}).on("mouseup.ColReorder",function(t){i._fnMouseUp.call(i,t)}))},_fnMouseMove:function(t){if(null===this.dom.drag){if(5>Math.pow(Math.pow(t.pageX-this.s.mouse.startX,2)+Math.pow(t.pageY-this.s.mouse.startY,2),.5))return;this._fnCreateDragNode()}this.dom.drag.css({left:t.pageX-this.s.mouse.offsetX,top:t.pageY-this.s.mouse.offsetY});for(var e=!1,o=this.s.mouse.toIndex,s=1,n=this.s.aoTargets.length;n>s;s++)if(t.pageX<this.s.aoTargets[s-1].x+(this.s.aoTargets[s].x-this.s.aoTargets[s-1].x)/2){this.dom.pointer.css("left",this.s.aoTargets[s-1].x),this.s.mouse.toIndex=this.s.aoTargets[s-1].to,e=!0;break}e||(this.dom.pointer.css("left",this.s.aoTargets[this.s.aoTargets.length-1].x),this.s.mouse.toIndex=this.s.aoTargets[this.s.aoTargets.length-1].to),this.s.init.bRealtime&&o!==this.s.mouse.toIndex&&(this.s.dt.oInstance.fnColReorder(this.s.mouse.fromIndex,this.s.mouse.toIndex),this.s.mouse.fromIndex=this.s.mouse.toIndex,this._fnRegions())},_fnMouseUp:function(){t(e).off("mousemove.ColReorder mouseup.ColReorder"),null!==this.dom.drag&&(this.dom.drag.remove(),this.dom.pointer.remove(),this.dom.drag=null,this.dom.pointer=null,this.s.dt.oInstance.fnColReorder(this.s.mouse.fromIndex,this.s.mouse.toIndex),this._fnSetColumnIndexes(),(""!==this.s.dt.oScroll.sX||""!==this.s.dt.oScroll.sY)&&this.s.dt.oInstance.fnAdjustColumnSizing(!1),this.s.dt.oInstance.oApi._fnSaveState(this.s.dt),null!==this.s.reorderCallback&&this.s.reorderCallback.call(this))},_fnRegions:function(){var e=this.s.dt.aoColumns;this.s.aoTargets.splice(0,this.s.aoTargets.length),this.s.aoTargets.push({x:t(this.s.dt.nTable).offset().left,to:0});for(var o=0,s=0,n=e.length;n>s;s++)s!=this.s.mouse.fromIndex&&o++,e[s].bVisible&&this.s.aoTargets.push({x:t(e[s].nTh).offset().left+t(e[s].nTh).outerWidth(),to:o});0!==this.s.fixedRight&&this.s.aoTargets.splice(this.s.aoTargets.length-this.s.fixedRight),0!==this.s.fixed&&this.s.aoTargets.splice(0,this.s.fixed)},_fnCreateDragNode:function(){var e=""!==this.s.dt.oScroll.sX||""!==this.s.dt.oScroll.sY,o=this.s.dt.aoColumns[this.s.mouse.targetIndex].nTh,s=o.parentNode,n=s.parentNode,i=n.parentNode,r=t(o).clone();this.dom.drag=t(i.cloneNode(!1)).addClass("DTCR_clonedTable").append(t(n.cloneNode(!1)).append(t(s.cloneNode(!1)).append(r[0]))).css({position:"absolute",top:0,left:0,width:t(o).outerWidth(),height:t(o).outerHeight()}).appendTo("body"),this.dom.pointer=t("<div></div>").addClass("DTCR_pointer").css({position:"absolute",top:e?t("div.dataTables_scroll",this.s.dt.nTableWrapper).offset().top:t(this.s.dt.nTable).offset().top,height:e?t("div.dataTables_scroll",this.s.dt.nTableWrapper).height():t(this.s.dt.nTable).height()}).appendTo("body")},_fnSetColumnIndexes:function(){t.each(this.s.dt.aoColumns,function(e,o){t(o.nTh).attr("data-column-index",e)})}},a.defaults={aiOrder:null,bRealtime:!0,iFixedColumnsLeft:0,iFixedColumnsRight:0,fnReorderCallback:null},a.version="1.2.0",t.fn.dataTable.ColReorder=a,t.fn.DataTable.ColReorder=a,"function"==typeof t.fn.dataTable&&"function"==typeof t.fn.dataTableExt.fnVersionCheck&&t.fn.dataTableExt.fnVersionCheck("1.10.8")?t.fn.dataTableExt.aoFeatures.push({fnInit:function(t){var e=t.oInstance;return t._colReorder?e.oApi._fnLog(t,1,"ColReorder attempted to initialise twice. Ignoring second"):(e=t.oInit,new a(t,e.colReorder||e.oColReorder||{})),null},cFeature:"R",sFeature:"ColReorder"}):alert("Warning: ColReorder requires DataTables 1.10.8 or greater - www.datatables.net/download"),t(e).on("preInit.dt.colReorder",function(e,o){if("dt"===e.namespace){var s=o.oInit.colReorder,n=r.defaults.colReorder;(s||n)&&(n=t.extend({},s,n),!1!==s&&new a(o,n))}}),t.fn.dataTable.Api.register("colReorder.reset()",function(){return this.iterator("table",function(t){t._colReorder.fnReset()})}),t.fn.dataTable.Api.register("colReorder.order()",function(t){return t?this.iterator("table",function(e){e._colReorder.fnOrder(t)}):this.context.length?this.context[0]._colReorder.fnOrder():null}),a},"function"==typeof define&&define.amd?define(["jquery","datatables"],t):"object"==typeof exports?t(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.ColReorder&&t(jQuery,jQuery.fn.dataTable)}(window,document);
*/


/*! DataTables Editor v1.5.1 */
!function(t,e,i){var n=function(n,o){function s(t){return t=t.context[0],t.oInit.editor||t._editor}function a(t,e,n,o){return e||(e={}),e.buttons===i&&(e.buttons="_basic"),e.title===i&&(e.title=t.i18n[n].title),e.message===i&&("remove"===n?(t=t.i18n[n].confirm,e.message=1!==o?t._.replace(/%d/,o):t[1]):e.message=""),e}if(!o||!o.versionCheck||!o.versionCheck("1.10"))throw"Editor requires DataTables 1.10 or newer";var r=function(t){!this instanceof r&&alert("DataTables Editor must be initialised as a 'new' instance'"),this._constructor(t)};o.Editor=r,n.fn.DataTable.Editor=r;var l=function(t,o){return o===i&&(o=e),n('*[data-dte-e="'+t+'"]',o)},d=0,u=function(t,e){var i=[];return n.each(t,function(t,n){i.push(n[e])}),i};r.Field=function(t,e,s){var a=this,d=s.i18n.multi,t=n.extend(!0,{},r.Field.defaults,t);this.s=n.extend({},r.Field.settings,{type:r.fieldTypes[t.type],name:t.name,classes:e,host:s,opts:t,multiValue:!1}),t.id||(t.id="DTE_Field_"+t.name),t.dataProp&&(t.data=t.dataProp),""===t.data&&(t.data=t.name);var u=o.ext.oApi;this.valFromData=function(e){return u._fnGetObjectDataFn(t.data)(e,"editor")},this.valToData=u._fnSetObjectDataFn(t.data),e=n('<div class="'+e.wrapper+" "+e.typePrefix+t.type+" "+e.namePrefix+t.name+" "+t.className+'"><label data-dte-e="label" class="'+e.label+'" for="'+t.id+'">'+t.label+'<div data-dte-e="msg-label" class="'+e["msg-label"]+'">'+t.labelInfo+'</div></label><div data-dte-e="input" class="'+e.input+'"><div data-dte-e="input-control" class="'+e.inputControl+'"/><div data-dte-e="multi-value" class="'+e.multiValue+'">'+d.title+'<span data-dte-e="multi-info" class="'+e.multiInfo+'">'+d.info+'</span></div><div data-dte-e="msg-multi" class="'+e.multiRestore+'">'+d.restore+'</div><div data-dte-e="msg-error" class="'+e["msg-error"]+'"></div><div data-dte-e="msg-message" class="'+e["msg-message"]+'"></div><div data-dte-e="msg-info" class="'+e["msg-info"]+'">'+t.fieldInfo+"</div></div></div>"),s=this._typeFn("create",t),null!==s?l("input-control",e).prepend(s):e.css("display","none"),this.dom=n.extend(!0,{},r.Field.models.dom,{container:e,inputControl:l("input-control",e),label:l("label",e),fieldInfo:l("msg-info",e),labelInfo:l("msg-label",e),fieldError:l("msg-error",e),fieldMessage:l("msg-message",e),multi:l("multi-value",e),multiReturn:l("msg-multi",e),multiInfo:l("multi-info",e)}),this.dom.multi.on("click",function(){a.val("")}),this.dom.multiReturn.on("click",function(){a.s.multiValue=!0,a._multiValueCheck()}),n.each(this.s.type,function(t,e){"function"==typeof e&&a[t]===i&&(a[t]=function(){var e=Array.prototype.slice.call(arguments);return e.unshift(t),e=a._typeFn.apply(a,e),e===i?a:e})})},r.Field.prototype={def:function(t){var e=this.s.opts;return t===i?(t=e["default"]!==i?e["default"]:e.def,n.isFunction(t)?t():t):(e.def=t,this)},disable:function(){return this._typeFn("disable"),this},displayed:function(){var t=this.dom.container;return t.parents("body").length&&"none"!=t.css("display")?!0:!1},enable:function(){return this._typeFn("enable"),this},error:function(t,e){var i=this.s.classes;return t?this.dom.container.addClass(i.error):this.dom.container.removeClass(i.error),this._msg(this.dom.fieldError,t,e)},isMultiValue:function(){return this.s.multiValue},inError:function(){return this.dom.container.hasClass(this.s.classes.error)},input:function(){return this.s.type.input?this._typeFn("input"):n("input, select, textarea",this.dom.container)},focus:function(){return this.s.type.focus?this._typeFn("focus"):n("input, select, textarea",this.dom.container).focus(),this},get:function(){if(this.isMultiValue())return i;var t=this._typeFn("get");return t!==i?t:this.def()},hide:function(t){var e=this.dom.container;return t===i&&(t=!0),this.s.host.display()&&t?e.slideUp():e.css("display","none"),this},label:function(t){var e=this.dom.label;return t===i?e.html():(e.html(t),this)},message:function(t,e){return this._msg(this.dom.fieldMessage,t,e)},multiGet:function(t){var e=this.s.multiValues,n=this.s.multiIds;if(t===i)for(var t={},o=0;o<n.length;o++)t[n[o]]=this.isMultiValue()?e[n[o]]:this.val();else t=this.isMultiValue()?e[t]:this.val();return t},multiSet:function(t,e){var o=this.s.multiValues,s=this.s.multiIds;e===i&&(e=t,t=i);var a=function(t,e){-1===n.inArray(s)&&s.push(t),o[t]=e};return n.isPlainObject(e)&&t===i?n.each(e,function(t,e){a(t,e)}):t===i?n.each(s,function(t,i){a(i,e)}):a(t,e),this.s.multiValue=!0,this._multiValueCheck(),this},name:function(){return this.s.opts.name},node:function(){return this.dom.container[0]},set:function(t){return this.s.multiValue=!1,t=this._typeFn("set",t),this._multiValueCheck(),t},show:function(t){var e=this.dom.container;return t===i&&(t=!0),this.s.host.display()&&t?e.slideDown():e.css("display","block"),this},val:function(t){return t===i?this.get():this.set(t)},dataSrc:function(){return this.s.opts.data},destroy:function(){return this.dom.container.remove(),this._typeFn("destroy"),this},multiIds:function(){return this.s.multiIds},multiInfoShown:function(t){this.dom.multiInfo.css({display:t?"block":"none"})},multiReset:function(){this.s.multiIds=[],this.s.multiValues={}},valFromData:null,valToData:null,_errorNode:function(){return this.dom.fieldError},_msg:function(t,e,i){if("function"==typeof e)var n=this.s.host,e=e(n,new o.Api(n.s.table));return t.parent().is(":visible")?(t.html(e),e?t.slideDown(i):t.slideUp(i)):(t.html(e||"").css("display",e?"block":"none"),i&&i()),this},_multiValueCheck:function(){for(var t,e,i=this.s.multiIds,n=this.s.multiValues,o=!1,s=0;s<i.length;s++){if(e=n[i[s]],s>0&&e!==t){o=!0;break}t=e}return o&&this.s.multiValue?(this.dom.inputControl.css({display:"none"}),this.dom.multi.css({display:"block"})):(this.dom.inputControl.css({display:"block"}),this.dom.multi.css({display:"none"}),this.s.multiValue&&this.val(t)),1<i.length&&this.dom.multiReturn.css({display:o&&!this.s.multiValue?"block":"none"}),this.s.host._multiInfo(),!0},_typeFn:function(t){var e=Array.prototype.slice.call(arguments);e.shift(),e.unshift(this.s.opts);var i=this.s.type[t];return i?i.apply(this.s.host,e):void 0}},r.Field.models={},r.Field.defaults={className:"",data:"",def:"",fieldInfo:"",id:"",label:"",labelInfo:"",name:null,type:"text"},r.Field.models.settings={type:null,name:null,classes:null,opts:null,host:null},r.Field.models.dom={container:null,label:null,labelInfo:null,fieldInfo:null,fieldError:null,fieldMessage:null},r.models={},r.models.displayController={init:function(){},open:function(){},close:function(){}},r.models.fieldType={create:function(){},get:function(){},set:function(){},enable:function(){},disable:function(){}},r.models.settings={ajaxUrl:null,ajax:null,dataSource:null,domTable:null,opts:null,displayController:null,fields:{},order:[],id:-1,displayed:!1,processing:!1,modifier:null,action:null,idSrc:null},r.models.button={label:null,fn:null,className:null},r.models.formOptions={onReturn:"submit",onBlur:"close",onBackground:"blur",onComplete:"close",onEsc:"close",submit:"all",focus:0,buttons:!0,title:!0,message:!0,drawType:!1},r.display={};var c,p=jQuery;r.display.lightbox=p.extend(!0,{},r.models.displayController,{init:function(){return c._init(),c},open:function(t,e,i){c._shown?i&&i():(c._dte=t,t=c._dom.content,t.children().detach(),t.append(e).append(c._dom.close),c._shown=!0,c._show(i))},close:function(t,e){c._shown?(c._dte=t,c._hide(e),c._shown=!1):e&&e()},node:function(){return c._dom.wrapper[0]},_init:function(){if(!c._ready){var t=c._dom;t.content=p("div.DTED_Lightbox_Content",c._dom.wrapper),t.wrapper.css("opacity",0),t.background.css("opacity",0)}},_show:function(e){var n=c._dom;t.orientation!==i&&p("body").addClass("DTED_Lightbox_Mobile"),n.content.css("height","auto"),n.wrapper.css({top:-c.conf.offsetAni}),p("body").append(c._dom.background).append(c._dom.wrapper),c._heightCalc(),n.wrapper.stop().animate({opacity:1,top:0},e),n.background.stop().animate({opacity:1}),n.close.bind("click.DTED_Lightbox",function(){c._dte.close()}),n.background.bind("click.DTED_Lightbox",function(){c._dte.background()}),p("div.DTED_Lightbox_Content_Wrapper",n.wrapper).bind("click.DTED_Lightbox",function(t){p(t.target).hasClass("DTED_Lightbox_Content_Wrapper")&&c._dte.background()}),p(t).bind("resize.DTED_Lightbox",function(){c._heightCalc()}),c._scrollTop=p("body").scrollTop(),t.orientation!==i&&(e=p("body").children().not(n.background).not(n.wrapper),p("body").append('<div class="DTED_Lightbox_Shown"/>'),p("div.DTED_Lightbox_Shown").append(e))},_heightCalc:function(){var e=c._dom,i=p(t).height()-2*c.conf.windowPadding-p("div.DTE_Header",e.wrapper).outerHeight()-p("div.DTE_Footer",e.wrapper).outerHeight();p("div.DTE_Body_Content",e.wrapper).css("maxHeight",i)},_hide:function(e){var n=c._dom;if(e||(e=function(){}),t.orientation!==i){var o=p("div.DTED_Lightbox_Shown");o.children().appendTo("body"),o.remove()}p("body").removeClass("DTED_Lightbox_Mobile").scrollTop(c._scrollTop),n.wrapper.stop().animate({opacity:0,top:c.conf.offsetAni},function(){p(this).detach(),e()}),n.background.stop().animate({opacity:0},function(){p(this).detach()}),n.close.unbind("click.DTED_Lightbox"),n.background.unbind("click.DTED_Lightbox"),p("div.DTED_Lightbox_Content_Wrapper",n.wrapper).unbind("click.DTED_Lightbox"),p(t).unbind("resize.DTED_Lightbox")},_dte:null,_ready:!1,_shown:!1,_dom:{wrapper:p('<div class="DTED DTED_Lightbox_Wrapper"><div class="DTED_Lightbox_Container"><div class="DTED_Lightbox_Content_Wrapper"><div class="DTED_Lightbox_Content"></div></div></div></div>'),background:p('<div class="DTED_Lightbox_Background"><div/></div>'),close:p('<div class="DTED_Lightbox_Close"></div>'),content:null}}),c=r.display.lightbox,c.conf={offsetAni:25,windowPadding:25};var f,h=jQuery;r.display.envelope=h.extend(!0,{},r.models.displayController,{init:function(t){return f._dte=t,f._init(),f},open:function(t,e,i){f._dte=t,h(f._dom.content).children().detach(),f._dom.content.appendChild(e),f._dom.content.appendChild(f._dom.close),f._show(i)},close:function(t,e){f._dte=t,f._hide(e)},node:function(){return f._dom.wrapper[0]},_init:function(){f._ready||(f._dom.content=h("div.DTED_Envelope_Container",f._dom.wrapper)[0],e.body.appendChild(f._dom.background),e.body.appendChild(f._dom.wrapper),f._dom.background.style.visbility="hidden",f._dom.background.style.display="block",f._cssBackgroundOpacity=h(f._dom.background).css("opacity"),f._dom.background.style.display="none",f._dom.background.style.visbility="visible")},_show:function(e){e||(e=function(){}),f._dom.content.style.height="auto";var i=f._dom.wrapper.style;i.opacity=0,i.display="block";var n=f._findAttachRow(),o=f._heightCalc(),s=n.offsetWidth;i.display="none",i.opacity=1,f._dom.wrapper.style.width=s+"px",f._dom.wrapper.style.marginLeft=-(s/2)+"px",f._dom.wrapper.style.top=h(n).offset().top+n.offsetHeight+"px",f._dom.content.style.top=-1*o-20+"px",f._dom.background.style.opacity=0,f._dom.background.style.display="block",h(f._dom.background).animate({opacity:f._cssBackgroundOpacity},"normal"),h(f._dom.wrapper).fadeIn(),f.conf.windowScroll?h("html,body").animate({scrollTop:h(n).offset().top+n.offsetHeight-f.conf.windowPadding},function(){h(f._dom.content).animate({top:0},600,e)}):h(f._dom.content).animate({top:0},600,e),h(f._dom.close).bind("click.DTED_Envelope",function(){f._dte.close()}),h(f._dom.background).bind("click.DTED_Envelope",function(){f._dte.background()}),h("div.DTED_Lightbox_Content_Wrapper",f._dom.wrapper).bind("click.DTED_Envelope",function(t){h(t.target).hasClass("DTED_Envelope_Content_Wrapper")&&f._dte.background()}),h(t).bind("resize.DTED_Envelope",function(){f._heightCalc()})},_heightCalc:function(){f.conf.heightCalc?f.conf.heightCalc(f._dom.wrapper):h(f._dom.content).children().height();var e=h(t).height()-2*f.conf.windowPadding-h("div.DTE_Header",f._dom.wrapper).outerHeight()-h("div.DTE_Footer",f._dom.wrapper).outerHeight();return h("div.DTE_Body_Content",f._dom.wrapper).css("maxHeight",e),h(f._dte.dom.wrapper).outerHeight()},_hide:function(e){e||(e=function(){}),h(f._dom.content).animate({top:-(f._dom.content.offsetHeight+50)},600,function(){h([f._dom.wrapper,f._dom.background]).fadeOut("normal",e)}),h(f._dom.close).unbind("click.DTED_Lightbox"),h(f._dom.background).unbind("click.DTED_Lightbox"),h("div.DTED_Lightbox_Content_Wrapper",f._dom.wrapper).unbind("click.DTED_Lightbox"),h(t).unbind("resize.DTED_Lightbox")},_findAttachRow:function(){var t=h(f._dte.s.table).DataTable();return"head"===f.conf.attach?t.table().header():"create"===f._dte.s.action?t.table().header():t.row(f._dte.s.modifier).node()},_dte:null,_ready:!1,_cssBackgroundOpacity:1,_dom:{wrapper:h('<div class="DTED DTED_Envelope_Wrapper"><div class="DTED_Envelope_ShadowLeft"></div><div class="DTED_Envelope_ShadowRight"></div><div class="DTED_Envelope_Container"></div></div>')[0],background:h('<div class="DTED_Envelope_Background"><div/></div>')[0],close:h('<div class="DTED_Envelope_Close">&times;</div>')[0],content:null}}),f=r.display.envelope,f.conf={windowPadding:50,heightCalc:null,attach:"row",windowScroll:!0},r.prototype.add=function(t){if(n.isArray(t))for(var e=0,o=t.length;o>e;e++)this.add(t[e]);else{if(e=t.name,e===i)throw"Error adding field. The field requires a `name` option";if(this.s.fields[e])throw"Error adding field '"+e+"'. A field already exists with this name";this._dataSource("initField",t),this.s.fields[e]=new r.Field(t,this.classes.field,this),this.s.order.push(e)}return this._displayReorder(this.order()),this},r.prototype.background=function(){var t=this.s.editOpts.onBackground;return"blur"===t?this.blur():"close"===t?this.close():"submit"===t&&this.submit(),this},r.prototype.blur=function(){return this._blur(),this},r.prototype.bubble=function(e,o,s,a){var r=this;if(this._tidy(function(){r.bubble(e,o,a)}))return this;n.isPlainObject(o)?(a=o,o=i,s=!0):"boolean"==typeof o&&(s=o,a=o=i),n.isPlainObject(s)&&(a=s,s=!0),s===i&&(s=!0);var a=n.extend({},this.s.formOptions.bubble,a),l=this._dataSource("individual",e,o);if(this._edit(e,l,"bubble"),!this._preopen("bubble"))return this;var d=this._formOptions(a);n(t).on("resize."+d,function(){r.bubblePosition()});var c=[];this.s.bubbleNodes=c.concat.apply(c,u(l,"attach")),c=this.classes.bubble,l=n('<div class="'+c.bg+'"><div/></div>'),c=n('<div class="'+c.wrapper+'"><div class="'+c.liner+'"><div class="'+c.table+'"><div class="'+c.close+'" /></div></div><div class="'+c.pointer+'" /></div>'),s&&(c.appendTo("body"),l.appendTo("body"));var s=c.children().eq(0),p=s.children(),f=p.children();s.append(this.dom.formError),p.prepend(this.dom.form),a.message&&s.prepend(this.dom.formInfo),a.title&&s.prepend(this.dom.header),a.buttons&&p.append(this.dom.buttons);var h=n().add(c).add(l);return this._closeReg(function(){h.animate({opacity:0},function(){h.detach(),n(t).off("resize."+d),r._clearDynamicInfo()})}),l.click(function(){r.blur()}),f.click(function(){r._close()}),this.bubblePosition(),h.animate({opacity:1}),this._focus(this.s.includeFields,a.focus),this._postopen("bubble"),this},r.prototype.bubblePosition=function(){var e=n("div.DTE_Bubble"),i=n("div.DTE_Bubble_Liner"),o=this.s.bubbleNodes,s=0,a=0,r=0,l=0;n.each(o,function(t,e){var i=n(e).offset();s+=i.top,a+=i.left,r+=i.left+e.offsetWidth,l+=i.top+e.offsetHeight});var s=s/o.length,a=a/o.length,r=r/o.length,l=l/o.length,o=s,d=(a+r)/2,u=i.outerWidth(),c=d-u/2,u=c+u,p=n(t).width();return e.css({top:o,left:d}),0>i.offset().top?e.css("top",l).addClass("below"):e.removeClass("below"),u+15>p?i.css("left",15>c?-(c-15):-(u-p+15)):i.css("left",15>c?-(c-15):0),this},r.prototype.buttons=function(t){var e=this;return"_basic"===t?t=[{label:this.i18n[this.s.action].submit,fn:function(){this.submit()}}]:n.isArray(t)||(t=[t]),n(this.dom.buttons).empty(),n.each(t,function(t,i){"string"==typeof i&&(i={label:i,fn:function(){this.submit()}}),n("<button/>",{"class":e.classes.form.button+(i.className?" "+i.className:"")}).html("function"==typeof i.label?i.label(e):i.label||"").attr("tabindex",0).on("keyup",function(t){13===t.keyCode&&i.fn&&i.fn.call(e)}).on("keypress",function(t){13===t.keyCode&&t.preventDefault()}).on("click",function(t){t.preventDefault(),i.fn&&i.fn.call(e)}).appendTo(e.dom.buttons)}),this},r.prototype.clear=function(t){var e=this,i=this.s.fields;return"string"==typeof t?(i[t].destroy(),delete i[t],t=n.inArray(t,this.s.order),this.s.order.splice(t,1)):n.each(this._fieldNames(t),function(t,i){e.clear(i)}),this},r.prototype.close=function(){return this._close(!1),this},r.prototype.create=function(t,e,i,o){var s=this,a=this.s.fields,r=1;if(this._tidy(function(){s.create(t,e,i,o)}))return this;"number"==typeof t&&(r=t,t=e,e=i),this.s.editFields={};for(var l=0;r>l;l++)this.s.editFields[l]={fields:this.s.fields};return r=this._crudArgs(t,e,i,o),this.s.action="create",this.s.modifier=null,this.dom.form.style.display="block",this._actionClass(),this._displayReorder(this.fields()),n.each(a,function(t,e){e.multiReset(),e.set(e.def())}),this._event("initCreate"),this._assembleMain(),this._formOptions(r.opts),r.maybeOpen(),this},r.prototype.dependent=function(t,e,i){var o=this,s=this.field(t),a={type:"POST",dataType:"json"},i=n.extend({event:"change",data:null,preUpdate:null,postUpdate:null},i),r=function(t){i.preUpdate&&i.preUpdate(t),n.each({labels:"label",options:"update",values:"val",messages:"message",errors:"error"},function(e,i){t[e]&&n.each(t[e],function(t,e){o.field(t)[i](e)})}),n.each(["hide","show","enable","disable"],function(e,i){t[i]&&o[i](t[i])}),i.postUpdate&&i.postUpdate(t)};return s.input().on(i.event,function(){var t={};if(t.rows=o.s.editFields?u(o.s.editFields,"data"):null,t.row=t.rows?t.rows[0]:null,t.values=o.val(),i.data){var l=i.data(t);l&&(i.data=l)}"function"==typeof e?(t=e(s.val(),t,r))&&r(t):(n.isPlainObject(e)?n.extend(a,e):a.url=e,n.ajax(n.extend(a,{url:e,data:t,success:r})))}),this},r.prototype.disable=function(t){var e=this.s.fields;return n.each(this._fieldNames(t),function(t,i){e[i].disable()}),this},r.prototype.display=function(t){return t===i?this.s.displayed:this[t?"open":"close"]()},r.prototype.displayed=function(){return n.map(this.s.fields,function(t,e){return t.displayed()?e:null})},r.prototype.displayNode=function(){return this.s.displayController.node(this)},r.prototype.edit=function(t,e,i,n,o){var s=this;if(this._tidy(function(){s.edit(t,e,i,n,o)}))return this;var a=this._crudArgs(e,i,n,o);return this._edit(t,this._dataSource("fields",t),"main"),this._assembleMain(),this._formOptions(a.opts),a.maybeOpen(),this},r.prototype.enable=function(t){var e=this.s.fields;return n.each(this._fieldNames(t),function(t,i){e[i].enable()}),this},r.prototype.error=function(t,e){return e===i?this._message(this.dom.formError,t):this.s.fields[t].error(e),this},r.prototype.field=function(t){return this.s.fields[t]},r.prototype.fields=function(){return n.map(this.s.fields,function(t,e){return e})},r.prototype.get=function(t){var e=this.s.fields;if(t||(t=this.fields()),n.isArray(t)){var i={};return n.each(t,function(t,n){i[n]=e[n].get()}),i}return e[t].get()},r.prototype.hide=function(t,e){var i=this.s.fields;return n.each(this._fieldNames(t),function(t,n){i[n].hide(e)}),this},r.prototype.inError=function(t){if(n(this.dom.formError).is(":visible"))return!0;for(var e=this.s.fields,t=this._fieldNames(t),i=0,o=t.length;o>i;i++)if(e[t[i]].inError())return!0;return!1},r.prototype.inline=function(t,o,s){var a=this;n.isPlainObject(o)&&(s=o,o=i);var r,l,d,s=n.extend({},this.s.formOptions.inline,s),u=this._dataSource("individual",t,o),c=0;if(n.each(u,function(t,e){if(c>0)throw"Cannot edit more than one row inline at a time";r=n(e.attach[0]),d=0,n.each(e.displayFields,function(t,e){if(d>0)throw"Cannot edit more than one field inline at a time";l=e,d++}),c++}),n("div.DTE_Field",r).length||this._tidy(function(){a.inline(t,o,s)}))return this;this._edit(t,u,"inline");var p=this._formOptions(s);if(!this._preopen("inline"))return this;var f=r.contents().detach();return r.append(n('<div class="DTE DTE_Inline"><div class="DTE_Inline_Field"/><div class="DTE_Inline_Buttons"/></div>')),r.find("div.DTE_Inline_Field").append(l.node()),s.buttons&&r.find("div.DTE_Inline_Buttons").append(this.dom.buttons),this._closeReg(function(t){n(e).off("click"+p),t||(r.contents().detach(),r.append(f)),a._clearDynamicInfo()}),setTimeout(function(){n(e).on("click"+p,function(t){var e=n.fn.addBack?"addBack":"andSelf";!l._typeFn("owns",t.target)&&-1===n.inArray(r[0],n(t.target).parents()[e]())&&a.blur()})},0),this._focus([l],s.focus),this._postopen("inline"),this},r.prototype.message=function(t,e){return e===i?this._message(this.dom.formInfo,t):this.s.fields[t].message(e),this},r.prototype.mode=function(){return this.s.action},r.prototype.modifier=function(){return this.s.modifier},r.prototype.multiGet=function(t){var e=this.s.fields;if(t===i&&(t=this.fields()),n.isArray(t)){var o={};return n.each(t,function(t,i){o[i]=e[i].multiGet()}),o}return e[t].multiGet()},r.prototype.multiSet=function(t,e){var o=this.s.fields;return n.isPlainObject(t)&&e===i?n.each(t,function(t,e){o[t].multiSet(e)}):o[t].multiSet(e),this},r.prototype.node=function(t){var e=this.s.fields;return t||(t=this.order()),n.isArray(t)?n.map(t,function(t){return e[t].node()}):e[t].node()},r.prototype.off=function(t,e){return n(this).off(this._eventName(t),e),this},r.prototype.on=function(t,e){return n(this).on(this._eventName(t),e),this},r.prototype.one=function(t,e){return n(this).one(this._eventName(t),e),this},r.prototype.open=function(){var t=this;return this._displayReorder(),this._closeReg(function(){t.s.displayController.close(t,function(){t._clearDynamicInfo()})}),this._preopen("main")?(this.s.displayController.open(this,this.dom.wrapper),this._focus(n.map(this.s.order,function(e){return t.s.fields[e]}),this.s.editOpts.focus),this._postopen("main"),this):this},r.prototype.order=function(t){if(!t)return this.s.order;if(arguments.length&&!n.isArray(t)&&(t=Array.prototype.slice.call(arguments)),this.s.order.slice().sort().join("-")!==t.slice().sort().join("-"))throw"All fields, and no additional fields, must be provided for ordering.";return n.extend(this.s.order,t),this._displayReorder(),this},r.prototype.remove=function(t,e,o,s,a){var r=this;if(this._tidy(function(){r.remove(t,e,o,s,a)}))return this;t.length===i&&(t=[t]);var l=this._crudArgs(e,o,s,a),d=this._dataSource("fields",t);return this.s.action="remove",this.s.modifier=t,this.s.editFields=d,this.dom.form.style.display="none",this._actionClass(),this._event("initRemove",[u(d,"node"),u(d,"data"),t]),this._event("initMultiRemove",[d,t]),this._assembleMain(),this._formOptions(l.opts),l.maybeOpen(),l=this.s.editOpts,null!==l.focus&&n("button",this.dom.buttons).eq(l.focus).focus(),this},r.prototype.set=function(t,e){var i=this.s.fields;if(!n.isPlainObject(t)){var o={};o[t]=e,t=o}return n.each(t,function(t,e){i[t].set(e)}),this},r.prototype.show=function(t,e){var i=this.s.fields;return n.each(this._fieldNames(t),function(t,n){i[n].show(e)}),this},r.prototype.submit=function(t,e,i,o){var s=this,a=this.s.fields,r=[],l=0,d=!1;if(this.s.processing||!this.s.action)return this;this._processing(!0);var u=function(){r.length!==l||d||(d=!0,s._submit(t,e,i,o))};return this.error(),n.each(a,function(t,e){e.inError()&&r.push(t)}),n.each(r,function(t,e){a[e].error("",function(){l++,u()})}),u(),this},r.prototype.title=function(t){var e=n(this.dom.header).children("div."+this.classes.header.content);return t===i?e.html():("function"==typeof t&&(t=t(this,new o.Api(this.s.table))),e.html(t),this)},r.prototype.val=function(t,e){return e===i?this.get(t):this.set(t,e)};var m=o.Api.register;m("editor()",function(){return s(this)}),m("row.create()",function(t){var e=s(this);return e.create(a(e,t,"create")),this}),m("row().edit()",function(t){var e=s(this);return e.edit(this[0][0],a(e,t,"edit")),this}),m("rows().edit()",function(t){var e=s(this);return e.edit(this[0],a(e,t,"edit")),this}),m("row().delete()",function(t){var e=s(this);return e.remove(this[0][0],a(e,t,"remove",1)),this}),m("rows().delete()",function(t){var e=s(this);return e.remove(this[0],a(e,t,"remove",this[0].length)),this}),m("cell().edit()",function(t,e){return t?n.isPlainObject(t)&&(e=t,t="inline"):t="inline",s(this)[t](this[0][0],e),this}),m("cells().edit()",function(t){return s(this).bubble(this[0],t),this}),m("file()",function(t,e){return r.files[t][e]}),m("files()",function(t,e){return t?e?(r.files[t]=e,this):r.files[t]:r.files}),n(e).on("xhr.dt",function(t,e,i){"dt"===t.namespace&&i&&i.files&&n.each(i.files,function(t,e){r.files[t]=e})}),r.error=function(t,e){throw e?t+" For more information, please refer to https://datatables.net/tn/"+e:t},r.pairs=function(t,e,o){var s,a,r,e=n.extend({label:"label",value:"value"},e);if(n.isArray(t))for(s=0,a=t.length;a>s;s++)r=t[s],n.isPlainObject(r)?o(r[e.value]===i?r[e.label]:r[e.value],r[e.label],s):o(r,r,s);else s=0,n.each(t,function(t,e){o(e,t,s),s++})},r.safeId=function(t){return t.replace(".","-")},r.upload=function(t,e,i,o,s){var a=new FileReader,l=0,d=[];t.error(e.name,""),a.onload=function(){var u,c=new FormData;if(c.append("action","upload"),c.append("uploadField",e.name),c.append("upload",i[l]),e.ajax?u=e.ajax:("string"==typeof t.s.ajax||n.isPlainObject(t.s.ajax))&&(u=t.s.ajax),!u)throw"No Ajax option specified for upload plug-in";"string"==typeof u&&(u={url:u});var p=!1;t.on("preSubmit.DTE_Upload",function(){return p=!0,!1}),n.ajax(n.extend(u,{type:"post",data:c,dataType:"json",contentType:!1,processData:!1,xhrFields:{onprogress:function(t){t.lengthComputable&&(t=100*(t.loaded/t.total)+"%",o(e,1===i.length?t:l+":"+i.length+" "+t))},onloadend:function(){o(e)}},success:function(e){if(t.off("preSubmit.DTE_Upload"),e.fieldErrors&&e.fieldErrors.length)for(var e=e.fieldErrors,o=0,u=e.length;u>o;o++)t.error(e[o].name,e[o].status);else e.error?t.error(e.error):(e.files&&n.each(e.files,function(t,e){r.files[t]=e}),d.push(e.upload.id),l<i.length-1?(l++,a.readAsDataURL(i[l])):(s.call(t,d),p&&t.submit()))}}))},a.readAsDataURL(i[0])},r.prototype._constructor=function(t){t=n.extend(!0,{},r.defaults,t),this.s=n.extend(!0,{},r.models.settings,{table:t.domTable||t.table,dbTable:t.dbTable||null,ajaxUrl:t.ajaxUrl,ajax:t.ajax,idSrc:t.idSrc,dataSource:t.domTable||t.table?r.dataSources.dataTable:r.dataSources.html,formOptions:t.formOptions,legacyAjax:t.legacyAjax}),this.classes=n.extend(!0,{},r.classes),this.i18n=t.i18n;var i=this,o=this.classes;if(this.dom={wrapper:n('<div class="'+o.wrapper+'"><div data-dte-e="processing" class="'+o.processing.indicator+'"></div><div data-dte-e="body" class="'+o.body.wrapper+'"><div data-dte-e="body_content" class="'+o.body.content+'"/></div><div data-dte-e="foot" class="'+o.footer.wrapper+'"><div class="'+o.footer.content+'"/></div></div>')[0],form:n('<form data-dte-e="form" class="'+o.form.tag+'"><div data-dte-e="form_content" class="'+o.form.content+'"/></form>')[0],formError:n('<div data-dte-e="form_error" class="'+o.form.error+'"/>')[0],formInfo:n('<div data-dte-e="form_info" class="'+o.form.info+'"/>')[0],header:n('<div data-dte-e="head" class="'+o.header.wrapper+'"><div class="'+o.header.content+'"/></div>')[0],buttons:n('<div data-dte-e="form_buttons" class="'+o.form.buttons+'"/>')[0]},n.fn.dataTable.TableTools){var s=n.fn.dataTable.TableTools.BUTTONS,a=this.i18n;n.each(["create","edit","remove"],function(t,e){s["editor_"+e].sButtonText=a[e].button})}n.each(t.events,function(t,e){i.on(t,function(){var t=Array.prototype.slice.call(arguments);t.shift(),e.apply(i,t)})});var o=this.dom,d=o.wrapper;o.formContent=l("form_content",o.form)[0],o.footer=l("foot",d)[0],o.body=l("body",d)[0],o.bodyContent=l("body_content",d)[0],o.processing=l("processing",d)[0],t.fields&&this.add(t.fields),n(e).on("init.dt.dte",function(t,e){i.s.table&&e.nTable===n(i.s.table).get(0)&&(e._editor=i)}).on("xhr.dt",function(t,e,o){o&&i.s.table&&e.nTable===n(i.s.table).get(0)&&i._optionsUpdate(o)}),this.s.displayController=r.display[t.display].init(this),this._event("initComplete",[])},r.prototype._actionClass=function(){var t=this.classes.actions,e=this.s.action,i=n(this.dom.wrapper);i.removeClass([t.create,t.edit,t.remove].join(" ")),"create"===e?i.addClass(t.create):"edit"===e?i.addClass(t.edit):"remove"===e&&i.addClass(t.remove)},r.prototype._ajax=function(t,e,i){var o,s={type:"POST",dataType:"json",data:null,success:e,error:i};o=this.s.action;var a=this.s.ajax||this.s.ajaxUrl,r="edit"===o||"remove"===o?u(this.s.editFields,"idSrc"):null;if(n.isArray(r)&&(r=r.join(",")),n.isPlainObject(a)&&a[o]&&(a=a[o]),n.isFunction(a)){var l=null,s=null;if(this.s.ajaxUrl){var d=this.s.ajaxUrl;d.create&&(l=d[o]),-1!==l.indexOf(" ")&&(o=l.split(" "),s=o[0],l=o[1]),l=l.replace(/_id_/,r)}a(s,l,t,e,i)}else"string"==typeof a?-1!==a.indexOf(" ")?(o=a.split(" "),s.type=o[0],s.url=o[1]):s.url=a:s=n.extend({},s,a||{}),s.url=s.url.replace(/_id_/,r),s.data&&(e=n.isFunction(s.data)?s.data(t):s.data,t=n.isFunction(s.data)&&e?e:n.extend(!0,t,e)),s.data=t,"DELETE"===s.type&&(t=n.param(s.data),s.url+=-1===s.url.indexOf("?")?"?"+t:"&"+t,delete s.data),n.ajax(s)},r.prototype._assembleMain=function(){var t=this.dom;n(t.wrapper).prepend(t.header),n(t.footer).append(t.formError).append(t.buttons),n(t.bodyContent).append(t.formInfo).append(t.form)},r.prototype._blur=function(){var t=this.s.editOpts;!1!==this._event("preBlur")&&("submit"===t.onBlur?this.submit():"close"===t.onBlur&&this._close())},r.prototype._clearDynamicInfo=function(){var t=this.classes.field.error,e=this.s.fields;n("div."+t,this.dom.wrapper).removeClass(t),n.each(e,function(t,e){e.error("").message("")}),this.error("").message("")},r.prototype._close=function(t){!1!==this._event("preClose")&&(this.s.closeCb&&(this.s.closeCb(t),this.s.closeCb=null),this.s.closeIcb&&(this.s.closeIcb(),this.s.closeIcb=null),n("body").off("focus.editor-focus"),this.s.displayed=!1,this._event("close"))},r.prototype._closeReg=function(t){this.s.closeCb=t},r.prototype._crudArgs=function(t,e,o,s){var a,r,l,d=this;return n.isPlainObject(t)||("boolean"==typeof t?(l=t,t=e):(a=t,r=e,l=o,t=s)),l===i&&(l=!0),a&&d.title(a),r&&d.buttons(r),{opts:n.extend({},this.s.formOptions.main,t),maybeOpen:function(){l&&d.open()}}},r.prototype._dataSource=function(t){var e=Array.prototype.slice.call(arguments);e.shift();var i=this.s.dataSource[t];return i?i.apply(this,e):void 0},r.prototype._displayReorder=function(t){var e=n(this.dom.formContent),i=this.s.fields,o=this.s.order;t?this.s.includeFields=t:t=this.s.includeFields,e.children().detach(),n.each(o,function(o,s){var a=s instanceof r.Field?s.name():s;-1!==n.inArray(a,t)&&e.append(i[a].node())}),this._event("displayOrder",[this.s.displayed,this.s.action])},r.prototype._edit=function(t,e,o){var s,a=this.s.fields,r=[];this.s.editFields=e,this.s.modifier=t,this.s.action="edit",this.dom.form.style.display="block",this._actionClass(),n.each(a,function(t,o){o.multiReset(),s=!0,n.each(e,function(e,n){if(n.fields[t]){var a=o.valFromData(n.data);o.multiSet(e,a!==i?a:o.def()),n.displayFields&&!n.displayFields[t]&&(s=!1)}}),0!==o.multiIds().length&&s&&r.push(t)});for(var a=this.order().slice(),l=a.length;l>=0;l--)-1===n.inArray(a[l],r)&&a.splice(l,1);this._displayReorder(a),this.s.editData=this.multiGet(),this._event("initEdit",[u(e,"node")[0],u(e,"data")[0],t,o]),this._event("initMultiEdit",[e,t,o])},r.prototype._event=function(t,e){if(e||(e=[]),!n.isArray(t))return i=n.Event(t),n(this).triggerHandler(i,e),i.result;for(var i=0,o=t.length;o>i;i++)this._event(t[i],e)},r.prototype._eventName=function(t){for(var e=t.split(" "),i=0,n=e.length;n>i;i++){var t=e[i],o=t.match(/^on([A-Z])/);o&&(t=o[1].toLowerCase()+t.substring(3)),e[i]=t}return e.join(" ")},r.prototype._fieldNames=function(t){return t===i?this.fields():n.isArray(t)?t:[t]},r.prototype._focus=function(t,e){var i,o=this,s=n.map(t,function(t){return"string"==typeof t?o.s.fields[t]:t});"number"==typeof e?i=s[e]:e&&(i=0===e.indexOf("jq:")?n("div.DTE "+e.replace(/^jq:/,"")):this.s.fields[e]),(this.s.setFocus=i)&&i.focus()},r.prototype._formOptions=function(t){var o=this,s=d++,a=".dteInline"+s;
return t.closeOnComplete!==i&&(t.onComplete=t.closeOnComplete?"close":"none"),t.submitOnBlur!==i&&(t.onBlur=t.submitOnBlur?"submit":"close"),t.submitOnReturn!==i&&(t.onReturn=t.submitOnReturn?"submit":"none"),t.blurOnBackground!==i&&(t.onBackground=t.blurOnBackground?"blur":"none"),this.s.editOpts=t,this.s.editCount=s,("string"==typeof t.title||"function"==typeof t.message)&&(this.title(t.title),t.title=!0),("string"==typeof t.message||"function"==typeof t.message)&&(this.message(t.message),t.message=!0),"boolean"!=typeof t.buttons&&(this.buttons(t.buttons),t.buttons=!0),n(e).on("keydown"+a,function(i){var s=n(e.activeElement),a=s.length?s[0].nodeName.toLowerCase():null;if(n(s).attr("type"),!o.s.displayed||"submit"!==t.onReturn||13!==i.keyCode||"input"!==a&&"select"!==a)if(27===i.keyCode)switch(i.preventDefault(),t.onEsc){case"blur":o.blur();break;case"close":o.close();break;case"submit":o.submit()}else s.parents(".DTE_Form_Buttons").length&&(37===i.keyCode?s.prev("button").focus():39===i.keyCode&&s.next("button").focus());else i.preventDefault(),o.submit()}),this.s.closeIcb=function(){n(e).off("keydown"+a)},a},r.prototype._legacyAjax=function(t,e,o){if(this.s.legacyAjax)if("send"===t)if("create"===e||"edit"===e){var s;n.each(o.data,function(t){if(s!==i)throw"Editor: Multi-row editing is not supported by the legacy Ajax data format";s=t}),o.data=o.data[s],"edit"===e&&(o.id=s)}else o.id=n.map(o.data,function(t,e){return e}),delete o.data;else o.data=!o.data&&o.row?[o.row]:[]},r.prototype._optionsUpdate=function(t){var e=this;t.options&&n.each(this.s.fields,function(n){if(t.options[n]!==i){var o=e.field(n);o&&o.update&&o.update(t.options[n])}})},r.prototype._message=function(t,e){"function"==typeof e&&(e=e(this,new o.Api(this.s.table))),t=n(t),!e&&this.s.displayed?t.stop().fadeOut(function(){t.html("")}):e?this.s.displayed?t.stop().html(e).fadeIn():t.html(e).css("display","block"):t.html("").css("display","none")},r.prototype._multiInfo=function(){var t=this.s.fields,e=this.s.includeFields,i=!0;if(e)for(var n=0,o=e.length;o>n;n++)t[e[n]].isMultiValue()&&i?(t[e[n]].multiInfoShown(i),i=!1):t[e[n]].multiInfoShown(!1)},r.prototype._postopen=function(t){var o=this,s=this.s.displayController.captureFocus;return s===i&&(s=!0),n(this.dom.form).off("submit.editor-internal").on("submit.editor-internal",function(t){t.preventDefault()}),!s||"main"!==t&&"bubble"!==t||n("body").on("focus.editor-focus",function(){0===n(e.activeElement).parents(".DTE").length&&0===n(e.activeElement).parents(".DTED").length&&o.s.setFocus&&o.s.setFocus.focus()}),this._multiInfo(),this._event("open",[t,this.s.action]),!0},r.prototype._preopen=function(t){return!1===this._event("preOpen",[t,this.s.action])?!1:(this.s.displayed=t,!0)},r.prototype._processing=function(t){var e=n(this.dom.wrapper),i=this.dom.processing.style,o=this.classes.processing.active;t?(i.display="block",e.addClass(o),n("div.DTE").addClass(o)):(i.display="none",e.removeClass(o),n("div.DTE").removeClass(o)),this.s.processing=t,this._event("processing",[t])},r.prototype._submit=function(t,e,s,a){var r,l,d=this,u=!1,c={},p={},f=o.ext.oApi._fnSetObjectDataFn,h=this.s.fields,m=this.s.action,_=this.s.editCount,b=this.s.modifier,v=this.s.editFields,y=this.s.editData,g=this.s.editOpts,T=g.submit,D={action:this.s.action,data:{}};if(this.s.dbTable&&(D.table=this.s.dbTable),"create"===m||"edit"===m)if(n.each(v,function(t,e){var i={},o={};n.each(h,function(s,a){if(e.fields[s]){var r=a.multiGet(t),l=f(s),d=n.isArray(r)&&-1!==s.indexOf("[]")?f(s.replace(/\[.*$/,"")+"-many-count"):null;l(i,r),d&&d(i,r.length),"edit"===m&&r!==y[s][t]&&(l(o,r),u=!0,d&&d(o,r.length))}}),c[t]=i,p[t]=o}),"create"===m||"all"===T||"allIfChanged"===T&&u)D.data=c;else{if("changed"!==T||!u)return this.s.action=null,"close"===g.onComplete&&(a===i||a)&&this._close(!1),t&&t.call(this),this._processing(!1),void this._event("submitComplete");D.data=p}else"remove"===m&&n.each(v,function(t,e){D.data[t]=e.data});this._legacyAjax("send",m,D),l=n.extend(!0,{},D),s&&s(D),!1===this._event("preSubmit",[D,m])?this._processing(!1):this._ajax(D,function(o){var s;if(d._legacyAjax("receive",m,o),d._event("postSubmit",[o,D,m]),o.error||(o.error=""),o.fieldErrors||(o.fieldErrors=[]),o.error||o.fieldErrors.length)d.error(o.error),n.each(o.fieldErrors,function(t,e){var i=h[e.name];i.error(e.status||"Error"),0===t&&(n(d.dom.bodyContent,d.s.wrapper).animate({scrollTop:n(i.node()).position().top},500),i.focus())}),e&&e.call(d,o);else{var u={};if(d._dataSource("prep",m,b,l,o.data,u),"create"===m||"edit"===m)for(r=0;r<o.data.length;r++)s=o.data[r],d._event("setData",[o,s,m]),"create"===m?(d._event("preCreate",[o,s]),d._dataSource("create",h,s,u),d._event(["create","postCreate"],[o,s])):"edit"===m&&(d._event("preEdit",[o,s]),d._dataSource("edit",b,h,s,u),d._event(["edit","postEdit"],[o,s]));else"remove"===m&&(d._event("preRemove",[o]),d._dataSource("remove",b,h,u),d._event(["remove","postRemove"],[o]));d._dataSource("commit",m,b,o.data,u),_===d.s.editCount&&(d.s.action=null,"close"===g.onComplete&&(a===i||a)&&d._close(!0)),t&&t.call(d,o),d._event("submitSuccess",[o,s])}d._processing(!1),d._event("submitComplete",[o,s])},function(t,i,n){d._event("postSubmit",[t,i,n,D]),d.error(d.i18n.error.system),d._processing(!1),e&&e.call(d,t,i,n),d._event(["submitError","submitComplete"],[t,i,n,D])})},r.prototype._tidy=function(t){if(this.s.processing)return this.one("submitComplete",t),!0;if(n("div.DTE_Inline").length||"inline"===this.display()){var e=this;return this.one("close",function(){e.s.processing?e.one("submitComplete",function(){var i=new n.fn.dataTable.Api(e.s.table);e.s.table&&i.settings()[0].oFeatures.bServerSide?i.one("draw",t):setTimeout(function(){t()},10)}):setTimeout(function(){t()},10)}).blur(),!0}return!1},r.defaults={table:null,ajaxUrl:null,fields:[],display:"lightbox",ajax:null,idSrc:"DT_RowId",events:{},i18n:{create:{button:"New",title:"Create new entry",submit:"Create"},edit:{button:"Edit",title:"Edit entry",submit:"Update"},remove:{button:"Delete",title:"Delete",submit:"Delete",confirm:{_:"Are you sure you wish to delete %d rows?",1:"Are you sure you wish to delete 1 row?"}},error:{system:'A system error has occurred (<a target="_blank" href="//datatables.net/tn/12">More information</a>).'},multi:{title:"Multiple values",info:"The selected items contain different values for this input. To edit and set all items for this input to the same value, click or tap here, otherwise they will retain their individual values.",restore:"Undo changes"}},formOptions:{bubble:n.extend({},r.models.formOptions,{title:!1,message:!1,buttons:"_basic",submit:"changed"}),inline:n.extend({},r.models.formOptions,{buttons:!1,submit:"changed"}),main:n.extend({},r.models.formOptions)},legacyAjax:!1};var _=function(t,e,i){n.each(i,function(n){(n=e[n])&&b(t,n.dataSrc()).each(function(){for(;this.childNodes.length;)this.removeChild(this.firstChild)}).html(n.valFromData(i))})},b=function(t,i){var o="keyless"===t?e:n('[data-editor-id="'+t+'"]');return n('[data-editor-field="'+i+'"]',o)},v=r.dataSources={},y=function(t){t=n(t),setTimeout(function(){t.addClass("highlight"),setTimeout(function(){t.addClass("noHighlight").removeClass("highlight"),setTimeout(function(){t.removeClass("noHighlight")},550)},500)},20)},g=function(t,e,i,n,o){e.rows(i).indexes().each(function(i){var i=e.row(i),s=i.data(),a=o(s);t[a]={idSrc:a,data:s,node:i.node(),fields:n,type:"row"}})},T=function(t,e,o,s,a,l){e.cells(o).indexes().each(function(o){var d,u=e.cell(o),c=e.row(o.row).data(),c=a(c);if(!(d=l)){d=o.column,d=e.settings()[0].aoColumns[d];var p=d.editField!==i?d.editField:d.mData,f={};n.each(s,function(t,e){if(n.isArray(p))for(var i=0;i<p.length;i++){var o=e,s=p[i];o.dataSrc()===s&&(f[o.name()]=o)}else e.dataSrc()===p&&(f[e.name()]=e)}),n.isEmptyObject(f)&&r.error("Unable to automatically determine field from source. Please specify the field name.",11),d=f}g(t,e,o.row,s,a),t[c].attach=[u.node()],t[c].displayFields=d})};if(v.dataTable={individual:function(t,e){var i,s,a=o.ext.oApi._fnGetObjectDataFn(this.s.idSrc),r=n(this.s.table).DataTable(),l=this.s.fields,d={};return t.nodeName&&n(t).hasClass("dtr-data")&&(s=t,t=r.responsive.index(n(t).closest("li"))),e&&(n.isArray(e)||(e=[e]),i={},n.each(e,function(t,e){i[e]=l[e]})),T(d,r,t,l,a,i),s&&n.each(d,function(t,e){e.attach=[s]}),d},fields:function(t){var e=o.ext.oApi._fnGetObjectDataFn(this.s.idSrc),s=n(this.s.table).DataTable(),a=this.s.fields,r={};return!n.isPlainObject(t)||t.rows===i&&t.columns===i&&t.cells===i?g(r,s,t,a,e):(t.rows!==i&&g(r,s,t.rows,a,e),t.columns!==i&&s.cells(null,t.columns).indexes().each(function(t){T(r,s,t,a,e)}),t.cells!==i&&T(r,s,t.cells,a,e)),r},create:function(t,e){var i=n(this.s.table).DataTable();if(!i.settings()[0].oFeatures.bServerSide){var o=i.row.add(e);i.draw(!1),y(o.node())}},edit:function(t,e,i,s){if(t=n(this.s.table).DataTable(),!t.settings()[0].oFeatures.bServerSide){var a=o.ext.oApi._fnGetObjectDataFn(this.s.idSrc),r=a(i),e=t.row("#"+r);e.any()||(e=t.row(function(t,e){return r===a(e)})),e.any()&&(e.data(i),y(e.node()),i=n.inArray(r,s.rowIds),s.rowIds.splice(i,1))}},remove:function(t){var e=n(this.s.table).DataTable();e.settings()[0].oFeatures.bServerSide||e.rows(t).remove()},prep:function(t,e,i,o,s){"edit"===t&&(s.rowIds=n.map(i.data,function(t,e){return n.isEmptyObject(i.data[e])?void 0:e}))},commit:function(t,e,i,s){if(e=n(this.s.table).DataTable(),"edit"===t&&s.rowIds.length)for(var a=s.rowIds,r=o.ext.oApi._fnGetObjectDataFn(this.s.idSrc),l=0,s=a.length;s>l;l++)t=e.row("#"+a[l]),t.any()||(t=e.row(function(t,e){return a[l]===r(e)})),t.any()&&t.remove();e.draw(this.s.editOpts.drawType)}},v.html={initField:function(t){var e=n('[data-editor-label="'+(t.data||t.name)+'"]');!t.label&&e.length&&(t.label=e.html())},individual:function(t,e){if((t instanceof n||t.nodeName)&&(e||(e=[n(t).attr("data-editor-field")]),t=n(t).parents("[data-editor-id]").data("editor-id")),t||(t="keyless"),e&&!n.isArray(e)&&(e=[e]),!e||0===e.length)throw"Cannot automatically determine field name from data source";var i=v.html.fields.call(this,t),o=this.s.fields,s={};return n.each(e,function(t,e){s[e]=o[e]}),n.each(i,function(i,a){a.type="cell";for(var r=t,l=e,d=n(),u=0,c=l.length;c>u;u++)d=d.add(b(r,l[u]));a.attach=d.toArray(),a.fields=o,a.displayFields=s}),i},fields:function(t){var o={},s={},a=this.s.fields;return t||(t="keyless"),n.each(a,function(e,n){var o=b(t,n.dataSrc()).html();n.valToData(s,null===o?i:o)}),o[t]={idSrc:t,data:s,node:e,fields:a,type:"row"},o},create:function(t,e){if(e){var i=o.ext.oApi._fnGetObjectDataFn(this.s.idSrc)(e);n('[data-editor-id="'+i+'"]').length&&_(i,t,e)}},edit:function(t,e,i){t=o.ext.oApi._fnGetObjectDataFn(this.s.idSrc)(i)||"keyless",_(t,e,i)},remove:function(t){n('[data-editor-id="'+t+'"]').remove()}},r.classes={wrapper:"DTE",processing:{indicator:"DTE_Processing_Indicator",active:"DTE_Processing"},header:{wrapper:"DTE_Header",content:"DTE_Header_Content"},body:{wrapper:"DTE_Body",content:"DTE_Body_Content"},footer:{wrapper:"DTE_Footer",content:"DTE_Footer_Content"},form:{wrapper:"DTE_Form",content:"DTE_Form_Content",tag:"",info:"DTE_Form_Info",error:"DTE_Form_Error",buttons:"DTE_Form_Buttons",button:"btn"},field:{wrapper:"DTE_Field",typePrefix:"DTE_Field_Type_",namePrefix:"DTE_Field_Name_",label:"DTE_Label",input:"DTE_Field_Input",inputControl:"DTE_Field_InputControl",error:"DTE_Field_StateError","msg-label":"DTE_Label_Info","msg-error":"DTE_Field_Error","msg-message":"DTE_Field_Message","msg-info":"DTE_Field_Info",multiValue:"multi-value",multiInfo:"multi-info",multiRestore:"multi-restore"},actions:{create:"DTE_Action_Create",edit:"DTE_Action_Edit",remove:"DTE_Action_Remove"},bubble:{wrapper:"DTE DTE_Bubble",liner:"DTE_Bubble_Liner",table:"DTE_Bubble_Table",close:"DTE_Bubble_Close",pointer:"DTE_Bubble_Triangle",bg:"DTE_Bubble_Background"}},o.TableTools){var m=o.TableTools.BUTTONS,D={sButtonText:null,editor:null,formTitle:null};m.editor_create=n.extend(!0,m.text,D,{formButtons:[{label:null,fn:function(){this.submit()}}],fnClick:function(t,e){var i=e.editor,n=i.i18n.create,o=e.formButtons;o[0].label||(o[0].label=n.submit),i.create({title:n.title,buttons:o})}}),m.editor_edit=n.extend(!0,m.select_single,D,{formButtons:[{label:null,fn:function(){this.submit()}}],fnClick:function(t,e){var i=this.fnGetSelectedIndexes();if(1===i.length){var n=e.editor,o=n.i18n.edit,s=e.formButtons;s[0].label||(s[0].label=o.submit),n.edit(i[0],{title:o.title,buttons:s})}}}),m.editor_remove=n.extend(!0,m.select,D,{question:null,formButtons:[{label:null,fn:function(){var t=this;this.submit(function(){n.fn.dataTable.TableTools.fnGetInstance(n(t.s.table).DataTable().table().node()).fnSelectNone()})}}],fnClick:function(t,e){var i=this.fnGetSelectedIndexes();if(0!==i.length){var n=e.editor,o=n.i18n.remove,s=e.formButtons,a="string"==typeof o.confirm?o.confirm:o.confirm[i.length]?o.confirm[i.length]:o.confirm._;s[0].label||(s[0].label=o.submit),n.remove(i,{message:a.replace(/%d/g,i.length),title:o.title,buttons:s})}}})}n.extend(o.ext.buttons,{create:{text:function(t,e,i){return t.i18n("buttons.create",i.editor.i18n.create.button)},className:"buttons-create",editor:null,formButtons:{label:function(t){return t.i18n.create.submit},fn:function(){this.submit()}},formMessage:null,formTitle:null,action:function(t,e,i,n){t=n.editor,t.create({buttons:n.formButtons,message:n.formMessage,title:n.formTitle||t.i18n.create.title})}},edit:{extend:"selected",text:function(t,e,i){return t.i18n("buttons.edit",i.editor.i18n.edit.button)},className:"buttons-edit",editor:null,formButtons:{label:function(t){return t.i18n.edit.submit},fn:function(){this.submit()}},formMessage:null,formTitle:null,action:function(t,e,i,n){var t=n.editor,i=e.rows({selected:!0}).indexes(),o=e.columns({selected:!0}).indexes(),e=e.cells({selected:!0}).indexes();t.edit(o.length||e.length?{rows:i,columns:o,cells:e}:i,{message:n.formMessage,buttons:n.formButtons,title:n.formTitle||t.i18n.edit.title})}},remove:{extend:"selected",text:function(t,e,i){return t.i18n("buttons.remove",i.editor.i18n.remove.button)},className:"buttons-remove",editor:null,formButtons:{label:function(t){return t.i18n.remove.submit},fn:function(){this.submit()}},formMessage:function(t,e){var i=e.rows({selected:!0}).indexes(),n=t.i18n.remove;return("string"==typeof n.confirm?n.confirm:n.confirm[i.length]?n.confirm[i.length]:n.confirm._).replace(/%d/g,i.length)},formTitle:null,action:function(t,e,i,n){t=n.editor,t.remove(e.rows({selected:!0}).indexes(),{buttons:n.formButtons,message:n.formMessage,title:n.formTitle||t.i18n.remove.title})}}}),r.fieldTypes={};var x=function(t,e){(null===e||e===i)&&(e=t.uploadText||"Choose file..."),t._input.find("div.upload button").text(e)},w=function(e,i,o){var s=e.classes.form.button,s=n('<div class="editor_upload"><div class="eu_table"><div class="row"><div class="cell upload"><button class="'+s+'" /><input type="file"/></div><div class="cell clearValue"><button class="'+s+'" /></div></div><div class="row second"><div class="cell"><div class="drop"><span/></div></div><div class="cell"><div class="rendered"/></div></div></div></div>');if(i._input=s,i._enabled=!0,x(i),t.FileReader&&!1!==i.dragDrop){s.find("div.drop span").text(i.dragDropText||"Drag and drop a file here to upload");var a=s.find("div.drop");a.on("drop",function(t){return i._enabled&&(r.upload(e,i,t.originalEvent.dataTransfer.files,x,o),a.removeClass("over")),!1}).on("dragleave dragexit",function(){return i._enabled&&a.removeClass("over"),!1}).on("dragover",function(){return i._enabled&&a.addClass("over"),!1}),e.on("open",function(){n("body").on("dragover.DTE_Upload drop.DTE_Upload",function(){return!1})}).on("close",function(){n("body").off("dragover.DTE_Upload drop.DTE_Upload")})}else s.addClass("noDrop"),s.append(s.find("div.rendered"));return s.find("div.clearValue button").on("click",function(){r.fieldTypes.upload.set.call(e,i,"")}),s.find("input[type=file]").on("change",function(){r.upload(e,i,this.files,x,o)}),s},E=r.fieldTypes,m=n.extend(!0,{},r.models.fieldType,{get:function(t){return t._input.val()},set:function(t,e){t._input.val(e).trigger("change")},enable:function(t){t._input.prop("disabled",!1)},disable:function(t){t._input.prop("disabled",!0)}});return E.hidden=n.extend(!0,{},m,{create:function(t){return t._val=t.value,null},get:function(t){return t._val},set:function(t,e){t._val=e}}),E.readonly=n.extend(!0,{},m,{create:function(t){return t._input=n("<input/>").attr(n.extend({id:r.safeId(t.id),type:"text",readonly:"readonly"},t.attr||{})),t._input[0]}}),E.text=n.extend(!0,{},m,{create:function(t){return t._input=n("<input/>").attr(n.extend({id:r.safeId(t.id),type:"text"},t.attr||{})),t._input[0]}}),E.password=n.extend(!0,{},m,{create:function(t){return t._input=n("<input/>").attr(n.extend({id:r.safeId(t.id),type:"password"},t.attr||{})),t._input[0]}}),E.textarea=n.extend(!0,{},m,{create:function(t){return t._input=n("<textarea/>").attr(n.extend({id:r.safeId(t.id)},t.attr||{})),t._input[0]}}),E.select=n.extend(!0,{},m,{_addOptions:function(t,e){var i=t._input[0].options;i.length=0,e&&r.pairs(e,t.optionsPair,function(t,e,n){i[n]=new Option(e,t)})},create:function(t){return t._input=n("<select/>").attr(n.extend({id:r.safeId(t.id),multiple:t.multiple===!0},t.attr||{})),E.select._addOptions(t,t.options||t.ipOpts),t._input[0]},update:function(t,e){var i=n(t._input),o=i.val();E.select._addOptions(t,e),i.children('[value="'+o+'"]').length&&i.val(o)},get:function(t){var e=t._input.val();if(t.multiple){if(t.separator)return e.join(t.separator);if(null===e)return[]}return e},set:function(t,e){t.multiple&&t.separator&&!n.isArray(e)&&(e=e.split(t.separator)),t._input.val(e).trigger("change")}}),E.checkbox=n.extend(!0,{},m,{_addOptions:function(t,e){var i=t._input.empty();e&&r.pairs(e,t.optionsPair,function(e,n,o){i.append('<div><input id="'+r.safeId(t.id)+"_"+o+'" type="checkbox" value="'+e+'" /><label for="'+r.safeId(t.id)+"_"+o+'">'+n+"</label></div>")})},create:function(t){return t._input=n("<div />"),E.checkbox._addOptions(t,t.options||t.ipOpts),t._input[0]},get:function(t){var e=[];return t._input.find("input:checked").each(function(){e.push(this.value)}),t.separator?e.join(t.separator):e},set:function(t,e){var i=t._input.find("input");n.isArray(e)||"string"!=typeof e?n.isArray(e)||(e=[e]):e=e.split(t.separator||"|");var o,s,a=e.length;i.each(function(){for(s=!1,o=0;a>o;o++)if(this.value==e[o]){s=!0;break}this.checked=s}).change()},enable:function(t){t._input.find("input").prop("disabled",!1)},disable:function(t){t._input.find("input").prop("disabled",!0)},update:function(t,e){var i=E.checkbox,n=i.get(t);i._addOptions(t,e),i.set(t,n)}}),E.radio=n.extend(!0,{},m,{_addOptions:function(t,e){var i=t._input.empty();e&&r.pairs(e,t.optionsPair,function(e,o,s){i.append('<div><input id="'+r.safeId(t.id)+"_"+s+'" type="radio" name="'+t.name+'" /><label for="'+r.safeId(t.id)+"_"+s+'">'+o+"</label></div>"),n("input:last",i).attr("value",e)[0]._editor_val=e})},create:function(t){return t._input=n("<div />"),E.radio._addOptions(t,t.options||t.ipOpts),this.on("open",function(){t._input.find("input").each(function(){this._preChecked&&(this.checked=!0)})}),t._input[0]},get:function(t){return t=t._input.find("input:checked"),t.length?t[0]._editor_val:i},set:function(t,e){t._input.find("input").each(function(){this._preChecked=!1,this._editor_val==e?this._preChecked=this.checked=!0:this._preChecked=this.checked=!1}),t._input.find("input:checked").change()},enable:function(t){t._input.find("input").prop("disabled",!1)},disable:function(t){t._input.find("input").prop("disabled",!0)},update:function(t,e){var i=E.radio,n=i.get(t);i._addOptions(t,e);var o=t._input.find("input");i.set(t,o.filter('[value="'+n+'"]').length?n:o.eq(0).attr("value"))}}),E.date=n.extend(!0,{},m,{create:function(t){return n.datepicker?(t._input=n("<input />").attr(n.extend({type:"text",id:r.safeId(t.id),"class":"jqueryui"},t.attr||{})),t.dateFormat||(t.dateFormat=n.datepicker.RFC_2822),t.dateImage===i&&(t.dateImage="../../images/calender.png"),setTimeout(function(){n(t._input).datepicker(n.extend({showOn:"both",dateFormat:t.dateFormat,buttonImage:t.dateImage,buttonImageOnly:!0},t.opts)),n("#ui-datepicker-div").css("display","none")},10),t._input[0]):(t._input=n("<input/>").attr(n.extend({id:r.safeId(t.id),type:"date"},t.attr||{})),t._input[0])},set:function(t,e){n.datepicker&&t._input.hasClass("hasDatepicker")?t._input.datepicker("setDate",e).change():n(t._input).val(e)},enable:function(t){n.datepicker?t._input.datepicker("enable"):n(t._input).prop("disabled",!1)},disable:function(t){n.datepicker?t._input.datepicker("disable"):n(t._input).prop("disabled",!0)},owns:function(t,e){return n(e).parents("div.ui-datepicker").length||n(e).parents("div.ui-datepicker-header").length?!0:!1}}),E.upload=n.extend(!0,{},m,{create:function(t){var e=this;return w(e,t,function(i){r.fieldTypes.upload.set.call(e,t,i[0])})},get:function(t){return t._val},set:function(t,e){t._val=e;var i=t._input;if(t.display){var n=i.find("div.rendered");t._val?n.html(t.display(t._val)):n.empty().append("<span>"+(t.noFileText||"No file")+"</span>")}n=i.find("div.clearValue button"),e&&t.clearText?(n.html(t.clearText),i.removeClass("noClear")):i.addClass("noClear"),t._input.find("input").triggerHandler("upload.editor",[t._val])},enable:function(t){t._input.find("input").prop("disabled",!1),t._enabled=!0},disable:function(t){t._input.find("input").prop("disabled",!0),t._enabled=!1}}),E.uploadMany=n.extend(!0,{},m,{create:function(t){var e=this,i=w(e,t,function(i){t._val=t._val.concat(i),r.fieldTypes.uploadMany.set.call(e,t,t._val)});return i.addClass("multi").on("click","button.remove",function(){var i=n(this).data("idx");t._val.splice(i,1),r.fieldTypes.uploadMany.set.call(e,t,t._val)}),i},get:function(t){return t._val},set:function(t,e){if(e||(e=[]),!n.isArray(e))throw"Upload collections must have an array as a value";t._val=e;var i=this,o=t._input;if(t.display)if(o=o.find("div.rendered").empty(),e.length){var s=n("<ul/>").appendTo(o);n.each(e,function(e,n){s.append("<li>"+t.display(n,e)+' <button class="'+i.classes.form.button+' remove" data-idx="'+e+'">&times;</button></li>')})}else o.append("<span>"+(t.noFileText||"No files")+"</span>");t._input.find("input").triggerHandler("upload.editor",[t._val])},enable:function(t){t._input.find("input").prop("disabled",!1),t._enabled=!0},disable:function(t){t._input.find("input").prop("disabled",!0),t._enabled=!1}}),o.ext.editorFields&&n.extend(r.fieldTypes,o.ext.editorFields),o.ext.editorFields=r.fieldTypes,r.files={},r.prototype.CLASS="Editor",r.version="1.5.1",r};"function"==typeof define&&define.amd?define(["jquery","datatables"],n):"object"==typeof exports?n(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.Editor&&n(jQuery,jQuery.fn.dataTable)}(window,document),function(t,e){e.ext.editorFields||(e.ext.editorFields={}),e.ext.editorFields.display={create:function(e){return e._div=t("<div/>").attr(t.extend({id:e.id},e.attr||{})),e._div[0]},get:function(t){return t._rawHtml},set:function(t,e){t._rawHtml=e,t._div.html(e)},enable:function(){},disable:function(){}}}(jQuery,jQuery.fn.dataTable);


/*! FixedColumns 3.1.0 *//*
!function(t,e,i){var o=function(o,l){var s=function(t,e){var l=this;if(this instanceof s){(e===i||!0===e)&&(e={});var n=o.fn.dataTable.camelToHungarian;if(n&&(n(s.defaults,s.defaults,!0),n(s.defaults,e)),n=new o.fn.dataTable.Api(t).settings()[0],this.s={dt:n,iTableColumns:n.aoColumns.length,aiOuterWidths:[],aiInnerWidths:[]},this.dom={scroller:null,header:null,body:null,footer:null,grid:{wrapper:null,dt:null,left:{wrapper:null,head:null,body:null,foot:null},right:{wrapper:null,head:null,body:null,foot:null}},clone:{left:{header:null,body:null,footer:null},right:{header:null,body:null,footer:null}}},n._oFixedColumns)throw"FixedColumns already initialised on this table";n._oFixedColumns=this,n._bInitComplete?this._fnConstruct(e):n.oApi._fnCallbackReg(n,"aoInitComplete",function(){l._fnConstruct(e)},"FixedColumns")}else alert("FixedColumns warning: FixedColumns must be initialised with the 'new' keyword.")};return s.prototype={fnUpdate:function(){this._fnDraw(!0)},fnRedrawLayout:function(){this._fnColCalc(),this._fnGridLayout(),this.fnUpdate()},fnRecalculateHeight:function(t){delete t._DTTC_iHeight,t.style.height="auto"},fnSetRowHeight:function(t,e){t.style.height=e+"px"},fnGetPosition:function(t){var e=this.s.dt.oInstance;if(o(t).parents(".DTFC_Cloned").length){if("tr"===t.nodeName.toLowerCase())return t=o(t).index(),e.fnGetPosition(o("tr",this.s.dt.nTBody)[t]);var i=o(t).index(),t=o(t.parentNode).index();return[e.fnGetPosition(o("tr",this.s.dt.nTBody)[t]),i,e.oApi._fnVisibleToColumnIndex(this.s.dt,i)]}return e.fnGetPosition(t)},_fnConstruct:function(i){var l=this;if("function"!=typeof this.s.dt.oInstance.fnVersionCheck||!0!==this.s.dt.oInstance.fnVersionCheck("1.8.0"))alert("FixedColumns "+s.VERSION+" required DataTables 1.8.0 or later. Please upgrade your DataTables installation");else if(""===this.s.dt.oScroll.sX)this.s.dt.oInstance.oApi._fnLog(this.s.dt,1,"FixedColumns is not needed (no x-scrolling in DataTables enabled), so no action will be taken. Use 'FixedHeader' for column fixing when scrolling is not enabled");else{this.s=o.extend(!0,this.s,s.defaults,i),i=this.s.dt.oClasses,this.dom.grid.dt=o(this.s.dt.nTable).parents("div."+i.sScrollWrapper)[0],this.dom.scroller=o("div."+i.sScrollBody,this.dom.grid.dt)[0],this._fnColCalc(),this._fnGridSetup();var n;o(this.dom.scroller).on("mouseover.DTFC touchstart.DTFC",function(){n="main"}).on("scroll.DTFC",function(t){!n&&t.originalEvent&&(n="main"),"main"===n&&(0<l.s.iLeftColumns&&(l.dom.grid.left.liner.scrollTop=l.dom.scroller.scrollTop),0<l.s.iRightColumns)&&(l.dom.grid.right.liner.scrollTop=l.dom.scroller.scrollTop)});var r="onwheel"in e.createElement("div")?"wheel.DTFC":"mousewheel.DTFC";0<l.s.iLeftColumns&&o(l.dom.grid.left.liner).on("mouseover.DTFC touchstart.DTFC",function(){n="left"}).on("scroll.DTFC",function(t){!n&&t.originalEvent&&(n="left"),"left"===n&&(l.dom.scroller.scrollTop=l.dom.grid.left.liner.scrollTop,0<l.s.iRightColumns&&(l.dom.grid.right.liner.scrollTop=l.dom.grid.left.liner.scrollTop))}).on(r,function(t){l.dom.scroller.scrollLeft-="wheel"===t.type?-t.originalEvent.deltaX:t.originalEvent.wheelDeltaX}),0<l.s.iRightColumns&&o(l.dom.grid.right.liner).on("mouseover.DTFC touchstart.DTFC",function(){n="right"}).on("scroll.DTFC",function(t){!n&&t.originalEvent&&(n="right"),"right"===n&&(l.dom.scroller.scrollTop=l.dom.grid.right.liner.scrollTop,0<l.s.iLeftColumns&&(l.dom.grid.left.liner.scrollTop=l.dom.grid.right.liner.scrollTop))}).on(r,function(t){l.dom.scroller.scrollLeft-="wheel"===t.type?-t.originalEvent.deltaX:t.originalEvent.wheelDeltaX}),o(t).on("resize.DTFC",function(){l._fnGridLayout.call(l)});var d=!0,a=o(this.s.dt.nTable);a.on("draw.dt.DTFC",function(){l._fnDraw.call(l,d),d=!1}).on("column-sizing.dt.DTFC",function(){l._fnColCalc(),l._fnGridLayout(l)}).on("column-visibility.dt.DTFC",function(){l._fnColCalc(),l._fnGridLayout(l),l._fnDraw(!0)}).on("destroy.dt.DTFC",function(){a.off("column-sizing.dt.DTFC column-visibility.dt.DTFC destroy.dt.DTFC draw.dt.DTFC"),o(l.dom.scroller).off("mouseover.DTFC touchstart.DTFC scroll.DTFC"),o(t).off("resize.DTFC"),o(l.dom.grid.left.liner).off("mouseover.DTFC touchstart.DTFC scroll.DTFC "+r),o(l.dom.grid.left.wrapper).remove(),o(l.dom.grid.right.liner).off("mouseover.DTFC touchstart.DTFC scroll.DTFC "+r),o(l.dom.grid.right.wrapper).remove()}),this._fnGridLayout(),this.s.dt.oInstance.fnDraw(!1)}},_fnColCalc:function(){var t=this,e=0,i=0;this.s.aiInnerWidths=[],this.s.aiOuterWidths=[],o.each(this.s.dt.aoColumns,function(l,s){var n,r=o(s.nTh);if(r.filter(":visible").length){var d=r.outerWidth();0===t.s.aiOuterWidths.length&&(n=o(t.s.dt.nTable).css("border-left-width"),d+="string"==typeof n?1:parseInt(n,10)),t.s.aiOuterWidths.length===t.s.dt.aoColumns.length-1&&(n=o(t.s.dt.nTable).css("border-right-width"),d+="string"==typeof n?1:parseInt(n,10)),t.s.aiOuterWidths.push(d),t.s.aiInnerWidths.push(r.width()),l<t.s.iLeftColumns&&(e+=d),t.s.iTableColumns-t.s.iRightColumns<=l&&(i+=d)}else t.s.aiInnerWidths.push(0),t.s.aiOuterWidths.push(0)}),this.s.iLeftWidth=e,this.s.iRightWidth=i},_fnGridSetup:function(){var t,e=this._fnDTOverflow();this.dom.body=this.s.dt.nTable,this.dom.header=this.s.dt.nTHead.parentNode,this.dom.header.parentNode.parentNode.style.position="relative";var i=o('<div class="DTFC_ScrollWrapper" style="position:relative; clear:both;"><div class="DTFC_LeftWrapper" style="position:absolute; top:0; left:0;"><div class="DTFC_LeftHeadWrapper" style="position:relative; top:0; left:0; overflow:hidden;"></div><div class="DTFC_LeftBodyWrapper" style="position:relative; top:0; left:0; overflow:hidden;"><div class="DTFC_LeftBodyLiner" style="position:relative; top:0; left:0; overflow-y:scroll;"></div></div><div class="DTFC_LeftFootWrapper" style="position:relative; top:0; left:0; overflow:hidden;"></div></div><div class="DTFC_RightWrapper" style="position:absolute; top:0; left:0;"><div class="DTFC_RightHeadWrapper" style="position:relative; top:0; left:0;"><div class="DTFC_RightHeadBlocker DTFC_Blocker" style="position:absolute; top:0; bottom:0;"></div></div><div class="DTFC_RightBodyWrapper" style="position:relative; top:0; left:0; overflow:hidden;"><div class="DTFC_RightBodyLiner" style="position:relative; top:0; left:0; overflow-y:scroll;"></div></div><div class="DTFC_RightFootWrapper" style="position:relative; top:0; left:0;"><div class="DTFC_RightFootBlocker DTFC_Blocker" style="position:absolute; top:0; bottom:0;"></div></div></div></div>')[0],l=i.childNodes[0],s=i.childNodes[1];this.dom.grid.dt.parentNode.insertBefore(i,this.dom.grid.dt),i.appendChild(this.dom.grid.dt),this.dom.grid.wrapper=i,0<this.s.iLeftColumns&&(this.dom.grid.left.wrapper=l,this.dom.grid.left.head=l.childNodes[0],this.dom.grid.left.body=l.childNodes[1],this.dom.grid.left.liner=o("div.DTFC_LeftBodyLiner",i)[0],i.appendChild(l)),0<this.s.iRightColumns&&(this.dom.grid.right.wrapper=s,this.dom.grid.right.head=s.childNodes[0],this.dom.grid.right.body=s.childNodes[1],this.dom.grid.right.liner=o("div.DTFC_RightBodyLiner",i)[0],t=o("div.DTFC_RightHeadBlocker",i)[0],t.style.width=e.bar+"px",t.style.right=-e.bar+"px",this.dom.grid.right.headBlock=t,t=o("div.DTFC_RightFootBlocker",i)[0],t.style.width=e.bar+"px",t.style.right=-e.bar+"px",this.dom.grid.right.footBlock=t,i.appendChild(s)),this.s.dt.nTFoot&&(this.dom.footer=this.s.dt.nTFoot.parentNode,0<this.s.iLeftColumns&&(this.dom.grid.left.foot=l.childNodes[2]),0<this.s.iRightColumns)&&(this.dom.grid.right.foot=s.childNodes[2])},_fnGridLayout:function(){var t=this.dom.grid,e=o(t.wrapper).width(),i=o(this.s.dt.nTable.parentNode).outerHeight(),l=o(this.s.dt.nTable.parentNode.parentNode).outerHeight(),s=this._fnDTOverflow(),n=this.s.iLeftWidth,r=this.s.iRightWidth,d=function(t,e){s.bar?t.style.width=e+s.bar+"px":(t.style.width=e+20+"px",t.style.paddingRight="20px",t.style.boxSizing="border-box")};s.x&&(i-=s.bar),t.wrapper.style.height=l+"px",0<this.s.iLeftColumns&&(t.left.wrapper.style.width=n+"px",t.left.wrapper.style.height="1px",t.left.body.style.height=i+"px",t.left.foot&&(t.left.foot.style.top=(s.x?s.bar:0)+"px"),d(t.left.liner,n),t.left.liner.style.height=i+"px"),0<this.s.iRightColumns&&(e-=r,s.y&&(e-=s.bar),t.right.wrapper.style.width=r+"px",t.right.wrapper.style.left=e+"px",t.right.wrapper.style.height="1px",t.right.body.style.height=i+"px",t.right.foot&&(t.right.foot.style.top=(s.x?s.bar:0)+"px"),d(t.right.liner,r),t.right.liner.style.height=i+"px",t.right.headBlock.style.display=s.y?"block":"none",t.right.footBlock.style.display=s.y?"block":"none")},_fnDTOverflow:function(){var t=this.s.dt.nTable,e=t.parentNode,i={x:!1,y:!1,bar:this.s.dt.oScroll.iBarWidth};return t.offsetWidth>e.clientWidth&&(i.x=!0),t.offsetHeight>e.clientHeight&&(i.y=!0),i},_fnDraw:function(t){this._fnGridLayout(),this._fnCloneLeft(t),this._fnCloneRight(t),null!==this.s.fnDrawCallback&&this.s.fnDrawCallback.call(this,this.dom.clone.left,this.dom.clone.right),o(this).trigger("draw.dtfc",{leftClone:this.dom.clone.left,rightClone:this.dom.clone.right})},_fnCloneRight:function(t){if(!(0>=this.s.iRightColumns)){var e,i=[];for(e=this.s.iTableColumns-this.s.iRightColumns;e<this.s.iTableColumns;e++)this.s.dt.aoColumns[e].bVisible&&i.push(e);this._fnClone(this.dom.clone.right,this.dom.grid.right,i,t)}},_fnCloneLeft:function(t){if(!(0>=this.s.iLeftColumns)){var e,i=[];for(e=0;e<this.s.iLeftColumns;e++)this.s.dt.aoColumns[e].bVisible&&i.push(e);this._fnClone(this.dom.clone.left,this.dom.grid.left,i,t)}},_fnCopyLayout:function(t,e,i){for(var l=[],s=[],n=[],r=0,d=t.length;d>r;r++){var a=[];a.nTr=o(t[r].nTr).clone(i,!1)[0];for(var h=0,f=this.s.iTableColumns;f>h;h++)if(-1!==o.inArray(h,e)){var u=o.inArray(t[r][h].cell,n);-1===u?(u=o(t[r][h].cell).clone(i,!1)[0],s.push(u),n.push(t[r][h].cell),a.push({cell:u,unique:t[r][h].unique})):a.push({cell:s[u],unique:t[r][h].unique})}l.push(a)}return l},_fnClone:function(t,e,l,s){var n,r,d,a,h,f,u,c,p,g=this,m=this.s.dt;if(s){for(o(t.header).remove(),t.header=o(this.dom.header).clone(!0,!1)[0],t.header.className+=" DTFC_Cloned",t.header.style.width="100%",e.head.appendChild(t.header),c=this._fnCopyLayout(m.aoHeader,l,!0),a=o(">thead",t.header),a.empty(),n=0,r=c.length;r>n;n++)a[0].appendChild(c[n].nTr);m.oApi._fnDrawHead(m,c,!0)}else for(c=this._fnCopyLayout(m.aoHeader,l,!1),p=[],m.oApi._fnDetectHeader(p,o(">thead",t.header)[0]),n=0,r=c.length;r>n;n++)for(d=0,a=c[n].length;a>d;d++)p[n][d].cell.className=c[n][d].cell.className,o("span.DataTables_sort_icon",p[n][d].cell).each(function(){this.className=o("span.DataTables_sort_icon",c[n][d].cell)[0].className});this._fnEqualiseHeights("thead",this.dom.header,t.header),"auto"==this.s.sHeightMatch&&o(">tbody>tr",g.dom.body).css("height","auto"),null!==t.body&&(o(t.body).remove(),t.body=null),t.body=o(this.dom.body).clone(!0)[0],t.body.className+=" DTFC_Cloned",t.body.style.paddingBottom=m.oScroll.iBarWidth+"px",t.body.style.marginBottom=2*m.oScroll.iBarWidth+"px",null!==t.body.getAttribute("id")&&t.body.removeAttribute("id"),o(">thead>tr",t.body).empty(),o(">tfoot",t.body).remove();var C=o("tbody",t.body)[0];if(o(C).empty(),0<m.aiDisplay.length){for(r=o(">thead>tr",t.body)[0],u=0;u<l.length;u++)h=l[u],f=o(m.aoColumns[h].nTh).clone(!0)[0],f.innerHTML="",a=f.style,a.paddingTop="0",a.paddingBottom="0",a.borderTopWidth="0",a.borderBottomWidth="0",a.height=0,a.width=g.s.aiInnerWidths[h]+"px",r.appendChild(f);o(">tbody>tr",g.dom.body).each(function(t){var e=this.cloneNode(!1);for(e.removeAttribute("id"),t=g.s.dt.aoData[g.s.dt.oFeatures.bServerSide===!1?g.s.dt.aiDisplay[g.s.dt._iDisplayStart+t]:t].anCells||o(this).children("td, th"),u=0;u<l.length;u++)h=l[u],t.length>0&&(f=o(t[h]).clone(!0,!0)[0],e.appendChild(f));C.appendChild(e)})}else o(">tbody>tr",g.dom.body).each(function(){f=this.cloneNode(!0),f.className=f.className+" DTFC_NoData",o("td",f).html(""),C.appendChild(f)});if(t.body.style.width="100%",t.body.style.margin="0",t.body.style.padding="0",m.oScroller!==i&&(r=m.oScroller.dom.force,e.forcer?e.forcer.style.height=r.style.height:(e.forcer=r.cloneNode(!0),e.liner.appendChild(e.forcer))),e.liner.appendChild(t.body),this._fnEqualiseHeights("tbody",g.dom.body,t.body),null!==m.nTFoot){if(s){for(null!==t.footer&&t.footer.parentNode.removeChild(t.footer),t.footer=o(this.dom.footer).clone(!0,!0)[0],t.footer.className+=" DTFC_Cloned",t.footer.style.width="100%",e.foot.appendChild(t.footer),c=this._fnCopyLayout(m.aoFooter,l,!0),e=o(">tfoot",t.footer),e.empty(),n=0,r=c.length;r>n;n++)e[0].appendChild(c[n].nTr);m.oApi._fnDrawHead(m,c,!0)}else for(c=this._fnCopyLayout(m.aoFooter,l,!1),e=[],m.oApi._fnDetectHeader(e,o(">tfoot",t.footer)[0]),n=0,r=c.length;r>n;n++)for(d=0,a=c[n].length;a>d;d++)e[n][d].cell.className=c[n][d].cell.className;this._fnEqualiseHeights("tfoot",this.dom.footer,t.footer)}e=m.oApi._fnGetUniqueThs(m,o(">thead",t.header)[0]),o(e).each(function(t){h=l[t],this.style.width=g.s.aiInnerWidths[h]+"px"}),null!==g.s.dt.nTFoot&&(e=m.oApi._fnGetUniqueThs(m,o(">tfoot",t.footer)[0]),o(e).each(function(t){h=l[t],this.style.width=g.s.aiInnerWidths[h]+"px"}))},_fnGetTrNodes:function(t){for(var e=[],i=0,o=t.childNodes.length;o>i;i++)"TR"==t.childNodes[i].nodeName.toUpperCase()&&e.push(t.childNodes[i]);return e},_fnEqualiseHeights:function(t,e,i){if("none"!=this.s.sHeightMatch||"thead"===t||"tfoot"===t){var l,s,n=e.getElementsByTagName(t)[0],i=i.getElementsByTagName(t)[0],t=o(">"+t+">tr:eq(0)",e).children(":first");t.outerHeight(),t.height();for(var n=this._fnGetTrNodes(n),e=this._fnGetTrNodes(i),r=[],i=0,t=e.length;t>i;i++)l=n[i].offsetHeight,s=e[i].offsetHeight,l=s>l?s:l,"semiauto"==this.s.sHeightMatch&&(n[i]._DTTC_iHeight=l),r.push(l);for(i=0,t=e.length;t>i;i++)e[i].style.height=r[i]+"px",n[i].style.height=r[i]+"px"}}},s.defaults={iLeftColumns:1,iRightColumns:0,fnDrawCallback:null,sHeightMatch:"semiauto"},s.version="3.1.0",l.Api.register("fixedColumns()",function(){return this}),l.Api.register("fixedColumns().update()",function(){return this.iterator("table",function(t){t._oFixedColumns&&t._oFixedColumns.fnUpdate()})}),l.Api.register("fixedColumns().relayout()",function(){return this.iterator("table",function(t){t._oFixedColumns&&t._oFixedColumns.fnRedrawLayout()})}),l.Api.register("rows().recalcHeight()",function(){return this.iterator("row",function(t,e){t._oFixedColumns&&t._oFixedColumns.fnRecalculateHeight(this.row(e).node())})}),l.Api.register("fixedColumns().rowIndex()",function(t){return t=o(t),t.parents(".DTFC_Cloned").length?this.rows({page:"current"}).indexes()[t.index()]:this.row(t).index()}),l.Api.register("fixedColumns().cellIndex()",function(t){if(t=o(t),t.parents(".DTFC_Cloned").length){var e=t.parent().index(),e=this.rows({page:"current"}).indexes()[e],t=t.parents(".DTFC_LeftWrapper").length?t.index():this.columns().flatten().length-this.context[0]._oFixedColumns.s.iRightColumns+t.index();return{row:e,column:this.column.index("toData",t),columnVisible:t}}return this.cell(t).index()}),o(e).on("init.dt.fixedColumns",function(t,e){if("dt"===t.namespace){var i=e.oInit.fixedColumns,n=l.defaults.fixedColumns;(i||n)&&(n=o.extend({},i,n),!1!==i&&new s(e,n))}}),o.fn.dataTable.FixedColumns=s,o.fn.DataTable.FixedColumns=s};"function"==typeof define&&define.amd?define(["jquery","datatables"],o):"object"==typeof exports?o(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.FixedColumns&&o(jQuery,jQuery.fn.dataTable)}(window,document);
*/


/*! FixedHeader 3.0.0 *//*
!function(t,o){var e=function(e,i){var d=0,s=function(o,n){if(!(this instanceof s))throw"FixedHeader must be initialised with the 'new' keyword.";!0===n&&(n={}),o=new i.Api(o),this.c=e.extend(!0,{},s.defaults,n),this.s={dt:o,position:{theadTop:0,tbodyTop:0,tfootTop:0,tfootBottom:0,width:0,left:0,tfootHeight:0,theadHeight:0,windowHeight:e(t).height(),visible:!0},headerMode:null,footerMode:null,namespace:".dtfc"+d++},this.dom={floatingHeader:null,thead:e(o.table().header()),tbody:e(o.table().body()),tfoot:e(o.table().footer()),header:{host:null,floating:null,placeholder:null},footer:{host:null,floating:null,placeholder:null}},this.dom.header.host=this.dom.thead.parent(),this.dom.footer.host=this.dom.tfoot.parent();var a=o.settings()[0];if(a._fixedHeader)throw"FixedHeader already initialised on table "+a.nTable.id;a._fixedHeader=this,this._constructor()};return s.prototype={update:function(){this._positions(),this._scroll(!0)},_constructor:function(){var o=this,i=this.s.dt;e(t).on("scroll"+this.s.namespace,function(){o._scroll()}).on("resize"+this.s.namespace,function(){o.s.position.windowHeight=e(t).height(),o._positions(),o._scroll(!0)}),i.on("column-reorder.dt.dtfc column-visibility.dt.dtfc",function(){o._positions(),o._scroll(!0)}).on("draw.dtfc",function(){o._positions(),o._scroll()}),i.on("destroy.dtfc",function(){i.off(".dtfc"),e(t).off(this.s.namespace)}),this._positions(),this._scroll()},_clone:function(t,o){var i=this.s.dt,d=this.dom[t],s="header"===t?this.dom.thead:this.dom.tfoot;!o&&d.floating?d.floating.removeClass("fixedHeader-floating fixedHeader-locked"):(d.floating&&(d.placeholder.remove(),d.floating.children().detach(),d.floating.remove()),d.floating=e(i.table().node().cloneNode(!1)).removeAttr("id").append(s).appendTo("body"),d.placeholder=s.clone(!1),d.host.append(d.placeholder),"footer"===t&&this._footerMatch(d.placeholder,d.floating))},_footerMatch:function(t,o){var i=function(i){var d=e(i,t).map(function(){return e(this).width()}).toArray();e(i,o).each(function(t){e(this).width(d[t])})};i("th"),i("td")},_footerUnsize:function(){var t=this.dom.footer.floating;t&&e("th, td",t).css("width","")},_modeChange:function(t,o,e){var i=this.dom[o],d=this.s.position;"in-place"===t?(i.placeholder&&(i.placeholder.remove(),i.placeholder=null),i.host.append("header"===o?this.dom.thead:this.dom.tfoot),i.floating&&(i.floating.remove(),i.floating=null),"footer"===o&&this._footerUnsize()):"in"===t?(this._clone(o,e),i.floating.addClass("fixedHeader-floating").css("header"===o?"top":"bottom",this.c[o+"Offset"]).css("left",d.left+"px").css("width",d.width+"px"),"footer"===o&&i.floating.css("top","")):"below"===t?(this._clone(o,e),i.floating.addClass("fixedHeader-locked").css("top",d.tfootTop-d.theadHeight).css("left",d.left+"px").css("width",d.width+"px")):"above"===t&&(this._clone(o,e),i.floating.addClass("fixedHeader-locked").css("top",d.tbodyTop).css("left",d.left+"px").css("width",d.width+"px")),this.s[o+"Mode"]=t},_positions:function(){var t=this.s.dt.table(),o=this.s.position,i=this.dom,t=e(t.node()),d=t.children("thead"),s=t.children("tfoot"),i=i.tbody;o.visible=t.is(":visible"),o.width=t.outerWidth(),o.left=t.offset().left,o.theadTop=d.offset().top,o.tbodyTop=i.offset().top,o.theadHeight=o.tbodyTop-o.theadTop,s.length?(o.tfootTop=s.offset().top,o.tfootBottom=o.tfootTop+s.outerHeight(),o.tfootHeight=o.tfootBottom-o.tfootTop):(o.tfootTop=o.tbodyTop+i.outerHeight(),o.tfootBottom=o.tfootTop,o.tfootHeight=o.tfootTop)},_scroll:function(t){var i,d=e(o).scrollTop(),s=this.s.position;this.c.header&&(i=!s.visible||d<=s.theadTop-this.c.headerOffset?"in-place":d<=s.tfootTop-s.theadHeight-this.c.headerOffset?"in":"below",(t||i!==this.s.headerMode)&&this._modeChange(i,"header",t)),this.c.footer&&this.dom.tfoot.length&&(d=!s.visible||d+s.windowHeight>=s.tfootBottom+this.c.footerOffset?"in-place":s.windowHeight+d>s.tbodyTop+s.tfootHeight+this.c.footerOffset?"in":"above",(t||d!==this.s.footerMode)&&this._modeChange(d,"footer",t))}},s.version="3.0.0",s.defaults={header:!0,footer:!1,headerOffset:0,footerOffset:0},e.fn.dataTable.FixedHeader=s,e.fn.DataTable.FixedHeader=s,e(o).on("init.dt.dtb",function(t,o){if("dt"===t.namespace){var e=o.oInit.fixedHeader||i.defaults.fixedHeader;e&&!o._buttons&&new s(o,e)}}),i.Api.register("fixedHeader()",function(){}),i.Api.register("fixedHeader.adjust()",function(){return this.iterator("table",function(t){(t=t._fixedHeader)&&t.update()})}),s};"function"==typeof define&&define.amd?define(["jquery","datatables"],e):"object"==typeof exports?e(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.FixedHeader&&e(jQuery,jQuery.fn.dataTable)}(window,document);
*/


/*! KeyTable 2.0.0 *//*
!function(e,t,s){var i=function(i,n){var l=function(e,t){if(!n.versionCheck||!n.versionCheck("1.10.8"))throw"KeyTable requires DataTables 1.10.8 or newer";this.c=i.extend(!0,{},n.defaults.keyTable,l.defaults,t),this.s={dt:new n.Api(e),enable:!0},this.dom={};var s=this.s.dt.settings()[0],a=s.keytable;return a?a:(s.keytable=this,void this._constructor())};return l.prototype={blur:function(){this._blur()},enable:function(e){this.s.enable=e},focus:function(e,t){this._focus(this.s.dt.cell(e,t))},_constructor:function(){this._tabInput();var e=this,s=this.s.dt,n=i(s.table().node());"static"===n.css("position")&&n.css("position","relative"),i(s.table().body()).on("click.keyTable","th, td",function(){if(!1!==e.s.enable){var t=s.cell(this);t.any()&&e._focus(t)}}),i(t.body).on("keydown.keyTable",function(t){e._key(t)}),this.c.blurable&&i(t.body).on("click.keyTable",function(t){i(t.target).parents(".dataTables_filter").length&&e._blur(),i(t.target).parents().filter(s.table().container()).length||i(t.target).parents("div.DTE").length||e._blur()}),this.c.editor&&s.on("key.kt",function(t,s,i,n,l){e._editor(i,l)}),s.settings()[0].oFeatures.bStateSave&&s.on("stateSaveParams.keyTable",function(t,s,i){i.keyTable=e.s.lastFocus?e.s.lastFocus.index():null}),s.on("destroy.keyTable",function(){s.off(".keyTable"),i(s.table().body()).off("click.keyTable","th, td"),i(t.body).off("keydown.keyTable").off("click.keyTable")}),(n=s.state.loaded())&&n.keyTable?s.cell(n.keyTable).focus():this.c.focus&&s.cell(this.c.focus).focus()},_blur:function(){if(this.s.enable&&this.s.lastFocus){var e=this.s.lastFocus;i(e.node()).removeClass(this.c.className),this.s.lastFocus=null,this._emitEvent("key-blur",[this.s.dt,e])}},_columns:function(){var e=this.s.dt,t=e.columns(this.c.columns).indexes(),s=[];return e.columns(":visible").every(function(e){-1!==t.indexOf(e)&&s.push(e)}),s},_editor:function(e,t){var s=this.s.dt,n=this.c.editor;t.stopPropagation(),n.inline(this.s.lastFocus.index());var l=i("div.DTE input");l.length&&l[0].select(),s.keys.enable("navigation-only"),s.one("key-blur.editor",function(){n.displayed()&&n.submit()}),n.one("close",function(){s.keys.enable(!0),s.off("key-blur.editor")})},_emitEvent:function(e,t){this.s.dt.iterator("table",function(s){i(s.nTable).triggerHandler(e,t)})},_focus:function(s,n){var l=this,a=this.s.dt,o=a.page.info(),r=this.s.lastFocus;if(this.s.enable){if("number"!=typeof s){var c=s.index(),n=c.column,s=a.rows({filter:"applied",order:"applied"}).indexes().indexOf(c.row);o.serverSide&&(s+=o.start)}if(s<o.start||s>=o.start+o.length)a.one("draw",function(){l._focus(s,n)}).page(Math.floor(s/o.length)).draw(!1);else if(-1!==i.inArray(n,this._columns())){if(o.serverSide&&(s-=o.start),o=a.cell(":eq("+s+")",n),r){if(r.node()===o.node())return;this._blur()}r=i(o.node()),r.addClass(this.c.className),this._scroll(i(e),i(t.body),r,"offset"),c=a.table().body().parentNode,c!==a.table().header().parentNode&&(c=i(c.parentNode),this._scroll(c,c,r,"position")),this.s.lastFocus=o,this._emitEvent("key-focus",[this.s.dt,o]),a.state.save()}}},_key:function(e){if(this.s.enable&&!(0===e.keyCode||e.ctrlKey||e.metaKey||e.altKey)){var t=this.s.lastFocus;if(t){var s=this,n=this.s.dt;if(!this.c.keys||-1!==i.inArray(e.keyCode,this.c.keys))switch(e.keyCode){case 9:this._shift(e,e.shiftKey?"left":"right",!0);break;case 27:this.s.blurable&&!0===this.s.enable&&this._blur();break;case 33:case 34:e.preventDefault();var l=n.cells({page:"current"}).nodes().indexOf(t.node());n.one("draw",function(){var e=n.cells({page:"current"}).nodes();s._focus(n.cell(l<e.length?e[l]:e[e.length-1]))}).page(33===e.keyCode?"previous":"next").draw(!1);break;case 35:case 36:e.preventDefault(),t=n.cells({page:"current"}).indexes(),this._focus(n.cell(t[35===e.keyCode?t.length-1:0]));break;case 37:this._shift(e,"left");break;case 38:this._shift(e,"up");break;case 39:this._shift(e,"right");break;case 40:this._shift(e,"down");break;default:!0===this.s.enable&&this._emitEvent("key",[n,e.keyCode,this.s.lastFocus,e])}}}},_scroll:function(e,t,s,i){var i=s[i](),n=s.outerHeight(),s=s.outerWidth(),l=t.scrollTop(),a=t.scrollLeft(),o=e.height(),e=e.width();i.top<l&&t.scrollTop(i.top),i.left<a&&t.scrollLeft(i.left),i.top+n>l+o&&t.scrollTop(i.top+n-o),i.left+s>a+e&&t.scrollLeft(i.left+s-e)},_shift:function(e,t,s){var i=this.s.dt,n=i.page.info(),l=n.recordsDisplay,a=this.s.lastFocus,o=this._columns();if(a){var r=i.rows({filter:"applied",order:"applied"}).indexes().indexOf(a.index().row);n.serverSide&&(r+=n.start),i=i.columns(o).indexes().indexOf(a.index().column),n=o[i],"right"===t?i>=o.length-1?(r++,n=o[0]):n=o[i+1]:"left"===t?0===i?(r--,n=o[o.length-1]):n=o[i-1]:"up"===t?r--:"down"===t&&r++,r>=0&&l>r&&n>=0&&n<=o.length?(e.preventDefault(),this._focus(r,n)):s&&this.c.blurable?this._blur():e.preventDefault()}},_tabInput:function(){var e=this,t=this.s.dt,s=null!==this.c.tabIndex?this.c.tabIndex:t.settings()[0].iTabIndex;-1!=s&&i('<div><input type="text" tabindex="'+s+'"/></div>').css({position:"absolute",height:1,width:0,overflow:"hidden"}).insertBefore(t.table().node()).children().on("focus",function(){e._focus(t.cell(":eq(0)",{page:"current"}))})}},l.defaults={blurable:!0,className:"focus",columns:"",editor:null,focus:null,keys:null,tabIndex:null},l.version="2.0.0",i.fn.dataTable.KeyTable=l,i.fn.DataTable.KeyTable=l,n.Api.register("cell.blur()",function(){return this.iterator("table",function(e){e.keytable&&e.keytable.blur()})}),n.Api.register("cell().focus()",function(){return this.iterator("cell",function(e,t,s){e.keytable&&e.keytable.focus(t,s)})}),n.Api.register("keys.disable()",function(){return this.iterator("table",function(e){e.keytable&&e.keytable.enable(!1)})}),n.Api.register("keys.enable()",function(e){return this.iterator("table",function(t){t.keytable&&t.keytable.enable(e===s?!0:e)})}),i(t).on("preInit.dt.dtk",function(e,t){if("dt"===e.namespace){var s=t.oInit.keys,a=n.defaults.keys;(s||a)&&(a=i.extend({},s,a),!1!==s&&new l(t,a))}}),l};"function"==typeof define&&define.amd?define(["jquery","datatables"],i):"object"==typeof exports?i(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.KeyTable&&i(jQuery,jQuery.fn.dataTable)}(window,document);*/


/*! Responsive 1.0.7 */
!function(e,t){var n=function(n,i){var s=function(e,t){if(!i.versionCheck||!i.versionCheck("1.10.1"))throw"DataTables Responsive requires DataTables 1.10.1 or newer";this.s={dt:new i.Api(e),columns:[]},this.s.dt.settings()[0].responsive||(t&&"string"==typeof t.details&&(t.details={type:t.details}),this.c=n.extend(!0,{},s.defaults,i.defaults.responsive,t),e.responsive=this,this._constructor())};s.prototype={_constructor:function(){var t=this,i=this.s.dt;i.settings()[0]._responsive=this,n(e).on("resize.dtr orientationchange.dtr",i.settings()[0].oApi._fnThrottle(function(){t._resize()})),i.on("destroy.dtr",function(){n(e).off("resize.dtr orientationchange.dtr draw.dtr")}),this.c.breakpoints.sort(function(e,t){return e.width<t.width?1:e.width>t.width?-1:0}),this._classLogic(),this._resizeAuto();var s=this.c.details;s.type&&(t._detailsInit(),this._detailsVis(),i.on("column-visibility.dtr",function(){t._detailsVis()}),i.on("draw.dtr",function(){i.rows({page:"current"}).iterator("row",function(e,n){var s=i.row(n);if(s.child.isShown()){var r=t.c.details.renderer(i,n);s.child(r,"child").show()}})}),n(i.table().node()).addClass("dtr-"+s.type)),this._resize()},_columnsVisiblity:function(e){var t,i,s=this.s.dt,r=this.s.columns,o=n.map(r,function(t){return t.auto&&null===t.minWidth?!1:!0===t.auto?"-":-1!==n.inArray(e,t.includeIn)}),a=0;for(t=0,i=o.length;i>t;t++)!0===o[t]&&(a+=r[t].minWidth);for(t=s.settings()[0].oScroll,t=t.sY||t.sX?t.iBarWidth:0,s=s.table().container().offsetWidth-t-a,t=0,i=o.length;i>t;t++)r[t].control&&(s-=r[t].minWidth);for(a=!1,t=0,i=o.length;i>t;t++)"-"===o[t]&&!r[t].control&&(a||0>s-r[t].minWidth?(a=!0,o[t]=!1):o[t]=!0,s-=r[t].minWidth);for(s=!1,t=0,i=r.length;i>t;t++)if(!r[t].control&&!r[t].never&&!o[t]){s=!0;break}for(t=0,i=r.length;i>t;t++)r[t].control&&(o[t]=s);return-1===n.inArray(!0,o)&&(o[0]=!0),o},_classLogic:function(){var e=this,t=this.c.breakpoints,i=this.s.dt.columns().eq(0).map(function(e){return e=this.column(e).header().className,{className:e,includeIn:[],auto:!1,control:!1,never:e.match(/\bnever\b/)?!0:!1}}),s=function(e,t){var s=i[e].includeIn;-1===n.inArray(t,s)&&s.push(t)},r=function(n,r,o,a){if(o){if("max-"===o)for(a=e._find(r).width,r=0,o=t.length;o>r;r++)t[r].width<=a&&s(n,t[r].name);else if("min-"===o)for(a=e._find(r).width,r=0,o=t.length;o>r;r++)t[r].width>=a&&s(n,t[r].name);else if("not-"===o)for(r=0,o=t.length;o>r;r++)-1===t[r].name.indexOf(a)&&s(n,t[r].name)}else i[n].includeIn.push(r)};i.each(function(e,i){for(var s=e.className.split(" "),o=!1,a=0,l=s.length;l>a;a++){var d=n.trim(s[a]);if("all"===d)return o=!0,void(e.includeIn=n.map(t,function(e){return e.name}));if("none"===d||"never"===d)return void(o=!0);if("control"===d)return o=!0,void(e.control=!0);n.each(t,function(e,t){var n=t.name.split("-"),s=d.match(RegExp("(min\\-|max\\-|not\\-)?("+n[0]+")(\\-[_a-zA-Z0-9])?"));s&&(o=!0,s[2]===n[0]&&s[3]==="-"+n[1]?r(i,t.name,s[1],s[2]+s[3]):s[2]===n[0]&&!s[3]&&r(i,t.name,s[1],s[2]))})}o||(e.auto=!0)}),this.s.columns=i},_detailsInit:function(){var e=this,t=this.s.dt,i=this.c.details;"inline"===i.type&&(i.target="td:first-child");var s=i.target;n(t.table().body()).on("click","string"==typeof s?s:"td",function(){if(n(t.table().node()).hasClass("collapsed")&&t.row(n(this).closest("tr")).length){if("number"==typeof s){var i=0>s?t.columns().eq(0).length+s:s;if(t.cell(this).index().column!==i)return}if(i=t.row(n(this).closest("tr")),i.child.isShown())i.child(!1),n(i.node()).removeClass("parent");else{var r=e.c.details.renderer(t,i[0]);i.child(r,"child").show(),n(i.node()).addClass("parent")}}})},_detailsVis:function(){var e=this,t=this.s.dt,i=t.columns().indexes().filter(function(e){var i=t.column(e);return i.visible()?null:n(i.header()).hasClass("never")?null:e}),s=!0;(0===i.length||1===i.length&&this.s.columns[i[0]].control)&&(s=!1),s?t.rows({page:"current"}).eq(0).each(function(n){if(n=t.row(n),n.child()){var i=e.c.details.renderer(t,n[0]);!1===i?n.child.hide():n.child(i,"child").show()}}):t.rows({page:"current"}).eq(0).each(function(e){t.row(e).child.hide()})},_find:function(e){for(var t=this.c.breakpoints,n=0,i=t.length;i>n;n++)if(t[n].name===e)return t[n]},_resize:function(){var t,i=this.s.dt,s=n(e).width(),r=this.c.breakpoints,o=r[0].name,a=this.s.columns;for(t=r.length-1;t>=0;t--)if(s<=r[t].width){o=r[t].name;break}var l=this._columnsVisiblity(o),r=!1;for(t=0,s=a.length;s>t;t++)if(!1===l[t]&&!a[t].never){r=!0;break}n(i.table().node()).toggleClass("collapsed",r),i.columns().eq(0).each(function(e,t){i.column(e).visible(l[t])})},_resizeAuto:function(){var e=this.s.dt,t=this.s.columns;if(this.c.auto&&-1!==n.inArray(!0,n.map(t,function(e){return e.auto}))){e.table().node();var i=e.table().node().cloneNode(!1),s=n(e.table().header().cloneNode(!1)).appendTo(i),r=n(e.table().body().cloneNode(!1)).appendTo(i);n(e.table().footer()).clone(!1).appendTo(i),e.rows({page:"current"}).indexes().flatten().each(function(t){var i=e.row(t).node().cloneNode(!0);e.columns(":hidden").flatten().length&&n(i).append(e.cells(t,":hidden").nodes().to$().clone()),n(i).appendTo(r)});var o=e.columns().header().to$().clone(!1);n("<tr/>").append(o).appendTo(s),"inline"===this.c.details.type&&n(i).addClass("dtr-inline collapsed"),i=n("<div/>").css({width:1,height:1,overflow:"hidden"}).append(i),i.find("th.never, td.never").remove(),i.insertBefore(e.table().node()),e.columns().eq(0).each(function(e){t[e].minWidth=o[e].offsetWidth||0}),i.remove()}}},s.breakpoints=[{name:"desktop",width:1/0},{name:"tablet-l",width:1024},{name:"tablet-p",width:768},{name:"mobile-l",width:480},{name:"mobile-p",width:320}],s.defaults={breakpoints:s.breakpoints,auto:!0,details:{renderer:function(e,t){var i=e.cells(t,":hidden").eq(0).map(function(t){var i=n(e.column(t.column).header()),t=e.cell(t).index();if(i.hasClass("control")||i.hasClass("never"))return"";var s=e.settings()[0],s=s.oApi._fnGetCellData(s,t.row,t.column,"display");return(i=i.text())&&(i+=":"),'<li data-dtr-index="'+t.column+'"><span class="dtr-title">'+i+'</span> <span class="dtr-data">'+s+"</span></li>"}).toArray().join("");return i?n('<ul data-dtr-index="'+t+'"/>').append(i):!1},target:0,type:"inline"}};var r=n.fn.dataTable.Api;return r.register("responsive()",function(){return this}),r.register("responsive.index()",function(e){return e=n(e),{column:e.data("dtr-index"),row:e.parent().data("dtr-index")}}),r.register("responsive.rebuild()",function(){return this.iterator("table",function(e){e._responsive&&e._responsive._classLogic()})}),r.register("responsive.recalc()",function(){return this.iterator("table",function(e){e._responsive&&(e._responsive._resizeAuto(),e._responsive._resize())})}),s.version="1.0.7",n.fn.dataTable.Responsive=s,n.fn.DataTable.Responsive=s,n(t).on("init.dt.dtr",function(e,t){if("dt"===e.namespace&&(n(t.nTable).hasClass("responsive")||n(t.nTable).hasClass("dt-responsive")||t.oInit.responsive||i.defaults.responsive)){var r=t.oInit.responsive;!1!==r&&new s(t,n.isPlainObject(r)?r:{})}}),s};"function"==typeof define&&define.amd?define(["jquery","datatables"],n):"object"==typeof exports?n(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.Responsive&&n(jQuery,jQuery.fn.dataTable)}(window,document);


/*! RowReorder 1.0.0 *//*
!function(t,e){var o=function(o,r){var s=function(t,e){if(!r.versionCheck||!r.versionCheck("1.10.8"))throw"DataTables RowReorder requires DataTables 1.10.8 or newer";this.c=o.extend(!0,{},r.defaults.rowReorder,s.defaults,e),this.s={bodyTop:null,dt:new r.Api(t),getDataFn:r.ext.oApi._fnGetObjectDataFn(this.c.dataSrc),middles:null,setDataFn:r.ext.oApi._fnSetObjectDataFn(this.c.dataSrc),start:{top:0,left:0,offsetTop:0,offsetLeft:0,nodes:[]},windowHeight:0},this.dom={clone:null};var n=this.s.dt.settings()[0],i=n.rowreorder;return i?i:(n.rowreorder=this,void this._constructor())};return s.prototype={_constructor:function(){var t=this,e=this.s.dt,r=o(e.table().node());"static"===r.css("position")&&r.css("position","relative"),o(r).on("mousedown.rowReorder touchstart.rowReorder",this.c.selector,function(r){var s=o(this).closest("tr");return e.row(s).any()?(t._mouseDown(r,s),!1):void 0}),e.on("destroy",function(){r.off("mousedown.rowReorder")})},_cachePositions:function(){var e=this.s.dt,r=o(e.table().node()).find("thead").outerHeight(),s=o.unique(e.rows({page:"current"}).nodes().toArray()),n=o.map(s,function(t){return o(t).position().top-r}),s=o.map(n,function(t,r){return n.length<r-1?(t+n[r+1])/2:(t+t+o(e.row(":last-child").node()).outerHeight())/2});this.s.middles=s,this.s.bodyTop=o(e.table().body()).offset().top,this.s.windowHeight=o(t).height()},_clone:function(t){var e=o(this.s.dt.table().node().cloneNode(!1)).addClass("dt-rowReorder-float").append("<tbody/>").append(t.clone(!1)),r=t.outerWidth(),s=t.outerHeight(),n=t.children().map(function(){return o(this).width()});e.width(r).height(s).find("tr").children().each(function(t){this.style.width=n[t]+"px"}),e.appendTo("body"),this.dom.clone=e},_clonePosition:function(t){var e=this.s.start,o=this._eventToPage(t,"Y")-e.top,t=this._eventToPage(t,"X")-e.left,r=this.c.snapX;this.dom.clone.css({top:o+e.offsetTop,left:!0===r?e.offsetLeft:"number"==typeof r?e.offsetLeft+r:t+e.offsetLeft})},_emitEvent:function(t,e){this.s.dt.iterator("table",function(r){o(r.nTable).triggerHandler(t+".dt",e)})},_eventToPage:function(t,e){return-1!==t.type.indexOf("touch")?t.originalEvent.touches[0]["page"+e]:t["page"+e]},_mouseDown:function(r,s){var n=this,i=this.s.dt,a=this.s.start,d=s.offset();a.top=this._eventToPage(r,"Y"),a.left=this._eventToPage(r,"X"),a.offsetTop=d.top,a.offsetLeft=d.left,a.nodes=o.unique(i.rows({page:"current"}).nodes().toArray()),this._cachePositions(),this._clone(s),this._clonePosition(r),this.dom.target=s,s.addClass("dt-rowReorder-moving"),o(e).on("mouseup.rowReorder touchend.rowReorder",function(t){n._mouseUp(t)}).on("mousemove.rowReorder touchmove.rowReorder",function(t){n._mouseMove(t)}),o(t).width()===o(e).width()&&o(e.body).addClass("dt-rowReorder-noOverflow")},_mouseMove:function(t){this._clonePosition(t);for(var r=this._eventToPage(t,"Y")-this.s.bodyTop,s=this.s.middles,n=null,i=this.s.dt,a=i.table().body(),d=0,l=s.length;l>d;d++)if(r<s[d]){n=d;break}null===n&&(n=s.length),(null===this.s.lastInsert||this.s.lastInsert!==n)&&(0===n?this.dom.target.prependTo(a):(r=o.unique(i.rows({page:"current"}).nodes().toArray()),n>this.s.lastInsert?this.dom.target.before(r[n-1]):this.dom.target.after(r[n])),this._cachePositions(),this.s.lastInsert=n),t=this._eventToPage(t,"Y")-e.body.scrollTop,n=this.s.scrollInterval,65>t?n||(this.s.scrollInterval=setInterval(function(){e.body.scrollTop=e.body.scrollTop-5},15)):65>this.s.windowHeight-t?n||(this.s.scrollInterval=setInterval(function(){e.body.scrollTop=e.body.scrollTop+5},15)):(clearInterval(n),this.s.scrollInterval=null)},_mouseUp:function(){var t,r,s=this.s.dt;this.dom.clone.remove(),this.dom.clone=null,this.dom.target.removeClass("dt-rowReorder-moving"),o(e).off(".rowReorder"),o(e.body).removeClass("dt-rowReorder-noOverflow");var n=this.s.start.nodes,i=o.unique(s.rows({page:"current"}).nodes().toArray()),a={},d=[],l=[],h=this.s.getDataFn,c=this.s.setDataFn;for(t=0,r=n.length;r>t;t++)if(n[t]!==i[t]){var u=s.row(i[t]).id(),f=s.row(i[t]).data(),w=s.row(n[t]).data();u&&(a[u]=h(w)),d.push({node:i[t],oldData:h(f),newData:h(w),newPosition:t,oldPosition:o.inArray(i[t],n)}),l.push(i[t])}if(this._emitEvent("row-reorder",[d,{dataSrc:this.c.dataSrc,nodes:l,values:a}]),this.c.editor&&this.c.editor.edit(l,!1,{submit:"changed"}).multiSet(this.c.dataSrc,a).submit(),this.c.update){for(t=0,r=d.length;r>t;t++)n=s.row(d[t].node),i=n.data(),c(i,d[t].newData),n.invalidate("data");s.draw(!1)}}},s.defaults={dataSrc:0,editor:null,selector:"td:first-child",snapX:!1,update:!0},s.version="1.0.0",o.fn.dataTable.RowReorder=s,o.fn.DataTable.RowReorder=s,o(e).on("init.dt.dtr",function(t,e){if("dt"===t.namespace){var n=e.oInit.rowReorder,i=r.defaults.rowReorder;(n||i)&&(i=o.extend({},n,i),!1!==n&&new s(e,i))}}),s};"function"==typeof define&&define.amd?define(["jquery","datatables"],o):"object"==typeof exports?o(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.RowReorder&&o(jQuery,jQuery.fn.dataTable)}(window,document);
*/

/*! Scroller 1.3.0 *//*
!function(t,s,o){var e=function(e,i){var r=function(t,i){this instanceof r?(i===o&&(i={}),this.s={dt:e.fn.dataTable.Api(t).settings()[0],tableTop:0,tableBottom:0,redrawTop:0,redrawBottom:0,autoHeight:!0,viewportRows:0,stateTO:null,drawTO:null,heights:{jump:null,page:null,virtual:null,scroll:null,row:null,viewport:null},topRowFloat:0,scrollDrawDiff:null,loaderVisible:!1},this.s=e.extend(this.s,r.oDefaults,i),this.s.heights.row=this.s.rowHeight,this.dom={force:s.createElement("div"),scroller:null,table:null,loader:null},this.s.dt.oScroller||(this.s.dt.oScroller=this,this._fnConstruct())):alert("Scroller warning: Scroller must be initialised with the 'new' keyword.")};r.prototype={fnRowToPixels:function(t,s,e){return t=e?this._domain("virtualToPhysical",t*this.s.heights.row):this.s.baseScrollTop+(t-this.s.baseRowTop)*this.s.heights.row,s||s===o?parseInt(t,10):t},fnPixelsToRow:function(t,s,e){var i=t-this.s.baseScrollTop,t=e?this._domain("physicalToVirtual",t)/this.s.heights.row:i/this.s.heights.row+this.s.baseRowTop;return s||s===o?parseInt(t,10):t},fnScrollToRow:function(t,s){var o=this,i=!1,r=this.fnRowToPixels(t),l=t-(this.s.displayBuffer-1)/2*this.s.viewportRows;0>l&&(l=0),(r>this.s.redrawBottom||r<this.s.redrawTop)&&this.s.dt._iDisplayStart!==l&&(i=!0,r=this.fnRowToPixels(t,!1,!0)),"undefined"==typeof s||s?(this.s.ani=i,e(this.dom.scroller).animate({scrollTop:r},function(){setTimeout(function(){o.s.ani=!1},25)})):e(this.dom.scroller).scrollTop(r)},fnMeasure:function(t){this.s.autoHeight&&this._fnCalcRowHeight();var s=this.s.heights;s.viewport=e(this.dom.scroller).height(),this.s.viewportRows=parseInt(s.viewport/s.row,10)+1,this.s.dt._iDisplayLength=this.s.viewportRows*this.s.displayBuffer,(t===o||t)&&this.s.dt.oInstance.fnDraw()},_fnConstruct:function(){var s=this;if(this.s.dt.oFeatures.bPaginate){this.dom.force.style.position="relative",this.dom.force.style.top="0px",this.dom.force.style.left="0px",this.dom.force.style.width="1px",this.dom.scroller=e("div."+this.s.dt.oClasses.sScrollBody,this.s.dt.nTableWrapper)[0],this.dom.scroller.appendChild(this.dom.force),this.dom.scroller.style.position="relative",this.dom.table=e(">table",this.dom.scroller)[0],this.dom.table.style.position="absolute",this.dom.table.style.top="0px",this.dom.table.style.left="0px",e(this.s.dt.nTableWrapper).addClass("DTS"),this.s.loadingIndicator&&(this.dom.loader=e('<div class="dataTables_processing DTS_Loading">'+this.s.dt.oLanguage.sLoadingRecords+"</div>").css("display","none"),e(this.dom.scroller.parentNode).css("position","relative").append(this.dom.loader)),this.s.heights.row&&"auto"!=this.s.heights.row&&(this.s.autoHeight=!1),this.fnMeasure(!1),this.s.ingnoreScroll=!0,this.s.stateSaveThrottle=this.s.dt.oApi._fnThrottle(function(){s.s.dt.oApi._fnSaveState(s.s.dt)},500),e(this.dom.scroller).on("scroll.DTS",function(){s._fnScroll.call(s)}),e(this.dom.scroller).on("touchstart.DTS",function(){s._fnScroll.call(s)}),this.s.dt.aoDrawCallback.push({fn:function(){s.s.dt.bInitialised&&s._fnDrawCallback.call(s)},sName:"Scroller"}),e(t).on("resize.DTS",function(){s.fnMeasure(!1),s._fnInfo()});var o=!0;this.s.dt.oApi._fnCallbackReg(this.s.dt,"aoStateSaveParams",function(t,e){o&&s.s.dt.oLoadedState?(e.iScroller=s.s.dt.oLoadedState.iScroller,e.iScrollerTopRow=s.s.dt.oLoadedState.iScrollerTopRow,o=!1):(e.iScroller=s.dom.scroller.scrollTop,e.iScrollerTopRow=s.s.topRowFloat)},"Scroller_State"),this.s.dt.oLoadedState&&(this.s.topRowFloat=this.s.dt.oLoadedState.iScrollerTopRow||0),e(this.s.dt.nTable).on("init.dt",function(){s.fnMeasure()}),this.s.dt.aoDestroyCallback.push({sName:"Scroller",fn:function(){e(t).off("resize.DTS"),e(s.dom.scroller).off("touchstart.DTS scroll.DTS"),e(s.s.dt.nTableWrapper).removeClass("DTS"),e("div.DTS_Loading",s.dom.scroller.parentNode).remove(),e(s.s.dt.nTable).off("init.dt"),s.dom.table.style.position="",s.dom.table.style.top="",s.dom.table.style.left=""}})}else this.s.dt.oApi._fnLog(this.s.dt,0,"Pagination must be enabled for Scroller")},_fnScroll:function(){var t,s=this,o=this.s.heights,i=this.dom.scroller.scrollTop;if(!this.s.skip&&!this.s.ingnoreScroll)if(this.s.dt.bFiltered||this.s.dt.bSorted)this.s.lastScrollTop=0;else{if(this._fnInfo(),clearTimeout(this.s.stateTO),this.s.stateTO=setTimeout(function(){s.s.dt.oApi._fnSaveState(s.s.dt)},250),i<this.s.redrawTop||i>this.s.redrawBottom){var r=Math.ceil((this.s.displayBuffer-1)/2*this.s.viewportRows);Math.abs(i-this.s.lastScrollTop)>o.viewport||this.s.ani?(t=parseInt(this._domain("physicalToVirtual",i)/o.row,10)-r,this.s.topRowFloat=this._domain("physicalToVirtual",i)/o.row):(t=this.fnPixelsToRow(i)-r,this.s.topRowFloat=this.fnPixelsToRow(i,!1)),0>=t?t=0:t+this.s.dt._iDisplayLength>this.s.dt.fnRecordsDisplay()?(t=this.s.dt.fnRecordsDisplay()-this.s.dt._iDisplayLength,0>t&&(t=0)):0!==t%2&&t++,t!=this.s.dt._iDisplayStart&&(this.s.tableTop=e(this.s.dt.nTable).offset().top,this.s.tableBottom=e(this.s.dt.nTable).height()+this.s.tableTop,o=function(){null===s.s.scrollDrawReq&&(s.s.scrollDrawReq=i),s.s.dt._iDisplayStart=t,s.s.dt.oApi._fnDraw(s.s.dt)},this.s.dt.oFeatures.bServerSide?(clearTimeout(this.s.drawTO),this.s.drawTO=setTimeout(o,this.s.serverWait)):o(),this.dom.loader&&!this.s.loaderVisible)&&(this.dom.loader.css("display","block"),this.s.loaderVisible=!0)}this.s.lastScrollTop=i,this.s.stateSaveThrottle()}},_domain:function(t,s){var o,e=this.s.heights;if(e.virtual===e.scroll){if(o=(e.virtual-e.viewport)/(e.scroll-e.viewport),"virtualToPhysical"===t)return s/o;if("physicalToVirtual"===t)return s*o}var i=(e.scroll-e.viewport)/2,r=(e.virtual-e.viewport)/2;return o=r/(i*i),"virtualToPhysical"===t?r>s?Math.pow(s/o,.5):(s=2*r-s,0>s?e.scroll:2*i-Math.pow(s/o,.5)):"physicalToVirtual"===t?i>s?s*s*o:(s=2*i-s,0>s?e.virtual:2*r-s*s*o):void 0},_fnDrawCallback:function(){var t=this,s=this.s.heights,o=this.dom.scroller.scrollTop,i=e(this.s.dt.nTable).height(),r=this.s.dt._iDisplayStart,l=this.s.dt._iDisplayLength,a=this.s.dt.fnRecordsDisplay();this.s.skip=!0,this._fnScrollForce(),o=0===r?this.s.topRowFloat*s.row:r+l>=a?s.scroll-(a-this.s.topRowFloat)*s.row:this._domain("virtualToPhysical",this.s.topRowFloat*s.row),this.dom.scroller.scrollTop=o,this.s.baseScrollTop=o,this.s.baseRowTop=this.s.topRowFloat;var n=o-(this.s.topRowFloat-r)*s.row;0===r?n=0:r+l>=a&&(n=s.scroll-i),this.dom.table.style.top=n+"px",this.s.tableTop=n,this.s.tableBottom=i+this.s.tableTop,i=(o-this.s.tableTop)*this.s.boundaryScale,this.s.redrawTop=o-i,this.s.redrawBottom=o+i,this.s.skip=!1,this.s.dt.oFeatures.bStateSave&&null!==this.s.dt.oLoadedState&&"undefined"!=typeof this.s.dt.oLoadedState.iScroller?((o=!this.s.dt.sAjaxSource&&!t.s.dt.ajax||this.s.dt.oFeatures.bServerSide?!1:!0)&&2==this.s.dt.iDraw||!o&&1==this.s.dt.iDraw)&&setTimeout(function(){e(t.dom.scroller).scrollTop(t.s.dt.oLoadedState.iScroller),t.s.redrawTop=t.s.dt.oLoadedState.iScroller-s.viewport/2,setTimeout(function(){t.s.ingnoreScroll=!1},0)},0):t.s.ingnoreScroll=!1,setTimeout(function(){t._fnInfo.call(t)},0),this.dom.loader&&this.s.loaderVisible&&(this.dom.loader.css("display","none"),this.s.loaderVisible=!1)},_fnScrollForce:function(){var t=this.s.heights;t.virtual=t.row*this.s.dt.fnRecordsDisplay(),t.scroll=t.virtual,1e6<t.scroll&&(t.scroll=1e6),this.dom.force.style.height=t.scroll>this.s.heights.row?t.scroll+"px":this.s.heights.row+"px"},_fnCalcRowHeight:function(){var t=this.s.dt,s=t.nTable,o=s.cloneNode(!1),i=e("<tbody/>").appendTo(o),r=e('<div class="'+t.oClasses.sWrapper+' DTS"><div class="'+t.oClasses.sScrollWrapper+'"><div class="'+t.oClasses.sScrollBody+'"></div></div></div>');for(e("tbody tr:lt(4)",s).clone().appendTo(i);3>e("tr",i).length;)i.append("<tr><td>&nbsp;</td></tr>");e("div."+t.oClasses.sScrollBody,r).append(o),r.appendTo(this.s.dt.nHolding||s.parentNode),this.s.heights.row=e("tr",i).eq(1).outerHeight(),r.remove()},_fnInfo:function(){if(this.s.dt.oFeatures.bInfo){var t=this.s.dt,s=t.oLanguage,o=this.dom.scroller.scrollTop,i=Math.floor(this.fnPixelsToRow(o,!1,this.s.ani)+1),r=t.fnRecordsTotal(),l=t.fnRecordsDisplay(),o=Math.ceil(this.fnPixelsToRow(o+this.s.heights.viewport,!1,this.s.ani)),o=o>l?l:o,a=t.fnFormatNumber(i),n=t.fnFormatNumber(o),h=t.fnFormatNumber(r),d=t.fnFormatNumber(l),a=0===t.fnRecordsDisplay()&&t.fnRecordsDisplay()==t.fnRecordsTotal()?s.sInfoEmpty+s.sInfoPostFix:0===t.fnRecordsDisplay()?s.sInfoEmpty+" "+s.sInfoFiltered.replace("_MAX_",h)+s.sInfoPostFix:t.fnRecordsDisplay()==t.fnRecordsTotal()?s.sInfo.replace("_START_",a).replace("_END_",n).replace("_MAX_",h).replace("_TOTAL_",d)+s.sInfoPostFix:s.sInfo.replace("_START_",a).replace("_END_",n).replace("_MAX_",h).replace("_TOTAL_",d)+" "+s.sInfoFiltered.replace("_MAX_",t.fnFormatNumber(t.fnRecordsTotal()))+s.sInfoPostFix;if((s=s.fnInfoCallback)&&(a=s.call(t.oInstance,t,i,o,r,l,a)),t=t.aanFeatures.i,"undefined"!=typeof t)for(i=0,r=t.length;r>i;i++)e(t[i]).html(a)}}},r.defaults={trace:!1,rowHeight:"auto",serverWait:200,displayBuffer:9,boundaryScale:.5,loadingIndicator:!1},r.oDefaults=r.defaults,r.version="1.3.0","function"==typeof e.fn.dataTable&&"function"==typeof e.fn.dataTableExt.fnVersionCheck&&e.fn.dataTableExt.fnVersionCheck("1.10.0")?e.fn.dataTableExt.aoFeatures.push({fnInit:function(t){var s=t.oInit;new r(t,s.scroller||s.oScroller||{})},cFeature:"S",sFeature:"Scroller"}):alert("Warning: Scroller requires DataTables 1.10.0 or greater - www.datatables.net/download"),e(s).on("preInit.dt.dtscroller",function(t,s){if("dt"===t.namespace){var o=s.oInit.scroller,l=i.defaults.scroller;(o||l)&&(l=e.extend({},o,l),!1!==o&&new r(s,l))}}),e.fn.dataTable.Scroller=r,e.fn.DataTable.Scroller=r;var l=e.fn.dataTable.Api;return l.register("scroller()",function(){return this}),l.register("scroller().rowToPixels()",function(t,s,o){var e=this.context;return e.length&&e[0].oScroller?e[0].oScroller.fnRowToPixels(t,s,o):void 0}),l.register("scroller().pixelsToRow()",function(t,s,o){var e=this.context;return e.length&&e[0].oScroller?e[0].oScroller.fnPixelsToRow(t,s,o):void 0}),l.register("scroller().scrollToRow()",function(t,s){return this.iterator("table",function(o){o.oScroller&&o.oScroller.fnScrollToRow(t,s)}),this}),l.register("row().scrollTo()",function(t){var s=this;return this.iterator("row",function(o,e){if(o.oScroller){var i=s.rows({order:"applied",search:"applied"}).indexes().indexOf(e);o.oScroller.fnScrollToRow(i,t)}}),this}),l.register("scroller.measure()",function(t){return this.iterator("table",function(s){s.oScroller&&s.oScroller.fnMeasure(t)}),this}),r};"function"==typeof define&&define.amd?define(["jquery","datatables"],e):"object"==typeof exports?e(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.Scroller&&e(jQuery,jQuery.fn.dataTable)}(window,document);
*/

/*! Select for DataTables 1.0.1 */
!function(e,t,l){e=function(e,s){function c(t){var l=t.settings()[0]._select.selector;e(t.table().body()).off("mousedown.dtSelect",l).off("mouseup.dtSelect",l).off("click.dtSelect",l),e("body").off("click.dtSelect")}function n(t){var l=e(t.table().body()),s=t.settings()[0],c=s._select.selector;l.on("mousedown.dtSelect",c,function(e){e.shiftKey&&l.css("-moz-user-select","none").one("selectstart.dtSelect",c,function(){return!1})}).on("mouseup.dtSelect",c,function(){l.css("-moz-user-select","")}).on("click.dtSelect",c,function(s){var c=t.select.items(),n=t.cell(this).index(),o=t.settings()[0];e(s.target).closest("tbody")[0]==l[0]&&t.cell(s.target).any()&&("row"===c?(c=n.row,r(s,t,o,"row",c)):"column"===c?(c=t.cell(s.target).index().column,r(s,t,o,"column",c)):"cell"===c&&(c=t.cell(s.target).index(),r(s,t,o,"cell",c)),o._select_lastCell=n)}),e("body").on("click.dtSelect",function(l){s._select.blurable&&!e(l.target).parents().filter(t.table().container()).length&&(e(l.target).parents("div.DTE").length||a(s,!0))})}function o(t,l,s,c){(!c||t.flatten().length)&&(s.unshift(t),e(t.table().node()).triggerHandler(l+".dt",s))}function i(t){var l=t.settings()[0];if(l._select.info&&l.aanFeatures.i){var s=e('<span class="select-info"/>'),c=function(l,c){s.append(e('<span class="select-item"/>').append(t.i18n("select."+l+"s",{_:"%d "+l+"s selected",0:"",1:"1 "+l+" selected"},c)))};c("row",t.rows({selected:!0}).flatten().length),c("column",t.columns({selected:!0}).flatten().length),c("cell",t.cells({selected:!0}).flatten().length),e.each(l.aanFeatures.i,function(t,l){var l=e(l),c=l.children("span.select-info");c.length&&c.remove(),""!==s.text()&&l.append(s)})}}function a(e,t){if(t||"single"===e._select.style){var l=new s.Api(e);l.rows({selected:!0}).deselect(),l.columns({selected:!0}).deselect(),l.cells({selected:!0}).deselect()}}function r(t,l,s,c,n){var o=l.select.style(),i=l[c](n,{selected:!0}).any();"os"===o?t.ctrlKey||t.metaKey?l[c](n).select(!i):t.shiftKey?"cell"===c?(c=s._select_lastCell||null,i=function(e,t){if(e>t)var s=t,t=e,e=s;var c=!1;return l.columns(":visible").indexes().filter(function(l){return l===e&&(c=!0),l===t?(c=!1,!0):c})},t=function(e,t){var s=l.rows({search:"applied"}).indexes();if(s.indexOf(e)>s.indexOf(t))var c=t,t=e,e=c;var n=!1;return s.filter(function(l){return l===e&&(n=!0),l===t?(n=!1,!0):n})},l.cells({selected:!0}).any()||c?(i=i(c.column,n.column),c=t(c.row,n.row)):(i=i(0,n.column),c=t(0,n.row)),c=l.cells(c,i).flatten(),l.cells(n,{selected:!0}).any()?l.cells(c).deselect():l.cells(c).select()):(t=s._select_lastCell?s._select_lastCell[c]:null,i=l[c+"s"]({search:"applied"}).indexes(),t=e.inArray(t,i),s=e.inArray(n,i),l[c+"s"]({selected:!0}).any()||-1!==t?(t>s&&(o=s,s=t,t=o),i.splice(s+1,i.length),i.splice(0,t)):i.splice(e.inArray(n,i)+1,i.length),l[c](n,{selected:!0}).any()?(i.splice(e.inArray(n,i),1),l[c+"s"](i).deselect()):l[c+"s"](i).select()):(t=l[c+"s"]({selected:!0}),i&&1===t.flatten().length?l[c](n).deselect():(t.deselect(),l[c](n).select())):l[c](n).select(!i)}function d(e,t){return function(l){return l.i18n("buttons."+e,t)}}s.select={},s.select.version="1.0.1",e.each([{type:"row",prop:"aoData"},{type:"column",prop:"aoColumns"}],function(e,t){s.ext.selector[t.type].push(function(e,s,c){var n,s=s.selected,o=[];if(s===l)return c;for(var i=0,a=c.length;a>i;i++)n=e[t.prop][c[i]],(!0===s&&!0===n._select_selected||!1===s&&!n._select_selected)&&o.push(c[i]);return o})}),s.ext.selector.cell.push(function(e,t,s){var c,t=t.selected,n=[];if(t===l)return s;for(var o=0,i=s.length;i>o;o++)c=e.aoData[s[o].row],(!0===t&&c._selected_cells&&!0===c._selected_cells[s[o].column]||!1===t&&(!c._selected_cells||!c._selected_cells[s[o].column]))&&n.push(s[o]);return n});var u=s.Api.register,f=s.Api.registerPlural;u("select()",function(){}),u("select.blurable()",function(e){return e===l?this.context[0]._select.blurable:this.iterator("table",function(t){t._select.blurable=e})}),u("select.info()",function(e){return i===l?this.context[0]._select.info:this.iterator("table",function(t){t._select.info=e})}),u("select.items()",function(e){return e===l?this.context[0]._select.items:this.iterator("table",function(t){t._select.items=e,o(new s.Api(t),"selectItems",[e])})}),u("select.style()",function(t){return t===l?this.context[0]._select.style:this.iterator("table",function(a){if(a._select.style=t,!a._select_init){var r=new s.Api(a);a.aoRowCreatedCallback.push({fn:function(t,l,s){for(l=a.aoData[s],l._select_selected&&e(t).addClass("selected"),t=0,s=a.aoColumns.length;s>t;t++)(a.aoColumns[t]._select_selected||l._selected_cells&&l._selected_cells[t])&&e(l.anCells[t]).addClass("selected")},sName:"select-deferRender"}),r.on("preXhr.dt.dtSelect",function(){var e=r.rows({selected:!0}).ids(!0).filter(function(e){return e!==l}),t=r.cells({selected:!0}).eq(0).map(function(e){var t=r.row(e.row).id(!0);return t?{row:t,column:e.column}:l}).filter(function(e){return e!==l});r.one("draw.dt.dtSelect",function(){r.rows(e).select(),t.any()&&t.each(function(e){r.cells(e.row,e.column).select()})})}),r.on("draw.dtSelect.dt select.dtSelect.dt deselect.dtSelect.dt",function(){i(r)}),r.on("destroy.dtSelect",function(){c(r),r.off(".dtSelect")})}var d=new s.Api(a);c(d),"api"!==t&&n(d),o(new s.Api(a),"selectStyle",[t])})}),u("select.selector()",function(e){return e===l?this.context[0]._select.selector:this.iterator("table",function(t){c(new s.Api(t)),t._select.selector=e,"api"!==t._select.style&&n(new s.Api(t))})}),f("rows().select()","row().select()",function(t){var l=this;return!1===t?this.deselect():(this.iterator("row",function(t,l){a(t),t.aoData[l]._select_selected=!0,e(t.aoData[l].nTr).addClass("selected")}),this.iterator("table",function(e,t){o(l,"select",["row",l[t]],!0)}),this)}),f("columns().select()","column().select()",function(t){var l=this;return!1===t?this.deselect():(this.iterator("column",function(t,l){a(t),t.aoColumns[l]._select_selected=!0;var c=new s.Api(t).column(l);e(c.header()).addClass("selected"),e(c.footer()).addClass("selected"),c.nodes().to$().addClass("selected")}),this.iterator("table",function(e,t){o(l,"select",["column",l[t]],!0)}),this)}),f("cells().select()","cell().select()",function(t){var s=this;return!1===t?this.deselect():(this.iterator("cell",function(t,s,c){a(t),t=t.aoData[s],t._selected_cells===l&&(t._selected_cells=[]),t._selected_cells[c]=!0,t.anCells&&e(t.anCells[c]).addClass("selected")}),this.iterator("table",function(e,t){o(s,"select",["cell",s[t]],!0)}),this)}),f("rows().deselect()","row().deselect()",function(){var t=this;return this.iterator("row",function(t,l){t.aoData[l]._select_selected=!1,e(t.aoData[l].nTr).removeClass("selected")}),this.iterator("table",function(e,l){o(t,"deselect",["row",t[l]],!0)}),this}),f("columns().deselect()","column().deselect()",function(){var t=this;return this.iterator("column",function(t,l){t.aoColumns[l]._select_selected=!1;var c=new s.Api(t),n=c.column(l);e(n.header()).removeClass("selected"),e(n.footer()).removeClass("selected"),c.cells(null,l).indexes().each(function(l){var s=t.aoData[l.row],c=s._selected_cells;s.anCells&&(!c||!c[l.column])&&e(s.anCells[l.column]).removeClass("selected")})}),this.iterator("table",function(e,l){o(t,"deselect",["column",t[l]],!0)}),this}),f("cells().deselect()","cell().deselect()",function(){var t=this;return this.iterator("cell",function(t,l,s){l=t.aoData[l],l._selected_cells[s]=!1,l.anCells&&!t.aoColumns[s]._select_selected&&e(l.anCells[s]).removeClass("selected")}),this.iterator("table",function(e,l){o(t,"deselect",["cell",t[l]],!0)}),this}),e.extend(s.ext.buttons,{selected:{text:d("selected","Selected"),className:"buttons-selected",init:function(e){var t=this;e.on("draw.dt.DT select.dt.DT deselect.dt.DT",function(){var e=t.rows({selected:!0}).any()||t.columns({selected:!0}).any()||t.cells({selected:!0}).any();t.enable(e)}),this.disable()}},selectedSingle:{text:d("selectedSingle","Selected single"),className:"buttons-selected-single",init:function(e){var t=this;e.on("draw.dt.DT select.dt.DT deselect.dt.DT",function(){var l=e.rows({selected:!0}).flatten().length+e.columns({selected:!0}).flatten().length+e.cells({selected:!0}).flatten().length;t.enable(1===l)}),this.disable()}},selectAll:{text:d("selectAll","Select all"),className:"buttons-select-all",action:function(){this[this.select.items()+"s"]().select()}},selectNone:{text:d("selectNone","Deselect all"),className:"buttons-select-none",action:function(){a(this.settings()[0],!0)}}}),e.each(["Row","Column","Cell"],function(e,t){var l=t.toLowerCase();s.ext.buttons["select"+t+"s"]={text:d("select"+t+"s","Select "+l+"s"),className:"buttons-select-"+l+"s",action:function(){this.select.items(l)},init:function(e){var t=this;e.on("selectItems.dt.DT",function(e,s,c){t.active(c===l)})}}}),e(t).on("init.dt.dtSelect",function(t,c){if("dt"===t.namespace){var n=c.oInit.select||s.defaults.select,o=new s.Api(c),i="row",a="api",r=!1,d=!0,u="td, th";c._select={},!0===n?a="os":"string"==typeof n?a=n:e.isPlainObject(n)&&(n.blurable!==l&&(r=n.blurable),n.info!==l&&(d=n.info),n.items!==l&&(i=n.items),n.style!==l&&(a=n.style),n.selector!==l)&&(u=n.selector),o.select.selector(u),o.select.items(i),o.select.style(a),o.select.blurable(r),o.select.info(d),e(o.table().node()).hasClass("selectable")&&o.select.style("os")}})},"function"==typeof define&&define.amd?define(["jquery","datatables"],e):"object"==typeof exports?e(require("jquery"),require("datatables")):jQuery&&!jQuery.fn.dataTable.select&&e(jQuery,jQuery.fn.dataTable)}(window,document);




/* UPLOADIFY //////////////////////////////////////////////////////////////////////////////////////////////////// */
(function(b){var a={init:function(c){return this.each(function(){var g=b(this);g.data("uploadifive",{inputs:{},inputCount:0,fileID:0,queue:{count:0,selected:0,replaced:0,errors:0,queued:0,cancelled:0},uploads:{current:0,attempts:0,successful:0,errors:0,count:0}});var d=g.data("uploadifive");var f=d.settings=b.extend({auto:true,buttonClass:false,buttonText:"Select Files",checkScript:false,dnd:true,dropTarget:false,fileObjName:"Filedata",fileSizeLimit:0,fileType:false,formData:{},height:30,itemTemplate:false,method:"post",multi:true,overrideEvents:[],queueID:false,queueSizeLimit:0,removeCompleted:false,simUploadLimit:0,truncateLength:0,uploadLimit:0,uploadScript:"uploadifive.php",width:100},c);if(isNaN(f.fileSizeLimit)){var e=parseInt(f.fileSizeLimit)*1.024;if(f.fileSizeLimit.indexOf("KB")>-1){f.fileSizeLimit=e*1000;}else{if(f.fileSizeLimit.indexOf("MB")>-1){f.fileSizeLimit=e*1000000;}else{if(f.fileSizeLimit.indexOf("GB")>-1){f.fileSizeLimit=e*1000000000;}}}}else{f.fileSizeLimit=f.fileSizeLimit*1024;}d.inputTemplate=b('<input type="file">').css({"font-size":f.height+"px",opacity:0,position:"absolute",right:"-3px",top:"-3px","z-index":999});d.createInput=function(){var j=d.inputTemplate.clone();var k=j.name="input"+d.inputCount++;if(f.multi){j.attr("multiple",true);}j.bind("change",function(){d.queue.selected=0;d.queue.replaced=0;d.queue.errors=0;d.queue.queued=0;var l=this.files.length;d.queue.selected=l;if((d.queue.count+l)>f.queueSizeLimit&&f.queueSizeLimit!==0){if(b.inArray("onError",f.overrideEvents)<0){alert("The maximum number of queue items has been reached ("+f.queueSizeLimit+").  Please select fewer files.");}if(typeof f.onError==="function"){f.onError.call(g,"QUEUE_LIMIT_EXCEEDED");}}else{for(var m=0;m<l;m++){file=this.files[m];d.addQueueItem(file);}d.inputs[k]=this;d.createInput();}if(f.auto){a.upload.call(g);}if(typeof f.onSelect==="function"){f.onSelect.call(g,d.queue);}});if(d.currentInput){d.currentInput.hide();}d.button.append(j);d.currentInput=j;};d.destroyInput=function(j){b(d.inputs[j]).remove();delete d.inputs[j];d.inputCount--;};d.drop=function(m){d.queue.selected=0;d.queue.replaced=0;d.queue.errors=0;d.queue.queued=0;var l=m.dataTransfer;var k=l.name="input"+d.inputCount++;var j=l.files.length;d.queue.selected=j;if((d.queue.count+j)>f.queueSizeLimit&&f.queueSizeLimit!==0){if(b.inArray("onError",f.overrideEvents)<0){alert("The maximum number of queue items has been reached ("+f.queueSizeLimit+").  Please select fewer files.");}if(typeof f.onError==="function"){f.onError.call(g,"QUEUE_LIMIT_EXCEEDED");}}else{for(var o=0;o<j;o++){file=l.files[o];d.addQueueItem(file);}d.inputs[k]=l;}if(f.auto){a.upload.call(g);}if(typeof f.onDrop==="function"){f.onDrop.call(g,l.files,l.files.length);}m.preventDefault();m.stopPropagation();};d.fileExistsInQueue=function(k){for(var j in d.inputs){input=d.inputs[j];limit=input.files.length;for(var l=0;l<limit;l++){existingFile=input.files[l];if(existingFile.name==k.name&&!existingFile.complete){return true;}}}return false;};d.removeExistingFile=function(k){for(var j in d.inputs){input=d.inputs[j];limit=input.files.length;for(var l=0;l<limit;l++){existingFile=input.files[l];if(existingFile.name==k.name&&!existingFile.complete){d.queue.replaced++;a.cancel.call(g,existingFile,true);}}}};if(f.itemTemplate==false){d.queueItem=b('<div class="uploadifive-queue-item">                        <a class="close" href="#">X</a>                        <div><span class="filename"></span><span class="fileinfo"></span></div>                        <div class="progress">                            <div class="progress-bar"></div>                        </div>                    </div>');}else{d.queueItem=b(f.itemTemplate);}d.addQueueItem=function(k){if(b.inArray("onAddQueueItem",f.overrideEvents)<0){d.removeExistingFile(k);k.queueItem=d.queueItem.clone();k.queueItem.attr("id",f.id+"-file-"+d.fileID++);k.queueItem.find(".close").bind("click",function(){a.cancel.call(g,k);return false;});var m=k.name;if(m.length>f.truncateLength&&f.truncateLength!=0){m=m.substring(0,f.truncateLength)+"...";}k.queueItem.find(".filename").html(m);k.queueItem.data("file",k);d.queueEl.append(k.queueItem);}if(typeof f.onAddQueueItem==="function"){f.onAddQueueItem.call(g,k);}if(f.fileType){if(b.isArray(f.fileType)){var j=false;for(var l=0;l<f.fileType.length;l++){if(k.type.indexOf(f.fileType[l])>-1){j=true;}}if(!j){d.error("FORBIDDEN_FILE_TYPE",k);}}else{if(k.type.indexOf(f.fileType)<0){d.error("FORBIDDEN_FILE_TYPE",k);}}}if(k.size>f.fileSizeLimit&&f.fileSizeLimit!=0){d.error("FILE_SIZE_LIMIT_EXCEEDED",k);}else{d.queue.queued++;d.queue.count++;}};d.removeQueueItem=function(m,l,k){if(!k){k=0;}var j=l?0:500;if(m.queueItem){if(m.queueItem.find(".fileinfo").html()!=" - Completed"){m.queueItem.find(".fileinfo").html(" - Cancelled");}m.queueItem.find(".progress-bar").width(0);m.queueItem.delay(k).fadeOut(j,function(){b(this).remove();});delete m.queueItem;d.queue.count--;}};d.filesToUpload=function(){var k=0;for(var j in d.inputs){input=d.inputs[j];limit=input.files.length;for(var l=0;l<limit;l++){file=input.files[l];if(!file.skip&&!file.complete){k++;}}}return k;};d.checkExists=function(k){if(b.inArray("onCheck",f.overrideEvents)<0){b.ajaxSetup({async:false});var j=b.extend(f.formData,{filename:k.name});b.post(f.checkScript,j,function(l){k.exists=parseInt(l);});if(k.exists){if(!confirm("A file named "+k.name+" already exists in the upload folder.\nWould you like to replace it?")){a.cancel.call(g,k);return true;}}}if(typeof f.onCheck==="function"){f.onCheck.call(g,k,k.exists);}return false;};d.uploadFile=function(k,l){if(!k.skip&&!k.complete&&!k.uploading){k.uploading=true;d.uploads.current++;d.uploads.attempted++;xhr=k.xhr=new XMLHttpRequest();if(typeof FormData==="function"||typeof FormData==="object"){var m=new FormData();m.append(f.fileObjName,k);for(i in f.formData){m.append(i,f.formData[i]);}xhr.open(f.method,f.uploadScript,true);xhr.upload.addEventListener("progress",function(n){if(n.lengthComputable){d.progress(n,k);}},false);xhr.addEventListener("load",function(n){if(this.readyState==4){k.uploading=false;if(this.status==200){if(k.xhr.responseText!=="Invalid file type."){d.uploadComplete(n,k,l);}else{d.error(k.xhr.responseText,k,l);}}else{if(this.status==404){d.error("404_FILE_NOT_FOUND",k,l);}else{if(this.status==403){d.error("403_FORBIDDEN",k,l);}else{d.error("Unknown Error",k,l);}}}}});xhr.send(m);}else{var j=new FileReader();j.onload=function(q){var t="-------------------------"+(new Date).getTime(),p="--",o="\r\n",s="";s+=p+t+o;s+='Content-Disposition: form-data; name="'+f.fileObjName+'"';if(k.name){s+='; filename="'+k.name+'"';}s+=o;s+="Content-Type: application/octet-stream"+o+o;s+=q.target.result+o;for(key in f.formData){s+=p+t+o;s+='Content-Disposition: form-data; name="'+key+'"'+o+o;s+=f.formData[key]+o;}s+=p+t+p+o;xhr.upload.addEventListener("progress",function(u){d.progress(u,k);},false);xhr.addEventListener("load",function(v){k.uploading=false;var u=this.status;if(u==404){d.error("404_FILE_NOT_FOUND",k,l);}else{if(k.xhr.responseText!="Invalid file type."){d.uploadComplete(v,k,l);}else{d.error(k.xhr.responseText,k,l);}}},false);var n=f.uploadScript;if(f.method=="get"){var r=b(f.formData).param();n+=r;}xhr.open(f.method,f.uploadScript,true);xhr.setRequestHeader("Content-Type","multipart/form-data; boundary="+t);if(typeof f.onUploadFile==="function"){f.onUploadFile.call(g,k);}xhr.sendAsBinary(s);};j.readAsBinaryString(k);}}};d.progress=function(l,j){if(b.inArray("onProgress",f.overrideEvents)<0){if(l.lengthComputable){var k=Math.round((l.loaded/l.total)*100);}j.queueItem.find(".fileinfo").html(" - "+k+"%");j.queueItem.find(".progress-bar").css("width",k+"%");}if(typeof f.onProgress==="function"){f.onProgress.call(g,j,l);}};d.error=function(l,j,k){if(b.inArray("onError",f.overrideEvents)<0){switch(l){case"404_FILE_NOT_FOUND":errorMsg="404 Error";break;case"403_FORBIDDEN":errorMsg="403 Forbidden";break;case"FORBIDDEN_FILE_TYPE":errorMsg="Forbidden File Type";break;case"FILE_SIZE_LIMIT_EXCEEDED":errorMsg="File Too Large";break;default:errorMsg="Unknown Error";break;}j.queueItem.addClass("error").find(".fileinfo").html(" - "+errorMsg);j.queueItem.find(".progress").remove();}if(typeof f.onError==="function"){f.onError.call(g,l,j);}j.skip=true;if(l=="404_FILE_NOT_FOUND"){d.uploads.errors++;}else{d.queue.errors++;}if(k){a.upload.call(g,null,true);}};d.uploadComplete=function(l,j,k){if(b.inArray("onUploadComplete",f.overrideEvents)<0){j.queueItem.find(".progress-bar").css("width","100%");j.queueItem.find(".fileinfo").html(" - Completed");j.queueItem.find(".progress").slideUp(250);j.queueItem.addClass("complete");}if(typeof f.onUploadComplete==="function"){f.onUploadComplete.call(g,j,j.xhr.responseText);}if(f.removeCompleted){setTimeout(function(){a.cancel.call(g,j);},3000);}j.complete=true;d.uploads.successful++;d.uploads.count++;d.uploads.current--;delete j.xhr;if(k){a.upload.call(g,null,true);}};d.queueComplete=function(){if(typeof f.onQueueComplete==="function"){f.onQueueComplete.call(g,d.uploads);}};if(window.File&&window.FileList&&window.Blob&&(window.FileReader||window.FormData)){f.id="uploadifive-"+g.attr("id");d.button=b('<div id="'+f.id+'" class="uploadifive-button">'+f.buttonText+"</div>");if(f.buttonClass){d.button.addClass(f.buttonClass);}d.button.css({height:f.height,"line-height":f.height+"px",overflow:"hidden",position:"relative","text-align":"center",width:f.width});g.before(d.button).appendTo(d.button).hide();d.createInput.call(g);if(!f.queueID){f.queueID=f.id+"-queue";d.queueEl=b('<div id="'+f.queueID+'" class="uploadifive-queue" />');d.button.after(d.queueEl);}else{d.queueEl=b("#"+f.queueID);}if(f.dnd){var h=f.dropTarget?b(f.dropTarget):d.queueEl.get(0);h.addEventListener("dragleave",function(j){j.preventDefault();j.stopPropagation();},false);h.addEventListener("dragenter",function(j){j.preventDefault();j.stopPropagation();},false);h.addEventListener("dragover",function(j){j.preventDefault();j.stopPropagation();},false);h.addEventListener("drop",d.drop,false);}if(!XMLHttpRequest.prototype.sendAsBinary){XMLHttpRequest.prototype.sendAsBinary=function(k){function l(n){return n.charCodeAt(0)&255;}var m=Array.prototype.map.call(k,l);var j=new Uint8Array(m);this.send(j.buffer);};}if(typeof f.onInit==="function"){f.onInit.call(g);}}else{if(typeof f.onFallback==="function"){f.onFallback.call(g);}return false;}});},debug:function(){return this.each(function(){console.log(b(this).data("uploadifive"));});},clearQueue:function(){this.each(function(){var f=b(this),c=f.data("uploadifive"),e=c.settings;for(var d in c.inputs){input=c.inputs[d];limit=input.files.length;for(i=0;i<limit;i++){file=input.files[i];a.cancel.call(f,file);}}if(typeof e.onClearQueue==="function"){e.onClearQueue.call(f,b("#"+c.settings.queueID));}});},cancel:function(d,c){this.each(function(){var g=b(this),e=g.data("uploadifive"),f=e.settings;if(typeof d==="string"){if(!isNaN(d)){fileID="uploadifive-"+b(this).attr("id")+"-file-"+d;}d=b("#"+fileID).data("file");}d.skip=true;e.filesCancelled++;if(d.uploading){e.uploads.current--;d.uploading=false;d.xhr.abort();delete d.xhr;a.upload.call(g);}if(b.inArray("onCancel",f.overrideEvents)<0){e.removeQueueItem(d,c);}if(typeof f.onCancel==="function"){f.onCancel.call(g,d);}});},upload:function(c,d){this.each(function(){var h=b(this),e=h.data("uploadifive"),f=e.settings;if(c){e.uploadFile.call(h,c);}else{if((e.uploads.count+e.uploads.current)<f.uploadLimit||f.uploadLimit==0){if(!d){e.uploads.attempted=0;e.uploads.successsful=0;e.uploads.errors=0;var g=e.filesToUpload();if(typeof f.onUpload==="function"){f.onUpload.call(h,g);}}b("#"+f.queueID).find(".uploadifive-queue-item").not(".error, .complete").each(function(){_file=b(this).data("file");if((e.uploads.current>=f.simUploadLimit&&f.simUploadLimit!==0)||(e.uploads.current>=f.uploadLimit&&f.uploadLimit!==0)||(e.uploads.count>=f.uploadLimit&&f.uploadLimit!==0)){return false;}if(f.checkScript){_file.checking=true;skipFile=e.checkExists(_file);_file.checking=false;if(!skipFile){e.uploadFile(_file,true);}}else{e.uploadFile(_file,true);}});if(b("#"+f.queueID).find(".uploadifive-queue-item").not(".error, .complete").size()==0){e.queueComplete();}}else{if(e.uploads.current==0){if(b.inArray("onError",f.overrideEvents)<0){if(e.filesToUpload()>0&&f.uploadLimit!=0){alert("The maximum upload limit has been reached.");}}if(typeof f.onError==="function"){f.onError.call(h,"UPLOAD_LIMIT_EXCEEDED",e.filesToUpload());}}}}});},destroy:function(){this.each(function(){var e=b(this),c=e.data("uploadifive"),d=c.settings;a.clearQueue.call(e);if(!d.queueID){b("#"+d.queueID).remove();}e.siblings("input").remove();e.show().insertBefore(c.button);c.button.remove();if(typeof d.onDestroy==="function"){d.onDestroy.call(e);}});}};b.fn.uploadifive=function(c){if(a[c]){return a[c].apply(this,Array.prototype.slice.call(arguments,1));}else{if(typeof c==="object"||!c){return a.init.apply(this,arguments);}else{b.error("The method "+c+" does not exist in $.uploadify");}}};})(jQuery);

/* STORAGE API PLUGIN //////////////////////////////////////////////////////////////////////////////////////////////////// */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e("object"==typeof exports?require("jquery"):jQuery)}(function(e){function t(t){var r,i,n,o=arguments.length,s=window[t],a=arguments,u=a[1];if(2>o)throw Error("Minimum 2 arguments must be given");if(e.isArray(u)){i={};for(var f in u){r=u[f];try{i[r]=JSON.parse(s.getItem(r))}catch(c){i[r]=s.getItem(r)}}return i}if(2!=o){try{i=JSON.parse(s.getItem(u))}catch(c){throw new ReferenceError(u+" is not defined in this storage")}for(var f=2;o-1>f;f++)if(i=i[a[f]],void 0===i)throw new ReferenceError([].slice.call(a,1,f+1).join(".")+" is not defined in this storage");if(e.isArray(a[f])){n=i,i={};for(var m in a[f])i[a[f][m]]=n[a[f][m]];return i}return i[a[f]]}try{return JSON.parse(s.getItem(u))}catch(c){return s.getItem(u)}}function r(t){var r,i,n=arguments.length,o=window[t],s=arguments,a=s[1],u=s[2],f={};if(2>n||!e.isPlainObject(a)&&3>n)throw Error("Minimum 3 arguments must be given or second parameter must be an object");if(e.isPlainObject(a)){for(var c in a)r=a[c],e.isPlainObject(r)?o.setItem(c,JSON.stringify(r)):o.setItem(c,r);return a}if(3==n)return"object"==typeof u?o.setItem(a,JSON.stringify(u)):o.setItem(a,u),u;try{i=o.getItem(a),null!=i&&(f=JSON.parse(i))}catch(m){}i=f;for(var c=2;n-2>c;c++)r=s[c],i[r]&&e.isPlainObject(i[r])||(i[r]={}),i=i[r];return i[s[c]]=s[c+1],o.setItem(a,JSON.stringify(f)),f}function i(t){var r,i,n=arguments.length,o=window[t],s=arguments,a=s[1];if(2>n)throw Error("Minimum 2 arguments must be given");if(e.isArray(a)){for(var u in a)o.removeItem(a[u]);return!0}if(2==n)return o.removeItem(a),!0;try{r=i=JSON.parse(o.getItem(a))}catch(f){throw new ReferenceError(a+" is not defined in this storage")}for(var u=2;n-1>u;u++)if(i=i[s[u]],void 0===i)throw new ReferenceError([].slice.call(s,1,u).join(".")+" is not defined in this storage");if(e.isArray(s[u]))for(var c in s[u])delete i[s[u][c]];else delete i[s[u]];return o.setItem(a,JSON.stringify(r)),!0}function n(t,r){var n=a(t);for(var o in n)i(t,n[o]);if(r)for(var o in e.namespaceStorages)u(o)}function o(r){var i=arguments.length,n=arguments,s=(window[r],n[1]);if(1==i)return 0==a(r).length;if(e.isArray(s)){for(var u=0;u<s.length;u++)if(!o(r,s[u]))return!1;return!0}try{var f=t.apply(this,arguments);e.isArray(n[i-1])||(f={totest:f});for(var u in f)if(!(e.isPlainObject(f[u])&&e.isEmptyObject(f[u])||e.isArray(f[u])&&!f[u].length)&&f[u])return!1;return!0}catch(c){return!0}}function s(r){var i=arguments.length,n=arguments,o=(window[r],n[1]);if(2>i)throw Error("Minimum 2 arguments must be given");if(e.isArray(o)){for(var a=0;a<o.length;a++)if(!s(r,o[a]))return!1;return!0}try{var u=t.apply(this,arguments);e.isArray(n[i-1])||(u={totest:u});for(var a in u)if(void 0===u[a]||null===u[a])return!1;return!0}catch(f){return!1}}function a(r){var i=arguments.length,n=window[r],o=arguments,s=(o[1],[]),a={};if(a=i>1?t.apply(this,o):n,a._cookie)for(var u in e.cookie())""!=u&&s.push(u.replace(a._prefix,""));else for(var f in a)s.push(f);return s}function u(t){if(!t||"string"!=typeof t)throw Error("First parameter must be a string");g?(window.localStorage.getItem(t)||window.localStorage.setItem(t,"{}"),window.sessionStorage.getItem(t)||window.sessionStorage.setItem(t,"{}")):(window.localCookieStorage.getItem(t)||window.localCookieStorage.setItem(t,"{}"),window.sessionCookieStorage.getItem(t)||window.sessionCookieStorage.setItem(t,"{}"));var r={localStorage:e.extend({},e.localStorage,{_ns:t}),sessionStorage:e.extend({},e.sessionStorage,{_ns:t})};return e.cookie&&(window.cookieStorage.getItem(t)||window.cookieStorage.setItem(t,"{}"),r.cookieStorage=e.extend({},e.cookieStorage,{_ns:t})),e.namespaceStorages[t]=r,r}function f(e){if(!window[e])return!1;var t="jsapi";try{return window[e].setItem(t,t),window[e].removeItem(t),!0}catch(r){return!1}}var c="ls_",m="ss_",g=f("localStorage"),h={_type:"",_ns:"",_callMethod:function(e,t){var r=[this._type],t=Array.prototype.slice.call(t),i=t[0];return this._ns&&r.push(this._ns),"string"==typeof i&&-1!==i.indexOf(".")&&(t.shift(),[].unshift.apply(t,i.split("."))),[].push.apply(r,t),e.apply(this,r)},get:function(){return this._callMethod(t,arguments)},set:function(){var t=arguments.length,i=arguments,n=i[0];if(1>t||!e.isPlainObject(n)&&2>t)throw Error("Minimum 2 arguments must be given or first parameter must be an object");if(e.isPlainObject(n)&&this._ns){for(var o in n)r(this._type,this._ns,o,n[o]);return n}var s=this._callMethod(r,i);return this._ns?s[n.split(".")[0]]:s},remove:function(){if(arguments.length<1)throw Error("Minimum 1 argument must be given");return this._callMethod(i,arguments)},removeAll:function(e){return this._ns?(r(this._type,this._ns,{}),!0):n(this._type,e)},isEmpty:function(){return this._callMethod(o,arguments)},isSet:function(){if(arguments.length<1)throw Error("Minimum 1 argument must be given");return this._callMethod(s,arguments)},keys:function(){return this._callMethod(a,arguments)}};if(e.cookie){window.name||(window.name=Math.floor(1e8*Math.random()));var l={_cookie:!0,_prefix:"",_expires:null,_path:null,_domain:null,setItem:function(t,r){e.cookie(this._prefix+t,r,{expires:this._expires,path:this._path,domain:this._domain})},getItem:function(t){return e.cookie(this._prefix+t)},removeItem:function(t){return e.removeCookie(this._prefix+t)},clear:function(){for(var t in e.cookie())""!=t&&(!this._prefix&&-1===t.indexOf(c)&&-1===t.indexOf(m)||this._prefix&&0===t.indexOf(this._prefix))&&e.removeCookie(t)},setExpires:function(e){return this._expires=e,this},setPath:function(e){return this._path=e,this},setDomain:function(e){return this._domain=e,this},setConf:function(e){return e.path&&(this._path=e.path),e.domain&&(this._domain=e.domain),e.expires&&(this._expires=e.expires),this},setDefaultConf:function(){this._path=this._domain=this._expires=null}};g||(window.localCookieStorage=e.extend({},l,{_prefix:c,_expires:3650}),window.sessionCookieStorage=e.extend({},l,{_prefix:m+window.name+"_"})),window.cookieStorage=e.extend({},l),e.cookieStorage=e.extend({},h,{_type:"cookieStorage",setExpires:function(e){return window.cookieStorage.setExpires(e),this},setPath:function(e){return window.cookieStorage.setPath(e),this},setDomain:function(e){return window.cookieStorage.setDomain(e),this},setConf:function(e){return window.cookieStorage.setConf(e),this},setDefaultConf:function(){return window.cookieStorage.setDefaultConf(),this}})}e.initNamespaceStorage=function(e){return u(e)},g?(e.localStorage=e.extend({},h,{_type:"localStorage"}),e.sessionStorage=e.extend({},h,{_type:"sessionStorage"})):(e.localStorage=e.extend({},h,{_type:"localCookieStorage"}),e.sessionStorage=e.extend({},h,{_type:"sessionCookieStorage"})),e.namespaceStorages={},e.removeAllStorages=function(t){e.localStorage.removeAll(t),e.sessionStorage.removeAll(t),e.cookieStorage&&e.cookieStorage.removeAll(t),t||(e.namespaceStorages={})}});

/* HASHTABLE //////////////////////////////////////////////////////////////////////////////////////////////////// */
/*
var Hashtable=function(t){function n(t){return typeof t==p?t:""+t}function e(t){var r;return typeof t==p?t:typeof t.hashCode==y?(r=t.hashCode(),typeof r==p?r:e(r)):n(t)}function r(t,n){for(var e in n)n.hasOwnProperty(e)&&(t[e]=n[e])}function i(t,n){return t.equals(n)}function u(t,n){return typeof n.equals==y?n.equals(t):t===n}function o(n){return function(e){if(null===e)throw new Error("null is not a valid "+n);if(e===t)throw new Error(n+" must not be undefined")}}function s(t,n,e,r){this[0]=t,this.entries=[],this.addEntry(n,e),null!==r&&(this.getEqualityFunction=function(){return r})}function a(t){return function(n){for(var e,r=this.entries.length,i=this.getEqualityFunction(n);r--;)if(e=this.entries[r],i(n,e[0]))switch(t){case E:return!0;case K:return e;case q:return[r,e[1]]}return!1}}function l(t){return function(n){for(var e=n.length,r=0,i=this.entries,u=i.length;u>r;++r)n[e+r]=i[r][t]}}function f(t,n){for(var e,r=t.length;r--;)if(e=t[r],n===e[0])return r;return null}function h(t,n){var e=t[n];return e&&e instanceof s?e:null}function c(){var n=[],i={},u={replaceDuplicateKey:!0,hashCode:e,equals:null},o=arguments[0],a=arguments[1];a!==t?(u.hashCode=o,u.equals=a):o!==t&&r(u,o);var l=u.hashCode,c=u.equals;this.properties=u,this.put=function(t,e){g(t),d(e);var r,o,a=l(t),f=null;return r=h(i,a),r?(o=r.getEntryForKey(t),o?(u.replaceDuplicateKey&&(o[0]=t),f=o[1],o[1]=e):r.addEntry(t,e)):(r=new s(a,t,e,c),n.push(r),i[a]=r),f},this.get=function(t){g(t);var n=l(t),e=h(i,n);if(e){var r=e.getEntryForKey(t);if(r)return r[1]}return null},this.containsKey=function(t){g(t);var n=l(t),e=h(i,n);return e?e.containsKey(t):!1},this.containsValue=function(t){d(t);for(var e=n.length;e--;)if(n[e].containsValue(t))return!0;return!1},this.clear=function(){n.length=0,i={}},this.isEmpty=function(){return!n.length};var y=function(t){return function(){for(var e=[],r=n.length;r--;)n[r][t](e);return e}};this.keys=y("keys"),this.values=y("values"),this.entries=y("getEntries"),this.remove=function(t){g(t);var e,r=l(t),u=null,o=h(i,r);return o&&(u=o.removeEntryForKey(t),null!==u&&0==o.entries.length&&(e=f(n,r),n.splice(e,1),delete i[r])),u},this.size=function(){for(var t=0,e=n.length;e--;)t+=n[e].entries.length;return t}}var y="function",p="string",v="undefined";if(typeof encodeURIComponent==v||Array.prototype.splice===t||Object.prototype.hasOwnProperty===t)return null;var g=o("key"),d=o("value"),E=0,K=1,q=2;return s.prototype={getEqualityFunction:function(t){return typeof t.equals==y?i:u},getEntryForKey:a(K),getEntryAndIndexForKey:a(q),removeEntryForKey:function(t){var n=this.getEntryAndIndexForKey(t);return n?(this.entries.splice(n[0],1),n[1]):null},addEntry:function(t,n){this.entries.push([t,n])},keys:l(0),values:l(1),getEntries:function(t){for(var n=t.length,e=0,r=this.entries,i=r.length;i>e;++e)t[n+e]=r[e].slice(0)},containsKey:a(E),containsValue:function(t){for(var n=this.entries,e=n.length;e--;)if(t===n[e][1])return!0;return!1}},c.prototype={each:function(t){for(var n,e=this.entries(),r=e.length;r--;)n=e[r],t(n[0],n[1])},equals:function(t){var n,e,r,i=this.size();if(i==t.size()){for(n=this.keys();i--;)if(e=n[i],r=t.get(e),null===r||r!==this.get(e))return!1;return!0}return!1},putAll:function(t,n){for(var e,r,i,u,o=t.entries(),s=o.length,a=typeof n==y;s--;)e=o[s],r=e[0],i=e[1],a&&(u=this.get(r))&&(i=n(r,u,i)),this.put(r,i)},clone:function(){var t=new c(this.properties);return t.putAll(this),t}},c.prototype.toQueryString=function(){for(var t,e=this.entries(),r=e.length,i=[];r--;)t=e[r],i[r]=encodeURIComponent(n(t[0]))+"="+encodeURIComponent(n(t[1]));return i.join("&")},c}();

/* NUMBER FORMATTER //////////////////////////////////////////////////////////////////////////////////////////////////// */
/*
!function(e){function r(e,r,t){this.dec=e,this.group=r,this.neg=t}function t(){for(var e=0;e<s.length;e++)for(var r=s[e],t=0;t<r.length;t++)a.put(r[t],e)}function n(e,n){0==a.size()&&t();var i=".",o=",",l="-";0==n&&(-1!=e.indexOf("_")?e=e.split("_")[1].toLowerCase():-1!=e.indexOf("-")&&(e=e.split("-")[1].toLowerCase()));var u=a.get(e);if(u){var s=f[u];s&&(i=s[0],o=s[1])}return new r(i,o,l)}var a=new Hashtable,i=["ae","au","ca","cn","eg","gb","hk","il","in","jp","sk","th","tw","us"],o=["at","br","de","dk","es","gr","it","nl","pt","tr","vn"],l=["bg","cz","fi","fr","no","pl","ru","se"],u=["ch"],f=[[".",","],[",","."],[","," "],[".","'"]],s=[i,o,l,u];e.fn.formatNumber=function(r,t,n){return this.each(function(){null==t&&(t=!0),null==n&&(n=!0);var a;a=e(this).is(":input")?new String(e(this).val()):new String(e(this).text());var i=e.formatNumber(a,r);return t&&(e(this).is(":input")?e(this).val(i):e(this).text(i)),n?i:void 0})},e.formatNumber=function(r,t){for(var t=e.extend({},e.fn.formatNumber.defaults,t),a=n(t.locale.toLowerCase(),t.isFullLocale),i=(a.dec,a.group,a.neg,"0#-,."),o="",l=!1,u=0;u<t.format.length;u++){if(-1!=i.indexOf(t.format.charAt(u))){if(0==u&&"-"==t.format.charAt(u)){l=!0;continue}break}o+=t.format.charAt(u)}for(var f="",u=t.format.length-1;u>=0&&-1==i.indexOf(t.format.charAt(u));u--)f=t.format.charAt(u)+f;t.format=t.format.substring(o.length),t.format=t.format.substring(0,t.format.length-f.length);var s=new Number(r);return e._formatNumber(s,t,f,o,l)},e._formatNumber=function(r,t,a,i,o){var t=e.extend({},e.fn.formatNumber.defaults,t),l=n(t.locale.toLowerCase(),t.isFullLocale),u=l.dec,f=l.group,s=l.neg;null!=t.overrideGroupSep&&(f=t.overrideGroupSep),null!=t.overrideDecSep&&(u=t.overrideDecSep),null!=t.overrideNegSign&&(s=t.overrideNegSign);var c=!1;if(isNaN(r)){if(1!=t.nanForceZero)return"";r=0,c=!0}(1==t.isPercentage||t.autoDetectPercentage&&"%"==a.charAt(a.length-1))&&(r=100*r);var g="";if(t.format.indexOf(".")>-1){var h=u,d=t.format.substring(t.format.lastIndexOf(".")+1);if(1==t.round)r=new Number(r.toFixed(d.length));else{var m=r.toString();m.lastIndexOf(".")>0&&(m=m.substring(0,m.lastIndexOf(".")+d.length+1)),r=new Number(m)}var v=new Number(r.toString().substring(r.toString().indexOf(".")));decimalString=new String(v.toFixed(d.length)),decimalString=decimalString.substring(decimalString.lastIndexOf(".")+1);for(var p=0;p<d.length;p++)if("#"!=d.charAt(p)||"0"==decimalString.charAt(p)){if("#"==d.charAt(p)&&"0"==decimalString.charAt(p)){var S=decimalString.substring(p);if(S.match("[1-9]")){h+=decimalString.charAt(p);continue}break}"0"==d.charAt(p)&&(h+=decimalString.charAt(p))}else h+=decimalString.charAt(p);g+=h}else r=Math.round(r);var b=Math.floor(r);0>r&&(b=Math.ceil(r));var x="";x=-1==t.format.indexOf(".")?t.format:t.format.substring(0,t.format.indexOf("."));var N="";if(0!=b||"#"!=x.substr(x.length-1)||c){var w=new String(Math.abs(b)),A=9999;-1!=x.lastIndexOf(",")&&(A=x.length-x.lastIndexOf(",")-1);for(var O=0,p=w.length-1;p>-1;p--)N=w.charAt(p)+N,O++,O==A&&0!=p&&(N=f+N,O=0);if(x.length>N.length){var F=x.indexOf("0");if(-1!=F)for(var D=x.length-F,L=x.length-N.length-1;N.length<D;){var P=x.charAt(L);","==P&&(P=f),N=P+N,L--}}}return N||-1===x.indexOf("0",x.length-1)||(N="0"),g=N+g,0>r&&o&&i.length>0?i=s+i:0>r&&(g=s+g),t.decimalSeparatorAlwaysShown||g.lastIndexOf(u)==g.length-1&&(g=g.substring(0,g.length-1)),g=i+g+a},e.fn.parseNumber=function(r,t,n){null==t&&(t=!0),null==n&&(n=!0);var a;a=e(this).is(":input")?new String(e(this).val()):new String(e(this).text());var i=e.parseNumber(a,r);return i&&(t&&(e(this).is(":input")?e(this).val(i.toString()):e(this).text(i.toString())),n)?i:void 0},e.parseNumber=function(r,t){var t=e.extend({},e.fn.parseNumber.defaults,t),a=n(t.locale.toLowerCase(),t.isFullLocale),i=a.dec,o=a.group,l=a.neg;null!=t.overrideGroupSep&&(o=t.overrideGroupSep),null!=t.overrideDecSep&&(i=t.overrideDecSep),null!=t.overrideNegSign&&(l=t.overrideNegSign);for(var u="1234567890",f=".-",s=t.strict;r.indexOf(o)>-1;)r=r.replace(o,"");r=r.replace(i,".").replace(l,"-");var c="",g=!1;(1==t.isPercentage||t.autoDetectPercentage&&"%"==r.charAt(r.length-1))&&(g=!0);for(var h=0;h<r.length;h++)if(u.indexOf(r.charAt(h))>-1)c+=r.charAt(h);else if(f.indexOf(r.charAt(h))>-1)c+=r.charAt(h),f=f.replace(r.charAt(h),"");else{if(t.allowPostfix)break;if(s){c="NaN";break}}var d=new Number(c);if(g){d/=100;var m=c.indexOf(".");if(-1!=m){var v=c.length-m-1;d=d.toFixed(v+2)}else d=d.toFixed(2)}return d},e.fn.parseNumber.defaults={locale:"us",decimalSeparatorAlwaysShown:!1,isPercentage:!1,autoDetectPercentage:!0,isFullLocale:!1,strict:!1,overrideGroupSep:null,overrideDecSep:null,overrideNegSign:null,allowPostfix:!1},e.fn.formatNumber.defaults={format:"#,###.00",locale:"us",decimalSeparatorAlwaysShown:!1,nanForceZero:!0,round:!0,isFullLocale:!1,overrideGroupSep:null,overrideDecSep:null,overrideNegSign:null,isPercentage:!1,autoDetectPercentage:!0},Number.prototype.toFixed=function(r){return e._roundNumber(this,r)},e._roundNumber=function(e,r){var t=Math.pow(10,r||0),n=String(Math.round(e*t)/t);if(r>0){var a=n.indexOf(".");for(-1==a?(n+=".",a=0):a=n.length-(a+1);r>a;)n+="0",a++}return n}}(jQuery);

/* JSCOLOR //////////////////////////////////////////////////////////////////////////////////////////////////// */
var jscolor={dir:lcp_path,bindClass:"lcp color",binding:!0,preloading:!0,install:function(){jscolor.addEvent(window,"load",jscolor.init)},init:function(){jscolor.binding&&jscolor.bind(),jscolor.preloading&&jscolor.preload()},getDir:function(){if(!jscolor.dir){var e=jscolor.detectDir();jscolor.dir=e!==!1?e:"jscolor/"}return jscolor.dir},detectDir:function(){for(var e=location.href,t=document.getElementsByTagName("base"),o=0;o<t.length;o+=1)t[o].href&&(e=t[o].href);for(var t=document.getElementsByTagName("script"),o=0;o<t.length;o+=1)if(t[o].src&&/(^|\/)jscolor\.js([?#].*)?$/i.test(t[o].src)){var r=new jscolor.URI(t[o].src),s=r.toAbsolute(e);return s.path=s.path.replace(/[^\/]+$/,""),s.query=null,s.fragment=null,s.toString()}return!1},bind:function(){for(var e=new RegExp("(^|\\s)("+jscolor.bindClass+")(\\s*(\\{[^}]*\\})|\\s|$)","i"),t=document.getElementsByTagName("input"),o=0;o<t.length;o+=1){var r;if(!t[o].color&&t[o].className&&(r=t[o].className.match(e))){var s={};if(r[4])try{s=new Function("return ("+r[4]+")")()}catch(i){}t[o].color=new jscolor.color(t[o],s)}}},preload:function(){for(var e in jscolor.imgRequire)jscolor.imgRequire.hasOwnProperty(e)&&jscolor.loadImage(e)},images:{pad:[181,101],sld:[16,101],cross:[15,15],arrow:[7,11]},imgRequire:{},imgLoaded:{},requireImage:function(e){jscolor.imgRequire[e]=!0},loadImage:function(e){jscolor.imgLoaded[e]||(jscolor.imgLoaded[e]=new Image,jscolor.imgLoaded[e].src=jscolor.getDir()+e)},fetchElement:function(e){return"string"==typeof e?document.getElementById(e):e},addEvent:function(e,t,o){e.addEventListener?e.addEventListener(t,o,!1):e.attachEvent&&e.attachEvent("on"+t,o)},fireEvent:function(e,t){if(e)if(document.createEvent){var o=document.createEvent("HTMLEvents");o.initEvent(t,!0,!0),e.dispatchEvent(o)}else if(document.createEventObject){var o=document.createEventObject();e.fireEvent("on"+t,o)}else e["on"+t]&&e["on"+t]()},getElementPos:function(e){var t=e,o=e,r=0,s=0;if(t.offsetParent)do r+=t.offsetLeft,s+=t.offsetTop;while(t=t.offsetParent);for(;(o=o.parentNode)&&"BODY"!==o.nodeName.toUpperCase();)r-=o.scrollLeft,s-=o.scrollTop;return[r,s]},getElementSize:function(e){return[e.offsetWidth,e.offsetHeight]},getRelMousePos:function(e){var t=0,o=0;return e||(e=window.event),"number"==typeof e.offsetX?(t=e.offsetX,o=e.offsetY):"number"==typeof e.layerX&&(t=e.layerX,o=e.layerY),{x:t,y:o}},getViewPos:function(){return"number"==typeof window.pageYOffset?[window.pageXOffset,window.pageYOffset]:document.body&&(document.body.scrollLeft||document.body.scrollTop)?[document.body.scrollLeft,document.body.scrollTop]:document.documentElement&&(document.documentElement.scrollLeft||document.documentElement.scrollTop)?[document.documentElement.scrollLeft,document.documentElement.scrollTop]:[0,0]},getViewSize:function(){return"number"==typeof window.innerWidth?[window.innerWidth,window.innerHeight]:document.body&&(document.body.clientWidth||document.body.clientHeight)?[document.body.clientWidth,document.body.clientHeight]:document.documentElement&&(document.documentElement.clientWidth||document.documentElement.clientHeight)?[document.documentElement.clientWidth,document.documentElement.clientHeight]:[0,0]},URI:function(e){function t(e){for(var t="";e;)if("../"===e.substr(0,3)||"./"===e.substr(0,2))e=e.replace(/^\.+/,"").substr(1);else if("/./"===e.substr(0,3)||"/."===e)e="/"+e.substr(3);else if("/../"===e.substr(0,4)||"/.."===e)e="/"+e.substr(4),t=t.replace(/\/?[^\/]*$/,"");else if("."===e||".."===e)e="";else{var o=e.match(/^\/?[^\/]*/)[0];e=e.substr(o.length),t+=o}return t}this.scheme=null,this.authority=null,this.path="",this.query=null,this.fragment=null,this.parse=function(e){var t=e.match(/^(([A-Za-z][0-9A-Za-z+.-]*)(:))?((\/\/)([^\/?#]*))?([^?#]*)((\?)([^#]*))?((#)(.*))?/);return this.scheme=t[3]?t[2]:null,this.authority=t[5]?t[6]:null,this.path=t[7],this.query=t[9]?t[10]:null,this.fragment=t[12]?t[13]:null,this},this.toString=function(){var e="";return null!==this.scheme&&(e=e+this.scheme+":"),null!==this.authority&&(e=e+"//"+this.authority),null!==this.path&&(e+=this.path),null!==this.query&&(e=e+"?"+this.query),null!==this.fragment&&(e=e+"#"+this.fragment),e},this.toAbsolute=function(e){var e=new jscolor.URI(e),o=this,r=new jscolor.URI;return null===e.scheme?!1:(null!==o.scheme&&o.scheme.toLowerCase()===e.scheme.toLowerCase()&&(o.scheme=null),null!==o.scheme?(r.scheme=o.scheme,r.authority=o.authority,r.path=t(o.path),r.query=o.query):(null!==o.authority?(r.authority=o.authority,r.path=t(o.path),r.query=o.query):(""===o.path?(r.path=e.path,null!==o.query?r.query=o.query:r.query=e.query):("/"===o.path.substr(0,1)?r.path=t(o.path):(null!==e.authority&&""===e.path?r.path="/"+o.path:r.path=e.path.replace(/[^\/]+$/,"")+o.path,r.path=t(r.path)),r.query=o.query),r.authority=e.authority),r.scheme=e.scheme),r.fragment=o.fragment,r)},e&&this.parse(e)},color:function(e,t){function o(e,t,o){var r=Math.min(Math.min(e,t),o),s=Math.max(Math.max(e,t),o),i=s-r;if(0===i)return[null,0,s];var n=e===r?3+(o-t)/i:t===r?5+(e-o)/i:1+(t-e)/i;return[6===n?0:n,i/s,s]}function r(e,t,o){if(null===e)return[o,o,o];var r=Math.floor(e),s=r%2?e-r:1-(e-r),i=o*(1-t),n=o*(1-t*s);switch(r){case 6:case 0:return[o,n,i];case 1:return[n,o,i];case 2:return[i,o,n];case 3:return[i,n,o];case 4:return[n,i,o];case 5:return[o,i,n]}}function s(){delete jscolor.picker.owner,document.getElementsByTagName("body")[0].removeChild(jscolor.picker.boxB)}function i(t,o){function r(){var e=f.pickerInsetColor.split(/\s+/),t=e.length<2?e[0]:e[1]+" "+e[0]+" "+e[0]+" "+e[1];h.btn.style.borderColor=t}if(!jscolor.picker){jscolor.picker={box:document.createElement("div"),boxB:document.createElement("div"),pad:document.createElement("div"),padB:document.createElement("div"),padM:document.createElement("div"),sld:document.createElement("div"),sldB:document.createElement("div"),sldM:document.createElement("div"),btn:document.createElement("div"),btnS:document.createElement("span"),btnT:document.createTextNode(f.pickerCloseText)};for(var s=0,i=4;s<jscolor.images.sld[1];s+=i){var a=document.createElement("div");a.style.height=i+"px",a.style.fontSize="1px",a.style.lineHeight="0",jscolor.picker.sld.appendChild(a)}jscolor.picker.sldB.appendChild(jscolor.picker.sld),jscolor.picker.box.appendChild(jscolor.picker.sldB),jscolor.picker.box.appendChild(jscolor.picker.sldM),jscolor.picker.padB.appendChild(jscolor.picker.pad),jscolor.picker.box.appendChild(jscolor.picker.padB),jscolor.picker.box.appendChild(jscolor.picker.padM),jscolor.picker.btnS.appendChild(jscolor.picker.btnT),jscolor.picker.btn.appendChild(jscolor.picker.btnS),jscolor.picker.box.appendChild(jscolor.picker.btn),jscolor.picker.boxB.appendChild(jscolor.picker.box)}var h=jscolor.picker;if(h.box.onmouseup=h.box.onmouseout=function(){e.focus()},h.box.onmousedown=function(){y=!0},h.box.onmousemove=function(e){(j||x)&&(j&&d(e),x&&p(e),document.selection?document.selection.empty():window.getSelection&&window.getSelection().removeAllRanges(),m())},"ontouchstart"in window){var u=function(e){var t={offsetX:e.touches[0].pageX-w.X,offsetY:e.touches[0].pageY-w.Y};(j||x)&&(j&&d(t),x&&p(t),m()),e.stopPropagation(),e.preventDefault()};h.box.removeEventListener("touchmove",u,!1),h.box.addEventListener("touchmove",u,!1)}h.padM.onmouseup=h.padM.onmouseout=function(){j&&(j=!1,jscolor.fireEvent(k,"change"))},h.padM.onmousedown=function(e){switch(b){case 0:0===f.hsv[2]&&f.fromHSV(null,null,1);break;case 1:0===f.hsv[1]&&f.fromHSV(null,1,null)}x=!1,j=!0,d(e),m()},"ontouchstart"in window&&h.padM.addEventListener("touchstart",function(e){w={X:e.target.offsetParent.offsetLeft,Y:e.target.offsetParent.offsetTop},this.onmousedown({offsetX:e.touches[0].pageX-w.X,offsetY:e.touches[0].pageY-w.Y})}),h.sldM.onmouseup=h.sldM.onmouseout=function(){x&&(x=!1,jscolor.fireEvent(k,"change"))},h.sldM.onmousedown=function(e){j=!1,x=!0,p(e),m()},"ontouchstart"in window&&h.sldM.addEventListener("touchstart",function(e){w={X:e.target.offsetParent.offsetLeft,Y:e.target.offsetParent.offsetTop},this.onmousedown({offsetX:e.touches[0].pageX-w.X,offsetY:e.touches[0].pageY-w.Y})});var g=n(f);h.box.style.width=g[0]+"px",h.box.style.height=g[1]+"px",h.boxB.style.position="absolute",h.boxB.style.clear="both",h.boxB.style.left=t+"px",h.boxB.style.top=o+"px",h.boxB.style.zIndex=f.pickerZIndex,h.boxB.style.border=f.pickerBorder+"px solid",h.boxB.style.borderColor=f.pickerBorderColor,h.boxB.style.background=f.pickerFaceColor,h.pad.style.width=jscolor.images.pad[0]+"px",h.pad.style.height=jscolor.images.pad[1]+"px",h.padB.style.position="absolute",h.padB.style.left=f.pickerFace+"px",h.padB.style.top=f.pickerFace+"px",h.padB.style.border=f.pickerInset+"px solid",h.padB.style.borderColor=f.pickerInsetColor,h.padM.style.position="absolute",h.padM.style.left="0",h.padM.style.top="0",h.padM.style.width=f.pickerFace+2*f.pickerInset+jscolor.images.pad[0]+jscolor.images.arrow[0]+"px",h.padM.style.height=h.box.style.height,h.padM.style.cursor="crosshair",h.sld.style.overflow="hidden",h.sld.style.width=jscolor.images.sld[0]+"px",h.sld.style.height=jscolor.images.sld[1]+"px",h.sldB.style.display=f.slider?"block":"none",h.sldB.style.position="absolute",h.sldB.style.right=f.pickerFace+"px",h.sldB.style.top=f.pickerFace+"px",h.sldB.style.border=f.pickerInset+"px solid",h.sldB.style.borderColor=f.pickerInsetColor,h.sldM.style.display=f.slider?"block":"none",h.sldM.style.position="absolute",h.sldM.style.right="0",h.sldM.style.top="0",h.sldM.style.width=jscolor.images.sld[0]+jscolor.images.arrow[0]+f.pickerFace+2*f.pickerInset+"px",h.sldM.style.height=h.box.style.height;try{h.sldM.style.cursor="pointer"}catch(v){h.sldM.style.cursor="hand"}h.btn.style.display=f.pickerClosable?"block":"none",h.btn.style.position="absolute",h.btn.style.left=f.pickerFace+"px",h.btn.style.bottom=f.pickerFace+"px",h.btn.style.padding="0 15px",h.btn.style.height="18px",h.btn.style.border=f.pickerInset+"px solid",r(),h.btn.style.color=f.pickerButtonColor,h.btn.style.font="12px sans-serif",h.btn.style.textAlign="center";try{h.btn.style.cursor="pointer"}catch(v){h.btn.style.cursor="hand"}switch(h.btn.onmousedown=function(){f.hidePicker()},h.btnS.style.lineHeight=h.btn.style.height,b){case 0:var M="views/img/jscolor/hs.png";break;case 1:var M="views/img/jscolor/hv.png"}h.padM.style.backgroundImage="url('"+jscolor.getDir()+"views/img/jscolor/cross.gif')",h.padM.style.backgroundRepeat="no-repeat",h.sldM.style.backgroundImage="url('"+jscolor.getDir()+"views/img/jscolor/arrow.gif')",h.sldM.style.backgroundRepeat="no-repeat",h.pad.style.backgroundImage="url('"+jscolor.getDir()+M+"')",h.pad.style.backgroundRepeat="no-repeat",h.pad.style.backgroundPosition="0 0",l(),c(),jscolor.picker.owner=f,document.getElementsByTagName("body")[0].appendChild(h.boxB)}function n(e){var t=[2*e.pickerInset+2*e.pickerFace+jscolor.images.pad[0]+(e.slider?2*e.pickerInset+2*jscolor.images.arrow[0]+jscolor.images.sld[0]:0),e.pickerClosable?4*e.pickerInset+3*e.pickerFace+jscolor.images.pad[1]+e.pickerButtonHeight:2*e.pickerInset+2*e.pickerFace+jscolor.images.pad[1]];return t}function l(){switch(b){case 0:var e=1;break;case 1:var e=2}var t=Math.round(f.hsv[0]/6*(jscolor.images.pad[0]-1)),o=Math.round((1-f.hsv[e])*(jscolor.images.pad[1]-1));jscolor.picker.padM.style.backgroundPosition=f.pickerFace+f.pickerInset+t-Math.floor(jscolor.images.cross[0]/2)+"px "+(f.pickerFace+f.pickerInset+o-Math.floor(jscolor.images.cross[1]/2))+"px";var s=jscolor.picker.sld.childNodes;switch(b){case 0:for(var i=r(f.hsv[0],f.hsv[1],1),n=0;n<s.length;n+=1)s[n].style.backgroundColor="rgb("+i[0]*(1-n/s.length)*100+"%,"+i[1]*(1-n/s.length)*100+"%,"+i[2]*(1-n/s.length)*100+"%)";break;case 1:var i,l,c=[f.hsv[2],0,0],n=Math.floor(f.hsv[0]),a=n%2?f.hsv[0]-n:1-(f.hsv[0]-n);switch(n){case 6:case 0:i=[0,1,2];break;case 1:i=[1,0,2];break;case 2:i=[2,0,1];break;case 3:i=[2,1,0];break;case 4:i=[1,2,0];break;case 5:i=[0,2,1]}for(var n=0;n<s.length;n+=1)l=1-1/(s.length-1)*n,c[1]=c[0]*(1-l*a),c[2]=c[0]*(1-l),s[n].style.backgroundColor="rgb("+100*c[i[0]]+"%,"+100*c[i[1]]+"%,"+100*c[i[2]]+"%)"}}function c(){switch(b){case 0:var e=2;break;case 1:var e=1}var t=Math.round((1-f.hsv[e])*(jscolor.images.sld[1]-1));jscolor.picker.sldM.style.backgroundPosition="0 "+(f.pickerFace+f.pickerInset+t-Math.floor(jscolor.images.arrow[1]/2))+"px"}function a(){return jscolor.picker&&jscolor.picker.owner===f}function h(){k===e&&f.importColor(),f.pickerOnfocus&&f.hidePicker()}function u(){k!==e&&f.importColor()}function d(e){var t=jscolor.getRelMousePos(e),o=t.x-f.pickerFace-f.pickerInset,r=t.y-f.pickerFace-f.pickerInset;switch(b){case 0:f.fromHSV(o*(6/(jscolor.images.pad[0]-1)),1-r/(jscolor.images.pad[1]-1),null,I);break;case 1:f.fromHSV(o*(6/(jscolor.images.pad[0]-1)),null,1-r/(jscolor.images.pad[1]-1),I)}}function p(e){var t=jscolor.getRelMousePos(e),o=t.y-f.pickerFace-f.pickerInset;switch(b){case 0:f.fromHSV(null,null,1-o/(jscolor.images.sld[1]-1),E);break;case 1:f.fromHSV(null,1-o/(jscolor.images.sld[1]-1),null,E)}}function m(){if(f.onImmediateChange){var e;e="string"==typeof f.onImmediateChange?new Function(f.onImmediateChange):f.onImmediateChange,e.call(f)}}this.required=!0,this.adjust=!0,this.hash=!1,this.caps=!0,this.slider=!0,this.valueElement=e,this.styleElement=e,this.onImmediateChange=null,this.hsv=[0,0,1],this.rgb=[1,1,1],this.minH=0,this.maxH=6,this.minS=0,this.maxS=1,this.minV=0,this.maxV=1,this.pickerOnfocus=!0,this.pickerMode="HSV",this.pickerPosition="bottom",this.pickerSmartPosition=!0,this.pickerButtonHeight=20,this.pickerClosable=!1,this.pickerCloseText="Close",this.pickerButtonColor="ButtonText",this.pickerFace=10,this.pickerFaceColor="ThreeDFace",this.pickerBorder=1,this.pickerBorderColor="ThreeDHighlight ThreeDShadow ThreeDShadow ThreeDHighlight",this.pickerInset=1,this.pickerInsetColor="ThreeDShadow ThreeDHighlight ThreeDHighlight ThreeDShadow",this.pickerZIndex=1e4;for(var g in t)t.hasOwnProperty(g)&&(this[g]=t[g]);this.hidePicker=function(){a()&&s()},this.showPicker=function(){if(!a()){var t,o,r,s=jscolor.getElementPos(e),l=jscolor.getElementSize(e),c=jscolor.getViewPos(),h=jscolor.getViewSize(),u=n(this);switch(this.pickerPosition.toLowerCase()){case"left":t=1,o=0,r=-1;break;case"right":t=1,o=0,r=1;break;case"top":t=0,o=1,r=-1;break;default:t=0,o=1,r=1}var d=(l[o]+u[o])/2;if(this.pickerSmartPosition)var p=[-c[t]+s[t]+u[t]>h[t]&&-c[t]+s[t]+l[t]/2>h[t]/2&&s[t]+l[t]-u[t]>=0?s[t]+l[t]-u[t]:s[t],-c[o]+s[o]+l[o]+u[o]-d+d*r>h[o]?-c[o]+s[o]+l[o]/2>h[o]/2&&s[o]+l[o]-d-d*r>=0?s[o]+l[o]-d-d*r:s[o]+l[o]-d+d*r:s[o]+l[o]-d+d*r>=0?s[o]+l[o]-d+d*r:s[o]+l[o]-d-d*r];else var p=[s[t],s[o]+l[o]-d+d*r];i(p[t],p[o])}},this.importColor=function(){k?this.adjust?!this.required&&/^\s*$/.test(k.value)?(k.value="",v.style.backgroundImage=v.jscStyle.backgroundImage,v.style.backgroundColor=v.jscStyle.backgroundColor,v.style.color=v.jscStyle.color,this.exportColor(M|C)):this.fromString(k.value)||this.exportColor():this.fromString(k.value,M)||(v.style.backgroundImage=v.jscStyle.backgroundImage,v.style.backgroundColor=v.jscStyle.backgroundColor,v.style.color=v.jscStyle.color,this.exportColor(M|C)):this.exportColor()},this.exportColor=function(e){if(!(e&M)&&k){var t=this.toString();this.caps&&(t=t.toUpperCase()),this.hash&&(t="#"+t),k.value=t}e&C||!v||(v.style.backgroundImage="none",v.style.backgroundColor="#"+this.toString(),v.style.color=.213*this.rgb[0]+.715*this.rgb[1]+.072*this.rgb[2]<.5?"#FFF":"#000"),e&E||!a()||l(),e&I||!a()||c()},this.fromHSV=function(e,t,o,s){null!==e&&(e=Math.max(0,this.minH,Math.min(6,this.maxH,e))),null!==t&&(t=Math.max(0,this.minS,Math.min(1,this.maxS,t))),null!==o&&(o=Math.max(0,this.minV,Math.min(1,this.maxV,o))),this.rgb=r(null===e?this.hsv[0]:this.hsv[0]=e,null===t?this.hsv[1]:this.hsv[1]=t,null===o?this.hsv[2]:this.hsv[2]=o),this.exportColor(s)},this.fromRGB=function(e,t,s,i){null!==e&&(e=Math.max(0,Math.min(1,e))),null!==t&&(t=Math.max(0,Math.min(1,t))),null!==s&&(s=Math.max(0,Math.min(1,s)));var n=o(null===e?this.rgb[0]:e,null===t?this.rgb[1]:t,null===s?this.rgb[2]:s);null!==n[0]&&(this.hsv[0]=Math.max(0,this.minH,Math.min(6,this.maxH,n[0]))),0!==n[2]&&(this.hsv[1]=null===n[1]?null:Math.max(0,this.minS,Math.min(1,this.maxS,n[1]))),this.hsv[2]=null===n[2]?null:Math.max(0,this.minV,Math.min(1,this.maxV,n[2]));var l=r(this.hsv[0],this.hsv[1],this.hsv[2]);this.rgb[0]=l[0],this.rgb[1]=l[1],this.rgb[2]=l[2],this.exportColor(i)},this.fromString=function(e,t){var o=e.match(/^\W*([0-9A-F]{3}([0-9A-F]{3})?)\W*$/i);return o?(6===o[1].length?this.fromRGB(parseInt(o[1].substr(0,2),16)/255,parseInt(o[1].substr(2,2),16)/255,parseInt(o[1].substr(4,2),16)/255,t):this.fromRGB(parseInt(o[1].charAt(0)+o[1].charAt(0),16)/255,parseInt(o[1].charAt(1)+o[1].charAt(1),16)/255,parseInt(o[1].charAt(2)+o[1].charAt(2),16)/255,t),!0):!1},this.toString=function(){return(256|Math.round(255*this.rgb[0])).toString(16).substr(1)+(256|Math.round(255*this.rgb[1])).toString(16).substr(1)+(256|Math.round(255*this.rgb[2])).toString(16).substr(1)};var f=this,b="hvs"===this.pickerMode.toLowerCase()?1:0,y=!1,k=jscolor.fetchElement(this.valueElement),v=jscolor.fetchElement(this.styleElement),j=!1,x=!1,w={},M=1,C=2,E=4,I=8;if(jscolor.addEvent(e,"focus",function(){f.pickerOnfocus&&f.showPicker()}),jscolor.addEvent(e,"blur",function(){y?y=!1:window.setTimeout(function(){y||h(),y=!1},0)}),k){var S=function(){f.fromString(k.value,M),m()};jscolor.addEvent(k,"keyup",S),jscolor.addEvent(k,"input",S),jscolor.addEvent(k,"blur",u),k.setAttribute("autocomplete","off")}switch(v&&(v.jscStyle={backgroundImage:v.style.backgroundImage,backgroundColor:v.style.backgroundColor,color:v.style.color}),b){case 0:jscolor.requireImage("views/img/jscolor/hs.png");break;case 1:jscolor.requireImage("views/img/jscolor/hv.png")}jscolor.requireImage("views/img/jscolor/cross.gif"),jscolor.requireImage("views/img/jscolor/arrow.gif"),this.importColor()}};jscolor.install();


/* LIVECHATPRO CLASS //////////////////////////////////////////////////////////////////////////////////////////////////// */


$.loadJSArray = function(arr, path) {
    var _arr = $.map(arr, function(scr) {
        return $.getScript( (path||"") + scr );
    });

    _arr.push($.Deferred(function( deferred ){
        $( deferred.resolve );
    }));

    return $.when.apply($, _arr);
}

}); //end document ready


var lcp_request, lcp_requests = [];

function Livechatpro(config) {

}

/*
params.load*           = type: string (required)
                       = desc: php switch to load

params.divs*           = type: object (required)
                       = desc: object of divs to be populated

params.params*         = type: object (required)
                       = desc: object of vars to be sent to the php switch

params.preloader       = type: object (optional)
                       = desc: object of preloader proprietes
(
	params.preloader.divs  = type: object (required if preloader active)
	                       = desc: object of divs to populate the preloader

	params.preloader.type  = type: string (optional: 1 ; 2)
	                       = desc: type of preloader (when preloader is in the same div as the response ; when preloader is in the other div as the response)

	params.preloader.style = type: string (optional: 1 ; 2)
	                       = desc: style of preloader (style of animated image)
)

params.append          = type: boolean (true|false) (optional)
                       = desc: if is set to true it will append instead of replace content of divs

params.complete          = type: string (optional)
                       = desc: if is set to "loop" then it loops trough this function again (used to comet push)

callback               = type: callback function (optional)
					   = desc: function to put the ajax result

abort_active_requests  = type: boolean (true|false) (optional)
					   = desc: if is set to true it will abort all current ajax requests runned trough this function
*/
Livechatpro.prototype.ajaxController = function(params, callback, abort_active_requests)
{
	//console.log(window_focus);

	var self = this;
	var timestamp = Math.round(new Date().getTime() / 1000);

	// we verify if staff has 2 or more tabs opened and if he does we make sure that he does not sent ajax sync requests from each tab
	if (params.load == 'syncChatDialog'/* || params.load =='syncFrontChatDialog'*/)
	{
		if($.localStorage.isEmpty('lcp_last_ajax_sync'))
			$.localStorage.set('lcp_last_ajax_sync', timestamp);

		if($.localStorage.isEmpty('lcp_last_ajax_load'))
			$.localStorage.set('lcp_last_ajax_load', params.load);

		var lcp_sync_chat_interval = lcp_primary_settings.sync_chat_interval_backend; 
		var timeout = timestamp - $.localStorage.get('lcp_last_ajax_sync'); 
		//console.log('(timeout:'+timeout+' ,<, sync interval:'+lcp_sync_chat_interval+')')
		if ((timeout < lcp_sync_chat_interval) && (params.load == $.localStorage.get('lcp_last_ajax_load')))
		{
			console.log('ajax request stopped due to interval');
			return;
		}
		
		$.localStorage.set('lcp_last_ajax_sync', timestamp);
		$.localStorage.set('lcp_last_ajax_load', params.load);
	}

	// assign the undefined params to null
	params.async = typeof(params.async) != 'undefined' ? params.async : true;
	params.divs = typeof(params.divs) != 'undefined' ? params.divs : null;
	params.preloader = typeof(params.preloader) != 'undefined' ? params.preloader : null;
	callback = typeof(callback) != 'undefined' ? callback : null;
	abort_active_requests = typeof(abort_active_requests) != 'undefined' ? abort_active_requests : null;

	// the URL of the ajax
	var load_url = lcp_url + 'ajax.php?time=' + timestamp;

	// building the params
	params.params = $.extend({
		'type': params.load,
		'token': lcp_token,
		'session': lcp_session,
	}, params.params);
	
	// start the preloader
	if (params.preloader && params.preloader.divs != null)
		this.preload(params.preloader.divs, 'on', params.preloader.style);

	// if abort_active_requests is TRUE we delete all the active ajax requests
	if (abort_active_requests != null) this.abortActiveRequests();
	
	// building the ajax request...
	lcp_request = $.ajax({
		url: load_url,
		async: params.async,
		type: 'POST',
		data: params.params,
		//dataType: function() { return (json != null) ? 'json' : 'html'; },
		success: function(result)
		{
			if (params.divs != null)
			{
				if (self.isJson(result))
				{
					var res = JSON.parse(result);
					if (typeof(res.response) != 'undefined')
					{
						if (res.success === true)
						{
							$.each(params.divs, function(k, v)
							{
								if (params.append == true) $('#' + v).append(res.response);
								else $('#' + v).html(res.response);
							});
						}
					}
					else
					{
						$.each(params.divs, function(k, v)
						{
							if (params.append == true) $('#' + v).append(result);
							else $('#' + v).html(result);
						});
					}
				}
				else
				{
					$.each(params.divs, function(k, v)
					{
						if (params.append == true) $('#' + v).append(result);
						else $('#' + v).html(result);
					});
				}
			}
			
			// we end the preloader
			if (params.preloader && params.preloader.type == 2)
				self.preload(params.preloader.divs, 'off', params.preloader.style);

			// if the callback parameter is true we call the callback function
			if (typeof(callback) === "function") 
				callback(result);
			
			// if the loop parameter is true we loop the function
			if (params.complete == 'loop')
			{
				if (result)
				{
					try
					{
						var res = JSON.parse(result);
						params.params.timestamp = res.timestamp;
						//params.params.action = res.action; //console.log('am ajuns in (if result) : '+params.params.timestamp);
					}
					catch (e)
					{
						//JSON parse error, this is not json (or JSON isn't in your browser)
					}
				}
				else
				{
					//console.log('am ajuns in (if NOT result) : '+params.params.timestamp); //params.params.timestamp = 0;
					params.params.data = '';
				}
			}
			return true;
		},
		complete: function()
		{
			if (params.complete == 'loop')
			{
				self.ajaxController(params, callback);
			}
			return true;
		},
		error: function()
		{
			if (params.complete == 'loop')
			{
				//if(typeof(callback) === "function") callback(result); self.ajaxController(params, callback);
			}
			return false;
		},
	});
	lcp_requests.push(lcp_request);
};

Livechatpro.prototype.preload = function(divs, action, style)
{
	if (style == 1)
		var html = '<div style="height: 100%; width: 100%;" align="center"><img border="0" src="' + lcp_path + 'views/img/ajax-loader.gif"></div>';
	else if (style == 2)
		var html = '<table cellpadding="0" cellspacing="0"><tr><td>Loading...&nbsp;&nbsp;</td><td><img border="0" src="' + lcp_path + 'views/img/ajax-loader.gif"></td></tr></table>';
	else if (style == 3)
		var html = '<img border="0" src="' + lcp_path + 'views/img/ajax-loader.gif">';

	if (action == 'on')
	{
		$.each(divs, function(k, v)
		{
			$('#' + v).html(html);
		});
	}
	else
	{
		$.each(divs, function(k, v)
		{
			$('#' + v).html('');
		});
	}
};


Livechatpro.prototype.abortActiveRequests = function()
{
	try 
	{
		for (i = 0; i < lcp_requests.length; i++)
		{
			lcp_requests[i].abort();
		}
	} catch(e){}
	lcp_requests = [];
};


Livechatpro.prototype.getRatingStars = function()
{
	var full_stars = $("#rating_td").find("img[src*='star-full']").length;
	var half_stars = $("#rating_td").find("img[src*='star-half']").length;
	var empty_stars = $("#rating_td").find("img[src*='star-empty']").length;
	return full_stars + (half_stars / 2);
};

Livechatpro.prototype.replaceURLWithHTMLLinks = function(text)
{
	if (text)
	{
		text = text.replace(/(https?:\/\/|www\.)[^\s]*/gi, function(url)
		{
			var full_url = url;
			if (!full_url.match('^https?:\/\/'))
			{
				full_url = 'http://' + full_url;
			}
			return '<a href="' + full_url + '" target="_blank">' + url + '</a>';
		});
	}
	return text;
};

Livechatpro.prototype.escapeHtml = function(text)
{
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	};
	return text.replace(/[&<>"']/g, function(m)
	{
		return map[m];
	});
};


Livechatpro.prototype.sliceText = function(str)
{
	var len = 25;

	var exp = str.split(' '),
		result = [];

	for (var i = 0; i < exp.length; i++) 
	{
		var word = exp[i];

		if (word.length > len) {
			var regex = new RegExp('.{1,'+len+'}', 'g'),
				wordExp = word.match(regex);

			word = wordExp.join('<br>');
		}

		result.push(word);
	}

	return result.join(' ');
};

Livechatpro.prototype.parseEmoticons = function(string)
{
	string = typeof(string) != 'undefined' ? ' '+string : null;
	//console.log(lcp_emoticons);
	$.each( lcp_emoticons, function( key, value ) {
		if (string.toLowerCase().indexOf(' '+value.code) != '-1')
		{
			var string_code = string.substr(string.toLowerCase().indexOf(value.code), value.code.length);
			string = string.replace(string_code, '<img border="0" src="' + lcp_path + 'views/img/emoticons/' + value.filename + '">');
		}
	});
	return string;
};

Livechatpro.prototype.parseChatText = function(string)
{
	string = this.escapeHtml(string);
	string = this.replaceURLWithHTMLLinks(string);
	//string = this.sliceText(string);
	string = this.parseEmoticons(string);

	return string;
};


Livechatpro.prototype.isJson = function(str)
{
	try
	{
		JSON.parse(str);
	}
	catch (e)
	{
		return false;
	}
	return true;
};

Livechatpro.prototype.updateTips = function(t)
{
	var tips = $(".validateTips");
	tips.text(t).addClass("ui-state-highlight");
	setTimeout(function()
	{
		tips.removeClass("ui-state-highlight", 1500);
	}, 500);
};

Livechatpro.prototype.checkLength = function(o, n, min, max)
{
	var l = this.l;

	if (o.val().length > max || o.val().length < min)
	{
		o.addClass("ui-state-error");
		this.updateTips(l('Length of') + ' ' + n + ' ' + lcp.l('must be between') + ' ' + min + ' ' + lcp.l('and') + ' ' + max + '.');
		return false;
	}
	else
		return true;
};

Livechatpro.prototype.getHtmlCode = function(type, res, k)
{
	var k = typeof(k) != 'undefined' ? k : 0;
	if (type == 'ajax.chat.tpl')
	{
		var html = '\
			<input type="hidden" name="lcp_chat_status" id="lcp_chat_status" value="'+res.response.status+'">\
			<div id="before_chat_div_with_image" style="'+((lcp_primary_settings.slide_with_image == 'Y' && res.response.status == 'online') ? '' : 'display: none;') +'">\
				<img src="'+lcp_path+'views/img/iconsets/'+res.response.iconset_img+'" style="cursor: hand; cursor: pointer;" id="">\
			</div>\
			<div style="'+ ((lcp_primary_settings.hide_when_offline == 'Y' && res.response.status == 'offline') ? 'display:none;' : '') +''; if (res.response.chat_type != 'Popup') { html += 'border: '+ ((lcp_primary_settings.chat_box_border == 'Y') ? '1' : '0') +'px solid #'+ lcp_primary_settings.chat_box_border_color +' !important;'; }
				html += '\
				width: '+lcp_primary_settings.width+'px;\
				color: #'+lcp_primary_settings.chat_box_foreground+' !important;\
				background-color: #'+lcp_primary_settings.chat_box_background+' !important;\
				-webkit-border-top-left-radius: '+lcp_primary_settings.corners_radius+'px;\
				-moz-border-top-left-radius:'+lcp_primary_settings.corners_radius+'px;\
				border-top-left-radius:'+lcp_primary_settings.corners_radius+'px;\
				-webkit-border-top-right-radius:'+lcp_primary_settings.corners_radius+'px;\
				-moz-border-top-right-radius:'+lcp_primary_settings.corners_radius+'px;\
				border-top-right-radius:'+lcp_primary_settings.corners_radius+'px;\
				" class="row" id="chat_table">\
			<div style="';
				if (res.response.chat_type == 'Popup') { html += 'display: none;'; } else { if (lcp_primary_settings.slide_with_image == 'Y') { if (res.response.status == 'offline') html += 'display: table;'; else html += 'display: none;'; } else html += 'display: table;'; }
				if (res.response.status == 'offline') { html += 'color: #'+lcp_primary_settings.header_offline_foreground+' !important; background-color: #'+lcp_primary_settings.header_offline_background+' !important;'; } else { html += 'color: #'+lcp_primary_settings.header_online_foreground+'; background-color: #'+lcp_primary_settings.header_online_background+' !important;'; }
				html += '\
				-webkit-border-top-left-radius:'+lcp_primary_settings.corners_radius+'px;\
				-moz-border-top-left-radius:'+lcp_primary_settings.corners_radius+'px;\
				border-top-left-radius:'+lcp_primary_settings.corners_radius+'px;\
				-webkit-border-top-right-radius:'+lcp_primary_settings.corners_radius+'px;\
				-moz-border-top-right-radius:'+lcp_primary_settings.corners_radius+'px;\
				border-top-right-radius:'+lcp_primary_settings.corners_radius+'px;\
				height: 30px;\
				vertical-align: middle;\
				margin: 0px;\
				padding: 0px 5px;\
				width: 100% !important;\
				" id="chat_header_table" class="row lcp blink-container">\
				<div style="line-height: 30px; width: 16px; display: table-cell;" class="fa fa-'+((res.response.status == 'offline') ? 'envelope' : 'comment') +'"></div>\
				<div style="line-height: 30px; cursor: hand; cursor: pointer; width: auto; display: table-cell;" id="header_message_td" class="">'+ ((res.response.status == 'offline') ? lcp_primary_settings.offline_header_message : lcp_primary_settings.online_header_message) +'</div>\
				<div style="line-height: 30px; cursor: hand; cursor: pointer; width: 16px; display: table-cell;" title="Minimize" class="fa fa-chevron-'+ ((lcp_primary_settings.start_minimized == 'Y' || lcp.empty(res.response.chat_toggled) || res.response.chat_toggled == 'down') ? 'up' : 'down') +'" id="minimize_chat"></div>\
				<div style="line-height: 30px; cursor: hand; cursor: pointer; width: 16px; display: table-cell;" title="Close" class="fa fa-times-circle" id="close_chat"></div>\
			</div>\
			<div class="clearboth"></div>\
		<div id="chat_inner_table" style="'+((lcp_primary_settings.start_minimized == 'Y' || lcp.empty(res.response.chat_toggled) || res.response.chat_toggled == 'down') ? 'display:none;' : '') +'">\
			\
			<!-- BEGIN before_chat_div -->\
			<div id="before_chat_div" style="'; if (res.response.visitor_chat_status != 'Y' && res.response.visitor_chat_status != 'P') { if (lcp_primary_settings.slide_with_image == 'Y') { if(res.response.status == 'offline') html += 'display: block;'; else html += 'display: none;'; } } else html += 'display:none;'; html += 'padding: 0px 10px;">\
				<div id="welcome_message_tr" style="margin: 5px 5px;">\
					<span id="welcome_message_span">'+ ((res.response.status == 'offline') ? lcp_primary_settings.offline_welcome_message : lcp_primary_settings.online_welcome_message) +'</span>\
				</div>\
				<div class="lcp validateTips" id="error_tr" style="margin: 5px 5px; display:none;">\
					'+lcp.l('All form fields are required.')+'\
				</div>\
				<div class="" id="name_field_tbody" style="margin: 5px 0px; '; if (res.response.status == 'online') { if (lcp_primary_settings.name_field_online == 'N') { html += 'display:none;'; } } else { if (lcp_primary_settings.name_field_offline == 'N') { html += 'display:none;'; } } html += '">\
					<input class="lcp form-control formfield" type="text" name="lcp_name" id="lcp_name" placeholder="'+ ((!lcp.empty(res.response.customer_details)) ? res.response.customer_details.firstname+' '+res.response.customer_details.lastname : lcp.l('Your name')) +'">\
				</div>\
				<div class="" id="email_field_tbody" style="margin: 5px 0px; '; if (res.response.status == 'online') { if (lcp_primary_settings.email_field_online == 'N') { html += 'display:none;'; } } else { if (lcp_primary_settings.email_field_offline == 'N') { html += 'display:none;'; } } html += '">\
					<input class="lcp form-control formfield" type="text" name="lcp_email" id="lcp_email" placeholder="'+ ((!lcp.empty(res.response.customer_details)) ? res.response.customer_details.email : lcp.l('E-mail')) +'">\
				</div>\
				<div class="" id="phone_field_tbody" style="margin: 5px 0px; '; if (res.response.status == 'online') { if (lcp_primary_settings.phone_field_online == 'N') { html += 'display:none;'; } } else { if (lcp_primary_settings.phone_field_offline == 'N') { html += 'display:none;'; } } html += '">\
					<input class="lcp form-control formfield" type="text" name="lcp_phone" id="lcp_phone" placeholder="'+((!lcp.empty(res.response.customer_details)) ? res.response.customer_details.phone : lcp.l('Phone')) +'">\
				</div>\
				<div class="" id="department_field_tbody" style="margin: 5px 0px; '; if (res.response.status == 'online') { if (lcp_primary_settings.department_field_online == 'N') { html += 'display:none;'; } } else { if (lcp_primary_settings.department_field_offline == 'N') { html += 'display:none;'; } } html += '">\
					<select class="lcp formfield " name="departments" id="departments" style="width: 100% !important;">';
						if (!lcp.empty(res.response.departments)) {
							$.each(res.response.departments, function( key, value ) 
							{
								html += '<option value="'+value['id_department']+'">'+value['name']+'</option>';
							});
						}
					html += '\
					</select>\
				</div>\
				<div class="" id="question_field_tbody" style="margin: 5px 0px; '; if (res.response.status == 'online') { if (lcp_primary_settings.question_field_online == 'N') { html += 'display:none;'; } } else { if (lcp_primary_settings.question_field_offline == 'N') { html += 'display:none;'; } } html += '">\
					<textarea class="lcp form-control formfield" rows="3" name="lcp_question" id="lcp_question" placeholder="'+lcp.l('Question')+'"></textarea>\
				</div>\
				<div class="row-centered" style="margin: 5px 0px;">'; if (res.response.status == 'offline') { html += '<a href="javascript:{}" name="leave_message" id="leave_message" class="lcp chat-button" style="color: #'+lcp_primary_settings.submit_button_foreground+' !important; background-color: #'+lcp_primary_settings.submit_button_background+' !important;"><span class="fa fa-envelope-o"></span> '+lcp.l('Leave message!')+'</a>'; } else { html += '<a href="javascript:{}" name="start_chat" id="start_chat" class="lcp chat-button" style="color: #'+lcp_primary_settings.submit_button_foreground+' !important; background-color: #'+lcp_primary_settings.submit_button_background+'; !important"><span class="fa fa-comment-o"></span> '+lcp.l('Start chat!')+'</a>'; } html += '</div>\
			</div> \
			<!-- END before_chat_div -->\
			\
			<!-- BEGIN start_chat  -->\
			<div id="start_chat_div" style="'+((res.response.visitor_chat_status != 'Y' && res.response.visitor_chat_status != 'P') ? 'display:none;' : '') +' padding-left: 10px;">\
			</div> <!-- END start_chat_div -->\
			\
			<div id="chat_msg_textarea_div" style="'+((res.response.visitor_chat_status != 'Y') ? 'display:none;' : '') +' padding-left: 10px;">\
				<div class="pull-left" style="margin-right: 5px;"><input class="lcp form-control formfield" type="text" name="msg" id="msg" placeholder="'+lcp.l('press enter key to chat')+'"></div>\
				<div class="pull-left" style="margin-right: 5px;"><a href="javascript:{}" name="send_msg_a" id="send_msg_a" class="lcp chat-button" style="color: #'+lcp_primary_settings.submit_button_foreground+' !important; background-color: #'+lcp_primary_settings.submit_button_background+' !important;"><span class="icon-paper-plane-o fa fa-paper-plane-o"></span></a></div>\
				<div class="pull-left"><a href="javascript:{}" name="show_hide_emoticons" id="show_hide_emoticons" class="lcp chat-button" style="color: #'+lcp_primary_settings.submit_button_foreground+' !important; background-color: #'+lcp_primary_settings.submit_button_background+' !important;"><span class="icon-smile-o fa fa-smile-o"></span></a></div>\
				<div style="'+((lcp_primary_settings.visitors_can_upload_files == 'N') ? 'display: none;' : '') +'"><input type="file" id="send_file_upload"></div>\
			</div> \
			\
			<div id="leave_message_div" style="display:none;">\
				'+lcp.l('Your message has been sent! We will get back to you as soon as possible. Thank you!')+'\
			</div>\
			\
			<!-- BEGIN after_chat_div -->\
			<div id="after_chat_div" class="row-centered" style="display: none; margin: 0px 10px;">\
				<br><a href="javascript:{}" name="back_to_start_chat_a" id="back_to_start_chat_a" class="lcp chat-button" style="color: #'+lcp_primary_settings.submit_button_foreground+' !important; background-color: #'+lcp_primary_settings.submit_button_background+' !important;"><span class="fa fa-caret-left"></span> '+lcp.l('Back to chat again')+'</a>\
				<br><div id="signature_td">&nbsp;</div>\
				<br>';
				if (lcp_primary_settings.staff_qualification == 'Y')
				{
					html += '\
					<div>'+lcp.l('Please rate this staff member below')+'</div>\
					<div id="rating_td" class="col-lg-6 col-lg-offset-3" style="text-align: center;"></div>\
					<div id="rate_ajax_load_span"></div>\
					<div class="pull-left"><input type="text" name="rating_comment" id="rating_comment" class="lcp form-control formfield" placeholder="'+lcp.l('Comment...')+'"></div>\
					<div class="pull-right"><a href="javascript:{}" name="rate" id="rate" class="lcp chat-button" style="color: #'+lcp_primary_settings.submit_button_foreground+' !important; background-color: #'+lcp_primary_settings.submit_button_background+' !important;"><span class="fa fa-star-half-o"></span> '+lcp.l('Rate!')+'</a></div>\
					<div class="clearfix"></div>\
					<br>';
				}
				html += '\
				</div>\
				<!-- END after_chat_div -->\
				\
				</div>\
			</div>\
			<script type="text/javascript">\
				$(\'input[id^="send_file_upload"]\').uploadifive({\
					"multi"    : false,\
					"formData" : {\
						"location" : "uploads",\
					},\
					"buttonText" : "'+lcp.l("Send file")+'",\
					"uploadScript"     : lcp_path+"libraries/uploadify/uploadifive.php",\
					"onUploadComplete" : function(file, data)\
					{\
						if (data == "error1") alert("'+lcp.l("File exists, choose different filename.")+'");\
						else if (data == "error2") alert("'+lcp.l("Invalid file type.")+'");\
						else\
						{\
							var e = $.Event("keydown");\
									e.which = 13;\
									e.keyCode = 13;\
							$("#msg").val(lcp_url+"uploads/"+data).trigger(e);\
						}\
					},\
					height     : 22,\
					width      : 100\
				});\
			</script>';
	}
	else if (type == 'ajax.start_chat.tpl')
	{
		//console.log(res.response.visitor_chat_status);
		var html = '\
				<div id="lcp_chat_wrapper" style="overflow-y: scroll; height: '+((lcp.empty(lcp_primary_settings.height)) ? '350' : lcp_primary_settings.height) +'px !important; padding-right: 10px; padding-top: 5px;">\
					<div id="chat_accepted_by" style="'+((res.response.visitor_chat_status != 'Y') ? 'display:none;' : '') +'">\
						'+lcp.l('Chat accepted by')+' <b>'+((lcp_primary_settings.show_names == 'N') ? lcp.l('Staff') : res.response.visitor_online_archive_details.internal) +'</b> ('+res.response.department_name+')<br>\
						'+((!lcp.empty(res.response.visitor_online_archive_details.welcome_message)) ? res.response.visitor_online_archive_details.welcome_message+'<hr>' : '') +'\
					</div>\
					<div id="chat_denied" style="display:none;">\
						'+lcp.l('The chat has been denied! Please reload the page to try again!')+' <a href="javascript:location.reload();">'+lcp.l('Reload page')+'</a>\
					</div>\
					<div id="be_patient" style="'+((res.response.visitor_chat_status != 'Y' && res.response.visitor_chat_status != 'N') ? '' : 'display:none;') +'">\
						'+lcp.l('A representative will be connected, please be patient.')+'\
					</div>\
					<div id="lcp_content">'+((!lcp.empty(res.response.visitor_online_archive_details.messages)) ? res.response.visitor_online_archive_details.messages : '') +'</div>\
				</div>\
				\
				<div id="menu-emoticons" style="position:absolute; z-index: 9999; display:none; float:left; clear:both;" class="lcp panel">\
					<table border="0" width="100%" cellspacing="0" cellpadding="0" class="lcp emoticon-table">\
						<tr>';
						if (!lcp.empty(lcp_emoticons)) {
							$.each(lcp_emoticons, function( key, value ) 
							{
								html += '\
								<td align="center" style="text-align: center; width: 40px;">\
							   		<input type="hidden" name="emoticon_code" id="emoticon_code_'+value.id_emoticon+'" value="'+value.code+'">\
							   		<img title="'+value.code+'" border="0" src="'+lcp_path+'views/img/emoticons/'+value.filename+'" id="emoticon_img_'+value.id_emoticon+'" class="lcp emoticon-img">\
							   	</td>';
								if (key % 5 === 0) { html += '</tr><tr>'; }
							});
						}
					html += '\
						</tr>\
					</table>\
				</div>';

		html += '<script type="text/javascript">try { $("#rating_td").html( lcp.generateRatingStars("5") ); $("#lcp_chat_wrapper").scrollTop($("#lcp_chat_wrapper")[0].scrollHeight); } catch(e){} </script>';

		//setInterval(function(){ $(".blink-container").toggleClass("blink"); }, 600);

		if (res.response.visitor_chat_status == 'N')
		{
			html += '<script type="text/javascript">try { ';
			if (lcp_primary_settings.slide_with_image == 'Y')
			{
				html += '$("#before_chat_div_with_image").show(); $("#chat_header_table").hide();';
			}
			/*else
			{
				html += '$("#before_chat_div").show();';
			}*/
			html += '\
				$("#start_chat_div").hide(); \
				$("#chat_msg_textarea_div").hide(); \
			} catch(e){}</script>';
		}

		if (res.response.action == 'chatClosedFromClient' || res.response.action == 'chatClosedFromStaff')
		{
			html += '<script type="text/javascript">try { \
			lcp_id_archive = "'+res.response.visitor_archive_details.id_archive+'"; \
			$("#signature_td").html(\''+res.response.visitor_archive_details.signature+'\');';

			if (lcp_primary_settings.slide_with_image == 'Y')
			{
				html += '$("#before_chat_div_with_image").show(); $("#chat_header_table").hide();';
			}
			/*else
			{
				html += '$("#before_chat_div").show();';
			}*/

			html += '$("#after_chat_div").show(); } catch(e){} </script>';
		}

		if (res.response.action == 'chatMessageFromStaff')
			html += '<script type="text/javascript"> try { if (typeof(blink_interval_id) == "undefined") { blink_interval_id = setInterval(function(){ $(".blink-container").toggleClass("blink"); }, 600); } if (typeof(new_message) == "undefined") { lcp.playSound("newmessage"); new_message = true; } } catch(e){} </script>';
		else
			html += '<script type="text/javascript"> try { clearInterval(blink_interval_id); $(".blink-container").removeClass("blink"); delete blink_interval_id; delete new_message; } catch(e){} </script>';

	}
	else if (type == 'ajax.chats.tpl')
	{
		var html = '\
		<div class="row lcp panel-head border-bottom" id="ajax_chats_table">'+lcp.l('Chats')+'</div> \
		<div class="row"> \
			<div class="row"> \
				<div id="no_chats" style="text-align: center; display:'+ ((lcp.empty(res.response.active_pending_archives)) ? '' : 'none') +'">'+lcp.l('There are not active chats!')+'</div> \
			</div> \
			<div class="row"> \
				<div id="tabs-chat"> \
					<ul>';
					if (!lcp.empty(res.response.active_pending_archives)) {
						$.each(res.response.active_pending_archives, function( key, value ) 
						{
							html += '\
							<li><a id="tabs-chat-a-'+key+'" href="#tabs-chat-'+key+'" class="'+value['id_visitor']+'" style="cursor: hand; cursor: pointer;"><span '+ (((value['in_chat'] == 'P' || value['in_chat'] == 'Y') && value['awaiting_response_from_staff'] == 'Y') ? 'class="lcp blink-container"' : '') +'><b>'+ ((!lcp.empty(value['name'])) ? value['name'] : 'Visitor ID: '+ value['id_visitor']) +'</b></span></a> <span style="float: right; cursor: hand; cursor: pointer;" class="lcp ui-icon ui-icon-close" role="presentation" id="remove_tab_'+key+'">'+lcp.l('Remove Tab')+'</span></li>';
						});
					}
					html += '\
					</ul>';
				if (!lcp.empty(res.response.active_pending_archives)) {	
				$.each(res.response.active_pending_archives, function( key, value ) 
				{
					html += '\
					<input type="hidden" name="id_visitor_'+key+'" id="id_visitor_'+key+'" value="'+value['id_visitor']+'"> \
					<input type="hidden" name="chat_request_from_'+key+'" id="chat_request_from_'+key+'" value="client"> \
					<input type="hidden" name="in_chat_'+key+'" id="in_chat_'+key+'" value="'+value['in_chat']+'"> \
					<div id="tabs-chat-'+key+'"> \
						<span id="userchat_span_'+key+'" class="lcp tab-selected">&nbsp;<a href="javascript:{}">'+lcp.l('Chat')+'</a>&nbsp;</span>';
					//console.log(value['id_visitor'].indexOf("i"));
					if (value['id_visitor'].indexOf("i") == -1)
					{
						html += '\
						&nbsp;&nbsp;|&nbsp;&nbsp; \
						<span id="details_span_'+key+'" class="">&nbsp;<a href="javascript:{}">'+lcp.l('Details')+'</a>&nbsp;</span>&nbsp;&nbsp;|&nbsp;&nbsp; \
						<span id="visitedpageshistory_span_'+key+'" class="">&nbsp;<a href="javascript:{}">'+lcp.l('Visited pages history')+'</a>&nbsp;</span>&nbsp;&nbsp;|&nbsp;&nbsp; \
						<span id="geotracking_span_'+key+'" class="">&nbsp;<a href="javascript:{}">'+lcp.l('GeoTracking')+'</a>&nbsp;</span>&nbsp;&nbsp;|&nbsp;&nbsp; \
						<span id="archive_span_'+key+'" class="">&nbsp;<a href="javascript:{}">'+lcp.l('Archive')+'</a>&nbsp;</span>&nbsp;&nbsp;|&nbsp;&nbsp; \
						<span id="messages_span_'+key+'" class="">&nbsp;<a href="javascript:{}">'+lcp.l('Messages')+'</a>&nbsp;</span>&nbsp;&nbsp;|&nbsp;&nbsp; \
						<span id="ratings_span_'+key+'" class="">&nbsp;<a href="javascript:{}">'+lcp.l('Ratings')+'</a>&nbsp;</span>&nbsp;&nbsp;|&nbsp;&nbsp; \
						<span id="logs_span_'+key+'" class="">&nbsp;<a href="javascript:{}">'+lcp.l('Logs')+'</a>&nbsp;</span>';
					}
						html += '\
						<div id="tabs-visitor-userchat-'+key+'" style="padding:5px; background-color: white;"> \
							<div class="row"> \
								<div id="content-'+key+'" style="padding: 5px 10px; text-align: left; overflow-y: scroll; '+ ((value['in_chat'] == 'Y') ? 'height:271px;' : 'height:299px;')  +'"> \
									<div id="content_wrapper_div_'+key+'" class="col-md-12 col-lg-7">';	
									if (value['in_chat'] == 'P' && value['chat_request_from'] == 'Client')
									{
										html += '\
										<div class="row alert alert-info"> \
											<div>'+lcp.l('Chat request for:')+' <b>'+value['department_name']+'</b></div> \
											<hr> \
											<div>'+lcp.l('Name:')+' <b>'+value['name']+'</b></div> \
											<div>'+lcp.l('Email:')+' <b>'+value['email']+'</b></div> \
											<div>'+lcp.l('Phone:')+' <b>'+value['phone']+'</b></div> \
											<div>'+lcp.l('Message:')+' <b>'+value['last_message']+'</b></div> \
										</div>';
									}
									if (value['in_chat'] == 'P' || value['in_chat'] == 'D')
									{
										html += '\
										<div class="row"> \
											<div id="chat_buttons_'+key+'">';
											if (value['in_chat'] == 'P')
											{
												if (value['chat_request_from'] == 'Staff')
												{
													html += '\
													<input type="button" disabled value="'+lcp.l('Invitation sent, please wait...')+'" id="invite_to_chat_'+key+'" name="invite_to_chat_'+key+'">';
												}
												else
												{
													html += '\
													<input type="button" class="btn btn-primary" value="'+lcp.l('Accept chat!')+'" id="accept_chat_'+key+'" name="accept_chat_'+key+'"> \
													<input type="button" class="btn btn-danger" value="'+lcp.l('Deny chat!')+'" id="deny_chat_'+key+'" name="deny_chat_'+key+'" '+ ((value['chat_request_from'] == 'Staff') ? 'style="display:none"' : '') +'>';
												}
											}
											else
											{
												html += '\
												<input type="button" class="btn btn-primary" value="'+lcp.l('Invite to chat!')+'" id="invite_to_chat_'+key+'" name="invite_to_chat_'+key+'">';
											}
												html +='\
												<span id="chatactionbuttons_ajax_load_span" style="padding-left: 5px;"></span> \
											</div> \
										</div>';
									}										
										html += '\
										<div class="row"> \
											<div id="chat_messages_tr_'+key+'" '+ ((value['in_chat'] != 'Y') ? 'style="display: none;' : '') +'> \
												<div id="chat_messages_'+key+'">'+value['messages']+'</div> \
											</div> \
										</div> \
									</div> <!-- end content_wrapper_div --> \
								</div> <!-- end content --> \
							</div> <!-- end row --> \
						</div> <!-- end tabs-visitor-userchat --> \
					</div> <!-- end tabs-chat -->';
			});
			}
			html +='\
				</div> <!-- end tabs-chat --> \
			</div> <!-- end row --> \
		</div> <!-- end row -->';

		if (lcp.empty(res.response.active_pending_archives))
			html += '<script type="text/javascript">$("#ajax_chats_textarea_div, #tabs-visitor-details, #tabs-visitor-visitedpageshistory, #tabs-visitor-geotracking, #tabs-visitor-archive, #tabs-visitor-messages, #tabs-visitor-ratings, #tabs-visitor-logs").hide();</script>';

		html += '\
		<script type="text/javascript">\
			try { $(\'div[id^="content"]\').each(function(i) {$(this).scrollTop($(this).prop("scrollHeight"));}); } catch(e) {} \
			$("#tabs-chat").tabs({ active: '+lcp_active_chat_tab+' }); \
			$("#tabs-chat-a-'+lcp_active_chat_tab+'").trigger("click"); \
		</script>';
	} 
	else if (type == 'ajax.chats_slide.tpl')
	{
		//var html = '<table border="0"><tr>';
		var html = '';
			if (!lcp.empty(res.response.active_pending_archives)) {
			$.each(res.response.active_pending_archives, function( key, value ) 
			{
				html += '\
				<td style="width: 260px; padding-right: 5px; padding-bottom: 0px;" valign="bottom" id="id_chat_'+key+'">	\
				<input type="hidden" name="id_visitor_'+key+'" id="id_visitor_'+key+'" value="'+value['id_visitor']+'"> \
				<input type="hidden" name="chat_request_from_'+key+'" id="chat_request_from_'+key+'" value="client"> \
				<input type="hidden" name="in_chat_'+key+'" id="in_chat_'+key+'" value="'+value['in_chat']+'"> \
				<div class="" style="box-shadow: 0px 0px 5px 0px rgba(66,66,66,0.75); background-color: white; border-top-left-radius: 5px; border-top-right-radius: 5px;"> \
					<div class="row lcp panel2"> \
						<div id="slide_chat_head_div_'+key+'" class="row lcp border-bottom" style="color: #564F4B; background-color: #f6f7f9; height: 26px; line-height: 26px; margin: 0px; padding: 0px 5px; border-top-left-radius: 5px; border-top-right-radius: 5px;"> \
							<span '+ (((value['in_chat'] == 'P' || value['in_chat'] == 'Y') && value['awaiting_response_from_staff'] == 'Y') ? 'class="lcp blink-container"' : '') +' style="float: left;"><b> '+((!lcp.empty(value['name'])) ? value['name'] : 'Visitor ID: ' + value['id_visitor']) +'</b></span> \
							<span style="line-height: 26px; float: right; cursor: hand; cursor: pointer;" class="lcp fa fa-times"" role="presentation" id="remove_tab_'+key+'"></span> \
							<span style="line-height: 26px; padding-right: 5px; float: right; cursor: hand; cursor: pointer;" class="lcp fa fa-chevron-'+(($("#content-"+key).is(":hidden")) ? "up" : "down") +'" role="presentation" id="minimize_tab_'+key+'"></span> \
						</div> \
							<div id="content-'+key+'" style="padding: 5px 10px; text-align: left; overflow-y: scroll; height:150px; '+(($("#content-"+key).is(":hidden")) ? "display: none" : "") +'"> \
								<div id="content_wrapper_div_'+key+'" class="col-sm-12">';
								if (value['in_chat'] == 'P' && value['chat_request_from'] == 'Client')
								{
									html += '\
									<div class="row alert alert-info"> \
										<div>'+lcp.l('Chat request for:')+' <b>'+value['department_name']+'</b></div> \
										<hr> \
										<div>'+lcp.l('Name:')+' <b>'+value['name']+'</b></div> \
										<div>'+lcp.l('Email:')+' <b>'+value['email']+'</b></div> \
										<div>'+lcp.l('Phone:')+' <b>'+value['phone']+'</b></div> \
										<div>'+lcp.l('Message:')+' <b>'+value['last_message']+'</b></div> \
									</div>';
								}
								if (value['in_chat'] == 'P' || value['in_chat'] == 'D')
								{
									html += '\
									<div class="row"> \
										<div id="chat_buttons_'+key+'">';
										if (value['in_chat'] == 'P')
										{
											if (value['chat_request_from'] == 'Staff') 
											{
												html += '<input type="button" disabled value="'+lcp.l('Invitation sent, please wait...')+'" id="invite_to_chat_'+key+'" name="invite_to_chat_'+key+'">';
											}
											else
											{
												html += '\
												<input type="button" class="btn btn-primary" value="'+lcp.l('Accept chat!')+'" id="accept_chat_'+key+'" name="accept_chat_'+key+'"> \
												<input type="button" class="btn btn-danger" value="'+lcp.l('Deny chat!')+'" id="deny_chat_'+key+'" name="deny_chat_'+key+'" '+((value['chat_request_from'] == 'Staff') ? 'style="display:none"' : '') +'>';
											}
										}
										else
										{
											html += '<input type="button" class="btn btn-primary" value="'+lcp.l('Invite to chat!')+'" id="invite_to_chat_'+key+'" name="invite_to_chat_'+key+'">';
										}
											html +='<span id="chatactionbuttons_ajax_load_span_'+key+'" style="padding-left: 5px;"></span> \
										</div> \
									</div>';
								}

									html += '\
									<div class="row"> \
										<div id="chat_messages_tr_'+key+'" '+((value['in_chat'] != 'Y') ? 'style="display: none;"' : '') +'> \
											<div id="chat_messages_'+key+'">'+value['messages']+'</div> \
										</div> \
									</div> \
								</div> <!-- end content_wrapper_div --> \
							</div>  <!-- end content --> \
					</div> <!-- end row --> \
				</div>\
				</td>';

			//if ($.localStorage.get('admin_chat_toggled_'+{$key|escape:'quotes':'UTF-8'}) == 'down') { $('#minimize_tab_'+{$key|escape:'quotes':'UTF-8'}).trigger('click');}

			});
			}
			//html += '</tr></table>';

			html += '<script type="text/javascript">\
				try {\
					$(\'div[id^="content"]\').each(function(e) {\
						$(this).scrollTop($(this).prop("scrollHeight"));\
					});\
				} catch(e) {}\
			</script>';

	}
	else if (type == 'ajax.chats_slide_textarea.tpl')
	{
		html = '\
			<div class="pull-left" style=""><input type="text" name="msg_admin_'+k+'" id="msg_admin_'+k+'" value="" class="form-control fixed-width-xxl" placeholder="press enter key to chat"></div> \
			<div class="pull-left" style="padding-left: 5px; padding-right: 5px;"><a href="javascript:{}" name="send_msg_admin_a_'+k+'" id="send_msg_admin_a_'+k+'" class="lcp button"><span class="icon-paper-plane-o fa fa-paper-plane-o"></span></a></div> \
			<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="show_hide_emoticons_admin_'+k+'" id="show_hide_emoticons_admin_'+k+'" class="lcp button"><span class="icon-smile-o fa fa-smile-o"></span></a></div> \
			<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="predefined_messages_'+k+'" id="predefined_messages_'+k+'" class="lcp button" title="Predefined messages"><span class="icon-keyboard-o fa fa-keyboard-o"></span></a></div> \
			<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="transfer_visitor_'+k+'" id="transfer_visitor_'+k+'" class="lcp button" title="Transfer visitor"><span class="icon-exchange fa fa-exchange"></span></a></div>';

	}
	else if (type == 'ajax.header_chats_counter.tpl')
	{
		var html = '';

		if(!lcp.empty(res.response.online_chats))
		{
			html += res.response.online_chats;
			if (!lcp.empty(res.response.new_chats_and_messages))
			{
				if (typeof(blink_interval_id) == "undefined")  {
				    blink_interval_id = setInterval(function(){ $(".blink-container").toggleClass("blink"); }, 600);
				}
			}
			else
			{
				try {
					clearInterval(blink_interval_id);
					$(".blink-container").removeClass("blink");
					delete blink_interval_id;
				} catch(e){}	
			}
		}
		else
		{
			html += '0';
			try {
				clearInterval(blink_interval_id);
				$(".blink-container").removeClass("blink");
				delete blink_interval_id;
			} catch(e){}	
		}

		if (!lcp.empty(res.response.new_chats) && lcp_primary_settings.popup_alert_on_income_chats == 'Y')
		{
			if (typeof(new_chat_window) == "undefined") {
				lcp.popupWindow2(lcp_url+"ajax.php?type=newChatAlert", "New Chat", "250", "100");
				new_chat_window = true;
			}
		}
		else {
			delete new_chat_window;
		}

		if (!lcp.empty(res.response.new_chats) && lcp_primary_settings.new_chat_sound != 'none') {
			lcp.playSound("newchat", true); 
		}
		else
		{
			lcp.stopSound("newchat"); 

			if (!lcp.empty(res.response.new_messages) && lcp_primary_settings.new_message_sound != 'none')
			{
				if (typeof(new_message) == "undefined") 
				{
					lcp.playSound("newmessage");
					new_message = true;
				}
			}
			else {
				delete new_message;
			}
		}
	
	} 
	else if (type == 'ajax.onlineusers.tpl')
	{
	var html = '\
	<input type="hidden" name="lcp_online_users_count" id="lcp_online_users_count" value="'+res.response.count_total_online_users+'">\
	<div class="lcp panel-head border-bottom">'+lcp.l('Online Users')+' ('+res.response.count_total_online_users+') <span id="users_ajax_load_span" style="padding-left: 5px;"></span></div> \
	<div id="tabs-users"> \
		<ul> \
			<li><a href="#tabs-users-external"><span class="badge">'+res.response.count_online_external_users+'</span> '+lcp.l('External')+'</a></li> \
			<li><a href="#tabs-users-internal"><span class="badge">'+res.response.count_online_internal_users+'</span> '+lcp.l('Internal')+'</a></li> \
		</ul> \
		<div style="width: 100%; '+ ((lcp_primary_settings.chat_type_admin == 'Popup') ? 'height: 275px;' : '') +' overflow-y: scroll;" id="div_scroll_users"> \
			<div id="tabs-users-external"> \
				<table border="0" width="100%" cellspacing="0" cellpadding="0" class="table table-striped table-hover"> \
					<tbody>';
					if (!lcp.empty(res.response.online_external_users)) {
						$.each(res.response.online_external_users, function( key, value ) 
						{
							html +='\
							<input type="hidden" name="id_external_user_'+key+'" id="id_external_user_'+key+'" value="'+value['id_user']+'"> \
							<input type="hidden" name="id_external_visitor_'+key+'" id="id_external_visitor_'+key+'" value="'+value['id_visitor']+'"> \
							<tr> \
								<td class="lcp" id="online_external_users_td_'+key+'" style="text-align: left;">'+ ((!lcp.empty(value['name'])) ? value['name'] : 'Visitor ID: ' + value['id_visitor']) +'</td> \
								<td class="lcp online_users_hover_td" style="cursor: hand; cursor: pointer; padding-left: 5px; padding-right: 5px;" align="right"></td> \
							</tr>';
						});
					}
					html +='\
					</tbody> \
				</table> \
			</div> \
			<div id="tabs-users-internal"> \
				<table border="0" width="100%" cellspacing="0" cellpadding="0" class="table table-striped table-hover"> \
					<tbody>';
					if (!lcp.empty(res.response.online_internal_users)) {
						$.each(res.response.online_internal_users, function( key, value ) 
						{
							html += '\
							<input type="hidden" name="id_internal_user_'+key+'" id="id_internal_user_'+key+'" value="'+value['id_user']+'"> \
							<input type="hidden" name="id_internal_staffprofile_'+key+'" id="id_internal_staffprofile_'+key+'" value="'+value['id_staffprofile']+'"> \
							<tr> \
								<td class="lcp '+ ((!lcp.empty(value['count_pending_archives'])) ? 'blink' : '') +'" id="online_internal_users_td_'+key+'" style="text-align: left;">'+value['firstname']+' '+value['lastname']+' ('+value['count_online_archives']+') '+((lcp_id_staffprofile == value['id_staffprofile']) ? '<span style="color: #999999 !important;"> (Me)</span>' : '') +' '+((!lcp.empty(value['count_pending_archives'])) ? '<span style="font-size: xx-small" class="lcp blink">ringing...</span>' : '') +'</td> \
								<td class="lcp online_users_hover_td" style="cursor: hand; cursor: pointer; padding-left: 5px; padding-right: 5px; '+((lcp_id_staffprofile == value['id_staffprofile']) ? 'color: #cccccc !important;' : '') +'" align="right"></td> \
							</tr>';
						});
					}
					html +='\
					</tbody> \
				</table> \
			</div> \
		</div> \
	</div> \
	<script type="text/javascript">$( "#tabs-users" ).tabs({ active: '+lcp_active_users_tab+' });</script>';

	if (parseInt($(window).width()) <= 768)
		html += '<script type="text/javascript">$(\'div[id^="div_scroll_users"]\').css({"height": "50px"});</script>';
	}


	return html;
};

Livechatpro.prototype.getUrlParameter = function(sParam)
{
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++)
	{
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam)
		{
			return sParameterName[1];
		}
	}
};

Livechatpro.prototype.syncChatDialog = function(preloader)
{
	preloader = typeof(preloader) != 'undefined' ? preloader : null;

	var data = {
		'action': 'syncChatDialog',
	};
	if (preloader != null)
	{
		var params = {
			'load': 'syncChatDialog',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: preloader,
				},
				'type': 2,
				'style': 3,
			}
		};
	}
	else
	{
		var params = {
			'load': 'syncChatDialog',
			'divs': null,
			'params':
			{
				'data': data,
			},
		};
	}

	this.ajaxController(params, function(result)
	{
		var res = JSON.parse(result);

		lcp_active_chat_tab = ($.localStorage.isEmpty('lcp_active_chat_tab')) ? "0" : $.localStorage.get('lcp_active_chat_tab'); 
		lcp_active_chat_subtab = ($.localStorage.isEmpty('lcp_active_chat_subtab')) ? "userchat_span_0" : $.localStorage.get('lcp_active_chat_subtab');
		lcp_active_users_tab = ($.localStorage.isEmpty('lcp_active_users_tab')) ? "0" : $.localStorage.get('lcp_active_users_tab');
		lcp_active_statistics_tab = ($.localStorage.isEmpty('lcp_active_statistics_tab')) ? "0" : $.localStorage.get('lcp_active_statistics_tab');
		lcp_active_statistics_tab_link = ($.localStorage.isEmpty('lcp_active_statistics_tab_link')) ? "tabs-visitors-a" : $.localStorage.get('lcp_active_statistics_tab_link');
		//console.log('lcp_active_chat_tab:'+lcp_active_chat_tab);
		//console.log('lcp_active_chat_subtab:'+lcp_active_chat_subtab);
		//console.log('lcp_active_users_tab:'+lcp_active_users_tab);
		//console.log('lcp_active_statistics_tab:'+lcp_active_statistics_tab);
		//console.log('lcp_active_statistics_tab_link:'+lcp_active_statistics_tab_link);
		//console.log(res);
		//console.log(lcp_primary_settings);
	
		$('#awaiting_response_chat_dialogs').html(lcp.getHtmlCode('ajax.header_chats_counter.tpl', res));

		if (lcp_primary_settings.chat_type_admin == 'Popup')
			$("#ajax_chats_div").html(lcp.getHtmlCode('ajax.chats.tpl', res));
		else
		{
			$("#ajax_chats_div").html(lcp.getHtmlCode('ajax.chats_slide.tpl', res));

			if (!lcp.empty(res.response.active_pending_archives)) 
			{
				$.each(res.response.active_pending_archives, function( key, value ) 
				{
					// daca nu exista divul
					if ($('#ajax_chats_textarea_div_'+key).length == 0)
					{
						$('#ajax_chats_textarea_div').append('<td id="ajax_chats_textarea_div_'+key+'" style="padding-top: 0px; padding-right: 5px;"><div style="background-color: white; height: 60px; box-shadow: 0px 5px 5px 0px rgba(66,66,66,0.75); padding: 2px;">'+lcp.getHtmlCode('ajax.chats_slide_textarea.tpl', res, key)+'</div></td>');
					}
					else
					{
						if ($('#ajax_chats_textarea_div_'+key).html() == '')
							$('#ajax_chats_textarea_div_'+key).html(lcp.getHtmlCode('ajax.chats_slide_textarea.tpl', res, key));
					}

				});
			}
			else
			{
				$('#ajax_chats_textarea_div').html('');
			}
		}

		// AJAX.ONLINEUSERS.TPL
		// -----------------------------------------------------------------------------------------------
		//console.log($("#lcp_online_users_count").val());
		if ($("#lcp_online_users_count").val() != res.response.count_total_online_users) 
			$("#ajax_onlineusers_div").html(lcp.getHtmlCode('ajax.onlineusers.tpl', res));

	});

};



Livechatpro.prototype.syncFrontChatDialog = function()
{
		var data = {
			'action': 'syncFrontChatDialog',
			'id_visitor': lcp_id_visitor,
			'id_customer': lcp_id_customer,
		};
		var params = {
			'load': 'syncFrontChatDialog',
			'divs': null,
			'params':
			{
				'data': data,
			},

		};
		this.ajaxController(params, function(result)
		{
			var res = JSON.parse(result);
			lcp_chat_status = res.response.status;
			lcp_visitor_name = res.response.visitor_online_archive_details.name;

			if ($("#lcp_chat_status").val() != lcp_chat_status) 
				$('#chat_div').html(lcp.getHtmlCode('ajax.chat.tpl', res));

			if (res.response.chat_type == 'Popup')
				$('#chat_inner_table').css('display', 'block');

			if ($('#start_chat_div').is(":visible"))
					$("#start_chat_div").html(lcp.getHtmlCode('ajax.start_chat.tpl', res));

			if (res.response.visitor_chat_status == 'Y')
				$("#chat_msg_textarea_div").show();

			// show chat invitation dialog
			if (res.response['is_chat_invitation'] == true && $('#dialog_chat_invitation').is(":hidden"))
			{
				$('#invitation_avatar_img').attr('src', lcp_path+'views/img/avatars/'+res.response.visitor_archive_details.avatar);
				$("#dialog_chat_invitation").dialog("open");
			}

		});
};


Livechatpro.prototype.popupWindow = function(url, title, w, h)
{
	var left = (screen.width / 2) - (w / 2);
	var top = (screen.height / 2) - (h / 2);
	return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
};


Livechatpro.prototype.addToolbarBtn = function(type)
{
	if (type == 'video') $('div.btn-toolbar > ul').prepend('<li><a id="desc-module-video" class="lcp toolbar_btn" title="Module Video Tutorial" href="' + lcp_video_link + '" target="_blank"><i style="margin-top: 2px !important;" class="fa fa-youtube fa-2x"></i><div style="margin-top: 4px !important;">Module Video</div></a></li>');
	if (type == 'documentation') $('div.btn-toolbar > ul').prepend('<li><a id="desc-module-documentation" class="lcp toolbar_btn" title="Module Documentation" href="' + lcp_doc_link + '" target="_blank"><i style="margin-top: 2px !important;" class="fa fa-life-ring fa-2x"></i><div style="margin-top: 4px !important;">Documentation</div></a></li>');
	if (type == 'contact') $('div.btn-toolbar > ul').prepend('<li><a id="desc-module-contact" class="lcp toolbar_btn" title="Contact Developer" href="' + lcp_support_link + '" target="_blank"><i style="margin-top: 2px !important;" class="fa fa-code fa-2x"></i><div style="margin-top: 4px !important;">Contact Dev. (suggestions)</div></a></li>');
	if (type == 'modules') $('div.btn-toolbar > ul').prepend('<li><a id="desc-module-modules" class="lcp toolbar_btn" title="Modules by this developer" href="' + lcp_dev_modules_link + '" target="_blank"><i style="margin-top: 2px !important;" class="fa fa-puzzle-piece fa-2x"></i><div style="margin-top: 4px !important;">Dev. Modules</div></a></li>');
	if (type == 'separator') $('div.btn-toolbar > ul').prepend('<li style="line-height: 40px; vertical-align: middle; color: #222222 !important; opacity: 0.3; filter: alpha(opacity=30);">|</li>');
};

Livechatpro.prototype.showVisitorDetails = function(id_visitor, dialog)
{
	dialog = typeof(dialog) != 'undefined' ? dialog : null;
	is_dialog = (dialog != null) ? 'y' : 'n';
	var data = {
		'id_visitor': id_visitor,
		'key': is_dialog + lcp_active_chat_tab,
	};
	var params = {
		'load': 'showVisitorDetails',
		'divs': null,
		'params':
		{
			'data': data,
		},
		'preloader':
		{
			'divs':
			{
				0: 'statistics_ajax_load_span',
			},
			'type': 2,
			'style': 3,
		}
	};
	this.ajaxController(params, function(result)
	{
		var res = JSON.parse(result);
		if (dialog != null)
			$("#tabs-visitordetails-details").html(res.response['ajax.visitor_details.tpl']);
		else
			$("#tabs-visitor-details").html(res.response['ajax.visitor_details.tpl']);

	});
};

Livechatpro.prototype.showVisitorVisitedPagesHistory = function(id_visitor, dialog)
{
	dialog = typeof(dialog) != 'undefined' ? dialog : null;
	is_dialog = (dialog != null) ? 'y' : 'n';
	var data = {
		'id_visitor': id_visitor,
		'key': is_dialog + lcp_active_chat_tab,
	};
	var params = {
		'load': 'showVisitorVisitedPagesHistory',
		'divs': null,
		'params':
		{
			'data': data,
		},
		'preloader':
		{
			'divs':
			{
				0: 'statistics_ajax_load_span',
			},
			'type': 2,
			'style': 3,
		}
	};
	this.ajaxController(params, function(result)
	{
		var res = JSON.parse(result);

		if (dialog != null)
			$("#tabs-visitordetails-visitedpageshistory").html(res.response['ajax.visitor_visited_pages_history.tpl']);
		else
			$("#tabs-visitor-visitedpageshistory").html(res.response['ajax.visitor_visited_pages_history.tpl']);

	});
};

Livechatpro.prototype.showVisitorGeoTracking = function(id_visitor, dialog)
{
	dialog = typeof(dialog) != 'undefined' ? dialog : null;
	is_dialog = (dialog != null) ? 'y' : 'n';
	var data = {
		'id_visitor': id_visitor,
		'key': is_dialog + lcp_active_chat_tab,
	};
	var params = {
		'load': 'showVisitorGeoTracking',
		'divs': null,
		'params':
		{
			'data': data,
		},
		'preloader':
		{
			'divs':
			{
				0: 'statistics_ajax_load_span',
			},
			'type': 2,
			'style': 3,
		}
	};
	this.ajaxController(params, function(result)
	{
		var res = JSON.parse(result);

		if (dialog != null)
			$("#tabs-visitordetails-geotracking").html(res.response['ajax.visitor_geotracking.tpl']);
		else
			$("#tabs-visitor-geotracking").html(res.response['ajax.visitor_geotracking.tpl']);
	});
};


Livechatpro.prototype.showVisitorArchive = function(id_visitor)
{
	var self = this,
		l = this.l;

	if (!$.fn.DataTable.isDataTable('#visitor_archive_grid'))
	{
		var archive_table = lcp_db_prefix + lcp_module_name + '_archive';
		var departments_table = lcp_db_prefix + lcp_module_name + '_departments';
		var visitor_archive_editor = new $.fn.dataTable.Editor(
		{
			//ajax: lcp_grid_path + 'visitorarchive',
			ajax:
			{
				url: lcp_grid_path + 'visitorarchive',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			table: "#visitor_archive_grid",
			fields: [
			{
				label: l("Name:"),
				name: archive_table + ".name",
			}, ],
			i18n: {
            create: {
                button: l("New"),
                title:  l("Create new entry"),
                submit: l("Create")
            },
            edit: {
                button: l("Edit"),
                title:  l("Edit entry"),
                submit: l("Update")
            },
            remove: {
                button: l("Delete"),
                title:  l("Delete"),
                submit: l("Delete"),
                confirm: {
                    _: l("Are you sure you want to delete")+" %d "+l("entries?"),
                    1: l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: l('Previous'),
                next:     l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
		});
		var visitor_archive_editor_view_button = {
			extend: 'selectedSingle',
			//sButtonClass: 'marginLeft',
			text: l('View details'),
			action: function(e, dt, node, config)
			{
				if (visitor_archive_datatable.api().row('.selected').length !== 0)
				{
					var id_archive = visitor_archive_datatable.api().row('.selected').data()[archive_table].id_archive;
					var data = {
						'id_archive': id_archive,
						'id_visitor': id_visitor,
					};
					var params = {
						'load': 'getArchive',
						'divs': null,
						'params':
						{
							'data': data,
						},
						'preloader':
						{
							'divs':
							{
								0: 'statistics_ajax_load_span',
							},
							'type': 2,
							'style': 3,
						}
					};
					self.ajaxController(params, function(result)
					{
						var res = JSON.parse(result);
						/*console.log(res); return;*/
						$("#archive_message").html(res.messages);
						$("#dialog-form-archive").dialog("open");
					});
				}
			}
		};
		var visitor_archive_editor_remove_button = {
			extend: "remove",
			editor: visitor_archive_editor
		};
		var visitor_archive_editor_buttons = [];
		if (lcp_employee_is_superadmin == 'Y')
		{
			visitor_archive_editor_buttons.push(visitor_archive_editor_view_button);
			visitor_archive_editor_buttons.push(visitor_archive_editor_remove_button);
		}
		else
		{
			visitor_archive_editor_buttons.push(visitor_archive_editor_view_button);
		}
		visitor_archive_datatable = $('#visitor_archive_grid').dataTable(
		{
			dom: "Bfrtip",
			//"processing": true,
			//"serverSide": true,
			"scrollX": true,
			"pageLength": 5,
			"autoWidth": false,
			"language": {
                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
            },
			ajax:
			{
				url: lcp_grid_path + 'visitorarchive',
				type: 'POST',
				"data":
				{
					"id_visitor": id_visitor,
					"session": lcp_session
				}
			},
			columns: [
			{
				data: archive_table + ".date_add",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".id_chat",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".name",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".internal",
				className: "dt-body-center"
			},
			{
				data: departments_table + ".name",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".email",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".phone",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".company",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".language",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".country",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".ip",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".host",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".duration",
				className: "dt-body-center"
			},
			{
				data: archive_table + ".log_entries",
				className: "dt-body-center"
			}, ],
			select: true,
			buttons: visitor_archive_editor_buttons,
		});
	}
};


Livechatpro.prototype.showVisitorMessages = function(id_visitor)
{
	var self = this,
		l = this.l;

	if (!$.fn.DataTable.isDataTable('#visitor_messages_grid'))
	{
		var messages_table = lcp_db_prefix + lcp_module_name + '_messages';
		var visitor_messages_editor = new $.fn.dataTable.Editor(
		{
			//ajax: lcp_grid_path + 'visitormessages',
			ajax:
			{
				url: lcp_grid_path + 'visitormessages',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			table: "#visitor_messages_grid",
			fields: [
			{
				label: l("Name:"),
				name: messages_table + ".name",
			}, ],
			i18n: {
            create: {
                button: l("New"),
                title:  l("Create new entry"),
                submit: l("Create")
            },
            edit: {
                button: l("Edit"),
                title:  l("Edit entry"),
                submit: l("Update")
            },
            remove: {
                button: l("Delete"),
                title:  l("Delete"),
                submit: l("Delete"),
                confirm: {
                    _: l("Are you sure you want to delete")+" %d "+l("entries?"),
                    1: l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: l('Previous'),
                next:     l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
		});
		var visitor_messages_editor_remove_button = {
			extend: "remove",
			editor: visitor_messages_editor
		};
		var visitor_messages_editor_reply_button = {
			extend: 'selectedSingle',
			//sButtonClass: 'marginLeft',
			text: l('Reply'),
			action: function(e, dt, node, config)
			{
				if (visitor_messages_datatable.api().row('.selected').length !== 0)
				{
					var id_message = visitor_messages_datatable.api().row('.selected').data()[messages_table].id_message;
					var data = {
						'id_message': id_message,
						'id_visitor': id_visitor,
					};
					var params = {
						'load': 'replyToMessage',
						'divs': null,
						'params':
						{
							'data': data,
						},
						'preloader':
						{
							'divs':
							{
								0: 'statistics_ajax_load_span',
							},
							'type': 2,
							'style': 3,
						}
					};
					self.ajaxController(params, function(result)
					{
						var res = JSON.parse(result);
						lcp_messages_id = res.id_message;
						$("#messages_date").html(res.date_add);
						$("#messages_department").html(res.department_name);
						$("#messages_sender_name").html(res.name);
						$("#messages_sender_email").html(res.email);
						$("#messages_sender_phone").html(res.phone);
						$("#messages_sender_company").html(res.company);
						$("#messages_message").html(res.question);
						$("#messages_reply_tr").show();
						$("#dialog-form-messages").dialog("open");
					});
				}
			}
		};
		var visitor_messages_editor_view_button = {
			extend: 'selectedSingle',
			//sButtonClass: 'marginLeft',
			text: l('View details'),
			action: function(e, dt, node, config)
			{
				if (visitor_messages_datatable.api().row('.selected').length !== 0)
				{
					var id_message = visitor_messages_datatable.api().row('.selected').data()[messages_table].id_message;
					var data = {
						'id_message': id_message,
						'id_visitor': id_visitor,
					};
					var params = {
						'load': 'getMessage',
						'divs': null,
						'params':
						{
							'data': data,
						},
						'preloader':
						{
							'divs':
							{
								0: 'statistics_ajax_load_span',
							},
							'type': 2,
							'style': 3,
						}
					};
					self.ajaxController(params, function(result)
					{
						var res = JSON.parse(result);
						/*console.log(res); return;*/
						lcp_messages_id = res.id_message;
						$("#messages_date").html(res.date_add);
						$("#messages_department").html(res.department_name);
						$("#messages_sender_name").html(res.name);
						$("#messages_sender_email").html(res.email);
						$("#messages_sender_phone").html(res.phone);
						$("#messages_sender_company").html(res.company);
						$("#messages_message").html(res.question);
						$("#dialog-form-messages").dialog("open");
					});
				}
			}
		};
		var visitor_messages_editor_mark_as_read_button = {
			extend: 'selectedSingle',
			//sButtonClass: 'marginLeft',
			text: l('Mark as read'),
			action: function(e, dt, node, config)
			{
				if (visitor_messages_datatable.api().row('.selected').length !== 0)
				{
					var id_message = visitor_messages_datatable.api().row('.selected').data()[messages_table].id_message;
					//console.log(messages_datatable.api().row('.selected').data()); 
					var data = {
						'id_message': id_message,
						'status': 'Read',
						'id_visitor': id_visitor,
					};
					var params = {
						'load': 'updateMessage',
						'divs': null,
						'params':
						{
							'data': data,
						},
						'preloader':
						{
							'divs':
							{
								0: 'statistics_ajax_load_span',
							},
							'type': 2,
							'style': 3,
						}
					};
					self.ajaxController(params, function(result)
					{
						visitor_messages_datatable.api().ajax.reload();
					});
				}
			}
		};
		var visitor_messages_editor_mark_as_unread_button = {
			extend: 'selectedSingle',
			//sButtonClass: 'marginLeft',
			text: l('Mark as unread'),
			action: function(e, dt, node, config)
			{
				if (visitor_messages_datatable.api().row('.selected').length !== 0)
				{
					var id_message = visitor_messages_datatable.api().row('.selected').data()[messages_table].id_message;
					var data = {
						'id_message': id_message,
						'status': 'Unread',
						'id_visitor': id_visitor,
					};
					var params = {
						'load': 'updateMessage',
						'divs': null,
						'params':
						{
							'data': data,
						},
						'preloader':
						{
							'divs':
							{
								0: 'statistics_ajax_load_span',
							},
							'type': 2,
							'style': 3,
						}
					};
					self.ajaxController(params, function(result)
					{
						visitor_messages_datatable.api().ajax.reload();
					});
				}
			}
		};
		var visitor_messages_editor_buttons = [];
		if (lcp_employee_is_superadmin == 'Y')
		{
			visitor_messages_editor_buttons.push(visitor_messages_editor_remove_button);
			visitor_messages_editor_buttons.push(visitor_messages_editor_view_button);
			//visitor_messages_editor_buttons.push(visitor_messages_editor_reply_button);
			visitor_messages_editor_buttons.push(visitor_messages_editor_mark_as_read_button);
			visitor_messages_editor_buttons.push(visitor_messages_editor_mark_as_unread_button);
		}
		else
		{
			visitor_messages_editor_buttons.push(visitor_messages_editor_view_button);
			//visitor_messages_editor_buttons.push(visitor_messages_editor_reply_button);
			visitor_messages_editor_buttons.push(visitor_messages_editor_mark_as_read_button);
			visitor_messages_editor_buttons.push(visitor_messages_editor_mark_as_unread_button);
		}
		visitor_messages_datatable = $('#visitor_messages_grid').dataTable(
		{
			dom: "Bfrtip",
			//"processing": true,
			//"serverSide": true,
			"scrollX": true,
			"pageLength": 5,
			"autoWidth": false,
			"language": {
                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
            },
			ajax:
			{
				url: lcp_grid_path + 'visitormessages',
				type: 'POST',
				"data":
				{
					"id_visitor": id_visitor,
					"session": lcp_session
				}
			},
			columns: [
			{
				data: messages_table + ".date_add",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: messages_table + ".name",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: messages_table + ".email",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: messages_table + ".phone",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: messages_table + ".department",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: messages_table + ".question",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data.substring(0, 20) + ' [...]</span>';
					else return data.substring(0, 20) + ' [...]';
				}
			},
			{
				data: messages_table + ".ip",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: messages_table + ".status",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + lcp.l(data) + '</span>';
					else return lcp.l(data);
				}
			}, ],
			select: true,
			buttons: visitor_messages_editor_buttons,
		});
	}
};

Livechatpro.prototype.showVisitorRatings = function(id_visitor)
{
	var self = this,
		l = this.l;

	if (!$.fn.DataTable.isDataTable('#visitor_ratings_grid'))
	{
		var ratings_table = lcp_db_prefix + lcp_module_name + '_ratings';
		var visitor_ratings_editor = new $.fn.dataTable.Editor(
		{
			//ajax: lcp_grid_path + 'visitorratings',
			ajax:
			{
				url: lcp_grid_path + 'visitorratings',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			table: "#visitor_ratings_grid",
			fields: [
			{
				label: l("Name:"),
				name: ratings_table + ".name",
			}, ],
			i18n: {
            create: {
                button: l("New"),
                title:  l("Create new entry"),
                submit: l("Create")
            },
            edit: {
                button: l("Edit"),
                title:  l("Edit entry"),
                submit: l("Update")
            },
            remove: {
                button: l("Delete"),
                title:  l("Delete"),
                submit: l("Delete"),
                confirm: {
                    _: l("Are you sure you want to delete")+" %d "+l("entries?"),
                    1: l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: l('Previous'),
                next:     l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
		});
		var visitor_ratings_editor_remove_button = {
			extend: "remove",
			editor: visitor_ratings_editor
		};
		var visitor_ratings_editor_view_button = {
			extend: 'selectedSingle',
			//sButtonClass: 'marginLeft',
			text: l('View details'),
			action: function(e, dt, node, config)
			{
				if (visitor_ratings_datatable.api().row('.selected').length !== 0)
				{
					var id_rating = visitor_ratings_datatable.api().row('.selected').data()[ratings_table].id_rating;
					var data = {
						'id_rating': id_rating,
						'id_visitor': id_visitor,
					};
					var params = {
						'load': 'getRating',
						'divs': null,
						'params':
						{
							'data': data,
						},
						'preloader':
						{
							'divs':
							{
								0: 'statistics_ajax_load_span',
							},
							'type': 2,
							'style': 3,
						}
					};
					self.ajaxController(params, function(result)
					{
						var res = JSON.parse(result);
						/*console.log(res); return;*/
						$("#ratings_politness").html(self.generateRatingStars(res.politness));
						$("#ratings_qualification").html(self.generateRatingStars(res.qualification));
						$("#ratings_date").html(res.date_add);
						$("#ratings_internal").html(res.internal);
						$("#ratings_sender_name").html(res.name);
						$("#ratings_sender_email").html(res.email);
						$("#ratings_sender_phone").html(res.phone);
						$("#ratings_sender_company").html(res.company);
						$("#ratings_comment").html(res.comment);
						$("#dialog-form-ratings").dialog("open");
					});
				}
			}
		};
		var visitor_ratings_editor_mark_as_read_button = {
			extend: 'selectedSingle',
			//sButtonClass: 'marginLeft',
			text: l('Mark as read'),
			action: function(e, dt, node, config)
			{
				if (visitor_ratings_datatable.api().row('.selected').length !== 0)
				{
					var id_rating = visitor_ratings_datatable.api().row('.selected').data()[ratings_table].id_rating;
					var data = {
						'id_rating': id_rating,
						'status': 'Read',
						'id_visitor': id_visitor,
					};
					var params = {
						'load': 'updateRating',
						'divs': null,
						'params':
						{
							'data': data,
						},
						'preloader':
						{
							'divs':
							{
								0: 'statistics_ajax_load_span',
							},
							'type': 2,
							'style': 3,
						}
					};
					self.ajaxController(params, function(result)
					{
						visitor_ratings_datatable.api().ajax.reload();
					});
				}
			}
		};
		var visitor_ratings_editor_mark_as_unread_button = {
			extend: 'selectedSingle',
			//sButtonClass: 'marginLeft',
			text: l('Mark as unread'),
			action: function(e, dt, node, config)
			{
				if (visitor_ratings_datatable.api().row('.selected').length !== 0)
				{
					var id_rating = visitor_ratings_datatable.api().row('.selected').data()[ratings_table].id_rating;
					var data = {
						'id_rating': id_rating,
						'status': 'Unread',
						'id_visitor': id_visitor,
					};
					var params = {
						'load': 'updateRating',
						'divs': null,
						'params':
						{
							'data': data,
						},
						'preloader':
						{
							'divs':
							{
								0: 'statistics_ajax_load_span',
							},
							'type': 2,
							'style': 3,
						}
					};
					self.ajaxController(params, function(result)
					{
						visitor_ratings_datatable.api().ajax.reload();
					});
				}
			}
		};
		var visitor_ratings_editor_view_matching_chat_button = {
			extend: 'selectedSingle',
			//sButtonClass: 'marginLeft',
			text: l('View matching chat'),
			action: function(e, dt, node, config)
			{
				if (visitor_ratings_datatable.api().row('.selected').length !== 0)
				{
					var id_rating = visitor_ratings_datatable.api().row('.selected').data()[ratings_table].id_rating;
					var data = {
						'id_rating': id_rating,
						'id_visitor': id_visitor,
					};
					var params = {
						'load': 'getRating',
						'divs': null,
						'params':
						{
							'data': data,
						},
						'preloader':
						{
							'divs':
							{
								0: 'statistics_ajax_load_span',
							},
							'type': 2,
							'style': 3,
						}
					};
					self.ajaxController(params, function(result)
					{
						var res = JSON.parse(result);
						var id_archive = res.id_archive;
						var data2 = {
							'id_archive': id_archive,
						};
						var params2 = {
							'load': 'getArchive',
							'divs': null,
							'params':
							{
								'data': data2,
							},
							'preloader':
							{
								'divs':
								{
									0: 'statistics_ajax_load_span',
								},
								'type': 2,
								'style': 3,
							}
						};
						self.ajaxController(params2, function(result2)
						{
							var res2 = JSON.parse(result2);
							$("#archive_message").html(res2.messages);
							$("#dialog-form-archive").dialog("open");
						});
					});
				}
			}
		};
		var visitor_ratings_editor_buttons = [];
		if (lcp_employee_is_superadmin == 'Y')
		{
			visitor_ratings_editor_buttons.push(visitor_ratings_editor_remove_button);
			visitor_ratings_editor_buttons.push(visitor_ratings_editor_view_button);
			visitor_ratings_editor_buttons.push(visitor_ratings_editor_mark_as_read_button);
			visitor_ratings_editor_buttons.push(visitor_ratings_editor_mark_as_unread_button);
			visitor_ratings_editor_buttons.push(visitor_ratings_editor_view_matching_chat_button);
		}
		else
		{
			visitor_ratings_editor_buttons.push(visitor_ratings_editor_view_button);
			visitor_ratings_editor_buttons.push(visitor_ratings_editor_mark_as_read_button);
			visitor_ratings_editor_buttons.push(visitor_ratings_editor_mark_as_unread_button);
			visitor_ratings_editor_buttons.push(visitor_ratings_editor_view_matching_chat_button);
		}
		visitor_ratings_datatable = $('#visitor_ratings_grid').dataTable(
		{
			dom: "Bfrtip",
			//"processing": true,
			//"serverSide": true,
			"scrollX": true,
			"pageLength": 5,
			"autoWidth": false,
			"language": {
                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
            },
			ajax:
			{
				url: lcp_grid_path + 'visitorratings',
				type: 'POST',
				"data":
				{
					"id_visitor": id_visitor,
					"session": lcp_session
				}
			},
			columns: [
			{
				data: ratings_table + ".politness",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					return self.generateRatingStars(data);
				}
			},
			{
				data: ratings_table + ".qualification",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					return self.generateRatingStars(data);
				}
			},
			{
				data: ratings_table + ".date_add",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: ratings_table + ".internal",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: ratings_table + ".name",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: ratings_table + ".email",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: ratings_table + ".company",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
					else return data;
				}
			},
			{
				data: ratings_table + ".comment",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data.substring(0, 20) + ' [...]</span>';
					else return data.substring(0, 20) + ' [...]';
				}
			},
			{
				data: ratings_table + ".status",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + lcp.l(data) + '</span>';
					else return lcp.l(data);
				}
			}, ],
			select: true,
			buttons: visitor_ratings_editor_buttons,
		});
	}

};


Livechatpro.prototype.showVisitorLogs = function(id_visitor)
{
	var self = this,
		l = this.l;
		
	if (!$.fn.DataTable.isDataTable('#visitor_logs_grid'))
	{
		var logs_table = lcp_db_prefix + lcp_module_name + '_logs';
		var visitor_logs_editor = new $.fn.dataTable.Editor(
		{
			//ajax: lcp_grid_path + 'visitorlogs',
			ajax:
			{
				url: lcp_grid_path + 'visitorlogs',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			table: "#visitor_logs_grid",
			fields: [
			{
				label: l("Name:"),
				name: logs_table + ".name",
			}, ],
			i18n: {
            create: {
                button: l("New"),
                title:  l("Create new entry"),
                submit: l("Create")
            },
            edit: {
                button: l("Edit"),
                title:  l("Edit entry"),
                submit: l("Update")
            },
            remove: {
                button: l("Delete"),
                title:  l("Delete"),
                submit: l("Delete"),
                confirm: {
                    _: l("Are you sure you want to delete")+" %d "+l("entries?"),
                    1: l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: l('Previous'),
                next:     l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
		});
		var visitor_logs_editor_create_button = {};
		var visitor_logs_editor_edit_button = {};
		var visitor_logs_editor_remove_button = {
			extend: "remove",
			editor: visitor_logs_editor
		};
		var visitor_logs_editor_buttons = [];
		if (lcp_employee_is_superadmin == 'Y')
		{
			visitor_logs_editor_buttons.push(visitor_logs_editor_remove_button);
		}
		else
		{
			visitor_logs_editor_buttons = [];
		}
		visitor_logs_datatable = $('#visitor_logs_grid').dataTable(
		{
			dom: "Bfrtip",
			//"processing": true,
			//"serverSide": true,
			"scrollX": true,
			"pageLength": 5,
			"autoWidth": false,
			"language": {
                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
            },
			ajax:
			{
				url: lcp_grid_path + 'visitorlogs',
				type: 'POST',
				"data":
				{
					"id_visitor": id_visitor,
					"session": lcp_session
				}
			},
			columns: [
			{
				data: logs_table + ".date_add",
				className: "dt-body-center"
			},
			{
				data: logs_table + ".message"
			}, ],
			select: true,
			buttons: visitor_logs_editor_buttons,
		});
	}

};



Livechatpro.prototype.checkModuleConflict = function()
{
	if (this.getUrlParameter('configure') == 'pm_abfighting' || this.getUrlParameter('configure') == 'pm_advancedbackgroundchanger' || this.getUrlParameter('configure') == 'pm_advancedsearch4' || this.getUrlParameter('configure') == 'pm_advancedtrackingwizard' || this.getUrlParameter('configure') == 'pm_cachemanager' || this.getUrlParameter('configure') == 'pm_seointernallinking' || this.getUrlParameter('configure') == 'pm_termsoninvoice') return true;
	else return false;
};

Livechatpro.prototype.generateChatWidgetFront = function()
{

	var data = {
		'ps_version': '1.6',
	};

	var params = {
		'load': 'generateChatWidgetFront',
		'divs': null,
		'params':
		{
			'data': data,
		},
	};

	this.ajaxController(params, function(result)
	{
		$('.footer-container, #footer-container, .footer_container, #footer_container, #footer').append('<div id="footer_livechatpro">'+result+'</div>');
	});


};

Livechatpro.prototype.generateChatWidgetAdmin = function()
{
	//remove all local storage chat control
	//$.localStorage.remove('admin_chat_control');

	var data = {
		'ps_version': '1.6',
	};

	var params = {
		'load': 'generateChatWidgetAdmin',
		'divs': null,
		'params':
		{
			'data': data,
		},
	};
	
	this.ajaxController(params, function(result)
	{
		//$('#header_quick').after(result);
		$('#header_quick').after('<ul id="header_livechatpro" style="float: left !important; list-style-type: none; margin: 0; padding: 0;"><li id="header_livechatpro_li">'+result+'</li></ul>');
		
		// init the dialogf
		$("#dialog_chat").dialog(
		{
			autoOpen: false,
			width: parseInt($(window).width()) - 20,
			height: parseInt($(window).height()) - 50,
			position: ['top', 40],
			modal: false,
		});

		// init the tabs
		$("#tabs-users").tabs();
		$("#tabs-chat").tabs();
		$("#tabs-statistics").tabs();
		$("#tabs-visitordetails").tabs();	
		
	});

};



Livechatpro.prototype.generateRatingStars = function(stars)
{
	var html = '';
	var full_stars = Math.floor(stars);
	/*full stars*/
	for (i = 1; i <= full_stars; i++)
	{
		html += '<img class="lcp star" border="0" src="' + lcp_path + 'views/img/star-full.png" style="float:left; cursor: hand; cursor: pointer;">';
	}
	/*half stars*/
	if (stars % 1 != 0)
	{
		var half_stars = 1;
		html += '<img class="lcp star" border="0" src="' + lcp_path + 'views/img/star-half.png" style="float:left; cursor: hand; cursor: pointer;">';
	}
	else
	{
		var half_stars = 0;
	}
	/*empty stars*/
	var empty_stars = 5 - Math.ceil(stars);
	for (i = 1; i <= empty_stars; i++)
	{
		html += '<img class="lcp star" border="0" src="' + lcp_path + 'views/img/star-empty.png" style="float:left; cursor: hand; cursor: pointer;">';
	}
	return html;
};

Livechatpro.prototype.playSound = function(type, loop, mute)
{
	type = typeof(type) != 'undefined' ? type : 'newmessage';
	loop = typeof(loop) != 'undefined' ? loop : false;
	mute = typeof(mute) != "undefined" ? mute : false;
	if (mute == false)
	{
		if (type == 'newmessage')
			var sound_src = lcp_path + 'sounds/new-message-default.mp3';
		else
			var sound_src = lcp_path + 'sounds/new-chat-default.mp3';
		
		$("#" + type).get(0).loop = loop;

		if ($("#" + type).attr('src') == '')
		{
			$("#" + type).attr('src', sound_src);
			//sound.setAttribute('autoplay', 'autoplay');
		}

		$("#" + type).get(0).play();
		//But for Chrome you cant use .play() and .pause(). You must use .Play() and .Stop()	
	}
};


Livechatpro.prototype.stopSound = function(type)
{
	type = typeof(type) != 'undefined' ? type : 'newmessage';
	try {
		$("#" + type).get(0).pause();
	} catch(e){}
};


Livechatpro.prototype.getDateTime = function(is_day, is_month, is_year, is_hour, is_minute, is_second)
{
	is_day = typeof(is_day) != 'undefined' ? is_day : true;
	is_month = typeof(is_month) != 'undefined' ? is_month : true;
	is_year = typeof(is_year) != 'undefined' ? is_year : true;
	is_hour = typeof(is_hour) != 'undefined' ? is_hour : true;
	is_minute = typeof(is_minute) != 'undefined' ? is_minute : true;
	is_second = typeof(is_second) != 'undefined' ? is_second : true;
	var now = new Date();
	var year = now.getFullYear();
	var month = now.getMonth() + 1;
	var day = now.getDate();
	var hour = now.getHours();
	var minute = now.getMinutes();
	var second = now.getSeconds();
	if (month.toString().length == 1)
	{
		var month = '0' + month;
	}
	if (day.toString().length == 1)
	{
		var day = '0' + day;
	}
	if (hour.toString().length == 1)
	{
		var hour = '0' + hour;
	}
	if (minute.toString().length == 1)
	{
		var minute = '0' + minute;
	}
	if (second.toString().length == 1)
	{
		var second = '0' + second;
	}
	/*console.log(is_day+', '+is_month+', '+is_year+', '+is_hour+', '+is_minute+', '+is_second); */
	var dateTime = '';
	if (is_day == true) dateTime += day;
	if (is_month == true) dateTime += '/' + month;
	if (is_year == true) dateTime += '/' + year;
	if (is_hour == true) dateTime += ' ' + hour;
	if (is_minute == true) dateTime += ':' + minute;
	if (is_second == true) dateTime += ':' + second;
	/*var dateTime = day+'/'+month+'/'+year+' '+hour+':'+minute+':'+second;  */
	return dateTime;
};

Livechatpro.prototype.l = function(name, prefix)
{
	prefix = typeof(prefix) != 'undefined' ? prefix : 'lcp';	

	if (lcp_lang.hasOwnProperty(name)) 
		return lcp_lang[name];
	else
	{
		console.warn('Js translation ' + name + ' do not exists.')
		return name;
	}
};

Livechatpro.prototype.validateInput = function(text)
{
	if (/[^a-zA-Z0-9]/.test(text))
	{
		return false;
	}
	return true;
};

Livechatpro.prototype.ucfirst = function(string)
{
	return string.charAt(0).toUpperCase() + string.slice(1);
};

Livechatpro.prototype.popupWindow2 = function(url, title, w, h)
{
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	var popup_window = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	//return setTimeout('popup_window.focus()', 1);
	return popup_window;
};

Livechatpro.prototype.empty = function(mixed_var) {

  var undef, key, i, len;
  var emptyValues = [undef, null, false, 0, '', '0'];

  for (i = 0, len = emptyValues.length; i < len; i++) {
    if (mixed_var === emptyValues[i]) {
      return true;
    }
  }

  if (typeof mixed_var === 'object') {
    for (key in mixed_var) {
      // TODO: should we check for own properties only?
      //if (mixed_var.hasOwnProperty(key)) {
      return false;
      //}
    }
    return true;
  }

  return false;
};

Livechatpro.prototype.checkRegexp = function(o, regexp, n)
{
	if (!(regexp.test(o.val())))
	{
		o.addClass("ui-state-error");
		this.updateTips(n);
		return false;
	}
	else
	{
		return true;
	}
};

//var livechatpro = new Livechatpro("Bob");
//livechatpro.speak();



/* LIVECHATPRO //////////////////////////////////////////////////////////////////////////////////////////////////// */

var lcp = new Livechatpro();

$(window).load(function()
{
	//if (lcp.checkModuleConflict() == true) return;

	if (lcp_init == 'front')
	{
		// FRONTOFFICE
		lcp.generateChatWidgetFront();

		/*adaugam vizitatorul in baza de date ------------------------------------------------*/
		var data = {
			'language': navigator.language || navigator.userLanguage,
			'browser': navigator.userAgent,
			'current_page': window.location.href,
			'timezone': new Date().getTimezoneOffset() / 60,
			'resolution': screen.width + ' x ' + screen.height,
			'referrer': document.referrer,
			'os': navigator.oscpu,
			/*'action' : 'loop',*/
		};

		var params = {
			'load': 'addUpdateVisitor',
			'divs': null,
			'params':
			{
				'data': data,
			},
		};

		lcp.ajaxController(params, function(result)
		{
			lcp_id_visitor = result;
			$('#lcp_id_visitor').val(lcp_id_visitor);
			//console.log(lcp_id_visitor);
			lcp.syncFrontChatDialog();

			if (!lcp.empty(lcp_primary_settings.sync_chat_interval_frontend)) sync_chat_interval_frontend = parseInt(lcp_primary_settings.sync_chat_interval_frontend) * 1000;
			else sync_chat_interval_frontend = 7000;

			setInterval(function()
			{
				lcp.syncFrontChatDialog();
			}, sync_chat_interval_frontend);

		});
	}
	else
	{
		// BACKOFFICE

		lcp.generateChatWidgetAdmin();

		// sync chat dialog
		if (!lcp.empty(lcp_primary_settings.sync_chat_interval_backend)) sync_chat_interval_backend = parseInt(lcp_primary_settings.sync_chat_interval_backend) * 1000;
		else sync_chat_interval_backend = 7000;

		if (lcp_primary_settings.host_type == 'Self')
		{
			lcp.syncChatDialog();
			// sync the chat dialog
			setInterval(function()
			{
				lcp.syncChatDialog(); // aici se face si syncOnlineUsers();
			}, sync_chat_interval_backend);


			// reload the visitors datatable
			setInterval(function()
			{
				if ($('#tabs-statistics').is(":visible"))
				{
					try
					{
						if ($.fn.DataTable.isDataTable('#onlinevisitors_grid')) 
							onlinevisitors_datatable.api().ajax.reload();
					}
					catch (e) {}
				}
			}, 30000);

		}

		// trigger status change
		try { if ($.localStorage.isEmpty('status_select')) $('#status_select').val('Online').trigger('change'); } catch (e)	{}

		// trigger change to settings
		if (lcp.getUrlParameter('controller') == 'AdminModules' && lcp.getUrlParameter('configure') == 'livechatpro')
		{
			setTimeout(function()
			{
				//console.log(lcp_primary_settings);
				$("#settings").trigger("change");
				$("#iconsets").trigger("change");
			}, 1000);
		}

	}

});

/*$(window).resize(function()
{
	if (lcp_init == 'front')
	{
		// FRONTOFFICE
		return;
	}
	else
	{
		// BACKOFFICE
		
		$('#dialog_chat').closest("div.ui-dialog").css(
		{
			'width': parseInt($(window).width()) - 20,
			'height': parseInt($(window).height()) - 50,
			'top': '40px'
		});
		//$('#onlinevisitors_grid_wrapper, #archive_grid_wrapper, #messages_grid_wrapper, #tickets_grid_wrapper, #ratings_grid_wrapper, #logs_grid_wrapper').css({'width': parseInt($(window).width()) - 80 + 'px',});\
	}

}).resize();*/

var window_focus;
$(window).focus(function() {
    window_focus = true;
}).blur(function() {
    window_focus = false;
});

$(document).ready(function()
{
	if (lcp_init == 'front')
	{
		// FRONTOFFICE

		if ($('.info-account').is(':visible'))
		{
			$('.myaccount-link-list:eq(1)').append('<li><a id="lcp_customer_helpdesk_a" title="Helpdesk" href="javascript:{}"><i class="icon-ticket fa fa-ticket"></i><span>'+lcp.l('Helpdesk')+'</span></a></li>').after('<div id="dialog-customer-helpdesk" title="Helpdesk" style="display:none"><div style="width: 1000px; height:500px; overflow-y: scroll;" id="ajax_customer_helpdesk_details_div"></div></div>');

			$(document).on('click', "#lcp_ticket_save_a", function()
			{
				var data = {
					'id_ticket': lcp_id_ticket,
					'id_department': $('#lcp_ticket_department').val(),
					'subject': $('#lcp_ticket_subject').val(),
					'status': $('#lcp_ticket_status').val(),
					'priority': $('#lcp_ticket_priority').val(),
				};
				var params = {
					'load': 'updateTicket',
					'divs': null,
					'params':
					{
						'data': data,
					},
					'preloader':
					{
						'divs':
						{
							0: 'statistics_ajax_load_span',
						},
						'type': 2,
						'style': 3,
					}
				};
				lcp.ajaxController(params, function(result)
				{
					tickets_datatable.api().ajax.reload();
					alert(result);
				});
			});

			$(document).on('click', "#lcp_ticket_add_reply_a", function()
			{
				var data = {
					'id_ticket': lcp_id_ticket,
					'id_staffprofile': lcp_ticket_id_staffprofile,
					'id_customer': lcp_ticket_id_customer,
					'reply_from': 'Customer',
					'message': $('#lcp_ticket_reply_textarea').val(),
				};
				var params = {
					'load': 'addTicketReply',
					'divs': null,
					'params':
					{
						'data': data,
					},
					'preloader':
					{
						'divs':
						{
							0: 'statistics_ajax_load_span',
						},
						'type': 2,
						'style': 3,
					}
				};
				lcp.ajaxController(params, function(result)
				{
					var data2 = {
						'from' : 'Customer',
						'id_ticket': lcp_id_ticket,
					};
					var params2 = {
						'load': 'getTicket',
						'divs': 
						{
							0: 'ajax_ticket_details_div',
						},
						'params':
						{
							'data': data2,
						},
						'preloader':
						{
							'divs':
							{
								0: 'statistics_ajax_load_span',
							},
							'type': 2,
							'style': 3,
						}
					};
					lcp.ajaxController(params2, function(result2)
					{

					});

					tickets_datatable.api().ajax.reload();

				});
			});

			$(document).on('click', "#lcp_customer_helpdesk_a", function()
			{
				$( "#dialog-customer-helpdesk" ).dialog({
						
						autoOpen: false,
						modal: false,
						width: 1015,
						/*width: parseInt($(window).width()) - 20,
						height: parseInt($(window).height()) - 20,
						position: ['top', 10],*/
						close : function() {
							/*allFields.val( "" ).removeClass( "ui-state-error" );*/
						}
					});

				var data = {
					//'id_ticket': id_ticket,
				};
				var params = {
					'load': 'getCustomerTickets',
					'divs': 
					{
						0: 'ajax_customer_helpdesk_details_div',
					},
					'params':
					{
						'data': data,
					},
					'preloader':
					{
						'divs':
						{
							0: 'ajax_customer_helpdesk_details_div',
						},
						'type': 1,
						'style': 3,
					}
				};
				lcp.ajaxController(params, function(result)
				{

					$('#ajax_customer_helpdesk_details_div').prepend('<link rel="stylesheet" type="text/css" href="'+lcp_url+'libraries/datatables/datatables.min.css">');


					script_arr2 = [];

					script_arr2.push(lcp_url+"libraries/datatables/datatables.min.js");


					$.loadJSArray(script_arr2).done(function() {

						if (!$.fn.DataTable.isDataTable('#tickets_grid'))
						{
							var tickets_table = lcp_db_prefix + lcp_module_name + '_tickets';
							var staffprofiles_table = lcp_db_prefix + lcp_module_name + '_staffprofiles';
							var employee_table = lcp_db_prefix + 'employee';
							var customer_table = lcp_db_prefix + 'customer';
							var departments_table = lcp_db_prefix + lcp_module_name + '_departments';

							var tickets_editor = new $.fn.dataTable.Editor(
							{
								//ajax: lcp_grid_path + 'customertickets',
								ajax:
								{
									url: lcp_grid_path + 'customertickets',
									type: 'POST',
									"data":
									{
										"id_customer": lcp_id_customer,
										"session": lcp_session,
									}
								},
								table: "#tickets_grid",
								fields: [
								{
									label: lcp.l("Department:"),
									name: tickets_table + ".id_department",
									type: "select",
								}, 
								{
									label: lcp.l("Status:"),
									name: tickets_table + ".status",
									type: "select",
									options: [
									{
										label: lcp.l("Open"),
										value: "Open"
									},
									{
										label: lcp.l("Answered"),
										value: "Answered"
									}, 
									{
										label: lcp.l("Customer-Reply"),
										value: "Customer-Reply"
									}, 
									{
										label: lcp.l("In-Progress"),
										value: "In-Progress"
									}, 
									{
										label: lcp.l("Closed"),
										value: "Closed"
									}, 
									]
								}, 
								{
									label: lcp.l("Priority:"),
									name: tickets_table + ".priority",
									type: "select",
									options: [
									{
										label: lcp.l("Low"),
										value: "Low"
									},
									{
										label: lcp.l("Medium"),
										value: "Medium"
									}, 
									{
										label: lcp.l("High"),
										value: "High"
									}, 
									]
								}, 
								{
									label: lcp.l("Client:"),
									name: tickets_table + ".id_customer",
									default : lcp_id_customer,
									type: "hidden",
								}, 
								{
									label: lcp.l("Subject:"),
									name: tickets_table + ".subject",
								}, 
								{
									label: lcp.l("Message:"),
									name: 'message',
									type:  "textarea",
								},
								{
									name: 'reply_from',
									default: 'Customer',
									type: "hidden",
								},
								]
							});

							var tickets_editor_create_button = {
								extend: "create",
								editor: tickets_editor
							};
							var tickets_editor_remove_button = {
								extend: "remove",
								editor: tickets_editor
							};
							var tickets_editor_view_button = {
								extend: 'selectedSingle',
								//sButtonClass: 'marginLeft',
								text: lcp.l('View details'),
								action: function(e, dt, node, config)
								{
									if (tickets_datatable.api().row('.selected').length !== 0)
									{
										var id_ticket = tickets_datatable.api().row('.selected').data()[tickets_table].id_ticket;
										var data = {
											'from' : 'Customer',
											'id_ticket': id_ticket,
										};
										var params = {
											'load': 'getTicket',
											'divs': 
											{
												0: 'ajax_ticket_details_div',
											},
											'params':
											{
												'data': data,
											},
											'preloader':
											{
												'divs':
												{
													0: 'statistics_ajax_load_span',
												},
												'type': 2,
												'style': 3,
											}
										};
										lcp.ajaxController(params, function(result)
										{
											$("#dialog-form-ticket-details").dialog("open");
										});
									}
								}
							};
							var tickets_editor_close_ticket_button = {
								extend: 'selectedSingle',
								//sButtonClass: 'marginLeft',
								text: lcp.l('Close ticket'),
								action: function(e, dt, node, config)
								{
									if (tickets_datatable.api().row('.selected').length !== 0)
									{
										var id_ticket = tickets_datatable.api().row('.selected').data()[tickets_table].id_ticket;
										//console.log(tickets_datatable.api().row('.selected').data()); 
										var data = {
											'id_ticket': id_ticket,
											'status': 'Closed',
										};
										var params = {
											'load': 'updateTicket',
											'divs': null,
											'params':
											{
												'data': data,
											},
											'preloader':
											{
												'divs':
												{
													0: 'statistics_ajax_load_span',
												},
												'type': 2,
												'style': 3,
											}
										};
										lcp.ajaxController(params, function(result)
										{
											tickets_datatable.api().ajax.reload();
										});
									}
								}
							};
							var tickets_editor_buttons = [];
							
							tickets_editor_buttons.push(tickets_editor_create_button);
							tickets_editor_buttons.push(tickets_editor_remove_button);
							tickets_editor_buttons.push(tickets_editor_view_button);
							tickets_editor_buttons.push(tickets_editor_close_ticket_button);

							tickets_datatable = $('#tickets_grid').dataTable(
							{
								dom: "Bfrtip",
								//"processing": true,
								//"serverSide": true,
								//"scrollX": true,
								"pageLength": 10,
								"autoWidth": false,
								ajax:
								{
									url: lcp_grid_path + 'customertickets',
									type: 'POST',
									data: {
										'loaded_from': 'front',
										"session": lcp_session,
										"id_customer": lcp_id_customer,
									}
								},
								columns: [
								{
									data: tickets_table + ".date_add",
									className: "dt-body-center",
									render: function(data, type, row, meta)
									{
										if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
										else return data;
									}
								},
								{
									data: departments_table + ".name",
									className: "dt-body-center",
									render: function(data, type, row, meta)
									{
										if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
										else return data;
									}
								},
								{
									data: tickets_table + ".subject",
									className: "dt-body-center",
									render: function(data, type, row, meta)
									{
										if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data.substring(0, 20) + ' [...]</span>';
										else return data.substring(0, 20) + ' [...]';
									}
								},
								{
									data: employee_table + ".firstname",
									className: "dt-body-center",
									render: function(data, type, row, meta)
									{
										if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
										else return data;
									}
								},
								{
									data: customer_table + ".firstname",
									className: "dt-body-center",
									render: function(data, type, row, meta)
									{
										if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
										else return data;
									}
								},
								{
									data: tickets_table + ".last_update",
									className: "dt-body-center",
									render: function(data, type, row, meta)
									{
										if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
										else return data;
									}
								},
								{
									data: tickets_table + ".priority",
									className: "dt-body-center",
									render: function(data, type, row, meta)
									{
										if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
										else return data;
									}
								},
								{
									data: tickets_table + ".status",
									className: "dt-body-center",
									render: function(data, type, row, meta)
									{
										if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + lcp.l(data) + '</span>';
										else return lcp.l(data);
									}
								}, ],
								select: true,
								buttons: tickets_editor_buttons,
							});
											
						}
						else
						{
							lcp.preload({0: 'tickets_ajax_load_span'}, 'on', 3);
							tickets_datatable.api().ajax.reload(function(json)
							{
								lcp.preload({0: 'tickets_ajax_load_span'}, 'off', 3); //$('#myInput').val( json.lastInput );
							});
						}

					}); // end load datatables multiscripts

				}); // end ajax controller

				$("#dialog-customer-helpdesk").dialog("open");
			});
			
			if (!lcp.empty(lcp.getUrlParameter('ticket_details')))
			{
				$('#lcp_customer_helpdesk_a').trigger('click');	
			}

		} // end if info account is visible
		////////////////////////////////////////////////////////////////////////////////////////////////////


		$(document).on('click', '#header_message_td', function()
		{
			$('#minimize_chat').trigger('click');
		});

		$(document).on('click', '#before_chat_div_with_image', function()
		{
			//console.log('sss');
			$('#minimize_chat').trigger('click');
			$('#start_chat').trigger('click');
			$(this).hide();
			$('#chat_header_table').show();
		});

		//$("#before_chat_div").find('input[type=text]').attr('style', 'width: 100%; -webkit-border-radius: 5px !important; -moz-border-radius: 5px !important; border-radius: 5px !important; ');

		$(document).on('click', 'a[id^="show_hide_emoticons"]', function()
		{
			var button_id = $(this).attr('id'); /*//console.log( $(e.currentTarget).attr('id') );*/
			var position = $('#' + button_id).offset(); /*//console.log( position );*/
			var posX = position.left - $("#menu-emoticons").width();
			var posY = position.top - 250;
			$("#menu-emoticons").toggle();
			$("#menu-emoticons").offset(
			{
				left: posX,
				top: posY
			});
		});
		$(document).on('mouseleave', '#menu-emoticons', function()
		{
			$('#menu-emoticons').hide();
		});
		$(document).on('click', 'img[id^="emoticon_img"]', function()
		{
			var textarea_val = $('#msg').val();
			$('#msg').val('');
			$('#msg').val(textarea_val + $(this).prev().val());
			$('#menu-emoticons').hide();
		});
		$(document).on('click', '#minimize_chat', function()
		{

			var is_visible = $('#chat_inner_table').is(":visible");
			$('#chat_inner_table').slideToggle();

			if (is_visible)
			{
				$(this).removeClass('fa-chevron-down');
				$(this).addClass('fa-chevron-up');
				var data = {
					'chat_toggled': 'down',
				};
			}
			else
			{
				$(this).removeClass('fa-chevron-up');
				$(this).addClass('fa-chevron-down');
				var data = {
					'chat_toggled': 'up',
				};
			}
			var params = {
				'load': 'setCookie',
				'divs': null,
				'params':
				{
					'data': data,
				},
			};
			lcp.ajaxController(params, function(result) {});
		});
		$(document).on('click', '#close_chat', function()
		{
			$('#minimize_chat').trigger('click');
			var data = {
				'id_visitor': lcp_id_visitor,
			};

			var params = {
				'load': 'chatClosedFromClient',
				'divs': null,
				'params':
				{
					'data': data,
				},
			};

			if (lcp_primary_settings.slide_with_image == 'Y')
			{
				$('#before_chat_div_with_image').show();
				$('#chat_header_table').hide();
			}
			else
				$('#before_chat_div').show();

			$('#after_chat_div').hide();
			$("#start_chat_div").hide();
			$("#chat_msg_textarea_div").hide();
			try
			{
				clearInterval(blink_interval_id);
				$(".blink-container").removeClass("blink");
				delete blink_interval_id;
			}
			catch (e)
			{}
			lcp.ajaxController(params, function(result) {});
		});

		$(document).on('click', '#leave_message', function()
		{
			var name = $("#lcp_name"),
				email = $("#lcp_email"),
				phone = $("#lcp_phone"),
				/*department = $( "#department" ),*/
				question = $("#lcp_question"),
				all_fields = $([]).add(name).add(email).add(phone).add(question);

			all_fields.removeClass("ui-state-error");

			var valid = true;

			if (lcp_chat_status == 'offline')
			{
				if ($('#name_field_tbody').is(':visible') && lcp_primary_settings.name_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(name, lcp.l("name"), 3, 50);
				if ($('#email_field_tbody').is(':visible') && lcp_primary_settings.email_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(email, lcp.l("email"), 3, 50);
				if ($('#phone_field_tbody').is(':visible') && lcp_primary_settings.phone_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(phone, lcp.l("phone"), 3, 20);
				if ($('#question_field_tbody').is(':visible') && lcp_primary_settings.question_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(question, lcp.l("question"), 3, 500);
			}
			else
			{
				if ($('#name_field_tbody').is(':visible') && lcp_primary_settings.name_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(name, lcp.l("name"), 3, 50);
				if ($('#email_field_tbody').is(':visible') && lcp_primary_settings.email_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(email, lcp.l("email"), 3, 50);
				if ($('#phone_field_tbody').is(':visible') && lcp_primary_settings.phone_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(phone, lcp.l("phone"), 3, 20);
				if ($('#question_field_tbody').is(':visible') && lcp_primary_settings.question_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(question, lcp.l("question"), 3, 500);
			}

			if (valid)
			{
				var data = {
					'id_visitor': lcp_id_visitor,
					'name': $('#lcp_name').val(),
					'email': $('#lcp_email').val(),
					'phone': $('#lcp_phone').val(),
					'id_department': $('#departments').val(),
					'department': $('#departments').find('option:selected').text(),
					'question': $('#lcp_question').val(),
					'current_url': lcp_current_url,
				};
				var params = {
					'load': 'addMessage',
					'divs': null,
					'params':
					{
						'data': data,
					},
					'preloader':
					{
						'divs':
						{
							0: 'send_message_ajax_load_span',
						},
						'type': 2,
						'style': 3,
					}
				};
				lcp.ajaxController(params, function(result)
				{
					$('#lcp_name').val('');
					$('#lcp_email').val('');
					$('#lcp_phone').val('');
					$('#lcp_question').val('');
					alert(lcp.l('The message was succesfully sent!'));
				});
			}
			else
			{
				$("#error_tr").show();
			}
		});

		$(document).on('click', '#start_chat', function()
		{
			var name = $("#lcp_name"),
				email = $("#lcp_email"),
				phone = $("#lcp_phone"),
				/*department = $( "#department" ),*/
				question = $("#lcp_question"),
				all_fields = $([]).add(name).add(email).add(phone).add(question);
			all_fields.removeClass("ui-state-error");
			var valid = true;

			if (lcp_chat_status == 'offline')
			{
				if ($('#name_field_tbody').is(':visible') && lcp_primary_settings.name_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(name, lcp.l("name"), 3, 50);
				if ($('#email_field_tbody').is(':visible') && lcp_primary_settings.email_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(email, lcp.l("email"), 3, 50);
				if ($('#phone_field_tbody').is(':visible') && lcp_primary_settings.phone_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(phone, lcp.l("phone"), 3, 20);
				if ($('#question_field_tbody').is(':visible') && lcp_primary_settings.question_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(question, lcp.l("question"), 3, 500);
			}
			else
			{
				if ($('#name_field_tbody').is(':visible') && lcp_primary_settings.name_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(name, lcp.l("name"), 3, 50);
				if ($('#email_field_tbody').is(':visible') && lcp_primary_settings.email_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(email, lcp.l("email"), 3, 50);
				if ($('#phone_field_tbody').is(':visible') && lcp_primary_settings.phone_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(phone, lcp.l("phone"), 3, 20);
				if ($('#question_field_tbody').is(':visible') && lcp_primary_settings.question_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(question, lcp.l("question"), 3, 500);
			}

			if (valid)
			{
				$("#error_tr").hide();
				$("#before_chat_div").hide();
				$("#start_chat_div").show(); 
				$("#start_chat_div").html('<div id="be_patient" style="height: '+((lcp.empty(lcp_primary_settings.height)) ? '350' : lcp_primary_settings.height) +'px">'+lcp.l('A representative will be connected, please be patient.')+'</div>');
				$("#be_patient").show();
				//$('#lcp_chat_wrapper').css('height', (parseInt(lcp_chat_height) - parseInt($('#chat_msg_textarea_div')[0].style.height)) + 'px');
				var data = {
					'name': $('#lcp_name').val(),
					'email': $('#lcp_email').val(),
					'phone': $('#lcp_phone').val(),
					'department': $('#departments').val(),
					'question': $('#lcp_question').val(),
					'id_department': $('#departments').val(),
					'id_visitor': lcp_id_visitor,
				};

				var params = {
					'load': 'chatRequestFromClient',
					'divs': null,
					'params':
					{
						'data': data,
					},
				};
				lcp.ajaxController(params, function(result) {

				}, true);
			}
			else
			{
				$("#error_tr").show();
			}
		});
		
		/** start the chat when enter into the textarea at frist time
		**  Add by thandar 27-Jan 2018
		**/
		$(document).on('keydown', '#lcp_question', function(e){
			var key = e.which;
			if(key == 13)  // the enter key code
			{
			   var name = $("#lcp_name"),
				email = $("#lcp_email"),
				phone = $("#lcp_phone"),
				/*department = $( "#department" ),*/
				question = $("#lcp_question"),
				all_fields = $([]).add(name).add(email).add(phone).add(question);
				all_fields.removeClass("ui-state-error");
				var valid = true;

				if (lcp_chat_status == 'offline')
				{
					if ($('#name_field_tbody').is(':visible') && lcp_primary_settings.name_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(name, lcp.l("name"), 3, 50);
					if ($('#email_field_tbody').is(':visible') && lcp_primary_settings.email_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(email, lcp.l("email"), 3, 50);
					if ($('#phone_field_tbody').is(':visible') && lcp_primary_settings.phone_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(phone, lcp.l("phone"), 3, 20);
					if ($('#question_field_tbody').is(':visible') && lcp_primary_settings.question_field_offline_mandatory == 'Y') valid = valid && lcp.checkLength(question, lcp.l("question"), 2, 500);
				}
				else
				{
					if ($('#name_field_tbody').is(':visible') && lcp_primary_settings.name_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(name, lcp.l("name"), 3, 50);
					if ($('#email_field_tbody').is(':visible') && lcp_primary_settings.email_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(email, lcp.l("email"), 3, 50);
					if ($('#phone_field_tbody').is(':visible') && lcp_primary_settings.phone_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(phone, lcp.l("phone"), 3, 20);
					if ($('#question_field_tbody').is(':visible') && lcp_primary_settings.question_field_online_mandatory == 'Y') valid = valid && lcp.checkLength(question, lcp.l("question"), 2, 500);
				}

				if (valid)
				{
					$("#error_tr").hide();
					$("#before_chat_div").hide();
					$("#start_chat_div").show(); 
					$("#start_chat_div").html('<div id="be_patient" style="height: '+((lcp.empty(lcp_primary_settings.height)) ? '350' : lcp_primary_settings.height) +'px">'+lcp.l('A representative will be connected, please be patient.')+'</div>');
					$("#be_patient").show();
					//$('#lcp_chat_wrapper').css('height', (parseInt(lcp_chat_height) - parseInt($('#chat_msg_textarea_div')[0].style.height)) + 'px');
					var data = {
						'name': $('#lcp_name').val(),
						'email': $('#lcp_email').val(),
						'phone': $('#lcp_phone').val(),
						'department': $('#departments').val(),
						'question': $('#lcp_question').val(),
						'id_department': $('#departments').val(),
						'id_visitor': lcp_id_visitor,
					};

					var params = {
						'load': 'chatRequestFromClient',
						'divs': null,
						'params':
						{
							'data': data,
						},
					};
					lcp.ajaxController(params, function(result) {

					}, true);
				}
				else
				{
					$("#error_tr").show();
				}
				return false;   
			} // end if(key == 13)
		});		
		/** End 27-Jan 2018 **/

		
		$(document).on('click', '#send_msg_a', function()
		{

			var visitor_name = (lcp_primary_settings.show_names == 'Y') ? ' (' + lcp_visitor_name + ')' : '';
		
					var html = '<div style="margin-right: 40px;" align="left"> \
								<table border="0" cellspacing="0" cellpadding="0"> \
									<tr>';
							if (lcp_primary_settings.show_avatars == 'Y')
							{
								html += '<td valign="top" style="width: 40px; height: 40px; vertical-align: top; padding: 0px 2px 0px 0px;"> \
											<img style="vertical-align: top;" border="0" src="' + lcp_path + 'views/img/avatars/sample-40x40.png" width="40" height="40"> \
										</td>';
							}
								html +=	'<td align="center" style="text-align: center !important; border-radius: 5px !important; background-color: #'+ lcp_primary_settings.chat_bubble_client_background +' !important; padding: 3px !important;"> \
											'+ lcp.parseChatText($('#msg').val()) +' \
										</td> \
									</tr> \
									</table> \
									<table border="0" cellspacing="0" cellpadding="0"> \
										<tr> \
											<td style="margin: 0px; padding: 0px; text-align: right; color: #AAAAAA; font-size: x-small;">'+ lcp.l('Client') + ' '+ visitor_name + ': ' + lcp.getDateTime(false, false, false) +'</td> \
										</tr> \
									</table> \
									</div>';

					$('#lcp_content').append(html);
					//$('#lcp_chat_wrapper').scrollTop($('#lcp_chat_wrapper')[0].scrollHeight);
					$('#lcp_chat_wrapper').scrollTop($('#lcp_chat_wrapper').prop('scrollHeight'));

					var data = {
						'msg': $('#msg').val(),
						'messages': html, //$('#content').html(),
						'id_visitor': lcp_id_visitor,
					};

					var params = {
						'load': 'chatMessageFromClient',
						'divs': null,
						'params':
						{
							'data': data,
						},

					};
					$('#msg').val('');
					lcp.ajaxController(params, function(result) {}, true);

		});

		$(document).on('keydown', 'input[id^="msg"]', function(event)
		{
			var keypressed = event.keyCode || event.which;
			if (keypressed == 13)
			{
				$('#send_msg_a').trigger('click');
				return false;
			}
		});
		$(document).on('click', '#chat_arrow_td', function()
		{
			//$("#chat_arrow_td").on('click', function(){
			$('#chat_table_body').slideToggle('slow');
		});
		
		$(document).on('click', "img.star", function()
		{
			//$( "#rating_table").find("img.star").on('click', function(){
			$(this).attr('src', lcp_path + 'views/img/star-full.png');
			$(this).prevAll().attr('src', lcp_path + 'views/img/star-full.png');
			$(this).nextAll().attr('src', lcp_path + 'views/img/star-empty.png');
			$('#rating_click').val(1);
			lcp_rating_click = 1;
		});
		$(document).on('mouseover', "img.star", function()
		{
			//$( "#rating_table").find("img.star").on('mouseover', function(){
			lcp_rating_click = typeof(lcp_rating_click) != 'undefined' ? lcp_rating_click : 0;
			if (lcp_rating_click != 1)
			{
				$(this).attr('src', lcp_path + 'views/img/star-full.png');
				$(this).prevAll().attr('src', lcp_path + 'views/img/star-full.png');
				$(this).nextAll().attr('src', lcp_path + 'views/img/star-empty.png');
			}
		});
		$(document).on('click', '#back_to_start_chat_a', function()
		{
			//enable rating comment
			$('#rating_comment').val(lcp.l('Comment...'));
			$('#rating_comment').prop('disabled', false);
			//enable the rate button
			$('#rate').removeClass('chat-button-disabled');
			$('#rate').addClass('chat-button');
			//redirect
			$('#after_chat_div').hide();

			if (lcp_primary_settings.slide_with_image == 'Y')
			{
				$('#before_chat_div_with_image').show();
				$('#chat_header_table').hide();
			}
			else
				$('#before_chat_div').show();
		
		});
		$(document).on('click', '#rate', function()
		{
			if ($(this).attr('class').indexOf("chat-button-disabled") !== -1) return false;
			var data = {
				'id_archive': lcp_id_archive,
				'stars': lcp.getRatingStars(),
				'comment': $('#rating_comment').val(),
			};
			var params = {
				'load': 'addRating',
				'divs': null,
				'params':
				{
					'data': data,
				},
				'preloader':
				{
					'divs':
					{
						0: 'rate_ajax_load_span',
					},
					'type': 2,
					'style': 3,
				}
			};
			//$('#rating_comment').val('');
			$('#rating_comment').prop('disabled', true);
			$(this).removeClass('chat-button');
			$(this).addClass('chat-button-disabled');
			lcp.ajaxController(params, function(result)
			{
				$('#rating_comment').val(lcp.l('The rating was added!'));
			});
		});



		/////////////////////////////////////////////////////////////////////////////////////////////////





	}
	else
	{	
		// BACKOFFICE



	// init uploadify elements
	$('#online_icon_upload').uploadifive(
	{
		'multi': false,
		'formData':
		{
			'location': 'iconsets',
		},
		'buttonText': lcp.l('Upload'),
		"uploadScript"     : lcp_path+"libraries/uploadify/uploadifive.php",
		'onUploadComplete': function(file, data)
		{
			if (data == 'error1') alert(lcp.l('File exists, choose different filename.'));
			else if (data == 'error2') alert(lcp.l('Invalid file type.'));
			else
			{
				var iso_code = $('#online_img_languages').find('option:selected').text();
				$("#online_img_" + iso_code).attr('src', lcp_path + 'views/img/iconsets/' + data);
			}
		},
	});

	$('#offline_icon_upload').uploadifive(
	{
		'multi': false,
		'formData':
		{
			'location': 'iconsets',
		},
		'buttonText': lcp.l('Upload'),
		"uploadScript"     : lcp_path+"libraries/uploadify/uploadifive.php",
		'onUploadComplete': function(file, data)
		{
			if (data == 'error1') alert(lcp.l('File exists, choose different filename.'));
			else if (data == 'error2') alert(lcp.l('Invalid file type.'));
			else
			{
				var iso_code = $('#offline_img_languages').find('option:selected').text();
				$("#offline_img_" + iso_code).attr('src', lcp_path + 'views/img/iconsets/' + data);
			}
		},
	});



	if (lcp.getUrlParameter('controller') == 'AdminModules' && lcp.getUrlParameter('configure') == 'livechatpro')
	{
		// add buttons
		lcp.addToolbarBtn('separator');
		lcp.addToolbarBtn('video');
		lcp.addToolbarBtn('documentation');
		lcp.addToolbarBtn('contact');
		lcp.addToolbarBtn('modules');
	}

	if (lcp.getUrlParameter('controller') == 'AdminModules' || lcp.getUrlParameter('tab') == 'AdminModules')
	{

		//datatables
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		departments_table = lcp_db_prefix + lcp_module_name + '_departments';

		var departments_editor = new $.fn.dataTable.Editor(
		{
			//ajax: lcp_grid_path + 'departments',
			ajax:
			{
				url: lcp_grid_path + 'departments',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			table: "#departments_grid",
			fields: [
			{
				label: lcp.l('Name:'),
				name: departments_table + ".name",
			},
			{
				label: lcp.l('Status:'),
				name: departments_table + ".status",
				type: "select",
				options: [
				{
					label: lcp.l('Active'),
					value: "Active"
				},
				{
					label: lcp.l('Inactive'),
					value: "Inactive"
				}, ]
			}],
			i18n: {
            create: {
                button: lcp.l('New'),
                title:  lcp.l('Create new entry'),
                submit: lcp.l('Create')
            },
            edit: {
                button: lcp.l('Edit'),
                title:  lcp.l('Edit entry'),
                submit: lcp.l('Update')
            },
            remove: {
                button: lcp.l('Delete'),
                title:  lcp.l('Delete'),
                submit: lcp.l('Delete'),
                confirm: {
                    _: lcp.l('Are you sure you want to delete')+" %d "+lcp.l('entries?'),
                    1: lcp.l('Are sure you want to delete this entry?')
                }
            },
            error: {
                system: lcp.l('An error has occured, please contact the system administrator')
            },
            datetime: {
                previous: lcp.l('Previous'),
                next:     lcp.l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
		});

		var departments_editor_create_button = {
			extend: "create",
			editor: departments_editor
		};
		var departments_editor_edit_button = {
			extend: "edit",
			editor: departments_editor
		};
		var departments_editor_remove_button = {
			extend: "remove",
			editor: departments_editor
		};
		var departments_editor_buttons = [];
		if (lcp_employee_is_superadmin == 'Y')
		{
			departments_editor_buttons.push(departments_editor_create_button);
			departments_editor_buttons.push(departments_editor_edit_button);
			departments_editor_buttons.push(departments_editor_remove_button);
		}
		else
		{
			departments_editor_buttons.push(departments_editor_create_button);
		}
		var departments_datatable = $('#departments_grid').dataTable(
		{
			dom: 'Bfrtip',
			//"processing": true,
			//"serverSide": true,
			"pageLength": 50,
			"autoWidth": false,
			"language": {
                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
            },
			ajax:
			{
				url: lcp_grid_path + 'departments',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			columns: [
			{
				data: departments_table + ".name",
			},
			{
				data: departments_table + ".status",
			}, ],
			select: true,
			buttons: departments_editor_buttons,
		});
	
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		var avatars = lcp_avatars; //console.log(avatars);
		var departments = lcp_departments; //console.log(departments);
		var staffprofiles_table = lcp_db_prefix + lcp_module_name + '_staffprofiles';
		var departments_table = lcp_db_prefix+lcp_module_name+'_departments';
		var employee_table = lcp_db_prefix + 'employee';
		var staffprofiles_editor = new $.fn.dataTable.Editor(
		{
			//ajax: lcp_grid_path + 'staffprofiles',
			ajax:
			{
				url: lcp_grid_path + 'staffprofiles',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			table: "#staffprofiles_grid",
			fields: [
				{
					label: lcp.l('Active:'),
					name: staffprofiles_table + ".is_active",
					type: "select",
					options: [
					{
						label: lcp.l('Yes'),
						value: "Y"
					},
					{
						label: lcp.l('No'),
						value: "N"
					}, ]
				},
				{
					label: lcp.l("Avatar:"),
					name: staffprofiles_table + ".avatar",
					type: "select",
					options: avatars
				},
				{
					label: lcp.l("Departments:"),
					name: staffprofiles_table + ".departments",
					type: "checkbox",
					options: departments,
					separator: ','
				},
				{
					label: lcp.l("Welcome message:"),
					name: staffprofiles_table + ".welcome_message",
				},
				{
					label: lcp.l("Signature:"),
					name: staffprofiles_table + ".signature",
				},
			],
			i18n: {
            create: {
                button: lcp.l("New"),
                title:  lcp.l("Create new entry"),
                submit: lcp.l("Create")
            },
            edit: {
                button: lcp.l("Edit"),
                title:  lcp.l("Edit entry"),
                submit: lcp.l("Update")
            },
            remove: {
                button: lcp.l("Delete"),
                title:  lcp.l("Delete"),
                submit: lcp.l("Delete"),
                confirm: {
                    _: lcp.l("Are you sure you want to delete")+" %d "+lcp.l("entries?"),
                    1: lcp.l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: lcp.l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: lcp.l('Previous'),
                next:     lcp.l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
		});
		var staffprofiles_editor_create_button = {
			//extend: 'text',
			//class: 'marginLeft',
			text: lcp.l('Add (redirect to employees)'),
			action: function(e, dt, node, config)
			{
				window.open("index.php?controller=AdminEmployees&token=" + lcp_employees_token, '_blank');
			}
		};
		var staffprofiles_editor_edit_button = {
			extend: "edit",
			editor: staffprofiles_editor
		};
		var staffprofiles_editor_remove_button = {
			extend: 'selectedSingle',
			//class: 'marginLeft',
			text: lcp.l('Delete (redirect to employees)'),
			action: function(e, dt, node, config)
			{
				window.open("index.php?controller=AdminEmployees&token=" + lcp_employees_token, '_blank');
			}
		};
		var staffprofiles_editor_buttons = [];
		if (lcp_employee_is_superadmin == 'Y')
		{
			staffprofiles_editor_buttons.push(staffprofiles_editor_create_button);
			staffprofiles_editor_buttons.push(staffprofiles_editor_edit_button);
			staffprofiles_editor_buttons.push(staffprofiles_editor_remove_button);
		}
		else
		{
			staffprofiles_editor_buttons.push(staffprofiles_editor_create_button);
		}
		staffprofiles_editor.on('open', function()
		{
			$('#DTE_Field_' + staffprofiles_table + '-avatar').trigger('change');
		});
		var staffprofiles_datatable = $('#staffprofiles_grid').dataTable(
		{
			dom: "Bfrtip",
			//"processing": true,
			//"serverSide": true,
			"pageLength": 50,
			"autoWidth": false,
			"language": {
                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
            },
			ajax:
			{
				url: lcp_grid_path + 'staffprofiles',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			columns: [
			{
				data: staffprofiles_table + ".is_active",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					return (data == 'Y') ? 'Yes' : 'No';
				}
			},
			{
				data: employee_table + ".firstname",
				className: "dt-body-center"
			},
			{
				data: employee_table + ".lastname",
				className: "dt-body-center"
			},
			{
				data: staffprofiles_table + ".avatar",
				className: "dt-body-center",
				render: function(data, type, row, meta)
				{
					//return '<a href="'+data+'">Download</a>';
					return '<img border="0" src="' + lcp_path + 'views/img/avatars/' + data + '" width="40" height="40">';
				}
			},
			{
				data: staffprofiles_table + ".departments",
				className: "dt-body-center",
			},
			{
				data: staffprofiles_table + ".welcome_message",
				className: "dt-body-center"
			},
			{
				data: staffprofiles_table + ".signature",
				className: "dt-body-center"
			}, ],
			select: true,
			buttons: staffprofiles_editor_buttons,
		});

		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		var predefinedmessages_table = lcp_db_prefix + lcp_module_name + '_predefinedmessages';
		var predefinedmessages_editor = new $.fn.dataTable.Editor(
		{
			//ajax: lcp_grid_path + 'predefinedmessages',
			ajax:
			{
				url: lcp_grid_path + 'predefinedmessages',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			table: "#predefinedmessages_grid",
			fields: [
			{
				label: lcp.l("Language:"),
				name: predefinedmessages_table + ".iso_code",
				type: "select"
			},
			{
				label: lcp.l("Title:"),
				name: predefinedmessages_table + ".title",
			},
			{
				label: lcp.l("Message:"),
				name: predefinedmessages_table + ".message",
			}, ],
			i18n: {
            create: {
                button: lcp.l("New"),
                title:  lcp.l("Create new entry"),
                submit: lcp.l("Create")
            },
            edit: {
                button: lcp.l("Edit"),
                title:  lcp.l("Edit entry"),
                submit: lcp.l("Update")
            },
            remove: {
                button: lcp.l("Delete"),
                title:  lcp.l("Delete"),
                submit: lcp.l("Delete"),
                confirm: {
                    _: lcp.l("Are you sure you want to delete")+" %d "+lcp.l("entries?"),
                    1: lcp.l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: lcp.l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: lcp.l('Previous'),
                next:     lcp.l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
		});
		var predefinedmessages_editor_create_button = {
			extend: "create",
			editor: predefinedmessages_editor
		};
		var predefinedmessages_editor_edit_button = {
			extend: "edit",
			editor: predefinedmessages_editor
		};
		var predefinedmessages_editor_remove_button = {
			extend: "remove",
			editor: predefinedmessages_editor
		};
		var predefinedmessages_editor_buttons = [];
		if (lcp_employee_is_superadmin == 'Y')
		{
			predefinedmessages_editor_buttons.push(predefinedmessages_editor_create_button)
			predefinedmessages_editor_buttons.push(predefinedmessages_editor_edit_button)
			predefinedmessages_editor_buttons.push(predefinedmessages_editor_remove_button);
		}
		else
		{
			predefinedmessages_editor_buttons.push(predefinedmessages_editor_create_button);
		}
		var predefinedmessages_datatable = $('#predefinedmessages_grid').dataTable(
		{
			dom: "Bfrtip",
			//"processing": true,
			//"serverSide": true,
			"pageLength": 50,
			"autoWidth": false,
			"language": {
                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
            },
			ajax:
			{
				url: lcp_grid_path + 'predefinedmessages',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			columns: [
			{
				data: predefinedmessages_table + ".iso_code",
				className: "dt-body-center",
			},
			{
				data: predefinedmessages_table + ".title",
				//className: "dt-body-center"
			},
			{
				data: predefinedmessages_table + ".message",
				//className: "dt-body-center"
			},
			{
				data: predefinedmessages_table + ".last_update",
				className: "dt-body-center"
			}, ],
			select: true,
			buttons: predefinedmessages_editor_buttons,
		});

		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$("#tabs-departments-a").on('click', function()
		{
			try
			{
				departments_datatable.api().ajax.reload();
			}
			catch (e)
			{}
		});
		$("#tabs-staff-profiles-a").on('click', function()
		{
			try
			{
				var params = {
					'load': 'syncStaffProfiles',
					'divs': null,
				};
				lcp.ajaxController(params, function(result)
				{
					staffprofiles_datatable.api().ajax.reload();
				});
			}
			catch (e)
			{}
		});
		$("#tabs-predefined-messages-a").on('click', function()
		{
			try
			{
				predefinedmessages_datatable.api().ajax.reload();
			}
			catch (e)
			{}
		});

	} // end if controller

	////////////////////////////////////////////////////////////////////////////////////////////

	/*$("#tabs").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");
    $("#tabs li").removeClass("ui-corner-top").addClass("ui-corner-left");*/


//console.log('aici');
	$(document).on('change', '#DTE_Field_' + staffprofiles_table + '-avatar', function(event)
	{
		if ($('#staffprofiles_avatar_img').length === 0) $('div.DTE_Field_Name_' + staffprofiles_table + '\\.avatar > .DTE_Field_Input').append('<img id="staffprofiles_avatar_img" border="0" src="' + lcp_path + 'views/img/avatars/' + $(this).val() + '" width="40" height="40">');
		else $('#staffprofiles_avatar_img').attr('src', lcp_path + 'views/img/avatars/' + $(this).val());
	});
	$(document).on('click', 'a[id^="tabs-chat-a"]', function(event)
	{
		lcp_active_chat_tab = $("#tabs-chat").tabs('option', 'active');
		$.localStorage.set('lcp_active_chat_tab', lcp_active_chat_tab);

		lcp_active_chat_subtab = lcp_active_chat_subtab.slice(0, -1) + lcp_active_chat_tab;
		$.localStorage.set('lcp_active_chat_subtab', lcp_active_chat_subtab);

		$('#'+lcp_active_chat_subtab).trigger('click'); // cand se da click pe tab si da automat click si pe subtab

	});
	$(document).on('click', '#tabs-users > ul > li > a', function(event)
	{
		$.localStorage.set('lcp_active_users_tab', $("#tabs-users").tabs('option', 'active'));
	});
	$(document).on('click', '#tabs-statistics > ul > li > a', function(event)
	{
		$.localStorage.set('lcp_active_statistics_tab', $("#tabs-statistics").tabs('option', 'active'));
		$.localStorage.set('lcp_active_statistics_tab_link', $(this).attr('id'));
	});

	//$('#submit').css('height', $('#submit').parent('td').height());

	$("input[type=submit], button").on('mouseover', function()
	{
		$(this).addClass('ui-priority-primary');
	});
	$("input[type=submit], button").on('mouseout', function()
	{
		$(this).removeClass('ui-priority-primary');
	});
	$('#word').keydown(function(event)
	{
		var keypressed = event.keyCode || event.which;
		if (keypressed == 13)
		{
			$(this).closest('form').submit();
			$(this).val('');
			return false;
		}
	});

	$(document).on('mouseleave', '#menu-emoticons', function()
	{
		var _this = this;
		setTimeout(function()
		{
			var hovered = $(_this).find("li:hover").length;
			if ($(_this).is(':visible') && !hovered) $(_this).hide();
		}, 300);
	});

	
	//$("#tabs-visitors-a").on('click', function()
	$(document).on('click', '#tabs-visitors-a, #tabs-visitors', function()
	{		
	// initializam datagridurile
	if (!$.fn.DataTable.isDataTable('#onlinevisitors_grid'))
	{
		var onlinevisitors_table = lcp_db_prefix + lcp_module_name + '_onlinevisitors';
		var archive_table = lcp_db_prefix + lcp_module_name + '_archive';
		var is_visible_columns = (lcp_primary_settings.chat_type_admin == 'Popup') ? true : false;
		var onlinevisitors_datatable_dom = (lcp_primary_settings.chat_type_admin == 'Popup') ? "Bfrtip" : "Brtip";
		onlinevisitors_datatable = $('#onlinevisitors_grid').dataTable(
		{
			dom: onlinevisitors_datatable_dom,
			//"processing": true,
			//"serverSide": true,
			"scrollX": true,
			"pageLength": 50,
			"autoWidth": false,
			"language": {
                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
            },
			ajax:
			{
				url: lcp_grid_path + 'onlinevisitors',
				type: 'POST',
				"data":
				{
					"session": lcp_session
				}
			},
			columns: [
				{
					data: onlinevisitors_table + ".id_visitor",
					className: "dt-body-center",
					visible: is_visible_columns,
				},
				//{ data: onlinevisitors_table+".country", className: "dt-body-center" },
				{
					data: onlinevisitors_table + ".country",
					className: "dt-body-center",
					render: function(data, type, row, meta)
					{
						return '<table style="border: 0px !important;"><tr><td><img id="county_flag" border="0" style="width:16px;height:16px;vertical-align:middle;" src="' + lcp_path + 'views/img/country_flags/' + data + '.png"></td><td><span style="">' + data + '</span></td></tr></table>';
					},
				},
				//{ data: onlinevisitors_table+".in_chat", className: "dt-body-center" },
				{
					data: onlinevisitors_table + ".city",
					className: "dt-body-center",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".language",
					className: "dt-body-center",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".visits",
					className: "dt-body-center",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".current_page",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".host",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".ip",
					className: "dt-body-center"
				},
				{
					data: onlinevisitors_table + ".browser",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".timezone",
					className: "dt-body-center",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".resolution",
					className: "dt-body-center",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".referrer",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".page_count",
					className: "dt-body-center",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".os",
					visible: is_visible_columns,
				},
				{
					data: onlinevisitors_table + ".last_visit",
					className: "dt-body-center",
					visible: is_visible_columns,
				},
			],
			select: true,
			buttons: [
			{
				extend: 'selectedSingle',
				//sButtonClass: 'marginLeft',
				text: lcp.l('Invite to chat'),
				action: function(e, dt, node, config)
				{
					if (onlinevisitors_datatable.api().row('.selected').length !== 0)
					{
						var id_visitor = onlinevisitors_datatable.api().row('.selected').data()[onlinevisitors_table].id_visitor;
						var data = {
							'id_visitor': id_visitor,
						};
						var params = {
							'load': 'chatRequestFromStaff',
							'divs': null,
							'params':
							{
								'data': data,
							},
							'preloader':
							{
								'divs':
								{
									0: 'statistics_ajax_load_span',
								},
								'type': 2,
								'style': 3,
							}
						};
						lcp.ajaxController(params, function(result)
						{
							var res = JSON.parse(result);
							//console.log(result);
							if (lcp_primary_settings.host_type == 'Self')
							{
								$('#awaiting_response_chat_dialogs').html(lcp.getHtmlCode('ajax.header_chats_counter.tpl', res));
								
								if (lcp_primary_settings.chat_type_admin == 'Popup')
									$("#ajax_chats_div").html(lcp.getHtmlCode('ajax.chats.tpl', res));
								else
								{
									$("#ajax_chats_div").html(lcp.getHtmlCode('ajax.chats_slide.tpl', res));

									if (!lcp.empty(res.response.active_pending_archives)) 
									{
										$.each(res.response.active_pending_archives, function( key, value ) 
										{
											// daca nu exista divul
											if ($('#ajax_chats_textarea_div_'+key).length == 0)
											{
												$('#ajax_chats_textarea_div').append('<td id="ajax_chats_textarea_div_'+key+'" style="padding-top: 0px; padding-right: 5px;"><div style="background-color: white; height: 60px; box-shadow: 0px 5px 5px 0px rgba(66,66,66,0.75); padding: 2px;">'+lcp.getHtmlCode('ajax.chats_slide_textarea.tpl', res, key)+'</div></td>');
											}
											else
											{
												if ($('#ajax_chats_textarea_div_'+key).html() == '')
													$('#ajax_chats_textarea_div_'+key).html(lcp.getHtmlCode('ajax.chats_slide_textarea.tpl', res, key));
											}

										});
									}
									else
									{
										$('#ajax_chats_textarea_div').html('');
									}
								}

								$("#ajax_onlineusers_div").html(lcp.getHtmlCode('ajax.onlineusers.tpl', res));
							}
							//lcp.syncChatDialog('statistics_ajax_load_span');
						}, true);
					}
				}
			},
			{
				extend: 'selectedSingle',
				//sButtonClass: 'marginLeft',
				text: lcp.l('View details'),
				action: function(e, dt, node, config)
				{
					if (onlinevisitors_datatable.api().row('.selected').length !== 0)
					{
						// window.open("index.php?controller=AdminEmployees&token="+lcp_employees_token , '_blank');                            
						var id_visitor = onlinevisitors_datatable.api().row('.selected').data()[onlinevisitors_table].id_visitor;
						lcp.showVisitorDetails(id_visitor, true);
						lcp.showVisitorVisitedPagesHistory(id_visitor, true);
						lcp.showVisitorGeoTracking(id_visitor, true);

						$( "#dialog-form-visitordetails" ).dialog({
							autoOpen: false,
							/*height: 300,*/
							width: 800,
							modal: false,
							close: function() {
								/*allFields.val( "" ).removeClass( "ui-state-error" );*/
							}
						});

						$("#dialog-form-visitordetails").dialog("open");
					}
				}
			}, ],
		});
		
	}
	else
	{
		lcp.preload({0: 'online_visitors_ajax_load_span'}, 'on', 3);
		onlinevisitors_datatable.api().ajax.reload(function(json)
		{
			lcp.preload({0: 'online_visitors_ajax_load_span'}, 'off', 3); //$('#myInput').val( json.lastInput );
		});
	}
	//$('#onlinevisitors_grid_wrapper').css({'width': parseInt($(window).width()) - 80 + 'px',});
	});

	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

		//$("#tabs-archive-a").on('click', function()
		$(document).on('click', '#tabs-archive-a', function()
		{
			if (!$.fn.DataTable.isDataTable('#archive_grid'))
			{
				var archive_table = lcp_db_prefix + lcp_module_name + '_archive';
				var departments_table = lcp_db_prefix + lcp_module_name + '_departments';
				var archive_editor = new $.fn.dataTable.Editor(
				{
					//ajax: lcp_grid_path + 'archive',
					ajax:
					{
						url: lcp_grid_path + 'archive',
						type: 'POST',
						"data":
						{
							"session": lcp_session
						}
					},
					table: "#archive_grid",
					fields: [
					{
						label: lcp.l("Name:"),
						name: archive_table + ".name",
					}, ],
			i18n: {
            create: {
                button: lcp.l("New"),
                title:  lcp.l("Create new entry"),
                submit: lcp.l("Create")
            },
            edit: {
                button: lcp.l("Edit"),
                title:  lcp.l("Edit entry"),
                submit: lcp.l("Update")
            },
            remove: {
                button: lcp.l("Delete"),
                title:  lcp.l("Delete"),
                submit: lcp.l("Delete"),
                confirm: {
                    _: lcp.l("Are you sure you want to delete")+" %d "+lcp.l("entries?"),
                    1: lcp.l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: lcp.l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: lcp.l('Previous'),
                next:     lcp.l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
				});
				var archive_editor_view_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('View details'),
					action: function(e, dt, node, config)
					{
						if (archive_datatable.api().row('.selected').length !== 0)
						{
							var id_archive = archive_datatable.api().row('.selected').data()[archive_table].id_archive;
							//$( "#dialog-form-archive" ).dialog( "open" );
							var data = {
								'id_archive': id_archive,
							};
							var params = {
								'load': 'getArchive',
								'divs': null,
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								var res = JSON.parse(result);
								$("#archive_message").html(res.messages);
								$("#dialog-form-archive").dialog("open");
							}, true);
						}
					}
				};
				var archive_editor_remove_button = {
					extend: "remove",
					editor: archive_editor
				};
				var archive_editor_buttons = [];
				if (lcp_employee_is_superadmin == 'Y')
				{
					archive_editor_buttons.push(archive_editor_view_button);
					archive_editor_buttons.push(archive_editor_remove_button);
				}
				else
				{
					archive_editor_buttons.push(archive_editor_view_button);
				}
				archive_datatable = $('#archive_grid').dataTable(
				{
					dom: "Bfrtip",
					//"processing": true,
					//"serverSide": true,
					"scrollX": true,
					"pageLength": 10,
					"autoWidth": false,
					"language": {
		                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
		            },
					ajax:
					{
						url: lcp_grid_path + 'archive',
						type: 'POST',
						"data":
						{
							"session": lcp_session
						}
					},
					columns: [
					{
						data: archive_table + ".date_add",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".id_chat",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".name",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".internal",
						className: "dt-body-center"
					},
					{
						data: departments_table + ".name",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".email",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".phone",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".company",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".language",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".country",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".ip",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".host",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".duration",
						className: "dt-body-center"
					},
					{
						data: archive_table + ".log_entries",
						className: "dt-body-center"
					}, ],
					select: true,
					buttons: archive_editor_buttons,
				});
							
			}
			else
			{
				lcp.preload({0: 'archive_ajax_load_span'}, 'on', 3);
				archive_datatable.api().ajax.reload(function(json)
				{
					lcp.preload({0: 'archive_ajax_load_span'}, 'off', 3); //$('#myInput').val( json.lastInput );
				});
			}
			//$('#archive_grid_wrapper').css({'width': parseInt($(window).width()) - 80 + 'px',});
		});

			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

		//$("#tabs-messages-a").on('click', function()
		$(document).on('click', '#tabs-messages-a', function()
		{
			if (!$.fn.DataTable.isDataTable('#messages_grid'))
			{
				var messages_table = lcp_db_prefix + lcp_module_name + '_messages';
				var messages_editor = new $.fn.dataTable.Editor(
				{
					//ajax: lcp_grid_path + 'messages',
					ajax:
					{
						url: lcp_grid_path + 'messages',
						type: 'POST',
						"data":
						{
							"session": lcp_session
						}
					},
					table: "#messages_grid",
					fields: [
					{
						label: lcp.l("Name:"),
						name: messages_table + ".name",
					}, ],
			i18n: {
            create: {
                button: lcp.l("New"),
                title:  lcp.l("Create new entry"),
                submit: lcp.l("Create")
            },
            edit: {
                button: lcp.l("Edit"),
                title:  lcp.l("Edit entry"),
                submit: lcp.l("Update")
            },
            remove: {
                button: lcp.l("Delete"),
                title:  lcp.l("Delete"),
                submit: lcp.l("Delete"),
                confirm: {
                    _: lcp.l("Are you sure you want to delete")+" %d "+lcp.l("entries?"),
                    1: lcp.l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: lcp.l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: lcp.l('Previous'),
                next:     lcp.l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
				});
				var messages_editor_remove_button = {
					extend: "remove",
					editor: messages_editor
				};
				var messages_editor_reply_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('Reply'),
					action: function(e, dt, node, config)
					{
						if (messages_datatable.api().row('.selected').length !== 0)
						{
							var id_message = messages_datatable.api().row('.selected').data()[messages_table].id_message;
							var data = {
								'id_message': id_message,
							};
							var params = {
								'load': 'replyToMessage',
								'divs': null,
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								var res = JSON.parse(result);
								lcp_messages_id = res.id_message;
								$("#messages_date").html(res.date_add);
								$("#messages_department").html(res.department_name);
								$("#messages_sender_name").html(res.name);
								$("#messages_sender_email").html(res.email);
								$("#messages_sender_phone").html(res.phone);
								$("#messages_sender_company").html(res.company);
								$("#messages_message").html(res.question);
								$("#messages_reply_tr").show();
								$("#dialog-form-messages").dialog("open");
							});
						}
					}
				};
				var messages_editor_view_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('View details'),
					action: function(e, dt, node, config)
					{
						if (messages_datatable.api().row('.selected').length !== 0)
						{
							var id_message = messages_datatable.api().row('.selected').data()[messages_table].id_message;
							var data = {
								'id_message': id_message,
							};
							var params = {
								'load': 'getMessage',
								'divs': null,
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								var res = JSON.parse(result);
								/*console.log(res); return;*/
								lcp_messages_id = res.id_message;
								$("#messages_date").html(res.date_add);
								$("#messages_department").html(res.department_name);
								$("#messages_sender_name").html(res.name);
								$("#messages_sender_email").html(res.email);
								$("#messages_sender_phone").html(res.phone);
								$("#messages_sender_company").html(res.company);
								$("#messages_message").html(res.question);
								$("#dialog-form-messages").dialog("open");
							});
						}
					}
				};
				var messages_editor_mark_as_read_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('Mark as read'),
					action: function(e, dt, node, config)
					{
						if (messages_datatable.api().row('.selected').length !== 0)
						{
							var id_message = messages_datatable.api().row('.selected').data()[messages_table].id_message;
							//console.log(messages_datatable.api().row('.selected').data()); 
							var data = {
								'id_message': id_message,
								'status': 'Read',
							};
							var params = {
								'load': 'updateMessage',
								'divs': null,
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								messages_datatable.api().ajax.reload();
							});
						}
					}
				};
				var messages_editor_mark_as_unread_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('Mark as unread'),
					action: function(e, dt, node, config)
					{
						if (messages_datatable.api().row('.selected').length !== 0)
						{
							var id_message = messages_datatable.api().row('.selected').data()[messages_table].id_message;
							var data = {
								'id_message': id_message,
								'status': 'Unread',
							};
							var params = {
								'load': 'updateMessage',
								'divs': null,
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								messages_datatable.api().ajax.reload();
							});
						}
					}
				};
				var messages_editor_buttons = [];
				if (lcp_employee_is_superadmin == 'Y')
				{
					messages_editor_buttons.push(messages_editor_remove_button);
					messages_editor_buttons.push(messages_editor_view_button);
					//messages_editor_buttons.push(messages_editor_reply_button);
					messages_editor_buttons.push(messages_editor_mark_as_read_button);
					messages_editor_buttons.push(messages_editor_mark_as_unread_button);
				}
				else
				{
					messages_editor_buttons.push(messages_editor_view_button);
					//messages_editor_buttons.push(messages_editor_reply_button);
					messages_editor_buttons.push(messages_editor_mark_as_read_button);
					messages_editor_buttons.push(messages_editor_mark_as_unread_button);
				}
				messages_datatable = $('#messages_grid').dataTable(
				{
					dom: "Bfrtip",
					//"processing": true,
					//"serverSide": true,
					"scrollX": true,
					"pageLength": 10,
					"autoWidth": false,
					"language": {
		                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
		            },
					ajax:
					{
						url: lcp_grid_path + 'messages',
						type: 'POST',
						"data":
						{
							"session": lcp_session
						}
					},
					columns: [
					{
						data: messages_table + ".date_add",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: messages_table + ".name",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: messages_table + ".email",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: messages_table + ".phone",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: messages_table + ".department",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: messages_table + ".question",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data.substring(0, 20) + ' [...]</span>';
							else return data.substring(0, 20) + ' [...]';
						}
					},
					{
						data: messages_table + ".ip",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: messages_table + ".status",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[messages_table].status == 'Unread') return '<span style="font-weight: bold;">' + lcp.l(data) + '</span>';
							else return lcp.l(data);
						}
					}, ],
					select: true,
					buttons: messages_editor_buttons,
				});
								
			}
			else
			{
				lcp.preload({0: 'messages_ajax_load_span'}, 'on', 3);
				messages_datatable.api().ajax.reload(function(json)
				{
					lcp.preload({0: 'messages_ajax_load_span'}, 'off', 3); //$('#myInput').val( json.lastInput );
				});
			}
			//$('#messages_grid_wrapper').css({'width': parseInt($(window).width()) - 80 + 'px',});
		});	

			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

		//$("#tabs-tickets-a").on('click', function()
		$(document).on('click', '#tabs-tickets-a', function()
		{
			if (!$.fn.DataTable.isDataTable('#tickets_grid'))
			{
				var tickets_table = lcp_db_prefix + lcp_module_name + '_tickets';
				var staffprofiles_table = lcp_db_prefix + lcp_module_name + '_staffprofiles';
				var employee_table = lcp_db_prefix + 'employee';
				var customer_table = lcp_db_prefix + 'customer';
				var departments_table = lcp_db_prefix + lcp_module_name + '_departments';

				var tickets_editor = new $.fn.dataTable.Editor(
				{
					//ajax: lcp_grid_path + 'tickets',
					ajax:
					{
						url: lcp_grid_path + 'tickets',
						type: 'POST',
						"data":
						{
							"session": lcp_session
						}
					},
					table: "#tickets_grid",
					fields: [
					{
						label: lcp.l("Department:"),
						name: tickets_table + ".id_department",
						type: "select",
					}, 
					
					{
						label: lcp.l("Status:"),
						name: tickets_table + ".status",
						type: "select",
						options: [
						{
							label: lcp.l("Open"),
							value: "Open"
						},
						{
							label: lcp.l("Answered"),
							value: "Answered"
						}, 
						{
							label: lcp.l("Customer-Reply"),
							value: "Customer-Reply"
						}, 
						{
							label: lcp.l("In-Progress"),
							value: "In-Progress"
						}, 
						{
							label: lcp.l("Closed"),
							value: "Closed"
						}, 
						]
					}, 
					{
						label: lcp.l("Priority:"),
						name: tickets_table + ".priority",
						type: "select",
						options: [
						{
							label: lcp.l("Low"),
							value: "Low"
						},
						{
							label: lcp.l("Medium"),
							value: "Medium"
						}, 
						{
							label: lcp.l("High"),
							value: "High"
						}, 
						]
					}, 
					{
						label: lcp.l("Client:"),
						name: tickets_table + ".id_customer",
						type: "select",
					}, 
					{
						label: lcp.l("Staff:"),
						name: tickets_table + ".id_employee",
						type: "select",
					}, 
					{
						label: lcp.l("Subject:"),
						name: tickets_table + ".subject",
					}, 
					{
						label: lcp.l("Message:"),
						name: 'message',
						type:  "textarea",
					}, 
					{
						name: 'reply_from',
						default: 'Staff',
						type: "hidden",
					},
					],
			i18n: {
            create: {
                button: lcp.l("New"),
                title:  lcp.l("Create new entry"),
                submit: lcp.l("Create")
            },
            edit: {
                button: lcp.l("Edit"),
                title:  lcp.l("Edit entry"),
                submit: lcp.l("Update")
            },
            remove: {
                button: lcp.l("Delete"),
                title:  lcp.l("Delete"),
                submit: lcp.l("Delete"),
                confirm: {
                    _: lcp.l("Are you sure you want to delete")+" %d "+lcp.l("entries?"),
                    1: lcp.l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: lcp.l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: lcp.l('Previous'),
                next:     lcp.l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
				});
				var tickets_editor_create_button = {
					extend: "create",
					editor: tickets_editor
				};
				var tickets_editor_remove_button = {
					extend: "remove",
					editor: tickets_editor
				};
				var tickets_editor_view_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('View details'),
					action: function(e, dt, node, config)
					{
						if (tickets_datatable.api().row('.selected').length !== 0)
						{
							var id_ticket = tickets_datatable.api().row('.selected').data()[tickets_table].id_ticket;
							var data = {
								'from' : 'Staff',
								'id_ticket': id_ticket,
							};
							var params = {
								'load': 'getTicket',
								'divs': 
								{
									0: 'ajax_ticket_details_div',
								},
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								$("#dialog-form-ticket-details").dialog("open");
							});
						}
					}
				};
				var tickets_editor_close_ticket_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('Close ticket'),
					action: function(e, dt, node, config)
					{
						if (tickets_datatable.api().row('.selected').length !== 0)
						{
							var id_ticket = tickets_datatable.api().row('.selected').data()[tickets_table].id_ticket;
							//console.log(tickets_datatable.api().row('.selected').data()); 
							var data = {
								'id_ticket': id_ticket,
								'status': 'Closed',
							};
							var params = {
								'load': 'updateTicket',
								'divs': null,
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								tickets_datatable.api().ajax.reload();
							});
						}
					}
				};
				var tickets_editor_buttons = [];
				if (lcp_employee_is_superadmin == 'Y')
				{
					tickets_editor_buttons.push(tickets_editor_create_button);
					tickets_editor_buttons.push(tickets_editor_remove_button);
					tickets_editor_buttons.push(tickets_editor_view_button);
					tickets_editor_buttons.push(tickets_editor_close_ticket_button);
				}
				else
				{
					tickets_editor_buttons.push(tickets_editor_create_button);
					tickets_editor_buttons.push(tickets_editor_view_button);
					tickets_editor_buttons.push(tickets_editor_close_ticket_button);
				}
				tickets_datatable = $('#tickets_grid').dataTable(
				{
					dom: "Bfrtip",
					//"processing": true,
					//"serverSide": true,
					"scrollX": true,
					"pageLength": 10,
					"autoWidth": false,
					"language": {
		                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
		            },
					ajax:
					{
						url: lcp_grid_path + 'tickets',
						type: 'POST',
						data: {
							'loaded_from': 'back',
							"session": lcp_session,
						}
					},
					columns: [
					{
						data: tickets_table + ".date_add",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: departments_table + ".name",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: tickets_table + ".subject",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data.substring(0, 20) + ' [...]</span>';
							else return data.substring(0, 20) + ' [...]';
						}
					},
					{
						data: employee_table + ".firstname",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: customer_table + ".firstname",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: tickets_table + ".last_update",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: tickets_table + ".priority",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: tickets_table + ".status",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[tickets_table].status == 'Open' || row[tickets_table].status == 'Customer-Reply') return '<span style="font-weight: bold;">' + lcp.l(data) + '</span>';
							else return lcp.l(data);
						}
					}, ],
					select: true,
					buttons: tickets_editor_buttons,
				});
								
			}
			else
			{
				lcp.preload({0: 'tickets_ajax_load_span'}, 'on', 3);
				tickets_datatable.api().ajax.reload(function(json)
				{
					lcp.preload({0: 'tickets_ajax_load_span'}, 'off', 3); //$('#myInput').val( json.lastInput );
				});
			}
			//$('#tickets_grid_wrapper').css({'width': parseInt($(window).width()) - 80 + 'px',});
		});	


			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			
		//$("#tabs-ratings-a").on('click', function()
		$(document).on('click', '#tabs-ratings-a', function()
		{
			if (!$.fn.DataTable.isDataTable('#ratings_grid'))
			{
				var ratings_table = lcp_db_prefix + lcp_module_name + '_ratings';
				var ratings_editor = new $.fn.dataTable.Editor(
				{
					//ajax: lcp_grid_path + 'ratings',
					ajax:
					{
						url: lcp_grid_path + 'ratings',
						type: 'POST',
						"data":
						{
							"session": lcp_session
						}
					},
					table: "#ratings_grid",
					fields: [
					{
						label: lcp.l("Name:"),
						name: ratings_table + ".name",
					}, ],
			i18n: {
            create: {
                button: lcp.l("New"),
                title:  lcp.l("Create new entry"),
                submit: lcp.l("Create")
            },
            edit: {
                button: lcp.l("Edit"),
                title:  lcp.l("Edit entry"),
                submit: lcp.l("Update")
            },
            remove: {
                button: lcp.l("Delete"),
                title:  lcp.l("Delete"),
                submit: lcp.l("Delete"),
                confirm: {
                    _: lcp.l("Are you sure you want to delete")+" %d "+lcp.l("entries?"),
                    1: lcp.l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: lcp.l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: lcp.l('Previous'),
                next:     lcp.l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
				});
				var ratings_editor_remove_button = {
					extend: "remove",
					editor: ratings_editor
				};
				var ratings_editor_view_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('View details'),
					action: function(e, dt, node, config)
					{
						if (ratings_datatable.api().row('.selected').length !== 0)
						{
							var id_rating = ratings_datatable.api().row('.selected').data()[ratings_table].id_rating;
							var data = {
								'id_rating': id_rating,
							};
							var params = {
								'load': 'getRating',
								'divs': null,
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								var res = JSON.parse(result);
								/*console.log(res); return;*/
								$("#ratings_politness").html(lcp.generateRatingStars(res.politness));
								$("#ratings_qualification").html(lcp.generateRatingStars(res.qualification));
								$("#ratings_date").html(res.date_add);
								$("#ratings_internal").html(res.internal);
								$("#ratings_sender_name").html(res.name);
								$("#ratings_sender_email").html(res.email);
								$("#ratings_sender_phone").html(res.phone);
								$("#ratings_sender_company").html(res.company);
								$("#ratings_comment").html(res.comment);
								$("#dialog-form-ratings").dialog("open");
							});
						}
					}
				};
				var ratings_editor_mark_as_read_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('Mark as read'),
					action: function(e, dt, node, config)
					{
						if (ratings_datatable.api().row('.selected').length !== 0)
						{
							var id_rating = ratings_datatable.api().row('.selected').data()[ratings_table].id_rating;
							var data = {
								'id_rating': id_rating,
								'status': 'Read',
							};
							var params = {
								'load': 'updateRating',
								'divs': null,
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								ratings_datatable.api().ajax.reload();
							});
						}
					}
				};
				var ratings_editor_mark_as_unread_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('Mark as unread'),
					action: function(e, dt, node, config)
					{
						if (ratings_datatable.api().row('.selected').length !== 0)
						{
							var id_rating = ratings_datatable.api().row('.selected').data()[ratings_table].id_rating;
							var data = {
								'id_rating': id_rating,
								'status': 'Unread',
							};
							var params = {
								'load': 'updateRating',
								'divs': null,
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								ratings_datatable.api().ajax.reload();
							});
						}
					}
				};
				var ratings_editor_view_matching_chat_button = {
					extend: 'selectedSingle',
					//sButtonClass: 'marginLeft',
					text: lcp.l('View matching chat'),
					action: function(e, dt, node, config)
					{
						if (ratings_datatable.api().row('.selected').length !== 0)
						{
							var id_rating = ratings_datatable.api().row('.selected').data()[ratings_table].id_rating;
							var data = {
								'id_rating': id_rating,
							};
							var params = {
								'load': 'getRating',
								'divs': null,
								'params':
								{
									'data': data,
								},
								'preloader':
								{
									'divs':
									{
										0: 'statistics_ajax_load_span',
									},
									'type': 2,
									'style': 3,
								}
							};
							lcp.ajaxController(params, function(result)
							{
								var res = JSON.parse(result);
								var id_archive = res.id_archive;
								var data2 = {
									'id_archive': id_archive,
								};
								var params2 = {
									'load': 'getArchive',
									'divs': null,
									'params':
									{
										'data': data2,
									},
									'preloader':
									{
										'divs':
										{
											0: 'statistics_ajax_load_span',
										},
										'type': 2,
										'style': 3,
									}
								};
								lcp.ajaxController(params2, function(result2)
								{
									var res2 = JSON.parse(result2);
									$("#archive_message").html(res2.messages);
									$("#dialog-form-archive").dialog("open");
								});
							});
						}
					}
				};
				var ratings_editor_buttons = [];
				if (lcp_employee_is_superadmin == 'Y')
				{
					ratings_editor_buttons.push(ratings_editor_remove_button);
					ratings_editor_buttons.push(ratings_editor_view_button);
					ratings_editor_buttons.push(ratings_editor_mark_as_read_button);
					ratings_editor_buttons.push(ratings_editor_mark_as_unread_button);
					ratings_editor_buttons.push(ratings_editor_view_matching_chat_button);
				}
				else
				{
					ratings_editor_buttons.push(ratings_editor_view_button);
					ratings_editor_buttons.push(ratings_editor_mark_as_read_button);
					ratings_editor_buttons.push(ratings_editor_mark_as_unread_button);
					ratings_editor_buttons.push(ratings_editor_view_matching_chat_button);
				}
				ratings_datatable = $('#ratings_grid').dataTable(
				{
					dom: "Bfrtip",
					//"processing": true,
					//"serverSide": true,
					"scrollX": true,
					"pageLength": 10,
					"autoWidth": false,
					"language": {
		                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
		            },
					ajax:
					{
						url: lcp_grid_path + 'ratings',
						type: 'POST',
						"data":
						{
							"session": lcp_session
						}
					},
					columns: [
					{
						data: ratings_table + ".politness",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							return lcp.generateRatingStars(data);
						}
					},
					{
						data: ratings_table + ".qualification",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							return lcp.generateRatingStars(data);
						}
					},
					{
						data: ratings_table + ".date_add",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: ratings_table + ".internal",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: ratings_table + ".name",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: ratings_table + ".email",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: ratings_table + ".company",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data + '</span>';
							else return data;
						}
					},
					{
						data: ratings_table + ".comment",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + data.substring(0, 20) + ' [...]</span>';
							else return data.substring(0, 20) + ' [...]';
						}
					},
					{
						data: ratings_table + ".status",
						className: "dt-body-center",
						render: function(data, type, row, meta)
						{
							if (row[ratings_table].status == 'Unread') return '<span style="font-weight: bold;">' + lcp.l(data) + '</span>';
							else return lcp.l(data);
						}
					}, ],
					select: true,
					buttons: ratings_editor_buttons,
				});
				
			}
			else
			{
				lcp.preload({0: 'ratings_ajax_load_span'}, 'on', 3);
				ratings_datatable.api().ajax.reload(function(json)
				{
					lcp.preload({0: 'ratings_ajax_load_span'}, 'off', 3); //$('#myInput').val( json.lastInput );
				});
			}
			//$('#ratings_grid_wrapper').css({'width': parseInt($(window).width()) - 80 + 'px',});
		});
			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

		//$("#tabs-logs-a").on('click', function()
		$(document).on('click', '#tabs-logs-a', function()
		{

			if (!$.fn.DataTable.isDataTable('#logs_grid'))
			{
				var logs_table = lcp_db_prefix + lcp_module_name + '_logs';
				var logs_editor = new $.fn.dataTable.Editor(
				{
					//ajax: lcp_grid_path + 'logs',
					ajax:
					{
						url: lcp_grid_path + 'logs',
						type: 'POST',
						"data":
						{
							"session": lcp_session
						}
					},
					table: "#logs_grid",
					fields: [
					{
						label: lcp.l("Name:"),
						name: logs_table + ".name",
					}, ],
			i18n: {
            create: {
                button: lcp.l("New"),
                title:  lcp.l("Create new entry"),
                submit: lcp.l("Create")
            },
            edit: {
                button: lcp.l("Edit"),
                title:  lcp.l("Edit entry"),
                submit: lcp.l("Update")
            },
            remove: {
                button: lcp.l("Delete"),
                title:  lcp.l("Delete"),
                submit: lcp.l("Delete"),
                confirm: {
                    _: lcp.l("Are you sure you want to delete")+" %d "+lcp.l("entries?"),
                    1: lcp.l("Are sure you want to delete this entry?")
                }
            },
            error: {
                system: lcp.l("An error has occured, please contact the system administrator")
            },
            datetime: {
                previous: lcp.l('Previous'),
                next:     lcp.l('Next'),
                //months:   [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                //weekdays: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ]
            }
        }
				});
				var logs_editor_create_button = {};
				var logs_editor_edit_button = {};
				var logs_editor_remove_button = {
					extend: "remove",
					editor: logs_editor
				};
				var logs_editor_buttons = [];
				if (lcp_employee_is_superadmin == 'Y')
				{
					logs_editor_buttons.push(logs_editor_remove_button);
				}
				else
				{
					logs_editor_buttons = [];
				}
				logs_datatable = $('#logs_grid').dataTable(
				{
					dom: "Bfrtip",
					//"processing": true,
					//"serverSide": true,
					"scrollX": true,
					"pageLength": 10,
					"autoWidth": false,
					"language": {
		                "url": lcp_url+"libraries/datatables/translations/"+lcp_employee_iso+".txt"
		            },
					ajax:
					{
						url: lcp_grid_path + 'logs',
						type: 'POST',
						"data":
						{
							"session": lcp_session
						}
					},
					columns: [
					{
						data: logs_table + ".date_add",
						className: "dt-body-center"
					},
					{
						data: logs_table + ".message"
					}, ],
					select: true,
					buttons: logs_editor_buttons,
				});
				
			}
			else
			{
				lcp.preload({0: 'logs_ajax_load_span'}, 'on', 3);
				logs_datatable.api().ajax.reload(function(json)
				{
					lcp.preload({0: 'logs_ajax_load_span'}, 'off', 3); //$('#myInput').val( json.lastInput );
				});
			}
			//$('#logs_grid_wrapper').css({'width': parseInt($(window).width()) - 80 + 'px',});
		});	

		// END DATAGRID
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


	
	//$('#dialog_chat_a').on('click', function()
	$(document).on('click', '#dialog_chat_a', function()
	{
		//console.log('aaa');
		if ($('#dialog_chat').is(":visible"))
		{
			$("#dialog_chat").dialog("close");
		}
		else
		{
			$('#status_select').trigger('change'); // in caz ca e offline sa schimbe culoare de la chat in bubble

			$('#' + lcp_active_statistics_tab_link).trigger('click');

			$("#dialog_chat").dialog("open");
		}
		/*alos close mousetracking*/
		if ($('#dialog-mousetracking').is(":visible"))
		{
			$("#dialog-mousetracking").dialog("close");
		}
	});





	$(document).on('click', 'img[id^="emoticon_img_admin"]', function()
	{
		var key = (lcp_primary_settings.chat_type_admin == 'Popup') ? lcp_active_chat_tab : $('#active_emoticon_menu').val();
		
		if (lcp_primary_settings.chat_type_admin == 'Popup')
		{
			var textarea_val = $('#msg_admin').val();
			$('#msg_admin').val('');
			$('#msg_admin').val(textarea_val + $(this).prev().val());
		}
		else
		{
			var textarea_val = $('#msg_admin_'+key).val();
			$('#msg_admin_'+key).val('');
			$('#msg_admin_'+key).val(textarea_val + $(this).prev().val());
		}
		//console.log(key);
		$('#menu-emoticons').hide();
	});
	$(document).on('click', 'input[id^="accept_chat"]', function()
	{
		var key = $(this).attr('id').split('_')[2];
		var id_visitor = $('#id_visitor_' + key).val();
		var loader_div = (lcp_primary_settings.chat_type_admin == 'Popup') ? 'chatactionbuttons_ajax_load_span' : 'chatactionbuttons_ajax_load_span_'+key;
		var data = {
			'id_visitor': id_visitor,
			'internal': lcp_internal,
			'action': 'chatAcceptedFromStaff',
		};

		var params = {
			'load': 'chatAcceptedFromStaff',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: loader_div,
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			var res = JSON.parse(result);

			if (lcp_primary_settings.host_type == 'Self')
			{
				$('#awaiting_response_chat_dialogs').html(lcp.getHtmlCode('ajax.header_chats_counter.tpl', res));
				
				if (lcp_primary_settings.chat_type_admin == 'Popup')
					$("#ajax_chats_div").html(lcp.getHtmlCode('ajax.chats.tpl', res));
				else
				{
					$("#ajax_chats_div").html(lcp.getHtmlCode('ajax.chats_slide.tpl', res));

					if (!lcp.empty(res.response.active_pending_archives)) 
					{
						$.each(res.response.active_pending_archives, function( key, value ) 
						{
							// daca nu exista divul
							if ($('#ajax_chats_textarea_div_'+key).length == 0)
							{
								$('#ajax_chats_textarea_div').append('<td id="ajax_chats_textarea_div_'+key+'" style="padding-top: 0px; padding-right: 5px;"><div style="background-color: white; height: 60px; box-shadow: 0px 5px 5px 0px rgba(66,66,66,0.75); padding: 2px;">'+lcp.getHtmlCode('ajax.chats_slide_textarea.tpl', res, key)+'</div></td>');
							}
							else
							{
								if ($('#ajax_chats_textarea_div_'+key).html() == '')
									$('#ajax_chats_textarea_div_'+key).html(lcp.getHtmlCode('ajax.chats_slide_textarea.tpl', res, key));
							}

						});
					}
					else
					{
						$('#ajax_chats_textarea_div').html('');
					}
				}

				$("#ajax_onlineusers_div").html(lcp.getHtmlCode('ajax.onlineusers.tpl', res));
			}

			$('#ajax_chats_textarea_div').show();
			$('a[href="#tabs-chat-' + key + '"] >span').removeClass('blink-container');
			$('a[href="#tabs-chat-' + key + '"] >span').removeClass('blink');
			
			lcp.stopSound('newchat');
		}, true);
	});



	$('[id$="_languages"]').on('change', function()
	{
		var field = $(this).attr('id').split('_languages')[0];
		var iso_code = $(this).find('option:selected').text();
		$('[id^="' + field + '"]').hide();
		$('#' + field + '_' + iso_code).fadeIn();
		$(this).show();
		$('#' + field + '_' + iso_code).trigger('change'); //ca sa se schimbe in preview

	});
	$(document).on('click', 'input[id^="deny_chat"]', function()
	{
		var key = $(this).attr('id').split('_')[2];
		$('#remove_tab_' + key).trigger('click');
	});
	$(document).on('click', 'span[id^="minimize_tab"]', function()
	{
		var key = $(this).attr('id').split('_')[2];
		var is_visible = $('#content-'+key).is(":visible");
	
		$('#content-' + key).toggle();
		if ($('#ajax_chats_textarea_div_' + key).is('td') == false)
			$('#ajax_chats_textarea_div_' + key).toggle();

		if (is_visible)
		{
			$(this).removeClass('fa-chevron-down');
			$(this).addClass('fa-chevron-up');
			var admin_chat_toggled = 'down';
		}
		else
		{
			$(this).removeClass('fa-chevron-up');
			$(this).addClass('fa-chevron-down');
			var admin_chat_toggled = 'up';
		}

		$.localStorage.set('admin_chat_toggled_'+key, admin_chat_toggled);


	});
	$(document).on('click', 'span[id^="remove_tab"]', function()
	{
		var key = $(this).attr('id').split('_')[2];

		var id_visitor = $('#id_visitor_'+key).val();

		$.localStorage.set('lcp_active_chat_tab', 0);
		$.localStorage.set('lcp_active_chat_subtab', 'userchat_span_0');

		if (lcp_primary_settings.chat_type_admin == 'Popup')
		{
			var panelId = $(this).closest("li").remove().attr("aria-controls");
			$("#" + panelId).remove();
			$("#tabs-chat").tabs("refresh");
			if ($('#tabs-chat > ul > li').size() == 0)
			{
				$("#no_chats").show();

				$('#tabs-visitor-userchat-'+key+', #tabs-visitor-details, #tabs-visitor-visitedpageshistory, #tabs-visitor-geotracking, #tabs-visitor-archive, #tabs-visitor-messages, #tabs-visitor-ratings, #tabs-visitor-logs, #ajax_chats_textarea_div').hide();
			}
			else 
				$("#no_chats").hide();
		}
		else
		{	
			$('#id_chat_'+key).remove();
			$('#ajax_chats_textarea_div_'+key).remove();
		}
			
	
		var data = {
			'id_visitor': id_visitor,
			'internal': lcp_internal,
			'action': 'chatClosedFromStaff',
		};
		var params = {
			'load': 'chatClosedFromStaff',
			'divs': null,
			'params':
			{
				'data': data,
			},
		};
		lcp.stopSound('newchat');
		lcp.ajaxController(params, function(result)
		{
		}, true);
	});
	$(document).on('click', 'input[id^="invite_to_chat"]', function()
	{
		var key = $(this).attr('id').split('_')[3];
		var id_visitor = $('#id_visitor_' + key).val();

		var data = {
			'id_visitor': id_visitor,
		};

		var params = {
			'load': 'chatRequestFromStaff',
			'divs': null,
			'params':
			{
				'data': data,
			},
		};
		$(this).val('Invitation sent, please wait...');
		$(this).prop("disabled", true);

		lcp.ajaxController(params, function(result) {}, true);
	});

	$(document).on('click', '[id^="send_msg_admin_a"]', function()
	//$(document).on('click', '#send_msg_admin_a', function()
	{
		var key = (lcp_primary_settings.chat_type_admin == 'Popup') ? lcp_active_chat_tab : $(this).attr('id').split('_')[4];
		var staff_name = (lcp_primary_settings.show_names == 'Y') ? ' (' + lcp_internal + ')' : '';
		var msg_admin_val = (lcp_primary_settings.chat_type_admin == 'Popup') ? $('#msg_admin').val() : $('#msg_admin_'+key).val();

		var html = '<div style="margin-left: 40px;" align="right"> \
				<table border="0" cellspacing="0" cellpadding="0"> \
					<tr> \
						<td align="center" style="text-align: center !important; border-radius: 5px !important; background-color: #'+ lcp_primary_settings.chat_bubble_staff_background +' !important; padding: 3px !important;"> \
						'+ lcp.parseChatText( msg_admin_val ) +' \
						</td>';
			if (lcp_primary_settings.show_avatars == 'Y')
			{
				html +=	'<td valign="top" style="width: 40px; height: 40px; vertical-align: top; padding: 0px 0px 0px 2px;"> \
							<img style="vertical-align: top;" border="0" src="' + lcp_path + 'views/img/avatars/' + lcp_staff_avatar + '" width="40" height="40"> \
						</td>';
			}
			html += '</tr> \
				</table> \
				<table border="0" cellspacing="0" cellpadding="0"> \
					<tr> \
						<td style="margin: 0px; padding: 0px; text-align: left; color: #AAAAAA; font-size: x-small;">'+ lcp.l('Staff') + ' '+ staff_name + ': ' + lcp.getDateTime(false, false, false) +'</td> \
					</tr> \
				</table> \
				</div>';


		$('#chat_messages_' + key).append(html);

		$('#content-' + key).scrollTop($('#content-' + key).prop('scrollHeight'));

		var data = {
			'msg': msg_admin_val,
			'messages': html, //$('#chat_messages_'+key).html(),
			'id_visitor': $('#id_visitor_' + key).val(),
			'staff_avatar': lcp_staff_avatar,
			'action': 'chatMessageFromStaff',
		};
		var params = {
			'load': 'chatMessageFromStaff',
			'divs': null,
			'params':
			{
				'data': data,
			},
		};

		$('[id^="msg_admin"]').val('');

		lcp.ajaxController(params, function(result) {}, true);
	});


	$(document).on('keydown', 'input[id^="msg_admin"]', function(event)
	{
		var key = (lcp_primary_settings.chat_type_admin == 'Popup') ? lcp_active_chat_tab : $(this).attr('id').split('_')[2];
		//console.log(key);
		var keypressed = event.keyCode || event.which;
		try
		{
			if (keypressed == 13)
			{
				if (lcp_primary_settings.chat_type_admin == 'Popup')
					$('#send_msg_admin_a').trigger('click');
				else
					$('#send_msg_admin_a_'+key).trigger('click');

				return false;
			}
		}
		catch (e)
		{}
	});

	$(document).on('click', 'a[id^="show_hide_emoticons_admin"]', function()
	{
		var button_id = $(this).attr('id'); /*//console.log( $(e.currentTarget).attr('id') );*/
		$('#active_emoticon_menu').val(button_id.split('_')[4]);
		var position = $('#' + button_id).offset(); /*//console.log( position );*/
		var posX = position.left - $("#menu-emoticons").width();
		var posY = position.top - 150;
		$("#menu-emoticons").toggle();
		$("#menu-emoticons").offset(
		{
			left: posX,
			top: posY
		});
	});
	$('#delete_settings').on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;
		var q = confirm(lcp.l("Are you sure you want to delete the setting") + " `" + $('#settings option:selected').text() + "`?");
		if (q == true)
		{
			var data = {
				'id_setting': $('#settings').val(),
			};
			/*ajax aici:*/
			var params = {
				'load': 'deleteSettings',
				'divs':
				{
					0: 'settings_ajax_span',
				},
				'params':
				{
					'data': data,
				},
				'preloader':
				{
					'divs':
					{
						0: 'settings_ajax_load_span',
					},
					'type': 2,
					'style': 3,
				}
			};
			lcp.ajaxController(params, function(result)
			{
				var res = JSON.parse(result);
				if (res.success == true) $("#settings").trigger("change");
				else alert(lcp.l('You can`t delete the settings in DEMO MODE!'));
			});
		}
		else
		{
			return false;
		}
	});
	$(document).on('blur', 'input[id^="lcp_sync"]', function()
	{
		$(this).parseNumber(
		{
			format: "###0",
			locale: "us"
		});
		$(this).formatNumber(
		{
			format: "###0",
			locale: "us"
		});
		if ($(this).val() == '' || $(this).val() == 0) $(this).val(1);
	});
	
	$("#lcp_clear_database").on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;
		var q = confirm(lcp.l("Are you sure you want to clear the database?"));
		if (q != true) return false;
		if (lcp_employee_is_superadmin != 'Y') return alert(lcp.l("Only the admin can clear the database!"));
		var params = {
			'load': 'clearDatabase',
			'divs': null,
			'params':
			{
				'data': null,
			},
			'preloader':
			{
				'divs':
				{
					0: 'clear_database_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			alert(lcp.l('The database was succesfully cleared!'));
		});
	});

	
 	$(".tabs-menu a").click(function(event) {

        event.preventDefault();
        $(this).siblings().removeClass("active");
        $(this).addClass("active");
        var tab = $(this).attr("href");
        $(".tab-content").not(tab).css("display", "none");
        $(tab).fadeIn();
    });


 	$('#tabs-translations-a').on('click', function() {
 		$('#translations').trigger('change');
 	});

	$(document).on('change', "#translations", function()
	{
		var data = {
			'iso_code': $('#translations option:selected').text(),
		};
		/*fac request ajax sa iau setarile*/
		var params = {
			'load': 'getTranslations',
			'divs': 
			{
				0: 'ajax_translations_div',
			},
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'lang_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			//var res = JSON.parse(result);
			//$("#ajax_translations_div").html(res.response['ajax.translations.tpl']);
		});
	});


 	$("#save_translations").on('click', function()
	{

		var section_file_name = [],
			section_text_from_files = [],
			language_variables = [];

		$("input[name='section_file_name[]']").each(function() {
		    section_file_name.push($(this).val());
		});
		$("input[name='section_text_from_files[]']").each(function() {
		    section_text_from_files.push($(this).val());
		});
		$("textarea[name='language_variables[]']").each(function() {
		    language_variables.push($(this).val());
		});



		var data = {
			'iso_code': $('#translations option:selected').text(),
			'section_file_name': section_file_name,
			'section_text_from_files': section_text_from_files,
			'language_variables': language_variables,
		};

		var params = {
			'load': 'saveTranslations',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'lang_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};

		lcp.ajaxController(params, function(result)
		{
			$('#translations').trigger('change');
			var res = JSON.parse(result);
			if (res.success == true)
				alert(lcp.l('The save was succesfull!'));
			//$("#ajax_translations_div").html(res.response['ajax.translations.tpl']);
		});
	});

	$("#save_settings").on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;

		var offline_header = {},
			online_header = {},
			offline_welcome_message = {},
			online_welcome_message = {},
			iso_codes = {},
			iso_code;
		var i = 0;
		$('input[id^="offline_header"]').each(function()
		{
			iso_code = $(this).attr('id').split('_')[2];
			offline_header[iso_code] = $(this).val();
			iso_codes[i] = iso_code;
			i++;
		});
		$('input[id^="online_header"]').each(function()
		{
			iso_code = $(this).attr('id').split('_')[2];
			online_header[iso_code] = $(this).val();
		});
		$('textarea[id^="offline_welcome_message"]').each(function()
		{
			iso_code = $(this).attr('id').split('_')[3];
			offline_welcome_message[iso_code] = $(this).val();
		});
		$('textarea[id^="online_welcome_message"]').each(function()
		{
			iso_code = $(this).attr('id').split('_')[3];
			online_welcome_message[iso_code] = $(this).val();
		});
		var host_type = $('input[type=radio][name=host_type]:checked').val();
		
		var name_field_online = $('input[type=radio][name=name_field_online]:checked').val();
		var name_field_online_mandatory = $('input[name=name_field_online_mandatory]:checked').val();
		var name_field_offline = $('input[type=radio][name=name_field_offline]:checked').val();
		var name_field_offline_mandatory = $('input[name=name_field_offline_mandatory]:checked').val();
		
		var email_field_online = $('input[type=radio][name=email_field_online]:checked').val();
		var email_field_online_mandatory = $('input[name=email_field_online_mandatory]:checked').val();
		var email_field_offline = $('input[type=radio][name=email_field_offline]:checked').val();
		var email_field_offline_mandatory = $('input[name=email_field_offline_mandatory]:checked').val();
		
		var phone_field_online = $('input[type=radio][name=phone_field_online]:checked').val();
		var phone_field_online_mandatory = $('input[name=phone_field_online_mandatory]:checked').val();
		var phone_field_offline = $('input[type=radio][name=phone_field_offline]:checked').val();
		var phone_field_offline_mandatory = $('input[name=phone_field_offline_mandatory]:checked').val();
		
		var department_field_online = $('input[type=radio][name=department_field_online]:checked').val();
		var department_field_online_mandatory = $('input[name=department_field_online_mandatory]:checked').val();
		var department_field_offline = $('input[type=radio][name=department_field_offline]:checked').val();
		var department_field_offline_mandatory = $('input[name=department_field_offline_mandatory]:checked').val();

		var question_field_online = $('input[type=radio][name=question_field_online]:checked').val();
		var question_field_online_mandatory = $('input[name=question_field_online_mandatory]:checked').val();
		var question_field_offline = $('input[type=radio][name=question_field_offline]:checked').val();
		var question_field_offline_mandatory = $('input[name=question_field_offline_mandatory]:checked').val();

		var chat_type = $('input[type=radio][name=chat_type]:checked').val();
		var chat_type_admin = $('input[type=radio][name=chat_type_admin]:checked').val();
		var slide_with_image = $('input[type=radio][name=slide_with_image]:checked').val();
		var orientation = ($('input[type=radio][name=chat_type]:checked').val() == 'Slide') ? $('#orientation_slide_select').val() : $('#orientation_popup_select').val();
		var start_minimized = $('input[type=radio][name=start_minimized]:checked').val();
		var hide_when_offline = $('input[type=radio][name=hide_when_offline]:checked').val();
		var show_names = $('input[type=radio][name=show_names]:checked').val();
		var show_avatars = $('input[type=radio][name=show_avatars]:checked').val();
		var visitors_can_upload_files = $('input[type=radio][name=visitors_can_upload_files]:checked').val();
		var popup_alert_on_income_chats = $('input[type=radio][name=popup_alert_on_income_chats]:checked').val();
		var staff_qualification = $('input[type=radio][name=staff_qualification]:checked').val();
		var new_chat_rings_to = $('input[type=radio][name=new_chat_rings_to]:checked').val();
		var fixed_position = $('input[type=radio][name=fixed_position]:checked').val();
		var data = {
			'id_setting': $('#settings').val(),
			'id_iconset': $('#iconsets').val(),
			'id_theme': $('#themes').val(),
			'host_type': host_type,
			'offline_messages_go_to': $('#offline_messages_go_to').val(),
			'new_chat_sound': $('#new_chat_sound').val(),
			'new_message_sound': $('#new_message_sound').val(),

			'name_field_online': name_field_online,
			'name_field_online_mandatory': name_field_online_mandatory,
			'name_field_offline': name_field_offline,
			'name_field_offline_mandatory': name_field_offline_mandatory,

			'email_field_online': email_field_online,
			'email_field_online_mandatory': email_field_online_mandatory,
			'email_field_offline': email_field_offline,
			'email_field_offline_mandatory': email_field_offline_mandatory,

			'phone_field_online': phone_field_online,
			'phone_field_online_mandatory': phone_field_online_mandatory,
			'phone_field_offline': phone_field_offline,
			'phone_field_offline_mandatory': phone_field_offline_mandatory,

			'department_field_online': department_field_online,
			'department_field_online_mandatory': department_field_online_mandatory,
			'department_field_offline': department_field_offline,
			'department_field_offline_mandatory': department_field_offline_mandatory,

			'question_field_online': question_field_online,
			'question_field_online_mandatory': question_field_online_mandatory,
			'question_field_offline': question_field_offline,
			'question_field_offline_mandatory': question_field_offline_mandatory,

			'chat_type': chat_type,
			'chat_type_admin': chat_type_admin,
			'slide_with_image': slide_with_image,
			'orientation': orientation,
			'offset': $('#offset').val(),
			'start_minimized': start_minimized,
			'hide_when_offline': hide_when_offline,
			'show_names': show_names,
			'show_avatars': show_avatars,
			'visitors_can_upload_files': visitors_can_upload_files,
			'popup_alert_on_income_chats': popup_alert_on_income_chats,
			'start_new_chat_after': $('#lcp_start_new_chat_after').val(),
			'staff_qualification': staff_qualification,
			'new_chat_rings_to': new_chat_rings_to,
			'fixed_position': fixed_position,
			'offline_header_message': offline_header,
			'online_header_message': online_header,
			'offline_welcome_message': offline_welcome_message,
			'online_welcome_message': online_welcome_message,
			'iso_codes': iso_codes,
			'sync_chat_interval_backend': $('#lcp_sync_chat_interval_backend').val(),
			'sync_chat_interval_frontend': $('#lcp_sync_chat_interval_frontend').val(),
			'realm_id': $('#lcp_realm_id').val(),
			'realm_key': $('#lcp_realm_key').val(),
		};
		var params = {
			'load': 'saveSettings',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'settings_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};

		//console.log('email_field_offline_mandatory '+$('input[name=email_field_offline_mandatory]:checked').val());

		lcp.ajaxController(params, function(result)
		{
			var res = JSON.parse(result);
			if (res.success == true)
			{
				/*salvez si iconset*/
				$("#save_iconset").trigger("click");
				/*salvez si theme*/
				$("#save_theme").trigger("click");
				alert(lcp.l('The save was successful! You need to refresh the page in order to take effect!'));
			}
			else
			{
				alert(lcp.l('You can`t save settings in DEMO MODE!'));
			}
			
		});

	});
	$("#save_as_settings").on('click', function()
	{
		var settings_name = prompt(lcp.l('Enter name'), 'mycustomsettings1');
		if (settings_name != null)
		{
			if (lcp.validateInput(settings_name) != false)
			{
				var offline_header = {},
					online_header = {},
					offline_welcome_message = {},
					online_welcome_message = {},
					iso_codes = {},
					iso_code;
				var i = 0;
				$('input[id^="offline_header"]').each(function()
				{
					iso_code = $(this).attr('id').split('_')[2];
					offline_header[iso_code] = $(this).val();
					iso_codes[i] = iso_code;
					i++;
				});
				$('input[id^="online_header"]').each(function()
				{
					iso_code = $(this).attr('id').split('_')[2];
					online_header[iso_code] = $(this).val();
				});
				$('textarea[id^="offline_welcome_message"]').each(function()
				{
					iso_code = $(this).attr('id').split('_')[3];
					offline_welcome_message[iso_code] = $(this).val();
				});
				$('textarea[id^="online_welcome_message"]').each(function()
				{
					iso_code = $(this).attr('id').split('_')[3];
					online_welcome_message[iso_code] = $(this).val();
				});
				var host_type = $('input[type=radio][name=host_type]:checked').val();

				var name_field_online = $('input[type=radio][name=name_field_online]:checked').val();
				var name_field_online_mandatory = $('input[name=name_field_online_mandatory]:checked').val();
				var name_field_offline = $('input[type=radio][name=name_field_offline]:checked').val();
				var name_field_offline_mandatory = $('input[name=name_field_offline_mandatory]:checked').val();
				
				var email_field_online = $('input[type=radio][name=email_field_online]:checked').val();
				var email_field_online_mandatory = $('input[name=email_field_online_mandatory]:checked').val();
				var email_field_offline = $('input[type=radio][name=email_field_offline]:checked').val();
				var email_field_offline_mandatory = $('input[name=email_field_offline_mandatory]:checked').val();
				
				var phone_field_online = $('input[type=radio][name=phone_field_online]:checked').val();
				var phone_field_online_mandatory = $('input[name=phone_field_online_mandatory]:checked').val();
				var phone_field_offline = $('input[type=radio][name=phone_field_offline]:checked').val();
				var phone_field_offline_mandatory = $('input[name=phone_field_offline_mandatory]:checked').val();
				
				var department_field_online = $('input[type=radio][name=department_field_online]:checked').val();
				var department_field_online_mandatory = $('input[name=department_field_online_mandatory]:checked').val();
				var department_field_offline = $('input[type=radio][name=department_field_offline]:checked').val();
				var department_field_offline_mandatory = $('input[name=department_field_offline_mandatory]:checked').val();

				var question_field_online = $('input[type=radio][name=question_field_online]:checked').val();
				var question_field_online_mandatory = $('input[name=question_field_online_mandatory]:checked').val();
				var question_field_offline = $('input[type=radio][name=question_field_offline]:checked').val();
				var question_field_offline_mandatory = $('input[name=question_field_offline_mandatory]:checked').val();

				var chat_type = $('input[type=radio][name=chat_type]:checked').val();
				var chat_type_admin = $('input[type=radio][name=chat_type_admin]:checked').val();
				var slide_with_image = $('input[type=radio][name=slide_with_image]:checked').val();
				var orientation = ($('input[type=radio][name=chat_type]:checked').val() == 'Slide') ? $('#orientation_slide_select').val() : $('#orientation_popup_select').val();
				var start_minimized = $('input[type=radio][name=start_minimized]:checked').val();
				var hide_when_offline = $('input[type=radio][name=hide_when_offline]:checked').val();
				var show_names = $('input[type=radio][name=show_names]:checked').val();
				var show_avatars = $('input[type=radio][name=show_avatars]:checked').val();
				var visitors_can_upload_files = $('input[type=radio][name=visitors_can_upload_files]:checked').val();
				var popup_alert_on_income_chats = $('input[type=radio][name=popup_alert_on_income_chats]:checked').val();
				var staff_qualification = $('input[type=radio][name=staff_qualification]:checked').val();
				var new_chat_rings_to = $('input[type=radio][name=new_chat_rings_to]:checked').val();
				var fixed_position = $('input[type=radio][name=fixed_position]:checked').val();
				var data = {
					'id_iconset': $('#iconsets').val(),
					'id_theme': $('#themes').val(),
					'name': settings_name,
					'host_type': host_type,
					'offline_messages_go_to': $('#offline_messages_go_to').val(),
					'new_chat_sound': $('#new_chat_sound').val(),
					'new_message_sound': $('#new_message_sound').val(),
					
					'name_field_online': name_field_online,
					'name_field_online_mandatory': name_field_online_mandatory,
					'name_field_offline': name_field_offline,
					'name_field_offline_mandatory': name_field_offline_mandatory,

					'email_field_online': email_field_online,
					'email_field_online_mandatory': email_field_online_mandatory,
					'email_field_offline': email_field_offline,
					'email_field_offline_mandatory': email_field_offline_mandatory,

					'phone_field_online': phone_field_online,
					'phone_field_online_mandatory': phone_field_online_mandatory,
					'phone_field_offline': phone_field_offline,
					'phone_field_offline_mandatory': phone_field_offline_mandatory,

					'department_field_online': department_field_online,
					'department_field_online_mandatory': department_field_online_mandatory,
					'department_field_offline': department_field_offline,
					'department_field_offline_mandatory': department_field_offline_mandatory,

					'question_field_online': question_field_online,
					'question_field_online_mandatory': question_field_online_mandatory,
					'question_field_offline': question_field_offline,
					'question_field_offline_mandatory': question_field_offline_mandatory,

					'chat_type': chat_type,
					'chat_type_admin': chat_type_admin,
					'slide_with_image': slide_with_image,
					'orientation': orientation,
					'offset': $('#offset').val(),
					'start_minimized': start_minimized,
					'hide_when_offline': hide_when_offline,
					'show_names': show_names,
					'show_avatars': show_avatars,
					'visitors_can_upload_files': visitors_can_upload_files,
					'popup_alert_on_income_chats': popup_alert_on_income_chats,
					'start_new_chat_after': $('#lcp_start_new_chat_after').val(),
					'staff_qualification': staff_qualification,
					'new_chat_rings_to': new_chat_rings_to,
					'fixed_position': fixed_position,
					'offline_header_message': offline_header,
					'online_header_message': online_header,
					'offline_welcome_message': offline_welcome_message,
					'online_welcome_message': online_welcome_message,
					'iso_codes': iso_codes,
					'sync_chat_interval_backend': $('#lcp_sync_chat_interval_backend').val(),
					'sync_chat_interval_frontend': $('#lcp_sync_chat_interval_frontend').val(),
					'realm_id': $('#lcp_realm_id').val(),
					'realm_key': $('#lcp_realm_key').val(),
				};
				var params = {
					'load': 'saveAsSettings',
					'divs':
					{
						0: 'settings_ajax_span',
					},
					'params':
					{
						'data': data,
					},
					'preloader':
					{
						'divs':
						{
							0: 'settings_ajax_load_span',
						},
						'type': 2,
						'style': 3,
					}
				};
				lcp.ajaxController(params, function(result)
				{
					/*salvez si iconset*/
					$("#save_iconset").trigger("click");
					/*salvez si theme*/
					$("#save_theme").trigger("click");
					/*$('#settings').val(template_name);*/
					$("#settings option").each(function()
					{
						if ($(this).text() == settings_name)
						{
							$(this).attr('selected', 'selected');
						}
					});
					alert(lcp.l('The save was successful! You need to refresh the page in order to take effect!'));
				});
			}
			else
			{
				alert(lcp.l('You need to use only aplhanumeric characters.'));
			}
		}
	});
	$('#delete_theme').on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;
		var q = confirm(lcp.l("Are you sure you want to delete the theme") + " `" + $('#themes option:selected').text() + "`?");
		if (q == true)
		{
			var data = {
				'id_theme': $('#themes').val(),
			};
			/*ajax aici:*/
			var params = {
				'load': 'deleteTheme',
				'divs':
				{
					0: 'themes_ajax_span',
				},
				'params':
				{
					'data': data,
				},
				'preloader':
				{
					'divs':
					{
						0: 'themes_ajax_load_span',
					},
					'type': 2,
					'style': 3,
				}
			};
			lcp.ajaxController(params, function(result)
			{
				var res = JSON.parse(result);
				if (res.success == true) $("#themes").trigger("change");
				else alert(lcp.l('You can`t delete the settings in DEMO MODE!'));
			});
		}
		else
		{
			return false;
		}
	});
	$("#save_theme").on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;

		var chat_box_border = $('input[type=radio][name=chat_box_border]:checked').val();
		var data = {
			'id_theme': $('#themes').val(),
			'width': $('#chat_width').val(),
			'height': $('#chat_height').val(),
			'corners_radius': $('#corners_radius').val(),
			'chat_box_background': $('#chat_box_background').val(),
			'chat_box_foreground': $('#chat_box_foreground').val(),
			'chat_bubble_staff_background': $('#chat_bubble_staff_background').val(),
			'chat_bubble_client_background': $('#chat_bubble_client_background').val(),
			'chat_box_border': chat_box_border,
			'chat_box_border_color': $('#chat_box_border_color').val(),
			'header_offline_background': $('#header_offline_background').val(),
			'header_online_background': $('#header_online_background').val(),
			'header_offline_foreground': $('#header_offline_foreground').val(),
			'header_online_foreground': $('#header_online_foreground').val(),
			'submit_button_background': $('#submit_button_background').val(),
			'submit_button_foreground': $('#submit_button_foreground').val(),
		};
		var params = {
			'load': 'saveTheme',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'themes_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			var res = JSON.parse(result);
			if (res.success == true) return;
			else alert(lcp.l('You can`t save settings in DEMO MODE!'));
		});
		
	});
	$("#save_as_theme").on('click', function()
	{
		var theme_name = prompt(lcp.l('Enter name'), 'mycustomtheme1');
		if (theme_name != null)
		{
			if (lcp.validateInput(theme_name) != false)
			{
				var chat_box_border = $('input[type=radio][name=chat_box_border]:checked').val();
				var data = {
					'name': theme_name,
					'width': $('#chat_width').val(),
					'height': $('#chat_height').val(),
					'corners_radius': $('#corners_radius').val(),
					'chat_box_background': $('#chat_box_background').val(),
					'chat_box_foreground': $('#chat_box_foreground').val(),
					'chat_bubble_staff_background': $('#chat_bubble_staff_background').val(),
					'chat_bubble_client_background': $('#chat_bubble_client_background').val(),
					'chat_box_border': chat_box_border,
					'chat_box_border_color': $('#chat_box_border_color').val(),
					'header_offline_background': $('#header_offline_background').val(),
					'header_online_background': $('#header_online_background').val(),
					'header_offline_foreground': $('#header_offline_foreground').val(),
					'header_online_foreground': $('#header_online_foreground').val(),
					'submit_button_background': $('#submit_button_background').val(),
					'submit_button_foreground': $('#submit_button_foreground').val(),
				};
				var params = {
					'load': 'saveAsTheme',
					'divs':
					{
						0: 'themes_ajax_span',
					},
					'params':
					{
						'data': data,
					},
					'preloader':
					{
						'divs':
						{
							0: 'themes_ajax_load_span',
						},
						'type': 2,
						'style': 3,
					}
				};
				lcp.ajaxController(params, function(result)
				{
					$("#themes option").each(function()
					{
						if ($(this).text() == theme_name)
						{
							$(this).attr('selected', 'selected');
						}
					});
				});
			}
			else
			{
				alert(lcp.l('You need to use only aplhanumeric characters.'));
			}
		}
	});
	$('#delete_iconset').on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;
		var q = confirm(l("Are you sure you want to delete the iconset") + " `" + $('#iconsets option:selected').text() + "`?");
		if (q == true)
		{
			var data = {
				'id_iconset': $('#iconsets').val(),
			};
			/*ajax aici:*/
			var params = {
				'load': 'deleteIconset',
				'divs':
				{
					0: 'iconsets_ajax_span',
				},
				'params':
				{
					'data': data,
				},
				'preloader':
				{
					'divs':
					{
						0: 'iconsets_ajax_load_span',
					},
					'type': 2,
					'style': 3,
				}
			};
			lcp.ajaxController(params, function(result)
			{
				var res = JSON.parse(result);
				if (res.success == true) $("#iconsets").trigger("change");
				else alert(lcp.l('You can`t delete the settings in DEMO MODE!'));
			});
		}
		else
		{
			return false;
		}
	});
	$("#save_iconset").on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;
		
		var offline_img = {},
			online_img = {},
			iso_codes = {},
			iso_code,
			offline_img_index,
			offline_img_file,
			online_img_index,
			online_img_file;
		var i = 0;
		$('img[id^="offline_img"]').each(function()
		{
			offline_img_index = $(this).attr('src').lastIndexOf("/") + 1;
			offline_img_file = $(this).attr('src').substr(offline_img_index);
			iso_code = $(this).attr('id').split('_')[2];
			offline_img[iso_code] = offline_img_file;
			iso_codes[i] = iso_code;
			i++;
		});
		$('img[id^="online_img"]').each(function()
		{
			online_img_index = $(this).attr('src').lastIndexOf("/") + 1;
			online_img_file = $(this).attr('src').substr(online_img_index);
			iso_code = $(this).attr('id').split('_')[2];
			online_img[iso_code] = online_img_file;
		});

		var data = {
			'id_iconset': $('#iconsets').val(),
			'offline_img': offline_img,
			'online_img': online_img,
			'iso_codes': iso_codes,
		};
		var params = {
			'load': 'saveIconset',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'iconsets_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			var res = JSON.parse(result);
			if (res.success == true) return;
			else alert(lcp.l('You can`t save settings in DEMO MODE!'));
		});
		
	});
	$("#save_as_iconset").on('click', function()
	{
		var iconset_name = prompt(lcp.l('Enter name'), 'mycustomiconset1');
		if (iconset_name != null)
		{
			if (lcp.validateInput(iconset_name) != false)
			{
				var offline_img = {},
					online_img = {},
					iso_codes = {},
					iso_code,
					offline_img_index,
					offline_img_file,
					online_img_index,
					online_img_file;
				var i = 0;
				$('img[id^="offline_img"]').each(function()
				{
					offline_img_index = $(this).attr('src').lastIndexOf("/") + 1;
					offline_img_file = $(this).attr('src').substr(offline_img_index);
					iso_code = $(this).attr('id').split('_')[2];
					offline_img[iso_code] = offline_img_file;
					iso_codes[i] = iso_code;
					i++;
				});
				$('img[id^="online_img"]').each(function()
				{
					online_img_index = $(this).attr('src').lastIndexOf("/") + 1;
					online_img_file = $(this).attr('src').substr(online_img_index);
					iso_code = $(this).attr('id').split('_')[2];
					online_img[iso_code] = online_img_file;
				});
				var data = {
					'name': iconset_name,
					'offline_img': offline_img,
					'online_img': online_img,
					'iso_codes': iso_codes,
				};
				var params = {
					'load': 'saveAsIconset',
					'divs':
					{
						0: 'iconsets_ajax_span',
					},
					'params':
					{
						'data': data,
					},
					'preloader':
					{
						'divs':
						{
							0: 'iconsets_ajax_load_span',
						},
						'type': 2,
						'style': 3,
					}
				};
				lcp.ajaxController(params, function(result)
				{
					$("#iconsets option").each(function()
					{
						if ($(this).text() == iconset_name)
						{
							$(this).attr('selected', 'selected');
						}
					});
				});
			}
			else
			{
				alert(lcp.l('You need to use only aplhanumeric characters.'));
			}
		}
	});
	$(document).on('click', "#messages_reply_send_a", function()
	{
		var data = {
			'id_message': lcp_messages_id,
			'messages_reply': $('#messages_reply').val(),
		};
		var params = {
			'load': 'replyToMessage',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'statistics_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			var res = JSON.parse(result);
			try
			{
				messages_datatable.api().ajax.reload();
			}
			catch (e)
			{}
			alert(lcp.l('The reply has been succesfully sent!'))
		});
	});

	$(document).on('click', "#lcp_ticket_save_a", function()
	{
		//$(this).prop('disabled', true);
		var data = {
			'id_ticket': lcp_id_ticket,
			'id_department': $('#lcp_ticket_department').val(),
			'subject': $('#lcp_ticket_subject').val(),
			'status': $('#lcp_ticket_status').val(),
			'priority': $('#lcp_ticket_priority').val(),
		};
		var params = {
			'load': 'updateTicket',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'statistics_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			tickets_datatable.api().ajax.reload();
			alert(lcp.l('The ticket was updated!'));
		});
	});

	$(document).on('click', "#lcp_ticket_add_reply_a", function()
	{
		var data = {
			'id_ticket': lcp_id_ticket,
			'id_staffprofile': lcp_ticket_id_staffprofile,
			'id_customer': lcp_ticket_id_customer,
			'reply_from': 'Staff',
			'message': $('#lcp_ticket_reply_textarea').val(),
		};
		var params = {
			'load': 'addTicketReply',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'statistics_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			var data2 = {
				'from' : 'Staff',
				'id_ticket': lcp_id_ticket,
			};
			var params2 = {
				'load': 'getTicket',
				'divs': 
				{
					0: 'ajax_ticket_details_div',
				},
				'params':
				{
					'data': data2,
				},
				'preloader':
				{
					'divs':
					{
						0: 'statistics_ajax_load_span',
					},
					'type': 2,
					'style': 3,
				}
			};
			lcp.ajaxController(params2, function(result2)
			{

			});
			tickets_datatable.api().ajax.reload();

		});
	});

	$(document).on('change', 'input[id^="departments_sp"]', function()
	{
		var text = '';
		$('input[id^="departments_sp"]').each(function()
		{
			/*console.log( $(this).val() );*/
			if ($(this).prop('checked') == true)
			{
				text += $(this).val() + ',';
			}
		});
		$("#departments_ids_sp").val(text.substr(0, text.length - 1));
	});
	$(document).on('change', "#themes", function()
	{
		var data = {
			'id_theme': $('#themes').val(),
		};
		/*fac request ajax sa iau setarile*/
		var params = {
			'load': 'getTheme',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'themes_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			var res = JSON.parse(result);
			$("#chat_width").val(res.width).trigger('change');
			$("#chat_height").val(res.height).trigger('change');
			$("#corners_radius").val(res.corners_radius).trigger('change');
			$('input[type=radio][name=chat_box_border]').val([res.chat_box_border]).filter('[value=' + res.chat_box_border + ']').prop('checked', true).trigger('change');
			$("#chat_box_border_color").val(res.chat_box_border_color).trigger('focus').trigger('blur').trigger('change');
			$("#chat_box_background").val(res.chat_box_background).trigger('focus').trigger('blur').trigger('change');
			$("#chat_box_foreground").val(res.chat_box_foreground).trigger('focus').trigger('blur').trigger('change');
			$("#chat_bubble_staff_background").val(res.chat_bubble_staff_background).trigger('focus').trigger('blur').trigger('change');
			$("#chat_bubble_client_background").val(res.chat_bubble_client_background).trigger('focus').trigger('blur').trigger('change');
			$("#header_offline_background").val(res.header_offline_background).trigger('focus').trigger('blur').trigger('change');
			$("#header_online_background").val(res.header_online_background).trigger('focus').trigger('blur').trigger('change');
			$("#header_offline_foreground").val(res.header_offline_foreground).trigger('focus').trigger('blur').trigger('change');
			$("#header_online_foreground").val(res.header_online_foreground).trigger('focus').trigger('change');
			$("#submit_button_background").val(res.submit_button_background).trigger('focus').trigger('blur').trigger('change');
			$("#submit_button_foreground").val(res.submit_button_foreground).trigger('focus').trigger('blur').trigger('change');
		});
	});
	$(document).on('change', "#iconsets", function()
	{
		var data = {
			'id_iconset': $('#iconsets').val(),
		};
		/*fac request ajax sa iau setarile*/
		var params = {
			'load': 'getIconset',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'iconsets_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			var res = JSON.parse(result);
			for (var k in res.online_img) $("#online_img_" + k).attr('src', lcp_path + 'views/img/iconsets/' + res.online_img[k]);
			for (var k in res.offline_img) $("#offline_img_" + k).attr('src', lcp_path + 'views/img/iconsets/' + res.offline_img[k]);
			$('[id$="img_languages"]').each(function()
			{
				$(this).val($(this).find('option:first').val()).trigger('change');
			});
		});
	});
	$(document).on('change', "#settings", function()
	{
		var data = {
			'id_setting': $('#settings').val(),
		};
		/*fac request ajax sa iau setarile*/
		var params = {
			'load': 'getSetting',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'settings_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			try
			{
				var res = JSON.parse(result);
				//console.log(res);
				$('input[type=radio][name=host_type]').val([res.host_type]).filter('[value=' + res.host_type + ']').prop('checked', true).trigger('change');
				$("#offline_messages_go_to").val(res.offline_messages_go_to);
				$("#new_chat_sound").val(res.new_chat_sound);
				$("#new_message_sound").val(res.new_message_sound);
				
				$('input[type=radio][name=name_field_online]').val([res.name_field_online]).filter('[value=' + res.name_field_online + ']').prop('checked', true).trigger('change');
				$('input[name=name_field_online_mandatory]').val([res.name_field_online_mandatory]).filter('[value=' + res.name_field_online_mandatory + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=name_field_offline]').val([res.name_field_offline]).filter('[value=' + res.name_field_offline + ']').prop('checked', true).trigger('change');
				$('input[name=name_field_offline_mandatory]').val([res.name_field_offline_mandatory]).filter('[value=' + res.name_field_offline_mandatory + ']').prop('checked', true).trigger('change');

				$('input[type=radio][name=email_field_online]').val([res.email_field_online]).filter('[value=' + res.email_field_online + ']').prop('checked', true).trigger('change');
				$('input[name=email_field_online_mandatory]').val([res.email_field_online_mandatory]).filter('[value=' + res.email_field_online_mandatory + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=email_field_offline]').val([res.email_field_offline]).filter('[value=' + res.email_field_offline + ']').prop('checked', true).trigger('change');
				$('input[name=email_field_offline_mandatory]').val([res.email_field_offline_mandatory]).filter('[value=' + res.email_field_offline_mandatory + ']').prop('checked', true).trigger('change');

				$('input[type=radio][name=phone_field_online]').val([res.phone_field_online]).filter('[value=' + res.phone_field_online + ']').prop('checked', true).trigger('change');
				$('input[name=phone_field_online_mandatory]').val([res.phone_field_online_mandatory]).filter('[value=' + res.phone_field_online_mandatory + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=phone_field_offline]').val([res.phone_field_offline]).filter('[value=' + res.phone_field_offline + ']').prop('checked', true).trigger('change');
				$('input[name=phone_field_offline_mandatory]').val([res.phone_field_offline_mandatory]).filter('[value=' + res.phone_field_offline_mandatory + ']').prop('checked', true).trigger('change');

				$('input[type=radio][name=department_field_online]').val([res.department_field_online]).filter('[value=' + res.department_field_online + ']').prop('checked', true).trigger('change');
				$('input[name=department_field_online_mandatory]').val([res.department_field_online_mandatory]).filter('[value=' + res.department_field_online_mandatory + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=department_field_offline]').val([res.department_field_offline]).filter('[value=' + res.department_field_offline + ']').prop('checked', true).trigger('change');
				$('input[name=department_field_offline_mandatory]').val([res.department_field_offline_mandatory]).filter('[value=' + res.department_field_offline_mandatory + ']').prop('checked', true).trigger('change');

				$('input[type=radio][name=question_field_online]').val([res.question_field_online]).filter('[value=' + res.question_field_online + ']').prop('checked', true).trigger('change');
				$('input[name=question_field_online_mandatory]').val([res.question_field_online_mandatory]).filter('[value=' + res.question_field_online_mandatory + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=question_field_offline]').val([res.question_field_offline]).filter('[value=' + res.question_field_offline + ']').prop('checked', true).trigger('change');
				$('input[name=question_field_offline_mandatory]').val([res.question_field_offline_mandatory]).filter('[value=' + res.question_field_offline_mandatory + ']').prop('checked', true).trigger('change');
				
				$('input[type=radio][name=chat_type]').val([res.chat_type]).filter('[value=' + res.chat_type + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=chat_type_admin]').val([res.chat_type_admin]).filter('[value=' + res.chat_type_admin + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=slide_with_image]').val([res.slide_with_image]).filter('[value=' + res.slide_with_image + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=hide_when_offline]').val([res.hide_when_offline]).filter('[value=' + res.hide_when_offline + ']').prop('checked', true).trigger('change');
				if (res.chat_type == 'Slide')
				{
					$('input[type=radio][name=fixed_position]').prop('disabled', true).val(['Y']).filter('[value=Y]').prop('checked', true).trigger('change');
					$("#orientation_slide_select").val(res.orientation);
				}
				else
				{
					$("#iconsets").val(res.id_iconset).trigger('change');
					$('input[type=radio][name=fixed_position]').prop('disabled', false).val([res.fixed_position]).filter('[value=' + res.fixed_position + ']').prop('checked', true).trigger('change');
					$("#orientation_popup_select").val(res.orientation);
				}
				$('input[type=radio][name=show_names]').val([res.show_names]).filter('[value=' + res.show_names + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=show_avatars]').val([res.show_avatars]).filter('[value=' + res.show_avatars + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=visitors_can_upload_files]').val([res.visitors_can_upload_files]).filter('[value=' + res.visitors_can_upload_files + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=popup_alert_on_income_chats]').val([res.popup_alert_on_income_chats]).filter('[value=' + res.popup_alert_on_income_chats + ']').prop('checked', true).trigger('change');
				$("#lcp_start_new_chat_after").val(res.start_new_chat_after);
				$('input[type=radio][name=staff_qualification]').val([res.staff_qualification]).filter('[value=' + res.staff_qualification + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=new_chat_rings_to]').val([res.new_chat_rings_to]).filter('[value=' + res.new_chat_rings_to + ']').prop('checked', true).trigger('change');
				$('input[type=radio][name=start_minimized]').val([res.start_minimized]).filter('[value=' + res.start_minimized + ']').prop('checked', true).trigger('change');
				$("#offset").val(res.offset);
				$("#themes").val(res.id_theme).trigger("change");
				for (var k in res.offline_header_message) $("#offline_header_" + k).val(res.offline_header_message[k]).trigger('change');
				for (var k in res.online_header_message) $("#online_header_" + k).val(res.online_header_message[k]).trigger('change');
				for (var k in res.offline_welcome_message) $("#offline_welcome_message_" + k).val(res.offline_welcome_message[k]).trigger('change');
				for (var k in res.online_welcome_message) $("#online_welcome_message_" + k).val(res.online_welcome_message[k]).trigger('change');
				$('[id$="_languages"]').each(function()
				{
					$(this).val($(this).find('option:first').val()).trigger('change');
				});
				$('#lcp_sync_chat_interval_backend').val(res.sync_chat_interval_backend);
				$('#lcp_sync_chat_interval_frontend').val(res.sync_chat_interval_frontend);
				$('#lcp_realm_id').val(res.realm_id);
				$('#lcp_realm_key').val(res.realm_key);
			}
			catch (e)
			{}
		});
	});
	$('#chat_width').on('keyup change', function()
	{
		$("#preview_offline_chat_table").css('width', $(this).val() + 'px');
		$("#preview_online_chat_table").css('width', $(this).val() + 'px');
		$("#popup_preview_offline_chat_table").css('width', $(this).val() + 'px');
		$("#popup_preview_online_chat_table").css('width', $(this).val() + 'px');
	});
	$('#chat_height').on('keyup change', function()
	{
		$("#popup_preview_offline_chat_table").css('height', $(this).val() + 'px');
		$("#popup_preview_online_chat_table").css('height', $(this).val() + 'px');
	});
	$('#corners_radius').on('keyup change', function()
	{
		$("#preview_offline_chat_table").css('-webkit-border-top-left-radius', $(this).val() + 'px');
		$("#preview_offline_chat_table").css('-moz-border-top-left-radius', $(this).val() + 'px');
		$("#preview_offline_chat_table").css('border-top-left-radius', $(this).val() + 'px');
		$("#preview_offline_chat_table").css('-webkit-border-top-right-radius', $(this).val() + 'px');
		$("#preview_offline_chat_table").css('-moz-border-top-right-radius', $(this).val() + 'px');
		$("#preview_offline_chat_table").css('border-top-right-radius', $(this).val() + 'px');
		$("#preview_offline_chat_header_table").css('-webkit-border-top-left-radius', $(this).val() + 'px');
		$("#preview_offline_chat_header_table").css('-moz-border-top-left-radius', $(this).val() + 'px');
		$("#preview_offline_chat_header_table").css('border-top-left-radius', $(this).val() + 'px');
		$("#preview_offline_chat_header_table").css('-webkit-border-top-right-radius', $(this).val() + 'px');
		$("#preview_offline_chat_header_table").css('-moz-border-top-right-radius', $(this).val() + 'px');
		$("#preview_offline_chat_header_table").css('border-top-right-radius', $(this).val() + 'px');
		$("#preview_online_chat_table").css('-webkit-border-top-left-radius', $(this).val() + 'px');
		$("#preview_online_chat_table").css('-moz-border-top-left-radius', $(this).val() + 'px');
		$("#preview_online_chat_table").css('border-top-left-radius', $(this).val() + 'px');
		$("#preview_online_chat_table").css('-webkit-border-top-right-radius', $(this).val() + 'px');
		$("#preview_online_chat_table").css('-moz-border-top-right-radius', $(this).val() + 'px');
		$("#preview_online_chat_table").css('border-top-right-radius', $(this).val() + 'px');
		$("#preview_online_chat_header_table").css('-webkit-border-top-left-radius', $(this).val() + 'px');
		$("#preview_online_chat_header_table").css('-moz-border-top-left-radius', $(this).val() + 'px');
		$("#preview_online_chat_header_table").css('border-top-left-radius', $(this).val() + 'px');
		$("#preview_online_chat_header_table").css('-webkit-border-top-right-radius', $(this).val() + 'px');
		$("#preview_online_chat_header_table").css('-moz-border-top-right-radius', $(this).val() + 'px');
		$("#preview_online_chat_header_table").css('border-top-right-radius', $(this).val() + 'px');
	});
	$('input[type=radio][name=chat_box_border]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$("#preview_offline_chat_table").css('border', '1px solid #' + $('#chat_box_border_color').val());
			$("#preview_online_chat_table").css('border', '1px solid #' + $('#chat_box_border_color').val());
		}
		else
		{
			$("#preview_offline_chat_table").css('border', '0px');
			$("#preview_online_chat_table").css('border', '0px');
		}
	});
	$('#chat_box_border_color').on('keyup change', function()
	{
		$("#preview_offline_chat_table").css('border-color', '#' + $(this).val());
		$("#preview_online_chat_table").css('border-color', '#' + $(this).val());
	});
	$('#chat_box_background').on('keyup change', function()
	{
		$("#preview_offline_chat_table").css('background-color', '#' + $(this).val(), 'important');
		$("#preview_online_chat_table").css('background-color', '#' + $(this).val(), 'important');
		$("#popup_preview_offline_chat_table").css('background-color', '#' + $(this).val(), 'important');
		$("#popup_preview_online_chat_table").css('background-color', '#' + $(this).val(), 'important');
	});
	$('#chat_box_foreground').on('keyup change', function()
	{
		$("#preview_offline_chat_inner_table").css('color', '#' + $(this).val(), 'important');
		$("#preview_online_chat_inner_table").css('color', '#' + $(this).val(), 'important');
		$("#popup_preview_offline_chat_table").css('color', '#' + $(this).val(), 'important');
		$("#popup_preview_online_chat_table").css('color', '#' + $(this).val(), 'important');
	});
	$('#header_offline_background').on('keyup change', function()
	{
		$("#preview_offline_chat_header_table").css('background-color', '#' + $(this).val(), 'important');
		//$("#popup_preview_offline_chat_table th").css('background-color', '#' + $(this).val(), 'important');
	});
	$('#header_online_background').on('keyup change', function()
	{
		$("#preview_online_chat_header_table").css('background-color', '#' + $(this).val(), 'important');
		//$("#popup_preview_online_chat_table th").css('background-color', '#' + $(this).val(), 'important');
	});
	$('#header_offline_foreground').on('keyup change', function()
	{
		$("#preview_offline_chat_header_table").css('color', '#' + $(this).val(), 'important');
		//$("#popup_preview_offline_chat_table th").css('color', '#' + $(this).val(), 'important');
	});
	$('#header_online_foreground').on('keyup change', function()
	{
		$("#preview_online_chat_header_table").css('color', '#' + $(this).val(), 'important');
		//$("#popup_preview_online_chat_table th").css('color', '#' + $(this).val(), 'important');
	});
	$('#submit_button_background').on('keyup change', function()
	{
		$("#preview_offline_chat_table").find("a.chat-button").css('background-color', '#' + $(this).val(), 'important');
		$("#preview_online_chat_table").find("a.chat-button").css('background-color', '#' + $(this).val(), 'important');
		$("#popup_preview_offline_chat_table").find("a.chat-button").css('background-color', '#' + $(this).val(), 'important');
		$("#popup_preview_online_chat_table").find("a.chat-button").css('background-color', '#' + $(this).val(), 'important');
	});
	$('#submit_button_foreground').on('keyup change', function()
	{
		$("#preview_offline_chat_table").find("a.chat-button").css('color', '#' + $(this).val(), 'important');
		$("#preview_online_chat_table").find("a.chat-button").css('color', '#' + $(this).val(), 'important');
		$("#popup_preview_offline_chat_table").find("a.chat-button").css('color', '#' + $(this).val(), 'important');
		$("#popup_preview_online_chat_table").find("a.chat-button").css('color', '#' + $(this).val(), 'important');
	});
	$('input[id^="offline_header"]').on('keyup change', function()
	{
		$("#preview_offline_header_message").html($(this).val());
		//$("#popup_preview_offline_header_message").html($(this).val());
	});
	$('input[id^="online_header"]').on('keyup change', function()
	{
		$("#preview_online_header_message").html($(this).val());
		//$("#popup_preview_online_header_message").html($(this).val());
	});
	$('textarea[id^="offline_welcome_message"]').on('keyup change', function()
	{
		$("#preview_offline_welcome_message").html($(this).val());
		$("#popup_preview_offline_welcome_message").html($(this).val());
	});
	$('textarea[id^="online_welcome_message"]').on('keyup change', function()
	{
		$("#preview_online_welcome_message").html($(this).val());
		$("#popup_preview_online_welcome_message").html($(this).val());
	});
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$('input[type=radio][name=name_field_online]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$('input[name=name_field_online_mandatory]').prop('disabled', false);
			$("#preview_online_name_field").show();
			$("#popup_preview_online_name_field").show();
		}
		else
		{
			$('input[name=name_field_online_mandatory]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#preview_online_name_field").hide();
			$("#popup_preview_online_name_field").hide();
		}
	});
	$('input[type=radio][name=name_field_offline]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$('input[name=name_field_offline_mandatory]').prop('disabled', false);
			$("#preview_offline_name_field").show();
			$("#popup_preview_offline_name_field").show();
		}
		else
		{
			$('input[name=name_field_offline_mandatory]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#preview_offline_name_field").hide();
			$("#popup_preview_offline_name_field").hide();
		}
	});
	$('input[name=name_field_online_mandatory]').on('change', function()
	{
		if ($(this).is(':checked'))
		{
			$("#preview_online_name_field_required").show();
			$("#popup_preview_online_name_field_required").show();
		}
		else
		{
			$("#preview_online_name_field_required").hide();
			$("#popup_online_name_field_required").hide();
		}
	});
	$('input[name=name_field_offline_mandatory]').on('change', function()
	{
		if ($(this).is(':checked'))
		{
			$("#preview_offline_name_field_required").show();
			$("#popup_preview_offline_name_field_required").show();
		}
		else
		{
			$("#preview_offline_name_field_required").hide();
			$("#popup_offline_name_field_required").hide();
		}
	});
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$('input[type=radio][name=email_field_online]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$('input[name=email_field_online_mandatory]').prop('disabled', false);
			$("#preview_online_email_field").show();
			$("#popup_preview_online_email_field").show();
		}
		else
		{
			$('input[name=email_field_online_mandatory]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#preview_online_email_field").hide();
			$("#popup_preview_online_email_field").hide();
		}
	});

	$('input[type=radio][name=email_field_offline]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$('input[name=email_field_offline_mandatory]').prop('disabled', false);
			$("#preview_offline_email_field").show();
			$("#popup_preview_offline_email_field").show();
		}
		else
		{
			$('input[name=email_field_offline_mandatory]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#preview_offline_email_field").hide();
			$("#popup_preview_offline_email_field").hide();
		}
	});
	$('input[name=email_field_online_mandatory]').on('change', function()
	{
		//console.log($(this).val());
		if ($(this).is(':checked'))
		{
			$("#preview_online_email_field_required").show();
			$("#popup_preview_online_email_field_required").show();
		}
		else
		{
			$("#preview_online_email_field_required").hide();
			$("#popup_online_email_field_required").hide();
		}
	});
	$('input[name=email_field_offline_mandatory]').on('change', function()
	{
		//console.log($(this).val());
		if ($(this).is(':checked'))
		{
			$("#preview_offline_email_field_required").show();
			$("#popup_preview_offline_email_field_required").show();
		}
		else
		{
			$("#preview_offline_email_field_required").hide();
			$("#popup_offline_email_field_required").hide();
		}
	});
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	$('input[type=radio][name=phone_field_online]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$('input[name=phone_field_online_mandatory]').prop('disabled', false);
			$("#preview_online_phone_field").show();
			$("#popup_preview_online_phone_field").show();
		}
		else
		{
			$('input[name=phone_field_online_mandatory]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#preview_online_phone_field").hide();
			$("#popup_preview_online_phone_field").hide();
		}
	});

	$('input[type=radio][name=phone_field_offline]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$('input[name=phone_field_offline_mandatory]').prop('disabled', false);
			$("#preview_offline_phone_field").show();
			$("#popup_preview_offline_phone_field").show();
		}
		else
		{
			$('input[name=phone_field_offline_mandatory]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#preview_offline_phone_field").hide();
			$("#popup_preview_offline_phone_field").hide();
		}
	});
	$('input[name=phone_field_online_mandatory]').on('change', function()
	{
		//console.log($(this).val());
		if ($(this).is(':checked'))
		{
			$("#preview_online_phone_field_required").show();
			$("#popup_preview_online_phone_field_required").show();
		}
		else
		{
			$("#preview_online_phone_field_required").hide();
			$("#popup_online_phone_field_required").hide();
		}
	});
	$('input[name=phone_field_offline_mandatory]').on('change', function()
	{
		//console.log($(this).val());
		if ($(this).is(':checked'))
		{
			$("#preview_offline_phone_field_required").show();
			$("#popup_preview_offline_phone_field_required").show();
		}
		else
		{
			$("#preview_offline_phone_field_required").hide();
			$("#popup_offline_phone_field_required").hide();
		}
	});
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	$('input[type=radio][name=department_field_online]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$('input[name=department_field_online_mandatory]').prop('disabled', false);
			$("#preview_online_department_field").show();
			$("#popup_preview_online_department_field").show();
		}
		else
		{
			$('input[name=department_field_online_mandatory]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#preview_online_department_field").hide();
			$("#popup_preview_online_department_field").hide();
		}
	});
	$('input[type=radio][name=department_field_offline]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$('input[name=department_field_offline_mandatory]').prop('disabled', false);
			$("#preview_offline_department_field").show();
			$("#popup_preview_offline_department_field").show();
		}
		else
		{
			$('input[name=department_field_offline_mandatory]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#preview_offline_department_field").hide();
			$("#popup_preview_offline_department_field").hide();
		}
	});
	$('input[name=department_field_online_mandatory]').on('change', function()
	{
		//console.log($(this).val());
		if ($(this).is(':checked'))
		{
			$("#preview_online_department_field_required").show();
			$("#popup_preview_online_department_field_required").show();
		}
		else
		{
			$("#preview_online_department_field_required").hide();
			$("#popup_online_department_field_required").hide();
		}
	});
	$('input[name=department_field_offline_mandatory]').on('change', function()
	{
		//console.log($(this).val());
		if ($(this).is(':checked'))
		{
			$("#preview_offline_department_field_required").show();
			$("#popup_preview_offline_department_field_required").show();
		}
		else
		{
			$("#preview_offline_department_field_required").hide();
			$("#popup_offline_department_field_required").hide();
		}
	});
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$('input[type=radio][name=question_field_online]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$('input[name=question_field_online_mandatory]').prop('disabled', false);
			$("#preview_online_question_field").show();
			$("#popup_preview_online_question_field").show();
		}
		else
		{
			$('input[name=question_field_online_mandatory]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#preview_online_question_field").hide();
			$("#popup_preview_online_question_field").hide();
		}
	});
	$('input[type=radio][name=question_field_offline]').on('change', function()
	{
		if ($(this).val() == 'Y')
		{
			$('input[name=question_field_offline_mandatory]').prop('disabled', false);
			$("#preview_offline_question_field").show();
			$("#popup_preview_offline_question_field").show();
		}
		else
		{
			$('input[name=question_field_offline_mandatory]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#preview_offline_question_field").hide();
			$("#popup_preview_offline_question_field").hide();
		}
	});
	$('input[name=question_field_online_mandatory]').on('change', function()
	{
		//console.log($(this).val());
		if ($(this).is(':checked'))
		{
			$("#preview_online_question_field_required").show();
			$("#popup_preview_online_question_field_required").show();
		}
		else
		{
			$("#preview_online_question_field_required").hide();
			$("#popup_online_question_field_required").hide();
		}
	});
	$('input[name=question_field_offline_mandatory]').on('change', function()
	{
		//console.log($(this).val());
		if ($(this).is(':checked'))
		{
			$("#preview_offline_question_field_required").show();
			$("#popup_preview_offline_question_field_required").show();
		}
		else
		{
			$("#preview_offline_question_field_required").hide();
			$("#popup_offline_question_field_required").hide();
		}
	});
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$('input[type=radio][name=host_type]').on('change', function()
	{
		if ($(this).val() == 'Remote')
		{
			$("#lcp_hosted_self_desc_span").hide();
			$("#lcp_hosted_remote_desc_span").show();
			$("#performance_settings_tbody").hide();
			$("#remote_settings_tbody").show();
		}
		else
		{
			$("#lcp_hosted_self_desc_span").show();
			$("#lcp_hosted_remote_desc_span").hide();
			$("#performance_settings_tbody").show();
			$("#remote_settings_tbody").hide();
		}
	});

	$('input[type=radio][name=chat_type]').on('change', function()
	{
		if ($(this).val() == 'Slide')
		{
			$("#preview_slide").show();
			$("#preview_popup").hide();
			$("#orientation_slide_select").show();
			$("#orientation_popup_select").hide();
			$('input[name=slide_with_image]').prop('disabled', false).trigger('change');
			$("#icons_tbody").hide();
			$('input[name=fixed_position]').prop('disabled', true).val(['Y']).filter('[value=Y]').prop('checked', true).trigger('change');
			$("#corners_radius_tr").show();
			$("#chat_box_border_tr").show();
			$("#chat_box_border_color_tr").show();
			$("#header_tbody").show();
			$("#orientation_tr").show();
			$("#offset_tr").show();
			
		}
		else
		{
			$("#preview_slide").hide();
			$("#preview_popup").show();
			$("#orientation_slide_select").hide();
			$("#orientation_popup_select").show();
			$('input[name=slide_with_image]').prop('disabled', true).val(['N']).filter('[value=N]').prop('checked', true).trigger('change');
			$("#icons_tbody").show();
			$('input[name=start_minimized]').prop('disabled', true).val(['Y']).filter('[value=Y]').prop('checked', true).trigger('change');
			$('input[name=fixed_position]').prop('disabled', false).val(['Y']).filter('[value=Y]').prop('checked', true).trigger('change');
			$("#corners_radius_tr").hide();
			$("#chat_box_border_tr").hide();
			$("#chat_box_border_color_tr").hide();
			$("#header_tbody").hide();
			
		}
	});

	$('input[type=radio][name=slide_with_image]').on('change', function()
	{
		if ($(this).val() == 'N')
		{
			if ($('input[type=radio][name=chat_type]:checked').val() == 'Popup')
				$("#icons_tbody").show();
			else
				$("#icons_tbody").hide();

			$("#icon_offline_div").show();
			$('input[name=start_minimized]').prop('disabled', false);
		}
		else
		{
			$("#icons_tbody").show();
			$("#icon_offline_div").hide();
			$('input[name=start_minimized]').prop('disabled', true).val(['Y']).filter('[value=Y]').prop('checked', true).trigger('change');
		}
	});

	$('input[type=radio][name=fixed_position]').on('change', function()
	{
		//alert($(this).val());
		if ($(this).val() == 'N')
		{
			$("#orientation_tr").hide();
			$("#offset_tr").hide();
		}
		else
		{
			$("#orientation_tr").show();
			$("#offset_tr").show();
		}
	});

$(document).on('focus blur', 'input[id^="msg_admin"]', function()
{
	if (lcp_primary_settings.chat_type_admin == 'Slide')
	{	
		var key = $(this).attr('id').split('_')[2];
		if ($(this).is(':focus'))
		{
			$('div[id^="slide_chat_head_div_"]').css({"background-color": "#f6f7f9", 'color': '#564F4B'});
			$("#slide_chat_head_div_"+key).css({"background-color": "#4080ff", 'color': 'white'});
		}
		else
		{
			$('div[id^="slide_chat_head_div_"]').css({"background-color": "#f6f7f9", 'color': '#564F4B'});
		}
	}
});

$(document).on('mouseover', '.odd, .even', function()
{
	$(this).find("td.online_users_hover_td").html('<span class="icon-comment fa fa-comment"></span>');
	$(this).css('background-color', '#ecf0f2');
});

$(document).on('mouseout', '.odd, .even', function()
{
	$(this).find("td.online_users_hover_td").html('');
	$(this).css('background-color', '');
});

	$(document).on('click', 'td[id^="online_internal_users_td"]', function()
	{
		var type = $(this).attr('id').split('_')[1];
		var type_nr = $(this).attr('id').split('_')[4];
		var id_staffprofile = $("#id_" + type + "_staffprofile_" + type_nr).val();
		if (id_staffprofile == lcp_id_staffprofile) return;
		var data = {
			'id_visitor': 'i' + id_staffprofile,
		};
		var params = {
			'load': 'chatRequestFromStaffToStaff',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'users_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result)
		{
			var res = JSON.parse(result);
			if (lcp_primary_settings.host_type == 'Self')
			{
				$('#awaiting_response_chat_dialogs').html(lcp.getHtmlCode('ajax.header_chats_counter.tpl', res));
				
				if (lcp_primary_settings.chat_type_admin == 'Popup')
					$("#ajax_chats_div").html(lcp.getHtmlCode('ajax.chats.tpl', res));
				else
				{
					$("#ajax_chats_div").html(lcp.getHtmlCode('ajax.chats_slide.tpl', res));

					if (!lcp.empty(res.response.active_pending_archives)) 
					{
						$.each(res.response.active_pending_archives, function( key, value ) 
						{
							// daca nu exista divul
							if ($('#ajax_chats_textarea_div_'+key).length == 0)
							{
								$('#ajax_chats_textarea_div').append('<td id="ajax_chats_textarea_div_'+key+'" style="padding-top: 0px; padding-right: 5px;"><div style="background-color: white; height: 60px; box-shadow: 0px 5px 5px 0px rgba(66,66,66,0.75); padding: 2px;">'+lcp.getHtmlCode('ajax.chats_slide_textarea.tpl', res, key)+'</div></td>');
							}
							else
							{
								if ($('#ajax_chats_textarea_div_'+key).html() == '')
									$('#ajax_chats_textarea_div_'+key).html(lcp.getHtmlCode('ajax.chats_slide_textarea.tpl', res, key));
							}

						});
					}
					else
					{
						$('#ajax_chats_textarea_div').html('');
					}
				}

				$("#ajax_onlineusers_div").html(lcp.getHtmlCode('ajax.onlineusers.tpl', res));
			}
		}, true);
	});

	$(document).on('click', '[id^="predefined_messages"]', function()
	//$(document).on('click', '#predefined_messages', function()
	{
		var _this = this;
		$( "#dialog-predefined-messages" ).dialog({
					autoOpen: false,
					/*height: 300,*/
					width: 400,
					modal: false,
					buttons: [
						{
							text: lcp.l("Insert message"),
							click: function () {
				
										var data = {
											'id_predefinedmessage' : $('#predefined_messages_select').val(),
									};
							
									var params = {
							
										'load' : 'getPredefinedMessage',
										'divs' : null,
										'params' : {
											'data' : data,
										},
										/*'preloader' : {
											'divs' : {
												0 : 'predefinedmessages_ajax_load_span',
											},
											'type' : 2,
											'style' : 3,
										}*/
									};
							
									lcp.ajaxController(params, function(result){
							
										var res = JSON.parse(result);
										var key = (lcp_primary_settings.chat_type_admin == 'Popup') ? lcp_active_chat_tab : $(_this).attr('id').split('_')[2];
										/*console.log(res); return;*/
										if (lcp_primary_settings.chat_type_admin == 'Popup')
										{
											var textarea_val = $('#msg_admin').val();
											$('#msg_admin').val('');
											$('#msg_admin').val( textarea_val + res.message );
										}
										else
										{
											var textarea_val = $('#msg_admin_'+key).val();
											$('#msg_admin_'+key).val('');
											$('#msg_admin_'+key).val( textarea_val + res.message );
										}
										
										$( "#dialog-predefined-messages" ).dialog( "close" );
							
									});
								},
					class:"",
					style:"color: black"
					},
					],
					close : function() {
						/*allFields.val( "" ).removeClass( "ui-state-error" );*/
					}
				});

		$("#dialog-predefined-messages").dialog("open");
	});
	$(document).on('click', '[id^="transfer_visitor"]', function()
	//$(document).on('click', '#transfer_visitor', function()
	{
		var key = (lcp_primary_settings.chat_type_admin == 'Popup') ? lcp_active_chat_tab : $(this).attr('id').split('_')[2];
		var id_visitor = $('#id_visitor_' + key).val();
		
		if (!$.isNumeric(id_visitor)) 
			return alert(lcp.l('You cannot transfer a staff member!'));

				$( "#dialog-online-internal-users" ).dialog({
					autoOpen: false,
					/*height: 300,*/
					width: 200,
					modal: false,
					buttons: [
						{
							text: lcp.l("Transfer"),
							click: function () {
							
									var id_visitor = $('#id_visitor_'+key).val();
							
										var data = {
											'id_visitor' : id_visitor,
											'id_staffprofile_destination' : $('#online_internal_users_select').val(),
									};
							
									var params = {
							
										'load' : 'transferVisitor',
										'divs' : null,
										'params' : {
											'data' : data,
										},
										/*'preloader' : {
											'divs' : {
												0 : 'transfervisitor_ajax_load_span',
											},
											'type' : 2,
											'style' : 3,
										}*/
									};
							
									lcp.ajaxController(params, function(result){
							
										//var res = JSON.parse(result);
										/*console.log(res); return;*/
										var e = $.Event("keydown");
										e.which = 13;
										e.keyCode = 13;

										var key = (lcp_primary_settings.chat_type_admin == 'Popup') ? lcp_active_chat_tab : $(this).attr('id').split('_')[2];
										/*console.log(res); return;*/
										if (lcp_primary_settings.chat_type_admin == 'Popup')
										{
											var textarea_val = $('#msg_admin').val();
											$('#msg_admin').val( lcp.l('You will be transfered to another staff member, please wait...') ).trigger(e);
										}
										else
										{
											var textarea_val = $('#msg_admin_'+key).val();
											$('#msg_admin_'+key).val( lcp.l('You will be transfered to another staff member, please wait...') ).trigger(e);
										}

										$( "#dialog-online-internal-users" ).dialog( "close" );
							
									});
								},
					class:"",
					style:"color: black"
					},
					],
					close : function() {
						/*allFields.val( "" ).removeClass( "ui-state-error" );*/
					}
				});

		$("#dialog-online-internal-users").dialog("open");


		var data = {
			'id_visitor': id_visitor,
		};
		var params = {
			'load': 'showOnlineInternalUsers',
			'divs':
			{
				0: 'ajax_online_internal_users_div',
			},
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'ajax_online_internal_users_div',
				},
				'type': 1,
				'style': 3,
			}
		};
		lcp.ajaxController(params, function(result) {});
	});

	$(document).on('click', 'span[id^="userchat_span"], span[id^="details_span"], span[id^="visitedpageshistory_span"], span[id^="geotracking_span"], span[id^="archive_span"], span[id^="messages_span"], span[id^="ratings_span"], span[id^="logs_span"], span[id^="mousetracking_span"]', function()
	{
		var tab_active = $(this).attr('id').split('_')[0];
		var key = $(this).attr('id').split('_')[2];
		var id_visitor = $('#id_visitor_' + key).val();

		$('#userchat_span_'+key+', #details_span_'+key+', #visitedpageshistory_span_'+key+', #geotracking_span_'+key+', #archive_span_'+key+', #messages_span_'+key+', #ratings_span_'+key+', #logs_span_'+key+', #mousetracking_span_'+key).removeClass('tab-selected');
		
		$('#tabs-visitor-userchat-'+key+', #tabs-visitor-details, #tabs-visitor-visitedpageshistory, #tabs-visitor-geotracking, #tabs-visitor-archive, #tabs-visitor-messages, #tabs-visitor-ratings, #tabs-visitor-logs, #ajax_chats_textarea_div').hide();
	
		$(this).addClass('lcp tab-selected');

		//lcp_active_chat_subtab = $(this).attr('id');
		$.localStorage.set('lcp_active_chat_subtab', $(this).attr('id'));

		if (tab_active == 'userchat')
		{
			$("#tabs-visitor-" + tab_active + "-" + key).show();
			
			if ($("#in_chat_" + key).val() == 'Y') 
				$('#ajax_chats_textarea_div').show();
		}
		else 
			$("#tabs-visitor-" + tab_active).show();

		// populam divurile cu details
		if (tab_active == 'details') lcp.showVisitorDetails(id_visitor);
		else if (tab_active == 'visitedpageshistory') lcp.showVisitorVisitedPagesHistory(id_visitor);
		else if (tab_active == 'geotracking') lcp.showVisitorGeoTracking(id_visitor);
		else if (tab_active == 'archive') lcp.showVisitorArchive(id_visitor);
		else if (tab_active == 'messages') lcp.showVisitorMessages(id_visitor);
		else if (tab_active == 'ratings') lcp.showVisitorRatings(id_visitor);
		else if (tab_active == 'logs') lcp.showVisitorLogs(id_visitor);


	});

	$('#open_mouse_tracking_a').on('click', function()
	{
		$("#dialog-mousetracking").dialog(
		{
			autoOpen: false,
			width: parseInt($(window).width()) - 20,
			height: parseInt($(window).height()) - 50,
			position: ['top', 40],
			modal: true,
			close: function()
			{
				/* allFields.val( "" ).removeClass( "ui-state-error" ); */
			}
		});
		if ($('#dialog-mousetracking').is(":visible"))
		{
			$("#dialog-mousetracking").dialog("close");
		}
		else
		{
			$("#dialog-mousetracking").dialog("open");
			var id_visitor = 44;
			var data = {
				'id_visitor': id_visitor,
			};
			var params = {
				'load': 'showMouseTracking',
				'divs':
				{
					0: 'dialog-mousetracking',
				},
				'params':
				{
					'data': data,
				},
				'preloader':
				{
					'divs':
					{
						0: 'dialog-mousetracking',
					},
					'type': 1,
					'style': 3,
				}
			};
			lcp.ajaxController(params, function(result) {});
		}
	});

	$(document).on('change', '#status_select', function()
	{
		$.localStorage.set('status_select', $(this).val());
		var data = {
			'status': $(this).val(),
		};
		var params = {
			'load': 'changeStaffStatus',
			'divs':
			{
				0: 'ajax_onlineusers_div',
			},
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'status_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		var status = $(this).val();
		lcp.ajaxController(params, function(result)
		{
			var res = JSON.parse(result);
			if (res.success == true)
			{
				$("#status_ajax_load_span").html('<img id="status_img" border="0" src="' + lcp_path + 'views/img/' + $('#status_select').val().toLowerCase() + '-ico.png">');

				$("#ajax_onlineusers_div").html(lcp.getHtmlCode('ajax.onlineusers.tpl', res));

				if (status == 'Offline')
				{
					if (lcp_ps_version == '1.5') $('#dialog_chat_a').css('color', '#ffffff');
					else $('#dialog_chat_a').css('color', '#b3b3b3');
				}
				else 
					$('#dialog_chat_a').css('color', '#7ab726');
			}
			else 
				alert(lcp.l('You can`t change the status of an inactive profile!'));
		
		}, true);
	});





	} // end BACKOFFICE


});  // end document ready


