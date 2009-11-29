<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Rss extends Controller {
		var $settings = array();
		function Rss()
		{
			parent::Controller();
			//$this->output->enable_profiler(true);
			$this->template['module'] = "news";

			$this->load->model('news_model', 'news');
			$this->load->helper('xml');
		}
		
		
		function index($lang = null)
		{
			$per_page = 20;
			if(is_null($lang)) $lang = $this->user->lang;
			$params['limit'] = $per_page;
			$params['where'] = array('news.lang' => $lang);
			
			if($rows = $this->news->get_list($params))
			{
				
				$this->layout->load($this->template, 'rss');
				$data['encoding'] = 'utf-8';
				$data['feed_name'] = $this->system->site_name;
				$data['feed_url'] = base_url();
				$data['page_description'] = $this->system->meta_description;
				$data['page_language'] = $lang;
				$data['creator_email'] = (isset($this->system->admin_email))? $this->system->admin_email : "";
				
				
				foreach ($rows as $key => $row)
				{
					$contents[$key]['title'] =  $row['title'];
					$contents[$key]['url'] = site_url($row['lang']  . '/news/' . $row['uri']);
					$contents[$key]['body'] = $row['body'];
					$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
					$contents[$key]['author'] = $row['author'];
					if (isset($row['image']['file']))
					{
						$contents[$key]['img'] = base_url(). 'media/images/s/' . $row['image']['file'];
					}
				}
				$data['contents'] = $contents;
				header("Content-Type: application/rss+xml");
				
				$this->load->view('rss', $data);
			}
		}
		
		function cat ($uri = null, $lang = null)
		{
			
			
			$per_page = 20;
			
			$params['limit'] = $per_page;
			
			
			if(!is_null($lang))
			{
				$params['where']['news.lang'] = $lang;
			}
			if(is_null($uri))
			{
				$params['where']['cat'] =  0;
				$category = array('title' => __("No category", "news"));
			}
			else
			{
				$cat = $this->news->get_cat(array('uri' => $uri, 'lang' => $lang));
				$params['where']['cat'] = $cat['id'];
				$category = $cat;
			}
			
			if( $rows = $this->news->get_list($params))
			{
			
				$data['encoding'] = 'utf-8';
				$data['feed_name'] = $category['title'];
				$data['feed_url'] = base_url();
				$data['page_description'] = $this->system->meta_description;
				$data['page_language'] = $this->user->lang;
				$data['creator_email'] = (isset($this->system->admin_email))? $this->system->admin_email : "";
				
				
				foreach ($rows as $key => $row)
				{
					$contents[$key]['title'] =  $row['title'];
					$contents[$key]['url'] = site_url($row['lang'] . '/news/' . $row['uri']);
					$contents[$key]['body'] = $row['body'];
					$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
					$contents[$key]['author'] = $row['author'];
					if (isset($row['image']['file']))
					{
						$contents[$key]['img'] = base_url(). 'media/images/s/' . $row['image']['file'];
					}
				}
				$data['contents'] = $contents;
				header("Content-Type: application/rss+xml");
				
				$this->load->view('rss', $data);

			}
			
		}
		
		
		function tag ($tag = null, $lang = null)
		{
			
			
			$per_page = 20;
			
			
			
			if(is_null($tag))
			{
				$this->template['title'] = __("Tag list", "news");
				$this->template['rows'] = $this->news->get_tags();
				header("Content-Type: application/rss+xml");
				$this->load->view('rss_tag_list', $this->template);
				return;
			}
			else
			{
				if(!is_null($lang))
				{
					$params['where']['news.lang'] = $lang;
				}
				$params['limit'] = $per_page;
				$params['where']['news_tags.uri'] = $tag;
				$params['order_by'] = 'news.id DESC';
				
				if( $rows = $this->news->get_news_by_tag($params))
				{
					$data['encoding'] = 'utf-8';
					$data['feed_name'] = $this->system->site_name . " - " . $tag;
					$data['feed_url'] = base_url();
					$data['page_description'] = $this->system->meta_description;
					$data['page_language'] = $this->user->lang;
					$data['creator_email'] = (isset($this->system->admin_email))? $this->system->admin_email : "";
					
					
					foreach ($rows as $key => $row)
					{
						$contents[$key]['title'] =  $row['title'];
						$contents[$key]['url'] = site_url($row['lang'] . '/news/' . $row['uri']);
						$contents[$key]['body'] = $row['body'];
						$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
						$contents[$key]['author'] = $row['author'];
						if (isset($row['image']['file']))
						{
							$contents[$key]['img'] = base_url(). 'media/images/s/' . $row['image']['file'];
						}
					}
					$data['contents'] = $contents;
					header("Content-Type: application/rss+xml");
					
					$this->load->view('rss', $data);

				}
			}
			
			
		}		
	}

