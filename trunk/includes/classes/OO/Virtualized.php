<?

/**
 * Virtualized
 * Permite agregar metodos virtuales, que solo llaman a otros métodos que posea el objeto. 
 * Permite una delegacion limpia si la funcion no esta ya sobreescrita (no redefinida, ni sobrecargada)
 * @package c:\appserv\www\practica
 * @author mauricio
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class OO_Virtualized{
	private $virtualMethods;
	/*$this->virtualMethods['nombreMetodoVirtual'] = array(nombreMetodoReal, staticArg1, staticArg2, ...  parametros de usuario)*/
  /**
   * Virtualized::addVirtualMethod(string, string, mixed, mixed ...)
   *
   * agrega un metodo para delegar con parametros estaticos + parametros de usuario
   * @param string $virtualMethodName: nombre del metodo a agregar
   * @param string $realMethodName: nombre del metodo real a llamar
   * @onlyThisParams boolean indica si el usuario final puede agregar mas parametros además de los estaticos
   * @extraParams parametros estaticos
   * @return
   */
	protected function addVirtualMethod($virtualMethodName,$realMethodName,$onlyThisParams){
		$args = func_get_args();
		if(count($args)<2)
			return(false);
		$args = array_slice($args, 3);
		$this->virtualMethods[NamingHelper::UnderScoreToCamelCase($virtualMethodName)] = array(
			'realMethodName'=>$realMethodName,
			'staticArguments'=>$args,
			'onlyThisParams'=>($onlyThisParams?true:false)
		);
		return(true);
	}
  /**
   * Virtualized::removeVirtualMethod()
   *
   * @param mixed $virtualMethodName
   * @return
   */
	protected function removeVirtualMethod($virtualMethodName){
		if(isset($this->virtualMethods[$virtualMethodName])){
			unset($this->virtualMethods[$virtualMethodName]);
			return(true);
		}
		return(false);
	}
  /**
   * Virtualized::__call()
   *
   * @param mixed $method
   * @param mixed $arguments
   * @return
   */
	public function __call($virtualMethodName, $arguments){
		//var_dump('Accesor',$virtualMethodName,$arguments);
		//var_dump($this);
		if(!isset($this->virtualMethods[$virtualMethodName])){
			Base_Errors::triggerError('Call to undefined method '.get_class($this).'::'.$virtualMethodName.'()');
		}
		extract($this->virtualMethods[$virtualMethodName]);
		$arguments = array_merge($staticArguments, $arguments);
		return(
			call_user_method_array($realMethodName, $this, $arguments)
		);
	}
	
	
	
	
  /**
   * Virtualized::exportVirtualMethods()
   * Devuelve la declaracion del conjunto de funciones virtualizadas hasta el momento en el objeto.
   * @return
   */
	public function exportVirtualMethods(){
		$methods = array();
		foreach($this->virtualMethods as $virtualMethodName=>$data){
			extract($data);
			$static_arg_list = implode('',explode("\n",var_export($staticArguments,true)));
			if(!$onlyThisParams){
				$methods[] = "
	public function $virtualMethodName(){
		\$arguments = func_get_args();
		\$arguments = array_merge($static_arg_list, \$arguments);
		return(call_user_method_array('$realMethodName', \$this, \$arguments));
	}";
			}
			else{
				$methods[] = "
	public function $virtualMethodName(){
		return(call_user_method_array('$realMethodName', \$this, \$arguments = func_get_args()));
	}";
			}
		}
		$methods = implode("\n", $methods);
		return($methods);
	}
}