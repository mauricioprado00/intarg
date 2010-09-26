<?
class Admin_Translate_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		
		$this
			->setTextField('nombre')
			//->setTextFormat('%s, %s')
		;
	}
    protected function _prepareLayout()
    {
		$translate = new Inta_Model_Traduccion();
    	$this
			->setEntityToList($translate)
		;
        return $this;
    }
}
?>