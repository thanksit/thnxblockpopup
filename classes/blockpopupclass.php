<?php

class blockpopupclass extends ObjectModel
{
	public $id;
	public $id_thnxblckpopuptbl;
	public $popuptype;
	public $layout_style;
	public $product_item;
	public $height;
	public $width;
	public $image;
	public $pages;
	public $fromdate;
	public $todate;
	public $starttime;
	public $staytime;
	public $iscustomer;
	public $isguest;
	public $active;
	public $position;
	public $title;
	public $subtitle;
	public $description;
	public $dontshow;
	public static $definition = array(
		'table' => 'thnxblckpopuptbl',
		'primary' => 'id_thnxblckpopuptbl',
		'multilang' => true,
		'fields' => array(
				'title' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString','lang' => true),
                'subtitle' =>       array('type' => self::TYPE_STRING, 'validate' => 'isString','lang' => true),
				'description' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml','lang' => true),
				'popuptype' =>		array('type' => self::TYPE_STRING, 'validate' => 'isString'),
				'layout_style' =>	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
				'product_item' =>	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
				'height' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
                'width' =>     		array('type' => self::TYPE_STRING, 'validate' => 'isString'),
                'image' =>          array('type' => self::TYPE_STRING, 'validate' => 'isString'),
                'dontshow' =>       array('type' => self::TYPE_STRING, 'validate' => 'isString'),
                'pages' =>       	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
				'starttime' =>		array('type' => self::TYPE_STRING, 'validate' => 'isString'),
				'staytime' =>		array('type' => self::TYPE_STRING, 'validate' => 'isString'),
				'iscustomer' =>		array('type' => self::TYPE_STRING, 'validate' => 'isString'),
				'isguest' =>		array('type' => self::TYPE_STRING, 'validate' => 'isString'),
				'fromdate' =>		array('type' => self::TYPE_DATE, 'validate' => 'isString'),
				'todate' =>			array('type' => self::TYPE_DATE, 'validate' => 'isString'),
				'position' =>		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
				'active' =>			array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
		),
	);
	public function __construct($id = null, $id_lang = null, $id_shop = null)
	{
        Shop::addTableAssociation('thnxblckpopuptbl', array('type' => 'shop'));
                parent::__construct($id, $id_lang, $id_shop);
    }
    public function update($null_values = false)
    {
    	$image = false;
        if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name'])){
            $image = $this->processImage($_FILES);
        }
        if($image){
            $this->image = $image;
        }else{
        	$this->image = self::getImageByID($this->id);
        }
        if(!parent::update($null_values))
            return false;
        return true;
    }
    public function add($autodate = true, $null_values = false)
    {
        if($this->position <= 0)
            $this->position = self::getTopPosition() + 1;
        $image = false;
        if(isset($this->image) && !empty($this->image)){
        	$image = $this->image;
        }else{
        	$image = $this->processImage($_FILES);
        }
        if($image){
            $this->image = $image;
        }
        if(!parent::add($autodate, $null_values) || !Validate::isLoadedObject($this))
            return false;
        return true;
    }
    public function processImage($FILES) {
        if (isset($FILES['image']) && isset($FILES['image']['tmp_name']) && !empty($FILES['image']['tmp_name'])) {
                $ext = substr($FILES['image']['name'], strrpos($FILES['image']['name'], '.') + 1);
                $id = time();
                $file_name = $id . '.' . $ext;
                $path = _PS_MODULE_DIR_ .'thnxblockpopup/img/' . $file_name;
                if (!move_uploaded_file($FILES['image']['tmp_name'], $path))
                    return false;         
                else
                    return $file_name;   
        }else{
        	return false;
        }
    }
    public static function getTopPosition()
    {
        $sql = 'SELECT MAX(`position`)
                FROM `'._DB_PREFIX_.'thnxblckpopuptbl`';
        $position = DB::getInstance()->getValue($sql);
        return (is_numeric($position)) ? $position : -1;
    }
    public static function getImageByID($identify = null)
    {
    	if($identify == null){
    		return false;
    	}
        $sql = 'SELECT `image` FROM `'._DB_PREFIX_.'thnxblckpopuptbl` WHERE `id_thnxblckpopuptbl` = '.(int)$identify;
        $image = DB::getInstance()->getValue($sql);
        if(isset($image) && !empty($image)){
        	return $image;
        }else{
        	return false;
        }
    }
    public function updatePosition($way, $position)
    {
        if (!$res = Db::getInstance()->executeS('
            SELECT `id_thnxblckpopuptbl`, `position`
            FROM `'._DB_PREFIX_.'thnxblckpopuptbl`
            ORDER BY `position` ASC'
        ))
            return false;
        if(!empty($res))
        foreach($res as $thnxblckpopuptbl)
            if((int)$thnxblckpopuptbl['id_thnxblckpopuptbl'] == (int)$this->id)
        $moved_thnxblckpopuptbl = $thnxblckpopuptbl;
        if(!isset($moved_thnxblckpopuptbl) || !isset($position))
            return false;
        $queryx = ' UPDATE `'._DB_PREFIX_.'thnxblckpopuptbl`
        SET `position`= `position` '.($way ? '- 1' : '+ 1').'
        WHERE `position`
        '.($way
        ? '> '.(int)$moved_thnxblckpopuptbl['position'].' AND `position` <= '.(int)$position
        : '< '.(int)$moved_thnxblckpopuptbl['position'].' AND `position` >= '.(int)$position.'
        ');
        $queryy = ' UPDATE `'._DB_PREFIX_.'thnxblckpopuptbl`
        SET `position` = '.(int)$position.'
        WHERE `id_thnxblckpopuptbl` = '.(int)$moved_thnxblckpopuptbl['id_thnxblckpopuptbl'];
        return (Db::getInstance()->execute($queryx,false)
        && Db::getInstance()->execute($queryy,false));
    }
    // START ALL SELECTS VALUE
    public function SimpleProductS()
    {
        $id_lang = (int)Context::getContext()->language->id;
        $sql = 'SELECT p.`id_product`, pl.`name`
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
                WHERE pl.`id_lang` = '.(int)$id_lang.' ORDER BY pl.`name`';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }
    public function AllProductS()
    {
        $rs = array();
        $rslt = array();
        $rs =  $this->SimpleProductS();
        $i = 0;
        foreach($rs as $r){
            $rslt[$i]['id'] = 'prd_'.$r['id_product'];
            $rslt[$i]['name'] = $r['name'];
            $i++;
        }
        return $rslt;
    }
    public function AllCategorieS()
    {
        $rs = array();
        $rslt = array();
        $id_lang = Context::getContext()->language->id;
        $rs =  Category::getCategories($id_lang,true,false);
        $i = 0;
        foreach($rs as $r){
            $rslt[$i]['id'] = 'cat_'.$r['id_category'];
            $rslt[$i]['name'] = $r['name'];
            $i++;
        }
        return $rslt;
    }
    public function AllSupplierS()
    {
        $rs = array();
        $rslt = array();
        $rs =  Supplier::getSuppliers();
        $i = 0;
        foreach($rs as $r){
            $rslt[$i]['id'] = 'sup_'.$r['id_supplier'];
            $rslt[$i]['name'] = $r['name'];
            $i++;
        }
        return $rslt;
    }
    public function GetManufacturers()
    {
        $rs = array();
        $rslt = array();
        $rs =  Manufacturer::getManufacturers();
        $i = 0;
        foreach($rs as $r){
            $rslt[$i]['id'] = 'man_'.$r['id_manufacturer'];
            $rslt[$i]['name'] = $r['name'];
            $i++;
        }
        return $rslt;
    }
    public static function GetPopUpBlock()
    {

		$id_guest = (int)Context::getContext()->customer->id_guest;
		$id_customer = (int)Context::getContext()->customer->id;
		$id_lang = (int)Context::getContext()->language->id;
		$id_shop = (int)Context::getContext()->shop->id;
		$date = date("Y-m-d");
		$sql = 'SELECT * FROM `'._DB_PREFIX_.'thnxblckpopuptbl` pb 
		       INNER JOIN `'._DB_PREFIX_.'thnxblckpopuptbl_lang` pbl ON (pb.`id_thnxblckpopuptbl` = pbl.`id_thnxblckpopuptbl` AND pbl.`id_lang` = '.$id_lang.')
		       INNER JOIN `'._DB_PREFIX_.'thnxblckpopuptbl_shop` pbs ON (pb.`id_thnxblckpopuptbl` = pbs.`id_thnxblckpopuptbl` AND pbs.`id_shop` = '.$id_shop.')
		       ';
		$sql .= ' WHERE pb.`active` = 1 AND pb.fromdate <= "'.$date.'" AND pb.todate >= "'.$date.'" ';
		if($id_customer != 0)
			$sql .= ' AND pb.iscustomer = 1 ';
		if($id_guest != 0 && $id_customer == 0)
			$sql .= ' AND pb.isguest = 1 ';
		$sql .= ' ORDER BY pb.`position` ASC ';
		return Db::getInstance()->executeS($sql);
    }
    public static function GetIndividualItem($items=NULL,$pref=NULL,$string=false)
    {
        if($pref == NULL)
            return false; 
        if($items == NULL)
            return false;  
        $results = array();
        $results_str = '';
        $items_arr = explode(",",$items);
        if(isset($items_arr) && !empty($items_arr)){
            foreach($items_arr as $item_ar){
                if(strpos($item_ar,$pref) !== false){
                    $results[] = str_replace($pref.'_',"",$item_ar);
                }
            }
            $results_str = implode(",",$results);
        }
        if($string == false)
            return $results;
        else
            return $results_str;
    }
}