<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Institute_model extends Model {

	var $fields = array();
	var $data = array();
	function Institute_model()
	{
		parent::Model();
		$this->fields = array(
			'institute_profiles' => array(
			  'p_id' =>'',
			  'p_username' => '',
			  'p_name' =>'',
			  'p_address' =>'',
			  'p_city' =>'',
			  'p_state' =>'',
			  'p_zip' =>'',
			  'p_country' =>'',
			  'p_phone' =>'',
			  'p_date' => time()
			),
			'institute_registrations' => array(
				'id' => 0,
				'student_id' => '',
				'class_id' => '',
				'reg_date' => time()
			),
			'institute_classes' => array(
				'id' => 0,
				'c_id' => '',
				'c_parent' => '',
				'c_name' => '',
				'c_description' => ''
			)
				
		);

	}

	function get_profile($username = null)
	{
		if(is_null($username)) $username = $this->user->username;
		
		if(empty($this->data['get_profile' . $username]))
		{
			$this->db->where = array('p_username' => $username);
			$query = $this->db->get('institute_profiles');
			if($query->num_rows() > 0)
			{
				$this->data['get_profile' . $username] = $query->row_array();
			}
			else
			{
				$this->data['get_profile' . $username] = false;
			}
		}
		
		return $this->data['get_profile' . $username];
	}
	
	function save_profile($data, $username = null)
	{
		if(is_null($username)) $username = $this->user->username;
		
		$this->db->query("INSERT INTO " . $this->db->dbprefix('institute_profiles') . " 
		(p_id, p_username, p_name, p_address, p_city, p_state, p_zip, p_country, p_phone, p_date) VALUES (" . $this->db->escape($data['p_id']) . ", '" . $username . "', " . $this->db->escape($data['p_name']) . ", " . $this->db->escape($data['p_address']) . ", " . $this->db->escape($data['p_city']) . ", " . $this->db->escape($data['p_state']) . ", " . $this->db->escape($data['p_zip']) . ", " . $this->db->escape($data['p_country']) . ", " . $this->db->escape($data['p_phone']) . ", " . time() . ") ON DUPLICATE KEY UPDATE p_name = " . $this->db->escape($data['p_name']) . ",  p_address = " . $this->db->escape($data['p_address']) . ",  p_city = " . $this->db->escape($data['p_city']) . ",  p_state = " . $this->db->escape($data['p_state']) . ",  p_zip = " . $this->db->escape($data['p_zip']) . ",  p_country = " . $this->db->escape($data['p_country']) . ",  p_phone = " . $this->db->escape($data['p_phone']) . ", p_date = " . time())	;
	}
	
	function get_classes($username = null)
	{
		if(is_null($username)) $username = $this->user->username;
		
		if(empty($this->data['get_classes' . $username]))
		{
			$query = $this->db->query(
			"SELECT r.*, c.c_name, c.c_id, p.p_name, u.username
			FROM " . $this->db->dbprefix('institute_registrations') . " r
			LEFT JOIN " . $this->db->dbprefix('institute_classes') . " c ON r.class_id=c.c_id 
			LEFT JOIN " . $this->db->dbprefix('institute_profiles') . " p ON r.student_id=p.p_id
			LEFT JOIN ". $this->db->dbprefix('users') . " u ON p.p_username=u.username 
			WHERE u.username='" . $username . "' ORDER BY c.c_name"			
			);
			
			if($query->num_rows() > 0)
			{
				$this->data['get_classes' . $username] = $query->result_array();
			}
			else
			{
				$this->data['get_classes' . $username] = false;
			}
		}
		
		return $this->data['get_classes' . $username];
	}
	
}
