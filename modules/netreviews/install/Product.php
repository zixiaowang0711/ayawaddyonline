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
 *  @version   Release: $Revision: 7.4.2
 *  @license   NetReviews
 *  @date      16/10/2017
 *  @category  api
 *  International Registered Trademark & Property of NetReviews SAS
 */



class Product extends ProductCore {

    public static function getProductProperties($id_lang, $row, Context $context = null)
    {
        if(empty($context) || !isset($context))
            $context = Context::getContext();

        $p = parent::getProductProperties($id_lang, $row, $context);

        $av_model = _PS_MODULE_DIR_.'netreviews/models/NetReviewsModel.php';

        if (!class_exists('NetReviewsModel') && file_exists($av_model))
            require_once($av_model);

        $NetReviewsModel = new NetReviewsModel;
        $p['l']['reviews'] = $NetReviewsModel::l("reviews");
        $p['l']['review'] = $NetReviewsModel::l("review");
        $p['design'] = true; // new star;
        $id_shop = (int)$context->shop->id;
        $p['av_stats'] = $NetReviewsModel->getStatsProduct($p['id_product'],null,$id_shop);
        $p['av_rate'] = "";
        $p['av_rate_percent'] = "";
        $p['av_nb_reviews'] = "";
        if (!empty($p['av_stats']['rate']))
            $p['av_rate'] = $p['av_stats']['rate'];
            $p['av_rate_percent'] = round($p['av_rate']*20);
            $p['av_nb_reviews'] = $p['av_stats']['nb_reviews'];

        return $p;
    }

}