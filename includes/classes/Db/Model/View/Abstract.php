<?
abstract class Db_Model_View_Abstract extends Db_Model_Abstract{
	protected function init(){
		parent::init();
	}
	private $attrname_to_fieldname = array();
	public function delete(){
		echo "El metodo de eliminar filas no esta soportado para vistas\n";
		die();
	}
	public function replace(){
		echo "El metodo de actualizar filas no esta soportado para vistas\n";
		die();
	}
	protected function setField($fieldname, $alias=null){
		if($alias===null || is_int($alias))
			$alias = $fieldname;
		$attrname = implode('_', explode('.', $alias));
		$this->attrname_to_fieldname[$attrname] = $fieldname;
		$this->setData($attrname, null);
		return($this);
	}
	private $from_parts = array();
	private $tables = array();
	protected function setTables($tablename, $join_condition=null, $alias=null, $join_method='left'){
		$tabledata = array('tablename'=>$tablename,'join_condition'=>$join_condition,'join_method'=>$join_method);
		if($alias===null){
			$this->tables[] = $tabledata;
			$idx = array_pop(array_keys($this->tables));
		}
		else{
			$this->tables[$alias] = $tabledata;
			$idx = $alias;
		}
		$this->from_parts[] = (array('type'=>'tables','idx'=>$idx));
		return($this);
	}
	public function addTable($tablename, $join_condition=null, $alias=null, $fields=array(), $join_method='left'){
		$this->setTables($tablename, $join_condition, $alias, $join_method);
		foreach($fields as $alias=>$fieldname){
			$this->setField($fieldname, $alias);
		}
		return($this);
	}
/* <nuevo para vistas> */
    private $views = array();
    public function addView(Db_Model_View_Abstract $view, $alias, $fields=array(), $join_condition=null, $join_method='left'){
        $viewdata = array('view'=>$view,'join_condition'=>$join_condition,'join_method'=>$join_method);
        $this->views[$alias] = $viewdata;
        $this->from_parts[] = (array('type'=>'views','idx'=>$alias));
		foreach($fields as $alias=>$fieldname){
			$this->setField($fieldname, $alias);
		}
        return($this);
    }
/* </nuevo para vistas> */
	private $custom_columns = array();
	public function addColumn($custom_select, $alias){
		$this->setData($alias);
		$this->custom_columns[$alias] = $custom_select;
		return($this);
	}
	private function customColumnsToSelect(){
		$select = array();
		foreach($this->custom_columns as $alias=>$custom_select){
			$select[] = $custom_select.' as '.$this->getDb()->nameToString($alias);
		}
		if(count($select))
			return(implode(', ', $select));
		return(null);
	}
	protected function getFrom(){
		$from = "";
		$tables_default = array('tablename'=>null,'join_condition'=>null,'join_method'=>'left');
		$views_default = array('view'=>null,'join_condition'=>null,'join_method'=>'left');
		foreach($this->from_parts as $info){
			$alias = $info['idx'];
			if($info['type']=='tables'){
			//foreach($this->tables as $alias=>$tabledata){
				$tabledata = $this->tables[$alias];
				extract($tables_default);
				extract($tabledata);
				$join = '';
				if(is_int($alias)){//no tiene alias
					$join = $this->getDb()->nameToString($tablename);
				}
				else{//tiene alias
					$join = $this->getDb()->nameToString($tablename).' '.$this->getDb()->nameToString($alias);
				}
				if($join_condition){
					$join = $join_method.' JOIN '.$join.' ON '.$join_condition;
				}
				$from[] = $join;
			}
			elseif($info['type']=='views'){
	/* <nuevo para vistas> */
			//foreach($this->views as $alias=>$viewdata){
				$viewdata = $this->views[$alias];
				extract($views_default);
				extract($viewdata);
				$join = '';
				if(is_int($alias)){//no tiene alias [esto nunca deberia ocurrir, siempre deben asignarle alias validos
					$join = $this->getDb()->nameToString($tablename);
				}
				else{//tiene alias
					$join = '('.$view->searchGetSql().') '.$this->getDb()->nameToString($alias);
				}
				if($join_condition){
					$join = $join_method.' JOIN '.$join.' ON '.$join_condition;
				}
				$from[] = $join;
			}
	/* </nuevo para vistas> */
		}
		return(implode(' ', $from));
	}
	protected function xgetFrom(){
		$from = "";
		$default = array('tablename'=>null,'join_condition'=>null,'join_method'=>'left');
		foreach($this->tables as $alias=>$tabledata){
			extract($default);
			extract($tabledata);
			$join = '';
			if(is_int($alias)){//no tiene alias
				$join = $this->getDb()->nameToString($tablename);
			}
			else{//tiene alias
				$join = $this->getDb()->nameToString($tablename).' '.$this->getDb()->nameToString($alias);
			}
			if($join_condition){
				$join = $join_method.' JOIN '.$join.' ON '.$join_condition;
			}
			$from[] = $join;
		}
/* <nuevo para vistas> */

		$default = array('view'=>null,'join_condition'=>null,'join_method'=>'left');
		foreach($this->views as $alias=>$viewdata){
			extract($default);
			extract($viewdata);
			$join = '';
			if(is_int($alias)){//no tiene alias [esto nunca deberia ocurrir, siempre deben asignarle alias validos
				$join = $this->getDb()->nameToString($tablename);
			}
			else{//tiene alias
				$join = '('.$view->searchGetSql().') '.$this->getDb()->nameToString($alias);
			}
			if($join_condition){
				$join = $join_method.' JOIN '.$join.' ON '.$join_condition;
			}
			$from[] = $join;
		}
/* </nuevo para vistas> */
		return(implode(' ', $from));
	}
	protected function getAttrToFieldTranslationTable(){
		return($this->attrname_to_fieldname);
	}
	protected function getColumnSelect(){
		//$columns = array_keys($this->getData());
		$select = array();
		$table_selections = $this->columnsToSelect($this->attrname_to_fieldname);
		$custom_selections = $this->customColumnsToSelect();
		if($table_selections)
			$select[] = $table_selections;
		if($custom_selections)
			$select[] = $custom_selections;
		if(!count($select)){
			foreach($this->tables as $alias=>$tabledata){
				$select[] = $this->getDb()->nameToString($alias).'.*';
			}
			foreach($this->views as $alias=>$viewdata){
				$select[] = $this->getDb()->nameToString($alias).'.*';
			}
		}
		$ret = implode(', ', $select);
		return($ret);
	}
	private $group_by = array();
	protected function setGroupBy(){
		$args = func_get_args();
		$this->group_by = $args;
	}
	protected function getGroupBy(){
		$group_by = array();
		foreach($this->group_by as $group_column){
			$group_by[] = $this->getDb()->nameToString($group_column);
		}
		$ret = count($group_by)?' GROUP BY '.implode(', ', $group_by):'';
		return($ret);
	}
	public function lockRead($force=true, $alias=null){
		foreach($this->from_parts as $alias=>$info){
			$alias = $info['idx'];
			//var_dump($info,$alias, array_keys($this->views));
			if($info['type']=='tables'){
				$tabledata = $this->tables[$alias];
				$tablename = $tabledata['tablename'];
				//$alias = $info['alias'];
				//echo ('lock tables '.$this->getDb()->nameToString($tablename))."\n";
				$this->getDb()->lockRead($tablename, $force, $alias);
			}
			elseif($info['type']=='views'){
				$data = $this->views[$alias];
				$view = $data['view'];
				//$view = $info['view'];
				//$alias = $info['alias'];
				$view->lockRead($force, $alias);
			}
		}
	}
}
?>