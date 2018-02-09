<?php
/**
 * 2007-2018 PrestaShop
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

$querys = array();

$querys[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'thnxblckpopuptbl` (
				`id_thnxblckpopuptbl` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`popuptype` VARCHAR(100) NULL,
				`layout_style` VARCHAR(100) NULL,
				`product_item` text NULL,
				`height` VARCHAR(50) NULL,
				`width` VARCHAR(50) NULL,
				`image` VARCHAR(150) NULL,
				`pages` text NULL,
				`fromdate` datetime NOT NULL,
				`todate` datetime NOT NULL,
				`starttime` VARCHAR(50) NULL,
				`staytime` VARCHAR(50) NULL,
				`dontshow` longtext DEFAULT NULL,
				`iscustomer` int(10) NOT NULL,
				`isguest` int(10) NOT NULL,
				`active` int(10) NOT NULL,
				`position`int(10) NOT NULL,
				PRIMARY KEY (`id_thnxblckpopuptbl`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

$querys[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'thnxblckpopuptbl_lang` (
				`id_thnxblckpopuptbl` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_lang` int(10) unsigned NULL ,
				`title` VARCHAR(300) NULL,
				`subtitle` VARCHAR(300) NULL,
				`description` text NULL,
				PRIMARY KEY (`id_thnxblckpopuptbl`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

$querys[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'thnxblckpopuptbl_shop` (
			  `id_thnxblckpopuptbl` int(11) NOT NULL,
			  `id_shop` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id_thnxblckpopuptbl`,`id_shop`)
			)ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$querys_u = array();

$querys_u[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'thnxblckpopuptbl`';

$querys_u[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'thnxblckpopuptbl_lang`';

$querys_u[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'thnxblckpopuptbl_shop`';
