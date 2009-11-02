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
					$contents[$key]['title'] =  xml_convert($row['title']);
					$contents[$key]['url'] = site_url('news/' . $row['uri']);
					$contents[$key]['body'] = htmlentities($row['body']);
					$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
				}
				$data['contents'] = $contents;
				//$this->output->set_header("Content-Type: application/rss+xml");
				
				$output =  $this->load->view('rss', $data, true);
				echo $output;
				//$this->output->set_output($output);
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
				$cat = $this->news->get_cat(array('uri' => $uri));
				$params['where'] = array('news.lang' => $this->user->lang, 'cat' => $cat['id']);
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
					$contents[$key]['title'] =  xml_convert($row['title']);
					$contents[$key]['url'] = site_url('tononkalo/' . $row['uri']);
					$contents[$key]['body'] = htmlentities($row['vetso']);
					$contents[$key]['date'] = (isset($row['daty'])) ? $row['daty'] : '';
				}
				$data['contents'] = $contents;
				$this->output->set_header("Content-Type: application/rss+xml");
				
				$output =  $this->load->view('rss', $data, true);

				$this->output->set_output($output);

			}
			
		}
	}

