<?
class Admin_Importador_Model_Importacion extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setArchivo(null);
	}
	public function getDbTableName() 
	{
		return 'bm_importacion';
	}
	protected function beforeDelete($data){
		$x = new self();
		$x->loadFromArray($data);
//		var_dump($this->getData(), $x->load());
//		var_dump($x->getArchivo());
//		if(file_exists($x->getArchivo()))
//			unlink($x->getArchivo());
//		die("on before");

		if(!$x->load())
			return(false);
		if(file_exists($x->getArchivo()))
			unlink($x->getArchivo());
		return(true);
	}
}
?>