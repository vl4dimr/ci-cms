<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* $Id$
**/
	
class Admin extends Controller {
	
	
	function Admin()
	{

		parent::Controller();
	

		$this->load->library('administration');
		$this->template['module'] = "tags";
		$this->load->model('tags_model', 'tags');

		$this->template['admin']		= true;
	}
	
	
	function index($start = null)
	{
		$limit = 20;
		
		
		$this->user->check_level($this->template['module'], LEVEL_VIEW);
		
		$rows = $this->tags->get_taglist(array('lang' => $this->user->lang), array('start' => $start, 'limit' => $limit, 'order_by' => 'tag'));
		$this->load->library('pagination');
		$config['uri_segment'] = 4;
		$config['first_link'] = __('First', $this->template['module']);
		$config['last_link'] = __('Last', $this->template['module']);
		$config['base_url'] = base_url() . 'admin/tags/index';
		$config['total_rows'] = $this->tags->get_totaltags();
		$config['per_page'] = $limit; 

		$this->pagination->initialize($config); 

		$this->template['pager'] = $this->pagination->create_links();		
		
		$this->template['rows'] = $rows;
		$this->layout->load($this->template, 'admin/tags');

	}
	
	function save()
	{
		$this->user->check_level($this->template['module'], LEVEL_EDIT);		
		$setting = is_array($this->input->post('settings')) ? serialize($this->input->post('settings')) : '';
		$this->system->set('palbum_settings', $setting);
		$this->session->set_flashdata('notification', __("Settings saved", $this->template['module']));
		redirect('admin/palbum');
	}

	function delete($tag, $js = 0)
	{
		$this->user->check_level($this->template['module'], LEVEL_DEL);
		//cannot delete if contains files or categories
		
		if ( $js > 0 )
		{
			$this->tags->delete(array('tag' => $tag, 'lang' => $this->user->lang));
			
			$this->session->set_flashdata('notification', __('The tag has been deleted from all elements.'));

			redirect('admin/tags');
		}
		else
		{
			$this->template['tag'] = $tag;

			$this->layout->load($this->template, 'admin/delete');
		}
	}

}

?>