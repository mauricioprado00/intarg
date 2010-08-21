<?
/*datos ftp (para el safe_mode)*/
class Base_FM{
	public static $CONF_FM_MODE; 
	const CONF_FM_WRAPPER_PASSWORD = 'THISISAVeryLongPasswordTo1234AvoidProblems';
	const CONF_FM_WRAPPER_URL = 'http://localhost/bigmotorcicle/?Base_FM=wrapper&';
	public static function autoSetConfFmMode(){
		self::$CONF_FM_MODE = ini_get('safe_mode')?'wrapper':'normal';
	}
	public static function isWrapper(){
		return self::$CONF_FM_MODE=='wrapper';
	}
	public static function isNormal(){
		return self::$CONF_FM_MODE=='normal';
	}
	public static function mkdir($path, $mode=null, $options=null){
		if(self::isWrapper())
			return self::wrapper_mkdir($path, $mode, $options);
		elseif(self::isNormal())
			return self::normal_mkdir($path, $mode, $options);
		return null;
//		elseif(self::isFtp())
//			return self::ftp_mkdir($path, $mode, $options);
	}
	public static function file_put_contents($filename, $data, $flags=null, $mode=0777){
		if(self::isWrapper())
			return self::wrapper_file_put_contents($filename, $data, $flags, $mode);
		elseif(self::isNormal())
			return self::normal_file_put_contents($filename, $data, $flags, $mode);
		return null;
//		elseif(self::isFtp())
//			return self::ftp_file_put_contents($path, $mode, $options);
	}
	public static function normal_mkdir($path, $mode=null, $options=null){
		if($ret = mkdir($path, $mode, $options))
			chmod($path, $mode);
		return $ret;
	}
	public static function wrapper_mkdir($path, $mode=null, $options=null){
		$info = array('path'=>$path, 'mode'=>$mode, 'options'=>$options);
		$info['method'] = 'wrapper_mkdir';
		$info = serialize($info);
		$info = array(
			'info'=>$info,
			'firma'=>sha1($info.self::CONF_FM_WRAPPER_PASSWORD),
		);
		$url = self::CONF_FM_WRAPPER_URL.'info='.urlencode($info['info']).'&firma='.urlencode($info['firma']);
		$ret = file_get_contents($url);
		$ret = $ret?unserialize($ret):false;
		if($ret)
			return true;
		return false;
		//return mkdir($path, $mode, $options);
	}
	public static function normal_file_put_contents($filename, $data, $flags=null, $mode=0777){
		if($ret = file_put_contents($filename, $data, $flags))
			chmod($filename, $mode);
		return $ret;
	}
	public static function wrapper_file_put_contents($filename, $data, $flags=null, $mode=0777){
		$info = array('filename'=>$filename, 'data'=>$data, 'flags'=>$flags, 'mode'=>$mode);
		$info['method'] = 'file_put_contents';
		$info = serialize($info);
		$info = array(
			'info'=>$info,
			'firma'=>sha1($info.self::CONF_FM_WRAPPER_PASSWORD)
		);
//		$params = array(
//			'http' => array
//			(
//				'method' => 'POST',
//				'header'=>"Content-Type: multipart/form-data\r\n",
//				//'content' => http_build_query(array('info'=>$info))
//				'content' => http_build_query(array('info'=>'sdf'))
//			)
//		);
//		//var_dump(http_build_query(array('info'=>$info)));
		$url = self::CONF_FM_WRAPPER_URL.'&firma='.urlencode($info['firma']);
//		$ctx = stream_context_create($params);
		//$ret = file_get_contents($url, false, $ctx);
		$ret = self::do_post_request($url, array('info'=>$info['info']));
		$ret = $ret?unserialize($ret):false;
		if($ret)
			return true;
		return false;
	}
	
	public static function init(){
		Base_FM::autoSetConfFmMode();
		if(self::isWrapper()){
			self::wrapper_init();
		}
	}
	public static function wrapper_init(){
		if(isset($_GET['Base_FM'])&&$_GET['Base_FM']=='wrapper'){
			if(isset($_GET['info']))
				$info = $_GET['info'];
			else $info = $_POST['info'];
			if(ini_get('magic_quotes_gpc')){
				$info = stripslashes($info);
			}
			$firma = sha1($info.self::CONF_FM_WRAPPER_PASSWORD);
			if($firma!=$_GET['firma']){
				die(serialize(false));
			}
			$info = unserialize($info);
			extract($info);
			$ret = null;
			switch($method){
				case 'wrapper_mkdir':{
					$ret = self::normal_mkdir($path, $mode, $options);
					break;
				}
				case 'file_put_contents':{
					$ret = self::normal_file_put_contents($filename, $data, $flags, $mode);
					break;
				}
			}
			die(serialize($ret));
		}
	}
	
	
	public static function do_post_request($url, $postdata, $files = array())
{
    $data = "";
    $boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10);
      
    //Collect Postdata
    foreach($postdata as $key => $val)
    {
        $data .= "--$boundary\n";
        $data .= "Content-Disposition: form-data; name=\"".$key."\"\n\n".$val."\n";
    }
    
    $data .= "--$boundary\n";
   
    //Collect Filedata
    foreach($files as $key => $file)
    {
        $fileContents = file_get_contents($file['tmp_name']);
       
        $data .= "Content-Disposition: form-data; name=\"{$key}\"; filename=\"{$file['name']}\"\n";
        $data .= "Content-Type: image/jpeg\n";
        $data .= "Content-Transfer-Encoding: binary\n\n";
        $data .= $fileContents."\n";
        $data .= "--$boundary--\n";
    }
 
    $params = array('http' => array(
           'method' => 'POST',
           'header' => 'Content-Type: multipart/form-data; boundary='.$boundary,
           'content' => $data
        ));

   $ctx = stream_context_create($params);
   $fp = fopen($url, 'rb', false, $ctx);
  
   if (!$fp) {
      throw new Exception("Problem with $url, $php_errormsg");
   }
 
   $response = @stream_get_contents($fp);
   if ($response === false) {
      throw new Exception("Problem reading data from $url, $php_errormsg");
   }
   return $response;
} 
	
//	public static function ftp_mkdir($path, $mode=null, $options=null){
//		$conn_id = ftp_connect(CONF_FTP_HOST);
//		if(@ftp_login($conn_id, CONF_FTP_USER, CONF_FTP_PASSWORD)){
//			if (@ftp_mkdir($conn_id, $path)){
//				ftp_chmod($conn_id, 0777, $path);//permisos de lectura/escritura/ejecución
//				ftp_close($conn_id);
//				return true;
//			}
//			else{
//				ftp_close($conn_id);
//				return false;
//			}
//		}
//		else{
//			ftp_close($conn_id);
//			return false;
//		}
//	}
}
Base_FM::init();
?>