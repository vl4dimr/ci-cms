<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @version $Id$
 * @package solaitra
 * @copyright Copyright (C) 2005 - 2008 Tsiky dia Ampy. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 */

function __($text, $domain = 'default') {
	$CI =& get_instance();
	$CI->load->library('Locale');
	return $CI->locale->tr($text, $domain);
}

function _e($text, $domain = 'default') {
	echo __($text, $domain);
}

?>