<?
class Granguia_Model_Contador extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_tipo',
			'info1',/*255*/
			'info2',/*500*/
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public static function Contar($nombre_tipo, $info1, $info2=null, $uk=null){
		if(!($tipo = Granguia_Model_TipoContador::getTipoContadorByName($nombre_tipo)))
			return null;
		$contador = new self();
		$contador
			->setIdTipo($tipo->getId())
			->setInfo1($info1)
			->setInfo2($info2)
		;
		if(isset($uk)){
			$contador->setUk(md5($uk));
		}
		return $contador->insert()?true:false;
	}
	public static function ContarAccesoCategoria($id_categoria,$id_barrio){/*ok*/
		return self::Contar('ACCESO_CATEGORIA',$id_categoria, $id_barrio, Core_Session::getId().date('Ymd').$id_categoria.$id_barrio);
	}
	public static function ContarClickAnuncio($id_anuncio){/*ok*/
		return self::Contar('CLICK_ANUNCIO',$id_anuncio, null, Core_Session::getId().date('Ymd').$id_anuncio);
	}
	public static function ContarClickBannerDinamico($id_banner_dinamico, $url=null){/*ok*/
		return self::Contar('CLICK_BANNER_DINAMICO',$id_banner_dinamico, $url, Core_Session::getId().date('Ymd').$id_banner_dinamico.$url);
	}
	public static function ContarClickBannerCarrousel($id_banner_carrousel, $url=null){/*ok*/
		return self::Contar('CLICK_BANNER_CARROUSEL',$id_banner_carrousel, $url, Core_Session::getId().date('Ymd').$id_banner_carrousel);
	}
	public static function ContarInicioSesion($ip){/*ok*/
		return self::Contar('INICIO_SESION',$ip, null, Core_Session::getId().date('Ymd').$ip);
	}
	public static function ContarClickMinisitio($id_anuncio, $url=null){/*ok, si la url es null entonces es sitio externo*/
		return self::Contar('CLICK_MINISITIO',$id_anuncio, $url, Core_Session::getId().date('Ymd').$id_anuncio);
	}
	public static function ContarAccesoSesion($ip, $path_url=null){/*ok*/
		return self::Contar('ACCESO_SESION',$ip, $path_url, Core_Session::getId().date('Ymd').$ip.$path_url);
	}
	public static function marcarProcesado($nombre_tipo, $max_id){
		if(!($tipo = Granguia_Model_TipoContador::getTipoContadorByName($nombre_tipo)))
			return false;
		$x = new self();
		$x->getDb()->open();
		$tabla = $x->getDb()->nameToString($x->getDbTableName());
		$campo = $x->getDb()->nameToString('procesado');
		$valor = $x->getDb()->valueToString('1');
		$campo_id = $x->getDb()->nameToString('id');
		$valor_max_id = $x->getDb()->valueToString($max_id);
		$campo_id_tipo = $x->getDb()->nameToString('id_tipo');
		$valor_id_tipo = $x->getDb()->valueToString($tipo->getId());
		$sql = 'UPDATE '.$tabla.' SET '.$campo.'='.$valor.' WHERE '.$campo_id.'<='.$valor_max_id.' AND '.$campo_id_tipo.'='.$valor_id_tipo;
		$x->getDb()->exec($sql);
		$x->getDb()->close();
	}
	public static function AccesoCategoriaMarcarProcesado($max_id){
		return self::marcarProcesado('ACCESO_CATEGORIA', $max_id);
	}
	public static function ClickAnuncioMarcarProcesado($max_id){
		return self::marcarProcesado('CLICK_ANUNCIO',$max_id);
	}
	public static function ClickBannerDinamicoMarcarProcesado($max_id){
		return self::marcarProcesado('CLICK_BANNER_DINAMICO',$max_id);
	}
	public static function ClickBannerCarrouselMarcarProcesado($max_id){
		return self::marcarProcesado('CLICK_BANNER_CARROUSEL',$max_id);
	}
	public static function InicioSesionMarcarProcesado($max_id){
		return self::marcarProcesado('INICIO_SESION',$max_id);
	}
	public static function ClickMinisitioMarcarProcesado($max_id){
		return self::marcarProcesado('CLICK_MINISITIO',$max_id);
	}
	public static function AccesoSesionMarcarProcesado($max_id){
		return self::marcarProcesado('ACCESO_SESION',$max_id);
	}
	public static function Limpiar($limpiar_fecha){
		$x = new self();
		$x->getDb()->open();
		$tabla = $x->getDb()->nameToString($x->getDbTableName());
		
		$campo_procesado = $x->getDb()->nameToString('procesado');
		$valor_procesado = $x->getDb()->valueToString('1');

		$campo_fecha = $x->getDb()->nameToString('fecha');
		$valor_fecha = $x->getDb()->valueToString(Mysql_Helper::filterDateInput($limpiar_fecha));
		
		$sql = 'DELETE FROM '.$tabla.' WHERE '.$campo_procesado.'='.$valor_procesado.' AND date('.$campo_fecha.')<='.$valor_fecha;
		$x->getDb()->exec($sql);
		$x->getDb()->close();
	}
	public function getDbTableName() 
	{
		return 'gg_contador';
	}
}
?>