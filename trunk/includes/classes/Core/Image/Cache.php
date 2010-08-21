<?
class Core_Image_Cache extends Core_Singleton{
	public static function create($filename, $max_width=null, $max_height=null){
		$image_cache = new self($filename, $max_width, $max_height);
		return($image_cache);
	}
	public function __construct($filename, $max_width=null, $max_height=null){
		parent::_construct();
		$this->setFilename($filename);
		$this->setMaxWidth($max_width);
		$this->setMaxHeight($max_height);
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	private function generateUniqueFileName($fields=null, $type=null){
		$seed = array();
		foreach($this->getData() as $key=>$value){
			if($fields==null or in_array($key, $fields))
				$seed[] = $key.'='.$value;
		}
		switch(strtolower($type)){
			case 'jpg':
			case 'jpeg':{
				$ext = 'jpg';
				break;
			}
			case 'bmp':
			case 'bitmap':{
				$ext = 'bmp';
				break;
			}
			case 'gif':
			case 'compuserve':{
				$ext = 'gif';
				break;
			}
			case 'png':{
				$ext = 'png';
				break;
			}
			default:{
				$ext = array_pop(explode('.', basename($this->getFilename())));
				break;
			}
		}
		
		$seed = implode('&', $seed);
//		Core_Http_Header::ContentType('text/plain');
//		var_dump($seed,md5($seed).'.'.$ext);
//		die();
		return(md5($seed).'.'.$ext);
	}
	public function getLinkUrl($type=null){
		$filename = $this->generateUniqueFileName(array('filename','max_width','max_height'), $type);
		$filepath = CFG_PATH_ROOT.'/cache/image/'.$filename;
		if(!file_exists($filepath)){
			$this->createCacheFile($filepath);
		}
		$filelinkurl = 'cache/image/'.$filename;
		return($filelinkurl);
	}
	public static function IsSupportedFormat($filename){
		$ext = array_pop(explode('.', basename($filename)));
		if(!$ext)
			return(false);
		return(in_array($ext, self::getSupportedFormatsExtensions()));
	}
	public static function getSupportedFormatsExtensions(){
		return(array('gif','bmp','jpg','png'));
	}
	private function createCacheFile($save_filepath){
		if($this->hasMaxWidth() and $this->getMaxWidth() != ""){
			$t = $this->getMaxWidth();
		}
		else{
			$t = null;
		}
		
		if($this->hasMaxHeight() and $this->getMaxHeight() != ""){
			$a = $this->getMaxHeight();
		}
		else{
				$a = null;
		}
		
		$x = self::Thumb($this->getFilename(), $t, $a);
		if(!$x)
			return(null);
		//header("Content-Type: image/jpeg");
		$ext = array_pop(explode('.', basename($save_filepath)));
		if(!file_exists(dirname($save_filepath)))
			Base_FM::mkdir(dirname($save_filepath), 0777, true);
		ob_start();
		switch($ext){
			case 'gif':{
				//imagegif($x, $save_filepath);
				imagegif($x);
				break;
			}
			case 'png':{
				//imagepng($x, $save_filepath);
				imagepng($x);
				break;
			}
			case 'jpg':
			default:{
				//imagejpeg($x, $save_filepath);
				imagejpeg($x);
				break;
			}
		}
		$data = ob_get_contents();
		ob_end_clean();
		Base_FM::file_put_contents($save_filepath, $data);
		//chmod($save_filepath, 0777);
		imagedestroy($x);
		return(file_exists($save_filepath)?true:false);
	}
	private function Thumb($imagen, $max_ancho, $max_alto){
		if(!file_exists($imagen))
			return(null);
		list($orig_ancho, $orig_alto) = @getimagesize($imagen);
		$ancho = $orig_ancho;
		$alto = $orig_alto;
		
		if($max_alto && $alto > $max_alto){
			$ancho = ($max_alto / $alto) * $ancho;
			$alto = $max_alto;
		}
		
		if($max_ancho && $ancho > $max_ancho){
			$alto = ($max_ancho / $ancho) * $alto;
			$ancho = $max_ancho;
		}
		
		$image_p = imagecreatetruecolor($ancho, $alto);
		$fileext = strtolower(array_pop(explode('.', basename($imagen))));
		switch($fileext){
			case 'jpg':
			case 'jpeg':{
				$image = imagecreatefromjpeg($imagen);
				break;
			}
			case 'gif':{
				$image = imagecreatefromgif($imagen);
				break;
			}
			case 'png':{
				$image = imagecreatefrompng($imagen);
				break;
			}
			case 'bmp':{
				$image = self::imagecreatefrombmp($imagen);
				break;
			}
			case 'wbmp':{
				$image = imagecreatefromwbmp($imagen);
				break;
			}
			default:{
				return(null);
				break;
			}
		}
//		$color = imagecolorallocate($image_p, 255, 255, 255);
//		imagecolortransparent($image_p, $color);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $ancho, $alto, $orig_ancho, $orig_alto);
		return $image_p;
	}
	private static function imagecreatefrombmp($p_sFile) 
    { 
        //    Load the image into a string 
        $file    =    fopen($p_sFile,"rb"); 
        $read    =    fread($file,10); 
        while(!feof($file)&&($read<>"")) 
            $read    .=    fread($file,1024); 
        
        $temp    =    unpack("H*",$read); 
        $hex    =    $temp[1]; 
        $header    =    substr($hex,0,108); 
        
        //    Process the header 
        //    Structure: http://www.fastgraph.com/help/bmp_header_format.html 
        if (substr($header,0,4)=="424d") 
        { 
            //    Cut it in parts of 2 bytes 
            $header_parts    =    str_split($header,2); 
            
            //    Get the width        4 bytes 
            $width            =    hexdec($header_parts[19].$header_parts[18]); 
            
            //    Get the height        4 bytes 
            $height            =    hexdec($header_parts[23].$header_parts[22]); 
            
            //    Unset the header params 
            unset($header_parts); 
        } 
        
        //    Define starting X and Y 
        $x                =    0; 
        $y                =    1; 
        
        //    Create newimage 
        $image            =    imagecreatetruecolor($width,$height); 
        
        //    Grab the body from the image 
        $body            =    substr($hex,108); 

        //    Calculate if padding at the end-line is needed 
        //    Divided by two to keep overview. 
        //    1 byte = 2 HEX-chars 
        $body_size        =    (strlen($body)/2); 
        $header_size    =    ($width*$height); 

        //    Use end-line padding? Only when needed 
        $usePadding        =    ($body_size>($header_size*3)+4); 
        
        //    Using a for-loop with index-calculation instaid of str_split to avoid large memory consumption 
        //    Calculate the next DWORD-position in the body 
        for ($i=0;$i<$body_size;$i+=3) 
        { 
            //    Calculate line-ending and padding 
            if ($x>=$width) 
            { 
                //    If padding needed, ignore image-padding 
                //    Shift i to the ending of the current 32-bit-block 
                if ($usePadding) 
                    $i    +=    $width%4; 
                
                //    Reset horizontal position 
                $x    =    0; 
                
                //    Raise the height-position (bottom-up) 
                $y++; 
                
                //    Reached the image-height? Break the for-loop 
                if ($y>$height) 
                    break; 
            } 
            
            //    Calculation of the RGB-pixel (defined as BGR in image-data) 
            //    Define $i_pos as absolute position in the body 
            $i_pos    =    $i*2; 
            $r        =    hexdec($body[$i_pos+4].$body[$i_pos+5]); 
            $g        =    hexdec($body[$i_pos+2].$body[$i_pos+3]); 
            $b        =    hexdec($body[$i_pos].$body[$i_pos+1]); 
            
            //    Calculate and draw the pixel 
            $color    =    imagecolorallocate($image,$r,$g,$b); 
            imagesetpixel($image,$x,$height-$y,$color); 
            
            //    Raise the horizontal position 
            $x++; 
        } 
        
        //    Unset the body / free the memory 
        unset($body); 
        
        //    Return image-object 
        return $image; 
    } 

}
?>