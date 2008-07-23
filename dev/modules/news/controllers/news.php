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
		
		function comment()
		{
			echo "comment";
		}
	
		function read($uri = null, $start = null)
		{
			
			if (is_null($uri))
			{
				redirect('news/list');
			}
			else
			{
				if($news = $this->news->get_news($uri))
				{
					//pagination for comments
					$limit = 20;
					$this->template['comments'] = $this->news->get_comments($news['id'], $limit, $start);
				

					$this->load->library('pagination');
					
					$config['uri_segment'] = 3;
					$config['first_link'] = __('First');
					$config['last_link'] = __('Last');
					$config['base_url'] = site_url('news/'. $news['uri'] . '/');
					$config['total_rows'] = $this->news->count_comments($news['id']);
					$config['per_page'] = $limit; 	
					$this->pagination->initialize($config); 

					$this->template['pager'] = $this->pagination->create_links();		
					
					
					$this->template['title'] = $news['title'];
					$this->template['news'] = $news;
					$this->layout->load($this->template, 'read');
				
				}
				else
				{
					$this->template['title'] = __("No news found");
					$this->layout->load($this->template, '404');
				}
			}
		}
		
		
		function index($start = 0)
		{
			$per_page = 20;
			$this->db->order_by('id DESC');
			$query = $this->db->get('news', $per_page, $start);
			
			$this->template['rows'] = $query->result_array();
			

			$this->load->library('pagination');
			
			$config['uri_segment'] = 3;
			$config['first_link'] = __('First');
			$config['last_link'] = __('Last');
			$config['base_url'] = site_url('news/index/');
			$config['total_rows'] = $this->db->count_all('news');
			$config['per_page'] = $per_page; 	
			$this->pagination->initialize($config); 

			$this->template['pager'] = $this->pagination->create_links();		
			
			$this->layout->load($this->template, 'index');
		}
	}

?>