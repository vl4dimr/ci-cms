<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
class Groups extends Controller {
	
	function Groups()
	{

		parent::Controller();
		//$this->output->enable_profiler(true);
		$this->load->library('administration');
		
		$this->template['module'] = "admin";
		$this->load->model('group_model', 'group');
		$this->user->check_level($this->template['module'], LEVEL_EDIT);
	}
	
	function index($start = 0, $id = null)
	{
		//group list
		$this->template['title'] = __("Group list", "admin");
		
		$params = array('limit' => 20, 'start' => $start, 'where' => $this->db->dbprefix("groups") . ".g_id <> '0' AND " . $this->db->dbprefix("groups") . ".g_id <> '1'");
		$rows = $this->group->get_list($params);
		$this->load->library('pagination');
		$config['uri_segment'] = 5;
		$config['first_link'] = __('First', "admin");
		$config['last_link'] = __('Last', "admin");
		$config['base_url'] = base_url() . 'admin/groups/index'  ;
		$config['total_rows'] = $this->group->get_total($params);
		$config['per_page'] = 20;

		$this->pagination->initialize($config);

		$this->template['rows'] = $rows;
		$this->template['start'] = $start;
		$this->template['pager'] = $this->pagination->create_links();
		$this->layout->load($this->template, 'groups/list');

	}
	
	function members($action = 'list', $g_id = null, $g_user = null, $confirm = null)
	{
		$this->template['g_id'] = $g_id;	
		switch($action)
		{
			case "add":
				$this->template['g_id'] = $g_id;	
				$this->template['row'] = $this->group->fields['group_members'];
				$this->user->check_level($this->template['module'], LEVEL_ADD);

				$this->template['title'] = __("Add member", "admin");
				$this->layout->load($this->template, 'groups/members_create');
			
			break;
			case "edit":
				$this->template['g_id'] = $g_id;	
				if($rows = $this->group->get_members(array('limit' => 1, 'where' => array('g_id' => $g_id, 'g_user' => $g_user))))
				{
					if($rows['members'])
					{
						$this->template['row'] = $rows['members']['0'];
					}
				}
				else
				{
					echo "Member not found";
					return;
				}
				$this->user->check_level($this->template['module'], LEVEL_ADD);

				$this->template['title'] = __("Add member", "admin");
				$this->layout->load($this->template, 'groups/members_create');
			
			break;
			case "delete":
				if(!is_null($confirm))
				{
					$this->group->delete_member(array('where' => array('g_user' => $this->input->post('g_user'))));
					redirect('admin/groups/members/list/' . $g_id);
					return;
				}
				else
				{
					$this->template['title'] = __("Remove from group", "admin");
					$this->template['g_user'] = $g_user;
					$this->layout->load($this->template, 'groups/members_delete');
					return;
				}
			break;
			case "save":
			
				$g_from = 0;
				if($this->input->post('g_from') != 0)
				{
					$g_froms = explode('/', $this->input->post('g_from'));
					$g_from = mktime(0,0,0, $g_froms['1'], $g_froms['0'], $g_froms['2']);
				}
				
				$g_to = 0;
				if($this->input->post('g_to') != 0)
				{
					$g_tos = explode('/', $this->input->post('g_to'));
					$g_to = mktime(0,0,0, $g_tos['1'], $g_tos['0'], $g_tos['2']);
				}
				$data = array(
					'g_user' => $this->input->post('g_user'),
					'g_id' => $g_id,
					'g_from' => $g_from,
					'g_to' => $g_to,
					'g_date' => mktime()
				);
				
				if($data['g_user'] != '' || is_null($g_id))
				{
					if($this->input->post('id') && $this->user->level["admin"] >= LEVEL_EDIT)
					{
						$this->group->update_member(array('id' => $this->input->post('id')), $data);
					}
					else
					{
						$this->group->save_member($data);
					}
					$this->session->set_flashdata('notification', __("Member saved", "admin"));
					redirect('admin/groups/members/list/' . $g_id);
					return;
				}
				else
				{
					$this->template['title'] = __("Error", "admin");
					$this->template['message'] = __("Username required", "admin");
					$this->layout->load($this->template, 'error');
					return;
				}
			
			
			break;
			case "list":
			default:
				$this->user->check_level($this->template['module'], LEVEL_VIEW);
				$params = array('where' => array('g_id' => $g_id));
				//group members
				$search_id = $this->group->save_params(serialize($params));
				$this->results($search_id);
			break;
		}
	}

	function results($search_id = 0, $start = 0)
	{
		$this->user->check_level($this->template['module'], LEVEL_VIEW);
		$params = array();

		//sorting

		if ($this->group->get_params($search_id))
		{
			$params = unserialize( $this->group->get_params($search_id));

		}
	
		$per_page = 20;
		$params['start'] = $start;

		$params['limit'] = $per_page;
		$this->template['rows'] = $this->group->get_members($params);
		$this->template['title'] = __("Members for:" , "admin") . " " . $this->template['rows']['g_name'];
		//echo $this->db->last_query();

		$config['first_link'] = __('First', 'admin');
		$config['last_link'] = __('Last', 'admin');
		$config['total_rows'] = $this->group->get_total_members($params);
		$config['per_page'] = $per_page;
		$config['base_url'] = base_url() . 'admin/groups/results/' . $search_id;
		$config['uri_segment'] = 5;
		$config['num_links'] = 20;
		$this->load->library('pagination');

		$this->pagination->initialize($config);

		$this->template['pager'] = $this->pagination->create_links();
		$this->template['start'] = $start;
		$this->template['total'] = $config['total_rows'];
		$this->template['per_page'] = $config['per_page'];
		$this->template['total_rows'] = $config['total_rows'];
		
		$this->layout->load($this->template, 'groups/members');
	
	}
	
	function create($start = 0)
	{
		$this->template['start'] = $start;	
		$this->template['row'] = $this->group->fields['groups'];
		$this->user->check_level($this->template['module'], LEVEL_ADD);

		$this->template['title'] = __("Create a new group", "admin");
		$this->layout->load($this->template, 'groups/create');
	
	}
	
	function edit($start = 0, $g_id = null)
	{
		
		$this->user->check_level("admin", LEVEL_EDIT);
		$this->template['title'] = __("Edit a group", "admin");
		$this->template['start'] = $start;
		$this->template['row'] = $this->group->get_members(array('where' => array('g_id' => $g_id)));
		$this->layout->load($this->template, 'groups/create');			
	
	}

	function delete($start = 0, $g_id = null)
	{
		$this->user->check_level("admin", LEVEL_DEL);
		$this->group->delete(array('where' => array('g_id' => $g_id)));
		$this->session->set_flashdata('notification', __("Group deleted", "admin"));
		redirect('admin/groups/index/' . $start, 'refresh');
	}
	
	function save($start = 0)
	{
		$data = array(
			'g_name'  => $this->input->post('g_name'),
			'g_desc' => $this->input->post('g_desc'),
			'g_date' => mktime(),
			'g_owner' => $this->input->post('g_owner')
		);
		
		if($data['g_name'] != '')
		{
			if($this->input->post('id') && $this->user->level["admin"] >= LEVEL_EDIT)
			{
				$this->group->update(array('id' => $this->input->post('id')), $data);
			}
			else
			{
				$this->group->save($data);
			}
			$this->session->set_flashdata('notification', __("Group saved", "admin"));
			redirect('admin/groups/index/' . $start, 'refresh');
			return;
		}
		else
		{
			$this->template['title'] = __("Error", "admin");
			$this->template['message'] = __("Group name required", "admin");
			$this->layout->load($this->template, 'error');
			return;
		}
	
	}
	
}
