<?php

//taken from wp


class Block {
	var $_blocks = array();

	function Block() {
		//get blocks for activated modules
		$this->obj =& get_instance();
		if (is_array($this->obj->system->modules))
		{
			foreach($this->obj->system->modules as $module)
			{
				//$class_name = ucfirst($module).'_Blocks';
				$block_file = APPPATH.'modules/'.$module['name'].'/'.$module['name'].'_blocks.php';
						if ( file_exists($block_file) )
						{
							include($block_file);
						}
			}
		}
		log_message('debug', "Blog Class Initialized");
	}
	
	function set($block_name, $call_back, $priority = 10)
	{
		$this->_blocks[$block_name][$priority][serialize($call_back)] = array('function' => $call_back);
	}
	
	function get($block_name, $param = '')
	{
		$params = array();
		if ( is_array($param) && 1 == count($param) && is_object($param[0]) ) // array(&$this)
			$params[] =& $param[0];
		else
			$params[] = $param;
		for ( $a = 2; $a < func_num_args(); $a++ )
			$params[] = func_get_arg($a);

		$this->merge_blocks($block_name);

		if ( !isset($this->_blocks[$block_name]) )
			return;
			
		do{
			foreach( (array) current($this->_blocks[$block_name]) as $the_ )
				if ( !is_null($the_['function']) )
					call_user_func_array($the_['function'], $params);

		} while ( next($this->_blocks[$block_name]) );

		if ( is_array($this->_blocks) )
			$this->_blocks[] = $block_name;
		else
			$this->_blocks = array($block_name);			
			
	}

	function merge_blocks($block_name) {


		if ( isset($this->_blocks['all']) )
			$this->_blocks[$block_name] = array_merge($this->_blocks['all'], (array) $this->_blocks[$block_name]);

		if ( isset($this->_blocks[$block_name]) ){
			reset($this->_blocks[$block_name]);
			uksort($this->_blocks[$block_name], "strnatcasecmp");
		}
	}

}

?>