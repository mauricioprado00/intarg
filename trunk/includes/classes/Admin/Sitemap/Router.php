<?
class Admin_Sitemap_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'generar'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_pagina');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function dispatchNode(){
		return;
	}
    protected function generar(){
            $nodo = new Granguia_Model_Nodo();
            $nodo->setWhere('es_activa = 1');
            $aNodos = $nodo->search(null,'ASC',null,0,'Granguia_Model_Nodo');
            $xml = new Base_XmlW('urlset');
            $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            foreach($aNodos As $oNodo){
                $tipoNodo = $oNodo->getTipoNodo();
                $pr = $tipoNodo->getPrioridadSeo() ? $tipoNodo->getPrioridadSeo() : '0.5';
                if($pr==0)
                	continue;
                $xml->startElement('url');
                    $xml->setCData('loc',Core_App::getUrlModel()->getUrl($oNodo->getPathUrl()));
                    $xml->setCData('priority',$pr);
                $xml->endElement();
            }
//            die($xml->output());
            if($h = fopen(CFG_PATH_ROOT . '/sitemap.xml', 'w'))
                fwrite($h,$xml->getDocument());
            else
            {
                echo utf8_encode("Error al crear el archivo.");
                die();
            }
            echo utf8_encode("Sitemap generado");
            die();
        }
}
?>