<?php

	class System {
		var $version ;
		var $revision;
		
		function System()
		{
			$this->obj =& get_instance();
			$this->obj->config->set_item('cache_path', APPPATH . 'cache');
			$dir = $this->obj->config->item('cache_path');
			$this->obj->load->library('cache', array('dir' => $dir));
			$this->get_version();
			$this->get_settings();
			$this->start();
		}
		
		
		function get_version()
		{
			$this->version = @file_get_contents(APPPATH . "version.txt");
			/*
			if ( $revision = @file_get_contents("http://ci-cms.googlecode.com/svn/") )
			{
				if ( ereg ("<title>(.*)</title>", $revision, $contents)) 
				{
					$this->latest_revision = $contents[1];
				}
			}*/
		}
		
		function start()
		{
			if ($this->cache && !$this->obj->user->logged_in && $this->obj->uri->segment(1) != 'admin')
			{
				$this->obj->output->cache($this->cache_time);
			}
		}
		
		function get_settings()
		{
			$query = $this->obj->db->get('settings');
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
			   {
			      $this->{$row->name} = $row->value;
			   }
			}			
		}
		
		function set($name, $value)
		{	
			//update only if changed
			if (!isset($this->$name)) {
				$this->$name = $value;
				$this->obj->db->insert('settings', array('name' => $name, 'value' => $value));
			}
			elseif ($this->$name != $value) 
			{
				$this->$name = $value;
				$this->obj->db->update('settings', array('value' => $value), "name = '$name'");
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
						unlink($dir.$cache_file);
					}
				}
			}
		}
	}


?>