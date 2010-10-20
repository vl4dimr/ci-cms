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
	var $table;
	var $codes;
	var $default;

	function Locale() {
		$this->table = ('languages');
		$this->obj =& get_instance();
		$this->codes = $this->get_codes();
		$this->default = $this->get_default();
		
		$this->obj->load->library('session');
		if (!$this->obj->session->userdata('lang')) {
			$this->obj->session->set_userdata('lang', $this->default);
		}

		log_message('debug', 'Locale Class Initialized');

	}
/*
	function load_messages()
	{
		$handle = opendir(APPPATH.'modules');
	
		if ($handle)
		{
			while ( false !== ($module = readdir($handle)) )
			{
				
				// make sure we don't map silly dirs like .svn, or . or ..

				if (substr($module, 0, 1) != ".")
				{
					if ( file_exists(APPPATH . 'modules/'.$module.'/locale/' . $this->obj->session->userdata('lang') . '.mo' )) { 
						//echo APPPATH . 'modules/'.$module.'/locale/' . $this->obj->session->userdata('lang') . '.mo' ;
						$this->load_textdomain(APPPATH . 'modules/'.$module.'/locale/' . $this->obj->session->userdata('lang') . '.mo', $module );
					}
				}	
			}
		}	
	}
*/	
	
	function get_active()
	{
		$this->obj->db->where('active', 1);
		$this->obj->db->order_by('ordering');
		$query = $this->obj->db->get($this->table);
		
		$languages = array();
		
		if ( $query->num_rows() > 0 )
		{
			$languages = $query->result_array();
		}
		
		return $languages;
	}	
	
	function get_list()
	{
		$this->obj->db->order_by('ordering');
		$query = $this->obj->db->get($this->table);
		
		$languages = array();
		
		if ( $query->num_rows() > 0 )
		{
			$languages = $query->result_array();
		}
		
		return $languages;
	}

	function get_default()
	{
		$this->obj->db->select('code');
		$this->obj->db->where('default', 1);
		$this->obj->db->limit(1);
		$query = $this->obj->db->get($this->table);
		if ($query->num_rows() == 1)
		{
			$row = $query->row();
			return $row->code ;
		}
		elseif (strlen( $this->obj->config->item('language') ) == 2 )
		{
			return $this->obj->config->item('language');
		}
		else
		{
			return 'en';
		}
		
	}
	function get_codes()
	{
		$this->obj->db->select('code');
		$this->obj->db->where('active', 1);
		$this->obj->db->order_by('ordering');
		$query = $this->obj->db->get($this->table);
		$codes = array();
		
		if ( $query->num_rows() > 0 )
		{
			foreach ( $query->result() as $row )
			{
				$codes[] = $row->code;
			}
		}
		return $codes;
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
		
		
		include_once(APPPATH . 'libraries/gettext' . EXT);
		include_once(APPPATH . 'libraries/streams' . EXT);
		
		if (isset($this->_l10n[$domain]))
			
			return;

		if ( is_readable($mofile)) {
			
			$input = new CachedFileReader($mofile);
			
		}	
		else
		{
			return;
		}
		//echo $domain;
		$this->_l10n[$domain] = new gettext_reader($input);
	}
}

?>