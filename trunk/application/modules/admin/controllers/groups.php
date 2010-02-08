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
		
		$params = array('limit' => 20, 'start' => $start);
		if ($rows = $this->group->get_list($params))
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
	
	function members()
	{
		$this->user->check_level($this->template['module'], LEVEL_VIEW);
		//group members
		
	}

	function create()
	{
		$this->user->check_level($this->template['module'], LEVEL_ADD);

		$this->template['title'] = __("Create a new group", "admin");
		$this->layout->load($this->template, 'groups/create');
	
	}
	
	function edit()
	{
		$this->user->check_level("admin", LEVEL_EDIT);
		$this->template['title'] = __("Fanitsiana fanontaniana");
		$this->template['start'] = $start;
		$this->template['row'] = $this->group->get(array('where' => array('id' => $id)));
		$this->layout->load($this->template, 'lalao/quiz/index');			
	
	}

	function delete()
	{
		$this->user->check_level("admin", LEVEL_DEL);
		$this->group->delete(array('where' => array('id' => $id)));
			$this->session->set_flashdata('notification', __("Voafafa ny fanontaniana", "admin"));
		redirect('karajia/lalao/quiz/lisitra/' . $start, 'refresh');
	}
	
	function save()
	{
		$data['fanontaniana'] = $this->input->post('fanontaniana');
		$data['valiny'] = $this->input->post('valiny');
		$data['username'] = $this->user->username;
		$data['valid'] = 'N';
		$data['categorie'] = $this->input->post;
		
		if($data['fanontaniana'] != '' && $data['valiny'] != '')
		{
			if($this->input->post('id') && $this->user->level["admin"] >= LEVEL_EDIT)
			{
				$this->group->update(array('id' => $this->input->post('id')), $data);
			}
			else
			{
				$this->group->save($data);
			}
			$this->session->set_flashdata('notification', sprintf(__("Tafiditra soa amantsara ny fanontaniana. Mbola jeren'ny mpandrindra izany dia hampidirina ao amin'ny #lalao, mampidira hafa indray na %sJereo eto%s ny lisitra", "admin"), "<a href='" . site_url('karajia/lalao/quiz/lisitra') . "'>", "</a>"));
			redirect('karajia/lalao/quiz/lisitra/' . $start, 'refresh');
			return;
		}
		else
		{
			$this->template['title'] = __("Hadisoana", "admin");
			$this->template['message'] = __("Ilaina ny fanontaniana sy valiny, avereno indray", "admin");
			$this->layout->load($this->template, 'error');
			return;
		}
	
	}
	
}