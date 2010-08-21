<?
abstract class Core_Block_Abstract extends Core_Object implements Core_IHtmlRenderer{

    /**
     * Block name in layout
     *
     * @var string
     */
    protected $_name;

    /**
     * Parent layout of the block
     *
     * @var Base_Layout
     */
    protected $_layout;

    /**
     * Parent block
     *
     * @var Core_Block_Abstract
     */
    protected $_parent;

    /**
     * Short alias of this block to be refered from parent
     *
     * @var string
     */
    protected $_alias;

    /**
     * Suffix for name of anonymous block
     *
     * @var string
     */
    protected $_anonSuffix;

    /**
     * Contains references to child block objects
     *
     * @var array
     */
    protected $_children = array();

    /**
     * Sorted children list
     *
     * @var array
     */
    protected $_sortedChildren = array();

    /**
     * Children blocks HTML cache array
     *
     * @var array
     */
    protected $_childrenHtmlCache = array();

    /**
     * Request object
     *
     * @var Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     * Messages block instance
     *
     * @var Mage_Core_Block_Messages
     */
    protected $_messagesBlock = null;

    /**
     * Whether this block was not explicitly named
     *
     * @var boolean
     */
    protected $_isAnonymous = false;

    /**
     * Parent block
     *
     * @var Core_Block_Abstract
     */
    protected $_parentBlock;

    protected static $_urlModel;

	
    /**
     * Internal constructor, that is called from real constructor
     *
     * Please override this one instead of overriding real __construct constructor
     *
     */
    protected function _construct()
    {
        /**
         * Please override this one instead of overriding real __construct constructor
         */
    }

    /**
     * Retrieve request object
     *
     * @return Mage_Core_Controller_Request_Http
     */
    public function getRequest()
    {
        if ($controller = Mage::app()->getFrontController()) {
            $this->_request = $controller->getRequest();
        }
        else {
            throw new Exception(Mage::helper('core')->__("Can't retrieve request object"));
        }
        return $this->_request;
    }

    /**
     * Retrieve parent block
     *
     * @return Core_Block_Abstract
     */
    public function getParentBlock()
    {
        return $this->_parentBlock;
    }

    /**
     * Set parent block
     *
     * @param   Core_Block_Abstract $block
     * @return  Core_Block_Abstract
     */
    public function setParentBlock(Core_Block_Abstract $block)
    {
        $this->_parentBlock = $block;
        return $this;
    }

    /**
     * Retrieve current action object
     *
     * @return Mage_Core_Controller_Varien_Action
     */
    public function getAction()
    {
        return Mage::app()->getFrontController()->getAction();
    }

    /**
     * Set layout object
     *
     * @param   Base_Layout $layout
     * @return  Core_Block_Abstract
     */
    public function setLayout(Base_Layout $layout)
    {
        $this->_layout = $layout;
        //Mage::dispatchEvent('core_block_abstract_prepare_layout_before', array('block' => $this));
        $this->_prepareLayout();
        //Mage::dispatchEvent('core_block_abstract_prepare_layout_after', array('block' => $this));
        return $this;
    }

    /**
     * Preparing global layout
     *
     * You can redefine this method in child classes for changin layout
     *
     * @return Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * Retrieve layout object
     *
     * @return Base_Layout
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    public function getIsAnonymous()
    {
        return $this->_isAnonymous;
    }

    public function setIsAnonymous($flag)
    {
        $this->_isAnonymous = $flag;
        return $this;
    }

    public function getAnonSuffix()
    {
        return $this->_anonSuffix;
    }

    public function setAnonSuffix($suffix)
    {
        $this->_anonSuffix = $suffix;
        return $this;
    }

    public function getBlockAlias()
    {
        return $this->_alias;
    }

    public function setBlockAlias($alias)
    {
        $this->_alias = $alias;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        if (!empty($this->_name) && $this->getLayout()) {
            $this->getLayout()
            ->unsetBlock($this->_name)
            ->setBlock($name, $this);
        }
        $this->_name = $name;
        return $this;
    }

    public function getSortedChildren()
    {
        return $this->_sortedChildren;
    }

    /**
     * Set block attribute value
     *
     * Wrapper for method "setData"
     *
     * @param   string $name
     * @param   mixed $value
     * @return  Core_Block_Abstract
     */
    public function setAttribute($name, $value=null)
    {
        return $this->setData($name, $value);
    }

    /**
     * Set child block
     *
     * @param   string $name
     * @param   Core_Block_Abstract $block
     * @return  Core_Block_Abstract
     */
    public function setChild($alias, $block)
    {
        if (is_string($block)) {
            $block = $this->getLayout()->getBlock($block);
        }
        /**
         * @see self::insert()
         */
        if (!$block) {
            return $this;
        }
        if ($block->getIsAnonymous()) {

            $suffix = $block->getAnonSuffix();
            if (empty($suffix)) {
                $suffix = 'child'.sizeof($this->_children);
            }
            $blockName = $this->getNameInLayout().'.'.$suffix;

            if ($this->getLayout()) {
                $this->getLayout()
                ->unsetBlock($block->getNameInLayout())
                ->setBlock($blockName, $block);
            }

            $block->setNameInLayout($blockName);
            $block->setIsAnonymous(false);

            if (empty($alias)) {
                $alias = $blockName;
            }
        }

        $block->setParentBlock($this);
        $block->setBlockAlias($alias);

        $this->_children[$alias] = $block;

        return $this;
    }

    /**
     * Unset child block
     *
     * @param   string $name
     * @return  Core_Block_Abstract
     */
    public function unsetChild($alias)
    {
        if (isset($this->_children[$alias])) {
            unset($this->_children[$alias]);
        }

        if (!empty($this->_sortedChildren)) {
            $key = array_search($alias, $this->_sortedChildren);
            if ($key!==false) {
                unset($this->_sortedChildren[$key]);
            }
        }

        return $this;
    }

    /**
     * Call a child and unset it, if callback matched result
     *
     * $params will pass to child callback
     * $params may be array, if called from layout with elements with same name, for example:
     * ...<foo>value_1</foo><foo>value_2</foo><foo>value_3</foo>
     *
     * Or, if called like this:
     * ...<foo>value_1</foo><bar>value_2</bar><baz>value_3</baz>
     * - then it will be $params1, $params2, $params3
     *
     * It is no difference anyway, because they will be transformed in appropriate way.
     *
     * @param string $alias
     * @param string $callback
     * @param mixed $result
     * @param array $params
     * @return Core_Block_Abstract
     */
    public function unsetCallChild($alias, $callback, $result, $params)
    {
        $child = $this->getChild($alias);
        if ($child) {
            $args     = func_get_args();
            $alias    = array_shift($args);
            $callback = array_shift($args);
            $result   = (string)array_shift($args);
            if (!is_array($params)) {
                $params = $args;
            }

            if ($result == call_user_func_array(array(&$child, $callback), $params)) {
                $this->unsetChild($alias);
            }
        }
        return $this;
    }

    /**
     * Unset all children blocks
     *
     * @return Core_Block_Abstract
     */
    public function unsetChildren()
    {
        $this->_children = array();
        $this->_sortedChildren = array();
        return $this;
    }

    /**
     * Retrieve child block by name
     *
     * @param  string $name
     * @return mixed
     */
    public function getChild($name='')
    {
        if (''===$name) {
            return $this->_children;
        } elseif (isset($this->_children[$name])) {
            return $this->_children[$name];
        }
        return false;
    }

    /**
     * Retrieve child block HTML
     *
     * @param   string $name
     * @param   boolean $useCache
     * @return  string
     */
    public function getChildHtml($name='', $useCache=true, $sorted=false)
    {
        if ('' === $name) {
            if ($sorted) {
                $children = array();
                foreach ($this->getSortedChildren() as $childName) {
                    $children[$childName] = $this->getLayout()->getBlock($childName);
                }
            } else {
                $children = $this->getChild();
            }
            $out = '';
            foreach ($children as $child) {
                $out .= $this->_getChildHtml($child->getBlockAlias(), $useCache);
            }
            return $out;
        } else {
            return $this->_getChildHtml($name, $useCache);
        }
    }

    public function getChildChildHtml($name, $childName = '', $useCache = true, $sorted = false)
    {
        if (empty($name)) {
            return '';
        }
        $child = $this->getChild($name);
        if (!$child) {
            return '';
        }
        return $child->getChildHtml($childName, $useCache, $sorted);
    }

    /**
     * Obtain sorted child blocks
     *
     * @return array
     */
    public function getSortedChildBlocks()
    {
        $children = array();
        foreach ($this->getSortedChildren() as $childName) {
            $children[$childName] = $this->getLayout()->getBlock($childName);
        }
        return $children;
    }

    /**
     * Retrieve child block HTML
     *
     * @param   string $name
     * @param   boolean $useCache
     * @return  string
     */
    protected function _getChildHtml($name, $useCache=true)
    {
        if ($useCache && isset($this->_childrenHtmlCache[$name])) {
            return $this->_childrenHtmlCache[$name];
        }

        $child = $this->getChild($name);

        if (!$child) {
            $html = '';
        } else {
            $this->_beforeChildToHtml($name, $child);
            $html = $child->toHtml();
        }

        $this->_childrenHtmlCache[$name] = $html;
        return $html;
    }

    /**
     * Prepare child block before generate html
     *
     * @param   string $name
     * @param   Core_Block_Abstract $child
     */
    protected function _beforeChildToHtml($name, $child)
    {
    }

    /**
     * Retrieve block html
     *
     * @param   string $name
     * @return  string
     */
    public function getBlockHtml($name)
    {
        if (!($layout = $this->getLayout()) && !($layout = Mage::app()->getFrontController()->getAction()->getLayout())) {
            return '';
        }
        if (!($block = $layout->getBlock($name))) {
            return '';
        }
        return $block->toHtml();
    }

    /**
     * Insert child block
     *
     * @param   Core_Block_Abstract|string $block
     * @param   string $siblingName
     * @param   boolean $after
     * @param   string $alias
     * @return  object $this
     */
    public function insert($block, $siblingName='', $after=false, $alias='')
    {
        if (is_string($block)) {
            $block = $this->getLayout()->getBlock($block);
        }
        if (!$block) {
            /*
             * if we don't have block - don't throw exception because
             * block can simply removed using layout method remove
             */
            //Mage::throwException(Mage::helper('core')->__('Invalid block name to set child %s: %s', $alias, $block));
            return $this;
        }
        if ($block->getIsAnonymous()) {
            $this->setChild('', $block);
            $name = $block->getNameInLayout();
        } elseif ('' != $alias) {
            $this->setChild($alias, $block);
            $name = $block->getNameInLayout();
        } else {
            $name = $block->getNameInLayout();
            if(!$name)
            	$block->setIsAnonymous(true);
            $this->setChild($name, $block);
        }
        if(!$block->getLayout()){
			$block->setLayout($this->getLayout());
		}
        if (''===$siblingName) {
            if ($after) {
                array_push($this->_sortedChildren, $name);
            }
            else {
                array_unshift($this->_sortedChildren, $name);
            }
        } else {
            $key = array_search($siblingName, $this->_sortedChildren);
            if (false!==$key) {
                if ($after) {
                    $key++;
                }
                array_splice($this->_sortedChildren, $key, 0, $name);
            } else {
                if ($after) {
                    array_push($this->_sortedChildren, $name);
                }
                else {
                    array_unshift($this->_sortedChildren, $name);
                }
            }
        }

        return $this;
    }

    /**
     * Append child block
     *
     * @param   Core_Block_Abstract|string $block
     * @param   string $alias
     * @return  Core_Block_Abstract
     */
    public function append($block, $alias='')
    {
        $this->insert($block, '', true, $alias);
        return $this;
    }

    /**
     * Before rendering html, but after trying to load cache
     *
     * @return Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        return $this;
    }

    /**
     * Produce and return block's html output
     *
     * It is a final method, but you can override _toHmtl() method in descendants if needed
     *
     * @return string
     */
    protected function _allwaysBeforeToHtml(){
		return($this);
	}
    final public function toHtml()
    {
        //Mage::dispatchEvent('core_block_abstract_to_html_before', array('block' => $this));

//        if (Mage::getStoreConfig('advanced/modules_disable_output/'.$this->getModuleName())) {
//            return '';
//        }
		$this->_allwaysBeforeToHtml();
        if (!($html = $this->_loadCache())) {
            //$translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
//            if ($this->hasData('translate_inline')) {
//                $translate->setTranslateInline($this->getData('translate_inline'));
//            }

            $this->_beforeToHtml();
            $html = $this->_toHtml();
//            $this->_saveCache($html);

//            if ($this->hasData('translate_inline')) {
//                $translate->setTranslateInline(true);
//            }
        }

        $html = $this->_afterToHtml($html);
        //Mage::dispatchEvent('core_block_abstract_to_html_after', array('block' => $this));
        return $html;
    }

    /**
     * Processing block html after rendering
     *
     * @param   string $html
     * @return  string
     */
    protected function _afterToHtml($html)
    {
        return $html;
    }

    /**
     * Override this method in descendants to produce html
     *
     * @return string
     */
    protected function _toHtml()
    {
        return '';
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    protected function _getUrlModelClass()
    {
        return 'core/url';
    }

    /**
     * Enter description here...
     *
     * @return Mage_Core_Model_Url
     */
    protected function _getUrlModel()
    {
    	if($this->hasUrlRequestHandler()){
    		$handler = $this->getUrlRequestHandler();
    		if(is_string($handler)){
				$handler = $this->getLayout()->getBlock($handler);
			}
			return($handler);
		}
        //return Mage::getModel($this->_getUrlModelClass());;
        return Core_App::getUrlModel();
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route='', $params=array())
    {
        return $this->_getUrlModel()->getUrl($route, $params);
    }

    /**
     * Generate base64-encoded url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrlBase64($route='', $params=array())
    {
        return Mage::helper('core')->urlEncode($this->getUrl($route, $params));
    }

    /**
     * Generate url-encoded url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrlEncoded($route = '', $params = array())
    {
        return Mage::helper('core')->urlEncode($this->getUrl($route, $params));
    }

    /**
     * Retrieve url of skins file
     *
     * @param   string $file path to file in skin
     * @param   array $params
     * @return  string
     */
    public function getSkinUrl($file=null, array $params=array())
    {
        //return Mage::getDesign()->getSkinUrl($file, $params);
        return($this->getLayout()->getSkinUrl($file));
    }

    /**
     * Retrieve path of skins file
     *
     * @param   string $file path to file in skin
     * @param   array $params
     * @return  string
     */
    public function getSkinPath($file=null, array $params=array())
    {
        //return Mage::getDesign()->getSkinUrl($file, $params);
        return($this->getLayout()->getSkinPath($file));
    }

    /**
     * Retrieve messages block
     *
     * @return Mage_Core_Block_Messages
     */
    public function getMessagesBlock()
    {
        if (is_null($this->_messagesBlock)) {
            return $this->getLayout()->getMessagesBlock();
        }
        return $this->_messagesBlock;
    }

    /**
     * Set messages block
     *
     * @param   Mage_Core_Block_Messages $block
     * @return  Core_Block_Abstract
     */
    public function setMessagesBlock(Mage_Core_Block_Messages $block)
    {
        $this->_messagesBlock = $block;
        return $this;
    }

    /**
     * Enter description here...
     *
     * @param string $type
     * @return Core_Block_Abstract
     */
    public function getHelper($type)
    {
        return $this->getLayout()->getBlockSingleton($type);
        //return $this->helper($type);
    }

    /**
     * Enter description here...
     *
     * @param string $name
     * @return Core_Block_Abstract
     */
    public function helper($name)
    {
        if ($this->getLayout()) {
            return $this->getLayout()->helper($name);
        }
        return Mage::helper($name);
    }

    /**
     * Retrieve formating date
     *
     * @param   string $date
     * @param   string $format
     * @param   bool $showTime
     * @return  string
     */
    public function formatDate($date=null, $format='short', $showTime=false)
    {
        return $this->helper('core')->formatDate($date, $format, $showTime);
    }

    /**
     * Retrieve formating time
     *
     * @param   string $time
     * @param   string $format
     * @param   bool $showDate
     * @return  string
     */
    public function formatTime($time=null, $format='short', $showDate=false)
    {
        return $this->helper('core')->formatTime($time, $format, $showDate);
    }

    /**
     * Retrieve module name of block
     *
     * @return string
     */
    public function getModuleName()
    {
        $module = $this->getData('module_name');
        if (is_null($module)) {
            $class = get_class($this);
            $module = substr($class, 0, strpos($class, '_Block'));
            $this->setData('module_name', $module);
        }
        return $module;
    }

    /**
     * Translate block sentence
     *
     * @return string
     */
    public function __()
    {
        $args = func_get_args();
//        if($args[0]=='Add to Cart')
//        	$x = true;
        $expr = new Mage_Core_Model_Translate_Expr(array_shift($args), $this->getModuleName());
//        if(isset($x))
//        	var_dump($expr);
        array_unshift($args, $expr);
        return Mage::app()->getTranslator()->translate($args);
    }

    /**
     * Get Key for caching block content
     *
     * @return string
     */
    public function getCacheKey()
    {
        if (!$this->hasData('cache_key')) {
            $this->setCacheKey($this->getNameInLayout());
        }
        return $this->getData('cache_key');
    }

    /**
     * Get tags array for saving cache
     *
     * @return array
     */
    public function getCacheTags()
    {
        if (!$this->hasData('cache_tags')) {
            $tags = array();
        } else {
            $tags = $this->getData('cache_tags');
        }
        $tags[] = 'block_html';
        return $tags;
    }

    /**
     * Get block cache life time
     *
     * @return int
     */
    public function getCacheLifetime()
    {
        if (!$this->hasData('cache_lifetime')) {
            return null;
        }
        return $this->getData('cache_lifetime');
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    protected function _loadCache()
    {
        if (is_null($this->getCacheLifetime()) || !Mage::app()->useCache('block_html')) {
            return false;
        }
        return Mage::app()->loadCache($this->getCacheKey());
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $data
     * @return Core_Block_Abstract
     */
    protected function _saveCache($data)
    {
        if (is_null($this->getCacheLifetime()) || !Mage::app()->useCache('block_html')) {
            return false;
        }
        Mage::app()->saveCache($data, $this->getCacheKey(), $this->getCacheTags(), $this->getCacheLifetime());
        return $this;
    }

    /**
     * Escape html entities
     *
     * @param   mixed $data
     * @param   array $allowedTags
     * @return  mixed
     */
    public function htmlEscape($data, $allowedTags = null)
    {
        return $this->helper('core')->htmlEscape($data, $allowedTags);
    }

    /**
     * Escape quotes in java scripts
     *
     * @param mixed $data
     * @param string $quote
     * @return mixed
     */
    public function jsQuoteEscape($data, $quote = '\'')
    {
        return $this->helper('core')->jsQuoteEscape($data, $quote);
    }

//    public function getNameInLayout()
//    {
//        return $this->_getData('name_in_layout');
//    }

    public function countChildren()
    {
        return count($this->_children);
    }

    /**
     * Prepare url for save to cache
     *
     * @return Core_Block_Abstract
     */
    protected function _beforeCacheUrl()
    {
        if (Mage::app()->useCache('block_html')) {
            Mage::app()->setUseSessionVar(true);
        }
        return $this;
    }

    /**
     * Replace URLs from cache
     *
     * @param string $html
     * @return string
     */
    protected function _afterCacheUrl($html)
    {
        if (Mage::app()->useCache('block_html')) {
            Mage::app()->setUseSessionVar(false);
            Varien_Profiler::start('CACHE_URL');
            $html = Mage::getSingleton('core/url')->sessionUrlVar($html);
            Varien_Profiler::stop('CACHE_URL');
        }
        return $html;
    }
    public function onBeforeInsertChild($block){
		return($block);
	}
	public function onAfterLayoutLoad(){
		return;
	}
	private $custom_block_types_to_classnames = array();
	protected function addCustomBlockType($type, $classname){
		$this->custom_block_types_to_classnames[$type] = $classname;
		return($this);
	}
	public function getCustomBlockTypes(){
		$ret = array();
		foreach($this->custom_block_types_to_classnames as $type=>$classname){
			$ret[] = $type;
		}
		return($ret);
	}
	public function getCustomBlockTypeClassname($type){
		if(!isset($this->custom_block_types_to_classnames[$type]))
			return(null);
		return($this->custom_block_types_to_classnames[$type]);
	}
	public function getNombreModulo(){
		return((array_shift(explode('_Block_', get_class($this)))));
	}
	protected static function generateRandomId(){
		return(md5(rand(0,1000).microtime(true)));
	}
	public function appendBlock($obj, $classname='', $parent_block=null){
		if($parent_block == null)
			$parent_block = $this;
		return($this->getLayout()->appendBlock($obj, $parent_block, $classname));
	}
	public function appendXmlBlocks($xml, $parent_block=null){
		if($parent_block == null)
			$parent_block = &$this;
		return($this->getLayout()->appendXmlBlocks($xml, $parent_block));
	}
	public function appendXmlReferences($xml){
		return($this->getLayout()->appendXmlReferences($xml));
	}
	public function dump(){
		$this->_dump(0, $dump);
		return($dump);
	}
	private function _dump($ident=0, &$dump){
		$dump .= str_repeat('	', $ident).$this->_dump_me()."\n";
		foreach($this->getChild() as $child){
			$child->_dump($ident+1, $dump);
		}
	}
	protected function _dump_me(){
		return($this->_dump_info().$this->_dump_extra());
	}
	protected function _dump_info(){
		return(get_class($this));
	}
	protected function _dump_extra(){
		return '';
	}
	private $_type;
	public function getType(){
		return $this->_type;
	}
	public function setType($type){
		$this->_type = $type;
		return $this;
	}
	private $_name_in_layout;
	public function getNameInLayout(){
		return $this->_name_in_layout;
	}
	public function setNameInLayout($name_in_layout){
		$this->_name_in_layout = $name_in_layout;
		return $this;
	}
	

			//print $this->getParentBlock()->getChildHtml('editor_desarrollo');
			//$this->getLayout()->appendBlock('<fckeditor name="editor_desarrollo2" />', $this);
			//$this->getLayout()->appendBlock('<fckeditor name="editor_desarrollo2"><fckeditor name="editor_desarrollo3" /></fckeditor>', $this);
			//$this->getLayout()->appendXmlBlocks('<fckeditor name="editor_desarrollo2"><fckeditor name="editor_desarrollo3" /></fckeditor>', $this);
//			$this->getLayout()->appendXmlReferences('
//			<reference name="novedad_add_edit_form">
//				<fckeditor name="editor_desarrollo2"><fckeditor name="editor_desarrollo3" /></fckeditor>
//			</reference>');
			//$this->getLayout()->appendBlock('<fckeditor name="editor_desarrollo3" />', $this->getChild('editor_desarrollo2'));
			//$this->getLayout()->appendBlock('<block type="Core_Wysiwyg_Fckeditor/Editor" name="editor_desarrollo2" />', $this);

			//print $this->getParentBlock()->getChildHtml('editor_desarrollo');
//			$this->appendBlock('<fckeditor name="editor_desarrollo2" />');
//			$this->appendBlock('<fckeditor name="editor_desarrollo2"><fckeditor name="editor_desarrollo3" /></fckeditor>');
//			$this->appendXmlBlocks('<fckeditor name="editor_desarrollo2"><fckeditor name="editor_desarrollo3" /></fckeditor>');
//			$this->appendXmlReferences('
//			<reference name="novedad_add_edit_form">
//				<fckeditor name="editor_desarrollo2"><fckeditor name="editor_desarrollo3" /></fckeditor>
//			</reference>');
//			$this->appendBlock('<fckeditor name="editor_desarrollo3" />', '', $this->getChild('editor_desarrollo2'));
//			$this->appendBlock('<block type="Core_Wysiwyg_Fckeditor/Editor" name="editor_desarrollo2" />');
	
}
?>