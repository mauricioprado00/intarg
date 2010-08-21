<?
class Core_Zip_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function getEntryesInfo($filepath, $tipo_objeto='Core_Object', $name=true, $file_size=false, $compressed_size=false, $compression_method=false){
		if(!$name && !$file_size && !$compressed_size && !$compression_method)
			return(null);
		$zip = zip_open($filepath);
		if($zip){
			$ret = array();
		    while($zip_entry = zip_read($zip)){
		    	$class = in_array($tipo_objeto, array('object', 'StdClass', 'array'))?'Core_Object':$tipo_objeto;
		    	$obj = new $class;
		    	if($name)
		    		$obj->setName(zip_entry_name($zip_entry));
		    	if($file_size)
		    		$obj->setFileSize(zip_entry_filesize($zip_entry));
		        if($compressed_size)
		        	$obj->setCompressedSize(zip_entry_compressedsize($zip_entry));
		        if($compression_method)
		        	$obj->setCompressionMethod(zip_entry_compressionmethod($zip_entry));
		        if($tipo_objeto=='array')
		        	$obj = $obj->getData();
		        elseif($tipo_objeto=='StdClass' || $tipo_objeto=='object')
		        	$obj = $obj->getDataAsStdClass();
		        $ret[] = $obj;
		    }
		    zip_close($zip);
		    return($ret);
		}
		return(null);
	}
	public static function extract($filepath, $destino){
		if(!is_array($destino))
			if(substr($destino, -1,1)!='/')
				$destino .= '/';
		$zip = zip_open($filepath);
		if($zip){
			while($zip_entry = zip_read($zip)){
				$zen = zip_entry_name($zip_entry);
				if(is_array($destino)){
					$destino_archivo = array_search($zen, $destino);
					if($destino_archivo===false)
						continue;
					$completePath = dirname($destino_archivo);
					$completeName = $destino_archivo;
				}
				else{
					$completePath = $destino . dirname($zen);
	            	$completeName = $destino . $zen;
				}
            	if(!file_exists($completePath))
            		@mkdir($completePath, 0777, true);
            	if(file_exists($completeName))
            		@unlink($completeName);
            	if (zip_entry_open($zip, $zip_entry, "r")){
            		if ($fd = @fopen($completeName, 'w+')){
						fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
                        fclose($fd);
					}
					else{
						// We think this was an empty directory
						if(!file_exists($completeName))
							mkdir($completeName, 0777);
					}
					zip_entry_close($zip_entry);
				}
			}
		    zip_close($zip);
		    return(true);
		}
		return(null);
	}
}
?>