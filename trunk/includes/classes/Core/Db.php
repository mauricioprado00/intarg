<?php
define('SQL_AND', 'AND');
define('SQL_OR', 'OR');

define('SQL_IGUAL', 'eq');
define('SQL_DISTINTO', 'ne');
define('SQL_MENOR', 'lt');
define('SQL_MENOR_IGUAL', 'le');
define('SQL_MAYOR', 'gt');
define('SQL_MAYOR_IGUAL', 'ge');
define('SQL_TERMINA', 'ew');
define('SQL_EMPIEZA', 'bw');
define('SQL_CONTIENE', 'cn');

define('SQL_IS_NULL', ' IS_NULL');
define('SQL_IS_NOT_NULL', ' IS_NOT_NULL');

class DB{

    private $conn;
    private $host;
    private $usuario;
    private $pass;
    private $bd;

    private $aSql = array();
    private $tCount = 0;
    private $auto_slashes = true;

    function DB($conectar = true)
    {
        $this->host = DB_HOST;
        $this->usuario = DB_USER;
        $this->pass = DB_PASS;
        $this->bd = DB_SCHEMA;

        if($conectar)
        {
            $this->conn = $this->Conectar();
        }
    }


    function Conectar($die = true)
    {
        if($conex = @mysql_connect($this->host, $this->usuario, $this->pass))
        {
            if(!mysql_select_db($this->bd, $conex))
               $eMsg = "La Base de Datos seleccionada es incorrecta.";
        }
        else
        {
            $eMsg = "No se pudo establecer una conexion al servidor de Bases de Datos.";
        }
        
        if(isset($eMsg))
        {
            $e = new Base_Error();
            $e->add(mysql_errno(), $eMsg);
			if($die)
            	die($e->outputErrores());
        }
        return $conex;
    }
	
	
	
	function SelectDB()
	{
		if(!mysql_select_db($this->bd))
			return;
			
		return true;
	}
	

    /**
     *
    Realiza una consulta a la base de datos segun un sql mandado por
    parametro
    Devuelve el recorset
    Parametros:
            >$sql: Sentencia sql con la cual voy a realizar la consulta
    ***************************************************************/
    function Query($sql, $mostrarError = false)
    {
        
        if(!isset($_SESSION['sql']))
        {
            $_SESSION['sql'] = "";
        }
        
        $_SESSION['sql'] .= time()/*date("d/m/Y H:i:s u")*/ . " => " . $sql . "@nho@";// . date("d/m/Y H:i:s") . "@@nho@@";
        
        $r = mysql_query($sql, $this->conn);
        if($r)
        {
            return $r;
        }
        else
        {

            if($mostrarError || DB_DEBUG)
            {
                $e = new Base_Error();
                $e->add(mysql_errno(), mysql_error(), "SQL: " . $sql);
                echo $e->outputErrores();
                //echo mysql_error();
            }

            $f = fopen($_SERVER['DOCUMENT_ROOT'] . CONF_DIR_APP . "sql.log.htm", "a");
            fwrite($f, '<pre><b>' . $this->errorLogCode() . '</b><br/>' .  mysql_error() . "<br/><br/>" . $sql . "</pre><hr>");
            fclose($f);
            return false;

        }
    }


    /**
     *
     * @param <type> $aData
     * @param <type> $dbt
     * @param <type> $esTransaccion
     * @return <type>
     */
    function insertArray($aData, $dbt, $esTransaccion = false)
    {
        $e = new Base_Error();
        if (!is_array($aData))
            $e->add(1, 'Array de datos inv&aacute;lidos');

        if(empty($aData))
            $e->add(2, 'Falta nombre de tabla');


        if($e->existeError())
            return $e;

        $cols = '(';
        $values = '(';

        foreach ($aData as $key=>$value)
        {
            $cols .= $key . ",";
            $values .= $value . ",";

            /*
            $col_type = $this->get_column_type($table, $key);  // get column type
            if (!$col_type) return false;  // error!

            // determine if we need to encase the value in single quotes
            if (is_null($value)) {
                $values .= "NULL,";
            }
            elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
                $values .= "$value,";
            }
            elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
                $value = $this->sql_date_format($value, $col_type); // format date
                $values .= "'$value',";
            }
            elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
                if ($this->auto_slashes) $value = $this->fixString($value);
                $values .= "'$value',";
            }
            *
            */
        }
        $cols = rtrim($cols, ',').')';
        $values = rtrim($values, ',').')';

        // insert values
        $sql = "INSERT INTO $dbt $cols VALUES $values";

        if($esTransaccion)
        {
            $this->addSql($sql);
            return true;
        }

        return $this->Query($sql);

    }

    /**
     *
     * @param <type> $aData
     * @param <type> $dbt
     * @param <type> $condition
     * @param <type> $esTransaccion
     * @return <type>
     */
    function updateArray($aData, $dbt, $condition, $esTransaccion = false)
    {

        $e = new Base_Error();
        if (!is_array($aData))
            $e->add(1, 'Array de datos inv&aacute;lidos');

        if(empty($aData))
            $e->add(2, 'Falta nombre de tabla');


        if($e->existeError())
            return $e;

        $sql = "UPDATE " . $dbt . " SET ";
        foreach ($aData as $key=>$value)
        {
            $sql .= $key . " = " . $value . ",";

            /*
            $col_type = $this->get_column_type($table, $key);  // get column type
            if (!$col_type) return false;  // error!

            // determine if we need to encase the value in single quotes
            if (is_null($value)) {
                $sql .= "NULL,";
            }
            elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
                $sql .= "$value,";
            }
            elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
                $value = $this->sql_date_format($value, $col_type); // format date
                $sql .= "'$value',";
            }
            elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
                if ($this->auto_slashes) $value = $this->fixString($value);
                $sql .= "'$value',";
            }
            *
            */

        }
        $sql = rtrim($sql, ','); // strip off last "extra" comma
        if (!empty($condition) && is_array($condition))
           $sql .= " WHERE " . $condition[0] . " = " . $condition[1];

        if($esTransaccion)
        {
            $this->addSql($sql);
            return true;
        }

        // insert values
        return $this->Query($sql);
    }



        /**
         * ******************************************************
        Me devuelve un registro del recorset
        Parametros:
        >$r: es el recorset
        *********************************************************/
    function FetchArray($r)
    {
        $row = mysql_fetch_array($r);
        return $row;
    }


    function AffectedRows()
    {
        $int = mysql_affected_rows();
        return $int;
    }


    function InsertId()
    {
        $id = mysql_insert_id();
        return $id;
    }


    function FetchObject($r)
    {
        $row = mysql_fetch_object($r);
        return $row;
    }


        /*********************************************************
        Devuelve la cantidad de resultados de la consulta
        Parametros:
        >$r: recorset
        *********************************************************/

    function NumRows($r)
    {
        $cant = mysql_num_rows($r);
        return $cant;
    }


        /*******************************************************
        Metodo q cierra la conexion a la base de datos.
        Hay que invocarla cuando ya no se presisa la conexion
        *******************************************************/
    function Desconectar()
    {
        mysql_close();
    }



    /*
    * Transacciones
    */
    function addSql($sql)
    {
        $this->tCount++;
        $this->aSql[$this->tCount] = $sql;
    }

    function countSqlTransactions()
    {
        return $this->tCount;
    }

    function mySqlTransaction()
    {
        if($this->Query("START TRANSACTION"))
        {
            //echo "<pre>"; print_r($this->aSql); echo "<pre>";
            foreach($this->aSql as $sql)
            {
                $res = $this->Query($sql);
                if(!$res)
                    break;
            }

            if ($res)
            {
                $res = $this->Query("COMMIT");
                return true;
            }
            else
            {
                $res = $this->Query("ROLLBACK");
                return;
            }
        }
        else
        {
            return;
        }
    }

    function error()
    {
        if(DB_DEBUG)
        {
        return mysql_error();
        }
        else
        {
            
            $msg = "";
            switch(mysql_errno())
            {
                case 1451:
                  $msg = "Existen registros relacionados.";
                  break;

            }

            return array(mysql_errno()=> $msg);

        }
    }

    function errorNumber()
    {
        return mysql_errno();
    }

    function errorLogCode()
    {
        return date('dmYHis') . $this->errorNumber();
    }

    function stats()
    {
        $status = explode('  ', mysql_stat());
        return $status;
    }

    function showProcesses()
    {
        $result = mysql_list_processes();
        echo "<ul><li>Procesos<ul>";
        while ($row = mysql_fetch_assoc($result)){
            printf("<li>ID: %s <br />HOST: %s <br />DB: %s <br />COMMAND: %s <br />TIME: %s<br /><br /></li>", $row["Id"], $row["Host"], $row["db"], $row["Command"], $row["Time"]);
        }
        echo "</ul></li></ul>";
        mysql_free_result($result);
    }
    
    
    
    
    public function fixString($string, $trim = false)
    {
        if(!Helpers::esUTF8($string))
        {
            if($trim)
            {
                return "'" . mysql_real_escape_string(trim(htmlentities($string, ENT_COMPAT, 'UTF-8'))) . "'";
            }
            else
            {
                return "'" . mysql_real_escape_string(htmlentities($string, ENT_COMPAT, 'UTF-8')) . "'";
            }
        }
        else
        {
            return "'" . (utf8_decode($string)) . "'";
        }
    }


    /**
     * Para insertar en MySQL
     *
     * @param String $date Fecha a parsear, debe estar en formato d/m/Y
     * @param String $separador Delimitador
     * @return String Fecha en formato Y-m-d
     */
    static public function parseDate($date, $separador = "/") {
        if(empty($date))
            return 'NULL';
            
        $fecha = explode($separador, $date);
        return "'" . date('Y-m-d', strtotime($fecha[2] . "-" . $fecha[1] . "-" . $fecha[0])) . "'";
    }



    public function getSqlTransaction()
    {
        return $this->aSql;        
    }


    static public function outputSqlSentences()
    {
        error_reporting(0);
        $e = new Base_Error('SQL\'s');
        //$_SESSION['sql'] = substr($_SESSION['sql'], 0, strlen($_SESSION['sql']) - 7);
        //echo $_SESSION['sql'];
        $aSql = explode("@nho@", $_SESSION['sql']);
        for($i = 0; $i < count($aSql) - 1; $i++)
        {
            //$_sql = explode("@nho@", $sql);
            $e->add($i, $aSql[$i]);
        }
        $db = new DB();
        $stats = $db->stats();
        $strStats = "";
        foreach($stats as $v)
        {
            $strStats .= "<br>" . $v;
        }
        $e->add('MySQL Stats', $strStats);
        
        unset($_SESSION['sql']);
        
        $scr = '<script type="text/javascript">';
        $scr .= '$(document).ready(function(){';
        $scr .= '   $("#debugSQL").slideUp().empty().append("' . /*$db->fixString*/($e->outputErrores()) . '").slideDown();';
        $src .= 'alert("ok");';
        $scr .= '});';
        $scr .= '</script>';
        echo $scr;
        error_reporting(E_ALL);
    }


    public function get_column_type($table, $column)
    {

        // Gets information about a particular column using the mysql_fetch_field
        // function.  Returns an array with the field info or false if there is
        // an error.

        $r = mysql_query("SELECT $column FROM $table");
        if (!$r) {
            //$this->last_error = mysql_error();
            return false;
        }
        $ret = mysql_field_type($r, 0);
        if (!$ret) {
            //$this->last_error = "Unable to get column information on $table.$column.";
            mysql_free_result($r);
            return false;
        }
        mysql_free_result($r);
        return $ret;

    }




    public function getCB($operador, $fld, $comparacion, $val)
    {
        $wh = " " . $operador . " " . $fld;
        switch ($comparacion)
        {
            
            case 'eq'://SQL_IGUAL:
                if(is_numeric($val)) {
                    $wh .= " = ".$val;
                } else {
                    $wh .= " = '".$val."'";
                }
                break;
            case 'ne'://SQL_DISTINTO:
                if(is_numeric($val)) {
                    $wh .= " <> ".$val;
                } else {
                    $wh .= " <> '".$val."'";
                }
                break;
            case SQL_MENOR:
                if(is_numeric($val)) {
                    $wh .= " < ".$val;
                } else {
                    $wh .= " < '".$val."'";
                }
                break;
            case SQL_MENOR_IGUAL:
                if(is_numeric($val)) {
                    $wh .= " <= ".$val;
                } else {
                    $wh .= " <= '".$val."'";
                }
                break;
            case SQL_MAYOR:
                if(is_numeric($val)) {
                    $wh .= " > ".$val;
                } else {
                    $wh .= " > '".$val."'";
                }
                break;
            case SQL_MAYOR_IGUAL:
                if(is_numeric($val)) {
                    $wh .= " >= ".$val;
                } else {
                    $wh .= " >= '".$val."'";
                }
                break;

            case SQL_TERMINA:
                $wh .= " LIKE '%".$val."'";
                break;

            case SQL_EMPIEZA:
                $wh .= " LIKE '".$val."%'";
                break;
            
            case SQL_CONTIENE:
                $wh .= " LIKE '%".$val."%'";
                break;
            case SQL_IS_NULL:
                $wh .= " IS NULL";
                break;
            case SQL_IS_NOT_NULL:
                $wh .= " IS NOT NULL";
                break;
            default:
                $wh = "";
        }

        return $wh;

    }
};
?>