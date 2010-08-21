<?
class Core_File_Info{
	public static function getMimeType($file){
		$tipos = array(
			'js'=>'text/javascript',
			'css'=>'text/css',
		);
		$extensiones = array_keys($tipos);
		$extension = array_pop($x = explode('.', $file));
		if(in_array($extension, $extensiones)){
			return($tipos[$extension]);
		}
		return(null);
	}
}
?>