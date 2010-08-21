<?
class Base_Error{
	private $mensaje;
	private $informacion;
	private $code;
	public function Error($mensaje,$informacion,$code=null){
		$this->mensaje = $mensaje;
		$this->informacion = $informacion;
		$this->code = $code;
	}
	public function getMensaje(){
		return($this->mensaje);
	}
	public function getInformacion(){
		return($this->informacion);
	}
	public function getCode(){
		return($this->code);
	}
}

?>