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


	function get_users($where = array(), $params = array())
	{
	
		$default_params = array
		(
			'order_by' => 'title',
			'limit' => 5,
			'start' => null,
			'limit' => null
		);
		
		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
	
		$this->db->order_by($params['order_by']);
		$this->db->limit($params['limit'], $params['start']);
	
		if (!is_array($where))
		{
			$where = array('id', $where);
		}

		$query = $this->db->or_like($where);
		$query = $this->db->get('users');
		if ($query->num_rows() > 0 )
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}	
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get specific user
	 *
	 * @access	public
	 * @param	string	username
	 * @return	mixed	user data
	 */
	function get_user($username)
	{
		$query = $this->db->get_where('users', array('username' => $username), 1, 0);
		
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
		$query = $this->db->get_where('users', $fields, 1, 0);
		
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