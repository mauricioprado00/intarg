<?php













class Granguia_Cronjob_Base extends Core_Cronjob{
	private $_last_result;
	function getLastResult(){
		if(!isset($this->_last_result)){
			$r = new Granguia_Model_CronjobResult();
			$r->setName(get_class($this));
			$r->setWhere(Db_Helper::equal('name'));
			if($res = $r->search('id', 'DESC', 1, 0, 'Granguia_Model_CronjobResult')){
				$this->_last_result = $res[0];
			}
		}
		return $this->_last_result;
	}
	function getEndTimeLastResult(){
		if(!($lr = $this->getLastResult()))
			return null;
		return $lr->getFin();
	}
	function getStartTimeLastResult(){
		if(!($lr = $this->getLastResult()))
			return null;
		return $lr->getInicio();
	}
	function getDiffEndTimeLastResult(){
		if(($t = $this->getEndTimeLastResult())===null)
			return null;
		return time() - $t;
	}
	function getDiffStartTimeLastResult(){
		if(($t = $this->getStartTimeLastResult())===null)
			return null;
		return time() - $t;
	}
	function isDiffEndTimeLastResultGreaterThan($segundos = 3600){
		if(($diff = $this->getDiffEndTimeLastResult())===null)
			return true;
		//var_dump("comparando {$this->getEndTimeLastResult()} $diff>$segundos", $diff>$segundos);
		return $diff>$segundos;
	}
	function isDiffStartTimeLastResultGreaterThan($segundos = 3600){
		if(($diff = $this->getDiffStartTimeLastResult())===null)
			return true;
		return $diff>$segundos;
	}
	public function Verificar(){
		$minutos = (int) Granguia_Model_Config::findConfigValue('cronjob/'.get_class($this).'/cant_minutos', 10);
		if($minutos==0)//esta desactivado
			return false;
		$segundos = $minutos * 60;
		if(!$this->isDiffEndTimeLastResultGreaterThan($segundos)){
			return false;
		}
		return true;
	}
}
?>