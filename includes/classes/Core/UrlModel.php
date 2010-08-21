<?
class Core_UrlModel extends Base_Singleton{
	public static function getUrl($route='', $params=array()){
		foreach($params as $name=>$param)
			$params[$name] = $name.'='.$param;
		$params = implode('&', $params);
		$params = $params?'?'.$params:'';
		return(CONF_URL_APP.(Core_App::getInstance()->getFancyUrlEnabled()?'':'index.php/').$route.$params);
	}
	public static function cleanUrl($url){
		$base = CONF_URL_APP.(Core_App::getInstance()->getFancyUrlEnabled()?'':'index.php/');
		if(strpos($url, $base)===0){
			return substr($url, strlen($base));
		}
		return $url;
	}
	public static function urlToVar($string){
		return strtr($string, array_flip(self::getUrlVars()));
	}
	public static function varToUrl($string){
		return strtr($string, self::getUrlVars());
	}
	public static function autofilterFieldInput($object, $field, $straight=true){
		if(!($object instanceof Core_Object))
			return;
		if($straight)
			$filter = array('Core_UrlModel','urlToVar');
		else $filter = array('Core_UrlModel','varToUrl'); 
		$object->addAutofilterFieldInput($field, $filter);
	}
	public static function autofilterFieldOutput($object, $field, $straight=true){
		if(!($object instanceof Core_Object))
			return;
		if(!$straight)
			$filter = array('Core_UrlModel','urlToVar');
		else $filter = array('Core_UrlModel','varToUrl'); 
		$object->addAutofilterFieldOutput($field, $filter);
	}
	public static function getUrlVars($nombres_compuestos=true){
		$pre = $nombres_compuestos?'{!':'';
		$post = $nombres_compuestos?'}':'';
		$base = CONF_URL_APP.(Core_App::getInstance()->getFancyUrlEnabled()?'':'index.php/');
		return array(
			$pre.'uploads_url'.$post => $base.CONF_SUBPATH_UPLOADS,
			$pre.'skin_url'.$post => $base.CONF_SUBPATH_SKIN,
			$pre.'base_url'.$post => $base,
		);
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}
?>