<?php
/*choreated from php.net*/
class Core_Iterator extends Core_Object implements Iterator
{
	public function rewind()
	{
		reset($this->_data);
	}

	public function current()
	{
		$var = current($this->_data);
		return $var;
	}

	public function key()
	{
		$var = key($this->_data);
		return $var;
	}

	public function next()
	{
		$var = next($this->_data);
		return $var;
	}

	public function valid()
	{
		$var = $this->current() !== false;
		return $var;
	}
}
?>