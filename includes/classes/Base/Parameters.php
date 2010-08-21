<?

class Base_Parameters{
	private static function checkTypes($arguments,$types,$canBeNullFrom){
		$keys = array_keys($arguments);
		$i = 0;
		$return = true;
		foreach($types as $key=>$types){
			$type = gettype($types);
			if($types===null){
				$i++;
				continue;
			}
			if(!in_array($type,array('string','NULL'))){
				Base_Errors::triggerError('array\'s elements in types parameter must be string type',2);
			}

			$types = explode('||', $types);
			
			$valor = $arguments[$keys[$i]];
			if(//cuando hay error?
				!in_array(gettype($valor),$types)			//si no coincide el tipo
				&&								//y
				(
					$canBeNullFrom===null		//no puede ser null 
					||$canBeNullFrom>$i			//o si puede pero no este indice
					|| $valor!==null			//o no es null
				)
				
			){
				if(count($types)>1)
					$type = ' one of the following types ('.implode(', ',$types).')';
				elseif(is_array($types))$type = '\''.$types[0].'\'';
				Base_Errors::Add('<b>'.$key.'</b>\'s parameter type must be '.$type.'',debug_backtrace());
				$return = false;
			}
			$i++;
		}
		return($return);
	}
	public static function validate($types=null,$minimum=null,$maximum=null,$die_check=true){
		if(Base_Configuration::getconf_debug_environment()==false)
			return(true);
		$c = Base_Errors::count();
		//solo para debug en el enviroment o cuando algo anda mal en entorno real
		$data = (array_pop(array_slice($x = debug_backtrace(), 0, 2)));
		$arguments = $data['args'];
		$return = true;
		$backwards = 2;
		if(($types==null&&$minimum==null&&$maximum==null)){
			Base_Errors::triggerError('Wrong number of parameters or null contents');
		}
		elseif($maximum!==null&&$minimum!==null&&$maximum<$minimum){
			Base_Errors::triggerError("Parameter maximun cant be lower than minimum");
		}
		do{
			if($types!=null){
				if(self::checkTypes($arguments,$types,$minimum)==false){
					$return = false;
					//break;
				}
			}
			if($minimum!==null){
				if(count($arguments)<$minimum){
					Base_Errors::Add('Must be at least '.$minimum.' parameters',debug_backtrace());
					$return = false;
					//break;
				}
			}
			if($maximum!==null){
				if(count($arguments)>$maximum){
					Base_Errors::Add('Cant be more than '.$maximum.' parameters',debug_backtrace());
					$return = false;
					//break;
				}
			}
		}while(0);
		if(!$return&&$die_check){
			$errores = Base_Errors::getErrors($c);
			foreach($errores as $error){
				Base_Errors::triggerError($error->getMensaje(),2,E_USER_WARNING);
			}
			$data = array_shift(array_slice(debug_backtrace(),1));
			$method = ($class = isset($data['class'])?$data['class'].'::':'').$data['function'];
			Base_Errors::triggerError('Cant continue excuting '.$method.' called',2);
		}
		return($arguments);
	}
}