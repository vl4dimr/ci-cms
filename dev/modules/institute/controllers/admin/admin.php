<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Controller {

	function Admin()
	{
		parent::Controller();
	
		$this->load->library('administration');
		$this->template['module']	= 'guestbook';
		$this->template['admin'] = true;
		$this->load->model('guestbook_model', 'gbook');
		$this->load->helper('smiley');
	}

	function index()
	{
		//just go to list
		$this->template['title'] = __("Guestbook messages", "guestbook");
		$this->results();
	}
	
	function delete($id = null, $confirm = null)
	{
		$this->user->check_level('guestbook', LEVEL_DEL);
		$this->template['title'] = __("Delete message", "guestbook");
		$params = array('where' => array('id' => $id));

		if(is_null($confirm))
		{
			$this->template['row'] = $this->gbook->get($params);
			$this->layout->load($this->template, "admin/delete");
			return;
		}
		else
		{
			$params = array('where' => array('id' => $id));
			$this->gbook->delete($params);
			$this->session->set_flashdata('notification', __("Message deleted successfully", "guestbook"));
			redirect('admin/guestbook');
			return;
		}
		
	}
	
	function edit($id = null)
	{
		$this->user->check_level('guestbook', LEVEL_EDIT);
		if(is_null($id)) 
		{
			redirect('admin/guestbook');
		}
		$this->template['title'] = __("Modify message", "guestbook");
		$this->template['row'] = $this->gbook->get(array('where' => array('id' => $id)));
		$this->layout->load($this->template, "admin/edit");
	}
	
	function save()
	{
		$this->user->check_level('guestbook', LEVEL_EDIT);
		foreach($this->gbook->fields['guestbook_posts'] as $key => $val)
		{
			if ($this->input->post($key) === false)
			{
				$data[$key] = $val;
			}
			else
			{
				$data[$key] = $this->input->post($key);
			}
		}
		//verification should go here
		
		$passed = true;
		if(!$data['g_msg']) $passed = false;
		
		if(!$passed)
		{
			$this->template['title'] = __("Error", "guestbook");
			$this->template['message'] = __("There was en error, please fill all fields", "guestbook");
			
			$this->layout->load($this->template, 'error');
			return;
		}
		
		$this->gbook->update(array('id' => $data['id']), $data);
		$this->session->set_flashdata('notification', __("Thank you, your message is registered", "guestbook"));
		redirect('admin/guestbook');
	}
	
	
	function results($search_id = 0, $start = 0)
	{
		$params = array();

		//sorting

		if ($search_id !== 0 && $tmp = $this->gbook->get_params($search_id))
		{
			$params = unserialize( $tmp);

		}


		$per_page = 20;
		$params['start'] = $start;

		$params['limit'] = $per_page;

		$this->template['rows'] = $this->gbook->get_list($params);
		//echo $this->db->last_query();

		if(!isset($this->template['title'])) $this->template['title'] = __("Search result", "guestbook");
		$config['first_link'] = __('First', 'guestbook');
		$config['last_link'] = __('Last', 'guestbook');
		$config['total_rows'] = $this->gbook->get_total($params);
		$config['per_page'] = $per_page;
		$config['base_url'] = base_url() . 'admin/guestbook/results/' . $search_id;
		$config['uri_segment'] = 5;
		$config['num_links'] = 20;
		$this->load->library('pagination');

		$this->pagination->initialize($config);

		$this->template['pager'] = $this->pagination->create_links();
		$this->template['start'] = $start;
		$this->template['total'] = $config['total_rows'];
		$this->template['per_page'] = $config['per_page'];
		$this->template['total_rows'] = $config['total_rows'];

		$this->layout->load($this->template, 'admin/index');
	
	}
}