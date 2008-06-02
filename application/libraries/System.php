<?php

	class System {
		
		function System()
		{
			$this->obj =& get_instance();
			
			$this->get_settings();
			$this->start();
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