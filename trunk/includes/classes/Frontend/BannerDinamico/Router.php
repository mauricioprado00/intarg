<?php
class Frontend_BannerDinamico_Router extends Core_Router_Abstract{
	protected function initialize(){
	}
//	protected function localDispatch(){
//		return false;
//	}
	private function dispatchAll(){//esto no es de Core_Router_Abstract, es un invento para procesar todo lo que no coincide en un solo lado
		$request_path = $this->arr_request_path;
		$banner_dinamico = new Granguia_Model_BannerDinamico();
		$id_banner_dinamico = array_shift($request_path);
		$banner_dinamico->setId($id_banner_dinamico);
		if(!$banner_dinamico->load())
			return false;
		$banner_dinamico->fixLinksFilters();
		$links = $banner_dinamico->getLinks();
		if(count($request_path)&&$request_path[0]!==''){
			$idx_link = array_shift($request_path);
			if(!isset($links[$idx_link])){
				if(strtolower($idx_link)=='http:'){
					array_unshift($request_path, $idx_link);
					$link = implode('/',$request_path);
					$qs = $_SERVER["REDIRECT_QUERY_STRING"];
					if($qs)
						$link .= '?'.$qs;
					//die("tiene  y es url ".$link);
				}
				//else $link = $links[0];
			}
			else $link = $links[$idx_link];
		}
		//else $link = $links[0];
		if(!isset($link)){
			if(!$links){
				//die("no tiene links");
				return false;
			}
			$link = $links[0]; 
		}
		Granguia_Model_Contador::ContarClickBannerDinamico($id_banner_dinamico, $link);
		header('location:'.$link);
		die();
	}
	protected function dispatchNode(){
		return $this->dispatchAll();
	}
}
?>