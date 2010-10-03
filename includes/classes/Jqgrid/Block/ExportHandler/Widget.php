<?php
class Jqgrid_Block_ExportHandler_Widget extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this->setTemplate('jqgrid/export_handler/widget.phtml');
	}
}
?>