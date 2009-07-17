<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @version $Id$
 * @package solaitra
 * @copyright Copyright (C) 2005 - 2008 Tsiky dia Ampy. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 */

function getblock($tag, $param = '') 
{
	$params = array();
	if ( is_array($param) && 1 == count($param) && is_object($param[0]) ) // array(&$this)
		$params[] =& $param[0];
	else
		$params[] = $param;
	for ( $a = 2; $a < func_num_args(); $a++ )
		$params[] = func_get_arg($a);
		
	$CI =& get_instance();
	$CI->load->library('block');
	return $CI->block->get($tag, $params);
}

function setblock($block_name, $call_back, $priority = 10)
{
	$CI =& get_instance();
	$CI->load->library('block');
	return $CI->block->set($block_name, $call_back, $priority);
}

?>