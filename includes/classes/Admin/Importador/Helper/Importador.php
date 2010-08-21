<?
//function str2bin($str, $mode=0) {
//    $out = false;
//    for($a=0; $a < strlen($str); $a++) {
//        $dec = ord(substr($str,$a,1));
//        $bin = '';
//        for($i=7; $i>=0; $i--) {
//            if ( $dec >= pow(2, $i) ) {
//                $bin .= "1";
//                $dec -= pow(2, $i);
//            } else {
//                $bin .= "0";
//            }
//        }
//        /* Default-mode */
//        if ( $mode == 0 ) $out .= $bin;
//        /* Human-mode (easy to read) */
//        if ( $mode == 1 ) $out .= $bin . " ";
//        /* Array-mode (easy to use) */
//        if ( $mode == 2 ) $out[$a] = $bin;
//    }
//    return $out;
//}

class Admin_Importador_Helper_Importador extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function importarArchivos(){
		$cantidad_archivos_importados = 0;
		$cantidad_archivos_fallidos = 0;
		foreach(Core_Http_Files::getParameters('core_object') as $file){
			if($this->_importarArchivoSubido($file))
				$cantidad_archivos_importados++;
			else $cantidad_archivos_fallidos++;
		}
		if($cantidad_archivos_importados){
			if($cantidad_archivos_fallidos){
				Admin_App::getInstance()->addWarningMessage($cantidad_archivos_importados.' archivo/s Importado/s exitosamente y '.$cantidad_archivos_fallidos.' archivo/s fallidos');
			}
			else{
				Admin_App::getInstance()->addSuccessMessage($cantidad_archivos_importados.' archivo/s Importado/s exitosamente');
			}
		}
		else{
			Admin_App::getInstance()->addErrorMessage("No se pudo importar archivo/s");
		}
		return($cantidad_archivos_importados);
	}
	private function detectarTipoArchivo($file){
		return(strtolower(array_pop(explode('.', basename($file)))));
	}
	private function newFileName($filepath){
		return(CFG_PATH_ROOT."/files/importados/".array_shift(explode('.', basename($filepath))).'_'.date('Ymd_His_u').".".(array_pop(explode('.', basename($filepath)))));
	}
	private function createFilePath($new_file){
		$dir = dirname($new_file);
		if(!file_exists($dir))
			mkdir($dir,0777,true);
	}
	public function _importarArchivoSubido($file){
		if(!$file->hasTmpName()||!$file->getTmpName()){
			echo "no se puede importar no tiene nombre ".get_class($file);
			return(false);
		}
		//$new_file = CFG_PATH_ROOT."/files/importados/".array_shift(explode('.', basename($file->getName()))).date('Ymd_His_u').".xls";
		$newFileName = $this->newFileName($file->getName());
		$this->createFilePath($newFileName);
		if(file_exists($newFileName))
			unlink($newFileName);
		move_uploaded_file($file->getTmpName(),$newFileName);
		return($this->_importarArchivo($newFileName));
	}
	public function importarArchivo($file){
		if(!file_exists($file))
			return(false);
		$newFileName = $this->newFileName($file);
		$this->createFilePath($newFileName);
		copy($file, $newFileName);
		return($this->_importarArchivo($newFileName));
	}
	private function _importarArchivo($file, $id_importacion=null){
		if($id_importacion==null){
			$i = new Admin_Importador_Model_Importacion();
			$i->setArchivo($file);
			$i->replace();
			$id_importacion = $i->getId();
		}
		if(!file_exists($file))
			return(false);
		switch($this->detectarTipoArchivo($file)){
			case 'xls':{
				return($this->importarXls($file, $id_importacion)?1:false);
				break;
			}
			case 'rar':{
				break;
			}
			case 'zip':{
				return($this->importarZip($file, $id_importacion)?1:false);
			}
		}
		return(false);
	}
	private function importarZip($file, $id_importacion){
		Core_Helper::max_execution_time(null,null,1);
		$arr_entryes_info = Core_Zip_Helper::getInstance()->getEntryesInfo($file,'array');
		$imagen = new Admin_Importador_Model_Imagen();
		$ret = 0;
		foreach($arr_entryes_info as $arr_info){
			$imagen->resetData();
			$imagen->loadFromArray($arr_info);
			if(preg_match('/.*\/$/', $imagen->getName()))
				continue;
			$imagen->setIdImportacion($id_importacion);
			$imagen->replace();
			$ret++;
		}
		return($ret);
	}
	private function importarXls($file, $id_importacion){
		Core_Helper::max_execution_time(null,null,1);
		$data = new Spreadsheet_Excel_Reader();
		$data->read($file);
		$header_values = array(
			//"",//micro$oft y sus arrays base 1, vb kradkk
			"Codigo"=>"Codigo",
			//"Nombre",
			"Descripcion"=>"Descripcion",
			"Marca"=>"Marca",
			"Categoria"=>"Categoria",
			"Rubro"=>"Rubro",
			"Stock"=>"Stock Disponible",
			"Precio"=>"Precio Unitario",
			"Imagen"=>"Ruta Imagen"
		);
		
		$config = Granguia_Model_Config::findConfigValue('text_orden_importacion_excel');
		if($config){
			$config = preg_replace('/#.*/', '', $config);
			$config = preg_replace('/\\n\\n/', '', $config);
			$re = '/([a-z_]+)\s*=\s*"(.*?)"/i';
			$cant = preg_match_all($re, $config, $matches);
			if($cant){
				$new_header_values = array();
				//var_dump($matches);
				foreach($matches[1] as $idx=>$fieldname){
					$new_header_values[$fieldname] = utf8_decode($matches[2][$idx]);
				}
				$header_values = $new_header_values;
			}
		}
		$variant_indexs = array();
		$cantidad_sheets_importadas = 0;
		$reindex_header_values = array();
		foreach($data->sheets as $sheet){
			$find_header_row = false;
			$count = 0;
			foreach($sheet['cells'] as $idx_cell=>$cell){
				$is_ok = true;
				foreach($header_values as $fieldname=>$value){
					if($value==='')
						continue;
					$idx = array_search($value, $cell);
					if($idx===false){
						$is_ok = false;
						break;
					}
					else $reindex_header_values[$idx] = $value;
				}
				if($is_ok){
					$find_header_row = $count;
					break;
				}
				$count++;
			}
			
			if($find_header_row===false)
				break;
			
			$cantidad_sheets_importadas++;
			$arr_productos = array_slice($sheet['cells'],$find_header_row+1);
			$idx_field = array();
			foreach($header_values as $fieldname=>$column_title){
				$idx_field[strtolower($fieldname)] = array_search($column_title, $reindex_header_values); 
			}
			
			$producto = new Admin_Importador_Model_Producto();
//			var_dump($producto->getData());
//			var_dump($idx_field);
//			var_dump($arr_productos);
//			die();
//			foreach($arr_productos as $arr_producto){
//				if(!trim($arr_producto[6]))
//					continue;
//				$txt = utf8_encode($arr_producto[6]);
//				//$txt = str_replace(pack('S', 0), '', $txt);
//				$new_txt = '';
//				for($i=0;$i<strlen($txt);$i++){
//					//$x = unpack('S', $txt[$i]);
////					var_dump($x);
//					if(ord($txt[$i])>0){
//						$new_txt .= $txt[$i];
//					}
//				}
//			}
//			die();
			
			$arr_ignorar_marcas = array();
			$marcas = strtolower(Granguia_Model_Config::findConfigValue('text_ignorar_marcas'));
			if($marcas){
				$marcas = preg_replace('/#.*/', '', $marcas);
				$marcas = str_replace("\r\n", "\n", $marcas);
				$marcas = preg_replace('/\\n\\n/', '', $marcas);
				$arr_ignorar_marcas = explode("\n", $marcas);
			}
			$saved_products = array();
			foreach($arr_productos as $arr_producto){
				$producto = new Admin_Importador_Model_Producto();
				foreach($producto->getData() as $key=>$value){
					if($key=='modelos')
						continue;
					if(in_array($key, array('id', 'id_importacion')))
						continue;
					$value = "";
					if($idx_field[$key]!==null&&$arr_producto[$idx_field[$key]]!==null){
						$value = utf8_encode($arr_producto[$idx_field[$key]]);
					}
					switch($key){
						case 'imagen':{
							if($value!==''){
								//$value = array_pop( explode( '/', array_pop( explode( "\\", $value ) ) ) );
								$value = implode( '/', explode( "\\", $value ) );
							}
							break;
						}
						case 'rubro':{
							$regexp = '/([^\*]*)(\*(.*))?/';
							$count = preg_match_all($regexp, $value, $matches);
							$hay_modelos = $count && count($matches[1][0]) && count($matches[3][0]);
							if($hay_modelos){
								$value = $matches[1][0];
								$producto->setData('modelos', $matches[3][0]);
							} 
							break;
						}
						case 'descripcion':{
							$new_txt = '';
							for($i=0;$i<strlen($value);$i++){
								if(ord($value[$i])>0){
									$new_txt .= $value[$i];
								}
							}
							$value = $new_txt;
						}
					}
					$producto->setData($key, $value);
				}
				$marca = $producto->getMarca();
				if($marca && in_array(strtolower($marca), $arr_ignorar_marcas))
					continue;
				if(!$producto->getCodigo())
					continue;
				
				if(isset($saved_products[$producto->getCodigo()])){
					if(!trim($producto->getImagen())){//si ya existe y no tiene imagen esta fila, no aporta nada
						continue;
					}
					$producto_anterior = $saved_products[$producto->getCodigo()];
					$imagen = trim($producto_anterior->getImagen());
					$imagen = $imagen?explode(',', $imagen):array();
					$imagen[] = $producto->getImagen();
					$producto_anterior->setImagen(implode(',', $imagen));
					$producto_anterior->replace();
					continue;
				}
				
				$producto->setIdImportacion($id_importacion);
				$producto->setImportar(null);
				$producto->replace();
				$saved_products[$producto->getCodigo()] = $producto;
			}
		}
		//var_dump($data);
		return($cantidad_sheets_importadas);
	}
	private static function deleteProduccion(){
		self::deleteProduccionDb();
		self::deleteProduccionImageFiles();
	}
	private static function deleteProduccionDb(){
		$obj_producto = new Granguia_Catalog_Model_Producto();
		$obj_producto->truncate();
	}
	private static function deleteProduccionImageFiles(){
		$x = new Base_FileFilterRetriever();
		$path = CFG_PATH_ROOT."/img/";
		$x->Start($path);
		$arr_files = $x->getFilteredFiles();
		if(!$arr_files)
			return;
		foreach($arr_files as $filepath)
			unlink($filepath);
	}
	public static function makeBackup($with_delete=true){
		$id_backup = self::makeBackupDb($with_delete);
		if(!$id_backup)
			return(null);
		self::makeBackupImageFiles($id_backup, $with_delete);
		return($id_backup);
	}
	private static function makeBackupDb($with_delete=true){
		$producto = new Granguia_Catalog_Model_Producto();
		$productos = $producto->search();
		if(!$productos){
			return(null);
		}
		$backup = new Admin_Importador_Model_Backup();
		if(!$backup->replace(null, true)){
			return(false);
		}
		$id_backup = $backup->getId();
		$backup_producto = new Admin_Importador_Model_BackupProducto();
		foreach($productos as $producto){
			$backup_producto->loadFromArray($producto->getData());
			$backup_producto->setId(null);
			$backup_producto->setIdBackup($id_backup);
			$backup_producto->replace();
		}
		if($with_delete){
			$obj_producto = new Granguia_Catalog_Model_Producto();
			$obj_producto->truncate();
		}
		return($id_backup);
	}
	private static function makeBackupImageFiles($id_backup, $with_delete=true){
		$x = new Base_FileFilterRetriever();
		$path = CFG_PATH_ROOT."/img/";
		$x->Start($path);
		//var_dump($x->getFilteredFiles());
		$arr_files = $x->getFilteredFiles();
		$backup_path = CFG_PATH_ROOT.'/files/backup/img/'.$id_backup.'/';
		if(!file_exists($backup_path))
			mkdir($backup_path, 0777, true);
		if(!$arr_files)
			return;
		foreach($arr_files as $filepath){
			if(!$with_delete)
				copy($filepath, $backup_path.basename($filepath));
			else{
				if(file_exists($backup_path.basename($filepath)))
					unlink($backup_path.basename($filepath));
				rename($filepath, $backup_path.basename($filepath));
			}
		}
	}
	public static function importacionAProduccion($arr_ids_importacion_imagenes, $id_importacion_productos, $make_backup, $omitir_ids_productos){
//		echo "<pre>";
//		var_dump($x = func_get_args());
//		echo "</pre>";

		//busco productos a importar
		$objProducto = new Admin_Importador_Model_View_MatchingMultiImage($id_importacion_productos, $arr_ids_importacion_imagenes);
		//$objProducto->setIdImportacion($id_importacion_productos);
		$objProducto->setIdProductoImportacion($omitir_ids_productos);
		//$objProducto->setWhere(Db_Helper::equal('id_importacion'),Db_Helper::in('id',false));
		$objProducto->setWhere(Db_Helper::in('id_producto_importacion', false));
		$productos = $objProducto->search();
		if(!$productos)
			return(false);
			
		//armo las imagenes que corresponden a cada producto, si es que tienen mas de 1
		$arr_productos[] = array();
		foreach($productos as $producto){
			//echo $producto->getIdProductoImportacion().'-';
			$key = $producto->getIdProductoImportacion();
			if(isset($arr_productos[$key])){
				if(isset($arr_productos[$key]['name'])&&$arr_productos[$key]['name']!=''){
					$arr_productos[$key]['name'] .= ','.$producto->getName();
				}
				else{
					$arr_productos[$key]['name'] = $producto->getName();
				}
			}
			else
				$arr_productos[$key] = $producto->getData();
			//var_dump($producto->getData());
			//$producto = new Granguia_Catalog_Model_Producto();
		}
		
		/*borro producción [y hago backup]*/
		if($make_backup)
			self::makeBackup(true);
		self::deleteProduccion();
		/*importo datos*/
		$objProducto = new Granguia_Catalog_Model_Producto();
		foreach($arr_productos as $arr_producto){
			$objProducto->loadFromArray($arr_producto);
			$objProducto->setId(null);
			$objProducto->setImagen($arr_producto['name']);
			$objProducto->replace();
			$objProducto->resetData();
		}
		/*importo imagenes*/
		foreach($arr_ids_importacion_imagenes as $id_importacion_imagenes){
			$importacion_imagen = new Admin_Importador_Model_Importacion();
			$importacion_imagen->setId($id_importacion_imagenes);
			$importacion_imagen->load();
			//var_dump($importacion_imagen);
			Core_Zip_Helper::extract($importacion_imagen->getArchivo(), CFG_PATH_ROOT."/img/");
		}
		return(true);
		//var_dump($arr_productos);
	}
	public static function dumpImage($id){
		$imagen = new Admin_Importador_Model_Imagen();
		$imagen->setId($id);
		if($imagen->load()){
			$importacion = new Admin_Importador_Model_Importacion();
			$importacion->setId($imagen->getIdImportacion());
			if($importacion->load()){
				if(file_exists($importacion->getArchivo())){
//					var_dump($importacion->getData());
//					var_dump($imagen->getData());
					$extension = strtolower(array_pop(explode('.', $imagen->getName())));
					$mimes = array('png'=>'image/png','jpg'=>'image/jpeg','gif'=>'image/gif','jpeg'=>'image/jpeg');
					$destino = array(
						$filepath = (tempnam(CFG_PATH_ROOT."/img/",'')) => $imagen->getName()
					);
					Core_Zip_Helper::extract($importacion->getArchivo(), $destino);
					if(file_exists($filepath)){
						//var_dump(isset($mimes[$extension])?$mimes[$extension]:'image');
						Core_Http_Header::ContentType(isset($mimes[$extension])?$mimes[$extension]:'image');
						readfile($filepath);
						unlink($filepath);
					}
				}
			}
		}
	}
	public static function actionEditarProducto($post){
		$producto = new Admin_Importador_Model_Producto();
		$producto->loadFromArray($post->getData(), true);
		$producto->replace();
	}
	public static function getUrlNoUtilizar($id_importacion){
		return('administrator/importar/noutilizar/'.$id_importacion);
	}
	public static function eliminarArchivoImportado($id_importacion){
		$importacion = new Admin_Importador_Model_Importacion();
		return($importacion->setId($id_importacion)->delete());
	}
}
?>