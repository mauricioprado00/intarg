<?
/**
 *@referencia Actividad(id_actividad) Inta_Model_Actividad(id)
 *@referencia ResultadoEsperado(id_resultado_esperado) Inta_Model_ResultadoEsperado(id)
*/
class Inta_Model_ResultadoEsperadoActividad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_actividad',
			'id_resultado_esperado',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getNombre(){
		if($this->getResultadoEsperado()){
			return strip_tags($this->getResultadoEsperado()->getDescripcion());
		}
		return '';
	}
	private static function getSessionList(){
		$list = Core_App::getSession()->getVar('lista_nueva_actividad', __CLASS__);
		if(!$list||!is_object($list)||!($list instanceof Core_Collection)){
			$list = new Core_Collection();
			Core_App::getSession()->setVar('lista_nueva_actividad', $list, __CLASS__);
		}
		return $list;
	}
	public static function setSessionList($list){
		Core_App::getSession()->setVar('lista_nueva_actividad', $list, __CLASS__);
	}
	public static function clearSessionList(){
		Core_App::getSession()->setVar('lista_nueva_actividad', null, __CLASS__);
		return $this;
	}
	private function addInSession(){
		$list = $this->getSessionList();
		//$list[] = $this;
		$x = new self();
		$x->setData($this->getData());
		$x->setId('sess'.$list->count());
		$list->addItem($x);
		//var_dump($list->getItems());
		$this->setSessionList($list);
		return true;
	}
	private function editInSession(){
		$list = $this->getSessionList();
		$filtered = $list->addFilterEq('id', $this->getId());
//		var_dump(count($filtered));echo '<br />';
//		var_dump($this->getData());echo '<br />';
//		echo '<br />';echo '<br />';
		if($filtered){
			foreach($filtered as $item){
				//var_dump($item->getData());echo '<br />';
				$item->setData($this->getData());
				//var_dump($item->getData());echo '<br />';echo '<br />';
			}
			$this->setSessionList($list);
		}
		return true;
	}
	private function isSearchInSession(){
		$where = $this->getWhere();
		$where_string = $where->toString($this->getData());
		return preg_match('/`id`\s*=\s*[\']sess[0-9]+[\']/', $where_string);
	}
	private function searchInSession(){
		$list = $this->getSessionList();
		$filtered = $list->addFilterEq('id', $this->getId());
		//var_dump($filtered->count());
		return $filtered->getItems();
	}
	private function deleteInSession(){
		$list = $this->getSessionList();
		$filtered = $list->addFilterEq('id', $this->getId(), false, true);
		$this->setSessionList($filtered);
		//var_dump($filtered->count());
		return $this;
	}
	public function replace(){
		//var_dump($this->getIdActividad());
		if(!$this->getIdActividad()){
			return $this->addInSession();
		}
		else{
			return parent::replace();
		}
		return true;
	}
	public function update(){
		//var_dump($this->getIdActividad());
		if(!$this->getIdActividad()){
			return $this->editInSession();
		}
		else{
			return parent::replace();
		}
		return true;
	}
	public function delete($data=null){
		$_data = $data;
		if($data===null){
			$data = $this->getData();
		}
		if(strpos($data['id'], 'sess')===0){
			$this->deleteInSession();
			return true;
		}
		return parent::delete($_data);
	}
	public function search(){
		$args = func_get_args();
		if($this->isSearchInSession()){
			return $this->searchInSession();
		}
		return call_user_func_array(array('parent', 'search'), $args);
	}
	public static function getListParaActividad($id_actividad){
		$id_actividad = $id_actividad?$id_actividad:0;
		$r = c($resultado_esperado_actividad = new Inta_Model_ResultadoEsperadoActividad())
			->setIdActividad($id_actividad)
			->setWhere(Db_Helper::equal('id_actividad'))
			->search(null, null, null, null, get_class($resultado_esperado_actividad))
		;
		if(!$id_actividad){
//			$x = new self();
//			$x->setId('sess1');
//			$x->setWhereByArray(array(Db_Helper::equal('id')));
//			$where = $x->getWhere();
//			echo $where->toString($x->getData());
//			var_dump($x->isLoadFromSession());
			$list = self::getSessionList();
			if($list&&count($list)){
				//var_dump($list->getItems());
				foreach($list as $item){
					$r[] = $item;
				}
			}
		}
		return $r;
	}
	public static function flushSessionParaActividad($id_actividad){
		if(!$id_actividad)
			return false;
		$list = self::getSessionList();
		if($list){
			foreach($list as $item){
				$rea = new self();
				$rea->setData($item->getData());
				$rea->setIdActividad($id_actividad);
				$rea->setId(null);
				$rea->replace();
			}
		}
		self::clearSessionList();
	}

	public function getDbTableName() 
	{
		return 'inta_resultado_esperado_actividad';
	}
}
//	private $_actividad = null;
//	public function getActividad(){
//		if(!$this->hasIdActividad()||!($id_actividad = $this->getIdActividad()))
//			return null;
//		if(!isset($this->_actividad)||$this->_actividad->getId()!=$id_actividad){
//			$actividad = new Inta_Model_Actividad();
//			$actividad->setId($id_actividad);
//			if(!$actividad->load())
//				$actividad = null;
//			$this->_actividad = $actividad;
//		}
//		return $this->_actividad;
//	}
//	private $_resultado_esperado = null;
//	public function getResultadoEsperado(){
//		if(!$this->hasIdResultadoEsperado()||!($id_resultado_esperado = $this->getIdResultadoEsperado()))
//			return null;
//		if(!isset($this->_resultado_esperado)||$this->_resultado_esperado->getId()!=$id_resultado_esperado){
//			$resultado_esperado = new Inta_Model_ResultadoEsperado();
//			$resultado_esperado->setId($id_resultado_esperado);
//			if(!$resultado_esperado->load())
//				$resultado_esperado = null;
//			$this->_resultado_esperado = $resultado_esperado;
//		}
//		return $this->_resultado_esperado;
//	}
//
?>