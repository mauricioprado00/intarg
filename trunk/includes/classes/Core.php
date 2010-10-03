<?
class Core extends Core_Singleton{
	public function create($class){
		return new $class;
	}
	public static function getObject(){
		$args = func_get_args();
		if(!$args)
			return null;
		$o = $args[0];
		if(is_string($o)){
			$args = array_slice($args, 1);
			$classname = self::getRewriteClass($o);
			if(class_exists($classname)){
				$o = @(new $classname());
				call_user_func_array(array($o, '__construct'), $args);
			}
			else{
				return null;
			}
		}
		return $o;
	}
	public static function getTypeObject($type, $o){
		$classname = self::getTypeObjectClass($type, $o);
	}
	public static function getRewriteClass($classname){
		$rw = Core_App::getInstance()->getConfig()->getClassRewrites();
		if(isset($rw[$classname])){
			return $rw[$classname];
		}
		return $classname;
	}
	public static function getTypeObjectClass($type, $o){
		$value = $o;
		$value = explode('/', $value);
		$modulo = 'Core';
		$cant = count($value);
		if($cant>2)
			return(null);
		elseif($cant==2){
			$modulo = array_shift($value);
		}
		$classname = $modulo.'_'.$type.'_'.$value[0];
		if(class_exists('Core_'.$classname)){
			$classname = 'Core_'.$classname;
		}
		if(!class_exists($classname)){
			echo "no existe clase $classname\n";
			return(null);
		}
		return $classname;
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}
?>