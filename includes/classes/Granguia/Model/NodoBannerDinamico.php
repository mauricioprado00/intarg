<?
class Granguia_Model_NodoBannerDinamico extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_nodo',
			'id_banner_dinamico',
			'posicion',
			'orden',
			'random',
			'cantidad_espacios_vertical',
			'cantidad_espacios_horizontal'
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getBannerDinamico($si_es_dinamico_tambien=true){
		if(!$this->hasIdBannerDinamico())
			return null;
		$id = $this->getIdBannerDinamico();
		if(!$id){
			if(!$si_es_dinamico_tambien)
				return null;
			else{
				$banner_dinamico = new Granguia_Model_BannerDinamicoAleatorio();
				$banner_dinamico
					->setCantidadEspaciosVertical($this->getCantidadEspaciosVertical())
					->setCantidadEspaciosHorizontal($this->getCantidadEspaciosHorizontal())
				;
				$banner_dinamico->setWhere(
					Db_Helper::equal('cantidad_espacios_vertical'),
					Db_Helper::equal('cantidad_espacios_horizontal')
				);
				$banner_dinamicos = $banner_dinamico->search(null,'ASC', null, 0, 'Granguia_Model_BannerDinamico');
				if(!$banner_dinamicos)
					return null;
				return array_shift($banner_dinamicos);
			}
		}

		$banner_dinamico = new Granguia_Model_BannerDinamico();
		$banner_dinamico->setId($this->getIdBannerDinamico());
		if(!$banner_dinamico->load())
			return null;
		return $banner_dinamico;
	}
	public function getBannersDinamicos($retornar_relacion=false){
		$relacion = $this->getRelation();
		if($retornar_relacion)
			return $relacion;
		return $this->NodoBannersDinamicos_to_BannersDinamicos( $relacion );
	}
	private function NodoBannersDinamicos_to_BannersDinamicos($relacion){
		if(!$relacion)
			return null;
		$banners_dinamicos = array();
		foreach($relacion as $nodo_banner_dinamico){
			$banner_dinamico = $nodo_banner_dinamico->getBannerDinamico();
			if(!$banner_dinamico){
				continue;
			}
			$banners_dinamicos[] = $banner_dinamico;
		}
		return $banners_dinamicos;
	}
	private function getRelation(){
		if(!$this->getIdNodo())
			return null;
		$wheres = array();
		$nodo_banner_dinamico = new self();
		$nodo_banner_dinamico
			->setIdNodo($this->getIdNodo())
		;
		$where[] = Db_Helper::equal('id_nodo');
		if($this->hasPosicion()){
			$nodo_banner_dinamico->setPosicion($this->getPosicion());
			$where[] = Db_Helper::equal('posicion');
		}
		$nodo_banner_dinamico->setWhereByArray($where);
		return $nodo_banner_dinamico->search('posicion, orden', 'ASC', null, 0, __CLASS__);
	}
	public function getBannersNoUsadosEnNodo(){
		$banner_dinamico = new Granguia_Model_BannerDinamico();
		$wheres = array();
		if($this->getIdNodo()){
			$where[] = Db_Helper::custom('{@id} NOT IN(SELECT {@id_banner_dinamico} FROM {@gg_nodo_banner_dinamico} WHERE {@id_nodo}={%s})',$this->getIdNodo());
			$banner_dinamico->setWhereByArray($where);
		}
		return $banner_dinamico->search(null, 'ASC', null, 0, __CLASS__);
	}

	//$wheres[] = Db_Helper::custom('{#path_url} like concat({@path_url},{%s})', '/%');
	public function getDbTableName() 
	{
		return 'gg_nodo_banner_dinamico';
	}
}
?>