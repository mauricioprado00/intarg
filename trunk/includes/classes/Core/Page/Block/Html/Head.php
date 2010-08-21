<?
class Core_Page_Block_Html_Head extends Core_Block_Template{
	function __construct(){
		parent::__construct();
		$this->addCustomBlockType('script', 'Core_Page_Block_Html_Head_Script');
		$this->addCustomBlockType('css', 'Core_Page_Block_Html_Head_Css');
		$this->setTemplate('page/html/head.phtml');
	}
	function addCss_named_args($args){
		extract($args);
		if(!isset($script))
			return($this);
		//var_dump($args,isset($ifie7),!isset($script),$ifie7?true:false,(isset($ifie7)&&$ifie7));
		if(isset($if)&&$if){
			//call_user_method_array('addIfCss', $this, $script);
			$script = is_array($script)?$script:array($script);
			$this->addIfCss($script, $if);
		}
		else{
			call_user_method_array('addCss', $this, $script);
			//$this->addCss($script);
		}
	}
	function removeCss_named_args($args){
		extract($args);
		if(!isset($script))
			return($this);
		//var_dump($args,isset($ifie7),!isset($script),$ifie7?true:false,(isset($ifie7)&&$ifie7));
		if(isset($if)&&$if){
			//call_user_method_array('addIfCss', $this, $script);
			$script = is_array($script)?$script:array($script);
			$this->removeIfCss($script, $if);
		}
		else{
			call_user_method_array('removeCss', $this, $script);
			//$this->addCss($script);
		}
	}
	function addJs(){
		$js_files = func_get_args();
		if(!count($js_files))
			return($this);
		$prev = array();
		if($this->hasJs()){
			$prev = $this->getJs();
		}
		foreach($js_files as $js_file){
			array_push($prev, $js_file);
		}
		$this->setJs($prev);
		//echo "agregando js $js\n";
		return($this);
	}
	function removeJs(){
		$js_files = func_get_args();
		if(!count($js_files)||!$this->hasJs())
			return($this);
		$prev = $this->getJs();
		foreach($js_files as $js_file){
			$key = array_search($js_file, $prev);
			$first = array_slice($prev, 0, $key);
			if(isset($prev[$key+1]))
				$last = array_slice($prev, $key+1);
			else $last = array();
			$prev = array_merge($first,$last);
			//array_push($prev, $js_file);
			//echo "quitando js $js_file\n";
		}
		$this->setJs($prev);
		return($this);
	}
	function removeCss(){
		$css_files = func_get_args();
		if(!count($css_files)||!$this->hasCss())
			return($this);
		$prev = $this->getCss();
		foreach($css_files as $css_file){
			$key = array_search($css_file, $prev);
			$first = array_slice($prev, 0, $key);
			if(isset($prev[$key+1]))
				$last = array_slice($prev, $key+1);
			else $last = array();
			$prev = array_merge($first,$last);
			//array_push($prev, $css_file);
			//echo "quitando css $css_file\n";
		}
		$this->setCss($prev);
		return($this);
	}
	function addCss(){
		$js_files = func_get_args();
		if(!count($js_files))
			return($this);
		$prev = array();
		if($this->hasCss()){
			$prev = $this->getCss();
		}
		foreach($js_files as $js_file){
			array_push($prev, $js_file);
		}
		$this->setCss($prev);
		//echo "agregando js $js\n";
		return($this);
	}
	function addIfCss($js_files,$if){
		//$js_files = func_get_args();
		if(!count($js_files))
			return($this);
		$prev = array();
		if($this->hasIfCss()){
			$prev = $this->getIfCss();
		}
		if(!isset($prev[$if]))
			$prev[$if] = array();
		foreach($js_files as $js_file){
			array_push($prev[$if], $js_file);
		}
		$this->setIfCss($prev);
		//echo "agregando js $js\n";
		return($this);
	}
	function removeIfCss($css_files,$if){
		//$css_files = func_get_args();
		if(!count($css_files)||!$this->hasIfCss())
			return($this);
		$prev = $this->getIfCss();
		if(!isset($prev[$if]))
			return($this);
		foreach($css_files as $css_file){
			$key = array_search($css_file, $prev[$if]);
			$first = array_slice($prev[$if], 0, $key);
			if(isset($prev[$if][$key+1]))
				$last = array_slice($prev[$if], $key+1);
			else $last = array();
			$prev[$if] = array_merge($first,$last);
			//array_push($prev[$if], $css_file);
		}
		$this->setIfCss($prev);
		//echo "agregando css $css\n";
		return($this);
	}
}
?>