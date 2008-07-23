<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class News extends Controller {
		
		function News()
		{
			parent::Controller();
			//$this->output->enable_profiler(true);
			$this->template['module'] = "news";
			$this->load->model('news_model', 'news');
			$this->lang = $this->session->userdata('lang');
		}
		
		//all available blocks
	
		function read($uri = null)
		{
			if (is_null($uri))
			{
				echo "Error: Choose a news";
			}
			else
			{
				echo "News uri " . var_dump($uri);
			}
		}
		
		
		function index($start = null)
		{
			$per_page = 20;
			$this->db->order_by('id DESC');
			$query = $this->db->get('news', $per_page, $start);
			
			$this->template['rows'] = $query->result_array();
			

			$this->load->library('pagination');
			
			$config['uri_segment'] = 5;
			$config['first_link'] = __('First');
			$config['last_link'] = __('Last');
			$config['base_url'] = site_url('news/');
			$config['total_rows'] = $this->db->count_all('news');
			$config['per_page'] = $per_page; 	
			$this->pagination->initialize($config); 

			$this->template['pager'] = $this->pagination->create_links();		
			
			$this->layout->load($this->template, 'index');
		}
	}

?>