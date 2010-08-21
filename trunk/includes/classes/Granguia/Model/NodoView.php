<?
class Granguia_Model_NodoView extends Granguia_Model_Nodo{
	public function init(){
		parent::init();
		$datafields = array(
			"nombre",
			"tipo",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function replace(){}
	public function delete(){}
	public function update(){}
	public function insert(){}
	public function getDbTableName() 
	{
		return 'gg_nodo_view';
	}
}
?>