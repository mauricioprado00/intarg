<?
class CPrueba extends Granguia_DB_Model_Abstract{
	protected function init(){
		parent::init();
		$this->setId(null)
			->setActivo(null)
			->setUsername(null)
			->setPassword(null)
			->setNombre(null)
			->setApellido(null)
			->setPrivilegios(null)
			->setUltimoAcceso(null);
	}
	public function getDbTablename(){
		return("bm_administrador");
	}
	public function Listar(){
		var_dump($str_where = $this->getWhere()->toString($this->getData()));
	}
}

$x = new Admin_User_Model_User();
$x->setId(null)
			->setActivo(null)
			->setUsername(null)
			->setPassword(null)
			->setNombre(null)
			->setApellido(null)
			->setPrivilegios(null)
			->setUltimoAcceso(null);
$x->setUsername("mauricioprado");
$x->setNombre("mau");
//$x->setWhere('(', DB_Helper::equal('username'),'AND', DB_Helper::Between('valido','1','2'),')');
$x->setWhere('(', DB_Helper::equal('username'),'OR', DB_Helper::equal('username','gomez'), 'OR' , DB_Helper::like('nombre','%',null,'%'), ')');

$x->search();

$x->setUsername("pereslopez");
$x->replace(array (
      'id' => '1',
      'activo' => '1',
      'username' => 'mauricioprado',
      'password' => 'e9fa57f40877bdc4630b2cf19130a4f7',
      'nombre' => 'mauricio',
      'apellido' => 'prado macat',
      'privilegios' => '777',
      'ultimo_acceso' => NULL,
    ));
$x->replace(array (
//      'id' => '1',
      'activo' => '1',
      'username' => 'mauricioprado',
      'password' => 'e9fa57f40877bdc4630b2cf19130a4f7',
      'nombre' => 'mauricio',
      'apellido' => 'prado macat',
      'privilegios' => '777',
      'ultimo_acceso' => NULL,
    ));
var_dump(
	$x->exists(array('username'=>'mauricioprado')),
	$x->exists(array('username'=>'matortiz')),
	$x->exists(array('username'=>'alguienmas'))
);
$r = $x->search();
var_export($r);

die();
?>