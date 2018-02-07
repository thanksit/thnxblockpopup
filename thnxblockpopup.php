<?php
include_once _PS_MODULE_DIR_.'thnxblockpopup/classes/blockpopupclass.php';
class thnxblockpopup extends Module
{
	const GUEST_NOT_REGISTERED = -1;
	const CUSTOMER_NOT_REGISTERED = 0;
	const GUEST_REGISTERED = 1;
	const CUSTOMER_REGISTERED = 2;
	public $css_files = array(
		array(
			'key' => 'thnxblockpopup_css',
			'src' => 'thnxblockpopup.css',
			'priority' => 50,
			'media' => 'all',
			'load_theme' => false,
		),
	);
	public $js_files = array(
		array(
			'key' => 'thnxblockpopup_js',
			'src' => 'thnxblockpopup.js',
			'priority' => 50,
			'position' => 'bottom', // bottom or head
			'load_theme' => false,
		),
	);
	public $tabs_files_url = '/tabs/tabs.php';
	public $suc_msg;
	public static $inlinejs = array();
	public $mysql_files_url = '/querys/querys.php';
	public function __construct()
	{
		$this->name = 'thnxblockpopup';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'thanksit.com';
		$this->bootstrap = true;
		parent::__construct();
		$this->error = false;
		$this->valid = false;
		$this->_files = array(
			'name' => array('thnxpopnewsletter_conf', 'thnxpopnewsletter_voucher','thnxpopnewsletter_verif'),
			'ext' => array(
				0 => 'html',
				1 => 'txt'
			)
		);
		$this->displayName = $this->l('Platinum Theme PopUp Modules');
		$this->suc_msg = $this->l('Successfully Submitted');
		$this->description = $this->l('Platinum Theme Adds an Products Display Block in any where.');
		$this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
		if(!isset($this->context)){
			$this->context = Context::getContext();
        }
        if((isset($this->context->controller->controller_type)) && ($this->context->controller->controller_type == 'front' || $this->context->controller->controller_type == 'modulefront')){
			global $smarty;
			smartyRegisterFunction($smarty, 'block', 'thnxblockpopup_js', array('thnxblockpopup', 'thnxblockpopup_js'));
		}
	}
	public function install()
	{
		if(!parent::install()
		 || !$this->Register_Hooks()
		 || !$this->Register_Tabs()
		 || !$this->Register_SQL()
		 || !$this->SampleDataInstall()
		)
			return false;
		return true;
	}
	public function uninstall()
	{
		if(!parent::uninstall()
		 || !$this->UnRegister_Hooks()
		 || !$this->UnRegister_Tabs()
		 || !$this->UnRegister_SQL()
		)
			return false;
		return true;
	}
	public static function thnxblockpopup_js($params, $content, &$smarty)
	{
		if(isset($params['name']) && !empty($params['name']) && !empty($content)){
			self::$inlinejs[$params['name']] = $content;
		}
	}
	public function Register_Hooks()
	{
        $this->registerHook("displayHeader");
        $this->registerHook("displayfooter");
        $this->registerHook("displayBeforeBodyClosingTag");
        return true;
	}
	public function UnRegister_Hooks()
	{
	    $hook_id = Hook::getIdByName("displayBeforeBodyClosingTag");
	        if(isset($hook_id) && !empty($hook_id))
	        	$this->unregisterHook((int)$hook_id);
	    $hook_id2 = Hook::getIdByName("displayHeader");
	        if(isset($hook_id2) && !empty($hook_id2))
	        	$this->unregisterHook((int)$hook_id2);
	    $hook_id3 = Hook::getIdByName("displayfooter");
	        if(isset($hook_id3) && !empty($hook_id3))
	        	$this->unregisterHook((int)$hook_id3);
        return true;
	}
	public function Register_SQL()
	{
		$querys = array();
		if(file_exists(dirname(__FILE__).$this->mysql_files_url)){
			require_once(dirname(__FILE__).$this->mysql_files_url);
			if(isset($querys) && !empty($querys))
				foreach($querys as $query){
					if(!Db::getInstance()->Execute($query,false))
					    return false;
				}
		}
        return true;
	}
	public function UnRegister_SQL()
	{
		$querys_u = array();
		if(file_exists(dirname(__FILE__).$this->mysql_files_url)){
			require_once(dirname(__FILE__).$this->mysql_files_url);
			if(isset($querys_u) && !empty($querys_u))
				foreach($querys_u as $query_u){
					if(!Db::getInstance()->Execute($query_u,false))
					    return false;
				}
		}
        return true;
	}
	public function UnRegister_Tabs()
    {
        $tabs_lists = array();
        require_once(dirname(__FILE__) .$this->tabs_files_url);
        if(isset($tabs_lists) && !empty($tabs_lists)){
        	foreach($tabs_lists as $tab_list){
        	    $tab_list_id = Tab::getIdFromClassName($tab_list['class_name']);
        	    if(isset($tab_list_id) && !empty($tab_list_id)){
        	        $tabobj = new Tab($tab_list_id);
        	        $tabobj->delete();
        	    }
        	}
        } 
        $save_tab_id = (int)Tab::getIdFromClassName("Adminthnxthemedashboard");
        if($save_tab_id != 0){
        	$count = Tab::getNbTabs($save_tab_id);
        	if($count == 0){
        		if(isset($save_tab_id) && !empty($save_tab_id)){
        		    $tabobjs = new Tab($save_tab_id);
        		    $tabobjs->delete();
        		}
        	}
        }
        return true;
    }
	public function RegisterParentTabs()
	{
		$langs = Language::getLanguages();
		$adminmodules_id = (int)Tab::getIdFromClassName("IMPROVE");
		$save_tab_id = (int)Tab::getIdFromClassName("Adminthnxthemedashboard");
		if($save_tab_id != 0){
			return $save_tab_id;
		}else{
			$tab_listobj = new Tab();
			$tab_listobj->class_name = 'Adminthnxthemedashboard';
			$tab_listobj->id_parent = $adminmodules_id;
			$tab_listobj->module = $this->name;
			foreach($langs as $l)
			{
			    $tab_listobj->name[$l['id_lang']] = $this->l("Theme Settings");
			}
			if($tab_listobj->save())
				return (int)$tab_listobj->id;
			else
				return (int)$adminmodules_id;
		}
	}
	public function Register_Tabs()
    {
        $tabs_lists = array();
        $langs = Language::getLanguages();
        $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $save_tab_id = $this->RegisterParentTabs();
        require_once(dirname(__FILE__) .$this->tabs_files_url);
        if(isset($tabs_lists) && !empty($tabs_lists))
            foreach ($tabs_lists as $tab_list)
            {
                $tab_listobj = new Tab();
                $tab_listobj->class_name = $tab_list['class_name'];
                $tab_listobj->id_parent = $save_tab_id;
                if(isset($tab_list['module']) && !empty($tab_list['module'])){
                    $tab_listobj->module = $tab_list['module'];
                }else{
                    $tab_listobj->module = $this->name;
                }
                foreach($langs as $l)
                {
                    $tab_listobj->name[$l['id_lang']] = $this->l($tab_list['name']);
                }
                $tab_listobj->save();
            }
        return true;
    }
    public function _prepareHook()
    {
    		$this->newsletterRegistration();
    		if(empty($this->error)){
    			return true;
    		}else{
    			return false;
    		}
    }
    public function newsletterRegistration()
	{
		if (empty($_POST['email']) || !Validate::isEmail($_POST['email']))
			return $this->error = $this->l('Invalid email address.');
		else if ($_POST['action'] == '1')
		{
			$register_status = $this->isNewsletterRegistered($_POST['email']);

			if ($register_status < 1)
				return $this->error = $this->l('This email address is not registered.');

			if (!$this->unregister($_POST['email'], $register_status))
				return $this->error = $this->l('An error occurred while attempting to unsubscribe.');

			return $this->valid = $this->l('Unsubscription successful.');
		}
		/* Subscription */
		else if ($_POST['action'] == '0')
		{
			$register_status = $this->isNewsletterRegistered($_POST['email']);
			if ($register_status > 0)
				return $this->error = $this->l('This email address is already registered.');

			$email = pSQL($_POST['email']);
			if (!$this->isRegistered($register_status))
			{
				if (Configuration::get('NW_VERIFICATION_EMAIL'))
				{
					// create an unactive entry in the newsletter database
					if ($register_status == self::GUEST_NOT_REGISTERED)
						$this->registerGuest($email, false);

					if (!$token = $this->getToken($email, $register_status))
						return $this->error = $this->l('An error occurred during the subscription process.');

					$this->sendVerificationEmail($email, $token);

					return $this->valid = $this->l('A verification email has been sent. Please check your inbox.');
				}
				else
				{
					if ($this->register($email, $register_status))
						$this->valid = $this->l('You have successfully subscribed to this newsletter.');
					else
						return $this->error = $this->l('An error occurred during the subscription process.');

					if ($code = Configuration::get('NW_VOUCHER_CODE'))
						$this->sendVoucher($email, $code);

					if (Configuration::get('NW_CONFIRMATION_EMAIL'))
						$this->sendConfirmationEmail($email);
				}
			}
		}
	}
	protected function isRegistered($register_status)
	{
		return in_array(
			$register_status,
			array(self::GUEST_REGISTERED, self::CUSTOMER_REGISTERED)
		);
	}
	public function isNewsletterRegistered($customer_email)
	{
	 	$sql = 'SELECT `email`
	 			FROM '._DB_PREFIX_.'emailsubscription
	 			WHERE `email` = \''.pSQL($customer_email).'\'
	 			AND id_shop = '.$this->context->shop->id;

	 	if (Db::getInstance()->getRow($sql))
	 		return self::GUEST_REGISTERED;

	 	$sql = 'SELECT `newsletter`
	 			FROM '._DB_PREFIX_.'customer
	 			WHERE `email` = \''.pSQL($customer_email).'\'
	 			AND id_shop = '.$this->context->shop->id;

	 	if (!$registered = Db::getInstance()->getRow($sql))
	 		return self::GUEST_NOT_REGISTERED;

	 	if ($registered['newsletter'] == '1')
	 		return self::CUSTOMER_REGISTERED;

	 	return self::CUSTOMER_NOT_REGISTERED;
	}
	public function unregister($email, $register_status)
	{
		if ($register_status == self::GUEST_REGISTERED)
			$sql = 'DELETE FROM '._DB_PREFIX_.'emailsubscription WHERE `email` = \''.pSQL($_POST['email']).'\' AND id_shop = '.$this->context->shop->id;
		else if ($register_status == self::CUSTOMER_REGISTERED)
			$sql = 'UPDATE '._DB_PREFIX_.'customer SET `newsletter` = 0 WHERE `email` = \''.pSQL($_POST['email']).'\' AND id_shop = '.$this->context->shop->id;

		if (!isset($sql) || !Db::getInstance()->execute($sql))
			return false;

		return true;
	}
	public function registerGuest($email, $active = true)
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.'emailsubscription (id_shop, id_shop_group, email, newsletter_date_add, ip_registration_newsletter, http_referer, active)
				VALUES
				('.$this->context->shop->id.',
				'.$this->context->shop->id_shop_group.',
				\''.pSQL($email).'\',
				NOW(),
				\''.pSQL(Tools::getRemoteAddr()).'\',
				(
					SELECT c.http_referer
					FROM '._DB_PREFIX_.'connections c
					WHERE c.id_guest = '.(int)$this->context->customer->id.'
					ORDER BY c.date_add DESC LIMIT 1
				),
				'.(int)$active.'
				)';

		return Db::getInstance()->execute($sql);
	}
	public function register($email, $register_status)
	{
		if ($register_status == self::GUEST_NOT_REGISTERED)
			return $this->registerGuest($email);

		if ($register_status == self::CUSTOMER_NOT_REGISTERED)
			return $this->registerUser($email);

		return false;
	}
	public function registerUser($email)
	{
		$sql = 'UPDATE '._DB_PREFIX_.'customer
				SET `newsletter` = 1, newsletter_date_add = NOW(), `ip_registration_newsletter` = \''.pSQL(Tools::getRemoteAddr()).'\'
				WHERE `email` = \''.pSQL($email).'\'
				AND id_shop = '.$this->context->shop->id;

		return Db::getInstance()->execute($sql);
	}
	public function getToken($email, $register_status)
	{
		if (in_array($register_status, array(self::GUEST_NOT_REGISTERED, self::GUEST_REGISTERED)))
		{
			$sql = 'SELECT MD5(CONCAT( `email` , `newsletter_date_add`, \''.pSQL(Configuration::get('NW_SALT')).'\')) as token
					FROM `'._DB_PREFIX_.'emailsubscription`
					WHERE `active` = 0
					AND `email` = \''.pSQL($email).'\'';
		}
		else if ($register_status == self::CUSTOMER_NOT_REGISTERED)
		{
			$sql = 'SELECT MD5(CONCAT( `email` , `date_add`, \''.pSQL(Configuration::get('NW_SALT')).'\' )) as token
					FROM `'._DB_PREFIX_.'customer`
					WHERE `newsletter` = 0
					AND `email` = \''.pSQL($email).'\'';
		}

		return Db::getInstance()->getValue($sql);
	}
	public function sendVoucher($email, $code)
	{
		return Mail::Send($this->context->language->id, 'thnxpopnewsletter_voucher', Mail::l('Newsletter voucher', $this->context->language->id), array('{discount}' => $code), $email, null, null, null, null, null, dirname(__FILE__).'/mails/', false, $this->context->shop->id);
	}
	public function sendConfirmationEmail($email)
	{
		return Mail::Send($this->context->language->id, 'thnxpopnewsletter_conf', Mail::l('Newsletter confirmation', $this->context->language->id), array(), pSQL($email), null, null, null, null, null, dirname(__FILE__).'/mails/', false, $this->context->shop->id);
	}
	public function sendVerificationEmail($email, $token)
	{
		$verif_url = Context::getContext()->link->getModuleLink(
			'ps_emailsubscription', 'verification', array(
				'token' => $token,
			)
		);

		return Mail::Send($this->context->language->id, 'thnxpopnewsletter_verif', Mail::l('Email verification', $this->context->language->id), array('{verif_url}' => $verif_url), $email, null, null, null, null, null, dirname(__FILE__).'/mails/', false, $this->context->shop->id);
	}
    public function hookexecute()
	{
		$results = array();
		$blckpopup = blockpopupclass::GetPopUpBlock();
		// start
		// popuptype
		$id_customer = (int)$this->context->cart->id_customer;
		$id_guest = (int)$this->context->cart->id_guest;
		if($id_customer != 0){
			$id = 'c_'.$id_customer;		
		}else{
			$id = 'g_'.$id_guest;		
		}
		// end
		$i = 0;
		if(isset($blckpopup) && !empty($blckpopup)){
			foreach ($blckpopup as $blockpopup) {
				if(self::PageException($blockpopup['pages'])){
					$results[$i] = $blockpopup;
					$i++;
				}
			}
		}
		$this->context->smarty->assign(array('results' => $results));
		return $this->fetch('module:'.$this->name.'/views/templates/front/thnxblockpopup.tpl');
	}
	public static function PageException($exceptions = NULL)
	{
		if($exceptions == NULL)
			return false;
		$exceptions = explode(",",$exceptions);
		$page_name = Context::getContext()->controller->php_self;
		$this_arr = array();
		$this_arr[] = 'all_page';
		$this_arr[] = $page_name;
		if($page_name == 'category'){
			$id_category = Tools::getvalue('id_category');
			$this_arr[] = 'cat_'.$id_category;
		}elseif($page_name == 'product'){
			$id_product = Tools::getvalue('id_product');
			$this_arr[] = 'prd_'.$id_product;
			// Start Get Product Category
			$prd_cat_sql = 'SELECT cp.`id_category` AS id
			    FROM `'._DB_PREFIX_.'category_product` cp
			    LEFT JOIN `'._DB_PREFIX_.'category` c ON (c.id_category = cp.id_category)
			    '.Shop::addSqlAssociation('category', 'c').'
			    WHERE cp.`id_product` = '.(int)$id_product;
			$prd_catresults = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($prd_cat_sql);
			if(isset($prd_catresults) && !empty($prd_catresults))
			{
			    foreach($prd_catresults as $prd_catresult)
			    {
			        $this_arr[] = 'prdcat_'.$prd_catresult['id'];
			    }
			}
			// END Get Product Category
			// Start Get Product Manufacturer
			$prd_man_sql = 'SELECT `id_manufacturer` AS id FROM `'._DB_PREFIX_.'product` WHERE `id_product` = '.(int)$id_product;
			$prd_manresults = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($prd_man_sql);
			if(isset($prd_manresults) && !empty($prd_manresults))
			{
			    foreach($prd_manresults as $prd_manresult)
			    {
			        $this_arr[] = 'prdman_'.$prd_manresult['id'];
			    }
			}
			// END Get Product Manufacturer
			// Start Get Product SupplierS
			$prd_sup_sql = "SELECT `id_supplier` AS id FROM `"._DB_PREFIX_."product_supplier` WHERE `id_product` = ".(int)$id_product." GROUP BY `id_supplier`";
			$prd_supresults = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($prd_sup_sql);
			if(isset($prd_supresults) && !empty($prd_supresults))
			{
			    foreach($prd_supresults as $prd_supresult)
			    {
			        $this_arr[] = 'prdsup_'.$prd_supresult['id'];
			    }
			}
			// END Get Product SupplierS
		}elseif($page_name == 'cms'){
			$id_cms = Tools::getvalue('id_cms');
			$this_arr[] = 'cms_'.$id_cms;
		}elseif($page_name == 'manufacturer'){
			$id_manufacturer = Tools::getvalue('id_manufacturer');
			$this_arr[] = 'man_'.$id_manufacturer;
		}elseif($page_name == 'supplier'){
			$id_supplier = Tools::getvalue('id_supplier');
			$this_arr[] = 'sup_'.$id_supplier;
		}
		if(isset($this_arr)){
			foreach ($this_arr as $this_arr_val) {
				if(in_array($this_arr_val,$exceptions))
					return true;
			}
		}
		return false;
	}
    public static function isEmptyFileContet($path = null){
    	if($path == null)
    		return false;
    	if(file_exists($path)){
    		$content = Tools::file_get_contents($path);
    		if(empty($content)){
    			return false;
    		}else{
    			return true;
    		}
    	}else{
    		return false;
    	}
    }
    public function Register_Css()
    {
        if(isset($this->css_files) && !empty($this->css_files)){
        	$theme_name = $this->context->shop->theme_name;
    		$page_name = $this->context->controller->php_self;
    		$root_path = _PS_ROOT_DIR_.'/';
        	foreach($this->css_files as $css_file):
        		if(isset($css_file['key']) && !empty($css_file['key']) && isset($css_file['src']) && !empty($css_file['src'])){
        			$media = (isset($css_file['media']) && !empty($css_file['media'])) ? $css_file['media'] : 'all';
        			$priority = (isset($css_file['priority']) && !empty($css_file['priority'])) ? $css_file['priority'] : 50;
        			$page = (isset($css_file['page']) && !empty($css_file['page'])) ? $css_file['page'] : array('all');
        			if(is_array($page)){
        				$pages = $page;
        			}else{
        				$pages = array($page);
        			}
        			if(in_array($page_name, $pages) || in_array('all', $pages)){
        				if(isset($css_file['load_theme']) && ($css_file['load_theme'] == true)){
        					$theme_file_src = 'themes/'.$theme_name.'/assets/css/'.$css_file['src'];
        					if(self::isEmptyFileContet($root_path.$theme_file_src)){
        						$this->context->controller->registerStylesheet(
        							$css_file['key'],
        							$theme_file_src ,
        											array(
        												'media' => $media,
        												'priority' => $priority
        											)
        							);
        					}
        				}else{
        					$module_file_src = 'modules/'.$this->name.'/css/'.$css_file['src'];
        					if(self::isEmptyFileContet($root_path.$module_file_src)){
        						$this->context->controller->registerStylesheet(
        							$css_file['key'],
        							$module_file_src ,
        											array(
        												'media' => $media,
        												'priority' => $priority
        											)
        							);
        					}
        				}
    				}
        		}
        	endforeach;
        }
        return true;
    }
    public function Register_Js()
    {
        if(isset($this->js_files) && !empty($this->js_files)){
	    	$theme_name = $this->context->shop->theme_name;
			$page_name = $this->context->controller->php_self;
			$root_path = _PS_ROOT_DIR_.'/';
        	foreach($this->js_files as $js_file):
        		if(isset($js_file['key']) && !empty($js_file['key']) && isset($js_file['src']) && !empty($js_file['src'])){
        			$position = (isset($js_file['position']) && !empty($js_file['position'])) ? $js_file['position'] : 'bottom';
        			$priority = (isset($js_file['priority']) && !empty($js_file['priority'])) ? $js_file['priority'] : 50;
        			$page = (isset($css_file['page']) && !empty($css_file['page'])) ? $css_file['page'] : array('all');
        			if(is_array($page)){
        				$pages = $page;
        			}else{
        				$pages = array($page);
        			}
        			if(in_array($page_name, $pages) || in_array('all', $pages)){
	        			if(isset($js_file['load_theme']) && ($js_file['load_theme'] == true)){
	        				$theme_file_src = 'themes/'.$theme_name.'/assets/js/'.$js_file['src'];
	        				if(self::isEmptyFileContet($root_path.$theme_file_src)){
	        					$this->context->controller->registerJavascript(
	        						$js_file['key'],
	        						$theme_file_src ,
	        										array(
	        											'position' => $position,
	        											'priority' => $priority
	        										)
	        						);
	        				}
	        			}else{
		        			$module_file_src = 'modules/'.$this->name.'/js/'.$js_file['src'];
	        				if(self::isEmptyFileContet($root_path.$module_file_src)){
		        				$this->context->controller->registerJavascript(
		        					$js_file['key'],
		        					$module_file_src ,
		        									array(
		        										'position' => $position,
		        										'priority' => $priority
		        									)
		        					);
	        				}
	        			}
        			}
        		}
        	endforeach;
        }
        return true;
    }
	public function hookdisplayHeader($params)
	{
        if((isset($this->context->controller->controller_type)) && ($this->context->controller->controller_type == 'front' || $this->context->controller->controller_type == 'modulefront')){
			global $smarty;
			smartyRegisterFunction($smarty, 'block', 'thnxblockpopup_js', array('thnxblockpopup', 'thnxblockpopup_js'));
		}
		$base_url = $this->context->shop->getBaseURL(true, true);
    	Media::addJsDef(array('thnx_base_dir' => $base_url));
		$this->Register_Css();
		$this->Register_Js();
	}
	public function SampleDataInstall()
	{
	    $dummy_datas = array(
	            array(
	                'popuptype' => 'newsletter',
	                'layout_style' => 'general',
	                'product_item' => '',
	                'height' => '358px',
	                'width' => '800px',
	                'image' => '1.jpg',
	                'pages' => 'index',
	                'fromdate' => '2015-01-05 00:00:00',
	                'todate' => '2018-10-31 00:00:00',
	                'starttime' => '',
	                'staytime' => '',
	                'iscustomer' => 1,
	                'isguest' => 1,
	                'active' => 1,
	                'position' => 0,
	                'title' => 'Newsletter',
	                'subtitle' => 'Get timely updates from your favorite products',
	                'description' => '',
	            ),
	            array(
	                'popuptype' => 'custom',
	                'layout_style' => 'general',
	                'product_item' => '',
	                'height' => '358px',
	                'width' => '800px',
	                'image' => '1.jpg',
	                'pages' => 'product',
	                'fromdate' => '2015-01-05 00:00:00',
	                'todate' => '2018-10-31 00:00:00',
	                'starttime' => '',
	                'staytime' => '',
	                'iscustomer' => 1,
	                'isguest' => 1,
	                'active' => 1,
	                'position' => 1,
	                'title' => 'sale up to 50% off',
	                'subtitle' => 'special promotion',
	                'description' => 'Over +1000 new product was available on our store. Let come here and grab it. 
You certain will love it',
	            )
	        );
	    $id_lang = (int)Context::getContext()->language->id;
	    $id_shop = (int)Context::getContext()->shop->id;
	    if(isset($dummy_datas) && !empty($dummy_datas)){
	        $languages = Language::getLanguages(false);
	        $i = 1;
	        foreach($dummy_datas as $valu){
            $sqldumi2 = "INSERT INTO "._DB_PREFIX_."thnxblckpopuptbl(`popuptype`,`layout_style`,`product_item`,`height`,`width`,`image`,`pages`,`fromdate`,`todate`,`starttime`,`staytime`,`iscustomer`,`isguest`,`active`,`position`)VALUES('".$valu['popuptype']."','".$valu['layout_style']."','".$valu['product_item']."','".$valu['height']."','".$valu['width']."','".$valu['image']."','".$valu['pages']."','".$valu['fromdate']."','".$valu['todate']."','".$valu['starttime']."','".$valu['staytime']."',".(int)$valu['iscustomer'].",".(int)$valu['isguest'].",".(int)$valu['active'].",".(int)$valu['position'].");";
	                Db::getInstance()->execute($sqldumi2,false);
	                // Start Lang
	            foreach($languages as $language)
	            {
	                $sqldumi = "INSERT INTO "._DB_PREFIX_."thnxblckpopuptbl_lang(id_thnxblckpopuptbl,id_lang,title,subtitle,description)VALUES(".(int)$i.",".(int)$language['id_lang'].",'".$valu['title']."','".$valu['subtitle']."','".$valu['description']."');";
	                Db::getInstance()->execute($sqldumi,false);
	            }
	                // End Lang
	                // Start shop
	            $damisqs1 = "INSERT INTO "._DB_PREFIX_."thnxblckpopuptbl_shop(id_thnxblckpopuptbl,id_shop)VALUES(".$i.",".$id_shop.");";
	            Db::getInstance()->execute($damisqs1,false); 
	                // End shop
	        $i = $i + 1;    
	        }
	    }
	    return true;
	}
	public function hookdisplayBeforeBodyClosingTag($params)
	{
		if(isset(self::$inlinejs) && !empty(self::$inlinejs)){
			foreach (self::$inlinejs as $keyinlinejs => $valueinlinejs) {
				print $valueinlinejs;
			}
		}
	}
	public function hookdisplayfooter($params)
	{
		return $this->hookexecute();
	}
}