<?
class Jqgrid_Block_Grid extends Core_Block_Template{
	public function __construct(){
		$this
			->setButtons(array())
			->setTemplate('jqgrid/grid.phtml')
			->setDatatype('xml')
			->setRowNumber(10)
			->appRowList(10,20,30)
			->setImgPath(Core_App::getLayout()->getSkinUrl('jqGrid/themes/basic/images'))
			->setSortName('id')
			->setViewRecords(true)
			->setSortOrder('desc')
			//->setCaption('Lista de\' ejemplo')
			->setCanEdit(true)
			->setCanAdd(false)
			->setCanDelete(false)
			->setCanSearch(true)
			->setBooleanData('can_export_as_html','full_width','view_records','can_add','can_edit','can_delete','can_search')
			->addArrayData('row_list','toolbar_edit_url','toolbar_delete_url')
			->setCanExportAsHtmlView(true)
			->setCanExportAsHtml(true)
			->setCanExportAsExcel(true)
			->setPage(1)
			//->setEditUrl('urledit')
		;
		$this->addAutofilterFieldInput('source', array($this, 'reset_translate_context'));
		
	}
	protected function reset_translate_context($new){
		$this->setTranslateContext($new);
		return $new;
	}
	private $_export_types = array();
	public function addExportType($filetype_description, $export_name, $xmllist_export_handler_class, $layout_descripcion=null){
		if(!isset($layout_descripcion)){
			$layout_descripcion = $this->__t('Sin agrupar');
		}
		$this->_export_types[] =$export_type = new Core_Object(array(
			'filetype_description'=>$filetype_description,
			'export_name'=>$export_name,
			'export_handler_class'=>$xmllist_export_handler_class,
			'layout_description'=>$layout_descripcion,
		));
		
		return $this;
	}
	public function getExportTypes(){
		return $this->_export_types;
	}
	public function getExportTypesNames(){
		$names = array();
		foreach($this->_export_types as $type=>$data){
			$names[] = $data->getExportName();
		}
		return $names;
	}
	public function canExport(){
		if($this->hasData('can_export')&&!$this->getData('can_export'))
			return false;
		return count($this->getExportTypes())>0;
	}
	public function clearExportTypes(){
		$this->_export_types = array();
	}
	
	protected function generarRandomId(){
		if(!$this->hasRandomId()){
			$id = $this->getNameInLayout();
			$id = $id.rand(0,1000);
	    	$this->setRandomId($id);
			$this->setData('table_id', $id.'_list');
			$this->setData('pager_id', $id.'_pager');
			$this->setData('table_container_id', $id.'_container');
			$this->setData('button_container_id', $id.'_button_container');
    	}
    	return($this);
	}
    protected function _allwaysBeforeToHtml(){
    	if($this->getCanExportAsExcel()){
			$this->addExportType('Descarga de Excel','excel','Jqgrid_XmlList_ExportHandler_Xlst_Downloader_Excel');
		}
		if($this->getCanExportAsHtml()){
			$this->addExportType('Descarga de Html','html_download','Jqgrid_XmlList_ExportHandler_Xlst_Downloader');
		}
		if($this->getCanExportAsHtmlView()){
			$this->addExportType('Vista de Html','html_view','Jqgrid_XmlList_ExportHandler_Xlst');
		}
		
    	
//    	$id = $this->generarRandomId();
		return($this);
	}
	private function replaceData($str){
		preg_match_all('([#][#]([^#]*)[#][#])', $str, $matches);
		if(count($matches)>1&&count($matches[1])){
			foreach($matches[1] as $key){
				//var_dump($key, $this->getData($key));
				$str = str_replace('##'.$key.'##', $this->getData($key), $str);
			}
		}
		return($str);
	}
	protected function _beforeToHtml(){
		if($this->hasButtons()){
			foreach($this->getButtons() as $boton){
				foreach($boton->getData() as $key=>$data){
					switch($key){
						 case 'action_url':{
							$data = explode(',', $data);
							$data[0] = $this->getUrl($data[0]);
							$data = implode(',', $data);
							$data = $this->replaceData($data);
							break;
						}
						case 'action_js':{
							$this->generarRandomId();
							$data = $this->replaceData($data);
							//$data = str_replace('##table_id##', $this->getTableId(), $data);
						}
					}
					$boton->setData($key, $data);
				}
			}
			
		}
		return($this);
	}
	public function onBeforeInsertChild($block){
		if(!$block->getCustomType())
			return($block);
		switch($block->getCustomType()){
			case 'boton':{
				
				break;
			}
		}
		$boton = new Core_Object();
		$boton->setRequireSelection('auto');
		foreach($block->getData() as $key=>$data){
			$boton->setData($key, $data);
		}
		$this->appButtons($boton);
		return(null);
	}

	public function addColumn_named_args(){
		$default = array (
		    'title' => 'default',
		    'name' => 'default',
		    'index' => 'id',
		    'width' => '55',
		    'align' => 'left',
		    'sortable' => 'true',
		    'hideinexport' => 'false',
  		);
  		$args = func_get_args();
		foreach($args[0] as $name=>$value)
			$default[$name] = $value;
		extract($default);
		return($this->addColumn($title, $name, $index, $width, $align, $sortable, $hideinexport));
		die();
	}
	public function addColumn($title, $name, $index, $width, $align='left', $sortable=true, $hideinexport=false){
		$columna = new Core_Object();
		$columna
			->setTitle($title)
			->setName($name)
			->setIndex($index)
			->setWidth($width)
			->setAlign($align)
			->setSortable($sortable&&$sortable!='false'?true:false)
			->setHideinexport($hideinexport)
		;
		$this->_addColumn($columna);
	} 
	private function _addColumn($columna){
		$this->appColumns($columna);
	}
}
?>