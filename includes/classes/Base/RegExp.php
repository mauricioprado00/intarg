<?
class Base_RegExp{
	private $re;
	public function Base_RegExp($re){
		$this->setRegExp($re);
	}
	public function setRegExp($re){
		$this->re = $re;
	}
	public function executeOn($subject){
		$retorno = array();
		preg_match_all($this->re,$subject,$retorno);
		return($retorno);
	}
	public function replace($subject, $replace){
		return(preg_replace($this->re, $replace, $subject));
	}
	public function match($subject,&$matches){
		return(preg_match($this->re, $subject, $matches));
	}
}