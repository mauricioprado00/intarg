<?
include_once(dirname(__FILE__).'/external_library/Secureimage/securimage.php');
class Core_Capcha_SecurImage extends Securimage{
	public function show($background_image = ''){
		$dir = getcwd();
		chdir(dirname(__FILE__).'/external_library/Secureimage');
		$ret = parent::show($background_image);
		chdir($dir);
		return $ret;
	}
}
?>