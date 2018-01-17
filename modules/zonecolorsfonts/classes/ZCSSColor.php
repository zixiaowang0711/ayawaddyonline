<?php
/**
 * 2007-2017 PrestaShop.
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
 */

class ZCSSColor
{
    public function __construct()
    {
    }

    public function darken($color, $amount = 10)
    {
        $hsl = $this->hexToHsl($color);
        $darkerHSL = $this->_darken($hsl, $amount);

        return $this->hslToHex($darkerHSL);
    }

    public function lighten($color, $amount = 10)
    {
        $hsl = $this->hexToHsl($color);
        $lighterHSL = $this->_lighten($hsl, $amount);

        return $this->hslToHex($lighterHSL);
    }

    private function hexToHsl($color)
    {
        $color = $this->_checkHex($color);

        $R = hexdec($color[0].$color[1]);
        $G = hexdec($color[2].$color[3]);
        $B = hexdec($color[4].$color[5]);

        $HSL = array();

        $var_R = ($R / 255);
        $var_G = ($G / 255);
        $var_B = ($B / 255);

        $var_Min = min($var_R, $var_G, $var_B);
        $var_Max = max($var_R, $var_G, $var_B);
        $del_Max = $var_Max - $var_Min;

        $L = ($var_Max + $var_Min)/2;

        if ($del_Max == 0) {
            $H = 0;
            $S = 0;
        } else {
            if ($L < 0.5) {
                $S = $del_Max / ( $var_Max + $var_Min );
            } else {
                $S = $del_Max / ( 2 - $var_Max - $var_Min );
            }

            $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
            $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
            $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

            if ($var_R == $var_Max) {
                $H = $del_B - $del_G;
            } elseif ($var_G == $var_Max) {
                $H = ( 1 / 3 ) + $del_R - $del_B;
            } elseif ($var_B == $var_Max) {
                $H = ( 2 / 3 ) + $del_G - $del_R;
            }

            if ($H<0) {
                $H++;
            }
            if ($H>1) {
                $H--;
            }
        }

        $HSL['H'] = ($H*360);
        $HSL['S'] = $S;
        $HSL['L'] = $L;

        return $HSL;
    }

    private function hslToHex($hsl = array())
    {
        if (empty($hsl) || !isset($hsl["H"]) || !isset($hsl["S"]) || !isset($hsl["L"])) {
            throw new Exception("Param was not an HSL array");
        }

        list($H,$S,$L) = array( $hsl['H']/360,$hsl['S'],$hsl['L'] );

        if ($S == 0) {
            $r = $L * 255;
            $g = $L * 255;
            $b = $L * 255;
        } else {
            if ($L<0.5) {
                $var_2 = $L*(1+$S);
            } else {
                $var_2 = ($L+$S) - ($S*$L);
            }

            $var_1 = 2 * $L - $var_2;

            $r = round(255 * $this->_huetorgb($var_1, $var_2, $H + (1/3)));
            $g = round(255 * $this->_huetorgb($var_1, $var_2, $H));
            $b = round(255 * $this->_huetorgb($var_1, $var_2, $H - (1/3)));
        }

        $r = dechex($r);
        $g = dechex($g);
        $b = dechex($b);

        $r = (Tools::strlen("".$r)===1) ? "0".$r:$r;
        $g = (Tools::strlen("".$g)===1) ? "0".$g:$g;
        $b = (Tools::strlen("".$b)===1) ? "0".$b:$b;

        return "#".$r.$g.$b;
    }

    private function _darken($hsl, $amount = 10)
    {
        if ($amount) {
            $hsl['L'] = ($hsl['L'] * 100) - $amount;
            $hsl['L'] = ($hsl['L'] < 0) ? 0:$hsl['L']/100;
        } else {
            $hsl['L'] = $hsl['L']/2 ;
        }

        return $hsl;
    }

    private function _lighten($hsl, $amount = 10)
    {
        if ($amount) {
            $hsl['L'] = ($hsl['L'] * 100) + $amount;
            $hsl['L'] = ($hsl['L'] > 100) ? 1:$hsl['L']/100;
        } else {
            $hsl['L'] += (1-$hsl['L'])/2;
        }

        return $hsl;
    }

    private function _checkHex($hex)
    {
        $color = str_replace("#", "", $hex);

        if (Tools::strlen($color) == 3) {
            $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
        } elseif (Tools::strlen($color) != 6) {
            throw new Exception("HEX color needs to be 6 or 3 digits long");
        }

        return $color;
    }

    private function _huetorgb($v1, $v2, $vH)
    {
        if ($vH < 0) {
            $vH += 1;
        }

        if ($vH > 1) {
            $vH -= 1;
        }

        if ((6*$vH) < 1) {
               return ($v1 + ($v2 - $v1) * 6 * $vH);
        }

        if ((2*$vH) < 1) {
            return $v2;
        }

        if ((3*$vH) < 2) {
            return ($v1 + ($v2-$v1) * ( (2/3)-$vH ) * 6);
        }

        return $v1;
    }
}
