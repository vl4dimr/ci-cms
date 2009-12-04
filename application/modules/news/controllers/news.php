<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class News extends Controller {
		var $settings = array();
		function News()
		{
			parent::Controller();
			//$this->output->enable_profiler(true);
			$this->template['module'] = "news";

			$this->settings = isset($this->system->news_settings) ? unserialize($this->system->news_settings) : array();

			$this->load->model('news_model', 'news');
			//$this->lang = $this->session->userdata('lang');
		}
		
		function comment()
		{
			//settings
			$news = $this->news->get_news($this->input->post('uri'));
			
			$this->plugin->do_action('news_save_comment');
			
			$fields = array('news_id', 'author', 'email', 'website', 'body');
			$data = array();
			$data['ip'] = $this->input->ip_address();
			foreach ($fields as $field)
			{
				$data[$field] = $this->input->post($field);
			}
			
			if ($this->settings['approve_comments'])
			{
				$data['status'] = 1;
				if ($news['notify'] == 1 && $news['email'])
				{
					$this->load->library('email');

					$this->email->from($news['email'], $this->system->site_name );
					$this->email->to($news['email']);

					$this->email->subject('[' . $this->system->site_name . '] '. __("Comment Notification", $this->template['module']));
					
					$smsg = __("
Hello,

A new comment has been sent to the news
%s


If you don't want to receive other notification, go to
%s

and disable notification.
");
					$msg = sprintf($smsg, 
							site_url('news/' . $news['uri']),
							site_url('admin/news/create/' . $news['id'])
						);
						
					$this->email->message($msg);

					$this->email->send();
					
					//notify admin
				
				}

				if (isset($this->settings['notify_admin']) && $this->settings['notify_admin'] === true)
				{
					$this->load->library('email');

					$this->email->from($news['email'], $this->system->site_name );
					$this->email->to($news['email']);

					$this->email->subject('[' . $this->system->site_name . '] '. __("Comment Notification", $this->template['module']));
					$msg = __("
Hello,

A new comment has been sent to the news
%s


If you don't want to receive other notification, go to
%s

and disable notification.
", "news");
					$msg = sprintf($msg,
							site_url('news/' . $news['uri']),
							site_url('admin/news/settings#two')
						);
					$this->email->to($this->system->admin_email);
					$this->email->message($msg);
					$this->email->send();
				}
				
			}
			else
			{
				
				if ($news['email'] != '')
				{
					$this->load->library('email');

					$this->email->from($news['email'], $this->system->site_name );
					$this->email->to($news['email']);

					$this->email->subject('[' . $this->system->site_name . '] '. __("Comment to approve", $this->template['module']));
					
					$msg = __("
Hello,

A new comment has been sent to the news
%s
To approve it click the link below 
%s

If you don't want to receive other notification, go to
%s

and set to approve comments automatically.
", "news");
					$msg = sprintf($msg, 
							site_url('news/' . $news['uri']),
							site_url('admin/news/comments/approve/' . $news['id']),
							site_url('admin/news/settings#two')
						);
						
					$this->email->message($msg);

					$this->email->send();

				}
				
				$this->session->set_flashdata('notification', __("Thank you for your comment. In this site, the comments need to be approved by the administrator. Once approved, you will see it listed here.", $this->template['module']));
			}
			
			$data = $this->plugin->apply_filters('comment_filter', $data);
			
			
			$this->db->insert('news_comments', $data);
			redirect('news/' . $this->input->post('uri'), 'refresh');
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
					
					//debug
					
					//hits
					if ($this->session->userdata('news'.$news['id']) != $news['id'])
					{
						$this->session->set_userdata('news'.$news['id'], $news['id']);
						$this->db->where('id', $news['id']);
						$this->db->set('hit', 'hit+1', FALSE);
						
						$this->db->update('news');
						$this->cache->remove('news'.$this->user->lang, 'news');
					}
					
					//var_dump($rows->result_array());
					
					$this->template['title'] = $news['title'];
					$this->template['news'] = $news;
					$this->layout->load($this->template, 'read');
				
				}
				else
				{
					$this->template['title'] = __("No news found", $this->template['module']);
					$this->layout->load($this->template, '404');
				}
			}
		}
		
		
		function index($start = 0)
		{
			$per_page = 20;
			
			$params['limit'] = $per_page;
			$params['start'] = $start;
			$params['where'] = array('news.lang' => $this->user->lang);
			
			
			$this->load->library('pagination');
			
			$config['uri_segment'] = 3;
			$config['first_link'] = __('First', 'news');
			$config['last_link'] = __('Last', 'news');
			$config['base_url'] = site_url('news/list');
			$config['total_rows'] = $this->news->get_total_published($params);
			$config['per_page'] = $per_page; 	
			$this->pagination->initialize($config); 

			$this->template['rows'] = $this->news->get_list($params);
			$this->template['start'] = $start;
			$this->template['total_rows'] = $config['total_rows'];
			$this->template['pager'] = $this->pagination->create_links();		

			$this->template['title'] = __("All news", $this->template['module']);
			
			$this->layout->load($this->template, 'index');
		}
		
		function cat ($uri = null, $start = 0)
		{
			
			
			$per_page = 20;
			
			$params['limit'] = $per_page;
			$params['start'] = $start;
			if(is_null($uri))
			{
				$params['where'] = array('news.lang' => $this->user->lang, 'cat' => 0);
				$this->template['category'] = array('title' => __("No category", "news"));
				$config['uri_segment'] = 3;
				$config['base_url'] = site_url('news/cat');
			}
			else
			{
				$cat = $this->news->get_cat(array('uri' => $uri, 'lang' => $this->user->lang));

				$params['where'] = array('news.lang' => $this->user->lang, 'cat' => $cat['id']);
				$this->template['category'] = $cat;
				$config['uri_segment'] = 4;
				$config['base_url'] = site_url('news/cat/' . $uri);
			}
			
			
			$this->load->library('pagination');
			
			$config['first_link'] = __('First', 'news');
			$config['last_link'] = __('Last', 'news');
			$config['total_rows'] = $this->news->get_total_published($params);
			$config['per_page'] = $per_page; 	
			$this->pagination->initialize($config); 

			$this->template['rows'] = $this->news->get_list($params);

			$this->template['start'] = $start;
			$this->template['total_rows'] = $config['total_rows'];
			$this->template['pager'] = $this->pagination->create_links();
			if(isset($cat))
			{
				$this->template['title'] = sprintf(__("News in %s", $this->template['module']), $cat['title']);
			}
			else
			{
				$this->template['title'] = __("Other news", $this->template['module']);			
			}

			
			$this->layout->load($this->template, 'index');
			
		}
	
		function tag ($tag = null, $start = 0)
		{
			
			
			$per_page = 20;
			
			
			
			if(is_null($tag))
			{
				$this->template['title'] = __("Tag list", "news");
				$this->template['rows'] = $this->news->get_tags();
				$this->layout->load($this->template, 'tag_list');
				return;
			}
			else
			{
				$this->template['title'] = sprintf(__("News tagged with %s ", "news"), $tag);
				$per_page = 20;
				
				$params['limit'] = $per_page;
				$params['start'] = $start;
				$params['where'] = array('news.lang' => $this->user->lang);
				$params['where']['news_tags.uri'] = $tag;
				$params['order_by'] = 'news.id DESC';
				
				
				$this->load->library('pagination');
				
				$config['uri_segment'] = 4;
				$config['first_link'] = __('First', 'news');
				$config['last_link'] = __('Last', 'news');
				$config['base_url'] = site_url('news/tag/' . $tag);
				$config['total_rows'] = $this->news->get_total_news_by_tag($params);
				$config['per_page'] = $per_page; 	
				$this->pagination->initialize($config); 

				$this->template['rows'] = $this->news->get_news_by_tag($params);
				$this->template['start'] = $start;
				$this->template['total_rows'] = $config['total_rows'];
				$this->template['pager'] = $this->pagination->create_links();		
			
				$this->layout->load($this->template, 'index');
			}
		}		
	
	
	
	}

?>