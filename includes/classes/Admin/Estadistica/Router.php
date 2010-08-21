<?
class Admin_Estadistica_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'listar_acceso_categoria','datalist_acceso_categoria'
			,'listar_acceso_sesion','datalist_acceso_sesion'
			,'listar_acceso_sesion_url','datalist_acceso_sesion_url'
			,'listar_click_anuncio','datalist_click_anuncio'
			,'listar_click_anuncio_barrio','datalist_click_anuncio_barrio'
			,'listar_click_anuncio_categoria','datalist_click_anuncio_categoria'
			,'listar_click_banner_carrousel','datalist_click_banner_carrousel'
			,'listar_click_banner_dinamico','datalist_click_banner_dinamico'
			,'listar_click_minisitio','datalist_click_minisitio'
			,'listar_inicio_sesion','datalist_inicio_sesion'
			,'listar_contador','datalist_contador'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_estadistica');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	
	/*<acceso_categoria>*/
	protected function listar_acceso_categoria($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_acceso_categoria');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_acceso_categoria');
			$fecha_desde = implode('',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_Estadistica_AccesoCategoria();
			$e->setWhere(Db_Helper::between('dia', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_acceso_categoria_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('dia','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_acceso_categoria(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_acceso_categoria');
	}
	/*<acceso_categoria>*/

	/*<acceso_sesion>*/
	protected function listar_acceso_sesion($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_acceso_sesion');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_acceso_sesion');
			$fecha_desde = implode('',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_Estadistica_AccesoSesion();
			$e->setWhere(Db_Helper::between('dia', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_acceso_sesion_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('dia','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_acceso_sesion(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_acceso_sesion');
	}
	/*</acceso_sesion>*/
	
	/*<acceso_sesion_url>*/
	protected function listar_acceso_sesion_url($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_acceso_sesion_url');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_acceso_sesion_url');
			$fecha_desde = implode('',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_Estadistica_AccesoSesionUrl();
			$e->setWhere(Db_Helper::between('dia', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_acceso_sesion_url_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('dia','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_acceso_sesion_url(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_acceso_sesion_url');
	}
	/*</acceso_sesion_url>*/

	/*<click_anuncio>*/
	protected function listar_click_anuncio($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_click_anuncio');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_click_anuncio');
			$fecha_desde = implode('',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_Estadistica_ClickAnuncio();
			$e->setWhere(Db_Helper::between('dia', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_click_anuncio_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('dia','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_click_anuncio(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_click_anuncio');
	}
	/*</click_anuncio>*/
	
	/*<click_anuncio_barrio>*/
	protected function listar_click_anuncio_barrio($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_click_anuncio_barrio');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_click_anuncio_barrio');
			$fecha_desde = implode('',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_Estadistica_ClickAnuncioBarrio();
			$e->setWhere(Db_Helper::between('dia', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_click_anuncio_barrio_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('dia','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_click_anuncio_barrio(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_click_anuncio_barrio');
	}
	/*</click_anuncio_barrio>*/
	
	/*<click_anuncio_categoria>*/
	protected function listar_click_anuncio_categoria($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_click_anuncio_categoria');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_click_anuncio_categoria');
			$fecha_desde = implode('',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_Estadistica_ClickAnuncioCategoria();
			$e->setWhere(Db_Helper::between('dia', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_click_anuncio_categoria_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('dia','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_click_anuncio_categoria(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_click_anuncio_categoria');
	}
	/*</click_anuncio_categoria>*/

	/*<click_banner_carrousel>*/
	protected function listar_click_banner_carrousel($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_click_banner_carrousel');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_click_banner_carrousel');
			$fecha_desde = implode('',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_Estadistica_ClickBannerCarrousel();
			$e->setWhere(Db_Helper::between('dia', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_click_banner_carrousel_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('dia','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_click_banner_carrousel(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_click_banner_carrousel');
	}
	/*</click_banner_carrousel>*/	
	
	/*<click_banner_dinamico>*/
	protected function listar_click_banner_dinamico($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_click_banner_dinamico');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_click_banner_dinamico');
			$fecha_desde = implode('',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_Estadistica_ClickBannerDinamico();
			$e->setWhere(Db_Helper::between('dia', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_click_banner_dinamico_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('dia','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_click_banner_dinamico(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_click_banner_dinamico');
	}
	/*</click_banner_dinamico>*/		
	
	/*<click_minisitio>*/
	protected function listar_click_minisitio($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_click_minisitio');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_click_minisitio');
			$fecha_desde = implode('',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_Estadistica_ClickMinisitio();
			$e->setWhere(Db_Helper::between('dia', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_click_minisitio_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('dia','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_click_minisitio(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_click_minisitio');
	}
	/*</click_minisitio>*/
	
	/*<inicio_sesion>*/
	protected function listar_inicio_sesion($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_inicio_sesion');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_inicio_sesion');
			$fecha_desde = implode('',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_Estadistica_InicioSesion();
			$e->setWhere(Db_Helper::between('dia', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_inicio_sesion_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('dia','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_inicio_sesion(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_inicio_sesion');
	}
	/*</inicio_sesion>*/

	/*<contador>*/
	protected function listar_contador($tipo='html',$fecha_desde=null,$fecha_hasta=null){
		if($tipo=='html'){
			Core_App::getLayout()->addActions('entity_list', 'list_admin_estadistica_contador');
			Admin_App::getInstance()->addWarningMessage('La información aqui disponible sera eliminada automaticamente. Para preservar esta información para otra finalidades deberá descargarla o desactivar el borrado desde las configuraciones.');
		}
		elseif($tipo=='excel'){
			Core_App::getLayout()->setActions(array());//reset
			Core_App::getLayout()->addActions('excel', 'excel_admin_estadistica_contador');
			$fecha_desde = implode('-',array_reverse(explode('-', $fecha_desde)));
			$fecha_hasta = implode('-',array_reverse(explode('-', $fecha_hasta)));
			$e = new Granguia_Model_View_Contador();
			$e->setWhere(Db_Helper::between('fecha', $fecha_desde, $fecha_hasta));
			Core_Http_Header::ContentEncoding('gzip');
			Core_Http_Header::avoidCache();
			Core_Http_Header::ContentDisposition('estadistica_contador_'.$fecha_desde.'_'.$fecha_hasta.'.xls');
			Core_Http_Header::ContentType('application/vnd.ms-excel');
			Core_Http_Header::ContentType('application/x-msexcel');
			$listado = $e->search('id','ASC');
			foreach($listado as $e){
				$e->addAutofilterOutput('utf8_decode');
			}
			Core_App::getLoadedLayout()->setUseCompression(true)->getBlock('excel')->setListado($listado);
		}
		//$this->cambiarUrlAjax('administrator/estadistica/listar');
	}
	protected function datalist_contador(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estadistica_contador');
	}
	/*<contador>*/


	protected function dispatchNode(){
		return;
	}
}
?>