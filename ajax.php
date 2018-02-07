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
 
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once (_PS_MODULE_DIR_.'thnxblockpopup/thnxblockpopup.php');
$action = Tools::getValue('action_type');

if($action == 'dontshow'){
	DontShowFunc();
}elseif($action == 'submit_newsletter'){
	$thnxblockpopup = new thnxblockpopup();
	if($thnxblockpopup->_prepareHook()){
		DontShowFunc();
		die(Tools::jsonEncode(array("msg" => $thnxblockpopup->suc_msg)));
	}else{
		die(Tools::jsonEncode(array("msg" => $thnxblockpopup->error)));
	}
}
function DontShowFunc(){
	$context = Context::getcontext();
	$id_newsletter = (int)Tools::getValue('id_newsletter');
	$blockpopupclass = new blockpopupclass($id_newsletter);
	$id_customer = (int)$context->cart->id_customer;
	$id_guest = (int)$context->cart->id_guest;

	if($id_customer != 0){
		$id = 'c_'.$id_customer;		
	}else{
		$id = 'g_'.$id_guest;		
	}
	if(isset($blockpopupclass->dontshow) && !empty($blockpopupclass->dontshow)){
		$dontshow = explode(",",$blockpopupclass->dontshow);
		if(!in_array($id, $dontshow)){
			$dontshow[] = $id;
		}
		$dontshow = implode(",", $dontshow);
	}else{
		$dontshow = $id;
	}
	$blockpopupclass->dontshow = $dontshow;

	if($blockpopupclass->update())
	{
		return true;
	}else{
		return false;
	}
}