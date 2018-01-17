<?php
/**
 * 2012-2017 NetReviews
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
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * avisverifiesApi.php file used to execute query from AvisVerifies plateform
 *
 *  @author    NetReviews SAS <contact@avis-verifies.com>
 *  @copyright 2017 NetReviews SAS
 *  @version   Release: $Revision: 7.4.0
 *  @license   NetReviews
 *  @date      01/09/2017
 *  @category  api
 *  International Registered Trademark & Property of NetReviews SAS
 */

require(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/models/NetReviewsModel.php');

$nom_group = null;
$id_shop = null;
$module = Module::getInstanceByName('netreviews');
/*
# Ajax file to pagination enfine
# This file contains the same code as hook productTabContent but use a template dedicated to the ajax data loaded
*/
$id_product = Tools::getValue('id_product');
if (empty($id_product)) {
    exit;
}

$avisverifies_nb_reviews = Tools::getValue('avisverifies_nb_reviews');
$o_av = new NetReviewsModel();
$nb_comments = (int)Tools::getValue('count_reviews');

$nom_group = Tools::getValue('nom_group');
$id_shop = (int)Tools::getValue('id_shop');

$p = abs((int)Tools::getValue('p', 1));
$range = 2;
if ($p > (($nb_comments / $o_av->reviews_by_page) + 1)) {
    Tools::redirect(preg_replace('/[&?]p=\d+/', '', $_SERVER['REQUEST_URI']));
}
$pages_nb = ceil($nb_comments / (int)$o_av->reviews_by_page);
$start = (int)$p - $range;
if ($start < 1) {
    $start = 1;
}
$stop = (int)$p + $range;
if ($stop > $pages_nb) {
    $stop = (int)$pages_nb;
}
/* $first_review = ($p - 1) * $reviews_by_page;  */
$reviews = $o_av->getProductReviews((int)$id_product, $nom_group, $id_shop, false, $p);

$reviews_list = array();
$response = '';
foreach ($reviews as $review) {

    $my_review = array();
    $my_review['ref_produit'] = $review['ref_product'];
    $my_review['id_product_av'] = $review['id_product_av'];
    $my_review['rate'] = $review['rate'];
    $my_review['avis'] = urldecode($review['review']);
    $date = new DateTime();
    $date->setTimestamp((int)$review['horodate']);
    $my_review['horodate'] =$date->format('d/m/Y') ;
    $my_review['customer_name'] = urldecode($review['customer_name']);
    $my_review['discussion'] = '';
    $unserialized_discussion = Tools::jsonDecode(NetReviewsModel::AcDecodeBase64($review['discussion']), true);
    if ($unserialized_discussion) {
        foreach ($unserialized_discussion as $k_discussion => $each_discussion) {
            $date = new DateTime();
            $date->setTimestamp((int)$each_discussion['horodate']);
            $my_review['discussion'][$k_discussion]['commentaire'] = $each_discussion['commentaire'];
            $my_review['discussion'][$k_discussion]['horodate'] = $date->format('d/m/Y') ;
            if ($each_discussion['origine'] == 'ecommercant') {
                $my_review['discussion'][$k_discussion]['origine'] = Configuration::get('PS_SHOP_NAME');
            } elseif ($each_discussion['origine'] == 'internaute') {
                $my_review['discussion'][$k_discussion]['origine'] = $my_review['customer_name'];
            } else {
                $my_review['discussion'][$k_discussion]['origine'] = $testModule->l('Moderator');
            }
        }
    }
    $reviews_list[] = $my_review;
}

$i= 0;
$first= true;

foreach ($reviews_list as $key=> $review)
{    
    if ($i == 1 && !$first)
    {
       $response .= '<span class="groupAvis">';
    }
    
    $response .= '<div class="reviewAV" >';
    $response .=  '<ul class="reviewInfosAV">';
    $response .=    '<li style="text-transform:capitalize">'.$review['customer_name'].'</li>';
    $response .=       '<li>&nbsp;'. $module->l('the').' '. $review['horodate'] .' </li>';
    $response .=        '<li class="rateAV"><img src="'. _MODULE_DIR_ .'netreviews/views/img/etoile'.$review['rate'].'.png" width="80" height="15" /> '.$review["rate"].'/5</li>';
    $response .=    '</ul>';

    $response .=    '<div class="triangle-border top">'.$review['avis'].'</div>';
        if ($review['discussion'])
        {
            foreach ($review['discussion'] as $key2=>$discussion)
            {
                $response .= '<div class="triangle-border top answer"';
                 if ($key2 > 0)
                 { 
                    $response .= 'review_number='. $review['id_product_av'].' style= "display: none"'; 
                }
                $response .= '>';
                $response .= '<span>&rsaquo; '. $module->l('Comment from') .'<b style="text-transform:capitalize; font-weight:normal">'.$discussion['origine'].'</b>'. $module->l('the'). ' '. $discussion['horodate'] .'</span>';
                $response .=  '<p class="answer-bodyAV">' . $discussion['commentaire'] .'</p>';
                $response .=    '</div>';
            }
            if ($k_discussion > 0) 
            {
                $response .= '<a href="javascript:switchCommentsVisibility("'.$review['id_product_av'].'")" style="padding-left: 6px;margin-left: 30px; display: block; font-style:italic" id="display'.$review['id_product_av'].'" class="display-all-comments" review_number="'.$review['id_product_av'].'" >'.$module->l('Show exchanges').'</a>';
                $response .= '<a href="javascript:switchCommentsVisibility('.$review['id_product_av'].')" style="padding-left: 6px;margin-left: 30px; display: none; font-style:italic" id="hide'.$review['id_product_av'].'" class="display-all-comments" review_number="'.$review['id_product_av'].'" >'. $module->l('Hide exchanges').'</a>';
                $response .=  '</a>';
            }
        }
    $response .='</div>';
    if ($i == $avisverifies_nb_reviews && !$first)
    {
        $response .= "</span>";
        $i = 1;
    }else{
        if ($i == $avisverifies_nb_reviews && $first)
        {
            $first = false;
            $i = 1;
        }else{
            $i++;
        }
    }

}
echo ($response);
