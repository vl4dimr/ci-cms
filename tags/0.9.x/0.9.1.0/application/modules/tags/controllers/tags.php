<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * $Id
 **/
 
 class Tags extends Controller {
 
 	function Tags()
	{
		parent::Controller();
	

		$this->template['module']	= 'tags';
		$this->settings = isset($this->system->tags_settings) ? unserialize($this->system->tags_settings) : null;

		$this->load->library('cache');
		$this->load->model('tags_model', 'tags');
		

	}
	

	function index($tag = null, $start = null)
	{
		$limit = 20;
		if(is_null($tag))
		{
			$tags = $this->tags->get_cloud();
			$this->template['tags'] = $tags;
			
			$this->layout->load($this->template, 'index');
		}
		else
		{
			$rows = $this->tags->get_tags(array('tag' => $tag), array('start' => $start, 'limit'=> $limit));
			$this->template['tag'] = $tag;
			$this->template['rows'] = $rows;
			$this->layout->load($this->template, 'tag');
			
		}
	}

	function rss($tag = null, $lang = null)
	{
		$limit = 10;
		if($lang= null) $lang = $this->user->lang;
		if(!is_null($tag))
		{
		
			if( $rows = $this->tags->get_tags(array('tag' => $tag, 'lang' => $lang)));
			$data['encoding'] = 'utf-8';
			$data['feed_name'] = __("Tag:", "tags") . " " .  strip_tags($tag) . " - " . $this->system->site_name ;
			$data['feed_url'] = base_url();
			$data['page_description'] = $this->system->meta_description;
			$data['page_language'] = $lang;
			$data['creator_email'] = (isset($this->system->admin_email))? $this->system->admin_email : "";
			
			
			foreach ($rows as $key => $row)
			{
				$contents[$key]['title'] =  $row['title'];
				$contents[$key]['url'] = site_url($row['url']);
				$contents[$key]['body'] = $row['summary'];
				$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
				$contents[$key]['author'] = (isset($row['author'])) ? $row['author'] : '';;
			}
			$data['contents'] = $contents;
			header("Content-Type: application/rss+xml");
			
			$this->load->view('rss', $data);
			
			
			
		}
	}
}