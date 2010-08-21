<?
abstract class Core_Singleton extends Core_Object{
    static function getInstanceOf ($class)
    // implements the 'singleton' design pattern.
    {
        static $instances = array();  // array of instance names
        if (!array_key_exists($class, $instances)) {
            // instance does not exist, so create it
            $instances[$class] = new $class;
        } // if
        $instance =& $instances[$class];
        return $instance;
    } // getInstance
    abstract public function getInstance();
}

?>