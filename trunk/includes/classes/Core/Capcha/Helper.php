<?
class Core_Capcha_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function getUrlShow(){
		return('capcha/show/'.md5(microtime(true)));
	}
	public static function getDefaultName(){
		return('capcha_code');
	}
	public static function validarPost($fieldname=null){
		$fieldname = $fieldname!==null?$fieldname:Core_Capcha_Helper::getDefaultName();
		$post = Core_Http_Post::getParameters('core_object');
		if(!$post->hasData($fieldname))
			return(false);
		$value = $post->getData($fieldname);
		return(self::validarCodigo($value)); 
	}
	public static function validarCodigo($code){
		if(!$code)
			return(false);
		$img = new Core_Capcha_SecurImage();
		$valid = $img->check($code);
		return($valid?true:false);
	}
}
?>