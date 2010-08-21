<?php
class Frontend_Barrio_Block_Mapa extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('barrio/mapa.phtml');
	}
	public function checkData(){
		return $this->hasBarrio()&&$this->hasCategoria();
	}
	public function getAnuncios(){
		if(!parent::hasData('anuncios')){
			/** 
			@todo: falta agregar el filtro de categoria, solo esta filtrando por barrio y estado del anuncio
			**/
			$barrio = $this->getBarrio();
			$anuncio = new Granguia_Model_Anuncio();
			$anuncio->setIdBarrio($barrio->getId());
			$anuncio->setEstado(1);
			$anuncio->setWhere(Db_Helper::equal('id_barrio'), Db_Helper::equal('estado'));
			parent::setData('anuncios', $anuncio->search(null, 'ASC', null, 0, 'Granguia_Model_Anuncio'));
		}
		return parent::getData('anuncios');
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