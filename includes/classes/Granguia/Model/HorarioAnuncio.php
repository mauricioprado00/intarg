<?
class Granguia_Model_HorarioAnuncio extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_anuncio',
			'hora_inicio1_lunes',
			'hora_fin1_lunes',
			'hora_inicio2_lunes',
			'hora_fin2_lunes',
			'hora_inicio1_martes',
			'hora_fin1_martes',
			'hora_inicio2_martes',
			'hora_fin2_martes',
			'hora_inicio1_miercoles',
			'hora_fin1_miercoles',
			'hora_inicio2_miercoles',
			'hora_fin2_miercoles',
			'hora_inicio1_jueves',
			'hora_fin1_jueves',
			'hora_inicio2_jueves',
			'hora_fin2_jueves',
			'hora_inicio1_viernes',
			'hora_fin1_viernes',
			'hora_inicio2_viernes',
			'hora_fin2_viernes',
			'hora_inicio1_sabado',
			'hora_fin1_sabado',
			'hora_inicio2_sabado',
			'hora_fin2_sabado',
			'hora_inicio1_domingo',
			'hora_fin1_domingo',
			'hora_inicio2_domingo',
			'hora_fin2_domingo',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public static function getDias(){
		return $dias = array(
			'lunes','martes','miercoles','jueves','viernes','sabado','domingo'
		);
	}
	public static function getDiaActual(){
		$dias = self::getDias();
		$idx_dia = date('N');//dia iso:
		return $dias[$idx_dia-1]; 
	}
	public static function checkOpen($hora_inicio, $hora_fin){
		if(empty($hora_inicio)||empty($hora_fin))
			return false;
		$current = time();
		$hora_inicio = strtotime($hora_inicio);
		$hora_fin = strtotime($hora_fin);
		return $current<=$hora_fin && $hora_inicio<=$current;
	}
	
	public function getAbiertoAhora(){
		$info = implode('', $this->getData());
		if(!$info)
			return false;
		$dia_actual = self::getDiaActual();
		$hora_inicio = $this->getData('hora_inicio1_'.$dia_actual);
		$hora_fin = $this->getData('hora_fin1_'.$dia_actual);
		if(self::checkOpen($hora_inicio, $hora_fin))
			return true;
		$hora_inicio = $this->getData('hora_inicio2_'.$dia_actual);
		$hora_fin = $this->getData('hora_fin2_'.$dia_actual);
		return self::checkOpen($hora_inicio, $hora_fin);
	}
	
	public function getDbTableName() 
	{
		return 'gg_horario';
	}
}
?>