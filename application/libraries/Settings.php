<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @version $Id$
 * @package solaitra
 * @copyright Copyright (C) 2005 - 2008 Tsiky dia Ampy. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 */

	class Settings {
		var $version ;
		var $revision;
		var $modules;
		var $obj;
		
		function Settings()
		{
			$this->obj =& get_instance();
			if ($this->obj->uri->segment(1) == "install")
			{
				return;
			}
			$this->obj->load->database();
			$this->get_settings();
			$this->find_modules();
			//$this->propagate_values();
			$this->load_libraries();
			$this->load_locales();
		}
		
		function propagate_values()
		{
			foreach ($this->obj->system as $key => $value)
			{
				$this->{$key} = $value;
			}
		}

		function load_libraries()
		{
			$this->obj->load->library('javascripts');
			$this->obj->load->library('block');
			$this->obj->load->library('plugin');
			$this->obj->load->library('session');
			$this->obj->load->library('user');
			$this->obj->load->library('layout');
			$this->obj->load->library('navigation');
		
		}

		
		function load_locales()
		{
			$this->obj->load->library('locale');
			//overall locale
			//$this->obj->locale->load_textdomain(APPPATH . 'locale/' . $this->obj->session->userdata('lang') . '.mo');
			
			
			foreach ($this->obj->system->modules as $module)
			{
				$mofile = APPPATH . 'modules/'.$module['name'].'/locale/' . $this->obj->session->userdata('lang') . '.mo' ;
				if ( file_exists($mofile)) 
				{
					$this->obj->locale->load_textdomain($mofile, $module['name']);
				}
			}
		}
		
		
		function find_modules()
		{
			$this->obj->config->set_item('cache_path', './cache/');
			$dir = $this->obj->config->item('cache_path');
			$this->obj->load->library('cache', array('dir' => $dir));
			
			
			if ( !$modules = $this->obj->cache->get('modulelist', 'system') )
			{
				$this->obj->db->where('status', 1);
				$this->obj->db->order_by('ordering');
				$query = $this->obj->db->get('modules');
				foreach ($query->result_array() as $row)
				{
					$modules[ $row['name'] ] = $row;
				}
				$this->obj->cache->save('modulelist', $modules, 'system', 0);
			}
			
			$this->obj->system->modules = $modules;
		}
		
		
		function get_settings()
		{
			if(is_file('settings.php'))
			{
				$query = $this->obj->db->query("SHOW TABLE STATUS LIKE '" . $this->obj->db->dbprefix('sessions') . "' ");
				if($query->num_rows() == 0)
				{
					redirect('install');
					exit;
				}
				
				
				$settings = unserialize(file_get_contents('settings.php'));
				
				foreach ($settings as $key => $value)
				{
				  $this->obj->system->{$key} = $value;
				}
				
			}
			else
			{
				redirect('install');
				exit();
			}
		}
		
		function set($name, $value)
		{	
			//update only if changed
			if (!isset($this->$name) || $this->$name != $value) {
			
				$this->obj->system->{$name} = $value;
				
				$fp = @fopen('settings.php', 'wb');
				fwrite($fp, serialize($this->obj->system));
				fclose($fp);
				
			}
		}
		
		function clear_cache()
		{
			$dir = $this->obj->config->item('cache_path');
			
			$handle = opendir($dir);

			if ($handle)
			{
				while ( false !== ($cache_file = readdir($handle)) )
				{
					// make sure we don't delete silly dirs like .svn, or . or ..
					
					if ($cache_file != 'index.html' && substr($cache_file, 0, 1) != "." && !is_dir($dir.$cache_file))
					{
						@unlink($dir.'/'.$cache_file);
					}
				}
			}
		}
	}


?>