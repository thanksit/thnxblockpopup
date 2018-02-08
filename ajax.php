<?php

require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(_PS_MODULE_DIR_.'thnxblockpopup/thnxblockpopup.php');
$action = Tools::getValue('action_type');

if ($action == 'dontshow') {
    DontShowFunc();
} else if ($action == 'submit_newsletter') {
    $thnxblockpopup = new thnxblockpopup();
    if ($thnxblockpopup->_prepareHook()) {
        DontShowFunc();
        die(Tools::jsonEncode(array("msg" => $thnxblockpopup->suc_msg)));
    } else {
        die(Tools::jsonEncode(array("msg" => $thnxblockpopup->error)));
    }
}
function DontShowFunc() {
    $context = Context::getcontext();
    $id_newsletter = (int)Tools::getValue('id_newsletter');
    $blockpopupclass = new blockpopupclass($id_newsletter);
    $id_customer = (int)$context->cart->id_customer;
    $id_guest = (int)$context->cart->id_guest;

    if ($id_customer != 0) {
        $id = 'c_'.$id_customer;        
    } else {
        $id = 'g_'.$id_guest;       
    }
    if (isset($blockpopupclass->dontshow) && !empty($blockpopupclass->dontshow)) {
        $dontshow = explode(",",$blockpopupclass->dontshow);
        if (!in_array($id, $dontshow)) {
            $dontshow[] = $id;
        }
        $dontshow = implode(",", $dontshow);
    } else {
        $dontshow = $id;
    }
    $blockpopupclass->dontshow = $dontshow;

    if ($blockpopupclass->update())
    {
        return true;
    } else {
        return false;
    }
}
