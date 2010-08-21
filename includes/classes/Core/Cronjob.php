<?
class Core_Cronjob extends Core_Singleton{
	public static $cronjobs;
	public static $d;
	public static $m;
	public static $Y;
	public static $G;
	public static $i;
	public static $N;
	private $cantidad_registros_a_guardar = 2;
	private $registra_salida = 1;
	private $salida;
	function _construct(){
		parent::_construct();
		$this->setCantidadRegistrosAGuardar(2);
		//self::AppendCronjobs($this);
	}
	private function setSalida($salida){
		$this->salida = $salida;
	}
	private function getSalida(){
		return($this->salida);
	}
	private function getSalidaFormateada(){
		return(get_class($this).': '."\n".$this->salida);
	}
	
	protected function setRegistraSalida($registra){
		$this->registra_salida = $registra?true:false;
	}
	protected function getRegistraSalida(){
		return($this->registra_salida);
	}
	protected function setCantidadRegistrosAGuardar($cant){
		if(!isset($cant))
			$cant = null;
		elseif($cant<=0)
			$cant = 0;
		return($this->cantidad_registros_a_guardar = $cant);
	}
	protected function getCantidadRegistrosAGuardar(){
		return($this->cantidad_registros_a_guardar);
	}
	public function getDirectorioDeClase(){
		return(self::getCronjobDirectory().'/'.get_class($this));
	}
	public function crearDirectorioDeClase(){
		$directorio = $this->getDirectorioDeClase();
		if(file_exists($directorio))
			return($directorio);
		mkdir($directorio,'777');
		return($directorio);
	}
	public static public function getCronjobDirectory(){
		return(
			dirname(__FILE__).'/../cronjobs'
		);
	}
	public static function AppendCronjobs($cronjob){
		$args = func_get_args();
		if(count($args)>1){
			return self::AppendCronjobs($args);
			
		}
		elseif(is_string($cronjob)){
			$cronjob = new $cronjob;
			if(!$cronjob)
				return false;
		}
		elseif(is_array($cronjob)){
			$c = 0;
			$cronjobs = $cronjob;
			foreach($cronjobs as $cronjob){
				if(self::AppendCronjobs($cronjob))
					$c++;
			}
			return $c;
		}
		elseif(!($cronjob instanceof Core_Cronjob)){
			return false;
		}
		self::$cronjobs[] = $cronjob;
		return true;
	}
	public static function esNumeroDia($numero){
		return(self::$d==$numero);
	}
	public static function esNumeroMes($numero){
		return(self::$m==$numero);
	}
	public static function esNumeroAnio($numero){
		return(self::$Y==$numero);
	}
	public static function esHora($numero){//del 0 al 23
		return(self::$G==$numero);
	}
	public static function esMinuto($numero){
		return(self::$i==$numero);
	}
	public static function esLunes(){
		return(self::$N==1);
	}
	public static function esMartes(){
		return(self::$N==2);
	}
	public static function esMiercoles(){
		return(self::$N==3);
	}
	public static function esJueves(){
		return(self::$N==4);
	}
	public static function esViernes(){
		return(self::$N==5);
	}
	public static function esSabado(){
		return(self::$N==6);
	}
	public static function esDomingo(){
		return(self::$N==7);
	}
	public static function esEnero(){
		return(self::$m==1);
	}
	public static function esFebrero(){
		return(self::$m==2);
	}
	public static function esMarzo(){
		return(self::$m==3);
	}
	public static function esAbril(){
		return(self::$m==4);
	}
	public static function esMayo(){
		return(self::$m==5);
	}
	public static function esJunio(){
		return(self::$m==6);
	}
	public static function esJulio(){
		return(self::$m==1+1);
	}
	public static function esAgosto(){
		return(self::$m==1+2);
	}
	public static function esSeptiembre(){
		return(self::$m==1+3);
	}
	public static function esOctubre(){
		return(self::$m==1+4);
	}
	public static function esNoviembre(){
		return(self::$m==1+5);
	}
	public static function esDiciembre(){
		return(self::$m==1+6);
	}
	public static function getNumeroDia(){
		return(self::$d);
	}
	public static function getNumeroMes(){
		return(self::$m);
	}
	public static function getNumeroAnio(){
		return(self::$Y);
	}
	public static function getHora(){//del 0 al 23
		return(self::$G);
	}
	public static function getMinuto(){
		return(self::$i);
	}

	function Verificar(){//deberia verificar si se ejecuta
		return(false);
	}
	function Ejecutar(){
		return(false);
	}
	function Init(){
		self::$i = date('i');
		self::$N = date('N');
		self::$G = date('G');
		self::$d = date('d');
		self::$m = date('m');
		self::$Y = date('Y');
	}
	public function Polling(){
		//echo "iniciando...\n";
		foreach(self::$cronjobs as $cronjob){
			$force = (isset($_GET['force']) && is_array($_GET['force']) && in_array(get_class($cronjob),$_GET['force']));

			//echo get_class($cronjob);
			//var_dump($cronjob);
			echo "verificando ".get_class($cronjob)."\n";
			if($force){
				echo "forzando ".get_class($cronjob)."\n";
			}
			if($force||$cronjob->Verificar()){
				$cronjob->RegistrarEjecucion();
				echo "ejecutando ".get_class($cronjob)."\n";
				$cronjob->iniciarSalida();
				$res = $cronjob->Ejecutar();
				$cronjob->finalizarSalida();
				echo "resultado ".$res."\n";
//				echo "salida: ".$res."\n";
				echo $cronjob->getSalidaFormateada();
				$cronjob->RegistrarResultado($res);
			}
		}
		die();
	}
	private function RegistraEjecucion(){
		$c = $this->getCantidadRegistrosAGuardar();
		return($c===null||$c>0);
	}
	private function RegistrarEjecucion(){
		if(!$this->RegistraEjecucion())
			return;
		$r = Granguia_Model_CronjobResult::Registrar(get_class($this));
//		$link = new DB();
//		$sql = 'INSERT INTO '.DBT_CRONJOBS_RESULTS.'
//			SET 
//				name = \''.get_class($this).'\',
//				inicio = now()';
//		$link->Query($sql);
		$this->BorrarResultadosViejos();
	}
	private function RegistrarResultado($resultado){
		if(!$this->RegistraEjecucion())
			return;
		$salida = null;
		if($this->getRegistraSalida()){
			$salida = $this->getSalida();
		}
		return Granguia_Model_CronjobResult::Actualizar(get_class($this), $resultado, $salida);
	}
	private function BorrarResultadosViejos(){
		$cant = $this->getCantidadRegistrosAGuardar();
		if($cant===null)
			return;
		return Granguia_Model_CronjobResult::EliminarViejos(get_class($this), $cant);
	}
	private function iniciarSalida(){
		ob_start();
	}
	private function finalizarSalida(){
		$this->setSalida(ob_get_contents());
		ob_end_clean();
	} 
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}
?>