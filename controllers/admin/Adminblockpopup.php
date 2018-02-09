<?php

class AdminblockpopupController extends ModuleAdminController
{

    public function __construct()
    {
        $this->table = 'thnxblckpopuptbl';
        $this->className = 'blockpopupclass';
        $this->lang = true;
        $this->deleted = false;
        $this->module = 'thnxblockpopup';
        $this->explicitSelect = true;
        $this->_defaultOrderBy = 'position';
        $this->allow_export = false;
        $this->_defaultOrderWay = 'DESC';
        $this->bootstrap = true;
        if (Shop::isFeatureActive()) {
            Shop::addTableAssociation($this->table, array('type' => 'shop'));
        }
            parent::__construct();
        $this->fields_list = array(
            'id_thnxblckpopuptbl' => array(
                    'title' => $this->l('Id'),
                    'width' => 100,
                    'type' => 'text',
            ),
            'title' => array(
                    'title' => $this->l('Title'),
                    'width' => 60,
                    'type' => 'text',
            ),
            'layout_style' => array(
                    'title' => $this->l('Layout Style'),
                    'width' => 220,
                    'type' => 'text',
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'width' => 60,
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false
            )
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?')
            )
        );
        parent::__construct();
    }
    public function init()
    {
        parent::init();
        $this->_join = 'LEFT JOIN '._DB_PREFIX_.'thnxblckpopuptbl_shop sbp ON a.id_thnxblckpopuptbl=sbp.id_thnxblckpopuptbl && sbp.id_shop IN('.implode(',', Shop::getContextListShopID()).')';
        $this->_select = 'sbp.id_shop';
        $this->_defaultOrderBy = 'a.position';
        $this->_defaultOrderWay = 'DESC';
        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
            $this->_group = 'GROUP BY a.id_thnxblckpopuptbl';
        }
        $this->_select = 'a.position position';
    }
    public function setMedia()
    {
        parent::setMedia();
        $this->addJqueryUi('ui.widget');
        $this->addJqueryPlugin('tagify');
        $this->addJqueryPlugin('select2');
    }
    public static function AllPageExceptions()
    {
        $id_lang = (int)Context::getContext()->language->id;
        $sql = 'SELECT p.`id_product`, pl.`name`
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
                WHERE pl.`id_lang` = '.(int)$id_lang.' ORDER BY pl.`name`';
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $id_lang = Context::getContext()->language->id;
        $categories =  Category::getCategories($id_lang, true, false);
        $controllers = Dispatcher::getControllers(_PS_FRONT_CONTROLLER_DIR_);
        if (isset($controllers)) {
            ksort($controllers);
        }
        $Manufacturers =  Manufacturer::getManufacturers();
        $Suppliers =  Supplier::getSuppliers();
        $rslt = array();
        $rslt[0]['id'] = 'all_page';
        $rslt[0]['name'] = 'All Pages';
        $i = 1;
        if (isset($controllers)) {
            foreach ($controllers as $r => $v) {
                $rslt[$i]['id'] = $r;
                $rslt[$i]['name'] = 'Page : '.ucwords($r);
                $i++;
            }
        }
        if (isset($Manufacturers)) {
            foreach ($Manufacturers as $r) {
                $rslt[$i]['id'] = 'man_'.$r['id_manufacturer'];
                $rslt[$i]['name'] = 'Manufacturer : '.$r['name'];
                $i++;
            }
        }
        if (isset($Suppliers)) {
            foreach ($Suppliers as $r) {
                $rslt[$i]['id'] = 'sup_'.$r['id_supplier'];
                $rslt[$i]['name'] = 'Supplier : '.$r['name'];
                $i++;
            }
        }
        if (isset($categories)) {
            foreach ($categories as $cats) {
                $rslt[$i]['id'] = 'cat_'.$cats['id_category'];
                $rslt[$i]['name'] = 'Category : '.$cats['name'];
                $i++;
            }
        }
        if (isset($products)) {
            foreach ($products as $r) {
                $rslt[$i]['id'] = 'prd_'.$r['id_product'];
                $rslt[$i]['name'] = 'Product : '. $r['name'];
                $i++;
            }
        }
        if (isset($categories)) {
            foreach ($categories as $cats) {
                $rslt[$i]['id'] = 'prdcat_'.$cats['id_category'];
                $rslt[$i]['name'] = 'Category Product: '.$cats['name'];
                $i++;
            }
        }
        if (isset($Manufacturers)) {
            foreach ($Manufacturers as $r) {
                $rslt[$i]['id'] = 'prdman_'.$r['id_manufacturer'];
                $rslt[$i]['name'] = 'Manufacturer Product : '.$r['name'];
                $i++;
            }
        }
        if (isset($Suppliers)) {
            foreach ($Suppliers as $r) {
                $rslt[$i]['id'] = 'prdsup_'.$r['id_supplier'];
                $rslt[$i]['name'] = 'Supplier Product : '.$r['name'];
                $i++;
            }
        }
        return $rslt;
    }
    public static function order_by_val()
    {
        $order_by_val = array(
                array(
                    'id' => 'id_product',
                    'name' => 'Product ID'
                ),
                array(
                    'id' => 'price',
                    'name' => 'Price'
                ),
                array(
                    'id' => 'date_add',
                    'name' => 'Created Date'
                ),
                array(
                    'id' => 'date_upd',
                    'name' => 'Update Date'
                )
            );
        return $order_by_val;
    }
    public static function order_way_val()
    {
        $order_way_val = array(
                array(
                    'id' => 'ASC',
                    'name' => 'Assending'
                ),
                array(
                    'id' => 'DESC',
                    'name' => 'Desending'
                ),
            );
        return $order_way_val;
    }
    public static function image_type_val()
    {
        $image_type_val = array();
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'image_type` WHERE `products` = 1 ORDER BY `name` ASC';
        $results = Db::getInstance()->executeS($sql);
        if (isset($results) && !empty($results)) {
            $i = 0;
            foreach ($results as $result) {
                $image_type_val[$i]['id'] = $result['name'];
                $image_type_val[$i]['name'] = ucwords(str_replace("_", " ", $result['name']));
                $i++;
            }
        }
        return $image_type_val;
    }
    public static function layout_style_val()
    {
        $layout_style_val = array();
        // $theme_path =  _PS_THEME_DIR_.'modules/thnxblockpopup/views/templates/front/layout/';
        // $mod_path =  _PS_MODULE_DIR_.'thnxblockpopup/views/templates/front/layout/';
        // if (file_exists($theme_path.'default.tpl')) {
        //     $file_lists = array_diff(scandir($theme_path), array('..', '.'));
        // } else {
           //  $file_lists = array_diff(scandir($mod_path), array('..', '.'));
        // }
        // if (isset($file_lists) && !empty($file_lists)) {
        //     $i = 0;
        //     foreach ($file_lists as $key => $value) {
        $layout_style_val[0]['id'] = "general";
        $layout_style_val[0]['name'] = "General Style";
        $layout_style_val[1]['id'] = "classic";
        $layout_style_val[1]['name'] = "Classic Style";
        //         $i++;
        //     }
        // }
        return $layout_style_val;
    }
    public static function slider_style_val()
    {
        $slider_style_val = array(
            array(
                'id' => 'general',
                'name' => 'General'
            ),
            array(
                'id' => 'slider',
                'name' => 'Slider'
            ),
            array(
                'id' => 'carousel',
                'name' => 'Carousel'
            ),
        );
        return $slider_style_val;
    }
    public function renderForm()
    {
        $image_url = false;
        $thnximage_src = '';
        $image_size = file_exists($image_url) ? filesize($image_url) / 1000 : false;
        $init_url = _MODULE_DIR_.'thnxblockpopup/img/';
        $init_path = _PS_MODULE_DIR_.'thnxblockpopup/img/';
        $popuptype[] = array('id' => 'newsletter','name' => 'Newsletter');
        $popuptype[] = array('id' => 'custom','name' => 'Custom');
        $bpdc = new blockpopupclass();
        $order_by_val = self::order_by_val();
        $order_way_val = self::order_way_val();
        $image_type_val = self::image_type_val();
        $layout_style_val = self::layout_style_val();
        $slider_style_val = self::slider_style_val();
        if (Tools::getvalue('id_thnxblckpopuptbl')) {
            $clsobj = new blockpopupclass(Tools::getvalue('id_thnxblckpopuptbl'));
            $product_item_defvalues = $clsobj->product_item;
            $thnximage = $clsobj->image;
        } else {
            $product_item_defvalues = '';
            $thnximage = '';
        }

        if (isset($thnximage) && !empty($thnximage) && file_exists($init_path.$thnximage)) {
            $thnximage_src = '<img src="'.$init_url.$thnximage.'" style="width:150px;height:auto;">';
        }
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Display Products'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'desc' => $this->l('Enter Your Block Title'),
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Sub Title'),
                    'name' => 'subtitle',
                    'desc' => $this->l('Enter Your Block subtitle'),
                    'lang' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Description'),
                    'name' => 'description',
                    'desc' => $this->l('Enter Your Block Description'),
                    'lang' => true,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select PopUp Type: '),
                    'name' => 'popuptype',
                    'required'=>true,
                    'options' => array(
                        'query' => $popuptype,
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'selectchange',
                    'name' => 'snt_selectchange',
                    'hideclass' => 'selecttwotypeclass',
                    'dependency' => array(
                        'custom' => 'description',
                        )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Layout Style'),
                    'name' => 'layout_style',
                    'required'=>true,
                    'options' => array(
                        'query' => $layout_style_val,
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'selecttwotype',
                    'label' => $this->l('Which Page You Want to Display'),
                    'placeholder' => $this->l('Please Type Your Page Controller Name.'),
                    'initvalues' => self::AllPageExceptions(),
                    'name' => 'pages',
                    'desc' => $this->l('Please Type Your Specific Page Name,Category name,Product Name,For All Product specific Category select category product: category name.<br>For showing All page Type: All Page. For Home Page Type:index.')
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Upload Image'),
                    'name' => 'image',
                    'desc' => $thnximage_src,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('PopUp Height'),
                    'name' => 'height',
                    'class' => 'fixed-width-sm',
                    'desc' => $this->l('Enter Your PopUp Height in px. (default:358px)'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('PopUp Width'),
                    'name' => 'width',
                    'class' => 'fixed-width-sm',
                    'desc' => $this->l('Enter Your PopUp Width in px. (default:800px)'),
                ),
                array(
                    'type' => 'date',
                    'label' => $this->l('PopUp Active From Date .'),
                    'name' => 'fromdate',
                    'desc' => $this->l('Enter Your PopUp Active From Date.'),
                ),
                array(
                    'type' => 'date',
                    'label' => $this->l('PopUp Active To Date '),
                    'name' => 'todate',
                    'desc' => $this->l('Enter Your PopUp Active To Date.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('PopUp Showing Time After Page Load.'),
                    'name' => 'starttime',
                    'class' => 'fixed-width-sm',
                    'desc' => $this->l('PopUp Showing Time After Page Load In Millisecound.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('How Many Time Staying This PopUp.'),
                    'name' => 'staytime',
                    'class' => 'fixed-width-sm',
                    'desc' => $this->l('How Many Time Staying This PopUp In Millisecound.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Customer Can Show This PopUp.'),
                    'name' => 'iscustomer',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'iscustomer',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'iscustomer',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Guest Can Show This PopUp.'),
                    'name' => 'isguest',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'isguest',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'isguest',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),array(
                    'type' => 'switch',
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );
        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association:'),
                'name' => 'checkBoxShopAsso',
            );
        }
        if (!($blockpopupclass = $this->loadObject(true))) {
            return;
        }
        $this->fields_form['submit'] = array(
            'title' => $this->l('Save   '),
            'class' => 'button'
        );
        return parent::renderForm();
    }
    public function renderList()
    {
        if (isset($this->_filter) && trim($this->_filter) == '') {
            $this->_filter = $this->original_filter;
        }
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
    public function initToolbar()
    {
          parent::initToolbar();
    }
    public function processPosition()
    {
        if ($this->tabAccess['edit'] !== '1') {
            $this->errors[] = Tools::displayError('You do not have permission to edit this.');
        } else if (!Validate::isLoadedObject($object = new blockpopupclass((int)Tools::getValue($this->identifier, Tools::getValue('id_thnxblckpopuptbl', 1))))) {
            $this->errors[] = Tools::displayError('An error occurred while updating the status for an object.').' <b>'.
            $this->table.'</b> '.Tools::displayError('(cannot load object)');
        }
        if (!$object->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position'))) {
            $this->errors[] = Tools::displayError('Failed to update the position.');
        } else {
            $object->regenerateEntireNtree();
            Tools::redirectAdmin(self::$currentIndex.'&'.$this->table.'Orderby=position&'.$this->table.'Orderway=asc&conf=5'.(($id_thnxblckpopuptbl = (int)Tools::getValue($this->identifier)) ? ('&'.$this->identifier.'='.$id_thnxblckpopuptbl) : '').'&token='.Tools::getAdminTokenLite('Adminblockpopup'));
        }
    }
    public function ajaxProcessUpdatePositions()
    {
        $id_thnxblckpopuptbl = (int)(Tools::getValue('id'));
        $way = (int)(Tools::getValue('way'));
        $positions = Tools::getValue($this->table);
        if (is_array($positions)) {
            foreach ($positions as $key => $value) {
                $pos = explode('_', $value);
                if ((isset($pos[1]) && isset($pos[2])) && ($pos[2] == $id_thnxblckpopuptbl)) {
                    $position = $key + 1;
                    break;
                }
            }
        }
        $blockpopupclass = new blockpopupclass($id_thnxblckpopuptbl);
        if (Validate::isLoadedObject($blockpopupclass)) {
            if (isset($position) && $blockpopupclass->updatePosition($way, $position)) {
                Hook::exec('action'.$this->className.'Update');
                die(true);
            } else {
                    die('{"hasError" : true, errors : "Can not update blockpopupclass position"}');
            }
        } else {
                die('{"hasError" : true, "errors" : "This blockpopupclass can not be loaded"}');
        }
    }
}
