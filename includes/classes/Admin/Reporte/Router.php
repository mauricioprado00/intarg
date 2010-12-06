<?
class Admin_Reporte_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist','datalist2',
			'ordenar','setorden','datalist3', 'agregarActividades',
			'clear',
			'formato1','formato2'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_reporte');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_reporte=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Reporte_Actividad()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Reporte.');
		}
		else{
			if(isset($id_reporte)){
				Admin_Reporte_Helper::actionEliminarReporte($id_reporte);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_reporte=null){

		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Reporte_Actividad()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Reporte.');
			//$mensajes[] = 'No tiene permitido editar reportees.';
			$this->listar();
			//return;
		}
		else{
//                        ini_set('display_errors','on');
//                        error_reporting(E_ERROR);
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_reporte = $post&&$post->hasResultadoActividad()?$post->GetResultadoActividad(true):null;

                        if(isset($post_reporte)){
                            $aReportes = Admin_Reporte_Helper::buscarActividadReporte($post_reporte->getData());
                            $id_usuario_logeado = Admin_User_Model_User::getLogedUser()->getId();
                            foreach ($aReportes As $oReporte){
                                c($resultadoActividad = new Inta_Model_Reporte_Actividad())
									->loadFromArray($oReporte)
									->setIdUsuarioLogeado($id_usuario_logeado)
									->replace(null, false, false)
								;
                            }
                            $mostrar_listado = true;
                        }
                        else{

                            $reporte = new Inta_Model_Reporte_Actividad();
                            if(!$mostrar_tabs){
                                    Core_App::getLayout()
                                            ->addActions('entity_new')
                                    ;
                            }			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_reporte)?'seteado':'no seteado'));
                            if($mostrar_listado){
                                    Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_reporte_action');
                                    $this->listar();
                            }
                            else{
                                    Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_reporte');
                                    $layout = Core_App::getLoadedLayout();

                                    if($block_add_edit_list_documentos_reporte = $layout->getBlock('add_edit_list_documentos_reporte')){
                                            $block_add_edit_list_documentos_reporte->setIdEntidad($reporte->getId());
                                    }
                                    if($reporte->getId()&&!$id_reporte){
                                            $this->cambiarUrlAjax('administrator/reporte/addEdit/'.$reporte->getId());
                                    }

                                    $reporte->addAutofilterOutput('utf8_decode');

                                    foreach($layout->getBlocks('reporte_add_edit_form') as $block){
                                            $block->setIdToEdit($reporte->getId());
                                            $block->setObjectToEdit($reporte);
                                    }
                            }
                        }
                        if($mostrar_listado){
                                Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_reporte_action');
                                $this->listar();
                        }
		}
	}
	protected function agregarActividades(){
		$post = Core_Http_Post::getParameters('Core_Object');
		Admin_Reporte_Helper::agregarActividades($post->getIdActividad());
		$this->listar();
		//echo Core_Helper::DebugVars($post->getIdActividad());
	}
	protected function listar(){
		$reporte = new Inta_Model_Reporte_Actividad();
		$reporte->setIdUsuarioLogeado($id_usuario);
		if(!$cantidad = $reporte->searchCount()){
			$this->showAddHelp();
			return;
		}
		Core_App::getLayout()->addActions('entity_list', 'list_admin_reporte');
		$this->cambiarUrlAjax('administrator/reporte/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_reporte');
	}
	protected function datalist2(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_reporte2');
	}
	protected function datalist3(){
		Core_Http_Header::ContentType('text/xml');
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_reporte3');
	}
	protected function showAddHelp(){
		Core_App::getLayout()->addActions('admin_reporte_add_help');
	}
	protected function formato1(){
		Admin_Reporte_Helper_Export_Format1::getInstance()->export();
	}
	protected function formato2(){
		Admin_Reporte_Helper_Export_Format2::getInstance()->export();
	}
	protected function clear(){
		Admin_Reporte_Helper::getInstance()->clearReportesDeUsuario();
		return $this->addEdit();
	}
	protected function dispatchNode(){
		return;
	}
}
?>