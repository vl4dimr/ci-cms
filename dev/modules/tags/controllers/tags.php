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

	
}