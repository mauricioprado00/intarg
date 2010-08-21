<?
abstract class Mysql_Db extends Db_Abstract{
	private static $__connections = array();
	private $_modo_singleton = false;
	private $_con_ref_count = 0;	
	protected function modoSingleton($modo_singleton){//solo una coneccion
		$this->_modo_singleton = $modo_singleton;
		return $this;
	}
	private function esModoSingleton(){
		return $this->_modo_singleton;
	}
	public function insertId(){
		return(mysql_insert_id());
	}
	private function incrementarContadorRefenciasDeConexion(){
		return ++ $this->_con_ref_count;
	}
	private function decrementarContadorRefenciasDeConexion(){
		return -- $this->_con_ref_count;
	}
	private function _open(){
		$con = $this->connect($this->getHost(), $this->getUser(), $this->getPassword(), $this->getModel());
		return $this->setConnectionHandler($con);
	}
	public function open(){
		if($this->esModoSingleton()){
			if(!$this->hasConnectionHandler()){
				$this->_open();
			}
			$con = $this->incrementarContadorRefenciasDeConexion();
			return $this->getConnectionHandler();
		}
		//modo normal
		return($this->_open());
	}
	private function _close(){
		$this->terminate($this->getConnectionHandler());
		$this->setConnectionHandler(null);
	}
	public function close(){
		if($this->esModoSingleton()){
			if($this->decrementarContadorRefenciasDeConexion()>0)
				return;
			return $this->_close();
		}
		//modo normal
		return($this->_close());
	}
	private $_errors = array();
	public function getLastErrors($cant=null){
		if(!isset($cant))
			return $this->_errors;
		return array_slice($this->_errors, 0, $cant);
	}
	private function addError($description, $code){
		$error = new Core_Object();
		$error->setDescription($description)->setCode($code);
		array_unshift($this->_errors, $error);
	}
	public function exec($sql){
		if(is_array($sql)){
			if(!isset($sql['debug']))
				return false;
			echo Core_Helper::DebugVars($sql = $sql['debug']);
		}
		$r = mysql_query($sql, $this->getConnectionHandler());
		if(!$r){
			$this->addError(mysql_error(), mysql_errno());
		}
		//var_dump($sql, $r)	;
		return($r);
	}
	public function fetchAssociative($res){
		$r = mysql_fetch_assoc($res);
		return($r);
	}
	
	private static function terminate($con){
		$icon = (int)$con;
		if(!isset(self::$__connections[$icon]))
			return(null);
		self::$__connections[$icon]--;
		if(self::$__connections[$icon]===0){
//			echo "cerrando de verdad $con(".(self::$__connections[$icon]).")\n";
			mysql_close($con);
		}
//		else echo "no cierro todavia $con(".(self::$__connections[$icon]).")\n";
	}
	private static function connect($host, $user, $password, $db){
		$con = mysql_connect($host, $user, $password);
		$icon = (int)$con;
		if(!isset(self::$__connections[$icon]))
			self::$__connections[$icon] = 1;
		else self::$__connections[$icon]++;
//		echo "abierto $con(".(self::$__connections[$icon]).")\n";
		mysql_select_db($db);
		mysql_set_charset('utf8', $con);
		return($con);
	}
	function  getCompare(Db_Compare_Abstract $compare){
		switch(true){
			case is_a($compare, 'Db_Compare_Equal'):
				return(new Mysql_Db_Compare_Equal($compare));
			case is_a($compare, 'Db_Compare_Between'):
				return(new Mysql_Db_Compare_Between($compare));
			case is_a($compare, 'Db_Compare_Like'):
				return(new Mysql_Db_Compare_Like($compare));
			case is_a($compare, 'Db_Compare_Null'):
				return(new Mysql_Db_Compare_Null($compare));
			case is_a($compare, 'Db_Compare_In'):
				return(new Mysql_Db_Compare_In($compare));
			case is_a($compare, 'Db_Compare_Custom'):
				return(new Mysql_Db_Compare_Custom($compare));
			//... so on
			default: return(new Db_Compare_Unsupported());
		}
	}
	public function nameToString($name){
		return(Mysql_Helper::getInstance()->nameToString($name));
	}
	public function valueToString($value){
		return(Mysql_Helper::getInstance()->valueToString($value));
	}
	public function formatDate($format, $value){
		$translations = array(
			'D'=>array (
			  'Sun' => 'Dom',
			  'Mon' => 'Lun',
			  'Tue' => 'Mar',
			  'Wed' => 'Mie',
			  'Thu' => 'Jue',
			  'Fri' => 'Vie',
			  'Sat' => 'Sab',
			),
			'l'=>array (
			  'Sunday' => 'Domingo',
			  'Monday' => 'Lunes',
			  'Tuesday' => 'Martes',
			  'Wednesday' => 'Miercoles',
			  'Thursday' => 'Jueves',
			  'Friday' => 'Viernes',
			  'Saturday' => 'Sabado',
			),
			'F'=>array (
			  'January' => 'Enero',
			  'February' => 'Febrero',
			  'March' => 'Marzo',
			  'April' => 'Abril',
			  'May' => 'Mayo',
			  'June' => 'Junio',
			  'July' => 'Julio',
			  'August' => 'Agosto',
			  'September' => 'Septiembre',
			  'October' => 'Octubre',
			  'November' => 'Noviembre',
			  'December' => 'Diciembre',
			),
			'M'=>array (
			  'Jan' => 'Ene',
			  'Feb' => 'Feb',
			  'Mar' => 'Mar',
			  'Apr' => 'Abr',
			  'May' => 'May',
			  'Jun' => 'Jun',
			  'Jul' => 'Jul',
			  'Aug' => 'Ago',
			  'Sep' => 'Sep',
			  'Oct' => 'Oct',
			  'Nov' => 'Nov',
			  'Dec' => 'Dic',
			),
		);
		$format = preg_replace('({([a-zA-Z])})','{\\\\$1:$1}', $format);
		//var_dump($format);
		$formated = date($format, strtotime($value));
		preg_match_all('({([a-zA-z]):([^}]*)})', $formated, $matches);
		if(count($matches)>2&&count($matches[2])){
			$arr_format_chars = array_keys($translations);
			foreach($matches[1] as $idx=>$format_char){
				$replace = $value = $matches[2][$idx];
				//var_dump($format_char);
				if(in_array($format_char, $arr_format_chars)&&isset($translations[$format_char][$value])){
					$replace = $translations[$format_char][$value];
				}
				$formated = str_replace('{'.$format_char.':'.$value.'}', $replace, $formated);
			}
		}
		//var_dump($matches);
		return($formated);
	}
	private $_trans_commit = true;
	public function transactionStart(){
		$this->open();
		$this->exec('START TRANSACTION');
		$this->_trans_commit = true;
		$this->exec('SET autocommit = 0');
		return $this;
	}
//	public function transactionCommit(){
//		//return $this->exec('COMMIT');
//	}
	public function transactionIsActive(){
		return $this->_trans_commit == true;
	}
	public function transactionRollback(){
		//return $this->exec('ROLLBACK');
		$this->_trans_commit = false;
		return $this;
	}
	public function transactionEnd(){
		if($this->_trans_commit){
			$this->exec('COMMIT');
		}
		else{
			$this->exec('ROLLBACK');
		}
		$this->close();
		return $this;
	}
	protected function _lockTable($table, $type='READ', $alias = null){
		$lock = 'LOCK TABLES '.$this->nameToString($table).(isset($alias)?' as '.$this->nameToString($alias):'').' '.$type;
//		echo "\n".$lock."\n";
		return $this->exec($lock);
	}
	protected function _unlockTables(){
		return $this->exec('UNLOCK TABLES');
	}
	
}
?>