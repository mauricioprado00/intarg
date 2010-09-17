<?php

class Admin_Chained_Router extends Core_Router_Abstract{
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

	protected function dispatchNode($classname){
		Core_App::getLayout()->setModo('admin_chained');
		if($root = Core_App::getLoadedLayout()->getBlock('root')){
			Core_App::getLayout()->appendBlock('<'.$classname.' name="chained_control"/>', $root, $classname);
			if($chained_control = Core_App::getLoadedLayout()->getBlock('chained_control')){
				$post = Core_Http_Post::getParameters('Core_Object');
				if($post && $post->hasChainOptions() && ($chain_options = $post->getChainOptions())){
					$chained_control->setChainOptions($chain_options);
				}
				if($post && $post->hasName() && ($name = $post->getName())){
					$chained_control->getSelectControl()
						->setName($name);
				}
				//var_dump($post->hasChainOptions());
				//echo get_class($chained_control);
			}
		}
		//->setActions(array());//reset
		//Core_App::getLayout()->addActions('admin_chained');
		return;
	}
}
?>