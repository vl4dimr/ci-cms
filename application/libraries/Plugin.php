<?php

//taken from wp


class Plugin {
	var $_filter = array();
	var $_action = array();
	var $obj;
	
	function Plugin ()
	{
		//get plugins for activated modules
		$this->obj =& get_instance();
		if (is_array($this->obj->system->modules))
		{
			foreach($this->obj->system->modules as $module)
			{
				$plugin_file = APPPATH.'modules/'.$module['name'].'/'.$module['name'].'_plugins.php';
						if ( file_exists($plugin_file) )
						{
							$this->obj->benchmark->mark($module['name'] . '_start'); 
							include($plugin_file);
							$this->obj->benchmark->mark($module['name'] . '_end'); 
						}
			}
		}	
		log_message('debug', "Plugin Class Initialized");
	}
	
	function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {

		// So the format is wp_filter['tag']['array of priorities']['array of functions serialized']['array of ['array (functions, accepted_args)]']
		$this->_filter[$tag][$priority][serialize($function_to_add)] = array('function' => $function_to_add, 'accepted_args' => $accepted_args);
		return true;
	}

	function apply_filters($tag, $string) {


		$this->merge_filters($tag);

		if ( !isset($this->_filter[$tag]) )
			return $string;

		$args = func_get_args();

		do{
			foreach( (array) current($this->_filter[$tag]) as $the_ )
				if ( !is_null($the_['function']) ){
					$args[1] = $string;
					$string = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
				}

		} while ( next($this->_filter[$tag]) );

		return $string;
	}

	function merge_filters($tag) {


		if ( isset($this->_filter['all']) )
			$this->_filter[$tag] = array_merge($this->_filter['all'], (array) $this->_filter[$tag]);

		if ( isset($this->_filter[$tag]) ){
			reset($this->_filter[$tag]);
			uksort($this->_filter[$tag], "strnatcasecmp");
		}
	}

	function remove_filter($tag, $function_to_remove, $priority = 10, $accepted_args = 1) {
		unset($this->_filter[$tag][$priority][serialize($function_to_remove)]);
		return true;
	}

	//
	// Action functions
	//

	function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
		$this->add_filter($tag, $function_to_add, $priority, $accepted_args);
	}

	function do_action($tag, $arg = '') {
		$args = array();
		if ( is_array($arg) && 1 == count($arg) && is_object($arg[0]) ) // array(&$this)
			$args[] =& $arg[0];
		else
			$args[] = $arg;
		for ( $a = 2; $a < func_num_args(); $a++ )
			$args[] = func_get_arg($a);

		$this->merge_filters($tag);

		if ( !isset($this->_filter[$tag]) )
			return;

		do{
			foreach( (array) current($this->_filter[$tag]) as $the_ )
				if ( !is_null($the_['function']) )
					call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));

		} while ( next($this->_filter[$tag]) );

		if ( is_array($this->_action) )
			$this->_action[] = $tag;
		else
			$this->_action = array($tag);
	}

	// Returns the number of times an action has been done
	function did_action($tag) {

		return count(array_keys($this->_action, $tag));
	}

	function do_action_ref_array($tag, $args) {

		if ( !is_array($this->_action) )
			$this->_action = array($tag);
		else
			$this->_action[] = $tag;

		$this->merge_filters($tag);

		if ( !isset($this->_filter[$tag]) )
			return;

		do{
			foreach( (array) current($this->_filter[$tag]) as $the_ )
				if ( !is_null($the_['function']) )
					call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));

		} while ( next($this->_filter[$tag]) );

	}

	function remove_action($tag, $function_to_remove, $priority = 10, $accepted_args = 1) {
		$this->remove_filter($tag, $function_to_remove, $priority, $accepted_args);
	}

}

?>