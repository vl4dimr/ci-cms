<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


class Member_model extends Model {
	
	var $member_total;
    function Member_model()
    {
        parent::Model();
		$this->member_total = $this->get_total();
    }

	function get_total()
	{
		$this->db->select('count(id) as nb');
		$query = $this->db->get('users');
		$row = $query->row();
		return $row->nb;
	}
		

	function get_list()
	{
		$query = $this->db->get('users');
		return $query->result_array();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get specific user
	 *
	 * @access	public
	 * @param	integer	user id
	 * @return	mixed	user data
	 */
	function get_user($id)
	{
		$query = $this->db->select('username, id, email, joined, post_count, timezone');
		$query = $this->db->getwhere('users', array('id' => $id), 1, 0);
		
		if($query->num_rows() == 1)
			return $query->row_array();
		else
			return FALSE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Checks if a user exists
	 *
	 * @access	public
	 * @param	mixed	search criteria
	 * @return	bool
	 */
	function exists($fields)
	{
		$query = $this->db->getwhere('users', $fields, 1, 0);
		
		if($query->num_rows() == 1)
			return TRUE;
		else
			return FALSE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Create a new user
	 *
	 * @access	public
	 * @param	string	username
	 * @param	string	email address
	 * @param	string	password
	 * @param	mixed	parameters
	 * @return	void
	 */
	function create($username, $email, $password, $timezone = 'UP1', $role = 5) {				
		$data = array(
			'username' 	=> $username,
			'email'		=> $email,
			'password'	=> $password,
			'role' 		=> $role,
			'joined'	=> time(),
			'timezone'	=> $timezone,
			'post_count'=> 0
		);		
		$this->db->insert('users', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Increment user post count
	 *
	 * @access	public
	 * @param	integer	user id
	 * @return	void
	 */
	function add_post($id)
	{
		$this->db->where('id', $id);
		$this->db->set('post_count','post_count+1', FALSE);
		$this->db->update('users');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Decrement user post count
	 *
	 * @access	public
	 * @param	integer	user id
	 * @return	void
	 */
	function remove_post($id)
	{
		$this->db->where('id', $id);
		$this->db->set('post_count','post_count-1', FALSE);
		$this->db->update('users');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Change user information
	 *
	 * @access	public
	 * @param	mixed	changed data
	 * @return	void
	 */
	function change($data)
	{
		$this->db->where('id', $this->_user['id']);
		$this->db->update('users', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Delete a user
	 *
	 * @access	public
	 * @param	integer	user id
	 * @return	void
	 */
	function delete($id)
	{
		$this->db->delete('users', array('id' => $id));
	}
}