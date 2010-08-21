<?
class Where{
	private $operador;
	private $comparaciones;
	public function Where($op){
		$args = func_get_args();
		switch($op){
			case '=':{
				if(count($args)!=3)
					return(false);
				$this->comparaciones = array_slice($args,1);
				break;
			}
			case 'like':{
				if(count($args)!=3)
					return(false);
			}
		}
	}
}
?>