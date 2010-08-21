<?php
class Frontend_Barrio_Block_Mapa extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('barrio/mapa.phtml');
	}
	public function checkData(){
		return $this->hasBarrio()&&$this->hasCategoria();
	}
//	public function getAnuncioDestacado(){
//		/*Este metodo lo utiliza el bloque Frontend_Anuncio_Block_Visor para ver que anuncio mostrar por defecto*/
//		/**
//		@todo: resolver el anuncio destacado en la categoria y barrio actual.
//		*/
//		if(!parent::hasData('anuncio_destacado')){
//			$anuncios = $this->getAnuncios();
//			if($anuncios){
//				$keys = array_keys($anuncios);
//				$randidx = rand(0,count($keys)-1);
//				$anuncio_destacado = $anuncios[$keys[$randidx]];
//			}
//			else $anuncio_destacado = null;
//			parent::setData('anuncio_destacado', $anuncio_destacado);
//		}
//		return parent::getData('anuncio_destacado');
//	}
	public function getAnuncios(){
		if(!parent::hasData('anuncios')){
			/** 
			@todo: falta agregar el filtro de categoria, solo esta filtrando por barrio y estado del anuncio
			**/
			$barrio = $this->getBarrio();
			$categoria = $this->getCategoria();
			$anuncio = new Granguia_Model_Anuncio();
			$wheres = array(
				Db_Helper::equal('id_nodo', null, false),//debe ser distinto de null (publicado)
				Db_Helper::equal('id_barrio'),//debe ser del barrio indicado
				Db_Helper::equal('id_categoria'),//debe ser del barrio indicado
				Db_Helper::equal('estado')//
			);
			$anuncio
				->setIdBarrio($barrio->getId())
				->setEstado(1)
				//->setIdNodo(null)
				->setIdCategoria($categoria->getId())
				->setWhereByArray($wheres)
			;
			//$anuncio->setWhere($wheres);
			parent::setData('anuncios', $anuncio->search(null, 'ASC', null, 0, 'Granguia_Model_Anuncio'));
		}
		return parent::getData('anuncios');
	}
	public function getSugerencias(){
		if(!($barrio = $this->getBarrio()))
			return null;
		if(!($categoria = $this->getCategoria()))
			return null;
		$anuncio = new Granguia_Model_Anuncio();
		$_mv = $mainview = new Granguia_Db_Model_View_Generic();
		$_v = $view_anuncios_activos_categoria = new Granguia_Db_Model_View_Generic();  
		$_v->addTable($anuncio->getDbTableName(), null, 'a');
		$_v->addColumn('DISTINCT a.id_barrio', 'id_barrio');
		$_v
			->setEstado(1)
			//->setIdNodo(null)
			->setIdCategoria($categoria->getId())
		;
		$_v->setWhere(Db_Helper::equal('estado'), Db_Helper::equal('id_categoria'));
		$_mv->addView($_v, 'a', array('id_barrio'=>'a.id_barrio'));
		$_mv->addTable(
			$barrio->getDbTableName(), 'b.id = a.id_barrio', 'b', 
			array(
				'id'=>'b.id', 'nombre'=>'b.nombre', 'imagen_mapa'=>'b.imagen_mapa', 'id_nodo'=>'b.id_nodo'
			)
		);
		//$_v->addTable($barrio->getDbTableName(), null, 'b');
		//echo $_mv->searchGetSql();
		return $_mv->search(null, 'ASC', null, 0, get_class($barrio));
	}
	public function getTiposPuntos(){
		$anuncios = $this->getAnuncios();
		if(!$anuncios)
			return null;
		$tipos_puntos = array();
		foreach($anuncios as $anuncio){
			$tipo_punto = $anuncio->getTipoPunto();
			if(!$tipo_punto)
				continue;
			$tipos_puntos[$tipo_punto->getId()] = $tipo_punto;
		}
		return $tipos_puntos;
	}
}
?>