<?php 

class Update extends Controller 
{
	var $_settings = array();
	function Update()
	{
		parent::Controller();
		$this->load->database();
		$this->_get_settings();
	}
	
	function _get_settings()
	{
		$query = $this->db->get("settings");
		$rows = $query->result_array();
		foreach($rows as $row)
		{
			$this->_settings[ $row['name'] ] = $row['value'];
		}
	}

	function _set($name, $value)
	{	
		//update only if changed
		if (!isset($this->_settings[$name])) {
			$this->_settings[$name] = $value;
			$this->db->insert('settings', array('name' => $name, 'value' => $value));
		}
		elseif ($this->_settings[$name] != $value) 
		{
			$this->_settings[$name] = $value;
			$this->db->update('settings', array('value' => $value), "name = '$name'");
		}
	}
	
	
	function index()
	{
		$this->load->helper('file');
		$new_version = read_file('./application/version.txt');
		$old_version = $this->_settings['version'];
		
		if($old_version >= $new_version)
		{
			echo "<p>You have already the latest version. You cannot upgrade anymore.</p>";
			echo "<p>Go to " . anchor('admin/module') . " to update the modules.</p>";
			exit();
		}
		
		//update starts only from 0.9.0.0
		
		
		
		
		//start upgrade
		$to_version = "0.9.1.0";
		if($old_version <= $to_version)
		{
				$query = $this->db->query("SHOW COLUMNS FROM " . $this->db->dbprefix('users') . " LIKE 'online'");
				if($query->num_rows() == 0)
				{
					$this->db->query("ALTER TABLE " . $this->db->dbprefix('users') . " ADD `online` INT( 1 ) NOT NULL DEFAULT 0") ;
					echo "<p>User table updated</p>";
					echo "<p>Now go to " . anchor('admin/module', 'admin/module') . " to update the modules.</p>";
				}
			
			$this->_set('version', $to_version);
		}
		
		$to_version = "0.9.2.0";
		if($old_version <= $to_version)
		{
				$query = $this->db->query("SHOW COLUMNS FROM " . $this->db->dbprefix('navigation') . " LIKE 'g_id'");
				if($query->num_rows() == 0)
				{
					$this->db->query("ALTER TABLE " . $this->db->dbprefix('navigation') . " ADD `g_id` VARCHAR( 20 ) NOT NULL DEFAULT '0'") ;
					echo "<p>Navigation table updated</p>";
				}
				$query = $this->db->query("SHOW COLUMNS FROM " . $this->db->dbprefix('pages') . " LIKE 'g_id'");
				if($query->num_rows() == 0)
				{
					$this->db->query("ALTER TABLE " . $this->db->dbprefix('pages') . " ADD `g_id` VARCHAR( 20 ) NOT NULL DEFAULT '0'") ;
					echo "<p>Page table updated</p>";
					echo "<p>Now go to " . anchor('admin/module', 'admin/module') . " to update the modules.</p>";
				}
			
			$this->_set('version', $to_version);
		}
		
		$to_version = "0.9.2.1";
		if($old_version <= $to_version)
		{
			$this->db->query("ALTER TABLE " . $this->db->dbprefix('navigation') . " CHANGE  `g_id`  `g_id` VARCHAR( 20 ) NOT NULL DEFAULT  '0'") ;
			echo "<p>Navigation table updated</p>";
			echo "<p>Now go to " . anchor('admin/module', 'admin/module') . " to update the modules.</p>";
			
			$this->_set('version', $to_version);
		}

		$to_version = "0.9.2.2";
		if($old_version <= $to_version)
		{
			$this->db->query("ALTER TABLE " . $this->db->dbprefix('group_members') . " ADD `g_level` INT( 1 ) NOT NULL DEFAULT  0") ;
			echo "<p>Member level inosde table updated</p>";
			echo "<p>Now go to " . anchor('admin/module', 'admin/module') . " to update the modules.</p>";
			
			$this->_set('version', $to_version);
		}

	}

}
