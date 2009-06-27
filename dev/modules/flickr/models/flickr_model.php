<?php

class Flickr_Model extends Model {
	var $tmppages;
	function Flickr_Model()
	{
		parent::Model();
		
	}
	
	function get_settings()
	{
		$query = $this->db->get('flickr_settings');
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
			$this->db->insert('flickr_settings', array('name' => $name, 'value' => $value));
		}
		elseif ($this->$name != $value) 
		{
			$this->$name = $value;
			$this->db->update('flickr_settings', array('value' => $value), "name = '$name'");
		}
	}
}		
		