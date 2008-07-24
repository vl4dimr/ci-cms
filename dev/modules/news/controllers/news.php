<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class News extends Controller {
		var $settings = array();
		function News()
		{
			parent::Controller();
			//$this->output->enable_profiler(true);
			$this->template['module'] = "news";

			$this->allow_comments = isset($this->settings->allowed_file_type) ? $this->settings->allowed_file_type : 'gif|jpg|png|bmp|doc|docx|xls|mp3|swf|exe|pdf|wav';
			$this->settings = isset($this->system->news_settings) ? unserialize($this->system->news_settings) : array();

			$this->load->model('news_model', 'news');
			$this->lang = $this->session->userdata('lang');
		}
		
		function comment()
		{
			//settings
			
			$fields = array('news_id', 'author', 'email', 'website', 'body');
			$data = array();
			foreach ($fields as $field)
			{
				$data[$field] = $this->input->post($field);
			}
			
			
			$this->db->insert('news_comments', $data);
			redirect('news/' . $this->input->post('uri'));
		}
	
		function read($uri = null)
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
					$this->template['comments'] = $this->news->get_comments($news['id']);
					if ($news['allow_comments'] == 1)
					{
						//generate captcha
			
						$pool = '0123456789';

						$str = '';
						for ($i = 0; $i < 6; $i++)
						{
							$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
						}
						
						$word = $str;
			
			
						$this->load->plugin('captcha');
						$vals = array(
							'img_path'	 => './media/captcha/',
							'img_url'	 => site_url('media/captcha'). '/',
							'font_path'	 => APPPATH . 'modules/news/fonts/Fatboy_Slim.ttf',
							'img_width'	 => 150,
							'img_height' => 30,
							'expiration' => 1800,
							'word' => $word
						);
		
						$cap = create_captcha($vals);
						
						$data = array(
							'captcha_id'	=> '',
							'captcha_time'	=> $cap['time'],
							'ip_address'	=> $this->input->ip_address(),
							'word'			=> $cap['word']
						);

						$this->db->insert('captcha', $data);
						
						
						$this->template['captcha'] = $cap['image'];
					
					}
					
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