<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @version $Id$
 * @package solaitra
 * @copyright Copyright (C) 2005 - 2008 Tsiky dia Ampy. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 */



class Locale {
	//initializing
	var $_data;
	var $locale;
	var $_l10n;

	function Locale() {
		
		log_message('debug', 'Locale Class Initialized');

	}
	

	function __($text, $domain = 'default') {

		if (isset($this->_l10n[$domain]))
			return $this->_l10n[$domain]->translate($text);
		else
			return $text;
	}

	function tr($text, $domain = 'default') {
		
		return $this->__($text, $domain);
	}
	function _e($text, $domain = 'default') {
		
		echo $this->__($text, $domain);
	}

	function __ngettext($single, $plural, $number, $domain = 'default') {

		if (isset($this->_l10n[$domain])) {
			return $this->_l10n[$domain]->ngettext($single, $plural, $number);
		} else {
			if ($number != 1)
				return $plural;
			else
				return $single;
		}
	}
	function load_textdomain($mofile, $domain = 'default') {
		@include(APPPATH . 'libraries/gettext' . EXT);
		@include(APPPATH . 'libraries/streams' . EXT);

		if (isset($this->_l10n[$domain]))
			return;

		if ( is_readable($mofile))
			$input = new CachedFileReader($mofile);
		else
			return;

		$this->_l10n[$domain] = new gettext_reader($input);
	}
}

?>